<?php

namespace App\Service\Equipo;

use App\Entity\Equipo;
use App\Repository\EquipoRepository;
use Ramsey\Uuid\UuidInterface;

class CreateEquipo
{

    public function __construct(private EquipoRepository $equipoRepository)
    {
    }

    public function __invoke(UuidInterface $Uuid, string $nombre, string $categoria , string $codigoAstfut): Equipo
    {
       
        $Equipo = Equipo::create($Uuid, $nombre, $categoria, $codigoAstfut);
       
        return $Equipo;
    }
}