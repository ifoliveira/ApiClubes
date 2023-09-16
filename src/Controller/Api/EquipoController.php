<?php

namespace App\Controller\Api;

use App\Repository\EquipoRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use App\Service\Equipo\EquipoFormProcessor;
use App\Service\Equipo\GetEquipo;
use Exception;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Equipo\EquipoRelacProcessor;


class EquipoController extends AbstractFOSRestController
{   
    #[Get(path: "/equipos")]
    #[ViewAttribute(serializerGroups: ['equiposUni'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(EquipoRepository $equipoRepository){
        
        return $equipoRepository->findAll();

    }

    #[Get(path: "/equipo/{id}")]
    #[ViewAttribute(serializerGroups: ['equipos'], serializerEnableMaxDepthChecks: true)]
    
    public function getSingleAction(string $id, GetEquipo $getEquipo)
    {
        try {
            $club = ($getEquipo)($id);
        } catch (Exception) {
            return View::create('Equipo no encontrado', Response::HTTP_BAD_REQUEST);
        }
        return $club;
    }    

    #[Post(path: "/equipos/{id}")]
    #[ViewAttribute(serializerGroups: ['clubes'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(string $id, Request $request, EquipoFormProcessor $EquipoFormProcessor){

        [$equipos, $error]= ($EquipoFormProcessor)($request, $id);

        return $equipos ?? $error ;

    }  
    

    #[Post(path: "/equipos/relac/alta")]
    #[ViewAttribute(serializerGroups: ['clubes'], serializerEnableMaxDepthChecks: true)]    
    public function relacionarAction(Request $request, EquipoRelacProcessor $equipoRelacProcessor){
        
        [$equipos, $error]= ($equipoRelacProcessor)($request);

        return $equipos ?? $error ;

    }      
    
}