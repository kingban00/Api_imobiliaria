<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Api\ApiMessages;
use Illuminate\Support\Facades\Validator;

class LoginJwtController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only("email","password");
        Validator::make($credentials, [
            'email' => 'required|string',
            'password' => 'required|string',


        ])->validate();
        // $credentials = $request->all(["email","password"]);
        if (!$token = auth('web')->attempt($credentials)){
            $message = new ApiMessages('Unauthorized', 401);
            return response()->json($message->getMessage(),401);
        }

        return response()->json([ 'token' => $token ], 200);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message'=> 'Logout successfully!'],200);
    }

    public function refresh(){
        $token = auth('web')->refresh(true);
        return response()->json(['token'=> $token],200);
    }
}
