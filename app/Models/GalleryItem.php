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
        return $this->hasMany(GalleryUserComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(GalleryUserComment::class)->where('is_approved', true);
    }
    
    // Add relationship for reactions
    public function reactions()
    {
        return $this->hasMany(GalleryReaction::class);
    }
    
    // Add relationship for user comments
    public function userComments()
    {
        return $this->hasMany(GalleryUserComment::class);
    }
    
    /**
     * Get current user's reaction (like/dislike/null)
     */
    public function getUserReactionAttribute()
    {
        if (!auth()->check()) {
            return null;
        }
        
        $reaction = GalleryReaction::where('user_id', auth()->id())
            ->where('gallery_item_id', $this->id)
            ->first();
            
        return $reaction ? $reaction->type : null;
    }
}