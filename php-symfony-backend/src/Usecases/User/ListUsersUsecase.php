<?php

namespace App\Usecases\User;

use App\DTO\PaginateRequest;
use App\Entity\User;
use App\Service\AccountService;
use App\Usecases\BaseUsecase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ListUsersUsecase extends BaseUsecase {
  
  function __construct(
    private AccountService $accountService,
  ){

  }

  public function execute(mixed $data): mixed
  { 

    $start = 0;
    $total = 20;

    if($data instanceof PaginateRequest){
      $start = $data->start;
      $total = $data->total;
    }

    return $this->accountService->listUsers($start, $total);
  }

}
