<?php

namespace App\Entity;

use App\Repository\TarjetaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarjetaRepository::class)]
class Tarjeta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroTarjeta = null;

    #[ORM\Column(length: 255)]
    private ?string $fechaVencimiento = null;

    #[ORM\Column(length: 255)]
    private ?string $cvv = null;

    #[ORM\ManyToOne(inversedBy: 'p_tarjeta')]
    private ?Persona $persona = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroTarjeta(): ?string
    {
        return $this->numeroTarjeta;
    }

    public function setNumeroTarjeta(string $numeroTarjeta): static
    {
        $this->numeroTarjeta = $numeroTarjeta;

        return $this;
    }

    public function getFechaVencimiento(): ?string
    {
        return $this->fechaVencimiento;
    }

    public function setFechaVencimiento(string $fechaVencimiento): static
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): static
    {
        $this->cvv = $cvv;

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
}
