<?php

namespace App\Collector\Model;

class ProcessSnapshot
{
    public function __construct(
        private int $pid,
        private int $vsize,
        private int $rss,
        private string $command,
        private int $numThreads,
        private int $utime,
        private int $stime,
        private int $priority,
        private int $nice,
        private int $shared,
        private int $text,
        private int $data
    )
    { }

    public function getData(): int
    {
        return $this->data;
    }

    public function getText(): int
    {
        return $this->text;
    }


    public function getShared(): int
    {
        return $this->shared;
    }

    public function getNice(): int
    {
        return $this->nice;
    }


    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getStime(): int
    {
        return $this->stime;
    }

    public function getUtime(): int
    {
        return $this->utime;
    }

    public function getNumThreads(): int
    {
        return $this->numThreads;
    }

    public function getPid(): int
    {
        return $this->pid;
    }

    public function getCpu(): float
    {
        return $this->cpu;
    }

    public function getMem(): float
    {
        return $this->mem;
    }

    public function getVsize(): int
    {
        return $this->vsize;
    }

    public function getRss(): int
    {
        return $this->rss;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

}
