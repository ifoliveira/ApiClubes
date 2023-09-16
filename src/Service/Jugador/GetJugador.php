<?php

namespace App\Service\Jugador;

use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use App\Model\Exception\Jugador\JugadorNotFound;
use Ramsey\Uuid\Uuid;

class GetJugador
{

    public function __construct(private JugadorRepository $jugadorRepository)
    {

    }

    public function __invoke(string $Uuid): ?jugador
    {
        $jugador = $this->jugadorRepository->find(Uuid::fromString($Uuid));
        if (!$jugador) {
                JugadorNotFound::throwException();

        }
        return $jugador;
    }
}