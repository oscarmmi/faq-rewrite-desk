<?php

use App\Services\Faq\FactKeeper;
use App\Services\Llm\FakeLlmClient;

it('extracts order ids, email addresses, and numbers in their original order', function () {
    $facts = (new FactKeeper)->extract('Order HD-1001 ships in 3 days. Email help@harbordesk.example. Shipping is $4.95.');

    expect($facts)->toBe(['HD-1001', '3', 'help@harbordesk.example', '4.95']);
});

it('does not treat the digits inside an order id as a separate number', function () {
    expect((new FactKeeper)->extract('The tracking ID is HD-1001.'))->toBe(['HD-1001']);
});

it('checks protected facts case-insensitively and requires every fact', function () {
    $factKeeper = new FactKeeper;

    expect($factKeeper->keepsEveryFact('Order hd-1001 is shipping.', ['HD-1001']))->toBeTrue()
        ->and($factKeeper->keepsEveryFact('Order HD-1001 is shipping.', ['HD-1001', '3 days']))->toBeFalse();
});

it('does not accept a longer number as an exact match for a protected number', function () {
    $factKeeper = new FactKeeper;

    expect($factKeeper->keepsEveryFact('Your order arrives in 30 days.', ['3']))->toBeFalse()
        ->and($factKeeper->missingFacts('Your order arrives in 30 days.', ['3']))->toBe(['3']);
});

it('keeps a protected number even when a longer number shares the same digits', function () {
    $source = 'We received 30 messages. Your order will ship in 3 days.';
    $factKeeper = new FactKeeper;
    $facts = $factKeeper->extract($source);
    $rewritten = (new FakeLlmClient)->rewrite($source, $facts);

    expect($factKeeper->keepsEveryFact($rewritten, $facts))->toBeTrue()
        ->and($rewritten)->toContain('3');
});
