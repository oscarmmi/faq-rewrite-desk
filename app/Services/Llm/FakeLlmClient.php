<?php

namespace App\Services\Llm;

use App\Services\Faq\FactKeeper;

class FakeLlmClient implements LlmClient
{
    /**
     * @param  list<string>  $facts
     */
    public function rewrite(string $source, array $facts): string
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', trim($source)) ?: [];
        $shortVersion = $sentences[0] ?? trim($source);
        $shortVersion = preg_replace('/\s+/', ' ', $shortVersion) ?? $shortVersion;

        $missingFacts = (new FactKeeper)->missingFacts($shortVersion, $facts);

        $rewrite = 'In short: '.$shortVersion;

        if ($missingFacts !== []) {
            $rewrite .= ' Key details: '.implode(', ', $missingFacts).'.';
        }

        return $rewrite;
    }
}
