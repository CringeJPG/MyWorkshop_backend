<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'user_id',
        "category_id",
        'image_id',
        'name',
        'description'
    ];

    protected $hidden = [
        'image_id'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
