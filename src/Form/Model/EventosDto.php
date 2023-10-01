<?php

namespace App\Form\Model;

use App\Entity\Eventos;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\VarDumper\Cloner\Data;


class EventosDto
{
    public ?int $id = null;
    public ?String $fechaIni = null;
    public ?String $fechaFin = null;
    public ?String $horaIni = null;
    public ?String $horaFin = null;
    public ?String $lugar = null;
    public ?String $tipo = null;
    public ?Array  $semana = [];


    public function __construct()
    {

    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromEventos(Eventos $evento): self
    {
        $dto = new self();
        $dto->id = $evento->getId();
        $dto->fechaIni = $evento->getFecha();
        $dto->fechaFin = $evento->getFecha();
        $dto->horaIni = $evento->getHoraIni();
        $dto->horaFin = $evento->getHoraFin();
        $dto->lugar = $evento->getLugar();
        $dto->tipo = $evento->getTipo();                


        return $dto;
    }



    public function obtenerFechas(): array
    {
        $diasemana = 0;
        $fechas = [];

        $nuevaFecha = strtotime($this->fechaIni);

        print_r($this->semana);

        if ($this->fechaIni != $this->fechaFin) {


            do {

                $diasemana = intval(date("w", strtotime($this->fechaIni))) - 1 ;
                $diasemana  = $diasemana < 0 ? $diasemana = 6 : $diasemana;                
        
                if ($this->semana[$diasemana] == 1) {
                    $fechas[] = $this->fechaIni;
                }
                $nuevaFecha = strtotime($this->fechaIni. "+ 1 days");

                $this->fechaIni =  date('Y-m-d', $nuevaFecha);

            } while ($nuevaFecha <= strtotime($this->fechaFin));

        } else {

            $fechas[] = $this->fechaIni;

        }

        return $fechas;
    }

   


    /**
     * Get the value of fechaIni
     *
     * @return ?String
     */
    public function getFechaIni(): ?String
    {
        return $this->fechaIni;
    }

    /**
     * Set the value of fechaIni
     *
     * @param ?String $fechaIni
     *
     * @return self
     */
    public function setFechaIni(?String $fechaIni): self
    {
        $this->fechaIni = $fechaIni;

        return $this;
    }

    /**
     * Get the value of fechaFin
     *
     * @return ?String
     */
    public function getFechaFin(): ?String
    {
        return $this->fechaFin;
    }

    /**
     * Set the value of fechaFin
     *
     * @param ?String $fechaFin
     *
     * @return self
     */
    public function setFechaFin(?String $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get the value of horaIni
     *
     * @return ?String
     */
    public function getHoraIni(): ?String
    {
        return $this->horaIni;
    }

    /**
     * Set the value of horaIni
     *
     * @param ?String $horaIni
     *
     * @return self
     */
    public function setHoraIni(?String $horaIni): self
    {
        $this->horaIni = $horaIni;

        return $this;
    }

    /**
     * Get the value of horaFin
     *
     * @return ?String
     */
    public function getHoraFin(): ?String
    {
        return $this->horaFin;
    }

    /**
     * Set the value of horaFin
     *
     * @param ?String $horaFin
     *
     * @return self
     */
    public function setHoraFin(?String $horaFin): self
    {
        $this->horaFin = $horaFin;

        return $this;
    }

    /**
     * Get the value of lugar
     *
     * @return ?String
     */
    public function getLugar(): ?String
    {
        return $this->lugar;
    }

    /**
     * Set the value of lugar
     *
     * @param ?String $lugar
     *
     * @return self
     */
    public function setLugar(?String $lugar): self
    {
        $this->lugar = $lugar;

        return $this;
    }

    /**
     * Get the value of tipo
     *
     * @return ?String
     */
    public function getSemana(): ?Array
    {
        return $this->semana;
    }

    /**
     * Set the value of tipo
     *
     * @param ?String $tipo
     *
     * @return self
     */
    public function setSemana(?Array $semana): self
    {
        $this->semana = $semana;

        return $this;
    }


    /**
     * Get the value of tipo
     *
     * @return ?String
     */
    public function getTipo(): ?String
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @param ?String $tipo
     *
     * @return self
     */
    public function setTipo(?String $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}