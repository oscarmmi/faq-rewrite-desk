<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiLlmClient implements LlmClient
{
    /**
     * @param  list<string>  $facts
     */
    public function rewrite(string $source, array $facts): string
    {
        $key = (string) config('services.gemini.key');
        $model = (string) config('services.gemini.model', 'gemini-2.0-flash');

        $response = Http::acceptJson()
            ->withHeaders(['x-goog-api-key' => $key])
            ->timeout(12)
            ->post('https://generativelanguage.googleapis.com/v1beta/models/'.rawurlencode($model).':generateContent', [
                'system_instruction' => [
                    'parts' => [[
                        'text' => 'Rewrite Harbor Desk FAQ answers as concise, plain-language copy. Preserve every protected fact exactly, including order IDs, email addresses, and numbers. Do not invent facts. Return only the rewritten answer.',
                    ]],
                ],
                'contents' => [[
                    'role' => 'user',
                    'parts' => [[
                        'text' => "Protected facts (must all appear verbatim):\n".implode("\n", $facts)."\n\nFAQ source:\n".$source,
                    ]],
                ]],
                'generationConfig' => ['temperature' => 0.2],
            ])
            ->throw();

        $rewritten = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($rewritten) || trim($rewritten) === '') {
            throw new RuntimeException('Gemini returned an empty rewrite.');
        }

        return trim($rewritten);
    }
}
