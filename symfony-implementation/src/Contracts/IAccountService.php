<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\PaginateResponseObject;
use App\Entity\Roles;
use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface IAccountService
{

  public function userExist(string $email): bool;

  public function listUsers(int $start = 0, int $totalItems = 20): PaginateResponseObject;

}
