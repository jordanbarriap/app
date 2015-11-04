<?php
function enformatoFecha($fecha){
    if($fecha){
        $dia = substr($fecha, -2);
        $mes   = substr($fecha, 5, 2);
        $ano = substr($fecha, 2,2);
        $fecha = $dia . '-' . $mes . '-20' . $ano;
    }

    return $fecha;
}

/**
 * retorna fecha solamente con el dia (viene con hora desde la BD)
 *
 * @param Integer $fecha
 *
 * @return String
 */
function enformatoFechaDia($fecha) {
     $fecha = substr($fecha, 0, 10);
    $fecha = enformatoFecha($fecha);
    return $fecha;
}

/**
 * formatea el semestre respecto al valor por la BD para despliegue en la tabla del admin
 *
 * @param Integer $val_semestre
 *
 * @return String
 */
function enformatoSemestre($val_semestre) {
    switch($val_semestre){
        case 0:
            $_resp = 'Ambos';
        break;
        case 1:
            $_resp = 'Primero';
        break;
         case 2:
            $_resp = 'Segundo';
        break;
    }
    return $_resp;
}


?>
