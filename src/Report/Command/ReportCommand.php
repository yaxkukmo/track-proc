<?php

namespace App\Report\Command;

use App\Report\Generator\GroffPdfGenerator;
use App\Repository\MetricRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:report',
    description: 'Command for generating pdf report',
)]
class ReportCommand extends Command
{
    public function __construct(
        private readonly MetricRepository $repo,
        private readonly GroffPdfGenerator $generator
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('from', null, InputOption::VALUE_REQUIRED, 'Start date (Y-m-d H:i)')
            ->addOption('to', null, InputOption::VALUE_REQUIRED, 'End date (Y-m-d H:i)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $from = $input->getOption('from');
        $to = $input->getOption('to');
        $report = '';

        if ($from) {
            $io->note(sprintf('You passed an option from: %s', $from));
        }

        if ($to) {
            $io->note(sprintf('You passed an option to: %s', $to));
        }

        $report = $this->generator->render(
            [
                'vsize' => $this->repo->findByDateRangeInGb('vsize', $from, $to),
                'rss' => $this->repo->findByDateRangeInMb('rss', $from, $to),
                'stime' => $this->repo->findByDateRangeInSeconds('stime', $from, $to),
                'utime' => $this->repo->findByDateRangeInSeconds('utime', $from, $to),
            ]
        );

        file_put_contents('/tmp/report_tmp.mm', $report);
        shell_exec('groff -Tpdf -s -t -mm /tmp/report_tmp.mm > /tmp/report.pdf');
        $io->success('Report generated. You can open /tmp/report.pdf');

        return Command::SUCCESS;
    }

}
