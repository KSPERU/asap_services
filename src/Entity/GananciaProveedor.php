<?php

namespace App\Entity;

use App\Repository\GananciaProveedorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GananciaProveedorRepository::class)]
class GananciaProveedor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $gp_total = null;

    #[ORM\ManyToOne(inversedBy: 'gananciaproveedor')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $idproveedor = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $gp_fechaoperacion = null;

    #[ORM\Column]
    private ?bool $gp_estadotransferencia = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $gp_metodocobro = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGpTotal(): ?float
    {
        return $this->gp_total;
    }

    public function setGpTotal(float $gp_total): static
    {
        $this->gp_total = $gp_total;

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

    public function getGpFechaoperacion(): ?\DateTimeInterface
    {
        return $this->gp_fechaoperacion;
    }

    public function setGpFechaoperacion(\DateTimeInterface $gp_fechaoperacion): static
    {
        $this->gp_fechaoperacion = $gp_fechaoperacion;

        return $this;
    }

    public function isGpEstadotransferencia(): ?bool
    {
        return $this->gp_estadotransferencia;
    }

    public function setGpEstadotransferencia(bool $gp_estadotransferencia): static
    {
        $this->gp_estadotransferencia = $gp_estadotransferencia;

        return $this;
    }

    public function getGpMetodocobro(): ?string
    {
        return $this->gp_metodocobro;
    }

    public function setGpMetodocobro(?string $gp_metodocobro): static
    {
        $this->gp_metodocobro = $gp_metodocobro;

        return $this;
    }
}