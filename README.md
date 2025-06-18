# Proyecto VeriFactu Laravel

Este proyecto es una implementación inicial de un servidor web basado en **Laravel**, diseñado para interactuar con el sistema de la Agencia Tributaria española a través de su servicio SOAP **VeriFactu**, en cumplimiento con la Ley Antifraude.

![example](/github.png)

## ¿Qué hace este proyecto?

El objetivo principal es ofrecer un servicio que facilite la comunicación con la Agencia Tributaria para:

- Consultar facturas emitidas.
- Dar de alta nuevas facturas.
- Subsanar errores en facturas.
- Eliminar facturas cuando sea necesario.

Todo ello utilizando el protocolo SOAP y los esquemas oficiales proporcionados por la Agencia Tributaria.

## Tecnologías y estructura

- **Laravel** como framework backend.
- Cliente SOAP en PHP para consumir los servicios de VeriFactu.
  - El core de la lógica esta ubicada en **app/Services/SoapService.php**
- Importación y uso de archivos **WSDL** y **XSD** oficiales para definir los servicios y tipos de datos.
- Uso de certificados digitales almacenados en carpetas específicas:
  - `/cert` para los certificados necesarios (en formato `.pem`).
  - `/wsdl` para los archivos WSDL y XSD.


## Certificados digitales

Para el correcto funcionamiento del servicio, es necesario usar un certificado digital en formato `.pem`. Si se dispone de un certificado en formato `.pfx` (PKCS#12), se debe convertir a `.pem` y colocar el resultado en la carpeta `/cert`. Esto permite que el cliente SOAP en PHP pueda autenticar las peticiones con la Agencia Tributaria.

## Estado actual

Este proyecto se encuentra en una versión inicial y sencilla, con las funcionalidades básicas implementadas y en desarrollo. La idea es ir ampliando y mejorando el conjunto de métodos para cubrir todas las necesidades del servicio VeriFactu.

## Información adicional

### Configuración necesaria
Antes de usar el proyecto es necesario agregar las variables de entorno necesarias al archivo .env el cual usaré como fuente de la verdad única para los valores usados en el servicio SOAP.

NOMBRE_RAZON=
NIF=
*Pendiente agregar otros campos (idSoftware, etc)*


### ¿Como leer los WSDL y XSD?
El archivo WSDL define el servicio y que opciones tenemos.

Para ver las opciones seguimos los pasos:
1. **portType** Que servicios podemos usar
2. **input** Nos dice el esquema esperado del XML
3. **como navegar** pf: sfWdsl <- Es el namespace. Miramos arriba en las definciones en el apartado de imports. Aqui vemos que hemos asociado este namespace a un archivo concreto. En este caso *xmlns:sfWdsl="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SistemaFacturacion.wsdl"* SistemaFacturacion. Para buscar las siguientes definiciones buscamos dentro de este fichero los esquemas necesarios.
4. **Campos opcionales/requiredos** Si aparece el tag minOcurrs="0" es que es opcional (pide el minimo de ocurencias). También debemos fijarnos en los campos choices, esto nos dice que debemos elegir entre alguno de los choices propuestos.
5. **Construir XML** Construimos el XML a travez del cliente proporcionado por PHP *Ver detalles en app/Services/SoapService.php*



### Recursos AEAT
1. [Portal de pruebas](https://preportal.aeat.es/)
2. [Validaciones respuestas SOAP](https://www.agenciatributaria.es/static_files/AEAT_Desarrolladores/EEDD/IVA/VERI-FACTU/Validaciones_Errores_Veri-Factu.pdf)
3. [Codigos errores respuesta](https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/errores.properties)
4. [Ejemplos](utaria.gob.es/static_files/AEAT_Desarrolladores/EEDD/IVA/VERI-FACTU/Veri-Factu_Descripcion_SWeb.pdf)
5. [Información tecnica y descarga esquemas](https://sede.agenciatributaria.gob.es/Sede/iva/sistemas-informaticos-facturacion-verifactu/informacion-tecnica.html)
6. [Consulta portal web](https://sede.agenciatributaria.gob.es/static_files/common/html/selector_acceso/SelectorAccesos.html?rep=S&ref=%2Fwlpl%2FTIKE-CONT%2FSvTikeEmitidasQuery&aut=CP)
