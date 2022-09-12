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
    private int $odooContactId;

    #[ORM\Column(type: 'string')]
    private string $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOdooContactId(): int
    {
        return $this->odooContactId;
    }

    public function setOdooContactId(int $odooContactId): void
    {
        $this->odooContactId = $odooContactId;
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
