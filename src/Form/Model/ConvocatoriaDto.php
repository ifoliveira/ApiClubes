<?php

namespace App\Form\Model;

use App\Entity\Convocatoria;
use App\Entity\Eventos;
use App\Entity\Jugador;
use Symfony\Component\Form\Extension\Core\DataTransformer\UuidToStringTransformer;

class ConvocatoriaDto
{
    public ?string $evento = null;
    public ?array $jugador = [];
    public ?int $asistencia = null;
    public ?bool $justificado = null;
    public ?bool $titular = null;
    public ?int $minutos = null;
    public ?string $comentario =null;

    public function __construct()
    {
        $this->jugador = [];
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function cretaFromConvocatoria(Convocatoria $convocatoria): self
    {
         
        $dto = new self();
        $dto->evento = $convocatoria->getEvento()->getId();
        $dto->asistencia = $convocatoria->getAsistencia();
        $dto->justificado =$convocatoria->isJustificado();
        $dto->titular = $convocatoria->isTitular();
        $dto->minutos = $convocatoria->getMinutos();
        $dto->comentario = $convocatoria->getComentario();

        return $dto;
    }


    public function getJugador(): ?array { return $this->jugador; }
    public function setJugador(?array $jugador): self { $this->jugador = $jugador; return $this; }

    public function getAsistencia(): ?int { return $this->asistencia;}
    public function setAsistencia(?int $asistencia): self { $this->asistencia = $asistencia; return $this; }

    public function getJustificado(): ?bool { return $this->justificado;}
    public function setJustificado(?bool $justificado): self { $this->justificado = $justificado; return $this; }

    public function getTitular(): ?bool { return $this->titular;}
    public function setTitular(?bool $titular): self { $this->titular = $titular; return $this; }

    public function getMinutos(): ?int { return $this->minutos;}
    public function setMinutos(?int $minutos): self { $this->minutos = $minutos; return $this; }

    public function getEvento(): ?string { return $this->evento; }
    public function setEvento(?string $evento): self { $this->evento = $evento; return $this; }


    public function getComentario(): ?string { return $this->comentario; }
    public function setComentario(?string $comentario): self { $this->comentario = $comentario; return $this; }    
}