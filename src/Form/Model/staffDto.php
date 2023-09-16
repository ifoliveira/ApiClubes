<?php

namespace App\Form\Model;


use App\Entity\staff;

class staffDto
{

    public ?string $nombre = null;
    public ?string $tipo = null;
    public ?string $foto = null;
    public ?array $equipo = null;    
    public ?array $club = null;    
    
    public function __construct()
    {

        }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromstaff(staff $staff): self
    {
        $dto = new self();
        $dto->nombre = $staff->getApellidos() . ' ,' .  $staff->getNombre();
        $dto->tipo = $staff->getTipo();

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

}
