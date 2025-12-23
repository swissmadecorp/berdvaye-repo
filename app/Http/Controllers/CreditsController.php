<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Order;
use Session;
use DB;

class CreditsController extends Controller
{
    public function __construct() {
        $this->middleware('role:superadmin|user', ['only' => ['create', 'store', 'edit', 'delete']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exclude = array();
        $credits = Credit::select('credit_customer.id','customer_id',
            'amount','credit_customer.created_at','firstname', 
            'lastname', 'company')->join('customers','customers.id','=','credit_customer.customer_id')
            ->latest()
            ->get();

        foreach ($credits as $credit) {
            $exclude[] = $credit->customer_id;
        }

        $customers=Customer::whereNotIn('id',$exclude)->get();
        return view('admin.credits', ['pagename' => 'Customer Credits','credits' => $credits,'customers'=>$customers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     **/
    public function create($id)
    {
        
        $orders = Order::with("customers")->whereHas('customers', function($query) use($id) {
            $query->where('id',$id);
        })
            ->where('method','Invoice')
            ->whereIn('status',[0,1])
            ->get();
        
            
        // if (!$orders->count()) {
        //     $orders = Order::with("customers")->whereHas('customers', function($query) use($id) {
        //         $query->where('id',$id);
        //     })
        //         ->where('method','Invoice')
        //         ->where('status',1)
        //         ->get();

        // } 
        
        // if (!$orders)
        //     return response()->view('errors/admin-notfound',['id'=>$id],404);
        
        return view('admin.credits.create',['pagename' => 'New Credit','orders'=>$orders]);
    }

    public function CreditPayment(Request $request) {
        parse_str($request['form'],$output);
        
        $amount = $output['amount'];
        $orderIds = $request['ids'];
        $customer_id = $output['customer_id'];
        $totalAmount = $amount; //1900
        $creditUsed = $output['creditused'];
        
        $paymentMethod = '';
        if ($creditUsed) {
            $paymentMethod = "C";
        } else {
            $paymentMethod = "P";
        }
        
        $orders = Order::findMany($orderIds);
        
        foreach ($orders as $order) {
            $orderTotal = $order->total-$order->disount; // 2950
            
            // foreach($order->payments->all() as $payment) {
            //     $orderTotal = $orderTotal-$payment->amount;
            // }

            $orderTotalReturned = 0;
            foreach($order->returns->all() as $return) {
                $orderTotalReturned+=$return->pivot->amount*$return->pivot->qty; 
            }

            $orderTotal -= $order->payments->sum('amount')+$orderTotalReturned;

            if ($totalAmount < $orderTotal) {
                $newAmount = $totalAmount;
            } else {
                $newAmount = $orderTotal; 
            }

            //return $newAmount . ' ' . $orderTotal . ' ' . $amount;
            Payment::create ([
                'amount' => $newAmount,
                'ref' => $output['ref'],
                'order_id' => $order->id,
                'paid_method' => $paymentMethod
            ]);
        
            if ($totalAmount >= $orderTotal) { 
            
                $order->update([
                    'status' => 1
                ]);
            } 
            
            $totalAmount -= $orderTotal; //950 - 2950 = 0
            //if ($order->id == 1543)
              //  return response()->json(array($totalAmount,$orderTotal,$newAmount));
            if ($totalAmount < 0) break;
            
        }
        
        $credit = Credit::where('customer_id',$customer_id)->first();
        if ($credit && $creditUsed) {
            if ($credit->amount > $newAmount) { 
                if ($credit->amount == $amount ) 
                    $credit->amount=$totalAmount;
                else $credit->amount-=$amount;
                
                $credit->update();
            } else
                $credit->delete();
        } elseif ($totalAmount > 0) {
            Credit::create([
                'amount' => $totalAmount,
                'customer_id' => $customer_id,
                'ref' => $output['ref']
            ]);
        }
        
        return response()->json('Success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = \Validator::make($request->all(), [
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->all())
                ->withErrors($validator);
        }

        // Credit::create([
        //     'amount' => $request['amount'],
        //     'customer_id' => $request['customer_id'],
        // ]);

        // return redirect('admin/credits');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $credit = Credit::find($id);
        
        if (!$credit)
            return response()->view('errors/admin-notfound',['id'=>$id],404);

        return view('admin.credits.edit',['pagename' => 'Edit Credit', 'credit' => $credit]);
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
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->all())
                ->withErrors($validator);
        }

        $credit = Credit::find($id);
        $credit->update([
            'amount' =>$credit->amount + $request['amount']
        ]);
        
        return redirect("admin/credits/$id/edit");
    }

    public function deletePayment(Request $request) {
        $payment = Payment::find($request["paymentId"]);
        $custId = $request["custId"];
        $order = Order::find($payment->order_id);
        
        if ($request['paymethod'] == "C") {
            $credit = Credit::where('customer_id',$custId)->first();
            if ($credit) {
                    $credit->amount+=$payment->amount;
                    $credit->update();
            } else {
                Credit::create([
                    'amount' => $payment->amount,
                    'customer_id' => $custId,
                    'ref' => $payment->ref
                ]);
            }
        } 
        $order->status = 0;
        $order->update();
        
        $payment->delete();

        return redirect("admin/orders/$custId/payments/create");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $credit = Credit::where('id',$id);
        $credit->delete();

        Session::flash('message','Successfully deleted credit!');
        return redirect('admin/credits');

    }
}
