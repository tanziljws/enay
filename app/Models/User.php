<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
        'bio',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relationships
     */
    public function galleryReactions(): HasMany
    {
        return $this->hasMany(GalleryReaction::class);
    }

    public function galleryComments(): HasMany
    {
        return $this->hasMany(GalleryUserComment::class);
    }

    public function newsReactions(): HasMany
    {
        return $this->hasMany(NewsReaction::class);
    }

    public function newsComments(): HasMany
    {
        return $this->hasMany(NewsUserComment::class);
    }

    public function teacherReactions(): HasMany
    {
        return $this->hasMany(TeacherReaction::class);
    }

    public function teacherComments(): HasMany
    {
        return $this->hasMany(TeacherComment::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }
    
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
