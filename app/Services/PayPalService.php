<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Taxable;
use App\Models\Customer;
use App\Models\Estimate;
use App\Libs\Countries; // Assuming you have this helper class
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

    // --- Final Order Saving Logic (createOrderTemp) ---

    /**
     * Saves the final order details to the database (Estimate and related tables).
     * Replaces the logic in CartController::createOrderTemp().
     * * @param string $paymentMethod The payment method used (e.g., 'Credit Card', 'PayPal').
     * @param array $requestData The full webhook/request data from PayPal or CC processor.
     * @param array $customer The customer array stored in Livewire state.
     * @return Estimate|null The created Estimate model instance.
     */
    public function saveFinalOrder(string $paymentMethod, array $requestData, array $customer): ?Estimate
    {
        $countries = new Countries; // Assuming this class exists

        $country = $countries->getCountryBySortname($customer['card-billing-address-country-code']);

        $totalWebprice = $this->cartService->calculateWebPrice();
        $discountAmount = $this->cartService->getDiscountAmount();

        // Ensure discount is applied to the final calculated subtotal
        $totalWebpriceBeforeTax = $totalWebprice - $discountAmount;

        $tax = 0;
        $total = $totalWebpriceBeforeTax;
        $company = $customer['b_company'] ?? ($customer['b_firstname'].' '.$customer['b_lastname']);

        // Final Tax/Total calculation from original logic
        if (isset($customer['b_state']) && $customer['b_state'] == 3956 && $country == 'United States') {
            $tax = Taxable::where('state_id', $customer['b_state'])->value('tax') ?? 0;
            $total = $totalWebpriceBeforeTax + ($totalWebpriceBeforeTax * ($tax / 100));
        }

        // Determine payment type from request data
        $payment = $requestData['payment_source']['card']['brand'] ?? ($requestData['payment_source']['paypal']['account_status'] ?? $paymentMethod);

        // Building order array (Replicated from original controller logic)
        $orderArray = [
            'b_firstname' => $customer['b_firstname'],
            'b_lastname' => $customer['b_lastname'],
            'b_company' => $company,
            'b_address1' => $customer['b_address1'],
            'b_address2' => $customer['b_address2'] ?? null,
            'b_phone' => $customer['b_phone'],
            'b_city' => $customer['b_city'],
            'b_state' => $customer['b_state'],
            'b_country' => $country,
            'b_zip' => $customer['b_zip'],
            's_firstname' => $customer['b_firstname'],
            's_lastname' => $customer['b_lastname'],
            's_company' => $company,
            's_address1' => $customer['b_address1'],
            's_address2' => $customer['b_address2'] ?? null,
            's_phone' => $customer['b_phone'],
            's_city' => $customer['b_city'],
            's_state' => $customer['b_state'],
            's_country' => $country,
            's_zip' => $customer['b_zip'],
            'payment_options' => $payment,
            'method' => 'Invoice',
            'transaction_id' => $requestData['id'] ?? null,
            'email' => $customer['email'],
            'discount' => $discountAmount,
            'status' => 0,
            'freight' => 0,
            'taxable' => $tax,
            'subtotal' => $totalWebpriceBeforeTax,
            'total' => $total,
        ];

        // Building customer array for updateOrCreate
        $new_customer = [
            'cgroup' => 0,
            'firstname' => $orderArray['b_firstname'],
            'lastname' => $orderArray['b_lastname'],
            'company' => $company,
            'address1' => $orderArray['b_address1'],
            'address2' => $orderArray['b_address2'],
            'phone' => $orderArray['b_phone'],
            'country' => $country,
            'state' => $customer['b_state'],
            'city' => $orderArray['b_city'],
            'zip' => $orderArray['b_zip'],
            'email' => $orderArray['email'] // Ensure email is passed for matching
        ];

        // 1. Save/Update Customer
        $customerModel = Customer::updateOrCreate(['email' => $customer['email']], $new_customer);

        // 2. Create Estimate (Order)
        $order = Estimate::create($orderArray);
        $order->customers()->attach($customerModel->id);

        // 3. Save Products to estimate_product table
        foreach (Cart::products() as $product) {
            DB::table('estimate_product')->insert([
                'estimate_id' => $order->id,
                'p_model' => $product['p_model'],
                'qty' => 1,
                'price' => $product['price'],
                'retail_price' => $product['price'],
                'product_name' => $product['model_name'].' ('.$product['p_model'] .')'
            ]);
        }

        Cart::clear(); // Clear the cart after order is saved
        session()->put('order', $order->id); // Store order ID in session for alldone view
        session()->forget('customer'); // Clear temporary customer session data

        return $order;
    }
}