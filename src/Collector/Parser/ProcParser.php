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
            $tmp = [];
            foreach($contentsByPid as $type => $content) {
                if($content !== null) {
                    $strategy = ($this->parserStrategy)($type);
                    $tmp[] = $strategy->parse($content);
                }
            }
            if(!empty($tmp)) {
                $windowData[$pid][] = array_merge($tmp[0], $tmp[1]);
            }
        }
    }
}
