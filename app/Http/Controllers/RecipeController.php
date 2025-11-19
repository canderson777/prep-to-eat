<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use OpenAI\Factory;

class RecipeController extends Controller
{
    public function generate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipe_link' => 'required|string|max:5000',
        ]);

        $apiKey = env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return redirect('/')->with('error', 'OpenAI API key is not configured. Please add OPENAI_API_KEY to your .env file.');
        }

        $client = (new Factory())->withApiKey($apiKey)->make();

        try {
            $result = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $this->buildRecipeExtractionPrompt() . "\n\n" . $validated['recipe_link'],
                    ],
                ],
            ]);

            $aiResponse = $result->choices[0]->message->content ?? 'AI could not parse this recipe.';
        } catch (\Throwable $exception) {
            return redirect('/')->with('error', 'There was a problem generating your recipe. Please try again.');
        }

        $parsed = $this->parseRecipeResponse($aiResponse);

        session([
            'recipe' => $aiResponse,
            'title' => $parsed['title'],
            'ingredients' => $parsed['ingredients'],
            'instructions' => $parsed['instructions'],
            'summary' => $parsed['summary'],
        ]);

        return redirect('/');
    }

    public function ask(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:2000',
        ]);

        $recipeHtml = session('recipe');
        $title = session('title', '');
        $ingredients = session('ingredients', '');
        $instructions = session('instructions', '');
        $summary = session('summary', '');

        if (empty($recipeHtml)) {
            return redirect('/')->with('error', 'Please generate a recipe first, then ask a question.');
        }

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
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $context . "User question: \n" . $question],
                ],
            ]);
            $answer = $result->choices[0]->message->content ?? 'Sorry, I could not generate an answer.';
        } catch (\Throwable $exception) {
            $answer = 'There was a problem generating an answer. Please try again.';
        }

        session([
            'qa_question' => $question,
            'qa_answer' => $answer,
        ]);

        return redirect('/#qa');
    }

    private function buildRecipeExtractionPrompt(): string
    {
        return <<<PROMPT
Extract the recipe's title, ingredients, instructions, and a summary or tip from the following input. 
Format the output in HTML, using <strong> for section headers (e.g., <strong>Ingredients:</strong>), line breaks for steps, and bullet points for ingredients if possible.

- The title should be in bold and larger font.
- Ingredients should be in a <ul> (unordered list) with each ingredient as a <li> item.
- Instructions should be in an <ol> (ordered list) with each step as a <li> item.
- Section headers ("Title", "Ingredients", "Instructions", "Summary") should use <strong> and a line break after.
- The summary should be in a <p> tag after the instructions.
PROMPT;
    }

    /**
     * @return array{
     *     title: string,
     *     ingredients: string,
     *     instructions: string,
     *     summary: string
     * }
     */
    private function parseRecipeResponse(string $aiResponse): array
    {
        $title = '';
        $ingredients = '';
        $instructions = '';
        $summary = '';

        if (
            preg_match('/<h[1-2]>(.*?)<\/h[1-2]>/', $aiResponse, $titleMatch)
            || preg_match('/<strong>Title:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $titleMatch)
            || preg_match('/^(.*?)(?=<strong>|$)/s', $aiResponse, $titleMatch)
        ) {
            $title = trim(strip_tags($titleMatch[1]));
        }

        if (
            preg_match('/<strong>Ingredients:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $ingredientsMatch)
            || preg_match('/<ul>(.*?)<\/ul>/s', $aiResponse, $ingredientsMatch)
        ) {
            $ingredients = trim(strip_tags($ingredientsMatch[1]));
        }

        if (
            preg_match('/<strong>Instructions:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $instructionsMatch)
            || preg_match('/<ol>(.*?)<\/ol>/s', $aiResponse, $instructionsMatch)
        ) {
            $instructions = trim(strip_tags($instructionsMatch[1]));
        }

        if (
            preg_match('/<strong>Summary:<\/strong>(.*?)(?=<strong>|$)/s', $aiResponse, $summaryMatch)
            || preg_match('/<p>(.*?)<\/p>/s', $aiResponse, $summaryMatch)
        ) {
            $summary = trim(strip_tags($summaryMatch[1]));
        }

        return [
            'title' => $title !== '' ? $title : 'Untitled Recipe',
            'ingredients' => $ingredients !== '' ? $ingredients : 'No ingredients found',
            'instructions' => $instructions !== '' ? $instructions : 'No instructions found',
            'summary' => $summary,
        ];
    }
}

