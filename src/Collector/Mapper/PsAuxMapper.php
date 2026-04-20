<?php

namespace App\Collector\Mapper;

use App\Collector\Model\ProcessSnapshot;

class PsAuxMapper
{
    public function toProcessSnapshot(array $rawData): ProcessSnapshot
    {
        return new ProcessSnapshot(
            user: current($rawData),
            pid:  next($rawData),
            cpu: next($rawData),
            mem: next($rawData),
            vsz: (int) next($rawData),
            rss: (int) next($rawData),
            command: implode(' ', array_slice($rawData, 10))
        );
    }
}
