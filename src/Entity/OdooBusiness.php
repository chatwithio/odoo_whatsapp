<?php

namespace App\Entity;

use App\Repository\OdooBusinessRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OdooBusinessRepository::class)]
#[ORM\Table(name: '`odoo_business`')]
class OdooBusiness
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $host;

    #[ORM\Column(type: 'string', length: 50)]
    private string $db;

    #[ORM\Column(type: 'string', length: 50)]
    private string $name;

    #[ORM\Column(type: 'string', length: 100)]
    private string $api_key;

    #[ORM\OneToMany(mappedBy: 'odooBusiness', targetEntity: 'OdooContact')]
    private Collection $odooContacts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost($host): void
    {
        $this->host = $host;
    }

    public function getDb(): string
    {
        return $this->db;
    }

    public function setDb($db): void
    {
        $this->db = $db;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getApiKey(): string
    {
        return $this->api_key;
    }

    public function setApiKey($api_key): void
    {
        $this->api_key = $api_key;
    }

    public function getOdooContacts(): Collection
    {
        return $this->odooContacts;
    }
}
