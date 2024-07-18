<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function listPostDetails($id)
    {
        $posts = Post::where('user_id', $id)->get();
        return PostResource::collection($posts);
    }


    public function uploadAvatar(Request $request, $id)
    {
        $files = "";
        if ($request->avatar) {
            $name = time() . rand(1, 100) . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('uploads'), $name);
            $files = $name;

            $user = User::findOrFail($id);
            $user->avatar = $files;
            $user->save();
        }
        return response()->json([
            'data' => $user,
        ]);
    }

    public function uploadCoverImage(Request $request, $id)
    {
        $files = "";
        if ($request->cover_image) {
            $name = time() . rand(1, 100) . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('uploads'), $name);
            $files = $name;

            $user = User::findOrFail($id);
            $user->cover_image = $files;
            $user->save();
        }
        return response()->json([
            'data' => $user,
        ]);
    }
}
