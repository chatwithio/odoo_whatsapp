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
    private ?int $odooId = null;

    #[ORM\ManyToOne(inversedBy: 'odooContact')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OdooBusiness $odooBusiness = null;

    #[ORM\OneToOne(mappedBy: 'odooContact', cascade: ['persist', 'remove'])]
    private ?OdooSentContact $odooSentContact = null;

    #[ORM\Column(nullable: true)]
    private ?int $tagId = null;

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
        return $this->odooId;
    }

    public function setOdooId(int $odooId): self
    {
        $this->odooId = $odooId;

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
        if ($odooSentContact->getOdooContact() !== $this) {
            $odooSentContact->setOdooContact($this);
        }

        $this->odooSentContact = $odooSentContact;

        return $this;
    }

    public function setTagId(?int $tagId): self
    {
        $this->tagId = $tagId;

        return $this;
    }

    public function getTagId(): ?int
    {
        return $this->tagId;
    }
}
