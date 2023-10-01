<?php

namespace App\Controller\Api;

use App\Repository\JugadorRepository;
use App\Service\Jugador\JugadorFormProcessor;
use App\Service\Jugador\GetJugador;
use App\Service\Jugador\JugadoresFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Post, Put, Patch};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use Exception;
use FOS\RestBundle\View\View;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\HttpFoundation\Response;


class JugadorController extends AbstractFOSRestController
{   
    #[Get(path: "/jugador")]
    #[ViewAttribute(serializerGroups: ['jugador'], serializerEnableMaxDepthChecks: true)]  
    public function getAction(JugadorRepository $jugadorRepository){
        
        return $jugadorRepository->findAll();

    }

    #[Get(path: "/jugador/{id}")]
    #[ViewAttribute(serializerGroups: ['jugador'], serializerEnableMaxDepthChecks: true)]
    public function getSingleAction(String $id, GetJugador $getJugador)
    {
        try {
            $jugador = ($getJugador)($id);
        } catch (Exception) {
            return View::create('Jugador no encontrado', Response::HTTP_BAD_REQUEST);
        }
        return $jugador;
    }     

    #[Get(path: "/jugador/convocatoria/{id}")]
    #[ViewAttribute(serializerGroups: ['convocatoriajugador'], serializerEnableMaxDepthChecks: true)]  
    public function geConvocatoriasJugador(GetJugador $getJugador, String $id){
        
        try {
            $jugador = ($getJugador)($id);
        } catch (Exception) {
            return View::create('Jugador no encontrado', Response::HTTP_BAD_REQUEST);
        }
        return $jugador->getConvocatorias();

    }


    #[Post(path: "/jugador")]
    #[ViewAttribute(serializerGroups: ['jugador'], serializerEnableMaxDepthChecks: true)]    
    public function postAction(Request $request, JugadorFormProcessor $JugadorFormProcessor){

        [$jugador, $error]= ($JugadorFormProcessor)($request);

        return $jugador ?? $error ;

    }  


    #[Post(path: "/jugadorMasivo")]
    #[ViewAttribute(serializerGroups: ['jugador'], serializerEnableMaxDepthChecks: true)]    
    public function postMultiple(Request $request, JugadoresFormProcessor $JugadoresFormProcessor){

        [$jugador, $error]= ($JugadoresFormProcessor)($request);

        return $jugador ?? $error ;

    }  



    #[Put(path: "/jugador/{id}")]
    #[Patch(path: "/jugador/{id}")]  
    #[ViewAttribute(serializerGroups: ['jugador'], serializerEnableMaxDepthChecks: true)]    
    public function updateAction(Request $request, String $id, JugadorFormProcessor $JugadorFormProcessor){

        [$jugador, $error]= ($JugadorFormProcessor)($request, $id);

        return $jugador ?? $error ;

    }  
}