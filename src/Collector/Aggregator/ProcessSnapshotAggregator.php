<?php

namespace App\Collector\Aggregator;

use App\Collector\Model\ProcessSnapshot;

class ProcessSnapshotAggregator
{
    public function aggregate(array $probes): ?ProcessSnapshot
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

        if ($numberOfItems === 0) {
            return null;
        }

        return new ProcessSnapshot(
            stime: (int)(array_sum($stime)/$numberOfItems),
            utime: (int)(array_sum($utime)/$numberOfItems),
            numThreads: (int)(array_sum($numThreads)/$numberOfItems),
            vsize: (int)(array_sum($vsize)/$numberOfItems),
            rss: (int)(array_sum($rss)/$numberOfItems),
            pid: $snapshot['pid'],
            command: $snapshot['name'],
            priority: $snapshot['priority'],
            nice: $snapshot['nice'],
            shared: (int)(array_sum($shared)/$numberOfItems),
            text: (int)(array_sum($text)/$numberOfItems),
            data: (int)(array_sum($data)/$numberOfItems),
            starttime: $snapshot['starttime'],
        );
    }
}
