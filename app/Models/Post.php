<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $table = 'posts';

    protected $fillable = ['description', 'user_id'];

    public function userTags()
    {
        return $this->belongsToMany(User::class, 'post_tag', 'post_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->hasMany(PostImage::class, 'post_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id', 'id')->with(['user']);
    }

    public function comments()
    {
        return $this->hasMany(CommentPost::class);
    }
}