<?php
/**
 * Muestra un enlace a recargar la lista de mensajes de la bitácora si existen 
 * nuevos mensajes en la base de datos. Utiliza la función dbTimeLineMensajesNuevos
 * de acuerdo a los filtros activados en el momento en que se invoca el script. 
 * Los parámetros de filtro son pasados:
 * $_REQUEST["modo"]
 * $_REQUEST["id_mensaje"] 
 * $_REQUEST["codeexp"]
 * $_REQUEST["id_grupo"]
 * $_REQUEST["et_clase_gemela"]
 * $_REQUEST["et_grupo_gemelo"]
 * $_REQUEST["producto"]
 * $_REQUEST["solo_usuario"]
 *         
 * LICENSE: código fuente distribuido con licencia LGPL
 *  
 * @author  Katherine Inalef - Kelluwen
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

$modo = $_REQUEST["modo"];
$id_mensaje = $_REQUEST["id_mensaje"];
$id_experiencia = $_REQUEST["codeexp"];
$usuario = $_REQUEST["solo_usuario"];
$id_diseno = $_REQUEST["id_diseno"];

$conexion               = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$mensajes_nuevos    = dbTimeLineCompartidaMensajesNuevos($conexion, $modo, $id_mensaje, $id_experiencia, $usuario, $id_diseno);
dbDesconectarMySQL($conexion);
if($mensajes_nuevos > 0){
    if($mensajes_nuevos == 1){//singular
        echo "<a href=\"#\" onclick=\"javascript: leerUltimosPostsCompartida(); return false;\"> ".$lang_hay_mensajes_hay.$mensajes_nuevos.$lang_hay_mensajes_s." </a>";
    }
    else {//plural
        echo "<a href=\"#\" onclick=\"javascript: leerUltimosPostsCompartida(); return false;\">".$lang_hay_mensajes_hay.$mensajes_nuevos.$lang_hay_mensajes_p."</a>";
    }    
}else{
    echo "0";
}
?>

