<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$id_usuario = $_REQUEST["id_usuario"];
$id_experiencia = $_REQUEST["id_exp"];
$rol= $_REQUEST["rol"];
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
if(!is_null($id_usuario)&& !is_null($id_experiencia)&& !is_null($rol)){
    $guardar_usuario = dbAdminVincularUsuarioExperiencia($id_usuario, $id_experiencia, $rol, $conexion);
}
echo $guardar_usuario;
dbDesconectarMySQL($conexion);
?>
