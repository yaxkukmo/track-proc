<?php

namespace App\Collector\Parser;

class StatParser implements ParserStrategyInterface
{
    public function parse(string $rawData): array
    {
        $lineWithStrippedName = trim(substr($rawData, strrpos($rawData, ')') + 1));
        $pid = substr($rawData, 0, strpos($rawData, " "));
        $data = array_merge([$pid, $this->getName($rawData)], explode(" ", $lineWithStrippedName));

        return [
            'pid' => $data[0],
            'name' => $data[1],
            'state' => $data[2],
            'utime' => $data[13],
            'stime' => $data[14],
            'priority' => $data[17],
            'nice' => $data[18],
            'num_threads' => $data[19],
            'starttime' => $data[21],
            'vsize' => $data[22],
            'rss' => $data[23]
        ];
    }

    private function getName(string $line): string
    {
        preg_match("/\(.+\)/", $line, $name);
        return trim($name[0], '()');
    }
}
