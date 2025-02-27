<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $fillable = [
        'user_id',
        "category_id",
        'name',
        'description',
        'image'
    ];
}
