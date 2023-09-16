<?php

namespace App\Model\Exception\User;

use Exception;

class userNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Usuario no encontrado');
    }
}