<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dealer;
use App\Models\Oem;
use Illuminate\Support\Facades\Validator;

class DmsController extends Controller
{
    public function get_oem_master(Request $request, $type)
    {
        $schema = Validator::make($request->all(), [
            'oem_short_name' => 'sometimes|array',
            'city' => 'sometimes|array'
        ]);

        if ($schema->fails()) {
            $error = $schema->errors()->first();
            $response = [
                'status' => 422,
                'message' => $error
            ];
        } else {

            $inputs = $schema->validated();
            switch ($type) {
                default:
                    $oems_master = [];
                    $oems = Oem::all();
                    foreach ($oems as $o) {
                        $oems_master[] = [
                            "oem_short_name" => $o->short_name,
                            "oem_name" => $o->name
                        ];
                    }
                    $response = [
                        'status' => 200,
                        'message'=>'success',
                        'data' => $oems_master
                    ];
                    break;
                case "C":
                    $oems_cities = [];
                    $oems_master = $inputs["oem_short_name"];
                    $cities = [];
                    foreach ($oems_master as $oem) {
                        $citiesForOem = Dealer::whereHas('oems', function ($query) use ($oem) {
                            $query->where('short_name', $oem);
                        })->pluck('dealer_city')->unique()->toArray();
                        $cities = array_merge($cities, $citiesForOem);
                    }
                    $oems_cities[] = [
                        "cities" => array_values(array_unique($cities))
                    ];
                    $response = [
                        'status' => 200,
                        'message'=>'success',
                        'data' => $oems_cities
                    ];
                    break;
                case "D":
                    $oems_short_names = $inputs["oem_short_name"];
                    $cities = $inputs["city"];
                    $dealers_master = [];
                    $dealers = Dealer::whereHas('oems', function ($query) use ($oems_short_names) {
                        $query->whereIn('short_name', $oems_short_names);
                    })->whereIn('dealer_city', $cities)->get();
                    foreach ($dealers as $dealer) {
                        $dealers_master[] = $dealer->dealer_name;
                    }
                    $response = [
                        'status' => 200,
                        'message'=>'success',
                        'data' => $dealers_master
                    ];
                    break;
            }
        }

        return response()->json($response, $response['status']);
    }
}
