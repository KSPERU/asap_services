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

    #[ORM\OneToMany(mappedBy: 'idservicio', targetEntity: Historialservicios::class)]
    private Collection $historialservicio;

    #[ORM\OneToMany(mappedBy: 'servicio', targetEntity: Favorito::class)]
    private Collection $favoritos;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
        $this->historialservicio = new ArrayCollection();
        $this->favoritos = new ArrayCollection();
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

    /**
     * @return Collection<int, Historialservicios>
     */
    public function getHistorialservicio(): Collection
    {
        return $this->historialservicio;
    }

    public function addHistorialservicio(Historialservicios $historialservicio): static
    {
        if (!$this->historialservicio->contains($historialservicio)) {
            $this->historialservicio->add($historialservicio);
            $historialservicio->setIdservicio($this);
        }

        return $this;
    }

    public function removeHistorialservicio(Historialservicios $historialservicio): static
    {
        if ($this->historialservicio->removeElement($historialservicio)) {
            // set the owning side to null (unless already changed)
            if ($historialservicio->getIdservicio() === $this) {
                $historialservicio->setIdservicio(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->sv_nombre;
    }

    /**
     * @return Collection<int, Favorito>
     */
    public function getFavoritos(): Collection
    {
        return $this->favoritos;
    }

    public function addFavorito(Favorito $favorito): static
    {
        if (!$this->favoritos->contains($favorito)) {
            $this->favoritos->add($favorito);
            $favorito->setServicio($this);
        }

        return $this;
    }

    public function removeFavorito(Favorito $favorito): static
    {
        if ($this->favoritos->removeElement($favorito)) {
            // set the owning side to null (unless already changed)
            if ($favorito->getServicio() === $this) {
                $favorito->setServicio(null);
            }
        }

        return $this;
    }
}
