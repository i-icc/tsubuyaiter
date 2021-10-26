<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninRequest;
use App\Models\User;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SigninController extends Controller
{
    public function signin(SigninRequest $request)
    {   
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $user = User::whereEmail($request->email)->first();
 
            $user->tokens()->delete();
            $token = $user->createToken("$user->id");
 
            return response()->json(['token' => $token->plainTextToken ], Response::HTTP_OK);
        }
        return response()->json(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
