<?php

namespace App\Service\Jugador;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Jugador;
use App\Service\Jugador\GetJugador;
use App\Service\Equipo\CreateEquipo;
use App\Service\Clubes\GetClub;
use App\Repository\JugadorRepository;
use App\Repository\EquipoRepository;
use App\Form\Model\JugadorDto;
use App\Service\Equipo\GetEquipo;
use App\Service\FileUploader;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;



class   JugadoresFormProcessor
{
    public function __construct(
        private FileUploader $fileuploader,
        private JugadorRepository $JugadorRepository,
        private EquipoRepository $EquipoRepository,
        private FormFactoryInterface $formFactory,
        private CreateEquipo $createEquipo,
        private GetJugador $getJugador,
        private GetClub $getClub,
        private GetEquipo $getEquipo,

    ) {

    }

    public function __invoke(Request $request, ?string $jugadorId = null): array
    {
        $jugador = null;
        $club = null;
        $equipo = null;  

        $clubId= $request->query->get('club');

        if ($clubId !== null) {
            $club = ($this->getClub)($clubId);
        }  

        $equipoId= $request->query->get('equipo');

        if ($equipoId !== null) {
            $equipo = ($this->getEquipo)($equipoId);
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

        $jugadores = $serializer->deserialize($request->getContent(), 'App\Form\Model\JugadorDto[]' , 'json');

        $jugadoresSalida = [];
        foreach ($jugadores as &$NewJugador) {

            $filename = null;
            if ($NewJugador->foto) {
                $filename = $this->fileuploader->uploadBase64File($NewJugador->foto);
            }
       
            
            $jugador = Jugador::create(Uuid::uuid4(),
                        $NewJugador->separarNombre()['nombre'], 
                        $NewJugador->separarNombre()['apellidos'], 
                        $filename,
                        $club,
                        $equipo);    
            
            $this->JugadorRepository->save($jugador,true);                                       
            
            $jugadoresSalida[] = $jugador;

         }         
        
        return [$jugadoresSalida, null];
    }
}