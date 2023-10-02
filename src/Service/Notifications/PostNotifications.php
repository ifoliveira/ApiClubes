<?php

namespace App\Service\Notifications;

use App\Entity\Convocatoria;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostNotifications
{

    public function __construct(private HttpClientInterface  $httpClient)
        {        

        }

    public function __invoke()
       {
        

    }

    public function mensaje_convocatoria(string $idequipo, string $fecha, string $momequipo): array 

    {


        //$client= $this->httpClient->create();

        $jsonData = [
            'to' => '/topics/' .$idequipo .'',
            'notification' => [
                'title' => '' . $momequipo . '',
                //'body' => 'Convocatoria en la siguiente fecha ' . date_format($Convocatoria[0]->getEvento()->getFecha(), 'D M j'),
                'body' => 'Hay convocatoria el ' . $fecha . ' comprueba si estas convocado y confirma tu asistencia',
            ],
        ];

        $accessToken = 'AAAASBS6Q9M:APA91bFu887zV_5FeWFBUvFyIA-ZM3A3Sdrx4QsnBGbwwx1puvYGKTnU0ea8M_MkSrOTdjHHsFDwZEUdtwhcA-KDoUZ-xhjSv_GoU5RRCj8N9alLNDIzdneejKKTDp0CgvmKoabWEQAO';

        // Realiza la solicitud POST
        $response = $this->httpClient->request('POST', 'https://fcm.googleapis.com/fcm/send', [
        'headers' => [
             'Authorization' => 'Bearer ' . $accessToken,
             'Content-Type' => 'application/json',
          ],

            'body' => json_encode($jsonData),
        ]);

        // Obtiene la respuesta
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();

        return ([$statusCode, $content]);
    }

    public function confirmacion_asistencia(Convocatoria $Convocatoria): array 

    {             
        $nomequipo= $Convocatoria->getEvento()->getTipo() . " " .  $Convocatoria->getEvento()->getEquipo()->getNombre() . " " .$Convocatoria->getEvento()->getEquipo()->getCategoria();
        $jugador = $Convocatoria->getJugador()->getNombre() . " " . $Convocatoria->getJugador()->getApellidos() ;

        if ($Convocatoria->getAsistencia() == 1) {
            $texto = "ha aceptado la convocatoria";

        } elseif ($Convocatoria->getAsistencia() == 2) {
            $texto = "no acudirá '" . $Convocatoria->getComentario() . "'";

        } elseif ($Convocatoria->getAsistencia() == 3) {
            $texto = "es dudoso en la convocatoria";

        }

        $jsonData = [
            'to' => '/topics/entrenador' . $Convocatoria->getEvento()->getEquipo()->getId() .'',
            'notification' => [
                'title' => 'Convocatoria al' . $nomequipo . '',
                //'body' => 'Convocatoria en la siguiente fecha ' . date_format($Convocatoria[0]->getEvento()->getFecha(), 'D M j'),
                'body' => $jugador . ', ' . $texto
            ],
        ];

        $accessToken = 'AAAASBS6Q9M:APA91bFu887zV_5FeWFBUvFyIA-ZM3A3Sdrx4QsnBGbwwx1puvYGKTnU0ea8M_MkSrOTdjHHsFDwZEUdtwhcA-KDoUZ-xhjSv_GoU5RRCj8N9alLNDIzdneejKKTDp0CgvmKoabWEQAO';

        // Realiza la solicitud POST
        $response = $this->httpClient->request('POST', 'https://fcm.googleapis.com/fcm/send', [
        'headers' => [
             'Authorization' => 'Bearer ' . $accessToken,
             'Content-Type' => 'application/json',
          ],

            'body' => json_encode($jsonData),
        ]);

        // Obtiene la respuesta
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        print_r ('entrenador'.$statusCode .''. $content.'');
        return ([$statusCode, $content]);
    }

}