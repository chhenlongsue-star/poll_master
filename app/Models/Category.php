<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the polls associated with this category.
     * * This allows you to do: $category->polls
     */
    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    /**
     * Optional: Route Key Name
     * This allows you to use the slug in URLs instead of the ID
     * e.g., /dashboard?category=technology instead of /dashboard?category=1
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}