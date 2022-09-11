<?php

namespace App\Repository;

use App\Entity\OdooContact;
use App\Entity\OdooSentContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    public function save(OdooContact $odooContact, string $message)
    {
        $entityManager = $this->getEntityManager();

        $odooSentContact = new OdooSentContact();
        $odooSentContact->setOdooContact($odooContact);
        $odooSentContact->setMessage($message);

        $entityManager->persist($odooSentContact);
        $entityManager->flush();
    }
}
