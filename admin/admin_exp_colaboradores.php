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
$nombre_buscar = $_REQUEST["nombre"];
$usuario = $_REQUEST["usuario"];
$nombre = $nombre." ".$apellido;
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$exito =0;
if($accion == 1){ /*Eliminar un colaborador*/
    $exito =dbAdminEliminarColaboradorExperiencia($id_usuario, $id_experiencia, $conexion);
    echo $exito;
}
if($accion == 2){ /*Agregar colaborador*/
    $exito = dbAdminAgregarColaborador($id_usuario, $id_experiencia, $conexion);
     echo $exito;
}
if($accion == 3){ /*Buscar colaborador*/
    
}
dbDesconectarMySQL($conexion);

?>
