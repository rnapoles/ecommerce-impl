<?php

namespace App\Command;

use App\Contracts\ISearchService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:setup-search-engine',
    description: 'Create admin user and Roles',
)]
class SetupSearchEngineCommand extends Command
{
  
    public function __construct(
        private ISearchService $searchService,    
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        /*
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
        */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->searchService->setup();

        return Command::SUCCESS;
    }
}
