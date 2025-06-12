<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function user()
    {
        // Absolute namespace is safest for model relationships.
        return $this->belongsTo(\App\Models\User::class);
    }
}
