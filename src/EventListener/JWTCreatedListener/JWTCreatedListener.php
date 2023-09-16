<?php

namespace App\EventListener\JWTCreatedListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;



class JWTCreatedListener
{
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        
        $request = $this->requestStack->getCurrentRequest();

        $payload       = $event->getData();

        $payload['ip'] = $request->getClientIp();

        $payload['id'] = $event->getUser()->getIdUser();
     
     
        if  (!empty($event->getUser()->getJugador())) {

            if ($event->getUser()->getJugador()) {
            $payload['jugador'] = $event->getUser()->getJugador()->getId();
            };

            if ($event->getUser()->getJugador()->getClub()){
            $payload['club'] = $event->getUser()->getJugador()->getClub()->getId(); 
            };

            if ($event->getUser()->getJugador()->getEquipo()){
                $payload['equipo'] = $event->getUser()->getJugador()->getEquipo()->getId();
            };
        }
        $event->setData($payload);

    }
}