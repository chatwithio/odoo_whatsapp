<?php

namespace App\Repository;

use App\Entity\OdooBusiness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OdooBusiness>
 *
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

    public function add(OdooBusiness $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OdooBusiness $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OdooBusiness[] Returns an array of OdooBusiness objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OdooBusiness
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
