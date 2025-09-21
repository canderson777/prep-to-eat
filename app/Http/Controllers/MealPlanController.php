<?php

namespace App\Http\Controllers;

use App\Models\MealPlanEntry;
use App\Models\SavedRecipe;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealPlanController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);

        $currentMonth = $today->copy()->startOfMonth();
        $monthStart = $currentMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $monthEnd = $currentMonth->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $entries = $user->mealPlanEntries()
            ->with(['recipe'])
            ->whereBetween('planned_for', [$monthStart, $monthEnd])
            ->orderBy('planned_for')
            ->get();

        $entriesByDate = $entries->groupBy(fn (MealPlanEntry $entry) => $entry->planned_for->toDateString());

        $weekEntries = $entries->filter(function (MealPlanEntry $entry) use ($startOfWeek, $endOfWeek) {
            return $entry->planned_for->betweenIncluded($startOfWeek, $endOfWeek);
        })->groupBy(fn (MealPlanEntry $entry) => $entry->planned_for->toDateString());

        $weekPeriod = CarbonPeriod::create($startOfWeek, $endOfWeek);
        $monthPeriod = CarbonPeriod::create($monthStart, '1 day', $monthEnd);

        $recipes = $user->savedRecipes()->orderBy('title')->get();

        return view('meal_plan.index', [
            'weekPeriod' => $weekPeriod,
            'weekEntries' => $weekEntries,
            'monthPeriod' => $monthPeriod,
            'entriesByDate' => $entriesByDate,
            'currentMonth' => $currentMonth,
            'recipes' => $recipes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'planned_for' => 'required|date',
            'saved_recipe_id' => 'required|exists:saved_recipes,id',
            'meal_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);

        $recipe = SavedRecipe::where('id', $validated['saved_recipe_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        MealPlanEntry::create([
            'user_id' => $request->user()->id,
            'saved_recipe_id' => $recipe->id,
            'planned_for' => $validated['planned_for'],
            'meal_type' => $validated['meal_type'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Recipe added to your meal plan.');
    }

    public function destroy(Request $request, MealPlanEntry $entry): RedirectResponse
    {
        if ($entry->user_id !== $request->user()->id) {
            abort(403);
        }

        $entry->delete();

        return redirect()->back()->with('success', 'Meal plan entry removed.');
    }
}
