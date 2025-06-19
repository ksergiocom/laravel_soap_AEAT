<?php

namespace App\Services\VeriFactu\DTO;

class VeriFactuAltaDTO
{
    public function __construct(
        public string $IDEmisorFactura,
        public string $NumSerieFactura,
        public string $FechaExpedicionFactura, // formato DD-MM-YYYY
        public string $TipoFactura,
        public string $CuotaTotal,
        public string $ImporteTotal,
        public string $HuellaAnterior,
        public string $FechaHoraHusoGenRegistro // formato ISO8601
    ) {}
}
