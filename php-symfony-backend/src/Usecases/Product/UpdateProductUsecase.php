<?php

namespace App\Usecases\Product;

use App\Contracts\ISearchService;
use App\DTO\Product\CreateProduct;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Usecases\BaseUsecase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateProductUsecase extends BaseUsecase {
  
  function __construct(
    private ProductRepository $productRepo,
    private CategoryRepository $categoryRepo,
    private SerializerInterface  $serializer,
    private ValidatorInterface $validator,
    private ISearchService $searchService,
  ){
    parent::__construct($validator);
  }

  public function execute(mixed $data): ?CreateProduct {

    $dto = $this->serializer->deserialize($data, CreateProduct::class, 'json');
    $this->validate($dto);

    $product = $this->productRepo->findOneById($dto->id);
    if(!$product){
      return null;
    }

    $categoryName = $dto->category;
    $category = $this->categoryRepo->findOneByName($categoryName);
    if(!$category){
      throw new \Exception("The $categoryName not exist");
    }

    $product->setName($dto->name);
    $product->setPrice($dto->price);
    $product->setUnitsInStock($dto->unitsInStock);
    $product->setCategory($category);
    $product->setDescription($dto->description);
    $product->setValoration($dto->valoration);
    $product->setTags($dto->tags);
    $product->setImages($dto->images);
    
    if($dto->aditionalInfo){
      $product->setAditionalInfo($dto->aditionalInfo);
    }

    if($dto->sku){
      $product->setSku($dto->sku);
    }

    $this->validate($product);
    $this->productRepo->save($product, true);
    
    $this->searchService->indexProduct($dto);

    return $dto;
  }

}
