<?php

namespace App\Http\Controllers;

use Session;
use App\Models\ProductRetail;
use Illuminate\Support\Facades\Http;
use App\Mail\GMailer;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Estimate;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;
use App\Models\Taxable;
use App\Models\Product;
use App\Models\Customer;
use App\Models\DiscountRule;
use App\Models\Cart;
use GuzzleHttp\Client;
//use App\Models\Mail\EmailConfirm;
//use Illuminate\Support\Facades\Mail;
use Omnipay\Common\Helper;

class CartController extends Controller
{
    private $domestic = 45;
    private $international = 115;
    private static $cartProducts;
    private $payPalURL = "https://api-m.paypal.com";

    private function discount($promocode,$orginal_amount,$action) {
            $webprice=0;
            foreach (Cart::products() as $products) {
                $webprice+=$products['price'];
            }

            if ($action==0)
                $amount = $webprice-$orginal_amount;
            elseif ($action==1)
                $amount = $webprice*($orginal_amount/100);
            elseif ($action==2) {
                // Todo enumerate all items in the cart and apply discount
            } else {
                // Todo enumerate all items in the cart and apply discount
            }

            $discount = [
                'original_amount' => $orginal_amount,
                'action' => $action,
                'amount'=>$amount,
                'promocode'=>$promocode,
                'newprice' => $webprice,
            ];

            session()->put('discount', $discount);

            $discountAmt = number_format($amount,2);

            return $discountAmt;
    }

    public function promo(Request $request) {
        if ($request->ajax()) {
            $discountRule = DiscountRule::where('discount_code',$request['promocode'])->first();

            if ($discountRule) {
                if ($discountRule->is_active) {
                    $discountAmt=$this->discount($request['promocode'],$discountRule->amount,$discountRule->action);
                    return array('error'=>0,'amount'=>$discountRule->amount,'content'=>"A discount has been applied.");
                } else return array('error'=>1,'content'=>"This promo code has expired.");
            } else {
                return array('error'=>1,'content'=>"That wasn't a correct promo code.");
            }
        }
    }

    function handleResponse($response) {
        $jsonResponse = json_decode($response->getBody(), true);
        return [
            'jsonResponse' => $jsonResponse,
            'httpStatusCode' => $response->getStatusCode()
        ];
    }

    public function Capture($orderID) {
        // $urlSegments = explode('/', $endpoint);
        // end($urlSegments); // Will set the pointer to the end of array
        // $orderID = prev($urlSegments);
        // \Log::debug($orderID);

        header('Content-Type: application/json');
        try {
            $captureResponse = $this->captureOrder($orderID);
            echo json_encode($captureResponse['jsonResponse']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            http_response_code(500);
        }
    }

    /**
     * Capture payment for the created order to complete the transaction.
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_capture
     */
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

        return $this->handleResponse($response);
    }

    private function createOrder($res,$customer) {
        $accessToken = $this->generateAccessToken();

        // Disabling certificate validation for local development
        $client = new Client(['verify' => false]);
        $webprice = 0;
        \Log::debug(Cart::products());
        foreach (Cart::products() as $products) {
            $webprice+=$products['price'];
            $items[] = ['name' => $products['model_name'],'quantity' => 1, 'sku' => $products['p_model']];
        }

        $tax = 0;$total = 0;
        if ($customer['b_state'] == 3956 && $customer['card-billing-address-country-code'] == 'US') {
            $tax = Taxable::where('state_id',$customer['b_state'])->value('tax');
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

    public function order(Request $request) { // payment/order

        foreach (Cart::products() as $product) {
            $cart[] = ['id' => $product['id'],'quantity' => '1'];
        }

        $customer = $request->all();
        session()->put('customer',$customer);
        \Log::debug(Cart::products());

        header('Content-Type: application/json');
        try {
            $orderResponse = $this->createOrder($cart,$customer);
            echo json_encode($orderResponse['jsonResponse']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            http_response_code(500);
        }

    }

    /**
     * Generate an OAuth 2.0 access token for authenticating with PayPal REST APIs.
     * @see https://developer.paypal.com/api/rest/authentication/
     */
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

    public function alldone(Request $request) {
        $order_id=session()->get('order');
        $products = Cart::products();
        $order = Estimate::find($order_id);

        // $order = Estimate::find(285);
        Cart::clear();
        return view('payment/alldone',['products'=>$products,'order'=>$order,'activePage'=>'cart']);

    }

    public function getTax() {
        $subtotal = 0;
        $tax = Taxable::where('state_id',3956)->value('tax');

        foreach (Cart::products() as $product) {
            $subtotal += $product['retail'];
        }

        $total = $subtotal + ($subtotal * ($tax/100));

        return [$tax,'$'.number_format($total,2)];
    }

    public function Thankyou(Request $request) {
        // \Log::debug($request['payment_source']['card']['brand']);
        // return;
        $customer= session()->get('customer');
        $products = Cart::products();

        if ($customer['card-billing-address-country-code'] != 'US') {
            $order = $this->createOrderTemp('Due upon receipt',$request);
            $printOrder = new \App\Libs\PrintOrder(); // Create Print Object
            $printOrder->print($order,'email','cart'); // Print newly create proforma.

            session()->forget('customer');
        } else {
            $order = $this->createOrderTemp('Credit Card',$request);
            if ($order) {
                $printOrder = new \App\Libs\PrintOrder(); // Create Print Object
                $printOrder->print($order,'email'); // Print newly create proforma.
                session()->forget('customer');
            }
        }
    }

    public function Unsuccessful() {
        return view('payment/unsuccessful',['activePage' =>'cart']);
    }

    public function addToCart(Request $request) {
        // $productRetail = ProductRetail::where('p_model',$request['model'])->first();

        $model = $request['model'];
        $product = Product::whereHas('retail', function($query) use($model) {
            $query->where('p_model',$model);
        })
            ->where('p_status',0)
            ->where('p_qty','>',0)
            ->first();

        if ($product) {
            // dd($product);
            // $product_name = //$product->title();
            $productRetail = ProductRetail::where('p_model',$request['model'])->first();

            $cartProducts= array(
                'id' => $product->id,
                'retail' => $productRetail->p_retail,
                'serial' => $product->p_serial,
                'price' => $productRetail->p_retail,
                'condition' => 'New',
                'size' => $productRetail->size,
                'qty' => $product->p_qty,
                'percent' => 0,
                'p_model' => $product->p_model,
                'model_name' => $productRetail->model_name,
                'image' => "/images/gallery/thumbnail/".strtolower($productRetail->p_model) .'.jpg'
            );

            if (Cart::products()) {
                $cart = Cart::insert($cartProducts);
                if (session()->has('discount')) {
                    $promoCode = session()->get('discount');
                    $this->discount($promoCode['promocode'],$promoCode['original_amount'],$promoCode['action']);
                }
            } else
                $cart = Cart::add($cartProducts);

            return ['error' => '', 'description' => ''];
        } else return ['error' => 'qty', 'description' => ' is out of stock.','product_name' => $request['model']];

    }

    public function cart(Request $request) {
        $ret = ['error' => '', 'description' => ''];

        $products = ProductRetail::select('model_name', 'p_model', 'image_location')
            ->where('is_active', 1)
            ->groupBy('model_name') // Group by model_name and p_model for additional filtering
            ->get();

        if ($request['id']) {
            $ret = $this->addToCart($request);
            if ($ret['error'] == 'qty'){
                return view('cart',['products'=>Cart::products(),'discount' => $this->getDiscountAmount(),'activePage' =>'cart','ret'=>$ret,'r_products' => $products]);
            }
        }

        return view('cart',['products'=>Cart::products(),'discount' => $this->getDiscountAmount(),'activePage' =>'cart','ret'=>$ret,'r_products' => $products]);
    }

    public function remove(Request $request) {
        $product_id = $request['id'];
        $cart = Cart::Remove($product_id);

        if (session()->has('discount')) {
            $promoCode = session()->get('discount');
            $this->discount($promoCode['promocode'],$promoCode['original_amount'],$promoCode['action']);
            if ($cart == 0)
                session()->forget('discount');
        }

        return $cart;
    }

    public function checkoutpayment(Request $request) {
        session()->put('customer',$request->all());

        //session()->put('order_id',3125);
        $items='';
        foreach (Cart::products() as $product) {
            $items .= $product['model_name'] . ' (' . $product['id'] . ")<br><br>" ;
        }

        $data = array(
            'to' => 'info@berdvaye.com',
            'fullname' =>$request['b_firstname'].' '.$request['b_lastname'],
            'purchasedFrom' => 1,
            'phone' => $request['b_phone'],
            'customer_email' =>$request['email'],
            'template' => 'emails.creditcard',
            'subject' => 'Credit Card',
            'from_name' => 'BerdVaye Credit Card Attempt',
            'item' => $items,
        );

        // $gmail = new GMailer($data);
        // $gmail->send();

        $r['tax'] = 0;
        if ($request['b_state'] == 3956 && $request['b_country'] == '231') {
            $tax = Taxable::where('state_id',$request['b_state'])->value('tax');
            $r['tax'] = $tax;
        }

        if ($request['b_country'] == '231')
            $r['freight'] = $this->domestic;
        else $r['freight'] = $this->international;

        $countries = new \App\Libs\Countries;
        $country_b = $countries->getCountry($request['b_country']);
        $request['b_country']=$country_b;
        $activePage = 'cart';

        if (Cart::products())
            return view("checkoutpayment",['products' => Cart::products(), 'discount' => $this->getDiscountAmount(), 'tax'=>$r['tax'],'freight'=>$r['freight'],'customer'=>$request->all(),'activePage' =>'cart']);
        else return redirect('cart')->with('products', null,'activePage');
    }

    private function getDiscountAmount() {
        $discount = 0;

        if (session()->has('discount')) {
            $discount = session()->get('discount');
            $discount = $discount['original_amount'];
        }

        return $discount;
    }

    public function checkout(Request $request) {
        return view('checkout',['products'=>Cart::products(),'discount' => $this->getDiscountAmount(),'activePage' =>'cart']);
    }

    private function createOrderTemp($method,$request) {
        // \Log::debug(print_r(Cart::products(),true));
        $countries = new \App\Libs\Countries;

        $customer= session()->get('customer');
        $country = $countries->getCountryBySortname($customer['card-billing-address-country-code']);

        $tax = 0;$subtotal = 0;$total = 0;$orderstatus = 0;$totalWebprice=0;

        if (session()->has('discount'))
            $discount= session()->get('discount');
        else {
            $discount['amount']=0;
            $discount['promocode']='';
        }

        if ($customer['b_company'])
            $company = $customer['b_company'];
        else $company = $customer['b_firstname'].' '.$customer['b_lastname'];

        $account_status = '';

        if (empty($request['payment_source']['card'])) {
            $payment = "PayPal";
            $account_status = $request['payment_source']['paypal']['account_status'];
        } else $payment = $request['payment_source']['card']['brand'];

        // if (!$account_status != 'VERIFIED') return '';

        $orderArray = [
            'b_firstname' => $customer['b_firstname'],
            'b_lastname' => $customer['b_lastname'],
            'b_company' => $company,
            'b_address1' => $customer['b_address1'],
            'b_address2' => !empty($customer['b_address2']) ? $customer['b_address2'] : "",
            'b_phone' => $customer['b_phone'],
            'b_city' => $customer['b_city'],
            'b_state' => $customer['b_state'],
            'b_country' => $country,
            'b_zip' => $customer['b_zip'],
            's_firstname' => $customer['b_firstname'],
            's_lastname' => $customer['b_lastname'],
            's_company' => $company,
            's_address1' => $customer['b_address1'],
            's_address2' => !empty($customer['b_address2']) ? $customer['b_address2'] : "",
            's_phone' => $customer['b_phone'],
            's_city' => $customer['b_city'],
            's_state' => $customer['b_state'],
            's_country' => $country,
            's_zip' => $customer['b_zip'],
            'payment_options' => $payment,
            'method' => 'Invoice',
            'transaction_id' => $request['id'],
            'email' => $customer['email'],
            'discount' => $discount['amount'],
            'status' => 0
        ];

        $new_customer = array(
            'cgroup' => 0,
            'firstname' => $orderArray['b_firstname'],
            'lastname' => $orderArray['b_lastname'],
            'company' => $company,
            'address1' => $orderArray['b_address1'],
            'address2' => !empty($customer['b_address2']) ? $customer['b_address2'] : "",
            'phone' => $orderArray['b_phone'],
            'country' => $country,
            'state' => $customer['b_state'],
            'city' => $orderArray['b_city'],
            'zip' => $orderArray['b_zip']
        );

        $customer = Customer::updateOrCreate(['email'=>$customer['email']],$new_customer);

        // \Log::debug($new_customer['state'] . ' ' . $country );
        // die;
        $order = Estimate::create($orderArray);
        $order->customers()->attach($customer->id);

        session()->put('order',$order->id);


        foreach (Cart::products() as $product) {
            $totalWebprice += $product['price'];

            $product=[
                'estimate_id' => $order->id,
                'p_model' => $product['p_model'],
                'qty' => 1,
                'price' => $product['price'],
                'retail_price' => $product['price'],
                'product_name' => $product['model_name']
            ];

            \DB::table('estimate_product')->insert($product);
        }

        // $totalWebprice -= $discount['amount'];

        if ($new_customer['state'] == 3956 && $country == 'United States') {
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
}
