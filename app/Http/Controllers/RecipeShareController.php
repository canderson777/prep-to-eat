<?php

namespace App\Http\Controllers;

use App\Models\RecipeShare;
use App\Models\SavedRecipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RecipeShareController extends Controller
{
    public function store(Request $request, SavedRecipe $recipe): RedirectResponse
    {
        if ($recipe->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'expires_at' => 'nullable|date|after:now',
        ]);

        $token = Str::uuid()->toString();

        RecipeShare::create([
            'saved_recipe_id' => $recipe->id,
            'user_id' => $request->user()->id,
            'token' => $token,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return redirect()
            ->route('recipes.index')
            ->with('success', 'Share link created!');
    }

    public function destroy(Request $request, RecipeShare $share): RedirectResponse
    {
        if ($share->user_id !== $request->user()->id) {
            abort(403);
        }

        $share->delete();

        return redirect()->route('recipes.index')->with('success', 'Share link removed.');
    }

    public function show(string $token): View
    {
        $share = RecipeShare::with(['recipe.user'])->where('token', $token)->firstOrFail();

        if ($share->isExpired()) {
            abort(410, 'This shared recipe has expired.');
        }

        return view('shared_recipes.show', [
            'share' => $share,
            'recipe' => $share->recipe,
        ]);
    }
}
