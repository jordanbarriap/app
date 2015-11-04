<?php
/**
 * Ejecuta la función dbInsertarComentarioExpActividad para insertar un comentario 
 * para una actividad. El script recibe:
 *  pcomact_id_exp_act: codigo (id) de la actividad
 *  pcomact_usuario: el usuario que hace el comentario 
 *  pcomact_nombre_usuario : el nombre del usuario (la información de comentarios 
 *  no tiene vínculo con la tabla usuarios para permitir mantener comentarios de 
 *  usuarios que pudieran eliminarse).
 *  pcomact_texto: el txto del comentario (es filtrado de carcteres extraños y 
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
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");

$id_act     = $_REQUEST["pcomact_id_act"];
$id_exp     = $_REQUEST["id_exp"];
$id_diseno  = $_REQUEST["id_diseno"];
$id_usuario = $_SESSION["klwn_id_usuario"];
$tipo       = '7';
$comentario = substr(filtrarString($_REQUEST["pcomact_texto"]),0,1000);

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$id_mensaje = dbMuralDisenoInsertarMensaje($id_diseno, $id_exp, $id_usuario, $comentario, $tipo, $id_act, $conexion);
dbDesconectarMySQL($conexion);
echo "1";

?>

