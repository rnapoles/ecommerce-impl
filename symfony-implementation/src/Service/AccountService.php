<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Roles;
use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountService
{

  function __construct(
    private UserRepository $userRepo,
    private GroupRepository $groupRepo,
    private UserPasswordHasherInterface $passwordEncoder,
  ){

  }

  public function userExist(string $email){
    $account = $this->userRepo->findOneByEmail($email);
    
    return $account !== null;
  }

}
