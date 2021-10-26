<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Response;

class SigninController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function signin(LoginRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        return response()->json(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
