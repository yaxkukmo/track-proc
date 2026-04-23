<?php

namespace App\Collector\Parser;

class CmdlineParser implements ParserStrategyInterface
{
    public function parse(string $rawData): array
    {
        $data = explode("@", trim($rawData));
        return ['cmdline' => isset($data[0]) ? $data[0] : []];
    }
}
