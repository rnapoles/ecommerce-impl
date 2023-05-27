<?php

namespace App\Usecases;

use App\DTO\RegisterUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetupAdminUsecase extends BaseUsecase {
  
  function __construct(
    private UserRegisterUsecase $registerUser,      
    private UserRepository $userRepo,
  ){

  }

  public function execute(mixed $data): mixed
  {

    $userRepo = $this->userRepo;

    $email = 'admin@localhost.loc';
    $account = $userRepo->findOneByEmail($email);

    if (!$account) {
      $dto = new RegisterUser();
      $dto->email = $email;
      $dto->password = '1234';
      $dto->roles = ['ROLE_USER', 'ROLE_ADMIN'];
      
      $this->registerUser->execute($dto);
      
    }
    
    return $account;
  }

}
