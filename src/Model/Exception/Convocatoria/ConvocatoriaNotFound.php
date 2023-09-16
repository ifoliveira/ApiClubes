<?php

namespace App\Model\Exception\Convocatoria;

use Exception;

class ConvocatoriaNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Convocatoria no encontrada');
    }
}