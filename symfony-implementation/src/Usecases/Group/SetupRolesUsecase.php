<?php

namespace App\Usecases\Group;

use App\DTO\RegisterUser;
use App\Entity\Group;
use App\Entity\Roles;
use App\Repository\GroupRepository;
use App\Usecases\BaseUsecase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetupRolesUsecase extends BaseUsecase {
  
  function __construct(
    private GroupRepository $groupRepo,
    private ValidatorInterface $validator,
  ){
    parent::__construct($validator);
  }

  public function execute(mixed $data): mixed
  {

    $groupRepo = $this->groupRepo;
    $newRoles = [];

    foreach (Roles::cases() as $case) {
        $roleName = $case->name;
        $rol = $groupRepo->findOneByName($roleName);
        if (!$rol) {
          $rol = new Group();
          $rol->setName($roleName);
          $groupRepo->save($rol, true);
          $newRoles[] = $rol;
        }
    }

    return $newRoles;
  }

}
