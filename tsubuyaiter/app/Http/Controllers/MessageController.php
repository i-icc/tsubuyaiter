<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use \Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function postMessage(MessageRequest $request)
    {
        $message = Message::create([
            'user_id' =>  $request->user()->id,
            'message' => $request->message,
        ]);
        return response()->json(['message_id' => $message->id], Response::HTTP_OK);;
    }

    public function getMessages(Request $request)
    {
        $sub_query = Favorite::select(DB::raw('count(message_id) as fav, message_id'))
            ->groupBy('message_id');

        $messages = Message::leftJoinSub($sub_query, 'fav', function ($join) {
            $join->on('messages.id', '=', 'fav.message_id');
        })->select(['messages.user_id', 'id as message_id', 'message', 'created_at', 'fav'])
            ->get();

        return response()->json(['messages' => $messages], Response::HTTP_OK);;
    }
}
