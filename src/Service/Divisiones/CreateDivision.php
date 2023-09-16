<?php

namespace App\Service\Divisiones;

use App\Entity\Divisiones;
use App\Repository\DivisionesRepository;

class CreateDivision
{

    public function __construct(private DivisionesRepository $divisionesRepository)
    {
    }

    public function __invoke(string $temporada, string $codtemporada,string $categoria, string $codcategoria , string $division, string $coddivision, string $grupo, string $codgrupo): Divisiones
    {
        $Division = Divisiones::create($temporada,  $codtemporada ,$categoria,  $codcategoria , $division, $coddivision, $grupo, $codgrupo);
       
        return $Division;
    }
}