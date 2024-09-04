<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\AdminOwner;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Correctly use the validate method with the rules array
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password'=>'required|confirmed'
        ]);

        // After validation, proceed with user creation
        $user = Admin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Generate a JWT token for the newly created user
        $token = JWTAuth::fromUser($user);

        // Return the token in a JSON response
        return response()->json(compact('token'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Try authenticating with the 'admin' guard first
        if ($token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(compact('token'));
        }

        // If that fails, try authenticating with the 'owner' guard
        if ($token = Auth::guard('owner-api')->attempt($credentials)) {
            return response()->json(compact('token'));
        }

        // If both fail, return an unauthorized error
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function getUser()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out']);
    }
}
