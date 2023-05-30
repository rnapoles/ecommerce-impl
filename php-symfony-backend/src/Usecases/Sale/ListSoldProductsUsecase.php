<?php

namespace App\Usecases\Sale;

use App\Exception\ApiException;
use App\DTO\Product\CreateProduct;
use App\Entity\User;
use App\Entity\Sale;
use App\Entity\Product;
use App\Repository\UserRepository;
use App\Repository\SaleRepository;
use App\Usecases\BaseUsecase;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ListSoldProductsUsecase extends BaseUsecase {
  
  function __construct(
    private SaleRepository $saleRepo,
    private SerializerInterface  $serializer,
    private AutoMapperInterface $mapper,
  ){

  }

  public function execute(): array {

    $entities = $this->saleRepo->listSoldProducts();
    $dtos = $this->mapper->mapMultiple($entities, CreateProduct::class);

    return $dtos;
  }

}
