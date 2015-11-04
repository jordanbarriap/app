<?php
/**
*Script que verifica la veracidad de la encuesta
*
* LICENSE: código fuente distribuido con licencia LGPL
*
* @author  Sergio Bustamante M. - Kelluwen
* @copyleft Kelluwen, Universidad Austral de Chile
* @license www.kelluwen.cl/app/licencia_kelluwen.txt
* @version 0.1
*
**/
$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz.  "encuestas/inc/en_db_functions.inc.php");

//obtencion de parametros por URL
$id_encuesta = $_REQUEST['id_encuesta'];
//var_dump( $id_encuesta);
//conexiones a las bases de datos
$conexion_ls = dbConectarMySQL($config_host_bd, $config_usuario_bd_ls, $config_password_bd_ls,$config_bd_ls);

//volvamos usuarios seleccionados desde el panel del administrador a la base de datos de LimeSurvey
$existe_encuesta = dbENVerificaEncuesta($id_encuesta, $conexion_ls);

dbDesconectarMySQL($conexion_ls);

echo $existe_encuesta;
?>
