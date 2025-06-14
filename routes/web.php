<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedRecipeController;
use App\Models\SavedRecipe;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use OpenAI\Factory;

// Homepage
Route::get('/', function () {
    return view('home');
});

// =========== AI Recipe Generation ============= //
Route::post('/recipe', function (Request $request) {
    $input = $request->input('recipe_link');

    // Create the OpenAI client using the Factory
    $client = (new Factory())->withApiKey(env('OPENAI_API_KEY'))->make();

    $prompt = <<<PROMPT
Extract the recipe's title, ingredients, instructions, and a summary or tip from the following input. 
Format the output in HTML, using <strong> for section headers (e.g., <strong>Ingredients:</strong>), line breaks for steps, and bullet points for ingredients if possible.

- The title should be in bold and larger font.
- Ingredients should be in a <ul> (unordered list) with each ingredient as a <li> item.
- Instructions should be in an <ol> (ordered list) with each step as a <li> item.
- Section headers ("Title", "Ingredients", "Instructions", "Summary") should use <strong> and a line break after.
- The summary should be in a <p> tag after the instructions.
PROMPT;

    $result = $client->chat()->create([
        'model' => 'gpt-3.5-turbo', // Or 'gpt-4' if available
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt . "\n\n" . $input,
            ],
        ],
    ]);

    $aiResponse = $result->choices[0]->message->content ?? 'AI could not parse this recipe.';

    // Extract title, ingredients, instructions, and summary from the AI response
    $title = '';
    $ingredients = '';
    $instructions = '';
    $summary = '';

    // Extract title (usually the first line or surrounded by h1/h2 tags)
    if (preg_match('/<h[1-2]>(.*?)<\/h[1-2]>/', $aiResponse, $titleMatch) || 
        preg_match('/<strong>Title:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $titleMatch) || 
        preg_match('/^(.*?)(?=<strong>|$)/s', $aiResponse, $titleMatch)) {
        $title = trim(strip_tags($titleMatch[1]));
    }

    // Extract ingredients
    if (preg_match('/<strong>Ingredients:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $ingredientsMatch) || 
        preg_match('/<ul>(.*?)<\/ul>/s', $aiResponse, $ingredientsMatch)) {
        $ingredients = trim(strip_tags($ingredientsMatch[1]));
    }

    // Extract instructions
    if (preg_match('/<strong>Instructions:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $instructionsMatch) || 
        preg_match('/<ol>(.*?)<\/ol>/s', $aiResponse, $instructionsMatch)) {
        $instructions = trim(strip_tags($instructionsMatch[1]));
    }

    // Extract summary
    if (preg_match('/<strong>Summary:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $summaryMatch) || 
        preg_match('/<p>(.*?)<\/p>/s', $aiResponse, $summaryMatch)) {
        $summary = trim(strip_tags($summaryMatch[1]));
    }

    // Store in session
    session([
        'recipe' => $aiResponse,
        'title' => $title ?: 'Untitled Recipe',
        'ingredients' => $ingredients ?: 'No ingredients found',
        'instructions' => $instructions ?: 'No instructions found',
        'summary' => $summary ?: '',
    ]);

    // Redirect to homepage
    return redirect('/');
})->middleware(['web']);

// Prevent GET /recipe from causing a 405 error by redirecting to the homepage
Route::get('/recipe', function () {
    return redirect('/');
});

// =========== RECIPE ROUTES ============= //
Route::post('/recipes/save', [SavedRecipeController::class, 'store'])->name('recipes.save')->middleware('auth');
Route::get('/my-recipes', [SavedRecipeController::class, 'index'])->name('recipes.index')->middleware('auth');
Route::delete('/recipes/{id}', [SavedRecipeController::class, 'destroy'])->name('recipes.destroy')->middleware('auth');

// Profile routes, dashboard, etc...
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
