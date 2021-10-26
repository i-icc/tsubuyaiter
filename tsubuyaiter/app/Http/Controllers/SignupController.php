<?php

namespace App\Http\Controllers;


use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Symfony\Component\HttpFoundation\Response;

class SignupController extends Controller
{
    public function signup(SignupRequest $request)
    {
        try {
            User::create([
                'user_name' =>  $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
