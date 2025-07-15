<?php

namespace App\Commands;

use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\BalanceRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TableCommand extends Command
{
    public function __construct(
        private BalanceRepository $repository
    ) {
        parent::__construct('table');
    }

    protected function configure(): void
    {
        $this
            ->setName('table')
            ->setDefinition([
                new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command help'),
            ])
            ->setDescription('Display help for a command')
            ->setHelp('asd');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        var_dump($this->repository->findAll());

        return 0;
    }
}
