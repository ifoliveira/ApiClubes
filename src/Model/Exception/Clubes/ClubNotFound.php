<?php

namespace App\Model\Exception\Clubes;

use Exception;

class ClubNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Club no encontrado');
    }
}