<?php

namespace App\Service\Jugador;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Jugador;
use App\Form\Model\ClubesDto;
use App\Form\Model\EquipoDto;
use App\Service\Jugador\GetJugador;
use App\Service\Equipo\CreateEquipo;
use App\Service\Clubes\GetClub;
use App\Repository\JugadorRepository;
use App\Repository\EquipoRepository;
use App\Form\Type\JugadorFormType;
use App\Form\Model\JugadorDto;
use App\Service\Equipo\GetEquipo;
use App\Service\FileUploader;
use PHPUnit\Framework\Constraint\IsEmpty;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;


class   JugadorFormProcessor
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
        $jugadorDto = null;      
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
        
        if ($jugadorId === null) {
            $jugadorDto = JugadorDto::createEmpty();
        } else {
            $jugador = ($this->getJugador)($jugadorId);
            $jugadorDto = JugadorDto::createFromJugador($jugador);
        }
        
      

        $content = json_decode($request->getContent(), true);

        $form = $this->formFactory->create(JugadorFormType::class, $jugadorDto);
        
        $form->submit($content, false);
       
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }
        
        $filename = null;
        if ($jugadorDto->foto) {
            $filename = $this->fileuploader->uploadBase64File($jugadorDto->foto);
        }
       
        if ($jugador === null){
         
             $jugador = Jugador::create(Uuid::uuid4(),
                                        $jugadorDto->separarNombre()['nombre'], 
                                        $jugadorDto->separarNombre()['apellidos'], 
                                        $filename,
                                        $club,
                                        $equipo);
                                      
            $this->JugadorRepository->save($jugador,true);

            return [$jugador, null];

        } else {
            
            $jugador->update(
                $filename === null ? $jugador->getFoto() : $filename,
                $club,
                $equipo
            );

            $this->JugadorRepository->save($jugador,true);
            
            return [$jugador, null];            

        }
        
        return [$form, null];
    }
}