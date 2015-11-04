<?php
/**
 * Despliegue del calendario
 *
 * @author  Katherine Inalef - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 **/

//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
//require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$mes=$_GET['month'];
$anio=$_GET['year'];
$dia=1; //primer dia del mes
calendar($mes,$anio,$calendario_dias);
?>
