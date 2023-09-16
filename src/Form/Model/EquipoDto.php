<?php

namespace App\Form\Model;

use App\Entity\Equipo;
use Ramsey\Uuid\UuidInterface;

class EquipoDto
{
    public ?string $id = null;
    public ?string $nombre = null;
    public ?string $codigoAstfut = null;
    public ?string $categoria = null;

    public function __construct()
    {

    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromEquipo(Equipo $equipo): self
    {
        $dto = new self();
        $dto->id = $equipo->getId();
        $dto->nombre = $equipo->getNombre();
        $dto->codigoAstfut = $equipo->getCodigoAstfut();
        $dto->categoria = $equipo->getCategoria();

        return $dto;
    }
}