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
            $process = $this->processRepository->findByPidAndStartTime($item->getPid(), $item->getStarttime());
            if (!$process) {
                $process = new Process();
                $process->setStarttime($item->getStarttime());
                $process->setPid($item->getPid());
                $process->setName($item->getCommand());
                $this->em->persist($process);
                $this->em->flush();
            }
            $metric = new Metric();
            $metric->setVsize($item->getVsize());
            $metric->setRss($item->getRss());
            $metric->setStime($item->getStime());
            $metric->setUtime($item->getUtime());
            $metric->setNumThreads($item->getNumThreads());
            $metric->setPriority($item->getPriority());
            $metric->setNice($item->getNice());
            $metric->setShared($item->getShared());
            $metric->setText($item->getText());
            $metric->setData($item->getData());
            $metric->setCollectedAt(new DateTimeImmutable('@' . $batch->getCollectedAt()));
            $process->addMetric($metric);
            $this->em->persist($metric);

        }
        $this->em->flush();
    }
}
