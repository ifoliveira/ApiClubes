<?php

namespace App\Service\Eventos;

use App\Repository\EventosRepository;

class DeleteEvento
{
    public function __construct(
        private GetEvento $getEvento,
        private EventosRepository $eventosRepository,
    ) {
    }

    public function __invoke(int $id): void
    {

        $evento = ($this->getEvento)($id);

        $this->eventosRepository->remove($evento, true);
    }
}