<?php

namespace App\Entity;

use App\Repository\MetodocobroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MetodocobroRepository::class)]
class Metodocobro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $mc_descripcion = null;

    #[ORM\OneToMany(mappedBy: 'idmetcobro', targetEntity: MetcobroProveedor::class, orphanRemoval: true)]
    private Collection $metcobroproveedor;

    public function __construct()
    {
        $this->metcobroproveedor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMcDescripcion(): ?string
    {
        return $this->mc_descripcion;
    }

    public function setMcDescripcion(string $mc_descripcion): static
    {
        $this->mc_descripcion = $mc_descripcion;

        return $this;
    }

    /**
     * @return Collection<int, MetcobroProveedor>
     */
    public function getMetcobroproveedor(): Collection
    {
        return $this->metcobroproveedor;
    }

    public function addMetcobroproveedor(MetcobroProveedor $metcobroproveedor): static
    {
        if (!$this->metcobroproveedor->contains($metcobroproveedor)) {
            $this->metcobroproveedor->add($metcobroproveedor);
            $metcobroproveedor->setIdmetcobro($this);
        }

        return $this;
    }

    public function removeMetcobroproveedor(MetcobroProveedor $metcobroproveedor): static
    {
        if ($this->metcobroproveedor->removeElement($metcobroproveedor)) {
            // set the owning side to null (unless already changed)
            if ($metcobroproveedor->getIdmetcobro() === $this) {
                $metcobroproveedor->setIdmetcobro(null);
            }
        }

        return $this;
    }
}
