<?php

namespace App\Entity;

use App\Repository\StaffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: StaffRepository::class)]
class Staff
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaAlta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaModi = null;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        protected UuidInterface|string $id,          
        #[ORM\Column(length: 255)]
        private ?string $nombre,    
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $apellidos,    
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $tipo,    
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $foto,
        #[ORM\ManyToOne(inversedBy: 'staff')]
        private ?Clubes $club,       
        #[ORM\ManyToMany(targetEntity: Equipo::class, inversedBy: 'staff', cascade : ['persist'])]
        private ?Collection $equipo,
          
       ) 
        {
   
        foreach ($this->equipo as $equipo) {
            $equipo->addStaff($this);
        }        

   
       $datatime = new DateTimeImmutable("now" , new \DateTimeZone('Europe/Madrid'));
       $this->fechaAlta = $datatime; 

   }
   
   
     public static function create(
        UuidInterface $Uuid,
        string $nombre, 
        ?string $apellidos,
       ?string $tipo,
       ?string $foto,
       ?Clubes $club,
       Equipo ...$equipo,
       ): self 
  
  {

      return new self($Uuid, $nombre,$apellidos, $tipo, $foto, $club, new ArrayCollection($equipo));
  }

  public function update(
    ?string $tipo,
    ?string $foto,
    ?Clubes $club,
    ?array $equipo
   ) {

        $this->tipo = $tipo;
        $this->foto =  $foto;
        $this->club = $club;

        if (!empty($equipo)) {
            $this->updateEquipos(...$equipo);
        }

        $this->fechaModi = new DateTimeImmutable("now" , new \DateTimeZone('Europe/Madrid'));
    
    }


    public function updateEquipos(Equipo ...$newEquipos)
    {

        /** @var ArrayCollection<Equipo> */
        $originalEquipos = new ArrayCollection();
        foreach ($this->getEquipo() as $equipo) {
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): static
    {
        $this->apellidos = $apellidos;

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

    /**
     * @return Collection<int, equipo>
     */
    public function getEquipo(): Collection
    {
        return $this->equipo;
    }

    public function addEquipo(equipo $equipo): static
    {
        if (!$this->equipo->contains($equipo)) {
            $this->equipo->add($equipo);
        }

        return $this;
    }

    public function removeEquipo(equipo $equipo): static
    {
        $this->equipo->removeElement($equipo);

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

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): static
    {
        $this->foto = $foto;

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
}
