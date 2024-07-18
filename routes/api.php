<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Laravel\Socialite\Facades\Socialite;


Route::middleware(['auth:api'])->group(function () {
    Route::get('/list', [ChatController::class, 'index']);
    Route::get('/user/{id}', [ChatController::class, 'getUserDetail']);
    Route::get('/chat-detail', [ChatController::class, 'getChatDetails']);
    Route::post('/send', [ChatController::class, 'send']);

    Route::resource('posts', PostController::class);
    Route::post('upload-file', [PostController::class, 'uploadFile']);

    Route::post('list-comments', [PostController::class, 'listComments']);
    Route::post('add-comment', [PostController::class, 'addComment']);
    Route::post('post-like', [PostController::class, 'postLike']);

    Route::get('notification', [NotificationController::class, 'index']);


    ///
    Route::get('list-post-details/{id}', [ProfileController::class, 'listPostDetails']);
    Route::post('upload-avatar/{id}', [ProfileController::class, 'uploadAvatar']);
    Route::post('upload-cover-image/{id}', [ProfileController::class, 'uploadCoverImage']);
});
Route::get('test/{id}', [NotificationController::class, 'test']);
// //Đăng nhập fb
// Route::get('auth/facebook', function () {
//     return Socialite::driver('facebook')->redirect();
// })->name('auth.facebook');
// Route::get('auth/facebook/callback', [LoginController::class, 'facebookCallback']);



//Đăng nhập google
Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
});
Route::get('auth/google/callback', [AuthenticatedSessionController::class, 'googleCallback']);

// //Đăng nhập Twitter
// Route::get('auth/twitter', function () {
//     return Socialite::driver('twitter')->redirect();
// });
// Route::get('auth/twitter/callback', [LoginController::class, 'twitterCallback']);


// //Đăng nhập Github
// Route::get('auth/github', function () {
//     return Socialite::driver('github')->redirect();
// })->name('auth.github');
// Route::get('auth/github/callback', [LoginController::class, 'githubCallback']);


Broadcast::routes(["middleware" => "auth:api"]);

Route::get('/routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'methods' => $route->methods(),
            'action' => $route->getActionName(),
        ];
    });

    return response()->json($routes);
});
