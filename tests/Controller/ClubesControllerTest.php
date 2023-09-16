<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClubesControllerTest extends WebTestCase
{
    public function testJSONVacio(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $client->request('POST', 'api/clubes');

        // Validate a successful response and some content
        $this->assertEquals('400', $client->getResponse()->getStatusCode());

    }

    public function testNombreInvalid(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $client->request('POST', 'api/clubes',
                         [],
                         [],
                         ['CONTENT_TYPE' => 'application/json'],
                         '{
                            "nombre":"L",
                            "direccion":"C/ José Cueto, 25  Avilés",
                            "codigoAstfut":"4546546",
                                "web": "web",
                                "email": "email@a.com",
                                "telefono": "985164060",
                            "base64Image":"data:image/jpeg;base64,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",
                            
                            "equipos": 
                                  [  
                                      {
                                        "nombre": "Veriña C.F. b",
                                        "codigoAstfut": "5465456",
                                        "categoria" : "Alevin"
                                    
                                    },
                            
                                      {
                                        "nombre": "Veriña C.F. b",
                                        "codigoAstfut": "5465456",
                                        "categoria" : "Infantil"
                                    }
                            
                                  ] 
                            }'
        );

        // Validate a successful response and some content
        $this->assertEquals('400', $client->getResponse()->getStatusCode());

    }    

    public function testCreacionClub(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $client->request('POST', 'api/clubes',
                         [],
                         [],
                         ['CONTENT_TYPE' => 'application/json'],
                         '{
                            "nombre":"Llano 2000",
                            "direccion":"C/ José Cueto, 25  Avilés",
                            "codigoAstfut":"4546546",
                                "web": "web",
                                "email": "email@a.com",
                                "telefono": "985164060",
                            "base64Image":"data:image/jpeg;base64,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",
                            
                            "equipos": 
                                  [  
                                      {
                                        "nombre": "Llano 2000 B",
                                        "codigoAstfut": "5465456",
                                        "categoria" : "Alevin"
                                    
                                    },
                            
                                      {
                                        "nombre": "Llano 2000 A",
                                        "codigoAstfut": "5465456",
                                        "categoria" : "Infantil"
                                    }
                            
                                  ] 
                            }'
        );

        // Validate a successful response and some content
        $this->assertEquals('201', $client->getResponse()->getStatusCode());

    }        
}