<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'email' => 'required|max:255|email',
            'password' => 'required'
        ]);
        if ($validations->fails()) {
            return collect([
                'status' => false,
                'errors' => $validations->errors()
            ]);
        }
        if (!Auth::attempt($request->only('email', 'password'))) {
            return collect([
                'status' => false,
                'errors' => 'Invalid login details'
            ]);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return collect([
            'status' => true,
            'data' => collect([
                'token' => $token,
            ])
        ]);
    }
}
