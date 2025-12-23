<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cookie;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use App\Models\DiscountRule;
use App\Models\Taxable;
use GuzzleHttp\Client;
use App\Services\CartService;
use Livewire\Attributes\Computed;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Country;
use App\Models\State;
use Carbon\Carbon;

class CartComponent extends Component
{
    public bool $quanityChanged = false;
    public bool $showFormInputs = false;
    public int $countCart = 0;
    public int $productStatus = 2;
    public $selectedBCountry;
    public $selectedBState;
    public $customer = [];
    public $grandtotal = '$0.00';
    public $totalPrice = 0;
    public $additionalFee = 0;
    public $totalWithTax = 0;
    public $paypalInfo = [];
    public $processing = false;
    public $cartProducts = null;
    public $productQtyErrorMessage = '';

    private $payPalURL = "https://api-m.paypal.com";

    protected function rules() {
        return [
            'customer.email' => ['required'],
            'customer.firstname' => ['required'],
            'customer.lastname' => ['required'],
            'customer.phone' => ['required'],
            'selectedBCountry' => ['required','not_in:0'],
            'customer.city' => ['required'],
            'customer.address1' => ['required'],
            'customer.zip' => ['required'],
            'selectedBState' => ['required','not_in:0'],
        ];
    }

    protected $messages = [
        'customer.email.required' =>'This field is required.',
        'customer.firstname.required' =>'This field is required.',
        'customer.lastname.required' =>'This field is required.',
        'customer.phone.required' =>'This field is required.',
        'selectedBCountry.not_in' =>'This field is required.',
        'selectedBCountry.required' =>'This field is required.',
        'customer.city.required' =>'This field is required.',
        'customer.address1.required' =>'This field is required.',
        'customer.zip.required' =>'This field is required.',
        'selectedBState.not_in' =>'This field is required.',
        'selectedBState.required' =>'This field is required.',
    ];

    private function discountRule() {
        $now = (date('Y-m-d',strtotime(now())));
        $discountRule = DiscountRule::whereIn('action',[4,5])
            ->where('start_date','<=',$now)
            ->where('end_date','>=',$now)
            ->where('is_active', '1')
            ->first();

        return $discountRule;
    }

    #[Computed]
    public function countries() {
        return Country::All();
    }

    #[Computed]
    public function billingStates() {
        return State::where('country_id',$this->selectedBCountry)->get();
    }

    #[On('initiate-paypal-order')]
    public function PayPalOrder() {
        // Logic to create PayPal order goes here
    }

    #[On('clear-form')]
    public function clearForm() {
        $this->resetValidation();
        $this->reset('customer.email','customer.firstname','customer.lastname','customer.phone','customer.address1','customer.city','customer.state','customer.zip');
        $this->productQtyErrorMessage = '';
    }

    public function updated($propertyName) {
        if ($propertyName == 'selectedBCountry') {
            $this->selectedBState = null;
        } elseif ($propertyName == 'selectedBState') {
            $this->selectedBState = $this->selectedBState;
            $this->calculateTotalPrice();
        }
    }

    public function startPaymentProcess() {
        $this->processing = true;
    }

    public function validateFields() {
        $validatedData = $this->validate();
    }

    public function order() {

        $this->validateFields();
        $cart = [];
        foreach (Cart::products() as $product) {
            $cart[] = [
                'id' => $product['id'],
                'quantity' => 1, // or $product['quantity'] if dynamic
            ];
        }

        try {
            // Call your existing createOrder() method
            $orderResponse = $this->createOrder($cart, $this->customer);

            // Return the JSON response as an array (Livewire will serialize it)
            return $orderResponse['jsonResponse'] ?? [];
        } catch (\Exception $e) {
            // Return an error array
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    private function createOrder($res,$customer) {
        $accessToken = $this->generateAccessToken();

        // Disabling certificate validation for local development
        $client = new Client(['verify' => false]);
        $webprice = 0;
        // \Log::debug(Cart::products());
        foreach (Cart::products() as $products) {
            $webprice+=$products['price']*$products['qty'];
            $items[] = ['name' => $products['model_name'],'quantity' => $products['qty'], 'sku' => $products['p_model']];
        }

        $customer = $this->customer;

        $countries = new \App\Libs\Countries;
        $country_b = $countries->getSortnameFromCountryId($this->selectedBCountry);
        $state_b = $countries->getStateByCode($this->selectedBState);

        $this->customer['card-billing-address-country-code'] = $country_b;

        $tax = 0;$total = 0;
        if ($this->selectedBState == 3956 && $customer['card-billing-address-country-code'] == 'US') {
            $tax = Taxable::where('state_id',$this->selectedBState)->value('tax');
            $total = $webprice + ($webprice * ($tax/100));
        } else
            $total = $webprice;

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($total,2, '.', '')
                    ]
                ]
            ],
            'items' => $items
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

    function handleResponse($response) {
        $jsonResponse = json_decode($response->getBody(), true);
        return [
            'jsonResponse' => $jsonResponse,
            'httpStatusCode' => $response->getStatusCode()
        ];
    }

    public function thankyou(Request $request) {
        $products = Cart::products();

        if ($this->customer['card-billing-address-country-code'] != 'US') {
            $order = $this->processOrder('Due upon receipt',$request);
            $printOrder = new \App\Libs\PrintOrder(); // Create Print Object
            $printOrder->print($order,'email','cart'); // Print newly create proforma.
        } else {
            $order = $this->processOrder('Credit Card',$request);
            if ($order) {
                $printOrder = new \App\Libs\PrintOrder(); // Create Print Object
                $printOrder->print($order,'email'); // Print newly create proforma.
            }
        }

        $this->processing = false;
        return redirect()->route('all.done');
    }

    public function extractPaypalOrderData($data) {
        $result = [];

        $this->customer['account_status'] = $data['payment_source']['paypal']['account_status'] ?? null;
        if (!empty($data['payment_source']) && is_array($data['payment_source'])) {
            $this->customer['payment_source'] = array_key_first($data['payment_source']);
        }

        $this->customer['country'] = $this->selectedBCountry;
        $this->customer['state'] = $this->selectedBState;

        if ($this->customer['payment_source'] == "paypal") {
            $this->customer['email'] = $data['payer']['email_address'] ?? null;
            $this->customer['firstname'] = $data['payer']['name']['given_name'] ?? '';
            $this->customer['lastname'] = $data['payer']['name']['surname'] ?? '';

            // Shipping address
            $address = $data['purchase_units'][0]['shipping']['address'] ?? [];

            $this->customer['address1'] = $address['address_line_1'] ?? '';
            $this->customer['address2'] = $address['address_line_2'] ?? '';

            $this->customer['city'] = $address['admin_area_2'] ?? '';
            $this->customer['state'] = $address['admin_area_1'] ?? '';
            $this->customer['zip'] = $address['postal_code'] ?? '';
            $this->customer['country'] = $address['country_code'] ?? '';

            // Gross amount
            $this->customer['gross_amount'] = $data['purchase_units'][0]['payments']['captures'][0]
                ['seller_receivable_breakdown']['gross_amount']['value'] ?? null;

            $this->customer['currency'] = $data['purchase_units'][0]['payments']['captures'][0]
                ['seller_receivable_breakdown']['gross_amount']['currency_code'] ?? null;
        }
    }

    private function processOrder($method, $data) {
        // \Log::debug(print_r(Cart::products(),true));
        $countries = new \App\Libs\Countries;

        $customer= $this->customer;

        $country = $countries->getCountryIdBySortname($customer['country']);
        $state = $countries->getStateByCode($customer['state']);

        $tax = 0;$subtotal = 0;$total = 0;$orderstatus = 0;$totalWebprice=0;

        if (session()->has('discount'))
            $discount= session()->get('discount');
        else {
            $discount['amount']=0;
            $discount['promocode']='';
        }

        $company = $customer['firstname'].' '.$customer['lastname'];

        $account_status = '';

        if (empty($customer['payment_source'])) {
            $payment = "PayPal";
            $account_status = $customer['account_status'];
        } else $payment = $customer['payment_source'];

        // if (!$account_status != 'VERIFIED') return '';

        $orderArray = [
            'b_firstname' => $customer['firstname'],
            'b_lastname' => $customer['lastname'],
            'b_company' => $company,
            'b_address1' => $customer['address1'],
            'b_address2' => !empty($customer['address2']) ? $customer['address2'] : "",
            'b_phone' => $customer['phone'],
            'b_city' => $customer['city'],
            'b_state' => $state,
            'b_country' => $country,
            'b_zip' => $customer['zip'],
            's_firstname' => $customer['firstname'],
            's_lastname' => $customer['lastname'],
            's_company' => $company,
            's_address1' => $customer['address1'],
            's_address2' => !empty($customer['address2']) ? $customer['address2'] : "",
            's_phone' => $customer['phone'],
            's_city' => $customer['city'],
            's_state' => $state,
            's_country' => $country,
            's_zip' => $customer['zip'],
            'payment_options' => $payment,
            'method' => 'Invoice',
            // 'transaction_id' => $request['id'],
            'email' => $customer['email'],
            'discount' => $discount['amount'],
            'status' => 0
        ];

        $new_customer = array(
            'cgroup' => 0,
            'firstname' => $customer['firstname'],
            'lastname' => $customer['lastname'],
            'company' => $company,
            'address1' => $customer['address1'],
            'address2' => !empty($customer['address2']) ? $customer['address2'] : "",
            'phone' => $customer['phone'],
            'country' => $country,
            'state' => $state,
            'city' => $customer['city'],
            'zip' => $customer['zip']
        );

        $customer = Customer::updateOrCreate(['email'=>$customer['email']],$new_customer);

        // \Log::debug($new_customer['state'] . ' ' . $country );
        // die;
        $order = Estimate::create($orderArray);
        $order->customers()->attach($customer->id);

        session()->put('order',$order->id);

        foreach (Cart::products() as $product) {
            $totalWebprice += $product['price'] * $product['qty'];

            $product=[
                'estimate_id' => $order->id,
                'p_model' => $product['p_model'],
                'qty' => $product['qty'],
                'price' => $product['price'],
                'retail_price' => $product['price'],
                'product_name' => $product['model_name']
            ];

            \DB::table('estimate_product')->insert($product);

        }

        // $totalWebprice -= $discount['amount'];

        if ($state == 3956 && $country == 231) {
            $tax = Taxable::where('state_id',$new_customer['state'])->value('tax');
            $total = $totalWebprice + ($totalWebprice * ($tax/100));
        } else
            $total = $totalWebprice;

        $order->update([
            'freight' => 0,
            'taxable' => $tax,
            'subtotal' => $totalWebprice,
            'total' => $total,
        ]);

        return $order;
    }

    public function finalizeOrder() {
        // $order_id=session()->get('order');
        $products = Cart::products();
        $order = Estimate::find($order_id);

        // $order = Estimate::find(285);
        Cart::clear();
        return redirect()->route('all.done'); // Named route
        // return view('payment/finalizeOrder',['products'=>$products,'order'=>$order,'activePage'=>'cart']);

    }

    public function capture($orderID) {
        try {

            // Call your existing createOrder() method
            $orderResponse = $this->captureOrder($orderID);

            // Return the JSON response as an array (Livewire will serialize it)
            return $orderResponse['jsonResponse'] ?? [];
        } catch (\Exception $e) {
            // Return an error array
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    private function captureOrder($orderID) {
        $accessToken = $this->generateAccessToken();

        // Disabling certificate validation for local development
        $client = new Client(['verify' => false]);
        $response = $client->post($this->payPalURL."/v2/checkout/orders/$orderID/capture", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $accessToken"
            ]
        ]);

        $this->extractPaypalOrderData(json_decode($response->getBody()->getContents(),true));
        return $this->handleResponse($response);
    }

    private function generateAccessToken() {
        $PAYPAL_CLIENT_ID = config('paypal.live.client_id');
        $PAYPAL_CLIENT_SECRET = config('paypal.live.client_secret');

        if (!$PAYPAL_CLIENT_ID || !$PAYPAL_CLIENT_SECRET) {
            throw new Exception("MISSING_API_CREDENTIALS");
        }

        $auth = base64_encode($PAYPAL_CLIENT_ID . ":" . $PAYPAL_CLIENT_SECRET);

        // Disabling certificate validation for local development
        $client = new Client(['verify' => false]);
        $response = $client->post($this->payPalURL."/v1/oauth2/token", [
            'form_params' => [
                'grant_type' => 'client_credentials'
            ],
            'headers' => [
                'Authorization' => "Basic $auth"
            ]
        ]);


        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

    #[On('add-to-cart')]
    public function addToCart($model, CartService $cartService) {


        $result = $cartService->addItemToCart($model);

        if (is_array($result) && isset($result['error'])) {
            $this->productQtyErrorMessage = $result['description'];
            return;
        }

        $this->productQtyErrorMessage = '';

        $this->countCart = Cart::count();
        $this->calculateTotalPrice();

        if (!$this->quanityChanged) {
            $this->dispatch('dispatched-message',['msg' =>'addtocart', 'id'=>$model]); // despatch message to this blade component.
        }

        $this->dispatch('refresh-cart-count');

    }

    public function removeItemFromCart($productId, CartService $cartService) {
        $cartService->removeItemFromCart($productId);

        $product = Product::find($productId);
        $cart = Cart::Remove($productId);

        if (Cookie::has('cookie_cart')) {
            Cookie::queue(Cookie::forget('cookie_cart'));
        }

        if (session()->has('discount')) {
            $promoCode = session()->get('discount');
            $this->discount($promoCode['promocode'],$promoCode['original_amount'],$promoCode['action']);
            if ($cart == 0)
                session()->forget('discount');
        }

        $this->countCart = Cart::count();

        if ($this->countCart == 0)
            session()->forget('customer');

        if (!$this->quanityChanged) {
            $this->dispatch('dispatched-message',['msg' =>'deleteproduct', 'id'=>$product->id]); // despatch message to this blade component.
        }

        $this->dispatch('refresh-cart-count');
        $this->calculateTotalPrice();
    }

    #[On('refresh-cart')]
    protected function calculateTotalPrice($calc = 1) {
        if (!Cart::products()) {
            $this->grandtotal = '$0.00';
            $this->totalPrice = 0;
            return;
        }

        $tax = 0;
        $discount = 0;

        if (isset($this->customer['freight']) && $this->customer['freight'])
            $freight = $this->customer['freight'];

        if ($this->selectedBState == 3956) {
            $tax = Taxable::where('state_id',$this->selectedBState)->value('tax');
            $this->customer['tax'] = $tax."%";
        } else $this->customer['tax'] = "0%";

        if (isset($this->customer['discount']) && $this->customer['discount']) {
            $discount = $this->customer['discount'];
            // $this->totalPrice -= $discount;
        }

        $this->totalPrice = 0;
        foreach (Cart::products() as $product) {
            $this->totalPrice += $product['price'] * $product['qty'];
        }

        $total = ($this->totalPrice -$discount );
        $taxamount = $total * ($tax/100);
        $this->customer['taxamount'] = floor($taxamount * 100) / 100;

        // $this->customer['taxamount'] += $total;

        $this->grandtotal = '$'.number_format($this->customer['taxamount']+$total,2);

    }

    public function mount() {
        $this->countCart = Cart::count();

        $states = $this->billingStates;

        $this->customer['tax'] = "0%";
        $this->selectedBCountry = 231;

        $this->customer['card-billing-address-country-code'] = "US";
        $this->calculateTotalPrice();
    }

    public function setNewQty($model, $productId, $action='add' ) {
        $this->quanityChanged = true;
        if ($action == "add") {
            $this->addToCart($model, app(CartService::class));
            $this->quanityChanged = false;
        } else {
            $this->productQtyErrorMessage = '';
            if ($this->countCart-1 == 0) {
                $this->quanityChanged = false;
                $this->removeItemFromCart($productId,app(CartService::class));
            } else {
                $product = Cart::find($productId);

                Cart::UpdateItem($productId,'qty',$product['qty']-1);
                $this->countCart = Cart::count();
                $this->calculateTotalPrice();
                $this->quanityChanged = false;

                $this->dispatch('refresh-cart-count');
            }
        }

        // \Log::debug(Cart::count());

    }

    public function previousStep() {
        $this->showFormInputs = false;
        $this->dispatch('go-to-checkout-step-one');
    }

    public function goToNextStep() {
        $this->showFormInputs = true;
        $this->dispatch('go-to-checkout-step-two');
    }

    public function render()
    {

        return view('livewire.cart',['cartproducts' => Cart::products()]);
    }
}
