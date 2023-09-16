<?php

namespace App\Service\Divisiones;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\DivisionesRepository;
use App\Form\Model\ClubesDto;
use App\Service\Categoriadecode;
use App\Service\Urldecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class DivisionesFormProcessor
{
    public function __construct(
        private DivisionesRepository $DivisionesRepository,
        private CreateDivision $createDivision,
        private Categoriadecode $categoriadecode,
        private Urldecode $urldecode
    ) {

    }

    public function __invoke(Request $request): array
    {
 
        $serializer = new Serializer(
            [
                new GetSetMethodNormalizer(),
                new ArrayDenormalizer(),
            ],
            [
                'json' => new JsonEncoder(),
            ]
        );

        $temporada= $request->query->get('temporada');
        $codtemporada = $request->query->get('codtemporada');
        $divisionDto = $serializer->deserialize($request->getContent(), 'App\Form\Model\DivisionesDto[]' , 'json');

        $divisiones[]=null;
        foreach ($divisionDto as &$NewDivisionDto) {
            $division = null;
            $Urlcompeticion = $this->urldecode->Urlparameters($NewDivisionDto->urlnom);

            $categoria = $this->categoriadecode->decode($NewDivisionDto->nombre);

            foreach($NewDivisionDto->getGrupos() as &$NewGrupoDto) {

                $Urlgrupo = $this->urldecode->Urlparameters($NewGrupoDto['urlnom']);
                $division = ($this->createDivision)
                                ($temporada , 
                                 $codtemporada, 
                                 $categoria, 
                                 $Urlcompeticion['codcompeticion'], 
                                 $NewDivisionDto->nombre, 
                                 $Urlgrupo['CodCompeticion'],
                                 $NewGrupoDto['nombre'], 
                                 $Urlgrupo['CodGrupo']  
                                );
                                
                $this->DivisionesRepository->save($division,true);
                $divisiones[]=$division;
            }
        }

           return [$divisiones, null];            
        }

    }