<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // <-- Add this line!
use OpenAI\Factory;


Route::get('/', function () {
    return view('home');
});

// ADD THIS:
Route::post('/recipe', function (Request $request) {
    $input = $request->input('recipe_link');

    // Create OpenAI client
    $client = (new Factory())->withApiKey(env('OPENAI_API_KEY'))->make();

    // Build your prompt
    $prompt = <<<PROMPT
Extract the recipe's title, ingredients, instructions, and a summary or tip from the following input. Format the output in HTML using these rules:
- The title should be in bold and larger font.
- Ingredients should be in a <ul> (unordered list) with each ingredient as a <li> item.
- Instructions should be in an <ol> (ordered list) with each step as a <li> item.
- Section headers ("Title", "Ingredients", "Instructions", "Summary") should use <strong> and a line break after.
- The summary should be in a <p> tag after the instructions.

Input:
PROMPT;

$prompt .= "\n\n" . $input;


    $result = $client->chat()->create([
        'model' => 'gpt-3.5-turbo', // Or 'gpt-4' if you have access
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ],
    ]);

    $aiResponse = $result->choices[0]->message->content ?? 'AI could not parse this recipe.';

    return view('home', ['recipe' => $aiResponse]);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
