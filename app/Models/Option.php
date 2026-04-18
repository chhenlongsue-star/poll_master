<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['poll_id', 'option_text'];

    /**
     * The poll this option belongs to.
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * The votes cast for this specific option.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}