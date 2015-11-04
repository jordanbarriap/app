<?php
/**
 * Ejecuta la función dbInsertarComentarioDD para insertar un comentario (testimonio)
 * de un Diseño Didáctico. El script recibe:
 *  pcomdd_id_dd: el id del diseño didáctico
 *  pcomdd_id_exp: el id de la experiencia didáctica
 *  pcomact_usuario: el usuario que hace el comentario 
 *  pcomact_nombre_usuario : el nombre del usuario (la información de comentarios 
 *  no tiene vínculo con la tabla usuarios para permitir mantener comentarios de 
 *  usuarios que pudieran eliminarse).
 *  pcomdd_texto: el texto del comentario (es filtrado de carcteres extraños y 
 *  cortado a 1000 caracteres) 
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

$_comentario = array();

$id_dd = filtrarString($_REQUEST["pcomdd_id_dd"]);
$id_exp = filtrarString($_REQUEST["pcomdd_id_exp"]);
$_comentario["id_dd"] = $id_dd;
$_comentario["id_exp"] = $id_exp;
$_comentario["usuario"] = filtrarString($_REQUEST["pcomdd_usuario"]);
$_comentario["nombre_usuario"] = filtrarString($_REQUEST["pcomdd_nombre_usuario"]);
$_comentario["comentario"] = substr(filtrarString($_REQUEST["pcomdd_texto"]),0,1000);

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$res = dbInsertarComentarioDD($_comentario, $conexion);
dbDesconectarMySQL($conexion);
echo "1";
?>
