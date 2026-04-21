<?php


namespace App\Collector\Parser;

class StatmParser implements ParserStrategyInterface
{

    public function parse(string $rawData): array
    {
        $data = explode(" ", trim($rawData));
        return [
            'size' => $data[0],
            'resident' => $data[1],
            'shared' => $data[2],
            'text' => $data[3],
            'data' => $data[4]
        ];
    }

}
