<?php

namespace App\Controller\Api;

use App\Repository\ConvocatoriaRepository;
use App\Entity\Convocatoria;
use App\Service\Convocatoria\ConvocatoriaFormProcessor;
use Exception;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Convocatoria\GetConvocatoria;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{ Get, Post, Put};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use App\Service\Notifications\PostNotifications;



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
    public function postAction(Request $request, ConvocatoriaFormProcessor $ConvocatoriaFormProcessor, PostNotifications $postNotifications){

        $diassemana = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        [$Convocatoria, $error]= ($ConvocatoriaFormProcessor)($request);


        
        if ($Convocatoria) {
            [$status, $contenido] = $postNotifications->mensaje_convocatoria($Convocatoria[0]->getEvento()->getEquipo()->getId(), $diassemana[date_format($Convocatoria[0]->getEvento()->getFecha(), 'w')] . " " . date_format($Convocatoria[0]->getEvento()->getFecha(), 'd') . " de " . $meses[date_format($Convocatoria[0]->getEvento()->getFecha(), 'n') - 1],$Convocatoria[0]->getEvento()->getTipo() . " " .  $Convocatoria[0]->getEvento()->getEquipo()->getNombre() . " " .$Convocatoria[0]->getEvento()->getEquipo()->getCategoria());
            return $Convocatoria ?? $error ;
        } else {

            return 'Sin convocatorias creadas'   ;         
        }
        

    }  

    #[Put(path: "/convocatoria/{id}")]
    #[ViewAttribute(serializerGroups: ['convocatoria'], serializerEnableMaxDepthChecks: true)]    
    public function putAction(Request $request, String $id, ConvocatoriaFormProcessor $ConvocatoriaFormProcessor,  PostNotifications $postNotifications){
        $diassemana = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        [$Convocatoria, $error]= ($ConvocatoriaFormProcessor)($request, $id);

        [$status, $contenido] = $postNotifications->confirmacion_asistencia($Convocatoria->getEvento()->getEquipo()->getId(), $Convocatoria->getJugador()->getNombre(),$diassemana[date_format($Convocatoria->getEvento()->getFecha(), 'w')] . " " . date_format($Convocatoria->getEvento()->getFecha(), 'd') . " de " . $meses[date_format($Convocatoria->getEvento()->getFecha(), 'n') - 1],$Convocatoria->getEvento()->getTipo() . " " .  $Convocatoria->getEvento()->getEquipo()->getNombre() . " " .$Convocatoria->getEvento()->getEquipo()->getCategoria());

        return $Convocatoria ?? $error ;

    }      
}