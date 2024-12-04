<?php
namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class MiConfiguracion extends BaseConfig
{
    public $nombreSistema = 'Scopi';
    public $anio = '2024';
    public $areasCeneval = array(
        'PMA'  => array('campo' => 'exa_ppma', 'desc' => 'Porcentaje Pensamiento Matemático'),
        'PRIN' => array('campo' => 'exa_prin', 'desc' => 'Porcentaje Redacción Indirecta'),
        'PCL'  => array('campo' => 'exa_pcc_cl', 'desc' => 'Porcentaje Comprensión Lectora'),
        'PING' => array('campo' => 'exa_ping', 'desc' => 'Porcentaje Inglés'),
        'PCE'  => array('campo' => 'exa_pcne', 'desc' => 'Porcentaje CENEVAL')
    );
    public $descripcionEstatusSolicitud = [
        "1A"   => "Inscrito en primera lista",
        "1AB"  => "Con derecho en primera lista",
        "1C"   => "Inscrito en corrimiento",
        "2A"   => "Inscrito por vacante 1",
        "3A"   => "Inscrito por vacante 2",
        "3B"   => "Con derecho por invitación",
        "BDCI" => "Baja por cancelación de inscripción",
        "C2A"  => "Cancelación de admisión en primera lista por solicitar un programa al que ya generó escolaridad",
        "CA"   => "Cancelación de admisión en primera lista por solicitar un programa al que ya generó escolaridad",
        "CAC"  => "Cancelación de admisión en corrimiento por solicitar un programa al que ya generó escolaridad",
        "N2A"  => "No inscrito por vacante",
        "NA"   => "Sin derecho a inscripción",
        "NI"   => "No inscrito en primera lista",
        "NIC"  => "No inscrito por corrimiento",
        "NP"   => "No presentó"
    ];
    /**
     * Columnas de la tabla de solicitudes, la llave es como se llama el input en el formulario y el valor es el nombre de la columna en la base de datos
     * @var array
     */
    public $columnMapping = [
        "sexo"               => "asp_sexo",
        "edodom"             => "asp_estadodomicilio",
        "mundom"             => "asp_municipiodomicilio",
        "aexi"               => "asp_esaexi",
        "edonac"             => "asp_estadonacimiento",
        "munnac"             => "asp_municipionacimiento",
        "solicitudes"        => "sol_fase",
        "aceptado"           => "sol_aceptado",
        "inscrito"           => "sol_inscrito",
        "tipoPlantel"        => "esc_tipoplantel",
        "turno"              => "turno_escuela",
        "municipioEscuela"   => "esc_municipio",
        "escuelaProcedencia" => "esc_id",
        "anioingreso"        => "anioingreso",
        "anioegreso"         => "anioegreso",
    ];
    /**
     * Summary of columnDescriptions
     * @var array
     * Descripción de las columnas de la tabla de solicitudes
     */
    public $descripcionColumnas = [
        "sexo"               => "Sexo",
        "edodom"             => "Estado domicilio",
        "mundom"             => "Municipio domicilio",
        "aexi"               => "Es aexi",
        "edonac"             => "Estado nacimiento.",
        "munnac"             => "Municipio nacimiento",
        "solicitudes"        => "Primera lista",
        "aceptado"           => "Aceptado",
        "inscrito"           => "Inscrito",
        "tipoPlantel"        => "Tipo de plantel",
        "turno"              => "Turno escuela",
        "municipioEscuela"   => "Municipio de escuela",
        "escuelaProcedencia" => "Escuela de procedencia",
        "anioingreso"        => "Año ingreso",
        "anioegreso"         => "Año egreso"
    ];

}