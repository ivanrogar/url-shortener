<?php

declare(strict_types=1);

namespace App\Command;

use App\Report\UrlReport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReportCommand extends Command
{
    protected static $defaultName = 'cron:report';

    private UrlReport $urlReport;

    public function __construct(
        UrlReport $urlReport,
        string $name = null
    ) {
        parent::__construct($name);

        $this->urlReport = $urlReport;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate report and send notifications');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->urlReport->processDaily();

        $this->urlReport->processWeekly();

        $this->urlReport->processMonthly();

        return 0;
    }
}
