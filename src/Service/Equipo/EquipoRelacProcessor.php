<?php

namespace App\Service\Equipo;

use App\Controller\Api\DivisionesController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Equipo\GetEquipo;
use App\Repository\EquipoRepository;
use App\Model\Equipo\EquipoRepositoryCriteria;
use App\Repository\DivisionesRepository;
use App\Service\Equipo\CreateEquipo;
use App\Service\Urldecode;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class EquipoRelacProcessor
{
    public function __construct(
        private EquipoRepository $equipoRepository,
        private DivisionesRepository $divisionesRepository,
        private GetEquipo $getEquipo,
        private CreateEquipo $createEquipo,
        private FormFactoryInterface $formFactory,
        private Urldecode $urldecode,
    ) {

    }

    public function __invoke(Request $request): array
    {

    # Relacionamos equipos con divisiones
    
        $itemsPerPage = $request->query->get('itemsPerPage');
        $page = $request->query->get('page');
        
        $serializer = new Serializer(
            [
                new GetSetMethodNormalizer(),
                new ArrayDenormalizer(),
            ],
            [
                'json' => new JsonEncoder(),
            ]
        );

        $relaciones = $serializer->deserialize($request->getContent(), 'App\Form\Model\EquipoRelacDto[]' , 'json');
   
        $equipos=[];
        foreach ($relaciones as &$NewRelacion) {
            $equipo = null;
            $parametersequipo = $this->urldecode->Urlparameters($NewRelacion->url);      
            $codequipo = $parametersequipo['codequipo'];      

            $parametersgrupo = $this->urldecode->Urlparameters($NewRelacion->grupo);      
            $codgrupo = $parametersgrupo['codgrupo'];

            # Obtener división
            $division = $this->divisionesRepository->findOneBy(['codGrupoAstfut'=>$codgrupo]);

            # Obtener equipo
            $criteria = new EquipoRepositoryCriteria(
                null,
                null,
                null,
                $codequipo,
                null,
                null,
                $itemsPerPage !== null ? \intval($itemsPerPage) : 10,
                $page !== null ? \intval($page) : 1
            );   

         
            $equipo= $this->equipoRepository->findByCriteria($criteria);
            
            # Si tenemos el equipo le asignamos la división
            if ($equipo['total'] == 1) {

                $equipo['data'][0]->setDivision($division);
                $this->equipoRepository->save($equipo['data'][0],true);
                $equipos[]=$equipo;

            }


  
        }
        return [$equipos, null];
    }
}