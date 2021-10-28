<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function postMessage(MessageRequest $request)
    {
        $message = Message::create([
            'user_id' =>  $request->user()->id,
            'message' => $request->message,
        ]);
        return response()->json(['message_id' => $message->id ], Response::HTTP_OK);;
    }

    public function getMessages(Request $request)
    {
        return "test";
    }
}
