<?php

namespace App\Usecases\Product;

use App\Contracts\ISearchService;
use App\DTO\Product\CreateProduct;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Usecases\BaseUsecase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateProductUsecase extends BaseUsecase {
  
  function __construct(
    private ProductRepository $productRepo,
    private CategoryRepository $categoryRepo,
    private SerializerInterface  $serializer,
    private ValidatorInterface $validator,
    private ISearchService $searchService,
  ){
    parent::__construct($validator);
  }

  public function execute(mixed $data): mixed {

    if($data instanceof CreateProduct){
      $dto = $data;
    } else {
      $dto = $this->serializer->deserialize($data, CreateProduct::class, 'json');
      $this->validate($dto);
    }

    $categoryName = $dto->category;
    $category = $this->categoryRepo->findOneByName($categoryName);
    if(!$category){
      //throw new \Exception("The $categoryName not exist");
      $category = new Category();
      $category->setName($categoryName);
      $this->categoryRepo->save($category);
    }

    $new = new Product();
    $new->setName($dto->name);
    $new->setPrice($dto->price);
    $new->setUnitsInStock($dto->unitsInStock);
    $new->setCategory($category);
    $new->setDescription($dto->description);
    $new->setValoration($dto->valoration);
    $new->setTags($dto->tags);
    $new->setImages($dto->images);
    
    if($dto->aditionalInfo){
      $new->setAditionalInfo($dto->aditionalInfo);
    }

    if($dto->sku){
      $new->setSku($dto->sku);
    }

    $this->validate($new);
    $this->productRepo->save($new, true);
    
    $dto->id = $new->getId();
    $dto->sku = $new->getSku();
    
    $this->searchService->indexProduct($dto);

    return $dto;
  }

}
