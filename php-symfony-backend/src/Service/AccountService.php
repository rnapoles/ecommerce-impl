<?php

declare(strict_types=1);

namespace App\Service;

use App\Contracts\IAccountService;
use App\DTO\PaginateResponseObject;
use App\Entity\Roles;
use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountService implements IAccountService
{

  function __construct(
    private UserRepository $userRepo,
    private GroupRepository $groupRepo,
    private EntityManagerInterface $em,
    private UserPasswordHasherInterface $passwordEncoder,
  ){

  }

  public function userExist(string $email): bool
  {
    $account = $this->userRepo->findOneByEmail($email);
    
    return $account !== null;
  }

  public function listUsers(int $start = 0, int $totalItems = 20): PaginateResponseObject
  {

    $em = $this->em;
    $qb = $em->createQueryBuilder();

    $qb->select('u')
      ->from(User::class, 'u')
      ->orderBy('u.id', 'ASC')
    ;

    $qb->setFirstResult($start);
    $qb->setMaxResults($totalItems);

    $query = $qb->getQuery();
    $qbCount = clone $qb;
    $qbCount->select('count(u) total');
    $qbCount->setFirstResult(null);
    $qbCount->setMaxResults(null);
    $queryCount = $qbCount->getQuery();

    $result = $queryCount->getSingleResult();
    $users = $query->getResult();

    $query = $qb->getQuery();

    $dto = new PaginateResponseObject();
    $dto->total = $result['total'];
    $dto->payload = $users;

    return $dto;
  }
}
