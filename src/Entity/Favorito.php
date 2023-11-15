<?php

namespace App\Entity;

use App\Repository\FavoritoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritoRepository::class)]
class Favorito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $favorito = null;

    #[ORM\ManyToOne(inversedBy: 'favoritos')]
    private ?Persona $persona = null;

    #[ORM\ManyToOne(inversedBy: 'favoritos')]
    private ?Servicio $servicio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isFavorito(): ?bool
    {
        return $this->favorito;
    }

    public function setFavorito(?bool $favorito): static
    {
        $this->favorito = $favorito;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): static
    {
        $this->persona = $persona;

        return $this;
    }

    public function getServicio(): ?Servicio
    {
        return $this->servicio;
    }

    public function setServicio(?Servicio $servicio): static
    {
        $this->servicio = $servicio;

        return $this;
    }
}
