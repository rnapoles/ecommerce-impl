<?php

namespace App\Usecases\User;

use App\DTO\User\PaginateRequest;
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

    $startPage = 1;
    $total = 10;

    if($data instanceof PaginateRequest){
      $startPage = $data->startPage;
      $total = $data->total;
    }
  
    return $this->accountService->listUsers($startPage, $total);
  }

}
