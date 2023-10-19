<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
class Persona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9)]
    private ?string $p_contacto = null;

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

    
    
}
