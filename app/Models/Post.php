<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = [
        'image_id',
        'title',
        'content',
        'user_id',
        'group_id'
    ];

    protected $hidden = [
        'image_id'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
