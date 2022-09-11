<?php

namespace App\Repository;

use App\Entity\OdooBusiness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OdooBusiness|null find($id, $lockMode = null, $lockVersion = null)
 * @method OdooBusiness|null findOneBy(array $criteria, array $orderBy = null)
 * @method OdooBusiness[]    findAll()
 * @method OdooBusiness[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OdooBusinessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OdooBusiness::class);
    }
}
