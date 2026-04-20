<?php

namespace App\Collector\Parser;

class PsAuxParser
{
    public function processCommandOutput(array $commandOutputList): array
    {
        $lines = array_merge(...array_map(
            fn($commandOutput) => array_slice(explode(PHP_EOL, $commandOutput), 1),
            $commandOutputList));
        return array_filter($lines, fn($line) => trim($line) !== '');
    }

    public function processLine(string $line): array
    {
       return array_filter(explode(' ', $line), fn($item) => trim($item) !== '');

    }
}
