<?php

use App\Events\UserEvent;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});


Broadcast::channel('chat.{receiver_id}.{send_id}', function ($user, $receiver_id) {
    return (int) $user->id === (int) $receiver_id;
});


Broadcast::channel('login', function ($user) {
    return (int) $user->id != null;
});

Broadcast::channel('logout', function ($user) {
    return (int) $user->id != null;
});

Broadcast::channel('user.status', function ($user) {
    return $user;
});

Broadcast::channel('add.post', function ($user) {
    return $user->id != null;
});


Broadcast::channel('add.comment.post', function ($user) {
    return $user->id != null;
});

Broadcast::channel('like.post', function ($user) {
    return $user->id != null;
});