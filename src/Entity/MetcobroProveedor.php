<?php

namespace App\Entity;

use App\Repository\MetcobroProveedorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MetcobroProveedorRepository::class)]
class MetcobroProveedor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $mcp_numerocuenta = null;

    #[ORM\Column]
    private ?bool $mcp_estado = null;

    #[ORM\ManyToOne(inversedBy: 'metcobroproveedor')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Metodocobro $idmetcobro = null;

    #[ORM\ManyToOne(inversedBy: 'metcobro')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $idproveedor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMcpNumerocuenta(): ?string
    {
        return $this->mcp_numerocuenta;
    }

    public function setMcpNumerocuenta(string $mcp_numerocuenta): static
    {
        $this->mcp_numerocuenta = $mcp_numerocuenta;

        return $this;
    }

    public function isMcpEstado(): ?bool
    {
        return $this->mcp_estado;
    }

    public function setMcpEstado(bool $mcp_estado): static
    {
        $this->mcp_estado = $mcp_estado;

        return $this;
    }

    public function getIdmetcobro(): ?Metodocobro
    {
        return $this->idmetcobro;
    }

    public function setIdmetcobro(?Metodocobro $idmetcobro): static
    {
        $this->idmetcobro = $idmetcobro;

        return $this;
    }

    public function getIdproveedor(): ?Persona
    {
        return $this->idproveedor;
    }

    public function setIdproveedor(?Persona $idproveedor): static
    {
        $this->idproveedor = $idproveedor;

        return $this;
    }
}
