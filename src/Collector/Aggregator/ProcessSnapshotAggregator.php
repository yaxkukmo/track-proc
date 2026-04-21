<?php

namespace App\Collector\Aggregator;

use App\Collector\Model\ProcessSnapshot;

class ProcessSnapshotAggregator
{
    public function aggregateStat(array $probes): ProcessSnapshot
    {
        $stime = $utime = $numThreads = $vsize = $rss = [];

        foreach($probes as $snapshot) {
            $stime[] = $snapshot['stime'];
            $utime[] = $snapshot['utime'];
            $numThreads[] = $snapshot['num_threads'];
            $vsize[] = $snapshot['vsize'];
            $rss[] = $snapshot['rss'];
        }
        $numberOfItems = count($rss);
        return new ProcessSnapshot(
            stime: array_sum($stime)/$numberOfItems,
            utime: array_sum($utime)/$numberOfItems,
            mumThreads: array_sum($numThreads)/$numberOfItems,
            vsize: array_sum($vsize)/$numberOfItems,
            rss: array_sum($rss)/$numberOfItems,
            pid: $snapshot['pid'],
            command: $snapshot['name'],
            priority: $snapshot['priority'],
            nice: $snapshot['nice']
        );
    }

    public function aggregateStatm(array $probes): ProcessSnapshot
    {
        $shared = $text = $data = [];
        foreach ($probes as $snapshot) {
            $shared[] = $snapshot['shared'];
            $text[] = $snapshot['text'];
            $data[] = $snapshot['data'];
        }
        $numberOfItems = count($data);
        return new ProcessSnapshot(
            shared: array_sum($shared)/$numberOfItems,
            text: array_sum($text)/$numberOfItems,
            data: array_sum($data)/$numberOfItems,
        );
    }

}
