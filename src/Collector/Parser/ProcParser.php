<?php

namespace App\Collector\Parser;

class ProcParser
{
    public function __construct(private Strategy $parserStrategy)
    {
    }

    public function __invoke(array $fileContentsList, array &$windowData): void
    {
        foreach($fileContentsList as $pid => $contentsByPid) {
            foreach($contentsByPid as $type => $content) {
                $strategy = ($this->parserStrategy)($type);
                if($content !== null) {
                    $windowData[$pid][$type][] = $strategy->parse($content);
                }
            }
        }
    }
}
