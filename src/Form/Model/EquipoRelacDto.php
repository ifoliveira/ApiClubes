<?php

namespace App\Form\Model;

class EquipoRelacDto
{
    public ?string $url = null;
    public ?string $grupo = null;

    public function __construct()
    {

    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function fromArray($array)
    {
        $model = new self();
        if (isset($array['url'])) {
            $model->seturl($array['url']);
        }
        if (isset($array['grupo'])) {
            $model->setGrupo($array['grupo']);
        }

        return $model;
    }    

    public function getGrupo()
    {
        return $this->grupo;
    }

    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    public function geturl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }


    public function toArray()
    {
        return ['url' => $this->url, 'grupo' => $this->grupo];
    }
}