<?php
// src/Controller/TrackingController.php
namespace App\Controller;

use App\Entity\EmailTracking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrackingController
{
    #[Route('/track/open/{clubId}', name: 'track_email_open')]
    public function track(int $clubId, Request $request, EntityManagerInterface $em): Response
    {
        $tracking = new EmailTracking();
        $tracking->setClubId($clubId);
        $tracking->setAbiertoA(new \DateTimeImmutable());
        $tracking->setIp($request->getClientIp());
        $tracking->setUserAgent($request->headers->get('User-Agent'));

        $em->persist($tracking);
        $em->flush();

        // Píxel GIF transparente de 1x1 (Base64)
        $gif = base64_decode("R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==");

        return new Response($gif, 200, [
            'Content-Type' => 'image/gif',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
