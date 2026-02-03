<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dealer;

class DealersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dealers = Dealer::all();
        return view('admin.dealers',['pagename' => 'Dealers', 'dealers'=>$dealers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dealers.create',['pagename'=>'Create Dealer']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = \Validator::make($request->all(), [
            'dealer'=>'required',
            'address' => "required",
            'website' => "required",
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->all())
                ->withErrors($validator);
        }

        $this::saveDealer($request);
        return redirect('admin/dealers');
    }

    public static function saveDealer($request) {
            
        
        $params=[
            'customer' => $request['dealer'],
            'address' => $request['address'],
            'website' => $request['website'],
            'phone' => $request['phone'],
            'lat'  => $request['lat'],
            'lng' => $request['lng'],
        ];

        if (!empty($request['logo']))
            $params['logo'] = $request['logo'];

        $id = Dealer::create($params);
        return $id; 
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
        $dealer=Dealer::find($id);
        return view('admin.dealers.edit',['pagename'=>'Edit Dealer','dealer'=>$dealer]);
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
        // dd($request->all());
        $validator = \Validator::make($request->all(), [
            'dealer'=>'required',
            'address' => "required",
            'website' => "required",
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->all())
                ->withErrors($validator);
        }

        $this::updateDealer($request->all(), $id);
        return redirect('admin/dealers');
    }

    public static function updateDealer($request, $id) {
        $params=[
            'customer' => $request['dealer'],
            'address' => $request['address'],
            'website' => $request['website'],
            'phone' => $request['phone'],
            'lat'  => $request['lat'],
            'lng' => $request['lng'],
        ];

        if (!empty($request['logo']))
            $params['logo'] = $request['logo'];

        $id = Dealer::where('id',$id)->update($params);

        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dealer = Dealer::find($id);
        if($dealer->logo) {
            unlink(base_path().'/public/uploads/logo/'.$dealer->logo);
            unlink(base_path().'/public/uploads/logo/thumbs/'.$dealer->logo);
        }

        $dealer->delete();

        return back();
    }
}
