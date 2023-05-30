<?php

namespace App\Usecases\Sale;

use App\Contracts\ISearchService;
use App\Exception\ApiException;
use App\DTO\Sale\CreateSale;
use App\DTO\Product\CreateProduct;
use App\Entity\User;
use App\Entity\Sale;
use App\Entity\Product;
use App\Repository\UserRepository;
use App\Repository\SaleRepository;
use App\Repository\ProductRepository;
use App\Usecases\BaseUsecase;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CreateSaleUsecase extends BaseUsecase {
  
  function __construct(
    private UserRepository $userRepo,
    private SaleRepository $saleRepo,
    private ProductRepository $productRepo,
    private ISearchService $searchService,
    private SerializerInterface  $serializer,
    private ValidatorInterface $validator,
    private AutoMapperInterface $mapper,
  ){
    parent::__construct($validator);
  }

  public function execute(mixed $data, int $userId): CreateSale {

    if($data instanceof CreateSale){
      $dto = $data;
    } else {
      $dto = $this->serializer->deserialize($data, CreateSale::class, 'json');
      $this->validate($dto);
    }

    $user = $this->userRepo->findOneById($userId);
    if(!$user){
      throw new ApiException("User not found");
    }

    $product = $this->productRepo->findOneById($dto->productId);

    if(!$product){
      throw new ApiException("Product not found");
    }

    if(!$product->isAvailable()){
      throw new ApiException("Product out of stock");
    }

    $sale = new Sale();
    $sale->setProduct($product);
    $sale->setUser($user);
    $sale->setPrice($product->getPrice());
    $product->reduceStock();

    //Persist sale
    $this->validate($sale);
    $this->userRepo->save($sale, true);

    //update search index
    $productDto = $this->mapper->map($product, CreateProduct::class); 
    $this->searchService->indexProduct($productDto);
    
    //todo: calc total win

    $dto->id = $sale->getId();
    $dto->userId = $userId;

    return $dto;
  }

}
