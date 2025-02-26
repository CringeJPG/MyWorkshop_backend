<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFollowsUser extends Model
{
    protected $table = 'user_follows_user';
    protected $fillable = [
        'user_id',
        'followed_user_id'
    ];
}
