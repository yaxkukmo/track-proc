<?php

namespace App\Processor\Handler;

use App\Collector\Model\ProcessSnapshotBatch;
use App\Entity\Metric;
use App\Entity\Process;
use App\Repository\ProcessRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcessSnapshotHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProcessRepository $processRepository
    )
    {}

    public function __invoke(ProcessSnapshotBatch $batch)
    {
        $list = $batch->getSnapshotList();
        foreach($list as $item) {
            $runFlush = false;
            $process = $this->processRepository->findByUserAndCommand($item->getUser(), $item->getCommand());
            if (!$process) {
                $process = new Process();
                $process->setUser($item->getUser());
                $process->setCommand($item->getCommand());
                $runFLush = true;
            }
            $metric = new Metric();
            $metric->setPid($item->getPid());
            $metric->setCpu($item->getCpu());
            $metric->setMem($item->getMem());
            $metric->setVsz($item->getVsz());
            $metric->setRss($item->getRss());
            $metric->setCollectedAt(new DateTimeImmutable('@' . $batch->getCollectedAt()));
            $process->addMetric($metric);
            $this->em->persist($metric);
            $this->em->persist($process);
            if ($runFlush) {
                $this->em->flush();
            }

        }
        $this->em->flush();
    }
}
