<?php

namespace App\Controller\Api;

use App\Repository\ConvocatoriaRepository;
use App\Service\Convocatoria\DeleteEvento;
use App\Service\Convocatoria\ConvocatoriaFormProcessor;
use Exception;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Convocatoria\GetConvocatoria;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use phpDocumentor\Reflection\Types\Boolean;

class ConvocatoriaController extends AbstractFOSRestController
{   
    #[Get(path: "/convocatoria")]
    #[ViewAttribute(serializerGroups: ['convocatoria'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(ConvocatoriaRepository $ConvocatoriaRepository){
        
        return $ConvocatoriaRepository->findAll();

    }

    #[Get(path: "/convocatoria/{id}")]
    #[ViewAttribute(serializerGroups: ['convocatoria'], serializerEnableMaxDepthChecks: true)]  
    public function getSingleAction(string $id, GetConvocatoria $getConvocatoria){
        

        try {
            $club = ($getConvocatoria)($id);
        } catch (Exception) {
            return View::create('Convocatoria no encontrada', Response::HTTP_BAD_REQUEST);
        }
        return $club;

    }    

    #[Post(path: "/convocatoria/crear")]
    #[ViewAttribute(serializerGroups: ['convocatoria'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(Request $request, ConvocatoriaFormProcessor $ConvocatoriaFormProcessor){

        [$Convocatoria, $error]= ($ConvocatoriaFormProcessor)($request);

        return $Convocatoria ?? $error ;

    }  

    #[Put(path: "/convocatoria/{id}")]
    #[ViewAttribute(serializerGroups: ['convocatoria'], serializerEnableMaxDepthChecks: true)]    
    public function putAction(Request $request, String $id, ConvocatoriaFormProcessor $ConvocatoriaFormProcessor, ){

        [$Convocatoria, $error]= ($ConvocatoriaFormProcessor)($request, $id);

        return $Convocatoria ?? $error ;

    }      
}