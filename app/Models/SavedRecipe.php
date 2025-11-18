<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SavedRecipe extends Model
{
    use HasFactory;

    // The fields that can be mass assigned
    protected $fillable = [
        'user_id',
        'title',
        'ingredients',
        'instructions',
        'summary',
        'category'
    ];

    /**
     * The user who owns this saved recipe.
     */
    public function user(): BelongsTo
    {
        // Absolute namespace is safest for model relationships.
        return $this->belongsTo(\App\Models\User::class);
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlanEntry::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(RecipeShare::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(RecipeTag::class, 'recipe_tag');
    }
}
