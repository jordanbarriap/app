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

$id_diseno = $_REQUEST["id_diseno"];
$accion = $_REQUEST["accion"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$exito =0;

if($accion == 1 || $accion == 0){ 
    $exito = dbAdminPublicarDiseno($id_diseno, $accion, $conexion);
    
}

echo $exito;
dbDesconectarMySQL($conexion);

?>
