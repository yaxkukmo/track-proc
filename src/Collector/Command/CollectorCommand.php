<?php

namespace App\Collector\Command;

use App\Collector\Aggregator\DeltaCalculator;
use App\Collector\Generator\ProcPathGenerator;
use App\Collector\Aggregator\ProcessSnapshotAggregator;
use App\Collector\Model\ProcessSnapshotBatch;
use App\Collector\Parser\ProcParser;
use App\Collector\Reader\FileContentsReader;
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
        private readonly ProcPathGenerator $procPathGenerator,
        private readonly FileContentsReader $fileReader,
        private readonly ProcParser $procParser,
        private readonly DeltaCalculator $deltaCalculator,
        private readonly ProcessSnapshotAggregator $aggregator,
        private readonly MessageBusInterface $bus
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $windowData = [];
        $start = time();

        while ($this->running) {
            $paths = ($this->procPathGenerator)();
            $procFileContents = ($this->fileReader)($paths);
            ($this->procParser)($procFileContents, $windowData);
            if ($this->isTimeToProcessBuffer($start)) {
                $snapshots = [];
                foreach ($windowData as $pid => $pidData) {
                    foreach($pidData as $key => $current) {
                        $prev = $key - 1;
                        if(isset($pidData[$prev])) {
                            $windowData[$pid][$key]['utime']
                                = ($this->deltaCalculator)(
                                    $pidData[$prev]['utime'],
                                    $current['utime']
                                );
                            $windowData[$pid][$key]['stime']
                                = ($this->deltaCalculator)(
                                    $pidData[$prev]['stime'],
                                    $current['stime']
                                );
                        }
                    }
                    $snapshot = $this->aggregator->aggregate($windowData[$pid]);
                    if ($snapshot !== null) {
                        $snapshots[] = $snapshot;
                    }
                }

                $message = new ProcessSnapshotBatch(
                    snapshots: $snapshots,
                    collectedAt: time()
                );

                $this->bus->dispatch($message);
                $start = time();
                $windowData = [];
                unset($snapshots);
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
