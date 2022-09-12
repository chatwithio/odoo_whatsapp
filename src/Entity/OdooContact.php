<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OdooContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OdooContactRepository::class)]
#[ApiResource]
class OdooContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?int $odoo_id = null;

    #[ORM\ManyToOne(inversedBy: 'odooContacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OdooBusiness $odooBusiness = null;

    #[ORM\OneToOne(mappedBy: 'odooContacts', cascade: ['persist', 'remove'])]
    private ?OdooSentContact $odooSentContact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getOdooId(): ?int
    {
        return $this->odoo_id;
    }

    public function setOdooId(int $odoo_id): self
    {
        $this->odoo_id = $odoo_id;

        return $this;
    }

    public function getOdooBusiness(): ?OdooBusiness
    {
        return $this->odooBusiness;
    }

    public function setOdooBusiness(?OdooBusiness $odooBusiness): self
    {
        $this->odooBusiness = $odooBusiness;

        return $this;
    }

    public function getOdooSentContact(): ?OdooSentContact
    {
        return $this->odooSentContact;
    }

    public function setOdooSentContact(OdooSentContact $odooSentContact): self
    {
        // set the owning side of the relation if necessary
        if ($odooSentContact->getOdooContacts() !== $this) {
            $odooSentContact->setOdooContacts($this);
        }

        $this->odooSentContact = $odooSentContact;

        return $this;
    }
}
