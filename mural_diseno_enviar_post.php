<?php
/**

 * Los parámetros necesarios pasados son:
 * $_REQUEST[""] : etiqueta de la experiencia didáctica
 * $_REQUEST[""]: etiqueta de la experiencia gemela
 * $_REQUEST["codeexp"] : código de la experiencia didáctica
 * $_REQUEST["txt_nuevo_post"]: mensaje
 * Los datos del usuario que postea son obtenidos de la sesión
 *
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
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");
date_default_timezone_set('America/Toronto');

if(existeSesion()){
    $id_diseno      = $_REQUEST["id_diseno"];
    $id_experiencia = $_REQUEST["id_experiencia"];
    $mensaje        = $_REQUEST["txt_nuevo_post_md"];
    $id_actividad   = $_REQUEST["id_actividad"];
    $id_usuario     = $_SESSION["klwn_id_usuario"];
    $conexion       = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $_detalle_dd    = dbObtenerDetalleDDidacticos($conexion, $id_diseno);
    $nombre_dd      = $_detalle_dd["nombre_dd"];
    $notificacion   = false;

    if(!is_null($_REQUEST["en_respuesta_a"])){
        if (!is_null($_REQUEST["textcontent"]) OR strlen($_REQUEST["textcontent"]>1)){       
            $mensaje_a_guardar  = $_REQUEST["textcontent"];
            $en_respuesta_a     = $_REQUEST["en_respuesta_a"];
            $id_usuario_autor = dbRECObtenerIdUsuarioAutorMuralDiseno($en_respuesta_a,$conexion);
            $n = dbMuralDisenoInsertarMensajeRespuesta($en_respuesta_a,$_SESSION["klwn_nombre"], $id_usuario_autor, $id_usuario, $_SESSION["klwn_usuario"],$imagen_perfil, $mensaje_a_guardar, $conexion);
            $id_mensaje_mu = dbMuralDisenoObtenerIdMensajeMu($en_respuesta_a, $conexion);
            if(!is_null($id_mensaje_mu)&& $id_mensaje_mu != 0){
                $m = dbMuralUsuarioInsertarMensajeRespuesta($id_mensaje_mu, $_SESSION["klwn_nombre"],$id_usuario_autor, $id_usuario, $_SESSION["klwn_usuario"], $imagen_perfil, $mensaje_a_guardar, $conexion);
            }
            echo $n;
        }

    }
    //Mensaje nuevo
    else{
        if (!is_null($mensaje) OR strlen($mensaje)>1) {
            $tipo   = 0;
            $n      = dbMuralDisenoInsertarMensaje($id_diseno,$id_experiencia,$id_usuario,$mensaje,$tipo,$id_actividad,$conexion);
            echo $n;
        }
        else {
                echo "0";
        }
    }

}
else{
    echo "0";
}
    dbDesconectarMySQL($conexion);
?>
