<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Get Semua Data City berdasarkan spesifik Province_id
     * 
     * @var mixed $cities
     * @return object
     */
    public function getCity()
    {
        $cities = City::where('province_id', request()->province_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $cities
        ]);
    }

    /**
     * Get Semua Data District berdasarkan spesifik City_id
     * 
     * @var mixed $districts
     * @return object
     */
    public function getDistrict()
    {
        $districts = District::where('city_id', request()->city_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $districts
        ]);
    }

    /**
     * Get data AddressBook Customer
     * 
     * @var mixed $address
     * @var mixed $cities
     * @var mixed $districts
     * @return object
     */
    public function getAddress()
    {
        $address = AddressBook::where('id', request()->address_book)->first();
        $cities = City::where('province_id', $address->district->province->id)->get();
        $districts = District::where('city_id', $address->district->city->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $address,
            'city' => $cities,
            'district' => $districts
        ]);
    }
}
