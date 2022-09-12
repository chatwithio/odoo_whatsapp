<?php

namespace App\Entity;

use App\Repository\OdooBusinessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OdooBusinessRepository::class)]
class OdooBusiness
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $host = null;

    #[ORM\Column(length: 255)]
    private ?string $db = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $api_key = null;

    #[ORM\OneToMany(mappedBy: 'odooBusiness', targetEntity: OdooContact::class)]
    private Collection $odooContacts;

    public function __construct()
    {
        $this->odooContacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getDb(): ?string
    {
        return $this->db;
    }

    public function setDb(string $db): self
    {
        $this->db = $db;

        return $this;
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

    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    public function setApiKey(string $api_key): self
    {
        $this->api_key = $api_key;

        return $this;
    }

    /**
     * @return Collection<int, OdooContact>
     */
    public function getOdooContacts(): Collection
    {
        return $this->odooContacts;
    }

    public function addOdooContact(OdooContact $odooContact): self
    {
        if (!$this->odooContacts->contains($odooContact)) {
            $this->odooContacts->add($odooContact);
            $odooContact->setOdooBusiness($this);
        }

        return $this;
    }

    public function removeOdooContact(OdooContact $odooContact): self
    {
        if ($this->odooContacts->removeElement($odooContact)) {
            // set the owning side to null (unless already changed)
            if ($odooContact->getOdooBusiness() === $this) {
                $odooContact->setOdooBusiness(null);
            }
        }

        return $this;
    }
}
