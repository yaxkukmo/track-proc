<?php

namespace App\Collector\Aggregator;

use App\Collector\Model\ProcessSnapshot;

class ProcessSnapshotAggregator
{
    public function aggregate(array $data): array
    {
        $grouped = $this->groupList($data);
        $aggregated = $this->avgValues($grouped);
        return $aggregated;
    }

    private function groupList(array $data): array
    {
        $grouped = [];
        foreach ($data as $snapshot) {
            $grouped[$snapshot->getPid() . '-' . $snapshot->getCommand()][] = $snapshot;
        }
        return $grouped;
    }

    private function avgValues(array $data): array
    {
        return array_values(array_map(function($group) {
            $cpu = []; $mem = []; $vsz = []; $rss = [];

            foreach($group as $snapshot) {
                $cpu[] = $snapshot->getCpu();
                $mem[] = $snapshot->getMem();
                $vsz[] = $snapshot->getVsz();
                $rss[] = $snapshot->getRss();
            }
            $numberOfItems = count($cpu);
            return new ProcessSnapshot(
                cpu: array_sum($cpu)/$numberOfItems,
                mem: array_sum($mem)/$numberOfItems,
                vsz: array_sum($vsz)/$numberOfItems,
                rss: array_sum($rss)/$numberOfItems,
                user: $snapshot->getUser(),
                pid: $snapshot->getPid(),
                command: $snapshot->getCommand()
            );
        }, $data));
    }

}
