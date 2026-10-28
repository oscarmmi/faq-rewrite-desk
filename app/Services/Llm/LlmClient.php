<?php

namespace App\Services\Llm;

interface LlmClient
{
    /**
     * @param  list<string>  $facts
     */
    public function rewrite(string $source, array $facts): string;
}
