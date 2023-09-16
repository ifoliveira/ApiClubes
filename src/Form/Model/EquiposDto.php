<?php

namespace App\Form\Model;

use App\Entity\Equipo;

class EquiposDto
{

    public ?string $nombre = null;
    public ?string $urlnom = null;
    public ?string $categoria = null;

    public function __construct()
    {

    }    
  
    public static function fromArray($array)
    {
        $model = new self();
        if (isset($array['nombre'])) {
            $model->setNombre($array['nombre']);
        }
        if (isset($array['urlnom'])) {
            $model->seturlnom($array['urlnom']);
        }
        if (isset($array['categoria'])) {
            $model->setCategoria($array['categoria']);
        }

        return $model;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function geturlnom()
    {
        return $this->urlnom;
    }

    public function setUrlnom($urlnom)
    {
        $this->urlnom = $urlnom;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public function toArray()
    {
        return ['nombre' => $this->nombre, 'urlnom' => $this->urlnom, 'categoria' => $this->categoria];
    }

     
}