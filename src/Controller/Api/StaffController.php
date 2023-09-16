<?php

namespace App\Controller\Api;

use App\Repository\StaffRepository;
use App\Service\Staff\StaffFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use Exception;
use Throwable;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;



class StaffController extends AbstractFOSRestController
{   
    #[Get(path: "/staff")]
    #[ViewAttribute(serializerGroups: ['staff'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(StaffRepository $staffRepository){
        
        return $staffRepository->findAll();

    }

    #[Post(path: "/staff")]
    #[ViewAttribute(serializerGroups: ['staff'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(Request $request, StaffFormProcessor $staffFormProcessor){

        [$staff, $error]= ($staffFormProcessor)($request);

        return $staff ?? $error ;

    }  

    #[Put(path: "/staff/{id}")]
    #[Patch(path: "/staff/{id}")]    
    #[ViewAttribute(serializerGroups: ['staff'], serializerEnableMaxDepthChecks: true)]
    public function editAction(
        string $id,
        StaffFormProcessor $staffFormProcessor,
        Request $request
    ) {
        [$club, $error] = ($staffFormProcessor)($request, $id);

        
        return $club ?? $error ;
    }        
}