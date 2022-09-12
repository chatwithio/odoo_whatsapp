<?php

namespace App\Entity;

use App\Repository\OdooSentContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OdooSentContactRepository::class)]
class OdooSentContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\OneToOne(inversedBy: 'odooSentContact', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OdooContact $odooContact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getOdooContact(): ?OdooContact
    {
        return $this->odooContact;
    }

    public function setOdooContact(OdooContact $odooContact): self
    {
        $this->odooContact = $odooContact;

        return $this;
    }
}
