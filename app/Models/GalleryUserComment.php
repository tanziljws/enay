<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryUserComment extends Model
{
    protected $fillable = [
        'user_id',
        'gallery_item_id',
        'comment'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function galleryItem(): BelongsTo
    {
        return $this->belongsTo(GalleryItem::class);
    }
}