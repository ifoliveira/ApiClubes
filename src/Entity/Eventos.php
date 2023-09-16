<?php

namespace App\Entity;

use App\Repository\EventosRepository;
use App\Entity\Equipo;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\ErrorHandler\Collecting;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Time;

#[ORM\Entity(repositoryClass: EventosRepository::class)]
class Eventos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Convocatoria::class)]
  
    private Collection $convocatorias;

    public function __construct(
        #[ORM\Column(type: Types::DATE_MUTABLE)]
        private ?\DateTimeInterface $fecha = null,
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $horaIni = null,
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $horaFin = null,
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $lugar = null,
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $tipo = null,
        #[ORM\ManyToOne(inversedBy: 'eventos',  targetEntity: 'Equipo')]
        private ?equipo $equipo = null        
        
    )
    {
        $this->convocatorias = new ArrayCollection();
        $this->setEquipo($equipo);
    }


    public static function create(
        DateTime $fecha, 
       ?DateTime $horaIni, 
       ?DateTime $horaFin,
       ?string $lugar,
       ?string $tipo,
        Equipo $equipo): self 
  
  {

      return new self($fecha, $horaIni, $horaFin, $lugar, $tipo, $equipo);
  }    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHoraIni(): ?\DateTimeInterface
    {
        return $this->horaIni;
    }

    public function setHoraIni(\DateTimeInterface $horaIni): static
    {
        $this->horaIni = $horaIni;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->horaFin;
    }

    public function setHoraFin(\DateTimeInterface $horaFin): static
    {
        $this->horaFin = $horaFin;

        return $this;
    }

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(?string $lugar): static
    {
        $this->lugar = $lugar;

        return $this;
    }

    public function getEquipo(): ?equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?equipo $equipo): static
    {
        $this->equipo = $equipo;

        return $this;
    }

    /**
     * @return Collection<int, Convocatoria>
     */
    public function getConvocatorias(): Collection
    {
        return $this->convocatorias;
    }

    public function addConvocatoria(Convocatoria $convocatoria): static
    {
        if (!$this->convocatorias->contains($convocatoria)) {
            $this->convocatorias->add($convocatoria);
            $convocatoria->setEvento($this);
        }

        return $this;
    }

    public function removeConvocatoria(Convocatoria $convocatoria): static
    {
        if ($this->convocatorias->removeElement($convocatoria)) {
            // set the owning side to null (unless already changed)
            if ($convocatoria->getEvento() === $this) {
                $convocatoria->setEvento(null);
            }
        }

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }
}
