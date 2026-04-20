<?php

namespace App\Entity;

use App\Repository\MetricRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MetricRepository::class)]
class Metric
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pid')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Process $process = null;

    #[ORM\Column]
    private ?int $pid = null;

    #[ORM\Column]
    private ?float $cpu = null;

    #[ORM\Column]
    private ?float $mem = null;

    #[ORM\Column]
    private ?int $vsz = null;

    #[ORM\Column]
    private ?int $rss = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $collectedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getProcess(): ?Process
    {
        return $this->process;
    }

    public function setProcess(?Process $process): static
    {
        $this->process = $process;

        return $this;
    }

    public function getPid(): ?int
    {
        return $this->pid;
    }

    public function setPid(int $pid): static
    {
        $this->pid = $pid;

        return $this;
    }

    public function getCpu(): ?float
    {
        return $this->cpu;
    }

    public function setCpu(float $cpu): static
    {
        $this->cpu = $cpu;

        return $this;
    }

    public function getMem(): ?float
    {
        return $this->mem;
    }

    public function setMem(float $mem): static
    {
        $this->mem = $mem;

        return $this;
    }

    public function getVsz(): ?int
    {
        return $this->vsz;
    }

    public function setVsz(int $vsz): static
    {
        $this->vsz = $vsz;

        return $this;
    }

    public function getRss(): ?int
    {
        return $this->rss;
    }

    public function setRss(int $rss): static
    {
        $this->rss = $rss;

        return $this;
    }

    public function getCollectedAt(): ?\DateTimeImmutable
    {
        return $this->collectedAt;
    }

    public function setCollectedAt(\DateTimeImmutable $collectedAt): static
    {
        $this->collectedAt = $collectedAt;

        return $this;
    }
}
