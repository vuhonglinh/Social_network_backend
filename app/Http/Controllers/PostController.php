<?php

namespace App\Http\Controllers;

use App\Events\AddCommentEvent;
use App\Events\AddPostEvent;
use App\Events\PostLikeEvent;
use App\Http\Resources\CommentPostResource;
use App\Http\Resources\PostLikeResource;
use App\Http\Resources\PostLikeResourceCollection;
use App\Http\Resources\PostResource;
use App\Models\CommentPost;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostLike;
use App\Models\User;
use App\Notifications\PostNotification;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('images')->orderBy('created_at', 'desc')->get();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tạo bài viết mới
        $post = Post::create([
            'description' => $request->description,
            'user_id' => $request->user()->id,
        ]);

        if (!empty($request->images)) {
            foreach ($request->images as $image) {
                PostImage::create([
                    'post_id' => $post->id,
                    'image' => $image,
                ]);
            }
        }
        if (!empty($request->ids)) {
            $post->userTags()->sync($request->ids);
            $users = User::whereIn('id', $request->ids)->get();

            foreach ($users as $user) {
                $user->notify(new PostNotification($post));
            }
        }



        AddPostEvent::dispatch($post);
        return response()->json([
            'message' => 'Thêm bài viết thành công',
            'data' => new PostResource($post)
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function listComments(Request $request)
    {
        $comments = CommentPost::with(['user'])->where('post_id', $request->post_id)->whereNull('parent_id')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Lấy danh sách bình luận thành công',
            'data' =>  CommentPostResource::collection($comments)
        ]);
    }

    public function addComment(Request $request)
    {
        $comment = CommentPost::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user()->id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id ?? null,
        ]);
        AddCommentEvent::dispatch($comment);
        return response()->json([
            'message' => 'Bình luận bài viết thành công',
            'data' => $comment
        ]);
    }

    public function postLike(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $postLike = PostLike::where('post_id', $request->post_id)
            ->where('user_id', $request->user()->id)
            ->first();
        if ($postLike) {
            $postLike->delete();
        } else {
            $postLike = PostLike::create([
                'post_id' => $request->post_id,
                'user_id' => $request->user()->id
            ]);
        }
        PostLikeEvent::dispatch($post);
        return response()->noContent();
    }


    public function uploadFile(Request $request)
    {
        $files = [];
        if ($request->images) {
            foreach ($request->images as $image) {
                $name = time() . rand(1, 100) . '.' . $image->extension();
                $image->move(public_path('uploads'), $name);
                $files[] = $name;
            }
        }
        return response()->json([
            'data' => $files,
        ]);
    }
}