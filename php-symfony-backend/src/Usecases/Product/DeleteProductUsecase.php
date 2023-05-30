<?php

namespace App\Usecases\Product;

use App\Contracts\ISearchService;
use App\DTO\PaginateRequest;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Usecases\BaseUsecase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteProductUsecase extends BaseUsecase {
  
  function __construct(
    private ProductRepository $productRepo,
    private ISearchService $searchService,
  ){

  }

  public function execute(int $id): void {
    $this->productRepo->removeById($id);
    $this->searchService->deleteProduct($id);
  }

}
