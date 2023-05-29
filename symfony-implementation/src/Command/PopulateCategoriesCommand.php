<?php

namespace App\Command;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[AsCommand(
    name: 'app:populate-categories',
    description: 'populate database with samples categories',
)]
class PopulateCategoriesCommand extends Command
{
  
    public function __construct(
        private CategoryRepository $categoryRepo,
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

        // todo: move to use case
        $categories = $this->categoryRepo->findAll();
        $count = is_countable($categories) ? count($categories) : 0;

        if($count === 0){
          for($i = 1; $i <= 11; $i++){
            
            $category = new Category();
            $category->setName('Category ' . $i);
            
            $this->categoryRepo->save($category);
            
            $io->success('Created category: ' . $category);
          }
          
          $this->categoryRepo->flush();
        }

        return Command::SUCCESS;
    }
}
