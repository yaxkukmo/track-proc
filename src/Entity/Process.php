<?php

namespace App\Entity;

use App\Repository\ProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProcessRepository::class)]
#[ORM\UniqueConstraint(columns: ['pid', 'starttime'])]
class Process
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $starttime = null;

    /**
     * @var Collection<int, Metric>
     */
    #[ORM\OneToMany(targetEntity: Metric::class, mappedBy: 'process')]
    private Collection $metrics;

    public function __construct()
    {
        $this->metrics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStarttime(): ?int
    {
        return $this->starttime;
    }

    public function setStarttime(int $starttime): static
    {
        $this->starttime = $starttime;

        return $this;
    }

    /**
     * @return Collection<int, Metric>
     */
    public function getMetrics(): Collection
    {
        return $this->metrics;
    }

    public function addMetric(Metric $metric): static
    {
        if (!$this->metrics->contains($metric)) {
            $this->metrics->add($metric);
            $metric->setProcess($this);
        }

        return $this;
    }

    public function removeMetric(Metric $metric): static
    {
        if ($this->metrics->removeElement($metric)) {
            if ($metric->getProcess() === $this) {
                $metric->setProcess(null);
            }
        }

        return $this;
    }
}
