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

}