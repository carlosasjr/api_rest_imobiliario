<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginJwtController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);

        Validator::make($credentials, [
            'email' => 'required|string',
            'password' => 'required|string',
        ])->validate();

        if (!$token = auth('api')->attempt($credentials)) {
            $message = new ApiMessages('Unauthorized');
            return response()->json($message->getMessage());
        }

        return response()->json([
            'token' => $token
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json([
            'token' => $token
        ]);
    }


}
