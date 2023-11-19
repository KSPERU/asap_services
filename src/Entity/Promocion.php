<?php

namespace App\Entity;

use App\Repository\PromocionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromocionRepository::class)]
class Promocion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $codigo = null;

    #[ORM\Column(nullable: true)]
    private ?bool $usado = null;

    #[ORM\OneToOne(mappedBy: 'promocion', cascade: ['persist', 'remove'])]
    private ?Persona $personacodigo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function isUsado(): ?bool
    {
        return $this->usado;
    }

    public function setUsado(?bool $usado): static
    {
        $this->usado = $usado;

        return $this;
    }

    public function getPersonacodigo(): ?Persona
    {
        return $this->personacodigo;
    }

    public function setPersonacodigo(?Persona $personacodigo): static
    {
        // unset the owning side of the relation if necessary
        if ($personacodigo === null && $this->personacodigo !== null) {
            $this->personacodigo->setPromocion(null);
        }

        // set the owning side of the relation if necessary
        if ($personacodigo !== null && $personacodigo->getPromocion() !== $this) {
            $personacodigo->setPromocion($this);
        }

        $this->personacodigo = $personacodigo;

        return $this;
    }

}
