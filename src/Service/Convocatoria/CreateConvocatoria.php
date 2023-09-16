<?php

namespace App\Service\Convocatoria;

use App\Entity\Jugador;
use App\Entity\Convocatoria;
use App\Entity\Eventos;
use App\Repository\ConvocatoriaRepository;
use DateTime;

class CreateConvocatoria
{

    public function __construct(private ConvocatoriaRepository $convocatoriaRepository)
    {
    }

    public function __invoke(Eventos $evento, Jugador $jugador): Convocatoria
    {
        $Evento = Convocatoria::create($evento, $jugador);
       
        return $Evento;
    }
}