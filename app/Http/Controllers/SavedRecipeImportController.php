<?php

namespace App\Http\Controllers;

use App\Models\SavedRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $rawTitle = $recipeInput['title'] ?? 'Untitled';
            // Normalize excessive whitespace to reduce duplicate-miss due to spacing differences
            $normalizedTitle = preg_replace('/\s+/u', ' ', (string) $rawTitle ?? '');
            $title = trim($normalizedTitle) !== '' ? trim($normalizedTitle) : 'Untitled';

            $incomingCategory = $recipeInput['category'] ?? 'Uncategorized';
            $incomingIngredients = $recipeInput['ingredients'] ?? '';
            $incomingInstructions = $recipeInput['instructions'] ?? '';
            $incomingSummary = $recipeInput['summary'] ?? '';
            // Single atomic upsert using ON DUPLICATE KEY UPDATE to avoid unique exceptions entirely
            $nowTs = now();
            $note = 'Imported from this device on ' . $nowTs->toDayDateTimeString() . '.';
            $sql = "INSERT INTO `saved_recipes` (`user_id`,`title`,`category`,`ingredients`,`instructions`,`summary`,`created_at`,`updated_at`)\n"
                . "VALUES (:user_id, :title, :category, :ingredients, :instructions, :incoming_summary, :created_at, :updated_at)\n"
                . "ON DUPLICATE KEY UPDATE\n"
                . "`category` = VALUES(`category`),\n"
                . "`ingredients` = VALUES(`ingredients`),\n"
                . "`instructions` = VALUES(`instructions`),\n"
                . "`summary` = CASE\n"
                . "  WHEN COALESCE(`summary`, '') = '' AND COALESCE(VALUES(`summary`), '') = '' THEN :note_only\n"
                . "  WHEN COALESCE(`summary`, '') = '' THEN CONCAT(VALUES(`summary`), '\n\n', :note)\n"
                . "  WHEN COALESCE(VALUES(`summary`), '') = '' THEN CONCAT(`summary`, '\n\n', :note)\n"
                . "  ELSE CONCAT(`summary`, '\n\n', VALUES(`summary`), '\n\n', :note)\n"
                . "END,\n"
                . "`updated_at` = VALUES(`updated_at`)";

            DB::statement($sql, [
                'user_id' => $userId,
                'title' => $title,
                'category' => $incomingCategory,
                'ingredients' => $incomingIngredients,
                'instructions' => $incomingInstructions,
                'incoming_summary' => (string) $incomingSummary,
                'created_at' => $nowTs,
                'updated_at' => $nowTs,
                'note_only' => $note,
                'note' => $note,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
