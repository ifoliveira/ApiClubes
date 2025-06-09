<?php

namespace App\Entity;

use App\Repository\EquipoGrupoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipoGrupoRepository::class)]
class EquipoGrupo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'equipoGrupos')]
    private ?EquipoTorneo $equipo = null;

    #[ORM\ManyToOne(inversedBy: 'equipoGrupos')]
    private ?Grupo $grupo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipo(): ?EquipoTorneo
    {
        return $this->equipo;
    }

    public function setEquipo(?EquipoTorneo $equipo): static
    {
        $this->equipo = $equipo;

        return $this;
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
}
