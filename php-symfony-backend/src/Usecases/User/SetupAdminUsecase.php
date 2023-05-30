<?php

namespace App\Usecases\User;

use App\DTO\User\User as UserDTO;
use App\DTO\User\RegisterUser;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Usecases\BaseUsecase;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetupAdminUsecase extends BaseUsecase {
  
  function __construct(
    private UserRegisterUsecase $userRegister,      
    private UserRepository $userRepo,
    private AutoMapperInterface $mapper,
  ){

  }

  public function execute(): UserDTO
  {

    $userRepo = $this->userRepo;

    $email = 'admin@localhost.loc';
    $account = $userRepo->findOneByEmail($email);

    if (!$account) {
      $dto = new RegisterUser();
      $dto->email = $email;
      $dto->password = '1234';
      $dto->roles = ['ROLE_USER', 'ROLE_ADMIN'];
      
      return $this->userRegister->execute($dto);
    }

    $dto = $this->mapper->map($newUser, UserDTO::class); 
    return $dto;
  }

}
