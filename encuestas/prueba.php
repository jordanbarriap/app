<?php
$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "encuestas/inc/en_db_functions.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
//$conexion_ls = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd_ls);/
$conexion_ls = mysql_connect($config_host_bd, $config_usuario_bd, $config_password_bd);


$rol[0]=1;
$rol[1]=3;
$rol[2]=2;
$id_encuesta = 59431;
$anio = 2012;
$semestre= 1;

dbENVolcarUsuariosAEncuesta($rol,$anio,$semestre, $id_encuesta,$conexion,$conexion_ls);


?>
