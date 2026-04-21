<?php

namespace App\Collector\Parser;

interface ParserStrategyInterface
{
    public function parse(string $rawData): array;
}
