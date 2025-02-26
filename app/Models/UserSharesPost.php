<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSharesPost extends Model
{
    protected $table = 'user_shares_post';
    protected $fillable = [
        'user_id',
        'post_id'
    ];
}
