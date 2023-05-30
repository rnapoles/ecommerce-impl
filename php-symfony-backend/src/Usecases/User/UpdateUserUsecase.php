<?php

namespace App\Usecases\User;

use App\DTO\User\UpdateUser;
use App\DTO\User\SimpleUpdateUser;
use App\DTO\User\User as UserDTO;
use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use App\Usecases\BaseUsecase;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserUsecase extends BaseUsecase {
  
  public $isSimpleUpdate = false;

  function __construct(
    private UserRepository $userRepo,
    private GroupRepository $groupRepo,
    private SerializerInterface  $serializer,
    private ValidatorInterface $validator,
    private UserPasswordHasherInterface $passwordEncoder,
    private AutoMapperInterface $mapper,
  ){
    parent::__construct($validator);
  }

  public function execute(string $data): ?UserDTO 
  {

    $dto = $this->serializer->deserialize($data, UpdateUser::class, 'json');
    $this->validate($dto);

    $user = $this->userRepo->findOneById($dto->id);
    if(!$user){
      return null;
    }

    $user->setEmail($dto->email);
    if(!!$dto->password){
      $password = $this->passwordEncoder->hashPassword($user, $dto->password); 
      $user->setPassword($password);
    }
    
    $roles = $dto->roles;
    foreach($roles as $roleName){
      $role = $this->groupRepo->findOneByName($roleName);
      if(!$role){
        throw new \Exception("The $roleName not exist");
      }
      $user->addGroup($role);
    }

    $this->validate($user);
    $this->userRepo->save($user, true);
    $dto = $this->mapper->map($user, UserDTO::class); 

    return $dto;
  }

}
