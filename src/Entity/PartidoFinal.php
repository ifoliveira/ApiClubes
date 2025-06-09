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
}
