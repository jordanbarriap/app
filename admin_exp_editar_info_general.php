<?php
/**
 * 
 **/
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$id_experiencia = $_REQUEST["codeexp"];
$localidad = $_REQUEST["admin_exp_campo_localidad"];
$curso = $_REQUEST["admin_exp_campo_curso"];
$colegio = $_REQUEST["admin_exp_campo_colegio"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$exito =0;
if(!is_null($localidad)){
    $exito = dbAdminEditarInfoExperiencia($id_experiencia, $localidad, null, null, null, null,null, $conexion);
}
if(!is_null($curso)){
    $exito = dbAdminEditarInfoExperiencia($id_experiencia, null, $curso, null, null, null, null, $conexion);
}
if(!is_null($colegio)){
    $exito = dbAdminEditarInfoExperiencia($id_experiencia, null, null, $colegio, null, null, null, $conexion);
}

echo $exito;
dbDesconectarMySQL($conexion);

?>

