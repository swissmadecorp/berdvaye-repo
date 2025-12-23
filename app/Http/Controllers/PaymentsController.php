<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Credit;
use App\Models\Returns;
use Session;

class PaymentsController extends Controller
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
        return view('admin.payments',['pagename' => 'Payments']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //$payments = Payment::where('order_id',$id)->orderBy('created_at','asc')->get();
        $order = Order::find($id);

        $total_credit=0;
        $credits = Credit::where('customer_id',$order->customers()->first()->id)->get();
        
        foreach ($credits as $credit) {
            $total_credit+=$credit->amount;
        }

        return view('admin.payments.create',['pagename' => 'Payments for Order #' . $id,'order' => $order,'totalCredit'=>$total_credit]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        dd($request->all());
        $validator = \Validator::make($request->all(),[
            'payment' => 'required',
            'payment_option' => 'required'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->all());
        }
        
        $order=Order::find($request['order_id']);
        Payment::create ([
            'amount' => $request['payment'],
            'ref' => $request['payment_option'],
            'order_id' => $request['order_id']
        ]);

        if (number_format($request['totalLeft'],2) == number_format($request['payment'],2)) {
            $order->update([
                'status' => 1
            ]);
        }

        
        $customer_id=$order->customers()->first()->id;
        $credit = Credit::where('customer_id',$customer_id)->first();
        if ($credit->Count()) {
            if ($credit->amount>$request['payment']) {
                $credit->amount-=$request['payment'];
                $credit->update();
            } else 
                $credit->delete();

        }
    

        return back();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$payment)
    {
        dd('asdf');
        $payment = Payment::find($payment);
        $payment->delete();

        Order::find($id)->update(['status' => 0]);

        Session::flash('message', "Successfully deleted payment!");
        return back();
    }
}
