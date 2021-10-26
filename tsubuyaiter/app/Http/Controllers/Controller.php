<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function signup(Request $request)
    {
        /** @var Illuminate\Validation\Validator $validator */
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        try {
            if ($validator->fails()) {
                return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::create([
                'user_name' =>  $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $json = [
                'data' => $user,
                'message' => 'ユーザー登録完了'
            ];

            return response()->json(Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json(Response::HTTP_BAD_REQUEST);
        }
    }

    public function signin(Request $request)
    {
    }
}
