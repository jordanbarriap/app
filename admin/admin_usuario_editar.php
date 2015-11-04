<?php
/**
 * 
 **/
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$id_usuario = $_REQUEST["id_usuario"];
$id_experiencia = $_REQUEST["id_exp"];
$accion = $_REQUEST["accion"];
$nombre = $_REQUEST["nombre"];
$usuario = $_REQUEST["usuario"];
$rol = $_REQUEST["rol"];
$nombre = quitar_espacios_dobles(str_replace ( ".", " ",$nombre));
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$exito =0;

if($accion == 1){ /*Guardar cambios de usuario (nombre, usuario y rol)*/
    $exito =dbAdminEditarUsuario($id_usuario, $nombre, $usuario, $rol, $conexion);
    
}
if($accion == 2){/*Eliminar usuario*/
     $exito = dbAdminEliminarUsuarioPlataforma($id_usuario, $conexion);
}
if($accion == 3){/*Resetear la contraseña de un usuario */
    $exito = dbAdminResetearContrasena($id_usuario, $conexion);
}
echo $exito;
dbDesconectarMySQL($conexion);

?>
