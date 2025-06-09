<?php

namespace App\Entity;

use App\Repository\CruceFinalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CruceFinalRepository::class)]
class CruceFinal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $fase = null;

    #[ORM\ManyToOne(inversedBy: 'cruceFinals')]
    private ?Torneos $torneo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFase(): ?string
    {
        return $this->fase;
    }

    public function setFase(?string $fase): static
    {
        $this->fase = $fase;

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
}
