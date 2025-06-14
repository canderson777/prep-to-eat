<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedRecipe;
use Illuminate\Support\Facades\Auth;

class SavedRecipeController extends Controller
{
    // Save a new recipe
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'summary' => 'nullable|string',
            'category' => 'required|string|max:255',
            'custom_category' => 'nullable|string|max:255',
        ], [
            'category.required' => 'Please select a category for your recipe.',
            'custom_category.required_if' => 'Please enter a custom category name.',
        ]);

        $category = $request->input('category');
        if ($category === 'other') {
            $category = trim($request->input('custom_category', ''));
            if (empty($category)) {
                return redirect()->back()->withErrors(['custom_category' => 'Please enter a custom category name.'])->withInput();
            }
        }
        if (empty($category)) {
            return redirect()->back()->withErrors(['category' => 'Please select a category for your recipe.'])->withInput();
        }

        $recipe = new SavedRecipe();
        $recipe->user_id = Auth::id();
        $recipe->title = $request->title;
        $recipe->ingredients = $request->ingredients;
        $recipe->instructions = $request->instructions;
        $recipe->summary = $request->summary;
        $recipe->category = $category;
        $recipe->save();

        // Clear generated recipe data from session
        session()->forget(['recipe', 'title', 'ingredients', 'instructions', 'summary']);

        return redirect()->route('recipes.index')->with('success', 'Recipe saved!');
    }

    // Show user's saved recipes
    public function index()
    {
        $recipes = Auth::user()->savedRecipes()->latest()->get();
        return view('saved_recipes.index', compact('recipes'));
    }

    // Delete a saved recipe
    public function destroy($id)
    {
        $recipe = SavedRecipe::findOrFail($id);
        if ($recipe->user_id === Auth::id()) {
            $recipe->delete();
            return redirect()->route('recipes.index')->with('success', 'Recipe deleted!');
        }
        return redirect()->route('recipes.index')->with('error', 'Not authorized!');
    }
}
