<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSharesPost extends Model
{
    protected $table = 'user_shares_posts';
    protected $fillable = [
        'user_id',
        'post_id'
    ];
}
