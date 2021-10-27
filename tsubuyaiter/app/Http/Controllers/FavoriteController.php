<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Message;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    public function add(Request $request)
    {
        $user_id = $request->user()->id;
        $favorite = Favorite::seelct(['user_id','message_id']);
        $message = Message::seelct(['message_id'])->where('message_id','=',$request->message_id);


        return $request->user()->id;
        Favorite::create([
            'user_id' =>  $user_id,
            'message_id' => $request->message_id,
        ]);
        return response()->json(Response::HTTP_OK);;
    }
}
