<?php

namespace App\Usecases\Product;

use App\Contracts\ISearchService;
use App\DTO\PaginateRequest;
use App\DTO\PaginateResponseObject;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Usecases\BaseUsecase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SearchProductsUsecase extends BaseUsecase {
  
  function __construct(
    private ProductRepository $productRepo,
    private CategoryRepository $categoryRepo,
    private SerializerInterface  $serializer,
    private ValidatorInterface $validator,
    private ISearchService $searchService,
  ){
    parent::__construct($validator);
  }

  public function execute(PaginateRequest $data): PaginateResponseObject {

    $start = $data->start;
    $total = $data->total;
    $query = $data->query ?? '';

    $hits = $this->searchService->search($query, $start, $total);

    return $hits;
  }

}
