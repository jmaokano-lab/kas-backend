<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_id',
        'user_id',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }
     public function blog()
    {
        return $this->belongsTo(\App\Models\Blog::class, 'blog_id');
    }
}
