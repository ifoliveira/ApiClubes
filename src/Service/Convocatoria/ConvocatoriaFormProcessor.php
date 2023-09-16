<?php

namespace App\Service\Convocatoria;

use App\Form\Model\ConvocatoriaDto;
use App\Form\Type\ConvocatoriaFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConvocatoriaRepository;
use App\Service\Convocatoria\CreateConvocatoria;
use App\Service\Eventos\GetEvento;
use App\Service\Jugador\GetJugador;
use DateTime;
use Symfony\Component\Form\Extension\Core\DataTransformer\UuidToStringTransformer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Form\FormFactoryInterface;
use Ramsey\Uuid\Uuid;

class ConvocatoriaFormProcessor
 {
    public function __construct(
        private ConvocatoriaRepository $ConvocatoriaRepository,
        private CreateConvocatoria $createConvocatoria,
        private GetEvento $getEvento,
        private GetJugador $getJugador,
        private GetConvocatoria $getConvocatoria,
        private FormFactoryInterface $formFactory,
    ) {

    }
    
    public function __invoke(Request $request, ?String $convocatoriaid = null): array
        
    {
        $convocatoria = null;
        $convocatoriaDto = null;    
        
        if ($convocatoriaid === null) {
            $convocatoriaDto = convocatoriaDto::createEmpty();
        } else {
            $convocatoria = ($this->getConvocatoria)($convocatoriaid);

            $convocatoriaDto = ConvocatoriaDto::cretaFromConvocatoria($convocatoria);
        }
       
       
        $content = json_decode($request->getContent(), true);

        if (empty($content)) {

            return [null, 'JSON Vacio'];
        } 


        $form = $this->formFactory->create(ConvocatoriaFormType::class, $convocatoriaDto);
        $form->submit($content, false);
 
      

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }


        if ($convocatoriaDto->getEvento() === null) {
            return [null, "El evento debe estar informao"];
        } else {
            $evento = ($this->getEvento)($convocatoriaDto->getEvento());
            $convos = $evento->getConvocatorias();
        }        

     
       if ($convocatoria === null){
           
            if (empty($convocatoriaDto->getJugador())) {
                foreach ($evento->getEquipo()->getJugadores() as &$jugador){
                    $exist = false ;
                    if (!empty($convos)) {
                        foreach($convos as &$convo){
                            if ($convo->getJugador()->getId() == $jugador->getId()) {
                                $exist = true;
                            }
                        }

                        if (!$exist) {
                            $jugadores[]= $jugador;    
                        }
                    } else {
                 $jugadores[]= $jugador;
                }
                } 
            } else {

                foreach ($convocatoriaDto->getJugador() as &$jugador){
                    $exist = false ;
                    if (!empty($convos)) {
                        foreach($convos as &$convo){
                            if ($convo->getJugador()->getId() == $jugador->getId()) {
                                $exist = true;
                            }
                        }

                        if (!$exist) {
                            $jugadores[]= $jugador;    
                        }
                    } else {
                 $jugadores[]= $jugador;
                }
                }
            }

            if (!empty($jugadores)) {
                foreach ($jugadores as $NuevoJugador) {

                    $convocatoria = null;

                    $jugador = ($this->getJugador)($NuevoJugador->getId());
                    
                    $convocatoria = ($this->createConvocatoria)
                                        ($evento, 
                                        $jugador
                                        );
                                        
                        $this->ConvocatoriaRepository->save($convocatoria,true);
                        $Convocatoria[]=$convocatoria;
                        
                }

                return [$Convocatoria, null]; 
                
            } else {
                return [null, null]; 
            }

        } else {

            $convocatoria->update(
                $convocatoriaDto->asistencia,
                $convocatoriaDto->justificado,
                $convocatoriaDto->titular,
                $convocatoriaDto->minutos,
                $convocatoriaDto->comentario,
                    );
 
            $this->ConvocatoriaRepository->save($convocatoria,true);
            return [$convocatoria, null];       
        }
        
        return [$form, null];     

    }
 }