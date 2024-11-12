<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function getCurrentUser(Request $request)
    {
        return response()->json([
            'result' => new UserResource($request->user())
        ]);
    }

    public function saveUserData(Request $request)
    {
        $request->validate([
            'city'      => 'integer',
            'weight'    => 'integer',
            'height'    => 'integer'
        ]);

        $user = $request->user();
        $data = [];
        $body = $request->all();
        $fillables = (new User)->getFillable();

        foreach($body as $key => $par) {
            if(
                $key == 'password' || 
                $key == 'pincode' || 
                $key == 'phone' || 
                !is_int(array_search($key, $fillables))
            )
                continue;

                $data[$key] = $par;
        }

        if(count($data) > 0) {
            $user->update($data);
        }

        return response()->json([
            'result' => new UserResource($user)
        ]);
    }
}
