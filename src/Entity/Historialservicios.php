<?php

namespace App\Entity;

use App\Repository\HistorialserviciosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistorialserviciosRepository::class)]
class Historialservicios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $hs_fecha = null;

    #[ORM\ManyToOne(inversedBy: 'historialservicio')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Servicio $idservicio = null;

    #[ORM\ManyToOne(inversedBy: 'histservcliente')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $idcliente = null;

    #[ORM\ManyToOne(inversedBy: 'histservproveedor')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $idproveedor = null;

    #[ORM\Column]
    private ?bool $hs_estado = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hs_estadopago = null;

    #[ORM\Column(nullable: true)]
    private ?float $hs_importe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHsFecha(): ?\DateTimeInterface
    {
        return $this->hs_fecha;
    }

    public function setHsFecha(\DateTimeInterface $hs_fecha): static
    {
        $this->hs_fecha = $hs_fecha;

        return $this;
    }

    public function getIdservicio(): ?Servicio
    {
        return $this->idservicio;
    }

    public function setIdservicio(?Servicio $idservicio): static
    {
        $this->idservicio = $idservicio;

        return $this;
    }

    public function getIdcliente(): ?Persona
    {
        return $this->idcliente;
    }

    public function setIdcliente(?Persona $idcliente): static
    {
        $this->idcliente = $idcliente;

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

    public function isHsEstado(): ?bool
    {
        return $this->hs_estado;
    }

    public function setHsEstado(bool $hs_estado): static
    {
        $this->hs_estado = $hs_estado;

        return $this;
    }

    public function isHsEstadopago(): ?bool
    {
        return $this->hs_estadopago;
    }

    public function setHsEstadopago(?bool $hs_estadopago): static
    {
        $this->hs_estadopago = $hs_estadopago;

        return $this;
    }

    public function getHsImporte(): ?float
    {
        return $this->hs_importe;
    }

    public function setHsImporte(?float $hs_importe): static
    {
        $this->hs_importe = $hs_importe;

        return $this;
    }
}
