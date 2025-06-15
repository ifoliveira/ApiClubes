<?php

namespace App\Entity;

use App\Repository\PartidoFinalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidoFinalRepository::class)]
class PartidoFinal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'partidoFinals')]
    private ?EquipoTorneo $local = null;

    #[ORM\ManyToOne(inversedBy: 'partidoFinals')]
    private ?EquipoTorneo $visitante = null;

    #[ORM\Column(nullable: true)]
    private ?int $golesLocal = null;

    #[ORM\Column(nullable: true)]
    private ?int $golesVisitante = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $penalties = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?CruceFinal $cruceFinal = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $estado = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $aliasLocal = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $aliasVisitante = null;

    #[ORM\ManyToOne(inversedBy: 'partidoFinals')]
    private ?Torneos $torneo = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $fase = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $localizacion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaFin = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPenalties(): ?string
    {
        return $this->penalties;
    }

    public function setPenalties(?string $penalties): static
    {
        $this->penalties = $penalties;

        return $this;
    }

    public function getCruceFinal(): ?CruceFinal
    {
        return $this->cruceFinal;
    }

    public function setCruceFinal(?CruceFinal $cruceFinal): static
    {
        $this->cruceFinal = $cruceFinal;

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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
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

    public function getTorneo(): ?Torneos
    {
        return $this->torneo;
    }

    public function setTorneo(?Torneos $torneo): static
    {
        $this->torneo = $torneo;

        return $this;
    }

    public function getFase(): ?string
    {
        return $this->fase;
    }

    public function setFase(?string $fase): static
    {
        $this->fase = $fase;

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

    public function getGanador(): ?EquipoTorneo
    {
        if ($this->golesLocal === null || $this->golesVisitante === null) {
            return null;
        }
    
        if ($this->golesLocal > $this->golesVisitante) {
            return $this->local;
        } elseif ($this->golesVisitante > $this->golesLocal) {
            return $this->visitante;
        }
    
        // Si hay empate, mirar penalties
        if ($this->penalties) {
            [$penLocal, $penVisitante] = explode('-', $this->penalties);
            if ((int)$penLocal > (int)$penVisitante) {
                return $this->local;
            } elseif ((int)$penVisitante > (int)$penLocal) {
                return $this->visitante;
            }
        }
    
        return null;
    }


    public function getPerdedor(): ?EquipoTorneo
    {
        if ($this->golesLocal === null || $this->golesVisitante === null) {
            return null;
        }
    
        if ($this->golesLocal > $this->golesVisitante) {
            return $this->visitante;
        } elseif ($this->golesVisitante > $this->golesLocal) {
            return $this->local;
        }
    
        // Si hay empate, mirar penalties
        if ($this->penalties) {
            [$penLocal, $penVisitante] = explode('-', $this->penalties);
            if ((int)$penLocal > (int)$penVisitante) {
                return $this->visitante;
            } elseif ((int)$penVisitante > (int)$penLocal) {
                return $this->local;
            }
        }
    
        return null;
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
