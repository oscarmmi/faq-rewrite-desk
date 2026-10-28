<?php

use App\Models\FaqExample;
use App\Models\RewriteJob;
use App\Services\Llm\FakeLlmClient;
use App\Services\Llm\LlmClient;
use Inertia\Testing\AssertableInertia as Assert;

it('uses the local fake client when no Gemini key is configured', function () {
    config()->set('services.gemini.key', null);

    expect(app(LlmClient::class))->toBeInstanceOf(FakeLlmClient::class);
});

it('lets a guest rewrite an FAQ and verifies the protected facts', function () {
    app()->instance(LlmClient::class, new FakeLlmClient);

    $response = $this->post('/rewrite', [
        'source' => 'Order HD-1001 shipped today. Email help@harbordesk.example after 3 business days if it has not arrived.',
    ]);

    $response->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->where('latest.rewrite.missing_facts', []));

    $job = RewriteJob::query()->with('rewrite')->firstOrFail();

    expect($job->status)->toBe('complete')
        ->and($job->rewrite->rewritten)->toContain('HD-1001')
        ->and($job->rewrite->kept_facts)->toBe(['HD-1001', 'help@harbordesk.example', '3'])
        ->and($job->rewrite->is_valid)->toBeTrue();
});

it('marks the rewrite invalid when the model drops a protected fact', function () {
    app()->instance(LlmClient::class, new class implements LlmClient
    {
        public function rewrite(string $source, array $facts): string
        {
            return 'Your order is on its way.';
        }
    });

    $this->post('/rewrite', [
        'source' => 'Your order HD-1001 ships in 3 business days.',
    ])->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->where('latest.rewrite.missing_facts', ['HD-1001', '3']));

    $job = RewriteJob::query()->with('rewrite')->firstOrFail();

    expect($job->status)->toBe('invalid')
        ->and($job->rewrite->is_valid)->toBeFalse();
});

it('falls back to a safe local rewrite when the model throws', function () {
    app()->instance(LlmClient::class, new class implements LlmClient
    {
        public function rewrite(string $source, array $facts): string
        {
            throw new RuntimeException('Provider unavailable.');
        }
    });

    $this->post('/rewrite', [
        'source' => 'Order HD-1001 shipped today and will arrive in 3 business days.',
    ])->assertOk();

    $job = RewriteJob::query()->with('rewrite')->firstOrFail();

    expect($job->rewrite->rewritten)->toContain('HD-1001')
        ->and($job->rewrite->is_valid)->toBeTrue();
});

it('shows the three seeded FAQ examples on the guest desk', function () {
    $this->seed();

    $this->get('/')->assertOk();

    expect(FaqExample::query()->count())->toBe(3)
        ->and(FaqExample::query()->pluck('source')->every(fn (string $source): bool => preg_match('/\d/', $source) === 1))->toBeTrue();
});
