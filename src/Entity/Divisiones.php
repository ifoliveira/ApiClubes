<?php

namespace App\Entity;

use App\Repository\DivisionesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DivisionesRepository::class)]
class Divisiones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $temporada = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codTemporadaAstfut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categoria = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codCategoriaAstfut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $division = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codDivisionAstfut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $grupo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codGrupoAstfut = null;

    #[ORM\OneToMany(mappedBy: 'division', targetEntity: Equipo::class)]
    private Collection $equipos;

    public function __construct(
        string $temporada, 
        string $codtemporada,  
        string $categoria, 
        string $codcategoria , 
        string $division, 
        string $coddivision, 
        string $grupo, 
        string $codgrupo
    ) {
        $this->setTemporada($temporada);
        $this->setCodTemporadaAstfut($codtemporada);        
        $this->setCategoria($categoria);
        $this->setCodCategoriaAstfut($codcategoria);
        $this->setDivision($division);
        $this->setCodDivisionAstfut($coddivision);
        $this->setGrupo($grupo);
        $this->setCodGrupoAstfut($codgrupo);
        $this->equipos = new ArrayCollection();
        
    }


    public static function create(string $temporada, 
                                  string $codtemporada, 
                                  string $categoria, 
                                  string $codcategoria , 
                                  string $division, 
                                  string $coddivision, 
                                  string $grupo, 
                                  string $codgrupo): self 
    
    {

     
        return new self($temporada,$codtemporada,$categoria,$codcategoria,$division,$coddivision,$grupo,$codgrupo);

    }   


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemporada(): ?string
    {
        return $this->temporada;
    }

    public function setTemporada(string $temporada): static
    {
        $this->temporada = $temporada;

        return $this;
    }

    public function getCodTemporadaAstfut(): ?string
    {
        return $this->codTemporadaAstfut;
    }

    public function setCodTemporadaAstfut(string $codTemporadaAstfut): static
    {
        $this->codTemporadaAstfut = $codTemporadaAstfut;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(?string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getCodCategoriaAstfut(): ?string
    {
        return $this->codCategoriaAstfut;
    }

    public function setCodCategoriaAstfut(?string $codCategoriaAstfut): static
    {
        $this->codCategoriaAstfut = $codCategoriaAstfut;

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(?string $division): static
    {
        $this->division = $division;

        return $this;
    }

    public function getCodDivisionAstfut(): ?string
    {
        return $this->codDivisionAstfut;
    }

    public function setCodDivisionAstfut(?string $codDivisionAstfut): static
    {
        $this->codDivisionAstfut = $codDivisionAstfut;

        return $this;
    }

    public function getGrupo(): ?string
    {
        return $this->grupo;
    }

    public function setGrupo(?string $grupo): static
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getCodGrupoAstfut(): ?string
    {
        return $this->codGrupoAstfut;
    }

    public function setCodGrupoAstfut(?string $codGrupoAstfut): static
    {
        $this->codGrupoAstfut = $codGrupoAstfut;

        return $this;
    }

    /**
     * @return Collection<int, Equipo>
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(Equipo $equipo): static
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos->add($equipo);
            $equipo->setDivision($this);
        }

        return $this;
    }

    public function removeEquipo(Equipo $equipo): static
    {
        if ($this->equipos->removeElement($equipo)) {
            // set the owning side to null (unless already changed)
            if ($equipo->getDivision() === $this) {
                $equipo->setDivision(null);
            }
        }

        return $this;
    }
}
