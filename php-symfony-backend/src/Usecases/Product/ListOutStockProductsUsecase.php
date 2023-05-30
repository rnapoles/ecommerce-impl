<?php

namespace App\Usecases\Product;

use App\Exception\ApiException;
use App\DTO\Product\CreateProduct;
use App\Entity\User;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Usecases\BaseUsecase;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ListOutStockProductsUsecase extends BaseUsecase {
  
  function __construct(
    private ProductRepository $productRepo,
    private SerializerInterface  $serializer,
    private AutoMapperInterface $mapper,
  ){

  }

  public function execute(): array {

    $entities = $this->productRepo->listOutStock();
    $dtos = $this->mapper->mapMultiple($entities, CreateProduct::class);

    return $dtos;
  }

}
