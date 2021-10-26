<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
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
