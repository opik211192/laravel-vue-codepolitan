<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $requset)
    {
        $user = $requset->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (! Auth::attempt($user)) {
            return response()->json([
                'message' => 'Authentication is invalid',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $accessToken = Auth::user()->createToken('access_token')->accessToken;

        return response()->json([
            'message' => 'success',
            'data' => Auth::user(),
            'meta' => [
                'token' => $accessToken,
            ],
        ], Response::HTTP_CREATED);
    }

    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'message' => 'logout',
        ], Response::HTTP_OK);
    }
}
