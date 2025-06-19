<?php

namespace App\Services\VeriFactu\DTO;

class VeriFactuAnulacionDTO
{
    public function __construct(
        public string $IDEmisorFacturaAnulada,
        public string $NumSerieFacturaAnulada,
        public string $FechaExpedicionFacturaAnulada,
        public string $HuellaAnterior,
        public string $FechaHoraHusoGenRegistro
    ) {}
}
