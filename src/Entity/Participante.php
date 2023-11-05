<?php

namespace App\Entity;

use App\Repository\ParticipanteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ParticipanteRepository::class)]
#[Broadcast]
class Participante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $mensaje_leido = null;

    #[ORM\ManyToOne(inversedBy: 'participantes')]
    private ?Usuario $usuario_id = null;

    #[ORM\ManyToOne(inversedBy: 'participantes')]
    private ?Conversacion $conversacion_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensajeLeido(): ?\DateTimeInterface
    {
        return $this->mensaje_leido;
    }

    public function setMensajeLeido(?\DateTimeInterface $mensaje_leido): static
    {
        $this->mensaje_leido = $mensaje_leido;

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
}
