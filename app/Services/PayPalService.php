<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Taxable;
use App\Models\Customer;
use App\Models\Estimate;
use App\Libs\Countries; // Assuming you have this helper class
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Exception;
use DB;

/**
 * Service dedicated to PayPal API interactions, order creation, capture, and final database saving.
 * Logic extracted from CartController::createOrder, ::generateAccessToken, ::Capture, etc.
 */
class PayPalService
{
    private $payPalURL = "https://api-m.paypal.com";
    protected CartService $cartService;

    // Inject the CartService for accessing calculations
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // --- Low-Level PayPal API Handlers (Extracted from CartController) ---

    private function generateAccessToken()
    {
        $PAYPAL_CLIENT_ID = config('paypal.live.client_id');
        $PAYPAL_CLIENT_SECRET = config('paypal.live.client_secret');

        if (!$PAYPAL_CLIENT_ID || !$PAYPAL_CLIENT_SECRET) {
            throw new Exception("MISSING_API_CREDENTIALS");
        }

        $auth = base64_encode($PAYPAL_CLIENT_ID . ":" . $PAYPAL_CLIENT_SECRET);
        $client = new Client(['verify' => false]);

        $response = $client->post($this->payPalURL."/v1/oauth2/token", [
            'form_params' => ['grant_type' => 'client_credentials'],
            'headers' => ['Authorization' => "Basic $auth"]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

        /**
    * Issue a Refund
    * * @param string $captureId  The Transaction ID (e.g. 3C679...)
    * @param float|null $amount Optional. If null, refunds FULL amount.
    * @return array
    */
    public function refund($order, $amount = null)
    {
        $accessToken = $this->generateAccessToken();

        $captureId = $order->transaction_id; //$captureId ?? $this->invoice->transaction_id;
        $url = $this->payPalURL . "/v2/payments/captures/{$captureId}/refund";

        // 1. Prepare Payload
        $payload = [
            'note_to_payer' => 'Refunding order per your request.'
        ];

        // If amount is provided, add it for Partial Refund
        $amount = $order->total;
        if ($amount !== null) {
            $payload['amount'] = [
                'value' => number_format($amount, 2, '.', ''),
                'currency_code' => 'USD'
            ];
        }

        $client = new Client(['verify' => false]);

        try {
            // 2. Send Request
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer $accessToken"
                ],
                'json' => $payload
            ]);

            // 3. Parse Success Response
            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'refund_id' => $data['id'],
                'status' => $data['status'], // usually 'COMPLETED'
                'total_refunded' => $data['seller_payable_breakdown']['total_refunded_amount']['value'] ?? '0.00'
            ];
        } catch (ClientException $e) {
            // 2. INTERCEPT THE ERROR
            // This block runs only if PayPal returns 4xx (like your 422)

            // Get the actual response body from PayPal (which contains the JSON)
            $responseBody = $e->getResponse()->getBody()->getContents();
            $errorJson = json_decode($responseBody, true);

            // 3. Extract the friendly message
            // PayPal usually puts the readable issue in 'details' or 'message'
            $errorMessage = "Refund Failed: ";

            if (isset($errorJson['details'][0]['issue'])) {
                // Example: "CAPTURE_FULLY_REFUNDED"
                $errorMessage .= $errorJson['details'][0]['description'];
            } elseif (isset($errorJson['message'])) {
                $errorMessage .= $errorJson['message'];
            } else {
                $errorMessage .= "Unknown error occurred.";
            }

            return [
                'success' => false,
                'error_name' => $errorJson['name'] ?? 'Unknown',
                'error_message' => $errorMessage,
                'details' => $errorJson['details'] ?? []
            ]; // Stop execution here

            // If using standard Controller:
            // return back()->with('error', $errorMessage);
        } catch (ClientException $e) {
            // 4. Handle Error Response
            $errorBody = json_decode($e->getResponse()->getBody(), true);

            return [
                'success' => false,
                'error_name' => $errorBody['name'] ?? 'Unknown',
                'error_message' => $errorBody['message'] ?? $e->getMessage(),
                'details' => $errorBody['details'] ?? []
            ];
        }
    }

    private function handleResponse($response)
    {
        $jsonResponse = json_decode($response->getBody(), true);
        return [
            'jsonResponse' => $jsonResponse,
            'httpStatusCode' => $response->getStatusCode()
        ];
    }

    /**
     * Calls PayPal API to create an order object.
     * Replaces the logic in CartController::createOrder().
     * @param array $customer The customer data array from Livewire state.
     */
    public function createOrder(array $customer)
    {
        $accessToken = $this->generateAccessToken();
        $client = new Client(['verify' => false]);
        $webprice = $this->cartService->calculateWebPrice();
        $items = [];

        foreach (Cart::products() as $product) {
            $items[] = [
                'name' => $product['model_name'],
                'quantity' => 1,
                'sku' => $product['p_model']
            ];
        }

        $total = $webprice;

        // Apply tax logic from original controller
        if (isset($customer['b_state']) && $customer['b_state'] == 3956 && ($customer['card-billing-address-country-code'] ?? '') == 'US') {
            $taxRate = Taxable::where('state_id', $customer['b_state'])->value('tax') ?? 0;
            $total = $webprice + ($webprice * ($taxRate / 100));
        }

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($total, 2, '.', '')
                    ],
                ]
            ],
            // Items are not necessary for API, but included for context.
        ];

        $response = $client->post($this->payPalURL."/v2/checkout/orders", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $accessToken"
            ],
            'json' => $payload
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Calls PayPal API to capture the payment.
     * Replaces the CartController::captureOrder() logic.
     */
    public function capturePayment(string $orderID)
    {
        $accessToken = $this->generateAccessToken();
        $client = new Client(['verify' => false]);

        $response = $client->post($this->payPalURL."/v2/checkout/orders/$orderID/capture", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $accessToken"
            ]
        ]);

        return $this->handleResponse($response);
    }

       // --- Invoicing Methods ---

    /**
     * High-level method to create a draft and send it immediately to the customer.
     * * @param array $customerData ['firstname', 'lastname', 'email']
     * @param array $items Array of items with name, quantity, and unit_amount
     * @param string $note Custom note on the invoice
     */
    public function createAndSendInvoice(array $customerData, array $items, string $note = "Thank you!")
    {
        // 1. Try to find the ID directly
        $invoiceId = $customerData['invoice_id'] ?? null;
        dd($invoiceId);
        $draftResult = $this->createInvoiceDraft($customerData, $items, $note);
        $json = $draftResult['jsonResponse'] ?? [];

        // 2. Fallback: Extract from 'href' (Handles the structure you provided in debug)
        if (!$invoiceId && isset($json['href'])) {
            $invoiceId = basename($json['href']);
        }

        dd($invoiceId);
        // 3. If we found an ID, move from Draft to SENT
        if ($invoiceId) {
            return $this->sendInvoice($invoiceId);
        }

        // If we reach here, Step 2 was never triggered.
        // We return the draft result so you can debug why the ID extraction failed.
        return $draftResult;
    }

    /**
     * Step 1: Create an invoice in DRAFT status.
     */
    public function createInvoiceDraft(array $customerData, array $items, string $note)
    {
        $accessToken = $this->generateAccessToken();
        $client = new Client(['verify' => false]);

        $payload = [
            "detail" => [
                "invoice_number" => $invoiceId ?? "INV-" . time(), // Unique invoice number
                "currency_code" => "USD",
                "note" => $note,
                "term" => "Due on receipt",
                // Provide your logo URL here (must be HTTPS)
                "logo_url" => "https://berdvaye.com/assets/berdvaye-black-logo.png"
            ],
            "invoicer" => [
                "name" => [
                    "given_name" => "Berd Vaye Inc."
                ],
                "email_address" => "info@berdvaye.com",
                // Address is omitted here so it won't show on the invoice
            ],
            "primary_recipients" => [
                [
                    "billing_info" => [
                        "name" => [
                            "given_name" => $customerData['firstname'],
                            "surname" => $customerData['lastname']
                        ],
                        "email_address" => $customerData['email']
                    ]
                ]
            ],
            "items" => $items
        ];

        $response = $client->post($this->payPalURL . "/v2/invoicing/invoices", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $accessToken"
            ],
            'json' => $payload
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Step 2: Send the draft invoice to the customer via email.
     */
    public function sendInvoice(string $invoiceId)
    {
        $accessToken = $this->generateAccessToken();
        $client = new Client(['verify' => false]);

        $response = $client->post($this->payPalURL . "/v2/invoicing/invoices/{$invoiceId}/send", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $accessToken"
            ],
            'json' => [
                "send_to_recipient" => true
            ]
        ]);

        return $this->handleResponse($response);
    }
}