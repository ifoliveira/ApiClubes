<?php

namespace App\Service;

class Urldecode
{

    public function __construct()
    {
    }

    public function Urlparameters(string $urldecode): array
    {
        $components = parse_url($urldecode);
        parse_str($components['query'], $results);

        return $results;
    }
}