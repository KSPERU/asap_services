<?php

namespace App\Entity;

use App\Repository\ServicioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServicioRepository::class)]
class Servicio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $sv_nombre = null;

    #[ORM\OneToMany(mappedBy: 'idServicio', targetEntity: PersonaServicio::class)]
    private Collection $personas;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $svimagen = null;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSvNombre(): ?string
    {
        return $this->sv_nombre;
    }

    public function setSvNombre(string $sv_nombre): static
    {
        $this->sv_nombre = $sv_nombre;

        return $this;
    }

    /**
     * @return Collection<int, PersonaServicio>
     */
    public function getPersonas(): Collection
    {
        return $this->personas;
    }

    public function addPersona(PersonaServicio $persona): static
    {
        if (!$this->personas->contains($persona)) {
            $this->personas->add($persona);
            $persona->setIdServicio($this);
        }

        return $this;
    }

    public function removePersona(PersonaServicio $persona): static
    {
        if ($this->personas->removeElement($persona)) {
            // set the owning side to null (unless already changed)
            if ($persona->getIdServicio() === $this) {
                $persona->setIdServicio(null);
            }
        }

        return $this;
    }

    public function getSvimagen(): ?string
    {
        return $this->svimagen;
    }

    public function setSvimagen(?string $svimagen): static
    {
        $this->svimagen = $svimagen;

        return $this;
    }
}
