<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'saved_recipe_id',
        'user_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(SavedRecipe::class, 'saved_recipe_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        if (!$this->expires_at instanceof CarbonInterface) {
            return false;
        }

        return $this->expires_at->isPast();
    }
}
