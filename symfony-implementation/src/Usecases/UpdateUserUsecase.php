<?php

namespace App\Usecases;

use App\DTO\UpdateUser;
use App\DTO\SimpleUpdateUser;
use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use App\Exception\ValidationException;
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
  ){
    parent::__construct($validator);
  }

  public function execute(mixed $data){

    $dto = $this->serializer->deserialize($data, UpdateUser::class, 'json');
    $this->validate($dto);

    $user = new User();
    $password = $this->passwordEncoder->hashPassword($user, $dto->password); 

    $user->setPassword($password);
    $user->setEmail($dto->email);
    
    //if()
    //$user->addGroup($userRole);

    $this->validate($user);
    $this->userRepo->save($user, true);
  }

}
