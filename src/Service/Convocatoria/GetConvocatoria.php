<?php

namespace App\Service\Convocatoria;

use App\Entity\Convocatoria;
use App\Repository\ConvocatoriaRepository;
use App\Model\Exception\Convocatoria\ConvocatoriaNotFound;

class GetConvocatoria
{

    public function __construct(private ConvocatoriaRepository $convocatoriaRepository)
    {

    }

    public function __invoke(string $id): ?Convocatoria
    {

  
        $equipo = $this->convocatoriaRepository->find($id);

        if (!$equipo) {
                ConvocatoriaNotFound::throwException();

        }
        return $equipo;
    }
}