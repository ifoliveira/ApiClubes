<?php

namespace App\Service\Equipo;

use Symfony\Component\HttpFoundation\Request;
use App\Service\Clubes\GetClub;
use App\Service\Equipo\GetEquipo;
use App\Repository\ClubesRepository;
use App\Repository\EquipoRepository;
use App\Form\Model\ClubesDto;
use App\Service\Categoriadecode;
use App\Service\Equipo\CreateEquipo;
use App\Service\Urldecode;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class EquipoFormProcessor
{
    public function __construct(
        private ClubesRepository $ClubesRepository,
        private EquipoRepository $EquipoRepository,
        private GetClub $getClub,
        private GetEquipo $getEquipo,
        private CreateEquipo $createEquipo,
        private FormFactoryInterface $formFactory,
        private Urldecode $urldecode,
        private Categoriadecode $categoriadecode,
    ) {

    }

    public function __invoke(Request $request, ?String $clubId = null): array
    {
        $club = null;
        $clubDto = null;        

        if ($clubId === null) {
            $clubDto = ClubesDto::createEmpty();
        } else {
            $club = ($this->getClub)($clubId);
            $clubDto = ClubesDto::createFromClubes($club);
        }
 
        $serializer = new Serializer(
            [
                new GetSetMethodNormalizer(),
                new ArrayDenormalizer(),
            ],
            [
                'json' => new JsonEncoder(),
            ]
        );

        $equipos = $serializer->deserialize($request->getContent(), 'App\Form\Model\EquiposDto[]' , 'json');

        $equips = [];
        foreach ($equipos as &$NewEquipoDto) {
            $equip = null;
            $parameters = $this->urldecode->Urlparameters($NewEquipoDto->urlnom);
            
            $categoria = $this->categoriadecode->decode($NewEquipoDto->categoria);
            
            $equip = ($this->createEquipo)(Uuid::uuid4(), $NewEquipoDto->nombre, $categoria, $parameters['Codigo_Equipo']);
            $equips[] = $equip;

            } 

        

        $club->update(
            $clubDto->nombre,
            null,
            $clubDto->direccion,
            $clubDto->web,
            $clubDto->email,
            $clubDto->telefono,
            $clubDto->codigoAstfut,
            $equips
        );

        $this->ClubesRepository->save($club,true);

       
        return [$club, null];            
        }

    }