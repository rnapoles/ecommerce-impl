<?php

namespace App\Command;

use App\Contracts\ISearchService;
use App\Repository\ProductRepository;
use App\DTO\Product\CreateProduct;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:index-products',
    description: 'Add all products to search index',
)]
class IndexProductsCommand extends Command
{
  
    public function __construct(
        private ProductRepository $productRepo,
        private AutoMapperInterface $mapper,   
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

        /*
        $this->searchService->down();
        sleep(5);
        $this->searchService->setup();          
        */

        $products = $this->productRepo->findAll();
        foreach($products as $product){
          $dto = $this->mapper->map($product, CreateProduct::class); 
          //echo $dto->name . "\n";
          $this->searchService->indexProduct($dto);
        }
        
        return Command::SUCCESS;
    }
}

