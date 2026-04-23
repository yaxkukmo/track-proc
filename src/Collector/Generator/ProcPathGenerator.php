<?php

namespace App\Collector\Generator;

class ProcPathGenerator
{
    private const PROC = '/proc';
    private const FILES_TO_READ = ['stat', 'statm', 'cmdline'];

    public function __invoke(): array
    {
        $pids = preg_grep('/^\d+$/', scandir(self::PROC));
        $pids = array_combine(array_values($pids), array_keys($pids));
         array_walk(
            $pids,
            fn(&$value, $pid): array => $value = $this->generatePaths($pid)
        );
        return $pids;
    }

    private function generatePaths($pid): array
    {
        return array_combine(
            self::FILES_TO_READ,
            array_map(
                fn($file) => self::PROC . "/${pid}/${file}",
                self::FILES_TO_READ
            )
        );
    }
}
