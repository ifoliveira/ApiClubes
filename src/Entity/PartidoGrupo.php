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

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $aliasLocal = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $aliasVisitante = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $localizacion = null;

    #[ORM\Column(length: 20,  nullable: true)]
    private ?string $penalties = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaFin = null;

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

    public function getFase(): ?string
    {
        return 'Fase de Grupos';
    }    

    public function getMinutoEnJuego(): ?int
    {
        if ($this->estado === 'En Juego' && $this->fechaInicio !== null) {
            $now = new \DateTime();
            $interval = $this->fechaInicio->diff($now);
            $minutos = ($interval->h * 60) + $interval->i;
    
            // Si hay diferencia de segundos, sumar 1 aunque los minutos completos sean 0
            if ($minutos === 0 && $interval->s > 0) {
                return 1;
            }
    
            return max(1, $minutos);
        }
    
        return null;
    }


    public function getAliasLocal(): ?string
    {
        return $this->aliasLocal;
    }

    public function setAliasLocal(?string $aliasLocal): static
    {
        $this->aliasLocal = $aliasLocal;

        return $this;
    }

    public function getAliasVisitante(): ?string
    {
        return $this->aliasVisitante;
    }

    public function setAliasVisitante(?string $aliasVisitante): static
    {
        $this->aliasVisitante = $aliasVisitante;

        return $this;
    }

    public function getLocalizacion(): ?string
    {
        return $this->localizacion;
    }

    public function setLocalizacion(?string $localizacion): static
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    public function getPenalties(): ?string
    {
        return $this->penalties;
    }

    public function setPenalties(string $penalties): static
    {
        $this->penalties = $penalties;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(?\DateTimeInterface $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): static
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }    
}
