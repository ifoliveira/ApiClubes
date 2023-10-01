<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\Update;

class HomeMercureController extends AbstractController
{
    #[Route('/mercure/push', name: 'app_publicar')]
    public function push(Request $request, HubInterface $hub): Response
    {
        $update = new Update (
            'https://127.0.0.1:8000/api/message' , 
            json_encode(['message' => 'HELLO']) ,
            
        );
        $hub->publish($update);
        return new Response('published!');
 
    }
}
