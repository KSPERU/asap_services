<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[Broadcast]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mensaje = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_creacion = null;

    #[ORM\ManyToOne(inversedBy: 'chats')]
    private ?Usuario $usuario_id = null;

    #[ORM\ManyToOne(inversedBy: 'chats')]
    private ?Conversacion $conversacion_id = null;

    #[ORM\OneToMany(mappedBy: 'ultimo_mensaje_id', targetEntity: Conversacion::class)]
    private Collection $conversacions;

    public function __construct()
    {
        $this->fecha_creacion = new \DateTimeImmutable();
        $this->conversacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje): static
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(?\DateTimeInterface $fecha_creacion): static
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getUsuarioId(): ?Usuario
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(?Usuario $usuario_id): static
    {
        $this->usuario_id = $usuario_id;

        return $this;
    }

    public function getConversacionId(): ?Conversacion
    {
        return $this->conversacion_id;
    }

    public function setConversacionId(?Conversacion $conversacion_id): static
    {
        $this->conversacion_id = $conversacion_id;

        return $this;
    }

    /**
     * @return Collection<int, Conversacion>
     */
    public function getConversacions(): Collection
    {
        return $this->conversacions;
    }

    public function addConversacion(Conversacion $conversacion): static
    {
        if (!$this->conversacions->contains($conversacion)) {
            $this->conversacions->add($conversacion);
            $conversacion->setUltimoMensajeId($this);
        }

        return $this;
    }

    public function removeConversacion(Conversacion $conversacion): static
    {
        if ($this->conversacions->removeElement($conversacion)) {
            // set the owning side to null (unless already changed)
            if ($conversacion->getUltimoMensajeId() === $this) {
                $conversacion->setUltimoMensajeId(null);
            }
        }

        return $this;
    }
}
