<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'google_id',    // Added for Gmail Login
        'avatar',       // Added for profile pictures
        'role',         // Added for Admin/User roles
        'last_seen_at', // Added for tracking user activity
        'is_active',    // Added for Ban/Disable functionality
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
            'last_seen_at' => 'datetime',
            'is_active' => 'boolean', // Ensures 0/1 is treated as true/false
        ];
    }

    /**
     * Helper to check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relationship: A user can create many polls
     * This fixes the "Call to undefined method polls()" error
     */
    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    /**
     * Relationship: A user can cast many votes
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function favouritePolls() {
    return $this->belongsToMany(Poll::class, 'favourites')->withTimestamps();
}
}