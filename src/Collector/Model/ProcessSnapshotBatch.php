<?php

namespace App\Collector\Model;

class ProcessSnapshotBatch
{
    public function __construct(
        private readonly array $snapshots,
        private readonly int $collectedAt
    )
    { }

    public function getSnapshotList(): array
    {
        return $this->snapshots;
    }

    public function getCollectedAt(): int
    {
        return $this->collectedAt;
    }
}
