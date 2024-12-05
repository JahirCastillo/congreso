<?php
/**
 * Formatea una fecha al estilo "04 de diciembre de 2024".
 *
 * @param string $fecha Fecha en formato Y-m-d.
 * @return string Fecha formateada.
 */
function fechaEspaniol($fecha)
{
    setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
    return strftime('%d de %B de %Y', strtotime($fecha));
}
