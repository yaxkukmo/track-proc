<?php

namespace App\Collector\Model;

class ProcessSnapshot
{
    public function __construct(
        private string $user,
        private int $pid,
        private float $cpu,
        private float $mem,
        private int $vsz,
        private int $rss,
        private string $command
    )
    { }

    public function getUser(): string
    {
        return $this->user;
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

    public function getVsz(): int
    {
        return $this->vsz;
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
