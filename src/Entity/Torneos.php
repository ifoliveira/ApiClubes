<?php

namespace App\Entity;

use App\Repository\TorneosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: TorneosRepository::class)]
class Torneos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(nullable: true)]
    private ?bool $activo = null;

    #[ORM\OneToMany(mappedBy: 'torneo', targetEntity: EquipoTorneo::class, orphanRemoval: true)]
    private Collection $equipoTorneos;

    #[ORM\OneToMany(mappedBy: 'torneo', targetEntity: Grupo::class)]
    private Collection $grupos;

    #[ORM\OneToMany(mappedBy: 'torneo', targetEntity: CruceFinal::class)]
    private Collection $cruceFinals;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $codigoAcceso = null;

    #[ORM\OneToMany(mappedBy: 'torneo', targetEntity: PartidoFinal::class)]
    private Collection $partidoFinals;

    public function __construct()
    {
        $this->equipoTorneos = new ArrayCollection();
        $this->grupos = new ArrayCollection();
        $this->cruceFinals = new ArrayCollection();
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

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(?\DateTimeInterface $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): static
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return Collection<int, EquipoTorneo>
     */
    public function getEquipoTorneos(): Collection
    {
        return $this->equipoTorneos;
    }

    public function addEquipoTorneo(EquipoTorneo $equipoTorneo): static
    {
        if (!$this->equipoTorneos->contains($equipoTorneo)) {
            $this->equipoTorneos->add($equipoTorneo);
            $equipoTorneo->setTorneo($this);
        }

        return $this;
    }

    public function removeEquipoTorneo(EquipoTorneo $equipoTorneo): static
    {
        if ($this->equipoTorneos->removeElement($equipoTorneo)) {
            // set the owning side to null (unless already changed)
            if ($equipoTorneo->getTorneo() === $this) {
                $equipoTorneo->setTorneo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Grupo>
     */
    public function getGrupos(): Collection
    {
        return $this->grupos;
    }

    public function addGrupo(Grupo $grupo): static
    {
        if (!$this->grupos->contains($grupo)) {
            $this->grupos->add($grupo);
            $grupo->setTorneo($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): static
    {
        if ($this->grupos->removeElement($grupo)) {
            // set the owning side to null (unless already changed)
            if ($grupo->getTorneo() === $this) {
                $grupo->setTorneo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CruceFinal>
     */
    public function getCruceFinals(): Collection
    {
        return $this->cruceFinals;
    }

    public function addCruceFinal(CruceFinal $cruceFinal): static
    {
        if (!$this->cruceFinals->contains($cruceFinal)) {
            $this->cruceFinals->add($cruceFinal);
            $cruceFinal->setTorneo($this);
        }

        return $this;
    }

    public function removeCruceFinal(CruceFinal $cruceFinal): static
    {
        if ($this->cruceFinals->removeElement($cruceFinal)) {
            // set the owning side to null (unless already changed)
            if ($cruceFinal->getTorneo() === $this) {
                $cruceFinal->setTorneo(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCodigoAcceso(): ?string
    {
        return $this->codigoAcceso;
    }

    public function setCodigoAcceso(?string $codigoAcceso): static
    {
        $this->codigoAcceso = $codigoAcceso;

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
            $partidoFinal->setTorneo($this);
        }

        return $this;
    }

    public function removePartidoFinal(PartidoFinal $partidoFinal): static
    {
        if ($this->partidoFinals->removeElement($partidoFinal)) {
            // set the owning side to null (unless already changed)
            if ($partidoFinal->getTorneo() === $this) {
                $partidoFinal->setTorneo(null);
            }
        }

        return $this;
    }
}
