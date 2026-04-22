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

    #[ORM\ManyToOne(inversedBy: 'metrics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Process $process = null;

    #[ORM\Column]
    private ?int $utime = null;

    #[ORM\Column]
    private ?int $stime = null;

    #[ORM\Column]
    private ?int $rss = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $vsize = null;

    #[ORM\Column]
    private ?int $numThreads = null;

    #[ORM\Column]
    private ?int $shared = null;

    #[ORM\Column]
    private ?int $text = null;

    #[ORM\Column]
    private ?int $data = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column]
    private ?int $nice = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $collectedAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUtime(): ?int
    {
        return $this->utime;
    }

    public function setUtime(int $utime): static
    {
        $this->utime = $utime;

        return $this;
    }

    public function getStime(): ?int
    {
        return $this->stime;
    }

    public function setStime(int $stime): static
    {
        $this->stime = $stime;

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

    public function getVsize(): ?int
    {
        return $this->vsize;
    }

    public function setVsize(int $vsize): static
    {
        $this->vsize = $vsize;

        return $this;
    }

    public function getNumThreads(): ?int
    {
        return $this->numThreads;
    }

    public function setNumThreads(int $numThreads): static
    {
        $this->numThreads = $numThreads;

        return $this;
    }

    public function getShared(): ?int
    {
        return $this->shared;
    }

    public function setShared(int $shared): static
    {
        $this->shared = $shared;

        return $this;
    }

    public function getText(): ?int
    {
        return $this->text;
    }

    public function setText(int $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getData(): ?int
    {
        return $this->data;
    }

    public function setData(int $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getNice(): ?int
    {
        return $this->nice;
    }

    public function setNice(int $nice): static
    {
        $this->nice = $nice;

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
