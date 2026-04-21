<?php

namespace App\Collector\Command;

use App\Collector\Aggregator\DeltaCalculator;
use App\Collector\Generator\ProcPathGenerator;
use App\Collector\Mapper\PsAuxMapper;
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
        private readonly PsAuxMapper $psAuxMapper,
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
                foreach ($windowData as $pid => $pidData) {
                    foreach($pidData['stat'] as $key => $current) {
                        if(isset($pidData['stat'][$key - 1])) {
                            $windowData[$pid]['stat'][$key]['utime'] = ($this->deltaCalculator)($pidData['stat'][$key - 1]['utime'], $current['utime']);
                            $windowData[$pid]['stat'][$key]['stime'] = ($this->deltaCalculator)($pidData['stat'][$key - 1]['stime'], $current['stime']);
                            $windowData[$pid]['stat'] = $this->aggregator->aggregateStat(array_shift($windowData[$pid]['stat']));
                            $windowData[$pid]['statm'] = $this->aggregator->aggregateStatm(array_shift($windowData[$pid]['statm']));
                        }
                    }
                }
                var_dump(max($windowData));
                exit;

                /*
                $message = new ProcessSnapshotBatch(
                    snapshots: $this->aggregator->aggregate($processSnapshotList),
                    collectedAt: time()
                );

                $this->bus->dispatch($message);
                */
                $start = time();
                $windowData = [];
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
