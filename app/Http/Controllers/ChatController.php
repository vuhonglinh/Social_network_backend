<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Events\UserEvent;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereNotIn('id', [$request->user()->id])->get();
        return response()->json(['data' => $users]);
    }


    public function getUserDetail($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => $user]);
    }


    public function getChatDetails(Request $request)
    {
        $sender = $request->user()->id; // Người gửi
        $receiver = $request->id; // Người nhận

        // Lấy tất cả tin nhắn giữa người gửi và người nhận
        $messages = Message::with(['sender', 'receiver'])
            ->where(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $sender)->where('receiver_id', $receiver);
            })->orWhere(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $receiver)->where('receiver_id', $sender);
            })->get();
        return MessageResource::collection($messages);
    }

    public function send(Request $request)
    {
        try {
            $message = Message::create([
                'message' => $request->message,
                'sender_id' => $request->user()->id, //Người gửi
                'receiver_id' => $request->id //Người nhận
            ]);
            $message->load('sender', 'receiver');
            ChatEvent::dispatch($message, $request->id, $request->user()->id);
            return new MessageResource($message);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}