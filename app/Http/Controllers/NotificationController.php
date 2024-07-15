<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PostNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->user()->notifications;

        return response()->json([
            'data' => $data,
        ]);
    }

    public function test($id)
    {
        // $user = User::findOrFail($id);
        // $data = $user->notify(new PostNotification('Hello World! I am a notification 😄'));
        // dd($data);
    }
}