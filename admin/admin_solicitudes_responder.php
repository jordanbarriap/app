<?php

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_solicitud = $_REQUEST["id_solicitud"];
$estado = $_REQUEST["estado"];
$accion = $_REQUEST["accion"];
$id_colaborador = $_REQUEST["id_colaborador"];
$id_experiencia = $_REQUEST["codeexp"];
$nombre_admin = $_SESSION["klwn_nombre"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$exito = dbAdminResponderSolicitud($id_solicitud, $estado, $accion, $id_colaborador, $id_experiencia, $nombre_admin, $conexion);
echo $exito;
dbDesconectarMySQL($conexion);
?>
