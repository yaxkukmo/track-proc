<?php

namespace App\Collector\Aggregator;

class DeltaCalculator
{
    public function __invoke(int $previousValue, int $currentValue): array
    {
        return $currentValue - $previousValue;

    }
}
