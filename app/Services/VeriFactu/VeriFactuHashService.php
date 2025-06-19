<?php

namespace App\Services\VeriFactu;

use App\Services\VeriFactu\DTO\VeriFactuAltaDTO;
use App\Services\VeriFactu\DTO\VeriFactuAnulacionDTO;
use App\Services\VeriFactu\DTO\VeriFactuEventoDTO;

/*
    Ejemplos de uso del servicio.

    🧾 Alta
    $hash = (new VeriFactuHashService)->generarHuellaAlta([
        'IDEmisorFactura' => '89890001K',
        'NumSerieFactura' => '12345678/G33',
        'FechaExpedicionFactura' => '01-01-2024',
        'TipoFactura' => 'F1',
        'CuotaTotal' => '12.35',
        'ImporteTotal' => '123.45',
        'HuellaAnterior' => '',
        'FechaHoraHusoGenRegistro' => '2024-01-01T19:20:30+01:00',
    ]);

    ❌ Anulación
    $hash = (new VeriFactuHashService)->generarHuellaAnulacion([
        'IDEmisorFacturaAnulada' => '89890001K',
        'NumSerieFacturaAnulada' => '12345679/G34',
        'FechaExpedicionFacturaAnulada' => '01-01-2024',
        'HuellaAnterior' => 'F7B94CFD8924EDFF273501B01EE5153E...',
        'FechaHoraHusoGenRegistro' => '2024-01-01T19:20:40+01:00',
    ]);

    📅 Evento
    $hash = (new VeriFactuHashService)->generarHuellaEvento([
        'NIF' => '12345678A',
        'ID' => '',
        'IdSistemaInformatico' => 'ID-SIF-001',
        'Version' => '1.0.0',
        'NumeroInstalacion' => '1',
        'NIF_Obligado' => '88888888B',
        'TipoEvento' => 'ALTA',
        'HuellaAnterior' => '',
        'FechaHoraHusoGenEvento' => '2024-01-01T19:20:30+01:00',
    ]);
*/

class VeriFactuHashService
{
    /**
     * Genera la huella de un registro de facturación de alta.
     *
     * @param VeriFactuAltaDTO $dto Datos del registro de alta
     * @return string Huella en formato SHA256 en mayúsculas
     */
    public function generarHuellaAlta(VeriFactuAltaDTO $datos): string
    {
        $cadena = $this->formatCampo('IDEmisorFactura', $datos['IDEmisorFactura'] ?? '') .
            '&' . $this->formatCampo('NumSerieFactura', $datos['NumSerieFactura'] ?? '') .
            '&' . $this->formatCampo('FechaExpedicionFactura', $datos['FechaExpedicionFactura'] ?? '') .
            '&' . $this->formatCampo('TipoFactura', $datos['TipoFactura'] ?? '') .
            '&' . $this->formatCampo('CuotaTotal', $this->formatearImporte($datos['CuotaTotal'] ?? '')) .
            '&' . $this->formatCampo('ImporteTotal', $this->formatearImporte($datos['ImporteTotal'] ?? '')) .
            '&' . $this->formatCampo('Huella', $datos['HuellaAnterior'] ?? '') .
            '&' . $this->formatCampo('FechaHoraHusoGenRegistro', $datos['FechaHoraHusoGenRegistro'] ?? '');

        return strtoupper(hash('sha256', $cadena));
    }
    /**
     * Genera la huella de un registro de facturación de anulación.
     *
     * @param VeriFactuAnulacionDTO $dto Datos del registro de anulación
     * @return string Huella en formato SHA256 en mayúsculas
     */
    public function generarHuellaAnulacion(VeriFactuAnulacionDTO $datos): string
    {
        $cadena = $this->formatCampo('IDEmisorFacturaAnulada', $datos['IDEmisorFacturaAnulada'] ?? '') .
            '&' . $this->formatCampo('NumSerieFacturaAnulada', $datos['NumSerieFacturaAnulada'] ?? '') .
            '&' . $this->formatCampo('FechaExpedicionFacturaAnulada', $datos['FechaExpedicionFacturaAnulada'] ?? '') .
            '&' . $this->formatCampo('Huella', $datos['HuellaAnterior'] ?? '') .
            '&' . $this->formatCampo('FechaHoraHusoGenRegistro', $datos['FechaHoraHusoGenRegistro'] ?? '');

        return strtoupper(hash('sha256', $cadena));
    }

    /**
     * Genera la huella de un registro de evento del sistema VERI*FACTU.
     *
     * @param VeriFactuEventoDTO $dto Datos del evento del sistema
     * @return string Huella en formato SHA256 en mayúsculas
     */
    public function generarHuellaEvento(VeriFactuEventoDTO $datos): string
    {
        $cadena = $this->formatCampo('NIF', $datos['NIF'] ?? '') .
            '&' . $this->formatCampo('ID', $datos['ID'] ?? '') .
            '&' . $this->formatCampo('IdSistemaInformatico', $datos['IdSistemaInformatico'] ?? '') .
            '&' . $this->formatCampo('Version', $datos['Version'] ?? '') .
            '&' . $this->formatCampo('NumeroInstalacion', $datos['NumeroInstalacion'] ?? '') .
            '&' . $this->formatCampo('NIF', $datos['NIF_Obligado'] ?? '') . // Segundo NIF (ObligadoEmision)
            '&' . $this->formatCampo('TipoEvento', $datos['TipoEvento'] ?? '') .
            '&' . $this->formatCampo('HuellaEvento', $datos['HuellaAnterior'] ?? '') .
            '&' . $this->formatCampo('FechaHoraHusoGenEvento', $datos['FechaHoraHusoGenEvento'] ?? '');

        return strtoupper(hash('sha256', $cadena));
    }

    /**
     * Formatea un campo como nombre=valor sin espacios iniciales/finales.
     */
    protected function formatCampo(string $nombre, string $valor): string
    {
        return $nombre . '=' . trim($valor);
    }

    /**
     * Normaliza importes según reglas VERI*FACTU (quita ceros a la derecha).
     */
    protected function formatearImporte($valor): string
    {
        if ($valor === null || $valor === '')
            return '';
        return rtrim(rtrim(trim((string) $valor), '0'), '.');
    }
}
