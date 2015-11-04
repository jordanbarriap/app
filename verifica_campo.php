<?php

/**
 * Hace verificaciones a la base de datos sobre la existencia de campos utilizados en
 * diversos formularios
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Carolina Aros - Kelluwen
 *          Katherine Inalef - Kelluwen
 *
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");

$usuario = $_REQUEST['fr_campo_nombre_usuario'];
$correo = $_REQUEST['fr_campo_correo'];
$codigo = $_REQUEST['fr_campo_codigo'];
$codigo_gem = $_REQUEST['fr_campo_codigo_gem'];
$rc_correo = $_REQUEST['rc_correo'];
$modificar_correo = $_REQUEST['fmp_campo_modificar_correo'];
$contrasena = $_REQUEST['fmp_campo_modificar_contrasena_antigua'];
$correo_admin = $_REQUEST['admin_fr_campo_correo'];
$usuario_admin = $_REQUEST['admin_fr_campo_nombre_usuario'];

$validacion = 'true';
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

if (isset($usuario)) {
    if (dbValidaNombreUsuario($usuario, $conexion) >= 1) {
        $validacion = 'false';
    }
} elseif (isset($correo)) {
    if (dbValidaCorreoUsuario($correo, $conexion) >= 1) {
        $validacion = 'false';
    }
}elseif (isset($usuario_admin)) {
    if (dbValidaNombreUsuario($usuario_admin, $conexion) >= 1) {
        $validacion = 'false';
    }
}elseif (isset($correo_admin)) {
    if (dbValidaCorreoUsuario($correo_admin, $conexion) >= 1) {
        $validacion = 'false';
    }
}elseif (isset($codigo)) {
    $validacion = 'false';
    if (dbValidaCodigoSecreto($codigo, $conexion) == 1) {
        $validacion = 'true';
    }
}
    elseif (isset($codigo_gem)) {
    $validacion = 'false';
    if (dbValidaCodigoGemelo($codigo_gem, $conexion) >= 1) {
        $validacion = 'true';
    }
} elseif (isset($contrasena)) {
    $validacion = 'false';
    $nombre_usuario = $_SESSION["klwn_usuario"];
    if (dbValidaContrasenaUsuario($nombre_usuario, $contrasena, $conexion) == 1) {
        $validacion = 'true';
    }
} elseif (isset($rc_correo)) {
    if (dbValidaCorreoUsuario($rc_correo, $conexion) != 1) {
        $validacion = 'false';
    }
} elseif (isset($modificar_correo)) {
    $nombre_usuario = $_SESSION["klwn_usuario"];
    $usuario = dbObtenerInfoUsuario($nombre_usuario, $conexion);
    if ($usuario["email"] != $modificar_correo) {
        if (dbValidaCorreoUsuario($modificar_correo, $conexion) >= 1) {
            $validacion = 'false';
        }
    }
}
echo $validacion;
dbDesconectarMySQL($conexion);
?>