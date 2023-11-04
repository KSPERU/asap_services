<?php

namespace App\Entity;

use App\Repository\CodigoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CodigoRepository::class)]
class Codigo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $c_codigo = null;

    #[ORM\OneToOne(inversedBy: 'codigo', cascade: ['persist', 'remove'])]
    private ?Persona $persona = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCCodigo(): ?string
    {
        return $this->c_codigo;
    }

    public function setCCodigo(?string $c_codigo): static
    {
        $this->c_codigo = $c_codigo;

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
