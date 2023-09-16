<?php

namespace App\Controller\Api;

use App\Repository\DivisionesRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use App\Service\Clubes\ClubesFormProcessor;
use App\Service\Divisiones\DivisionesFormProcessor;
use Throwable;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;


class DivisionesController extends AbstractFOSRestController
{   
    #[Get(path: "/divisiones")]
    #[ViewAttribute(serializerGroups: ['divisiones'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(DivisionesRepository $divisionesRepository){
        
        return $divisionesRepository->findAll();

    }

    #[Post(path: "/divisiones/crear")]
    #[ViewAttribute(serializerGroups: ['divisiones'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(Request $request, DivisionesFormProcessor $divisionesFormProcessor){

        [$divisiones, $error]= ($divisionesFormProcessor)($request);

        return $divisiones ?? $error ;

    }  
    
        
}