<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryComment extends Model
{
    protected $fillable = [
        'gallery_item_id',
        'name',
        'email',
        'comment',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean'
    ];

    public function galleryItem()
    {
        return $this->belongsTo(GalleryItem::class);
    }
}
