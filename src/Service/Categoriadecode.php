<?php

namespace App\Service;

class Categoriadecode
{

    public function __construct()
    {
    }

    public function decode(string $categoria): ?string
    {
        if ( is_numeric($categoria[0]) ) {

            preg_match('/[a-z]/i',$categoria,$coincidencias,PREG_OFFSET_CAPTURE);

            $categoria = substr($categoria,$coincidencias[0][1]);
        } 

        return $categoria;
    }
}