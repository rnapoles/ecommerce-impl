<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\PaginateResponseObject;
use App\DTO\Product\CreateProduct;

interface ISearchService
{

  public function setup(): void;

  public function down(): void;

  public function indexProduct(CreateProduct $product): void;

  public function deleteProduct(int $id): void;

  public function search(string $query, int $start = 0, int $totalItems = 10): PaginateResponseObject;

}
