<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Dealer;
use Session;
use DB;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $customers = Customer::latest()->get();
        return view('admin.customers', ['pagename' => 'Customers','customers' => $customers]);
    }

    public function lvcustomers() {
        return view('admin.lvcustomers');
    }
    
    public function lvdealers() {
        return view('admin.lvdealers');
    }

    public function ajaxgetCustomer(Request $request) {
        if ($request->ajax()) {
            $id = $request['_id'];
            
            // $dealer = Dealer::select("order")
            //     ->orderBy('created_at', 'desc')->first();

            $customer = Customer::with('credit')->find($id);
            
            // if ($credit)
            //     $availableCredit = '$'.number_format($credit->first()->amount,2);
            // else $availableCredit = '';

            if (isset($request['param']))
                return response()->json(array('data'=>$customer));
            else    
                return response()->json($customer);
        }
    }

    public function ajaxgetCustomerL(Request $request) {
        if ($request->ajax()) {
            $id = $request['_id'];
            
            $customer = Customer::with('credit')->find($id);

            return response()->json($customer);
        }
    }

    public function ajaxCustomerL(Request $request) {
        if ($request->ajax()) {
            $key = $request['query'];

            $addParam = '';

            if (isset($request['addParam']))
                $addParam = $request['addParam'];

            $customers = DB::table('customers')
                ->whereRaw("company LIKE '%$key%' or lastname LIKE '%$key%' or firstname LIKE '%$key%'")
                ->get();

            $data = array();
            $data['query'] = $key;
            $data['suggestions'] = array();
            
            foreach ($customers as $customer) {
                $lastname='';$firstname='';

                if ($customer->lastname)
                    $lastname = $customer->lastname . " "; 
                if ($customer->firstname)
                    $firstname = $customer->firstname . " ";

                if (!$addParam)
                    $data['suggestions'][] = array('value'=>$lastname . $firstname.$customer->company,'data' => $customer->id);
                else 
                    $data['suggestions'][] = array('value'=>$customer->company,'data' => $customer->id);
            }

            return response()->json($data);
        }
    }

    public function ajaxDealer(Request $request) {
        if ($request->ajax()) {
            $key = $request['query'];

            $customers = DB::table('customers')
                ->whereRaw("company LIKE '%$key%' or lastname LIKE '%$key%' or firstname LIKE '%$key%'")
                ->get();

            $data = array();
            $data['query'] = $key;
            $data['suggestions'] = array();
            
            foreach ($customers as $customer) {
                $data['suggestions'][] = array('value'=>$customer->company,'data' => $customer->id);
            }

            return response()->json($data);
        }
    }

    public function ajaxCustomer(Request $request) {
        if ($request->ajax()) {

            $n_criteria = strlen($request['_criteria']);

            $criteria = $request['_criteria'];
            $searchBy = $request['_searchBy'];

            $customers = DB::table('customers')
                ->whereRaw("left($searchBy,$n_criteria)='$criteria'")
                ->get();

            
            ob_clean();

            foreach ($customers as $customer) {
                ?>
                <div class="customer-item" data-id="<?= $customer->id ?>"><?= $customer->lastname . "  " . $customer->firstname . " " . $customer->company?></div>
                <?php
            }

            return response()->json(ob_get_clean());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$countries = Country::All();
        return view('admin.customers.create',['pagename' => 'New Customer']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'company' => 'unique:customers',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->all())
                ->withErrors($validator);
        }

        $this::saveCustomer($request->all());
        return redirect('admin/customers');
    }

    public function saveCustomer($request) {
        Customer::create([
            //'cgroup' => $request['customer-group'],
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'company' => $request['company'],
            'address1' => $request['address1'],
            'address2' => $request['address2'],
            'phone' => localize_us_number($request['phone']),
            'country' => $request['country'],
            'state' => $request['state'],
            'city' => $request['city'],
            'zip' => $request['zip'],
            'email' => $request['email'],
        ]);

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
        $customer = Customer::find($id);
        if (!$customer)
            return response()->view('errors/admin-notfound',['id'=>$id],404);

        return view('admin.customers.edit',['pagename' => 'Edit Customer', 'customer' => $customer]);
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
            'company' => 'required'
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->all())
                ->withErrors($validator);
        }

        Customer::find($id)->update($request->all());
        return redirect('admin/customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::where('id',$id);
        $customer->delete();

        Session::flash('message','Successfully deleted product!');
        return redirect('admin/customers');

    }
}
