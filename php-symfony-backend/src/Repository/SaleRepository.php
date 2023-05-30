<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sale>
 *
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
  use PersistTrait;

  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Sale::class);
  }
  
  public function calcProfit(): float
  {
    $class = Sale::class;
    $dql = "SELECT SUM(s.price) as profit FROM $class s";
    $query = $this->getEntityManager()->createQuery($dql);

    return $query->getSingleResult()['profit'];
  }

  public function listSoldProducts(): array 
  {

    $class = Sale::class;
    $dql = 'SELECT ';
    $dql .= 'p.id, p.name, p.price, p.unitsInStock,';
    $dql .= 'c.name, p.description, p.aditionalInfo, ';
    $dql .= 'p.sku, p.valoration, p.tags, p.images ';
    $dql .= "FROM $class s JOIN s.product p JOIN p.category c";
    $query = $this->getEntityManager()->createQuery($dql);

    return $query->getResult();
  }
}
