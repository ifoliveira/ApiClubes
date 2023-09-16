<?php

namespace App\Model\Exception\Jugador;

use Exception;

class JugadorNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Jugador no encontrado');
    }
}