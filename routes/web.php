<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use OpenAI\Factory;

Route::get('/', function () {
    return view('home');
});

Route::post('/recipe', function (Request $request) {
    $input = $request->input('recipe_link');

    $client = (new Factory())->withApiKey(env('OPENAI_API_KEY'))->make();

    $prompt = "Extract the recipe's title, ingredients, instructions, and a summary or tip from the following input. Format the output in HTML. Use <h2 class='recipe-title'> for the recipe title. For section headers (Ingredients, Instructions, Summary), use <strong> and put each on their own line. Use <ul> for ingredients and <ol> for instructions.";


    $result = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt . "\n\nRECIPE:\n" . $input,
            ],
        ],
    ]);

    $aiResponse = $result->choices[0]->message->content ?? 'AI could not parse this recipe.';

    return view('home', ['recipe' => $aiResponse]);
});
