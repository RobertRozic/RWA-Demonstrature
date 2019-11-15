<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function login(Request $request) {

        $this->validate(request(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);

        if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = auth()->user();
            $user->api_token = str_random(60);
            $user->save();
            return $user;
        }

        return response()->json([
            'error' => 'Unauthenticated user',
            'code' => 401,
        ], 401);
    }

    public function logout(Request $request) {

        if (auth()->user()) {
            $user = auth()->user();
            $user->api_token = null;
            $user->save();

            return response()->json([
                'message' => 'Thank you for using our application',
            ], 200);
        }

        return response()->json([
            'error' => 'Unable to logout user',
            'code' => 401,
        ], 401);
    }

    public function getUser()
    {
        return Auth::guard('api')->user();
    }

}
