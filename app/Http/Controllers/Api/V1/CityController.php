<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Http\Resources\CityResource;

class CityController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'result' => CityResource::collection(City::limit(50)->get())
        ]);
    }
}
