<?php
/*
 * Inserta en la tabla rec_megusta_mensaje las valoraciones 'Me gusta', o
 * elimina de la tabla rec_megusta_mensaje las valoraciones 'Ya no me gusta'.
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Cristian Miranda - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 */

$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");

$id_mensaje         = $_REQUEST["id_mensaje"];
$id_usuario_valora  = $_SESSION["klwn_id_usuario"];
$megusta            = $_REQUEST["megusta"];

if(existeSesion()){
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    if($megusta == 1){ // Me gusta
        $id_usuario_autor = dbRECObtenerIdUsuarioAutorMuralDiseno($id_mensaje,$conexion);
        dbRECInsertarMeGustaMensaje($id_mensaje, $id_usuario_valora, $id_usuario_autor, $conexion);
    }
    else{ // Ya no me gusta
        dbRECEliminarMeGustaMensaje($id_mensaje, $id_usuario_valora, $conexion);
    }
    dbDesconectarMySQL($conexion);
}
else{
    echo "-1";
}
?>