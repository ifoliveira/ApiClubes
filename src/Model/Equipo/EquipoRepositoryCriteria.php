<?php

namespace App\Model\Equipo;



class EquipoRepositoryCriteria
{
    public function __construct(
        public readonly ?string $Id = null,
        public readonly ?string $clubId = null,
        public readonly ?string $nombre = null,
        public readonly ?string $codequipo = null,
        public readonly ?string $fecha_baja = null,
        public readonly ?string $categoria = null,
        public readonly int $itemsPerPage = 10,
        public readonly int $page = 1
    ) {
    }
}