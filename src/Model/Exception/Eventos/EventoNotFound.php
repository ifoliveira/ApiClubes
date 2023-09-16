<?php

namespace App\Model\Exception\Eventos;

use Exception;

class EventoNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Eventos no encontrado');
    }
}