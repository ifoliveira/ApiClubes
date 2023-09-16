<?php

namespace App\Form\Model;


use App\Entity\Jugador;
use App\Form\Model\EquipoDto;

class JugadorDto
{
    public ?string $id = null;
    public ?string $nombre = null;
    public ?string $foto = null;
    public ?EquipoDto $equipo = null;    
    public ?array $club = null;    
    
    public function __construct()
    {

        }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromJugador(Jugador $jugador): self
    {
        $dto = new self();
        
        $dto->nombre = $jugador->getApellidos() . ',' .  $jugador->getNombre();
        $dto->id = $jugador->getId();

        return $dto;
    }

    public function separarNombre(): array
    {
        $salida=  [];

        preg_match('/,/',$this->nombre,$coincidencias,PREG_OFFSET_CAPTURE);

        $salida['nombre'] = substr($this->nombre,$coincidencias[0][1]+1);
        $salida['apellidos'] = substr($this->nombre,0,$coincidencias[0][1]);

        return $salida;
    }

    public function getId(): ?string { return $this->id; }
    public function setId(?string $id): self { $this->id = $id; return $this; }    

}
