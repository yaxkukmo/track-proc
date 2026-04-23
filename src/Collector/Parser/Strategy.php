<?php

namespace App\Collector\Parser;

class Strategy
{
    public function __construct(
        private StatParser $statStrategy,
        private StatmParser $statmStrategy,
        private CmdlineParser $cmdlineStrategy
    )
    { }

    public function __invoke(string $type): ParserStrategyInterface
    {
        return match($type) {
            'stat' => $this->statStrategy,
            'statm' => $this->statmStrategy,
            'cmdline' => $this->cmdlineStrategy,
        };
    }
}
