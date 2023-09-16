<?php

namespace App\Model\Exception\Staff;

use Exception;

class staffNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('staff no encontrado');
    }
}