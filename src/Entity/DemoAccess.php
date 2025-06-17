<?php

namespace App\Entity;

use App\Repository\DemoAccessRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemoAccessRepository::class)]
class DemoAccess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $accessedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sourceEmail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccessedAt(): ?\DateTimeInterface
    {
        return $this->accessedAt;
    }

    public function setAccessedAt(?\DateTimeInterface $accessedAt): static
    {
        $this->accessedAt = $accessedAt;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

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

    public function getSourceEmail(): ?string
    {
        return $this->sourceEmail;
    }

    public function setSourceEmail(?string $sourceEmail): static
    {
        $this->sourceEmail = $sourceEmail;

        return $this;
    }
}
