<?php
namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PublicidadSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;
    private RouterInterface $router;

    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request || !$request->hasSession()) {
            return;
        }

        $session = $request->getSession();
        $rutaActual = $request->attributes->get('_route');

        // No activar si estamos en la página de publicidad
        if ($rutaActual === 'publicidad') {
            return;
        }

        // Rutas donde queremos mostrar publicidad
        $rutasConPublicidad = ['clasificacion_grupo'];

        if (in_array($rutaActual, $rutasConPublicidad)) {

            // Tiempo en segundos entre publicidades (5s en pruebas)
            $intervalo = 20;

            $ultimo = $session->get('publicidad_timestamp', 0);
            $ahora = time();

            if (($ahora - $ultimo) >= $intervalo) {
                $session->set('publicidad_timestamp', $ahora);
                $session->set('ruta_destino', $request->getRequestUri());
                $event->setResponse(new RedirectResponse($this->router->generate('publicidad')));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => ['onKernelRequest', 10],
        ];
    }
}


?>