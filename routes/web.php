<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeShareController;
use App\Http\Controllers\SavedRecipeController;
use App\Http\Controllers\SavedRecipeImportController;
use App\Models\SavedRecipe;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use OpenAI\Factory;

// Homepage
Route::get('/', function () {
    // Optional clear flag to reset current recipe from session
    if (request()->boolean('clear')) {
        session()->forget(['recipe', 'title', 'ingredients', 'instructions', 'summary', 'qa_question', 'qa_answer']);
        return redirect('/');
    }
    return view('home');
});

Route::get('/recipes', function (Request $request) {
    $featuredRecipes = [
        [
            'title' => 'Chocolate Nut Milkshake',
            'category' => 'Breakfast',
            'summary' => 'Protein-rich cacao, almond butter, and coconut milk blended into a creamy start to your day.',
            'image' => 'https://images.unsplash.com/photo-1497534446932-c925b458314e?auto=format&fit=crop&w=900&q=80',
            'url' => 'https://drhyman.com/blogs/content/chocolate-nut-milkshake',
            'tags' => ['High Protein', 'Dairy-Free'],
        ],
        [
            'title' => 'Mushroom Walnut Stuffed Eggplant',
            'category' => 'Dinner',
            'summary' => 'Roasted eggplant filled with savory mushrooms, walnuts, and herbs for a hearty plant-based meal.',
            'image' => 'https://images.unsplash.com/photo-1604908176997-12518821c635?auto=format&fit=crop&w=900&q=80',
            'url' => 'https://drhyman.com/blogs/content/mushroom-walnut-stuffed-eggplant',
            'tags' => ['Plant-Based', 'Gluten-Free'],
        ],
        [
            'title' => 'Herbed Sardine Cakes with Avocado Salad',
            'category' => 'Dinner',
            'summary' => 'Omega-3 rich sardine cakes served with a refreshing avocado and broccoli salad.',
            'image' => 'https://images.unsplash.com/photo-1612874471570-ec9cbf5c0077?auto=format&fit=crop&w=900&q=80',
            'url' => 'https://drhyman.com/blogs/content/herbed-sardine-cakes-with-avocado-broccoli-salad',
            'tags' => ['High Protein', 'Low Carb'],
        ],
        [
            'title' => 'Bacon, Asparagus & Shallot Frittata',
            'category' => 'Breakfast',
            'summary' => 'An easy frittata packed with spring vegetables and smoky bacon for any time of day.',
            'image' => 'https://images.unsplash.com/photo-1508739826987-b79cd8b7da12?auto=format&fit=crop&w=900&q=80',
            'url' => 'https://drhyman.com/blogs/content/bacon-asparagus-shallot-frittata',
            'tags' => ['Meal Prep', 'Gluten-Free'],
        ],
        [
            'title' => 'Feel-Good Pesto Steak Salad',
            'category' => 'Salad',
            'summary' => 'Grass-fed steak sliced over greens with basil pesto, roasted tomatoes, and crisp veggies.',
            'image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=900&q=80',
            'url' => 'https://drhyman.com/blogs/content/feel-good-pesto-steak-salad',
            'tags' => ['High Protein', 'Weeknight Friendly'],
        ],
        [
            'title' => 'Lemon Herb Chicken & Veggies',
            'category' => 'Dinner',
            'summary' => 'Sheet-pan roasted chicken with bright citrus, seasonal vegetables, and fresh herbs.',
            'image' => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?auto=format&fit=crop&w=900&q=80',
            'url' => 'https://drhyman.com/blogs/content/lemon-herb-chicken-veggies',
            'tags' => ['Family Favorite', 'One-Pan'],
        ],
    ];

    $query = SavedRecipe::query()
        ->with('tags')
        ->select(['id', 'title', 'summary', 'ingredients', 'created_at']);
    
    // Add filtering by tags
    if ($request->has('tags') && is_array($request->tags) && !empty($request->tags)) {
        $query->whereHas('tags', function ($q) use ($request) {
            $q->whereIn('recipe_tags.id', $request->tags);
        });
    }
    
    $communityRecipes = $query->latest()->take(6)->get();
    
    // Get all available tags for filter UI
    $allTags = \App\Models\RecipeTag::orderBy('name')->get();
    
    return view('recipes.index', [
        'featuredRecipes' => $featuredRecipes,
        'communityRecipes' => $communityRecipes,
        'allTags' => $allTags,
        'selectedTags' => $request->input('tags', []),
    ]);
})->name('recipes.catalog');

// =========== AI Recipe Generation ============= //
Route::post('/recipe', function (Request $request) {
    $input = $request->input('recipe_link');

    // Check if OpenAI API key is configured
    $apiKey = env('OPENAI_API_KEY');
    if (empty($apiKey)) {
        return redirect('/')->with('error', 'OpenAI API key is not configured. Please add OPENAI_API_KEY to your .env file.');
    }

    // Create the OpenAI client using the Factory
    $client = (new Factory())->withApiKey($apiKey)->make();

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

// =========== AI Q&A ABOUT CURRENT RECIPE ============= //
Route::post('/recipe/ask', function (Request $request) {
    $validated = $request->validate([
        'question' => 'required|string|max:2000',
    ]);

    // Ensure there is a recipe context available
    $recipeHtml = session('recipe');
    $title = session('title', '');
    $ingredients = session('ingredients', '');
    $instructions = session('instructions', '');
    $summary = session('summary', '');

    if (empty($recipeHtml)) {
        return redirect('/')->with('error', 'Please generate a recipe first, then ask a question.');
    }

    // Check if OpenAI API key is configured
    $apiKey = env('OPENAI_API_KEY');
    if (empty($apiKey)) {
        return redirect('/')->with('error', 'OpenAI API key is not configured. Please add OPENAI_API_KEY to your .env file.');
    }

    $client = (new Factory())->withApiKey($apiKey)->make();

    $system = 'You are a helpful culinary assistant. Use the provided recipe context to answer the user\'s question. '
        .'Be concise and practical. Provide substitutions with measurements and notes about taste/texture impacts. '
        .'Offer dietary alternatives when requested. Provide precise temperatures/timings when converting. '
        .'If food safety is involved, err on the side of caution.';

    $context = "Recipe Context\n"
        ."Title: {$title}\n\n"
        ."Ingredients:\n{$ingredients}\n\n"
        ."Instructions:\n{$instructions}\n\n"
        .(!empty($summary) ? "Summary:\n{$summary}\n\n" : '');

    $question = $validated['question'];

    try {
        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [ 'role' => 'system', 'content' => $system ],
                [ 'role' => 'user', 'content' => $context."User question: \n".$question ],
            ],
        ]);
        $answer = $result->choices[0]->message->content ?? 'Sorry, I could not generate an answer.';
    } catch (\Throwable $e) {
        $answer = 'There was a problem generating an answer. Please try again.';
    }

    session([
        'qa_question' => $question,
        'qa_answer' => $answer,
    ]);

    return redirect('/#qa');
})->middleware(['web']);

// =========== RECIPE ROUTES ============= //
Route::post('/recipes/save', [SavedRecipeController::class, 'store'])->name('recipes.save')->middleware('auth');
Route::get('/my-recipes', [SavedRecipeController::class, 'index'])->name('recipes.index')->middleware('auth');
Route::put('/recipes/{id}', [SavedRecipeController::class, 'update'])->name('recipes.update')->middleware('auth');
Route::delete('/recipes/{id}', [SavedRecipeController::class, 'destroy'])->name('recipes.destroy')->middleware('auth');
Route::post('/recipes/{recipe}/share', [RecipeShareController::class, 'store'])->name('recipes.share.store')->middleware('auth');
Route::delete('/shares/{share}', [RecipeShareController::class, 'destroy'])->name('recipes.share.destroy')->middleware('auth');

// Import saved recipes from guest/local (auth only)
Route::post('/recipes/import-guest', [SavedRecipeImportController::class, 'import'])
    ->middleware('auth')
    ->name('recipes.import');

// Guest local recipes view (no auth)
Route::get('/my-local-recipes', function () {
    return view('saved_recipes.index_local');
})->name('recipes.local');

// Profile routes, dashboard, etc...
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/meal-plan', [MealPlanController::class, 'index'])->name('meal-plan.index');
    Route::post('/meal-plan', [MealPlanController::class, 'store'])->name('meal-plan.store');
    Route::delete('/meal-plan/{entry}', [MealPlanController::class, 'destroy'])->name('meal-plan.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/share/{token}', [RecipeShareController::class, 'show'])->name('recipes.share.show');
Route::post('/chat/ask', [ChatController::class, 'respond'])->name('chat.respond');

require __DIR__.'/auth.php';
