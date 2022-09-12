<?php

namespace App\Repository;

use App\Entity\OdooSentContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OdooSentContacts>
 *
 * @method OdooSentContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method OdooSentContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method OdooSentContact[]    findAll()
 * @method OdooSentContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OdooSentContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OdooSentContact::class);
    }

    public function add(OdooSentContact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OdooSentContact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OdooSentContacts[] Returns an array of OdooSentContacts objects
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

//    public function findOneBySomeField($value): ?OdooSentContacts
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
