<?php

namespace App\Service\Staff;

use App\Entity\Staff;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Clubes\GetClub;
use App\Repository\EquipoRepository;
use App\Form\Type\StaffFormType;
use App\Form\Model\staffDto;
use App\Repository\StaffRepository;
use App\Service\Equipo\CreateEquipo;
use App\Service\Equipo\GetEquipo;
use App\Service\FileUploader;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;


class StaffFormProcessor
{
    public function __construct(
        private FileUploader $fileuploader,
        private StaffRepository $staffRepository,
        private EquipoRepository $EquipoRepository,
        private FormFactoryInterface $formFactory,
        private CreateEquipo $createEquipo,
        private GetStaff $getStaff,
        private GetClub $getClub,
        private GetEquipo $getEquipo,
    ) {

    }

    public function __invoke(Request $request, ?string $staffId = null): array
    {
        $staff = null;
        $staffDto = null;     
 
        if ($staffId === null) {
            $staffDto = staffDto::createEmpty();
        } else {
            $staff = ($this->getStaff)($staffId);
            $staffDto = staffDto::createFromstaff($staff);
        }
        
        $content = json_decode($request->getContent(), true);

        $form = $this->formFactory->create(staffFormType::class, $staffDto);
       
        $form->submit($content, false);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }
  
        $filename = null;
        if ($staffDto->foto) {
            $filename = $this->fileuploader->uploadBase64File($staffDto->foto);
        }
        
        $club = null;        
        if ($staffDto->club !== null) {
            $club = ($this->getClub)($staffDto->club[0]->id());
        }           

        $equipos = [];

        if ($staffDto->equipo) {
               foreach ($staffDto->equipo as $Newequipo )
            {   
                $equipo = null;
                
                if ($Newequipo->id !== null) {
                    $equipo = ($this->getEquipo)($Newequipo->id);
                }
                if ($Newequipo->id === null) {
                    $equipo = ($this->createEquipo)($Newequipo->nombre,$Newequipo->categoria, $Newequipo->codigoAstfut);
                }
                $equipos[] = $equipo;


            }        
         }
         
        if ($staff === null){
                  
             $staff = Staff::create(Uuid::uuid4(),
                                    $staffDto->separarNombre()['nombre'], 
                                    $staffDto->separarNombre()['apellidos'], 
                                    $staffDto->tipo,
                                    $filename,
                                    $club,
                                    ...$equipos);

            $this->staffRepository->save($staff,true);

            return [$staff, null];

        } else {
    

            $staff->update(
                $staffDto->tipo,
                $filename === null ? $staff->getFoto() : $filename,
                $club,
                $equipos
            );

            $this->staffRepository->save($staff,true);
            
            return [$staff, null];            

        }
        
        return [$form, null];
    }
}