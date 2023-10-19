<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
class Persona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $p_nombre = null;

    #[ORM\Column(length: 64)]
    private ?string $p_apellido = null;

    #[ORM\Column(length: 9)]
    private ?string $p_contacto = null;

    #[ORM\Column(length: 255)]
    private ?string $p_direccion = null;

    #[ORM\Column(length: 64)]
    private ?string $p_correo = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $p_servicio = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $p_foto = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $p_cv = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $p_antpen = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $p_cert = null;

    #[ORM\OneToMany(mappedBy: 'idPersona', targetEntity: PersonaServicio::class)]
    private Collection $servicios;

    #[ORM\OneToOne(inversedBy: 'idPersona', cascade: ['persist', 'remove'])]
    private ?Usuario $usuario = null;

    public function __construct()
    {
        $this->servicios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPContacto(): ?string
    {
        return $this->p_contacto;
    }

    public function setPContacto(string $p_contacto): static
    {
        $this->p_contacto = $p_contacto;

        return $this;
    }


    /**
     * @return Collection<int, PersonaServicio>
     */
    public function getServicios(): Collection
    {
        return $this->servicios;
    }

    public function addServicio(PersonaServicio $servicio): static
    {
        if (!$this->servicios->contains($servicio)) {
            $this->servicios->add($servicio);
            $servicio->setIdPersona($this);
        }

        return $this;
    }

    public function removeServicio(PersonaServicio $servicio): static
    {
        if ($this->servicios->removeElement($servicio)) {
            // set the owning side to null (unless already changed)
            if ($servicio->getIdPersona() === $this) {
                $servicio->setIdPersona(null);
            }
        }

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getPNombre(): ?string
    {
        return $this->p_nombre;
    }

    public function setPNombre(string $p_nombre): static
    {
        $this->p_nombre = $p_nombre;

        return $this;
    }

    public function getPApellido(): ?string
    {
        return $this->p_apellido;
    }

    public function setPApellido(string $p_apellido): static
    {
        $this->p_apellido = $p_apellido;

        return $this;
    }

    public function getPDireccion(): ?string
    {
        return $this->p_direccion;
    }

    public function setPDireccion(string $p_direccion): static
    {
        $this->p_direccion = $p_direccion;

        return $this;
    }

    public function getPCorreo(): ?string
    {
        return $this->p_correo;
    }

    public function setPCorreo(string $p_correo): static
    {
        $this->p_correo = $p_correo;

        return $this;
    }

    public function getPServicio(): ?string
    {
        return $this->p_servicio;
    }

    public function setPServicio(?string $p_servicio): static
    {
        $this->p_servicio = $p_servicio;

        return $this;
    }

    public function getPFoto(): ?string
    {
        return $this->p_foto;
    }

    public function setPFoto(?string $p_foto): static
    {
        $this->p_foto = $p_foto;

        return $this;
    }

    public function getPCv(): ?string
    {
        return $this->p_cv;
    }

    public function setPCv(?string $p_cv): static
    {
        $this->p_cv = $p_cv;

        return $this;
    }

    public function getPAntpen(): ?string
    {
        return $this->p_antpen;
    }

    public function setPAntpen(?string $p_antpen): static
    {
        $this->p_antpen = $p_antpen;

        return $this;
    }

    public function getPCert(): ?string
    {
        return $this->p_cert;
    }

    public function setPCert(?string $p_cert): static
    {
        $this->p_cert = $p_cert;

        return $this;
    }

    
    
}
