<?php

namespace App\Form\Model;

use App\Entity\Message;
use App\Entity\Equipo;
use DateTime;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\Extension\Core\DataTransformer\UuidToStringTransformer;

class MessageDto
{
    public ?string $id = null;
    public ?string $user = null;
    public ?string $text = null;
    public ?string $createdAt = null;
    public ?string $equipo = null;
    public ?string $image = null;
    public ?string $video = null;
    public ?string $audio = null;
    public ?Boolean $sistema = null;
    public ?Boolean $sent = null;
    public ?Boolean $receive = null;
    public ?Boolean $pending = null;

    public function __construct()
    {
        
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function cretaFromMessage(Message $message): self
    {
         
        $dto = new self();
        $dto->user = $message->getUser()->getId();
        $dto->text = $message->getText();
        $dto->createdAt =$message->getCreatedAt();
        $dto->image = $message->getImage();
        $dto->video = $message->getVideo();
        $dto->audio = $message->getAudio();
        $dto->sistema = $message->isSistema();
        $dto->sent = $message->isSent();
        $dto->receive  =$message->isReceived();
        $dto->pending = $message->isPending();
        $dto->equipo = $message->getEquipo()->getId();


        return $dto;
    }


   

    public function getId(): ?string { return $this->id; }
    public function setId(?string $id): self { $this->id = $id; return $this; }

    public function getUser(): ?string { return $this->user; }
    public function setUser(?string $user): self { $this->user = $user; return $this; }

    public function getText(): ?string { return $this->text; }
    public function setText(?string $text): self { $this->text = $text; return $this; }

    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(?string $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): self { $this->image = $image; return $this; }

    public function getVideo(): ?string { return $this->video; }
    public function setVideo(?string $video): self { $this->video = $video; return $this; }

    public function getAudio(): ?string { return $this->audio; }
    public function setAudio(?string $audio): self { $this->audio = $audio; return $this; }

    public function getSistema(): ?Boolean { return $this->sistema; }
    public function setSistema(?Boolean $sistema): self { $this->sistema = $sistema; return $this; }

    public function getSent(): ?Boolean { return $this->sent; }
    public function setSent(?Boolean $sent): self { $this->sent = $sent; return $this; }

    public function getReceive(): ?Boolean { return $this->receive; }
    public function setReceive(?Boolean $receive): self { $this->receive = $receive; return $this; }

    public function getPending(): ?Boolean { return $this->pending; }
    public function setPending(?Boolean $pending): self { $this->pending = $pending; return $this; }

    public function getEquipo(): ?string { return $this->equipo; }
    public function setEquipo(?string $equipo): self { $this->equipo = $equipo; return $this; }
}