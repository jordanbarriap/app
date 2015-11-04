<?php
/**
*Script que activa la base de datos segun los parametros recibidos
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
require_once($ruta_raiz.   "encuestas/inc/en_db_functions.inc.php");

//obtencion de parametros por URL
$id_encuesta = $_REQUEST['id_encuesta'];
$grupo_rol = $_REQUEST['a_grupo'];
$avance = $_REQUEST['avance']; // porcentaje avance de la experiecnia
$anio = $_REQUEST['anio']; //anio comienzo
$anio1 = $_REQUEST['anio1']; // anio hasta
$semestre = $_REQUEST['semestre'];

//arreglo de grupos
$a_grupo = split('-', $grupo_rol);

//conexiones a las bases de datos
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
//volvamos usuarios seleccionados desde el panel del administrador a la base de datos de LimeSurvey
$volcado = dbENVolcarUsuariosAEncuesta($a_grupo,$avance,$anio,$anio1,$semestre, $id_encuesta,$config_host_bd,$config_bd_ls, $config_usuario_bd_ls, $config_password_bd_ls,$conexion);

//dbDesconectarMySQL($conexion);

//funcion para activar la encuesta
$conexion_ls = dbConectarMySQL($config_host_bd, $config_usuario_bd_ls, $config_password_bd_ls,$config_bd_ls);
$activacion = dbENSetEncuesta($id_encuesta,'Y',$grupo_rol,$semestre,$anio,$anio1,$conexion_ls);

dbDesconectarMySQL($conexion_ls);

if($volcado /*&& $activacion*/){
    echo '1';
}
else{
    echo '0';
}


?>
