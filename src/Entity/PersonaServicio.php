<?php

namespace App\Entity;

use App\Repository\PersonaServicioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaServicioRepository::class)]
class PersonaServicio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'servicios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $idPersona = null;

    #[ORM\ManyToOne(inversedBy: 'personas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Servicio $idServicio = null;

    #[ORM\Column(nullable: true)]
    private ?float $costoservicio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPersona(): ?Persona
    {
        return $this->idPersona;
    }

    public function setIdPersona(?Persona $idPersona): static
    {
        $this->idPersona = $idPersona;

        return $this;
    }

    public function getIdServicio(): ?Servicio
    {
        return $this->idServicio;
    }

    public function setIdServicio(?Servicio $idServicio): static
    {
        $this->idServicio = $idServicio;

        return $this;
    }

    public function getCostoservicio(): ?float
    {
        return $this->costoservicio;
    }

    public function setCostoservicio(?float $costoservicio): static
    {
        $this->costoservicio = $costoservicio;

        return $this;
    }

}
