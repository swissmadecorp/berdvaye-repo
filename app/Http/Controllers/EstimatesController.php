<?php

namespace App\Http\Controllers;

use App\Models\Events\CreatedOrderEvent;
use App\Models\Notifications\OrderCreated;
use App\Models\Notifications\OrderUpdated;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use PDF;
use App\Models\Estimate;
use App\Models\EstimateProduct;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Taxable;
use App\Models\Payment;
use Session;
use Input;

class EstimatesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estimates = Estimate::latest()->get();
        
        return view('admin.estimates',['pagename' => 'Order','estimates' => $estimates]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.estimates.create',['pagename' => 'New Order']);
    }

    public function createFromEstimate($id)
    {
        $estimate = Estimate::find($id);
        return view('admin.estimates.createfromestimate',['pagename' => "Invoice for order # $id",'estimate'=>$estimate]);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function storeInvoiceFromOrder(Request $request)
     {
        
        //dd('storeInvoiceFromOrder');
         $validator = \Validator::make($request->all(), [
             'b_company' => "required",
         ]);
 
         if ($validator->fails()) {
             return back()
                 ->withInputs($request->all())
                 ->withErrors($validator);
         }
 
         $keys = array_keys($request['qty']);
         $id= $request['order_id'];
         
         foreach ($keys as $key) {
            $product_id = $key;
            $qty = $request['qty'][$key];
            $price = $request['price'][$key];
            $serial = $request['serial'][$key];
            $model = $request['model'][$key];
            
            if ($serial) {
                $product = Product::where('p_serial',$serial)
                    ->where('p_model',$model)
                    ->first();
                
                if (!$product) {
                    return redirect("admin/estimates/$id/invoice/create")
                        ->withInput($request->all())
                        ->withErrors(array('message' => "Serial number $serial  with model number $model does not match the inventory."));
                } elseif ($product->p_qty < 1) {
                    return redirect("admin/estimates/$id/invoice/create")
                        ->withInput($request->all())
                        ->withErrors(array('message' => "Item with the serial number $serial with model number $model is out of stock."));
                }
            }
        }

         $status = 1; // 1 = paid 2 = unpaid
         if ($request['payment_options'] != 'None') {
             $status = 0;
         } elseif ($request['payment'] == 'On Memo' || $request['payment'] == 'On Hold'){
             $status = 0;
         }
 
         $created_at = $request['created_at'];
         $estimatearray = array(
             'b_firstname' => $request['b_firstname'],
             'b_lastname' => $request['b_lastname'],
             'b_company' => $request['b_company'],
             'b_address1' => $request['b_address1'],
             'b_address2' => $request['b_address2'],
             'b_phone' => $request['b_phone'],
             'b_country' => $request['b_country'],
             'b_state' => $request['b_state'],
             'b_city' => $request['b_city'],
             'b_zip' => $request['b_zip'],
             's_firstname' => $request['s_firstname'],
             's_lastname' => $request['s_lastname'],
             's_company' => $request['s_company'],
             's_address1' => $request['s_address1'],
             's_address2' => $request['s_address2'],
             's_phone' => $request['s_phone'],
             's_country' => $request['s_country'],
             's_state' => $request['s_state'],
             's_city' => $request['s_city'],
             's_zip' => $request['s_zip'],
             'po' => strtoupper($request['po']),
             'method' => $request['payment'],
             'taxable' => $request['taxable'],
             'comments' => $request['comments'],
             'payment_options' => $request['payment_options'],
             'freight' => $request['freight'],
             'status' => $status
         );
 
         if ($created_at) {
             $estimatearray['created_at']=date('Y-m-d H:i:s', strtotime($created_at));
             $estimatearray['updated_at']=date('Y-m-d H:i:s', strtotime($created_at));
         }
         
         $estimate = Order::create($estimatearray);
         
         $customer = Customer::find($request['customer_id']);
         $estimate->customers()->attach($customer->id);
         $subtotal = 0;
         $total = 0;
         $tax = 0;
         
         $status = 0;
         
         if ($request['payment'] == 'On Memo'){
             $status = 1;
         } elseif ($request['payment'] == 'On Hold'){
             $status = 2;
         } elseif ($request['payment'] == 'Proforma'){
             $status = 3;
         }

         foreach ($keys as $key) {
            $product_id = $key;
            $qty = $request['qty'][$key];
            $price = $request['price'][$key];
            $serial = $request['serial'][$key];
            $model = $request['model'][$key];
            
            if ($serial) {
                $product = Product::where('p_serial',$serial)
                    ->where('p_model',$model)
                    ->first();
            } else {
                $product = Product::where('id',$product_id)
                    ->where('p_model',$model)
                    ->first();
                $serial ='';
            }

            $estimate->products()->attach($product->id, [
                'price' => $price,
                'qty' => $qty,
                'serial' => $serial
            ]);

            if ($serial) {
                $tqty = $product->p_qty - $qty;
            
                $product->update([
                    'p_qty' => $tqty,
                    'p_status' => $status
                ]);
            }
            $subtotal = $subtotal + ($price*$qty);
         }
         
         $freight = $request['freight'];
        if ($customer->cgroup == 0) {
            $tax = Taxable::where('state_id',$estimate->s_state)->value('tax');
            $total = $subtotal + ($subtotal * ($tax/100))+$freight;
        } else {
            $total = $subtotal+$freight;
        }
 
         $estimate->update([
             'subtotal' => $subtotal,
             'total' => $total,
             'taxable' => $tax,
             'freight' => $freight
         ]);
 
         return redirect("admin/orders/".$estimate->id);
     }

     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // $validator = \Validator::make($request->all(), [
        //     'b_company' => "required",
        // ]);
    
        // if ($validator->fails()) {
        //     return back()
        //         ->withInputs($request->all())
        //         ->withErrors($validator);
        // }

        $totalArray = count($request['price'])-2;

        $request->validate([
            'b_company' => "required",
            "product_name" => "required|array|min:1",
            "price" => "required|array|min:1",
            "price.".$totalArray => "numeric|gt:0"
        ], [
            "product_name.min"=>"You must add at least one product.",
            "price.".$totalArray.".numeric" => "Price field may not be left empty.",
            "price.".$totalArray.".gt" => "Price was set to 0. Please enter a valid number"
           ]
        );

        $estimatearray = $request->all();
        $status = 1; // 1 = paid 2 = unpaid
        if ($request['payment_options'] != 'None') {
            $status = 0;
        } elseif ($request['payment'] == 'On Memo'){
            $status = 0;
        }

        $created_at = $request['created_at'];
        if ($request['b_country']!='231') 
        $request['b_state'] = '';

        if ($request['s_country']!='231') 
            $request['s_state'] = '';

        if ($created_at) {
            $estimatearray['created_at']=date('Y-m-d H:i:s', strtotime($created_at));
            $estimatearray['updated_at']=date('Y-m-d H:i:s', strtotime($created_at));
        }
        
        $customer = Customer::find($request['customer_id']);
        
        if (!$customer) {
            
            $data = array(
                'firstname' => $request['b_firstname'],
                'lastname' => $request['b_lastname'],
                'company' => $request['b_company'],
                'address1' => $request['b_address1'],
                'address2' => $request['b_address2'],
                'phone' => $request['b_phone'],
                'country' => $request['b_country'],
                'state' => $request['b_state'],
                'city' => strtoupper($request['b_city']),
                'zip' => $request['b_zip']
            );

            $customer = Customer::updateOrCreate(['company'=>$request['b_company']],$data);
         } 

        $estimate = Estimate::create($estimatearray);
        $estimate->customers()->attach($customer->id);

        $subtotal = 0;
        $total = 0;
        $tax = 0;

        if ($customer->cgroup == 0) 
            $tax = Taxable::where('state_id',$request['s_state'])->value('tax');
        
        $keys = array_keys($request['qty']);
        
        foreach ($keys as $key) {
            $p_model = $request['serial'][$key];
            $qty = $request['qty'][$key];
            $price = $request['price'][$key];
            $retail = 0;
            $product_name = "";
            
            if (isset($request['retail'][$key]))
                $retail = $request['retail'][$key];
            
            if (isset($request['product_name'][$key]))
                $product_name = $request['product_name'][$key];

            $product=[
                'estimate_id' => $estimate->id,
                'p_model' => $p_model,
                'qty' => $qty,
                'price' => $price,
                'retail_price' => $retail,
                'product_name' => $product_name
            ];

            if ($price) {
                \DB::table('estimate_product')->insert($product);
                $subtotal = $subtotal + ($price*$qty);
            }
        }
        
        if ($customer->cgroup == 0) {
            $total = $subtotal + ($subtotal * ($tax/100));
        } else 
            $total = $subtotal;

        $estimate->update([
            'subtotal' => $subtotal,
            'total' => $total,
            'taxable' => $tax
        ]);

        //$printOrder = new \App\Libs\PrintOrder(); // Create Print Object
        //$printOrder->print($estimate,'email','',$estimate->comments); // Print newly create proforma.

        return redirect("admin/estimates");
    }

    public function print($id) {

        $estimate=Estimate::find($id);
        $printOrder = new \App\Libs\PrintOrder(); // Create Print Object
        $printOrder->print($estimate); // Print newly create proforma.

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estimate = Estimate::find($id);
        

        //dd($estimate->products->first()->retail);
        // $estimate = EstimateProduct::join("product_retails","p_model","=","product_id")
        //     ->join("estimates", "estimates.id","=","estimate_product.estimate_id")
        //     ->where("estimate_id",$id)->groupBy('p_model','product_retails.id','estimate_product.id')
        // ->get(); 
        
        if (!$estimate)
            return response()->view('errors/admin-notfound',['id'=>$id],404);

        return view("admin.estimates.show",['pagename' => 'Order #'.$id, 'estimate' => $estimate]);
    }

    public function memotransfer(Request $request,$id)
    {
        $estimate = Estimate::find($id);
        $status=0;

        if ($request['payment_options'] == 'None')
            $status = 1;

        $estimate->update([
            'method' => $request['payment'],
            'payment_options' => $request['payment_options'],
            'status' => $status
        ]);

        return redirect("admin/estimates");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $estimate = Estimate::find($id);
        //dd($estimate->products->first());
        if (!$estimate)
            return response()->view('errors/admin-notfound',['id'=>$id],404);

        return view("admin.estimates.edit",['pagename' => 'Order #'.$id, 'estimate' => $estimate]);
    }

    // Deletes individual product from the Estimate when in edit mode.
    public function destroyestimatedproduct(Request $request) {
        if ($request->ajax()) {
            
            $estimate_id = $request['estimateid'];
            $op_id = $request['productid'];
            $estimate = Estimate::find($estimate_id);
            
            \DB::table('estimate_product')->where('id',$op_id)->delete();

            $this->refreshEstimateTotals($estimate);
                       
            return response()->json('success');
        }
    }

    public function refreshEstimateTotals($estimate) {
        $cgroup = $estimate->customers->first()->cgroup;
        $subtotal = 0;
        $total = 0;
        $tax = 0;

        foreach ($estimate->products as $product) {
            $qty = $product->qty;
            $price = $product->price;

            $subtotal = $subtotal + ($price*$qty);
        }
        
        $freight = str_replace(['.00',','],'',$estimate->freight);
        
        if ($cgroup == 0) {
            $tax = Taxable::where('state_id',$estimate->s_state)->value('tax');
            $total = $subtotal + ($subtotal * ($tax/100))+$freight;
        } else {
            $total = $subtotal+$freight;
        }

        $estimate->update([
            'subtotal' => $subtotal,
            'total' => $total,
            'taxable' => $tax,
            'freight' => $freight
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'b_company' => "required",
        ]);

        if ($validator->fails()) {
            return back()
                ->withInputs($request->all())
                ->withErrors($validator);
        }

        $status = 1; // 1 = paid 2 = unpaid
        if ($request['payment_options'] != 'None') {
            $status = 0;
        } elseif ($request['payment'] == 'On Memo'){
            $status = 0;
        }
        
        $estimatearray = $request->all();
        $customer = Customer::findOrFail($request['customer_id']);
        
        if (!$customer->address1) {
            $data = array(
                'firstname' => $request['b_firstname'],
                'lastname' => $request['b_lastname'],
                'address1' => $request['b_address1'],
                'address2' => $request['b_address2'],
                'phone' => localize_us_number($request['b_phone']),
                'country' => $request['b_country'],
                'state' => $request['b_state'],
                'city' => strtoupper($request['b_city']),
                'zip' => $request['b_zip']
            );
            $customer->update($data);
        }
        
        $estimate = Estimate::find($id);
        $estimate->update($estimatearray);

        if ($request['price']) {
            $keys = array_keys($request['price']);
            
            foreach ($keys as $key) {
                if (isset($request['op_id'][$key]))
                    $op_id = $request['op_id'][$key];
                else $op_id = 0;

                $price = $request['price'][$key];
                $retail = $request['retail'][$key];
                $qty = $request['qty'][$key];
                
                if (isset($request['p_model'][$key])) {
                    $p_model = $request['p_model'][$key];
    
                    $product_name = "";
                    
                    if (isset($request['product_name'][$key]))
                        $product_name = $request['product_name'][$key];

                    if (!isset($request['op_id'][$key]) && $p_model != "") {
                        \DB::table('estimate_product')
                            ->insert([
                                'estimate_id' => $estimate->id,
                                'p_model' => $p_model,
                                'retail_price' => $retail,
                                'qty' => $qty,
                                'price' => $price,
                                'product_name' => $product_name
                            ]);
                            
                    } else {
                        \DB::table('estimate_product')
                                ->where('id', $op_id)
                                ->update([
                                    'qty' => $qty,
                                    'price' => $price,
                                    'retail_price' => $retail,
                                    'product_name' => $product_name
                                ]);    
                    }
                }

            }
        }

        //$printOrder = new \App\Libs\PrintOrder(); // Create Print Object
        //$printOrder->print($estimate,'email','',$estimate->comments); // Print newly create proforma.
        
        //$estimate->load('products'); // Refreshes products after removal from the table
        
        //$when = now()->addSeconds(2);
        //\Notification::route('mail','info@berdvaye.com')->notify((new OrderCreated($estimate))->delay($when));
        //auth()->user()->notify(new OrderCreated($estimate));

        //$when = now()->addSeconds(2);
        //\Notification::route('mail','info@berdvaye.com')->notify((new OrderCreated($estimate))->delay($when));
        //auth()->user()->notify(new OrderCreated($estimate));
        
        //event(new CreatedOrderEvent($estimate));
        
        $this->refreshEstimateTotals($estimate);
        return redirect("admin/estimates");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estimate = Estimate::find($id);
        
        \DB::table('estimate_product')->where('estimate_id',$id)->delete();
        $estimate->customers()->detach();

        $estimate->delete();

        Session::flash('message', "Successfully deleted a proforma!");
        return back();
    }
}
