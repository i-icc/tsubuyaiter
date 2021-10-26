<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Symfony\Component\HttpFoundation\Response;

class SigninController extends Controller
{
    public function signin(LoginRequest $request)
    {
        try {
            $user = User::creat();
            $token = $user->createToken($request->token_name);

            return response()->json(['token' => $token->plainTextToken]);
        } catch (\Exception $ex) {
            return response()->json(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
