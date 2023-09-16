<?php

namespace App\Entity;

use App\Repository\ConvocatoriaRepository;
use App\Entity\Eventos;
use App\Entity\Jugador;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConvocatoriaRepository::class)]
class Convocatoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $asistencia = null;

    #[ORM\Column(nullable: true)]
    private ?bool $justificado = null;

    #[ORM\Column(nullable: true)]
    private ?bool $titular = null;

    #[ORM\Column(nullable: true)]
    private ?int $minutos = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comentario = null;

    public function __construct(
        #[ORM\ManyToOne(inversedBy: 'convocatorias', targetEntity: "Eventos")]
        #[ORM\JoinColumn(name: 'evento_id', onDelete: 'CASCADE', referencedColumnName: 'id')]
        private ?eventos $evento = null,
        #[ORM\ManyToOne(inversedBy: 'convocatorias')]
        private ?jugador $jugador = null
    )
    {
        $this->setEvento($evento);
        $this->setJugador($jugador);
    }    

    public static function create(
        Eventos $evento, 
       ?Jugador $jugador): self 
  
  {

      return new self($evento, $jugador);
  }    
  
  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvento(): ?eventos
    {
        return $this->evento;
    }

    public function setEvento(?eventos $evento): static
    {
        $this->evento = $evento;

        return $this;
    }

    public function getJugador(): ?jugador
    {
        return $this->jugador;
    }

    public function setJugador(?jugador $jugador): static
    {
        $this->jugador = $jugador;

        return $this;
    }

    public function getAsistencia(): ?int
    {
        return $this->asistencia;
    }

    public function setAsistencia(?int $asistencia): static
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    public function isJustificado(): ?bool
    {
        return $this->justificado;
    }

    public function setJustificado(?bool $justificado): static
    {
        $this->justificado = $justificado;

        return $this;
    }

    public function isTitular(): ?bool
    {
        return $this->titular;
    }

    public function setTitular(?bool $titular): static
    {
        $this->titular = $titular;

        return $this;
    }

    public function getMinutos(): ?int
    {
        return $this->minutos;
    }

    public function setMinutos(?int $minutos): static
    {
        $this->minutos = $minutos;

        return $this;
    }

    public function update(
        ?int $asistencia,
        ?bool $justificado,
        ?bool $titular,
        ?int $minutos,
        ?String $comentario
    ) {
        $this->asistencia = $asistencia;
        $this->justificado = $justificado;
        $this->titular = $titular;
        $this->minutos = $minutos;
        $this->comentario = $comentario;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

}
