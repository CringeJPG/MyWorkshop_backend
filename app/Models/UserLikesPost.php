<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLikesPost extends Model
{
    protected $table = 'user_likes_post';
    protected $fillable = [
        'user_id',
        'post_id'
    ];
}
