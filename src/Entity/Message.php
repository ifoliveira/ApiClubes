<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\User;
use App\Entity\Equipo;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{





    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $audio = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sistema = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sent = null;

    #[ORM\Column(nullable: true)]
    private ?bool $received = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pending = null;


    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        protected UuidInterface|string $id,
        #[ORM\Column(type: Types::TEXT)]
        private ?string $text = null,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null,
        #[ORM\Column]
        private ?\DateTime $createdAt = null,
        #[ORM\ManyToOne(inversedBy: 'messages')]
        private ?Equipo $equipo = null        
    )
     {}

    public static function create(
        ?UuidInterface $Uuid,
        ?user $user,
        ?string $text,
        ?DateTime $createdAt,
        ?equipo $equipo
        ): self

  {

      return new self($Uuid, $text, $user, $createdAt, $equipo);
  }            

    
    public function getId(): ?string
    {
        return UUID::fromString($this->id); 
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(?string $audio): static
    {
        $this->audio = $audio;

        return $this;
    }

    public function isSistema(): ?bool
    {
        return $this->sistema;
    }

    public function setSistema(?bool $sistema): static
    {
        $this->sistema = $sistema;

        return $this;
    }

    public function isSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(?bool $sent): static
    {
        $this->sent = $sent;

        return $this;
    }

    public function isReceived(): ?bool
    {
        return $this->received;
    }

    public function setReceived(?bool $received): static
    {
        $this->received = $received;

        return $this;
    }

    public function isPending(): ?bool
    {
        return $this->pending;
    }

    public function setPending(?bool $pending): static
    {
        $this->pending = $pending;

        return $this;
    }

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): static
    {
        $this->equipo = $equipo;

        return $this;
    }
}
