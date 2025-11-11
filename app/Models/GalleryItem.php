<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title', 'image', 'description', 'taken_at', 'status',
        'views_count', 'likes_count', 'dislikes_count'
    ];

    protected $casts = [
        'taken_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(GalleryComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(GalleryComment::class)->where('is_approved', true);
    }
}


