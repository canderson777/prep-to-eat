<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedRecipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $recipes = Auth::user()
            ->savedRecipes()
            ->with(['shares' => function ($query) {
                $query->where(function ($inner) {
                    $inner->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })->orderByDesc('created_at');
            }])
            ->latest()
            ->get();

        $upcomingPlans = Auth::user()->mealPlanEntries()
            ->with('recipe')
            ->whereDate('planned_for', '>=', now()->startOfDay())
            ->orderBy('planned_for')
            ->take(7)
            ->get();

        return view('saved_recipes.index', compact('recipes', 'upcomingPlans'));
    }

    // Update an existing recipe
    public function update(Request $request, $id)
    {
        $recipe = SavedRecipe::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:saved_recipes,title,' . $recipe->id . ',id,user_id,' . Auth::id(),
            'category' => 'required|string|max:255',
            'custom_category' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
        ], [
            'category.required' => 'Please select a category for your recipe.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('category') === 'other' && trim($request->input('custom_category', '')) === '') {
                $validator->errors()->add('custom_category', 'Please enter a custom category name.');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->route('recipes.index')
                ->withErrors($validator)
                ->withInput($request->all() + ['editing_id' => $recipe->id]);
        }

        $category = $request->input('category');
        if ($category === 'other') {
            $category = trim($request->input('custom_category', ''));
        }

        $recipe->title = $request->input('title');
        $recipe->category = $category;
        $recipe->summary = $request->input('summary');
        $recipe->save();

        return redirect()->route('recipes.index')->with('success', 'Recipe updated!');
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
