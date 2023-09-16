<?php

namespace App\Service\Eventos;

use App\Entity\Eventos;
use App\Model\Exception\Eventos\EventoNotFound;
use App\Repository\EventosRepository;

class GetEvento
{

    public function __construct(private EventosRepository $eventosRepository)
    {
    }

    public function __invoke(int $id): ?Eventos

    {
        
        $evento = $this->eventosRepository->findOneBy(array("id" => $id));
        
        if (!$evento) {
 
            EventoNotFound::throwException();
            }
        return $evento;
    }
}