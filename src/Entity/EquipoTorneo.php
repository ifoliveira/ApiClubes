<?php

namespace App\Entity;

use App\Repository\EquipoTorneoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipoTorneoRepository::class)]
class EquipoTorneo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'equipoTorneos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Torneos $torneo = null;

    #[ORM\OneToMany(mappedBy: 'equipo', targetEntity: EquipoGrupo::class)]
    private Collection $equipoGrupos;

    #[ORM\OneToMany(mappedBy: 'local', targetEntity: PartidoGrupo::class)]
    private Collection $partidoGrupos;

    #[ORM\OneToMany(mappedBy: 'local', targetEntity: PartidoFinal::class)]
    private Collection $partidoFinals;

    public function __construct()
    {
        $this->equipoGrupos = new ArrayCollection();
        $this->partidoGrupos = new ArrayCollection();
        $this->partidoFinals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
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
            $equipoGrupo->setEquipo($this);
        }

        return $this;
    }

    public function removeEquipoGrupo(EquipoGrupo $equipoGrupo): static
    {
        if ($this->equipoGrupos->removeElement($equipoGrupo)) {
            // set the owning side to null (unless already changed)
            if ($equipoGrupo->getEquipo() === $this) {
                $equipoGrupo->setEquipo(null);
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
            $partidoGrupo->setLocal($this);
        }

        return $this;
    }

    public function removePartidoGrupo(PartidoGrupo $partidoGrupo): static
    {
        if ($this->partidoGrupos->removeElement($partidoGrupo)) {
            // set the owning side to null (unless already changed)
            if ($partidoGrupo->getLocal() === $this) {
                $partidoGrupo->setLocal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PartidoFinal>
     */
    public function getPartidoFinals(): Collection
    {
        return $this->partidoFinals;
    }

    public function addPartidoFinal(PartidoFinal $partidoFinal): static
    {
        if (!$this->partidoFinals->contains($partidoFinal)) {
            $this->partidoFinals->add($partidoFinal);
            $partidoFinal->setLocal($this);
        }

        return $this;
    }

    public function removePartidoFinal(PartidoFinal $partidoFinal): static
    {
        if ($this->partidoFinals->removeElement($partidoFinal)) {
            // set the owning side to null (unless already changed)
            if ($partidoFinal->getLocal() === $this) {
                $partidoFinal->setLocal(null);
            }
        }

        return $this;
    }
}
