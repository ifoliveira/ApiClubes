<?php

namespace App\Entity;

use App\Repository\EmailTrackingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailTrackingRepository::class)]
class EmailTracking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $clubId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $abiertoA = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClubId(): ?int
    {
        return $this->clubId;
    }

    public function setClubId(?int $clubId): static
    {
        $this->clubId = $clubId;

        return $this;
    }

    public function getAbiertoA(): ?\DateTimeInterface
    {
        return $this->abiertoA;
    }

    public function setAbiertoA(?\DateTimeInterface $abiertoA): static
    {
        $this->abiertoA = $abiertoA;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}
