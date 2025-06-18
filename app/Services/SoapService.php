<?php

namespace App\Services;

use SoapClient;

class SoapService
{

    public SoapClient $client;


    // EJEMPLO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    // public function __construct(){
    //     $wsdl = 'http://www.dneonline.com/calculator.asmx?WSDL';
    //     $options = [
    //         'trace' => true,
    //         // 'exceptions' => true,
    //     ];
    //     $this->client = new SoapClient($wsdl, $options);
    // }

    // public function request(){
    //     $r = $this->client->Add(['intA'=>10, 'intB'=>23])->AddResult;

    //     dd($r);
    // }

    public function __construct()
    {

        /**
         * Certificado Servicio SOAP
         * 
         * Necesitamos especificar su ruta, su contraseña (si la tiene), y crear un 
         * contexto con ciertas opciones para que sea aceptada en el servidor.
         * 
         * Todo esto se pasa al final a las opciones al crear nuestra instancia de SoapClient.         * 
         */
        $certPath = base_path('cert/khudoley_sergiy.pem');
        $passphrase = '';

        $sslOpts = [
            'ssl' => [
                'local_cert' => $certPath,
                'passphrase' => $passphrase,
                'verify_peer' => true,
                'verify_peer_name' => true,
                // Para pruebas, podrías temporalmente poner allow_self_signed => true
                // y verify_peer => false, pero en producción no.
            ],
        ];

        $ctx = stream_context_create($sslOpts);


        /**
         * Opciones para nuestro SoapClient
         */

        $options = [
            'trace' => 1,
            'cache_wsdl' => WSDL_CACHE_NONE,  // Evita que se almacene en caché
            'exceptions' => true,
            'stream_context' => $ctx,
        ];

        /**
         * Ruta donde tenemos el WSDL, necesitamos tener allí también los XSD asociados
         */
        $wsdlPath = base_path('wsdl/SistemaFacturacion.wsdl.xml'); // Ruta al WSDL en la carpeta raíz del proyecto


        try {
            $this->client = new SoapClient($wsdlPath, $options);

            // Seleccionamos el ENDPOINT del servicio SOAP
            $this->client->__setLocation('https://prewww1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP');
        } catch (SoapFault $e) {
            dd("Error al cargar el WSDL: " . $e->getMessage());
        }
    }

    /**
     * Funcion para obtener todas las funciones y tipos del WSDL.
     * Así podemos ver que servicios tenemos disponible en el servidor
     * y que esquemas esperan recibir.
     */
    public function info()
    {
        dd([
            'functions' => $this->client->__getFunctions(),
            'types' => $this->client->__getTypes(),
        ]);

        return [
            'functions' => $this->client->__getFunctions(),
            'types' => $this->client->__getTypes(),
        ];
    }


    /**
     * Consultar las facturas presentadas.
     */
    public function consultarFacturas()
    {

        /**
         * Parametros para la request.
         * 
         * En caso de dudas mirar los WSDL y XSD de las definiciones del serivicio SOAP.
         * 
         *  struct ConsultaFactuSistemaFacturacionType {
         *      CabeceraConsultaSf Cabecera;
         *      LRFiltroRegFacturacionType FiltroConsulta;
         *      DatosAdicionalesRespuestaType DatosAdicionalesRespuesta;
         *   }
         */
$consultaFactuSistemaFacturacion = [
    'Cabecera' => [
        'IDVersion' => '1.0',
        'ObligadoEmision' => [
            'NombreRazon' => env('NOMBRE_RAZON', 'ValorPorDefecto'), 
            'NIF' => env('NIF', '00000000X'),
        ],
    ],
    'FiltroConsulta' => [
        'PeriodoImputacion' => [
            'Ejercicio' => '2024',
            'Periodo' => '03',
        ],
        'NumSerieFactura' => null,
        'Contraparte' => null,
        'FechaExpedicionFactura' => null,
        'SistemaInformatico' => [
            'NombreRazon' => env('NOMBRE_RAZON', 'ValorPorDefecto'),
            'NIF' => env('NIF', '00000000X'),
            'NombreSistemaInformatico' => 'ksergioVerifactu',
            'IdSistemaInformatico' => '01',
            'Version' => '0.1',
            'NumeroInstalacion' => '1',
            'TipoUsoPosibleSoloVerifactu' => 'S',
            'TipoUsoPosibleMultiOT' => 'N',
            'IndicadorMultiplesOT' => 'N',
        ],
        'RefExterna' => null,
        'ClavePaginacion' => null,
    ],
    'DatosAdicionalesRespuesta' => [
        'MostrarNombreRazonEmisor' => 'N',
        'MostrarSistemaInformatico' => 'N',
    ],
];

        try {
            $response = $this->client->ConsultaFactuSistemaFacturacion($consultaFactuSistemaFacturacion);

            dd($response);

            return $response;
        } catch (\SoapFault $e) {
            // dd($e);

            // DEBUGING
            // --------------------------------------------
            // Muestro el mensaje de la excepción
            dd([
                'SoapFault message' => $e->getMessage(),
                'Last Request Headers' => $this->client->__getLastRequestHeaders(),
                'Last Request' => $this->client->__getLastRequest(),
                'Last Response Headers' => $this->client->__getLastResponseHeaders(),
                'Last Response' => $this->client->__getLastResponse(),
            ]);
        }
    }
}