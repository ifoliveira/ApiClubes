<?php

namespace App\Entity;

use App\Repository\ClubesRepository;
use App\Entity\Equipo;
use DateTimeImmutable;
use App\Entity\Jugador;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: ClubesRepository::class)]
class Clubes
{
    #[ORM\OneToMany(mappedBy: 'club', targetEntity: equipo::class,  cascade :['all'])]
    #[ORM\JoinColumn(name: 'club_id', referencedColumnName: 'id')]
    #[ORM\OrderBy(["categoria" => "ASC"])]
    private ?Collection $equipos  = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaAlta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaModificacion = null;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Jugador::class)]
    private Collection $jugadores;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Staff::class)]
    private Collection $staff;

    /**
     * @param Collection|Equipo[]|null $equipo
     */
    public function __construct(

        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        protected UuidInterface|string $id,
        #[ORM\Column(length: 255)]
        private ?string $nombre,
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $escudo, 
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $direccion = null,   
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $web = null,
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $email = null,
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $telefono = null, 
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $codigoAstfut = null,                    
        ?Collection $equipo  = new ArrayCollection(),
       ) 
   {
    
       $datatime = new DateTimeImmutable("now" , new \DateTimeZone('Europe/Madrid'));
       $this->fechaAlta = $datatime; 
       $this->fechaModificacion = $datatime;

       $this->equipos = $equipo ?? new ArrayCollection();

       foreach ($this->equipos as $equipo) {
            $equipo->setClub($this);
        }
       $this->jugadores = new ArrayCollection();
       $this->staff = new ArrayCollection();            

   }

    /**
     * @param array|Equipo[] $equipos
     * @return self
     */

   public static function create(
    UuidInterface  $Uuid,
         string $nombre, 
        ?string $escudo, 
        ?string $direccion,
        ?string $web,
        ?string $email,
        ?string $telefono,
        ?string $codigoAstfut,
        Equipo ...$equipos,): self 
   
   {

       return new self($Uuid, $nombre, $escudo, $direccion, $web, $email, $telefono, $codigoAstfut, new ArrayCollection($equipos));
   }


    /**
     * @param array|Equipo[] $equipos
     * @return void
     */
    public function update(
        string $nombre,
        ?string $escudo,
        ?string $direccion,
        ?string $web,
        ?string $email,
        ?string $telefono,
        ?string $codigoAstfut,        
        ?array $equipos
    ) {
        $this->nombre = $nombre;
        $this->escudo = $escudo;
        $this->direccion = $direccion;
        $this->web = $web;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->codigoAstfut = $codigoAstfut;
 
     
        $this->fechaModificacion = new DateTimeImmutable("now" , new \DateTimeZone('Europe/Madrid'));
        if (!empty($equipos)) {
            $this->updateEquipos(...$equipos);
        }
    }

    public function updateEquipos(Equipo ...$newEquipos)
    {

        /** @var ArrayCollection<Equipo> */
        $originalEquipos = new ArrayCollection();
        foreach ($this->getEquipos() as $equipo) {
                $originalEquipos->add($equipo);
            }

        // Remove equipos
        foreach ($originalEquipos as $originalEquipo) {
            if (!\in_array($originalEquipo, $newEquipos, true)) {
                $this->removeEquipo($originalEquipo);
                }
            }

        // Add equipos
        foreach ($newEquipos as $newEquipo) {
            if (!$originalEquipos->contains($newEquipo)) {
                    $this->addEquipo($newEquipo);
                }
        }
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): static
    {
        $this->web = $web;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

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

    public function getFechaModificacion(): ?\DateTimeInterface
    {
        return $this->fechaModificacion;
    }

    public function setFechaModificacion(\DateTimeInterface $fechaModificacion): static
    {
        $this->fechaModificacion = $fechaModificacion;

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

    public function getEscudo(): ?string
    {
        return $this->escudo;
    }

    public function setEscudo(?string $escudo): static
    {
        $this->escudo = $escudo;

        return $this;
    }

    /**
     * @return Collection<int, Equipos>
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(Equipo $equipo): static
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos->add($equipo);
            $equipo->setClub($this);
        }

        return $this;
    }

    public function removeEquipo(Equipo $equipo): static
    {
        if ($this->equipos->removeElement($equipo)) {
            // set the owning side to null (unless already changed)
            if ($equipo->getClub() === $this) {
                $equipo->setClub(null);
            }
        }

        return $this;
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
            $jugadore->setClub($this);
        }

        return $this;
    }

    public function removeJugadore(jugador $jugadore): static
    {
        if ($this->jugadores->removeElement($jugadore)) {
            // set the owning side to null (unless already changed)
            if ($jugadore->getClub() === $this) {
                $jugadore->setClub(null);
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
            $staff->setClub($this);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): static
    {
        if ($this->staff->removeElement($staff)) {
            // set the owning side to null (unless already changed)
            if ($staff->getClub() === $this) {
                $staff->setClub(null);
            }
        }

        return $this;
    }

  
}
