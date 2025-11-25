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

    /**
     * Polymorphic comments relationship (new system)
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Approved comments only
     */
    public function approvedComments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('is_approved', true);
    }
    
    /**
     * Legacy relationship for backward compatibility
     */
    public function userComments()
    {
        return $this->hasMany(GalleryUserComment::class);
    }
    
    // Add relationship for reactions
    public function reactions()
    {
        return $this->hasMany(GalleryReaction::class);
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