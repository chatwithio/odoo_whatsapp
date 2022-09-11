<?php

namespace App\Entity;

use App\Repository\OdooSentContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OdooSentContactRepository::class)]
#[ORM\Table(name: '`odoo_sent_contact`')]
class OdooSentContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'odoo_contact_id', length: 50)]
    #[ORM\OneToOne(inversedBy: 'odoo_sent_contact', targetEntity: 'OdooContact')]
    #[ORM\JoinColumn(name: 'odoo_contact_id', referencedColumnName: 'id', nullable: false)]
    private OdooContact $odooContact;

    #[ORM\Column(type: 'string')]
    private string $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOdooContact(): OdooContact
    {
        return $this->odooContact;
    }

    public function setOdooContact(OdooContact $odooContact): void
    {
        $this->odooContact = $odooContact;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
