<?php

namespace App\Repository;

use App\Entity\OdooBusiness;
use App\Entity\OdooContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OdooContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method OdooContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method OdooContact[]    findAll()
 * @method OdooContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OdooContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OdooContact::class);
    }

    public function save(int $odooBusinessId, string $name, string $phone)
    {
        $entityManager = $this->getEntityManager();

        $odooContact = $this->findOneBy(['phone' => $phone]);

        if (!$odooContact) {
            $odooContact = new OdooContact();
        }

        $odooContact->setOdooBusinessId($odooBusinessId);
        $odooContact->setName($name);
        $odooContact->setPhone($phone);

        $entityManager->persist($odooContact);
        $entityManager->flush();
    }
}
