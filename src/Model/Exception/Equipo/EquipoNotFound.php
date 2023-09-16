<?php

namespace App\Model\Exception\Equipo;

use Exception;

class EquipoNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Equipo no encontrado');
    }
}