<?php

namespace App\Usecases;

use App\DTO\RegisterUser;
use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
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

  public function execute(mixed $data): mixed {

    if($data instanceof RegisterUser){
      $dto = $data;
    } else {
      $dto = $this->serializer->deserialize($data, RegisterUser::class, 'json');
      $this->validate($dto);
    }

    $newUser = new User();
    $password = $this->passwordEncoder->hashPassword($newUser, $dto->password); 
    $roles = $dto->roles;

    $newUser->setPassword($password);
    $newUser->setEmail($dto->email);
    
    foreach($roles as $roleName){
      $role = $this->groupRepo->findOneByName($roleName);
      if(!$role){
        throw new \Exception("The $roleName not exist");
      }
      $newUser->addGroup($role);
    }

    $this->validate($newUser);
    $this->userRepo->save($newUser, true);
    
    return $newUser;
  }

}
