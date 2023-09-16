<?php

namespace App\Service\Equipo;

use App\Entity\Equipo;
use App\Repository\EquipoRepository;
use App\Model\Exception\Equipo\EquipoNotFound;

class GetEquipo
{

    public function __construct(private EquipoRepository $equipoRepository)
    {

    }

    public function __invoke(string $id): ?Equipo
    {

  
        $equipo = $this->equipoRepository->find($id);

        if (!$equipo) {
                EquipoNotFound::throwException();

        }
        return $equipo;
    }
}