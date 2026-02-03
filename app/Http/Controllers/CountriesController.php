<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;

class CountriesController extends Controller
{
    public function getStateFromCountry(Request $request) {
        $id = $request['id'];
        // return($id);
        $country =Country::with('states')->where('id',$id)->get();
        $content = '<option value="0"></option>';
        foreach ($country->first()->states as $state) {
            $content .= '<option value="'. $state->id . '">'. $state->name .'</option>';
            
        }
        
        //dd($country->first()->states->toArray());
        return \Response::json($content,200);
    }

    public function getStateByCountry(Request $request) {
        if (!$request['id']) return '';

        $id = $request['id'];
        
        $state = State::find($id);
        if (!$state)
            return '';

        return \Response::json($state->name,200);
    }
}
