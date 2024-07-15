<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentPost extends Model
{
    use HasFactory;

    public $table = 'comment_posts';

    protected $fillable = ['post_id', 'user_id', 'comment', 'parent_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function reply()
    {
        return $this->hasMany(CommentPost::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(CommentPost::class, 'id', 'parent_id')->with(['user']);
    }
}