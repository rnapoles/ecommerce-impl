<?php

namespace App\Repository;

trait PersistTrait
{
    public function save(object $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(object $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function removeById(int $id): void 
    {
        $class = $this->getEntityName();
        $dql = "DELETE $class c WHERE c.id = ?0";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(0, $id);
        $query->execute();
    }
    
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
