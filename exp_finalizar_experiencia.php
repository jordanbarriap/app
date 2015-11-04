<?php
/**
 * Ejecuta la función dbTerminarActividad para marcar una actividad en ejecución
 * como terminada. El script recibe:
 *  codexpact: el id de la ejecución de la actividad (tabla exp_actividad)
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *  
 * @author  Daniel Guerra - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt  
 * @version 0.1
 * 
 **/
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_experiencia = $_REQUEST["codeexp"];
$id_usuario = $_SESSION["klwn_id_usuario"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$id_diseno = dbExpObtenerIdDiseno($id_experiencia, $conexion);
$detalle_dd =dbObtenerDetalleDDidacticos($conexion, $id_diseno);
$nombre_dd = $detalle_dd["nombre_dd"];
$mensaje = $nombre_dd;
$tipo = 3;
$n = dbMuralDisenoInsertarMensaje($id_diseno, $id_experiencia, $id_usuario, $mensaje, $tipo,-1, $conexion);
$fin_experiencia = dbTerminarEjecucionDD($id_experiencia, $conexion);
dbDesconectarMySQL($conexion);
echo $exito;
?>