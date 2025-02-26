<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFollowsGroup extends Model
{
    protected $table = 'user_follows_group';
    protected $fillable = [
        'group_id',
        'user_id',
        "is_admin"
    ];
}
