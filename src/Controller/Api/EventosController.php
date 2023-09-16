<?php

namespace App\Controller\Api;

use App\Repository\EventosRepository;
use App\Service\Eventos\DeleteEvento;
use App\Service\Eventos\EventosFormProcessor;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EventosController extends AbstractFOSRestController
{  
    
    #[Get(path: "/eventos")]
    #[ViewAttribute(serializerGroups: ['eventos'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(EventosRepository $EventosRepository){
        
        return $EventosRepository->findAll();

    }


    #[Get(path: "/eventos/{id}")]
    #[ViewAttribute(serializerGroups: ['eventos'], serializerEnableMaxDepthChecks: true)]  
    public function getSingleAction(Request $request, string $id, EventosRepository $EventosRepository){
        
        return $EventosRepository->findBy(['id' => $id]);

    }


    #[Get(path: "/eventos/equipo/{id}")]
    #[ViewAttribute(serializerGroups: ['eventos'], serializerEnableMaxDepthChecks: true)]  
    public function getEventosEquipo(Request $request, string $id, EventosRepository $EventosRepository){
        
        return $EventosRepository->findBy(['equipo' => $id]);

    }

    #[Post(path: "/eventos/crear/{id}")]
    #[ViewAttribute(serializerGroups: ['eventos'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(Request $request, string $id,
                               EventosFormProcessor $EventosFormProcessor){

        [$eventos, $error]= ($EventosFormProcessor)($request, $id);

        return $eventos ?? $error ;

    }  

    #[Delete(path: "/eventos/{id}")]
    #[ViewAttribute(serializerGroups: ['eventos'], serializerEnableMaxDepthChecks: true)]
    public function deleteAction(string $id, DeleteEvento $deleteEvento)
    {
        try {
            ($deleteEvento)($id);
        } catch (Throwable) {
            return View::create('Evento no encontrado', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }

}