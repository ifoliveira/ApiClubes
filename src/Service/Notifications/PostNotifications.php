<?php

namespace App\Service\Notifications;

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

    public function confirmacion_asistencia(string $idequipo, string $jugador, string $fecha, string $momequipo): array 

    {   
        print_r ('entrenador'.$idequipo .'');
       
        //$client= $this->httpClient->create();

        $jsonData = [
            'to' => '/topics/entrenador'.$idequipo .'',
            'notification' => [
                'title' => '' . $momequipo . '',
                //'body' => 'Convocatoria en la siguiente fecha ' . date_format($Convocatoria[0]->getEvento()->getFecha(), 'D M j'),
                'body' => $jugador . 'Aceptado convocatoria',
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