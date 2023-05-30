<?php

namespace App\Usecases\Sale;

use App\Exception\ApiException;
use App\DTO\Sale\TotalProfitResponse;
use App\Entity\Sale;
use App\Repository\SaleRepository;
use App\Usecases\BaseUsecase;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;


class CalcSalesProfitUsecase extends BaseUsecase {
  
  function __construct(
    private SaleRepository $saleRepo,
    private SerializerInterface  $serializer,
    private AutoMapperInterface $mapper,
  ){

  }

  public function execute(): TotalProfitResponse {

    $profit = $this->saleRepo->calcProfit();
    $dto = new TotalProfitResponse();
    $dto->profit = $profit;
    
    return $dto;
  }

}
