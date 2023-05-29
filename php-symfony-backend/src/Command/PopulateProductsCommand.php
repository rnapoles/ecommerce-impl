<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

#[AsCommand(
    name: 'app:populate-products',
    description: 'populate database with sample products',
)]
class PopulateProductsCommand extends Command
{
  
    public function __construct(
        private ProductRepository $productRepo,
        private CategoryRepository $categoryRepo,
    ) {
        parent::__construct();
        $this->faker = Faker\Factory::create('es_ES');
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
        $products = $this->productRepo->findAll();
        $count = is_countable($products) ? count($products) : 0;

        if($count === 0){

          $categories = $this->categoryRepo->findAll();
          $count = is_countable($categories) ? count($categories) : 0;
          
          if($count === 0){
            return Command::SUCCESS;
          }

          $index = 1;
          $faker = $this->faker;

          foreach($categories as $category){
            
            $max = rand(1, 5);
            
            for($i = 0; $i <= $max; $i++){
              
              $description = implode(' ', $faker->words(rand(20,40)));
              
              $product = new Product();
              $product->setName('Product ' . $index++);
              $product->setPrice($faker->randomFloat(2, 1, 100));
              $product->setUnitsInStock(rand(1, 5));
              $product->setCategory($category);
              $product->setDescription($description);
              $product->setValoration(rand(1, 5));
              $product->setTags($this->generateTags());
              $product->setImages($this->generateImages());
              
              $this->productRepo->save($product);
              
              $io->success('Created product: ' . $product);
            }
          }
          
          $this->productRepo->flush();
        }

        return Command::SUCCESS;
    }
    
    private function generateTags(): array {
      
      $max = rand(1, 3);
      $tags = [];
      for($i = 1; $i <= 11; $i++){
        $tags[] = 'Tag' . $i;
      }
      
      return $this->faker->randomElements($tags ,$max);
    }
    
    private function generateImages(): array {
      
      $max = rand(1, 3);
      $result = [];

      for($i = 0; $i <= $max; $i++){
        $url = $this->faker->imageUrl($width = 640, $height = 480) ;
        $result[] = $url;
      }
      
      return $result;
    }
}
