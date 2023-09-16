<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use DateTimeImmutable;
use App\Entity\Clubes;
use App\Entity\Divisiones;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: EquipoRepository::class)]
class Equipo
{


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaAlta = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaBaja = null;
    #[ORM\ManyToOne(inversedBy: 'equipos',  targetEntity: Clubes::class)]
    #[ORM\JoinColumn(name: 'club_id', referencedColumnName: 'id')]
    private ?Clubes $club = null;


    #[ORM\ManyToOne(inversedBy: 'equipos')]
    private ?Divisiones $division = null;

    #[ORM\OneToMany(mappedBy: 'equipo', targetEntity: Jugador::class)]
    private ?Collection $jugadores;

    #[ORM\ManyToMany(targetEntity: Staff::class, mappedBy: 'equipo')]
    private Collection $staff;

    #[ORM\OneToMany(mappedBy: 'equipo', targetEntity: Eventos::class)]
    private Collection $eventos;

    #[ORM\OneToMany(mappedBy: 'equipo', targetEntity: Message::class)]
    #[ORM\OrderBy(["createdAt" => "DESC"])]
    private Collection $messages;



    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        protected UuidInterface|string $id,
        #[ORM\Column(length: 255)]
        private ?string $nombre = null,
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $categoria = null,        
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $codigoAstfut = null


        )        
        {
        $this->setNombre($nombre);
        $this->setCodigoAstfut($codigoAstfut);
        $this->setFechaAlta(new DateTimeImmutable("now" , new \DateTimeZone('Europe/Madrid')));
        $this->setCategoria($categoria);
        $this->jugadores = new ArrayCollection();
        $this->staff = new ArrayCollection();
        $this->eventos = new ArrayCollection();
        $this->messages = new ArrayCollection();
        
    }


    public static function create(UuidInterface $Uuid, string $nombre, string $categoria, string $codigoAstfut): self 
    
    {
      
          
        return new self($Uuid, $nombre, $categoria, $codigoAstfut);

    }   



    public function getId(): ?UuidInterface
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

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): static
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fechaBaja;
    }

    public function setFechaBaja(?\DateTimeInterface $fechaBaja): static
    {
        $this->fechaBaja = $fechaBaja;

        return $this;
    }

    public function getClub(): ?Clubes
    {
        return $this->club;
    }

    public function setClub(?Clubes $club): static
    {
        $this->club = $club;

        return $this;
    }

    public function getCodigoAstfut(): ?string
    {
        return $this->codigoAstfut;
    }

    public function setCodigoAstfut(?string $codigoAstfut): static
    {
        $this->codigoAstfut = $codigoAstfut;

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

    public function getDivision(): ?divisiones
    {
        return $this->division;
    }

    public function setDivision(?divisiones $division): static
    {
        $this->division = $division;

        return $this;
    }

    public function __toString()
    {
        
        return $this->nombre;
    }

    /**
     * @return Collection<int, jugador>
     */
    public function getJugadores(): Collection
    {
        return $this->jugadores;
    }

    public function addJugadore(jugador $jugadore): static
    {
        if (!$this->jugadores->contains($jugadore)) {
            $this->jugadores->add($jugadore);
            $jugadore->setEquipo($this);
        }

        return $this;
    }

    public function removeJugadore(jugador $jugadore): static
    {
        if ($this->jugadores->removeElement($jugadore)) {
            // set the owning side to null (unless already changed)
            if ($jugadore->getEquipo() === $this) {
                $jugadore->setEquipo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Staff>
     */
    public function getStaff(): Collection
    {
        return $this->staff;
    }

    public function addStaff(Staff $staff): static
    {
        if (!$this->staff->contains($staff)) {
            $this->staff->add($staff);
            $staff->addEquipo($this);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): static
    {
        if ($this->staff->removeElement($staff)) {
            $staff->removeEquipo($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Eventos>
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(Eventos $evento): static
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos->add($evento);
            $evento->setEquipo($this);
        }

        return $this;
    }

    public function removeEvento(Eventos $evento): static
    {
        if ($this->eventos->removeElement($evento)) {
            // set the owning side to null (unless already changed)
            if ($evento->getEquipo() === $this) {
                $evento->setEquipo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setEquipo($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getEquipo() === $this) {
                $message->setEquipo(null);
            }
        }

        return $this;
    }
}
