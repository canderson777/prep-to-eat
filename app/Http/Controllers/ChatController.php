<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenAI\Factory;

class ChatController extends Controller
{
    public function respond(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'ingredients' => 'nullable|string|max:2000',
        ]);

        $prompt = $this->buildPrompt($validated['message'], $validated['ingredients'] ?? null, $request);

        try {
            $client = (new Factory())->withApiKey(env('OPENAI_API_KEY'))->make();
            $result = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are PrepToEat, a concise culinary assistant. Provide cooking substitutions, technique tips, '
                            .'and recipe suggestions based on the user\'s pantry. Suggest practical next steps.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            $answer = trim($result->choices[0]->message->content ?? 'Sorry, I could not come up with an answer.');
        } catch (\Throwable $exception) {
            $answer = 'I had trouble reaching the cooking assistant. Please try again soon.';
        }

        return response()->json([
            'reply' => $answer,
        ]);
    }

    protected function buildPrompt(string $message, ?string $ingredients, Request $request): string
    {
        $parts = [];

        if ($request->user()) {
            $savedCount = $request->user()->savedRecipes()->count();
            $parts[] = "The user has {$savedCount} saved recipes in their cookbook.";
        }

        if ($ingredients) {
            $parts[] = "Available ingredients: {$ingredients}.";
        }

        $parts[] = "Question or request: {$message}.";

        return implode("\n", $parts);
    }
}
