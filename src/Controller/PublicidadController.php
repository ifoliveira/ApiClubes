<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicidadController extends AbstractController
{
    #[Route('/publicidad', name: 'publicidad')]
    public function index(SessionInterface $session): Response
    {
        // Marcar publicidad como vista
        $session->set('publicidad_vista', true);

        $rutaDestino = $session->get('ruta_destino', '/');

        return $this->render('publicidad/index.html.twig', [
            'rutaDestino' => $rutaDestino,
        ]);
    }
}
?>