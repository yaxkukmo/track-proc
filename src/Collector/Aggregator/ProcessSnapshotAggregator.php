<?php

namespace App\Collector\Aggregator;

use App\Collector\Model\ProcessSnapshot;

class ProcessSnapshotAggregator
{
    public function aggregate(array $probes): ProcessSnapshot
    {
        $stime = $utime = $numThreads = $vsize = $rss = $shared = $text = $data = [];

        foreach($probes as $key => $snapshot) {
            if ($key === 0) continue;
            $stime[] = $snapshot['stime'];
            $utime[] = $snapshot['utime'];
            $numThreads[] = $snapshot['num_threads'];
            $vsize[] = $snapshot['vsize'];
            $rss[] = $snapshot['rss'];
            $shared[] = $snapshot['shared'];
            $text[] = $snapshot['text'];
            $data[] = $snapshot['data'];
        }

        $numberOfItems = count($rss);

        return new ProcessSnapshot(
            stime: array_sum($stime)/$numberOfItems,
            utime: array_sum($utime)/$numberOfItems,
            numThreads: array_sum($numThreads)/$numberOfItems,
            vsize: array_sum($vsize)/$numberOfItems,
            rss: array_sum($rss)/$numberOfItems,
            pid: $snapshot['pid'],
            command: $snapshot['name'],
            priority: $snapshot['priority'],
            nice: $snapshot['nice'],
            shared: array_sum($shared)/$numberOfItems,
            text: array_sum($text)/$numberOfItems,
            data: array_sum($data)/$numberOfItems,
        );
    }
}
