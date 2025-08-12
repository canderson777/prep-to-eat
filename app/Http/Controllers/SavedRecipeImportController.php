<?php

namespace App\Http\Controllers;

use App\Models\SavedRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedRecipeImportController extends Controller
{
    public function import(Request $request)
    {
        $validated = $request->validate([
            'recipes' => ['required', 'array', 'max:200'],
            'recipes.*.title' => ['nullable', 'string', 'max:255'],
            'recipes.*.category' => ['nullable', 'string', 'max:255'],
            'recipes.*.ingredients' => ['nullable', 'string'],
            'recipes.*.instructions' => ['nullable', 'string'],
            'recipes.*.summary' => ['nullable', 'string'],
        ]);

        $userId = Auth::id();

        foreach ($validated['recipes'] as $recipeInput) {
            $title = $recipeInput['title'] ?? 'Untitled';

            // Use updateOrCreate keyed by (user_id, title) to respect DB unique index
            SavedRecipe::updateOrCreate(
                [
                    'user_id' => $userId,
                    'title' => $title,
                ],
                [
                    'category' => $recipeInput['category'] ?? 'Uncategorized',
                    'ingredients' => $recipeInput['ingredients'] ?? '',
                    'instructions' => $recipeInput['instructions'] ?? '',
                    'summary' => $recipeInput['summary'] ?? '',
                ]
            );
        }

        return response()->json(['ok' => true]);
    }
}


