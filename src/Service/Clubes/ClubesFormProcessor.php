<?php

namespace App\Service\Clubes;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Clubes;
use App\Service\Clubes\GetClub;
use App\Service\Equipo\GetEquipo;
use App\Repository\ClubesRepository;
use App\Repository\EquipoRepository;
use App\Form\Type\ClubesFormType;
use App\Form\Model\ClubesDto;
use App\Service\Equipo\CreateEquipo;
use App\Service\FileUploader;
use App\Service\Urldecode;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Form\FormFactoryInterface;


class ClubesFormProcessor
{
    public function __construct(
        private FileUploader $fileuploader,
        private ClubesRepository $ClubesRepository,
        private EquipoRepository $EquipoRepository,
        private GetClub $getClub,
        private GetEquipo $getEquipo,
        private CreateEquipo $createEquipo,
        private FormFactoryInterface $formFactory,
    ) {

    }

    public function __invoke(Request $request, ?String $clubid = null): array
    {
        $club = null;
        $clubDto = null;        
        if ($clubid === null) {
            $clubDto = ClubesDto::createEmpty();
        } else {
            $club = ($this->getClub)($clubid);
   
            $clubDto = ClubesDto::createFromClubes($club);
        }
 
          $content = json_decode($request->getContent(), true);

        if (empty($content)) {

            return [null, 'JSON Vacio'];
        }
        
        $form = $this->formFactory->create(ClubesFormType::class, $clubDto);
        $form->submit($content, false);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        $equipos = [];
        foreach ($clubDto->equipos as $Newequipo )
        {   
            $equipo = null;
            if ($Newequipo->id !== null) {
                $equipo = ($this->getEquipo)($Newequipo->id);
            }
            if ($Newequipo->id === null) {
                $equipo = ($this->createEquipo)(Uuid::uuid4(), $Newequipo->nombre,$Newequipo->categoria, $Newequipo->codigoAstfut);
            }
            $equipos[] = $equipo;

        }

        $filename = null;
        if ($clubDto->base64Image) {
            $filename = $this->fileuploader->uploadBase64File($clubDto->base64Image);
        }

        if ($club === null){

             $club = Clubes::create(Uuid::uuid4(),
                                    $clubDto->nombre, 
                                   $filename, 
                                   $clubDto->direccion,
                                   $clubDto->web,
                                   $clubDto->email,
                                   $clubDto->telefono,
                                   $clubDto->codigoAstfut,
                                     ...$equipos);

            $this->ClubesRepository->save($club,true);

            return [$club, null];

        } else {
    
            $club->update(
                $clubDto->nombre,
                $filename === null ? $club->getEscudo() : $filename,
                $clubDto->direccion,
                $clubDto->web,
                $clubDto->email,
                $clubDto->telefono,
                $clubDto->codigoAstfut,
                $equipos
            );
 

            $this->ClubesRepository->save($club,true);
            
            return [$club, null];            

        }
        
        return [$form, null];
    }
}