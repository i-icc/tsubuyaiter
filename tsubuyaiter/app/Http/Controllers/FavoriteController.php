<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Message;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    public function giveFavorite(Request $request)
    {
        $user_id = $request->user()->id;
        if(Message::where('id',$request->message_id)->count() == 0){
            return response()->json(['error' => 'The message with this '.$request->message_id.' does not exist.'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(Favorite::where('message_id',$request->message_id)->where('user_id',$user_id)->count() > 0){
            return response()->json(['error' => 'I have already given this message a favorite.'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Favorite::insert([
            'user_id' =>  $user_id,
            'message_id' => $request->message_id,
        ]);

        return response()->json(Response::HTTP_OK);
    }
}
