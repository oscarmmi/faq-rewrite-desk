<?php

namespace App\Http\Controllers;

use App\Models\FaqExample;
use App\Models\RewriteJob;
use App\Services\Faq\FactKeeper;
use App\Services\Faq\RewriteFaq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RewriteDeskController extends Controller
{
    public function index(FactKeeper $factKeeper): Response
    {
        return $this->page($factKeeper);
    }

    public function store(Request $request, RewriteFaq $rewriteFaq, FactKeeper $factKeeper): Response|RedirectResponse
    {
        $validated = $request->validate([
            'source' => ['required', 'string', 'min:10', 'max:10000'],
        ]);

        $job = RewriteJob::query()->create([
            'source' => $validated['source'],
            'status' => 'processing',
        ]);

        $rewriteFaq->run($job);

        return $this->page($factKeeper);
    }

    private function page(FactKeeper $factKeeper): Response
    {
        $jobs = RewriteJob::query()
            ->with('rewrite')
            ->latest()
            ->limit(8)
            ->get();

        $jobs->each(function (RewriteJob $job) use ($factKeeper): void {
            $rewrite = $job->rewrite;

            if ($rewrite) {
                $rewrite->setAttribute('missing_facts', $factKeeper->missingFacts(
                    $rewrite->rewritten,
                    $rewrite->kept_facts,
                ));
            }
        });

        return Inertia::render('Welcome', [
            'examples' => FaqExample::query()->orderBy('id')->get(['id', 'title', 'source']),
            'latest' => $jobs->first(),
            'history' => $jobs->skip(1)->values(),
            'provider' => filled(config('services.gemini.key')) ? 'Gemini' : 'Local demo',
        ]);
    }
}
