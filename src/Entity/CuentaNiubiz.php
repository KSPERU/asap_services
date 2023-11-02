<?php

namespace App\Entity;

use App\Repository\CuentaNiubizRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CuentaNiubizRepository::class)]
class CuentaNiubiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $cn_codigocomercio = null;

    #[ORM\Column(length: 50)]
    private ?string $cn_usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $cn_password = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $moneda = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $liquidacion = null;

    #[ORM\ManyToOne(inversedBy: 'cuentaniubiz')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $cd_idproveedor = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnCodigocomercio(): ?string
    {
        return $this->cn_codigocomercio;
    }

    public function setCnCodigocomercio(string $cn_codigocomercio): static
    {
        $this->cn_codigocomercio = $cn_codigocomercio;

        return $this;
    }

    public function getCnUsuario(): ?string
    {
        return $this->cn_usuario;
    }

    public function setCnUsuario(string $cn_usuario): static
    {
        $this->cn_usuario = $cn_usuario;

        return $this;
    }

    public function getCnPassword(): ?string
    {
        return $this->cn_password;
    }

    public function setCnPassword(string $cn_password): static
    {
        $this->cn_password = $cn_password;

        return $this;
    }

    public function getMoneda(): ?string
    {
        return $this->moneda;
    }

    public function setMoneda(?string $moneda): static
    {
        $this->moneda = $moneda;

        return $this;
    }

    public function getLiquidacion(): ?string
    {
        return $this->liquidacion;
    }

    public function setLiquidacion(?string $liquidacion): static
    {
        $this->liquidacion = $liquidacion;

        return $this;
    }

    public function getCdIdproveedor(): ?Persona
    {
        return $this->cd_idproveedor;
    }

    public function setCdIdproveedor(?Persona $cd_idproveedor): static
    {
        $this->cd_idproveedor = $cd_idproveedor;

        return $this;
    }

}
