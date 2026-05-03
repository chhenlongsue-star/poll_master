<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'category_id',
        'type',       // 'admin' or 'user'
        'is_active',
    ];

    /**
     * Get the category that owns the poll.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the user (creator) that owns the poll.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the options for the poll.
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Get the votes for the poll. 
     * (Required for ->withCount('votes') in your Controller)
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favourites');
}
}