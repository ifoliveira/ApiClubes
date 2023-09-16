<?php

namespace App\Controller\Api;

use App\Repository\ClubesRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use App\Service\Clubes\ClubesFormProcessor;
use App\Service\Clubes\GetClub;
use Exception;
use Throwable;
use FOS\RestBundle\View\View;
use Ramsey\Uuid\Rfc4122\UuidInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;


class ClubesController extends AbstractFOSRestController
{   
    #[Get(path: "/clubes")]
    #[ViewAttribute(serializerGroups: ['clubes'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(ClubesRepository $ClubesRepository){
        
        return $ClubesRepository->findAll();

    }

    #[Post(path: "/clubes")]
    #[ViewAttribute(serializerGroups: ['clubes'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(Request $request, 
                               ClubesFormProcessor $ClubesFormProcessor){

        [$club, $error]= ($ClubesFormProcessor)($request);

        $statusCode = $club ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        
        $data = $club ?? $error;

        return View::create($data, $statusCode);

    }  

    #[Get(path: "/clubes/{id}")]
    #[ViewAttribute(serializerGroups: ['clubes'], serializerEnableMaxDepthChecks: true)]
    public function getSingleAction(String $id, GetClub $getClub)
    {
        try {
            $club = ($getClub)($id);
        } catch (Exception) {
            return View::create('Club no encontrado', Response::HTTP_BAD_REQUEST);
        }
        return $club;
    }    

    #[Put(path: "/clubes/{id}")]
    #[Patch(path: "/clubes/{id}")]    
    #[ViewAttribute(serializerGroups: ['clubes'], serializerEnableMaxDepthChecks: true)]
    public function editAction(
        string $id,
        ClubesFormProcessor $clubesFormProcessor,
        Request $request
    ) {

    
        [$club, $error] = ($clubesFormProcessor)($request, $id);
        $statusCode = $club ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $club ?? $error;
        return View::create($data, $statusCode);
        return $club ?? $error ;
    }    
}