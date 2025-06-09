<?php

namespace App\Entity;

use App\Repository\GrupoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrupoRepository::class)]
class Grupo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'grupos')]
    private ?Torneos $torneo = null;

    #[ORM\OneToMany(mappedBy: 'grupo', targetEntity: EquipoGrupo::class)]
    private Collection $equipoGrupos;

    #[ORM\OneToMany(mappedBy: 'grupo', targetEntity: PartidoGrupo::class)]
    private Collection $partidoGrupos;

    public function __construct()
    {
        $this->equipoGrupos = new ArrayCollection();
        $this->partidoGrupos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

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

    /**
     * @return Collection<int, EquipoGrupo>
     */
    public function getEquipoGrupos(): Collection
    {
        return $this->equipoGrupos;
    }

    public function addEquipoGrupo(EquipoGrupo $equipoGrupo): static
    {
        if (!$this->equipoGrupos->contains($equipoGrupo)) {
            $this->equipoGrupos->add($equipoGrupo);
            $equipoGrupo->setGrupo($this);
        }

        return $this;
    }

    public function removeEquipoGrupo(EquipoGrupo $equipoGrupo): static
    {
        if ($this->equipoGrupos->removeElement($equipoGrupo)) {
            // set the owning side to null (unless already changed)
            if ($equipoGrupo->getGrupo() === $this) {
                $equipoGrupo->setGrupo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PartidoGrupo>
     */
    public function getPartidoGrupos(): Collection
    {
        return $this->partidoGrupos;
    }

    public function addPartidoGrupo(PartidoGrupo $partidoGrupo): static
    {
        if (!$this->partidoGrupos->contains($partidoGrupo)) {
            $this->partidoGrupos->add($partidoGrupo);
            $partidoGrupo->setGrupo($this);
        }

        return $this;
    }

    public function removePartidoGrupo(PartidoGrupo $partidoGrupo): static
    {
        if ($this->partidoGrupos->removeElement($partidoGrupo)) {
            // set the owning side to null (unless already changed)
            if ($partidoGrupo->getGrupo() === $this) {
                $partidoGrupo->setGrupo(null);
            }
        }

        return $this;
    }
}
