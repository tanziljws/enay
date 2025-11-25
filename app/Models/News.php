<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'author',
        'category',
        'status',
        'published_at',
        'views_count',
        'likes_count',
        'dislikes_count'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function approvedComments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('is_approved', true);
    }
    
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
    
    /**
     * Get news-specific reactions (for backward compatibility)
     */
    public function newsReactions()
    {
        return $this->hasMany(NewsReaction::class);
    }
    
    /**
     * Get current user's reaction (like/dislike/null)
     */
    public function getUserReactionAttribute()
    {
        if (!auth()->check()) {
            return null;
        }
        
        $reaction = NewsReaction::where('user_id', auth()->id())
            ->where('news_id', $this->id)
            ->first();
            
        return $reaction ? $reaction->type : null;
    }
}
