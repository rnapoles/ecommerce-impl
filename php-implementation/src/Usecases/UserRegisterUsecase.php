<?php

namespace App\Usecases;

use App\DTO\RegisterUser;
use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use App\Exception\ValidationException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterUsecase extends BaseUsecase {
  
  function __construct(
    private UserRepository $userRepo,
    private GroupRepository $groupRepo,
    private SerializerInterface  $serializer,
    private ValidatorInterface $validator,
    private UserPasswordHasherInterface $passwordEncoder,
  ){
    parent::__construct($validator);
  }

  public function execute(mixed $data){

    $dto = $this->serializer->deserialize($data, RegisterUser::class, 'json');
    $this->validate($dto);

    $userRole = $this->groupRepo->findOneByName('ROLE_USER');
    if(!$userRole){
      throw new \Exception('The ROLE_USER not exist');
    }

    $newUser = new User();
    $password = $this->passwordEncoder->hashPassword($newUser, $dto->password); 

    $newUser->setPassword($password);
    $newUser->setEmail($dto->email);
    $newUser->addGroup($userRole);

    $this->validate($newUser);
    $this->userRepo->save($newUser, true);
  }

}
