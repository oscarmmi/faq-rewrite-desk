<?php

namespace App\Services\Faq;

class FactKeeper
{
    /**
     * @return list<string>
     */
    public function extract(string $source): array
    {
        $matches = [];
        $orderIds = [];

        foreach ([
            'order_id' => '/\b[A-Z]{1,10}-\d{2,}\b/i',
            'email' => '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i',
            'number' => '/(?<![\p{L}\p{N}])\d+(?:[,.]\d+)*(?![\p{L}\p{N}])/u',
        ] as $type => $pattern) {
            preg_match_all($pattern, $source, $found, PREG_OFFSET_CAPTURE);

            foreach ($found[0] as [$value, $offset]) {
                $end = $offset + strlen($value);

                if ($type === 'order_id') {
                    $orderIds[] = [$offset, $end];
                }

                if ($type === 'number' && collect($orderIds)->contains(
                    fn (array $range): bool => $offset >= $range[0] && $end <= $range[1],
                )) {
                    continue;
                }

                $matches[] = ['value' => $value, 'offset' => $offset];
            }
        }

        usort($matches, fn (array $left, array $right): int => $left['offset'] <=> $right['offset']);

        $facts = [];
        $seen = [];

        foreach ($matches as $match) {
            $key = mb_strtolower($match['value']);

            if (! isset($seen[$key])) {
                $facts[] = $match['value'];
                $seen[$key] = true;
            }
        }

        return $facts;
    }

    /**
     * @param  list<string>  $facts
     */
    public function keepsEveryFact(string $rewritten, array $facts): bool
    {
        return $this->missingFacts($rewritten, $facts) === [];
    }

    /**
     * @param  list<string>  $facts
     * @return list<string>
     */
    public function missingFacts(string $rewritten, array $facts): array
    {
        $rewrittenFacts = array_fill_keys(array_map(
            fn (string $fact): string => mb_strtolower($fact),
            $this->extract($rewritten),
        ), true);

        return array_values(array_filter(
            $facts,
            fn (string $fact): bool => ! isset($rewrittenFacts[mb_strtolower($fact)]),
        ));
    }
}
