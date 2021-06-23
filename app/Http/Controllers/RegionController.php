<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getCity()
    {
        // query data based on requested province id
        $cities = City::where('province_id', request()->province_id)->get();
        // return json
        return response()->json([
            'status' => 'success',
            'data' => $cities
        ]);
    }

    public function getDistrict()
    {
        // query data based on requested city id
        $districts = District::where('city_id', request()->city_id)->get();
        // return json
        return response()->json([
            'status' => 'success',
            'data' => $districts
        ]);
    }
}
