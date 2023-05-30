<?php

namespace App\Usecases\User;

use App\Contracts\IAccountService;
use App\DTO\PaginateRequest;
use App\DTO\PaginateResponseObject;
use App\Entity\User;
use App\Usecases\BaseUsecase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ListUsersUsecase extends BaseUsecase {
  
  function __construct(
    private IAccountService $accountService,
  ){

  }

  public function execute(PaginateRequest $data): PaginateResponseObject
  { 
    return $this->accountService->listUsers($data->start, $data->total);
  }

}
