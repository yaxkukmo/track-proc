<?php

namespace App\Collector\Command;

use App\Collector\Mapper\PsAuxMapper;
use App\Collector\Aggregator\ProcessSnapshotAggregator;
use App\Collector\Model\ProcessSnapshotBatch;
use App\Collector\Parser\PsAuxParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:collector',
    description: 'Command for collecting and agregating data from ps aux',
)]
class CollectorCommand extends Command implements SignalableCommandInterface
{
    private bool $running = true;

    public function __construct(
        private readonly PsAuxParser $psAuxParser,
        private readonly PsAuxMapper $psAuxMapper,
        private readonly ProcessSnapshotAggregator $aggregator,
        private readonly MessageBusInterface $bus
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $buffer = [];
        $start = time();

        while ($this->running) {
            $buffer[] = `ps aux`;
            if ($this->isTimeToProcessBuffer($start)) {
                $parsed = $this->psAuxParser->processCommandOutput($buffer);
                $processSnapshotList = array_map(
                    function($outputLine) {
                        $psLineRawData = $this->psAuxParser->processLine($outputLine);
                        return $this->psAuxMapper->toProcessSnapshot($psLineRawData);
                    },
                    $parsed
                );

                var_dump($processSnapshotList[2]);

                $message = new ProcessSnapshotBatch(
                    snapshots: $this->aggregator->aggregate($processSnapshotList),
                    collectedAt: time()
                );

                $this->bus->dispatch($message);

                $start = time();
                $buffer = [];
            }
            sleep(1);
        }

        return Command::SUCCESS;
    }

    private function isTimeToProcessBuffer(int $start): bool
    {
        return time() - $start >= 10;
    }

    public function getSubscribedSignals(): array
    {
        return [SIGINT, SIGTERM];
    }

    public function handleSignal(int $signal, int|false $previousExitCode = 0): int|false
    {
        $this->running = false;
        return false;
    }
}
