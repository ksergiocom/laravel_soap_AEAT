<?php

namespace App\Services\VeriFactu\DTO;

class VeriFactuEventoDTO
{
    public function __construct(
        public string $NIF,
        public string $ID,
        public string $IdSistemaInformatico,
        public string $Version,
        public string $NumeroInstalacion,
        public string $NIF_Obligado,
        public string $TipoEvento,
        public string $HuellaAnterior,
        public string $FechaHoraHusoGenEvento
    ) {}
}