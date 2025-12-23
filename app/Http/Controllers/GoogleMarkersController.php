<?php

namespace App\Http\Controllers;

use App\Models\GoogleMarkers;
use App\Models\Dealer;
use Illuminate\Http\Request;

class GoogleMarkersController extends Controller
{
    public function ajaxGoogleMarkers() {
        $results = Dealer::all();
        
        foreach ($results as $row) {
            $return[] = array('website'=>$row->website, 'customer'=>$row->customer, 'location'=>array('lat'=>$row->lat,'lng'=>$row->lng), 'address'=>nl2br($row->address), 'phone' => $row->phone);
        }

        return response()->json($return);
    }

    public function ajaxGetByGoogleMarker(Request $request) {
        $center_lat = $request["lat"];
        $center_lng = $request["lng"];

        // $query = sprintf("SELECT id, name, address, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM google_markers HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20",
        //     $center_lat,$center_lng,$center_lat,10);
        
        $result = Dealer::selectRaw("id, customer, website, address, phone, lat, lng, ( 3959 * acos( cos( radians('$center_lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( lat ) ) ) ) AS distance")
            ->havingRaw("distance < '10'")
            ->orderBy('distance')
            ->limit(20)
            ->get();

        return response()->json($result);
        $result = GoogleMarkers::selectRaw($query)->get();
        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoogleMarkers  $googleMarkers
     * @return \Illuminate\Http\Response
     */
    public function show(GoogleMarkers $googleMarkers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoogleMarkers  $googleMarkers
     * @return \Illuminate\Http\Response
     */
    public function edit(GoogleMarkers $googleMarkers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoogleMarkers  $googleMarkers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoogleMarkers $googleMarkers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoogleMarkers  $googleMarkers
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoogleMarkers $googleMarkers)
    {
        //
    }
}
