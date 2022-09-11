<?php

namespace App\Entity;

use App\Repository\OdooContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OdooContactRepository::class)]
#[ORM\Table(name: '`odoo_contact`')]
class OdooContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $name;

    #[ORM\Column(type: 'string', length: 50)]
    private string $phone;

    #[ORM\Column(name: 'odoo_business_id', type: 'string', length: 50)]
    #[ORM\ManyToOne(targetEntity: 'OdooBusiness', inversedBy: 'odoo_contact')]
    private OdooBusiness $odooBusiness;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getOdooBusiness(): OdooBusiness
    {
        return $this->odooBusiness;
    }

    public function setOdooBusiness(OdooBusiness $odooBusiness): void
    {
        $this->odooBusiness = $odooBusiness;
    }
}
