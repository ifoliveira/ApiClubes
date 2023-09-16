<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: JugadorRepository::class)]
class Jugador
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaAlta = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaModi = null;
    #[ORM\OneToMany(mappedBy: 'jugador', targetEntity: Convocatoria::class)]
    private Collection $convocatorias;

    #[ORM\OneToMany(mappedBy: 'jugador', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tipo = null;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        protected UuidInterface|string $id,  
        #[ORM\Column(length: 255)]
        private ?string $nombre = null,    
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $apellidos = null,    
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $foto = null,
        #[ORM\ManyToOne(inversedBy: 'jugadores')]
        private ?Clubes $club = null,       
        #[ORM\ManyToOne(inversedBy: 'jugadores')]
        private ?Equipo $equipo = null,         
       ) 
   {

        if (!empty($this->equipo)){
            foreach ($this->equipo as $equipo) {
                $equipo->addJugador($this);
            }    
        }
        $datatime = new DateTimeImmutable("now" , new \DateTimeZone('Europe/Madrid'));
        $this->fechaAlta = $datatime;
        $this->convocatorias = new ArrayCollection();
        $this->users = new ArrayCollection(); 

   }
   
   
     public static function create(
        UuidInterface $Uuid,
        string $nombre, 
        ?string $apellidos,
       ?string $foto,
       ?Clubes $club,
       ?Equipo $equipo
       ): self 
  
  { 
     

      return new self($Uuid, $nombre,$apellidos, $foto, $club, $equipo);
  }

  public function update(
    ?string $foto,
    ?Clubes $club,
    ?Equipo $equipo
   ) {
   
        $this->foto =  $foto;
        $this->club = $club;
        $this->equipo = $equipo; 
        $this->fechaModi = new DateTimeImmutable("now" , new \DateTimeZone('Europe/Madrid'));
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): static
    {
        $this->apellidos = $apellidos;

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

    public function getFechaModi(): ?\DateTimeInterface
    {
        return $this->fechaModi;
    }

    public function setFechaModi(?\DateTimeInterface $fechaModi): static
    {
        $this->fechaModi = $fechaModi;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): static
    {
        $this->foto = $foto;

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

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): static
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
            $convocatoria->setJugador($this);
        }

        return $this;
    }

    public function removeConvocatoria(Convocatoria $convocatoria): static
    {
        if ($this->convocatorias->removeElement($convocatoria)) {
            // set the owning side to null (unless already changed)
            if ($convocatoria->getJugador() === $this) {
                $convocatoria->setJugador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setJugador($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getJugador() === $this) {
                $user->setJugador(null);
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
