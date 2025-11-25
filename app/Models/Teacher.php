<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'teacher_number',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'qualification',
        'specialization',
        'major',
        'join_date',
        'photo',
        'status',
        'role'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
    ];

    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
    
    /**
     * Get current user's reaction (like/dislike/null)
     */
    public function getUserReactionAttribute()
    {
        if (!auth()->check()) {
            return null;
        }
        
        $reaction = TeacherReaction::where('user_id', auth()->id())
            ->where('teacher_id', $this->id)
            ->first();
            
        return $reaction ? $reaction->type : null;
    }
}
