<?php

namespace App\Service\Eventos;

use App\Entity\Equipo;
use App\Entity\Eventos;
use App\Repository\EventosRepository;
use DateTime;

class CreateEventos
{

    public function __construct(private EventosRepository $eventosRepository)
    {
    }

    public function __invoke(DateTime $fecha, DateTime $horaIni, DateTime $horaFin, String $lugar, String $tipo, Equipo $equipo): Eventos
    {
        $Evento = Eventos::create($fecha, $horaIni, $horaFin, $lugar, $tipo, $equipo);
       
        return $Evento;
    }
}