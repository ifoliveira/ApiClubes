<?php

namespace App\Service\Eventos;

use App\Form\Model\EventosDto;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Equipo\GetEquipo;
use App\Repository\EventosRepository;
use App\Service\Eventos\CreateEventos;
use DateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class EventosFormProcessor
{
    public function __construct(
        private EventosRepository $eventosRepository,
        private CreateEventos $createEventos,
        private GetEquipo $getEquipo
    ) {

    }

    public function __invoke(Request $request, $equipoId): array
    {
 
        $serializer = new Serializer(
            [
                new GetSetMethodNormalizer(),
                new ArrayDenormalizer(),
            ],
            [
                'json' => new JsonEncoder(),
            ]
        );


        $equipo = null;


        if ($equipoId === null) {
            return [null, "El equipo debe estar informao"];
        } else {
            $equipo = ($this->getEquipo)($equipoId);
        }

        $eventosDto = $serializer->deserialize($request->getContent(), EventosDto::class , 'json');

        $eventos[]= null;
        foreach ($eventosDto->obtenerFechas() as &$NuevoEvento) {
            $evento = null;
            $date = New DateTime($NuevoEvento);

            $evento = ($this->createEventos)
                                ($date, 
                                 New DateTime($eventosDto->horaIni), 
                                 New DateTime($eventosDto->horaFin),
                                 $eventosDto->lugar,
                                 $eventosDto->tipo,
                                 $equipo
                                );
                                
                $this->eventosRepository->save($evento,true);
                $eventos[]=$evento;
            }

        return [$eventos, null];            


    }
}