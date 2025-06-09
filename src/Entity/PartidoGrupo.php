<?php

namespace App\Entity;

use App\Repository\PartidoGrupoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidoGrupoRepository::class)]
class PartidoGrupo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'partidoGrupos')]
    private ?Grupo $grupo = null;

    #[ORM\ManyToOne(inversedBy: 'partidoGrupos')]
    private ?EquipoTorneo $local = null;

    #[ORM\ManyToOne(inversedBy: 'partidoGrupos')]
    private ?EquipoTorneo $visitante = null;

    #[ORM\Column(nullable: true)]
    private ?int $golesLocal = null;

    #[ORM\Column(nullable: true)]
    private ?int $golesVisitante = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): static
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getLocal(): ?EquipoTorneo
    {
        return $this->local;
    }

    public function setLocal(?EquipoTorneo $local): static
    {
        $this->local = $local;

        return $this;
    }

    public function getVisitante(): ?EquipoTorneo
    {
        return $this->visitante;
    }

    public function setVisitante(?EquipoTorneo $visitante): static
    {
        $this->visitante = $visitante;

        return $this;
    }

    public function getGolesLocal(): ?int
    {
        return $this->golesLocal;
    }

    public function setGolesLocal(?int $golesLocal): static
    {
        $this->golesLocal = $golesLocal;

        return $this;
    }

    public function getGolesVisitante(): ?int
    {
        return $this->golesVisitante;
    }

    public function setGolesVisitante(?int $golesVisitante): static
    {
        $this->golesVisitante = $golesVisitante;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getMinutoEnJuego(): ?int
    {
        if ($this->estado === 'En Juego' && $this->fecha !== null) {
            $now = new \DateTime();
            $interval = $this->fecha->diff($now);
            return ($interval->h * 60) + $interval->i;
        }
    
        return null;
    }    
}
