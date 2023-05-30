<?php

namespace App\Usecases\Product;

use App\DTO\Product\CreateProduct;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Usecases\BaseUsecase;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ReadProductUsecase extends BaseUsecase {
  
  function __construct(
    private ProductRepository $productRepo,
    private AutoMapperInterface $mapper,
  ){

  }

  public function execute(string $sku): ?CreateProduct
  {

    $product = $this->productRepo->findOneBySku($sku);
    if(!$product){
      return null;
    }

    $dto = $this->mapper->map($product, CreateProduct::class); 

    return $dto;
  }

}
