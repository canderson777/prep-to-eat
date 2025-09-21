<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlanEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'saved_recipe_id',
        'planned_for',
        'meal_type',
        'notes',
    ];

    protected $casts = [
        'planned_for' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(SavedRecipe::class, 'saved_recipe_id');
    }
}
