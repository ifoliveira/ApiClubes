<?php

namespace App\Form\Model;

use App\Entity\Clubes;


class ClubesDto
{
    public ?string $nombre = null;
    public ?string $base64Image = null;
    public ?string $direccion = null;
    public ?string $web = null;
    public ?string $email = null;
    public ?string $telefono = null;
    public ?string $codigoAstfut = null;

/** @var \App\Form\Model\EquipoDto[]|null */    
    public ?array $equipos = [];

    public function __construct()
    {
        $this->equipos = [];
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromClubes(Clubes $club): self
    {
        $dto = new self();
        $dto->nombre = $club->getNombre();
        $dto->direccion = $club->getDireccion();
        $dto->web = $club->getWeb();
        $dto->email = $club->getEmail();
        $dto->telefono = $club->getTelefono();
        $dto->codigoAstfut =$club->getCodigoAstfut();

        return $dto;
    }
}