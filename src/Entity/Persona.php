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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $p_biografia = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $p_experiencia = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $p_distrito = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $p_habilidades = null;

    #[ORM\OneToMany(mappedBy: 'idcliente', targetEntity: Historialservicios::class)]
    private Collection $histservcliente;

    #[ORM\OneToMany(mappedBy: 'idproveedor', targetEntity: Historialservicios::class)]
    private Collection $histservproveedor;

    #[ORM\OneToMany(mappedBy: 'idproveedor', targetEntity: MetcobroProveedor::class, orphanRemoval: true)]
    private Collection $metcobro;

    #[ORM\OneToMany(mappedBy: 'cd_idproveedor', targetEntity: CuentaNiubiz::class, orphanRemoval: true)]
    private Collection $cuentaniubiz;

    #[ORM\OneToOne(mappedBy: 'persona', cascade: ['persist', 'remove'])]
    private ?Codigo $codigo = null;

    #[ORM\OneToMany(mappedBy: 'persona', targetEntity: Calificacion::class)]
    private Collection $p_calificacion;

    #[ORM\OneToMany(mappedBy: 'persona', targetEntity: Tarjeta::class)]
    private Collection $p_tarjeta;

    #[ORM\OneToMany(mappedBy: 'idproveedor', targetEntity: GananciaProveedor::class, orphanRemoval: true)]
    private Collection $gananciaproveedor;

    #[ORM\OneToMany(mappedBy: 'persona', targetEntity: Favorito::class)]
    private Collection $favoritos;

    #[ORM\OneToOne(inversedBy: 'personacodigo', cascade: ['persist', 'remove'])]
    private ?Promocion $promocion = null;

    public function __construct()
    {
        $this->servicios = new ArrayCollection();
        $this->histservcliente = new ArrayCollection();
        $this->histservproveedor = new ArrayCollection();
        $this->p_calificacion = new ArrayCollection();
        $this->metcobro = new ArrayCollection();
        $this->cuentaniubiz = new ArrayCollection();
        $this->p_tarjeta = new ArrayCollection();
        $this->gananciaproveedor = new ArrayCollection();
        $this->favoritos = new ArrayCollection();
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

    public function getPBiografia(): ?string
    {
        return $this->p_biografia;
    }

    public function setPBiografia(?string $p_biografia): static
    {
        $this->p_biografia = $p_biografia;

        return $this;
    }

    public function getPExperiencia(): ?string
    {
        return $this->p_experiencia;
    }

    public function setPExperiencia(?string $p_experiencia): static
    {
        $this->p_experiencia = $p_experiencia;

        return $this;
    }

    public function getPDistrito(): ?string
    {
        return $this->p_distrito;
    }

    public function setPDistrito(?string $p_distrito): static
    {
        $this->p_distrito = $p_distrito;

        return $this;
    }

    public function getPHabilidades(): ?string
    {
        return $this->p_habilidades;
    }

    public function setPHabilidades(?string $p_habilidades): static
    {
        $this->p_habilidades = $p_habilidades;

        return $this;
    }

    /**
     * @return Collection<int, Historialservicios>
     */
    public function getHistservcliente(): Collection
    {
        return $this->histservcliente;
    }

    public function addHistservcliente(Historialservicios $histservcliente): static
    {
        if (!$this->histservcliente->contains($histservcliente)) {
            $this->histservcliente->add($histservcliente);
            $histservcliente->setIdcliente($this);
        }

        return $this;
    }

    public function removeHistservcliente(Historialservicios $histservcliente): static
    {
        if ($this->histservcliente->removeElement($histservcliente)) {
            // set the owning side to null (unless already changed)
            if ($histservcliente->getIdcliente() === $this) {
                $histservcliente->setIdcliente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Historialservicios>
     */
    public function getHistservproveedor(): Collection
    {
        return $this->histservproveedor;
    }

    public function addHistservproveedor(Historialservicios $histservproveedor): static
    {
        if (!$this->histservproveedor->contains($histservproveedor)) {
            $this->histservproveedor->add($histservproveedor);
            $histservproveedor->setIdproveedor($this);
        }

        return $this;
    }

    public function removeHistservproveedor(Historialservicios $histservproveedor): static
    {
        if ($this->histservproveedor->removeElement($histservproveedor)) {
            // set the owning side to null (unless already changed)
            if ($histservproveedor->getIdproveedor() === $this) {
                $histservproveedor->setIdproveedor(null);
            }
        }

        return $this;
    }

    public function getCodigo(): ?Codigo
    {
        return $this->codigo;
    }

    public function setCodigo(?Codigo $codigo): static
    {
        // unset the owning side of the relation if necessary
        if ($codigo === null && $this->codigo !== null) {
            $this->codigo->setPersona(null);
        }

        // set the owning side of the relation if necessary
        if ($codigo !== null && $codigo->getPersona() !== $this) {
            $codigo->setPersona($this);
        }

        $this->codigo = $codigo;

        return $this;
    }

    /**
     * @return Collection<int, Calificacion>
     */
    public function getPCalificacion(): Collection
    {
        return $this->p_calificacion;
    }

    public function addPCalificacion(Calificacion $pCalificacion): static
    {
        if (!$this->p_calificacion->contains($pCalificacion)) {
            $this->p_calificacion->add($pCalificacion);
            $pCalificacion->setPersona($this);
        }

        return $this;
    }

    public function removePCalificacion(Calificacion $pCalificacion): static
    {
        if ($this->p_calificacion->removeElement($pCalificacion)) {
            // set the owning side to null (unless already changed)
            if ($pCalificacion->getPersona() === $this) {
                $pCalificacion->setPersona(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection<int, MetcobroProveedor>
     */
    public function getMetcobro(): Collection
    {
        return $this->metcobro;
    }

    public function addMetcobro(MetcobroProveedor $metcobro): static
    {
        if (!$this->metcobro->contains($metcobro)) {
            $this->metcobro->add($metcobro);
            $metcobro->setIdproveedor($this);
        }

        return $this;
    }

    public function removeMetcobro(MetcobroProveedor $metcobro): static
    {
        if ($this->metcobro->removeElement($metcobro)) {
            // set the owning side to null (unless already changed)
            if ($metcobro->getIdproveedor() === $this) {
                $metcobro->setIdproveedor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CuentaNiubiz>
     */
    public function getCuentaniubiz(): Collection
    {
        return $this->cuentaniubiz;
    }

    public function addCuentaniubiz(CuentaNiubiz $cuentaniubiz): static
    {
        if (!$this->cuentaniubiz->contains($cuentaniubiz)) {
            $this->cuentaniubiz->add($cuentaniubiz);
            $cuentaniubiz->setCdIdproveedor($this);
        }

        return $this;
    }

    public function removeCuentaniubiz(CuentaNiubiz $cuentaniubiz): static
    {
        if ($this->cuentaniubiz->removeElement($cuentaniubiz)) {
            // set the owning side to null (unless already changed)
            if ($cuentaniubiz->getCdIdproveedor() === $this) {
                $cuentaniubiz->setCdIdproveedor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tarjeta>
     */
    public function getPTarjeta(): Collection
    {
        return $this->p_tarjeta;
    }

    public function addPTarjetum(Tarjeta $pTarjetum): static
    {
        if (!$this->p_tarjeta->contains($pTarjetum)) {
            $this->p_tarjeta->add($pTarjetum);
            $pTarjetum->setPersona($this);
        }

        return $this;
    }

    public function removePTarjetum(Tarjeta $pTarjetum): static
    {
        if ($this->p_tarjeta->removeElement($pTarjetum)) {
            // set the owning side to null (unless already changed)
            if ($pTarjetum->getPersona() === $this) {
                $pTarjetum->setPersona(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection<int, GananciaProveedor>
     */
    public function getGananciaproveedor(): Collection
    {
        return $this->gananciaproveedor;
    }

    public function addGananciaproveedor(GananciaProveedor $gananciaproveedor): static
    {
        if (!$this->gananciaproveedor->contains($gananciaproveedor)) {
            $this->gananciaproveedor->add($gananciaproveedor);
            $gananciaproveedor->setIdproveedor($this);
        }

        return $this;
    }

    public function removeGananciaproveedor(GananciaProveedor $gananciaproveedor): static
    {
        if ($this->gananciaproveedor->removeElement($gananciaproveedor)) {
            // set the owning side to null (unless already changed)
            if ($gananciaproveedor->getIdproveedor() === $this) {
                $gananciaproveedor->setIdproveedor(null);
            }
        }

        return $this;
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
            $favorito->setPersona($this);
        }

        return $this;
    }

    public function removeFavorito(Favorito $favorito): static
    {
        if ($this->favoritos->removeElement($favorito)) {
            // set the owning side to null (unless already changed)
            if ($favorito->getPersona() === $this) {
                $favorito->setPersona(null);
            }
        }

        return $this;
    }

    public function getPromocion(): ?Promocion
    {
        return $this->promocion;
    }

    public function setPromocion(?Promocion $promocion): static
    {
        $this->promocion = $promocion;

        return $this;
    }
    
    
}
