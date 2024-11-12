<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    private function createToken($user)
    {
        if($user->tokens) {
            foreach($user->tokens as $t) {
                $t->delete();
            }
        }

        $user->createToken('app_mobileai');
        $token = $user->tokens()->first()->token;

        return $token;
    }

    public function signup(Request $request)
    {
        $request->validate([
            'phone'     => ['required', 'unique:users', 'string'],
            'name'      => ['required', 'string'],
            'password'  => ['required', 'string']
        ]);
        $code = rand(1000, 9999);
        $user = new User;
        $user->email = $request->phone . '@gmail.com';
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->pincode = $code;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'result' => $code
        ]);
    }

    public function signin(Request $request)
    {
        $request->validate([
            'phone'     => ['required', 'string'],
            'password'  => ['required', 'string']
        ]);

        $hasher = app('hash');
        $user = User::where('phone', $request->phone)->first();

        if(!$user) {
            return response()->json([
                'result'     => false,
                'errors'     => [
                    'phone' => ['Пользователь с таким телефоном не найден!']
                ]
            ], 422);
        }

        if(!$hasher->check($request->password, $user->password)) {
            return response()->json([
                'result'     => false,
                'errors'     => [
                    'password' => ['Неверный пароль!']
                ]
            ], 422);
        }

        if(!$user->active) {
            return response()->json([
                'result'     => false,
                'errors'     => [
                    'auth' => ['Ваш аккаунт не активный']
                ]
            ], 422);
        }

        $token = $this->createToken($user);

        return response()->json([
            'result' => $token
        ]);
    }

    public function forgetPass(Request $request)
    {
        $request->validate([
            'phone'     => ['required', 'string']
        ]);

        $code = rand(1000, 9999);
        $user = User::where('phone', $request->phone)->first();

        if($user) {
            $user->pincode = $code;
            $user->save();
        } else {
            return response()->json([
                'result'     => false,
                'errors'     => [
                    'phone' => ['Пользователь с таким телефоном не найден!']
                ]
            ], 422);
        }

        return response()->json([
            'result' => $code
        ]);
    }

    public function enterPincode(Request $request)
    {
        $request->validate([
            'pincode'     => ['required', 'string']
        ]);

        $user = User::where('pincode', $request->pincode)->first();

        if($user) {
            $user->pincode = null;
            $user->active = 1;
            $user->save();
        } else {
            return response()->json([
                'result'     => false,
                'errors'     => [
                    'pincode' => ['Неверный пинкод!']
                ]
            ], 422);
        }

        $token = $this->createToken($user);

        return response()->json([
            'result' => $token
        ]);
    }

    public function restorePass(Request $request)
    {
        $request->validate([
            'password'     => ['required', 'string']
        ]);

        $user = $request->user();

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'result' => true
        ]);
    }
}
