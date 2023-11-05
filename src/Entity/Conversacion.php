<?php

namespace App\Entity;

use App\Repository\ConversacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ConversacionRepository::class)]
#[Broadcast]
class Conversacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'conversacion_id', targetEntity: Chat::class)]
    private Collection $chats;

    #[ORM\ManyToOne(inversedBy: 'conversacions')]
    private ?Chat $ultimo_mensaje_id = null;

    #[ORM\OneToMany(mappedBy: 'conversacion_id', targetEntity: Participante::class)]
    private Collection $participantes;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
        $this->participantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): static
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->setConversacionId($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): static
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getConversacionId() === $this) {
                $chat->setConversacionId(null);
            }
        }

        return $this;
    }

    public function getUltimoMensajeId(): ?Chat
    {
        return $this->ultimo_mensaje_id;
    }

    public function setUltimoMensajeId(?Chat $ultimo_mensaje_id): static
    {
        $this->ultimo_mensaje_id = $ultimo_mensaje_id;

        return $this;
    }

    /**
     * @return Collection<int, Participante>
     */
    public function getParticipantes(): Collection
    {
        return $this->participantes;
    }

    public function addParticipante(Participante $participante): static
    {
        if (!$this->participantes->contains($participante)) {
            $this->participantes->add($participante);
            $participante->setConversacionId($this);
        }

        return $this;
    }

    public function removeParticipante(Participante $participante): static
    {
        if ($this->participantes->removeElement($participante)) {
            // set the owning side to null (unless already changed)
            if ($participante->getConversacionId() === $this) {
                $participante->setConversacionId(null);
            }
        }

        return $this;
    }
}
