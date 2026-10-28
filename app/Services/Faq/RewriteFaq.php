<?php

namespace App\Services\Faq;

use App\Models\Rewrite;
use App\Models\RewriteJob;
use App\Services\Llm\FakeLlmClient;
use App\Services\Llm\LlmClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class RewriteFaq
{
    public function __construct(
        private readonly FactKeeper $factKeeper,
        private readonly LlmClient $llmClient,
        private readonly FakeLlmClient $fallbackClient,
    ) {}

    public function run(RewriteJob $job): Rewrite
    {
        $facts = $this->factKeeper->extract($job->source);

        try {
            $rewritten = $this->llmClient->rewrite($job->source, $facts);
        } catch (Throwable $exception) {
            Log::warning('FAQ rewrite provider failed; using local fallback.', [
                'rewrite_job_id' => $job->id,
                'exception' => $exception::class,
            ]);

            $rewritten = $this->fallbackClient->rewrite($job->source, $facts);
        }

        $isValid = $this->factKeeper->keepsEveryFact($rewritten, $facts);

        $job->update(['status' => $isValid ? 'complete' : 'invalid']);

        return $job->rewrite()->create([
            'rewritten' => $rewritten,
            'kept_facts' => $facts,
            'is_valid' => $isValid,
        ]);
    }
}
