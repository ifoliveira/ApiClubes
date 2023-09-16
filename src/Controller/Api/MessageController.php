<?php

namespace App\Controller\Api;

use App\Service\Message\MessageFormProcessor;
use App\Service\Equipo\GetEquipo;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use Exception;
use Throwable;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;



class MessageController extends AbstractFOSRestController
{   
    #[Get(path: "/message/{equipoid}")]
    #[ViewAttribute(serializerGroups: ['message'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(GetEquipo $getEquipo, String $equipoid){
        
        try {
            $equipo = ($getEquipo)($equipoid);
        } catch (Exception) {
            return View::create('Club no encontrado', Response::HTTP_BAD_REQUEST);
        }

        return $equipo->getMessages();

    }

    #[Post(path: "/message")]
    #[ViewAttribute(serializerGroups: ['message'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(MessageFormProcessor $MessageFormProcessor, Request $request,){

        [$message, $error]= ($MessageFormProcessor)($request);

        $statusCode = $message ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        
        $data = $message ?? $error;

        return View::create($data, $statusCode);

    }  

 
}