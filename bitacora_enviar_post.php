<?php
/**
 * Inserta en la base de datos el mismo
 * mensaje. Utiliza las funciones lbExpEnviarMensaje y 
 * dbInsertarMensajeHistorialBitacora 
 * Los parámetros necesarios pasados son:
 * $_REQUEST["et_exp"] : etiqueta de la experiencia didáctica
 * $_REQUEST["et_exp_gemela"]: etiqueta de la experiencia gemela
 * $_REQUEST["et_grupo"]: etiqueta del grupo  
 * $_REQUEST["et_grupo_gemelo"]: etiqueta de grupo gemelo
 * $_REQUEST["et_producto"] : etiqueta que indica que el mensaje es un producto  
 * $_REQUEST["codeexp"] : código de la experiencia didáctica
 * $_REQUEST["txt_nuevo_post"]: mensaje
 * Los datos del usuario que postea son obtenidos de la sesión  
 * 
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

if(existeSesion()){
    $respuesta=0;
    $etiqueta_experiencia = $_REQUEST["et_exp"];
    $etiqueta_experiencia_gemela = $_REQUEST["et_exp_gemela"];
    $etiqueta_grupo = $_REQUEST["et_grupo"];
    $etiqueta_grupo_gemelo = $_REQUEST["et_grupo_gemelo"];
    $et_producto = $_REQUEST["et_producto"];
    $es_producto = 1;
    if (is_null($et_producto)){
        $et_producto = '';
        $es_producto = 0;
    }
    $codeexp = $_REQUEST["codeexp"];
    $id_grupo = $_REQUEST["id_grupo"];
    $compartido = $_REQUEST["compartido"];
    if (is_null($id_grupo) OR strlen($id_grupo)==0) $id_grupo = '-1';
    $nombre_grupo = '';
    $mensaje = $_REQUEST["txt_nuevo_post"];
    if(!is_null($_REQUEST["txt_url_producto"]) && !is_null($_REQUEST["txt_post"]) ){
        $mensaje = $_REQUEST["txt_url_producto"]." ".$_REQUEST["txt_post"];
    }
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $avance_experiencia = dbExpObtenerAvance($codeexp, $conexion);
    $id_actividad = $avance_experiencia["ultima_actividad_id"];
    $id_exp_actividad = $avance_experiencia["ultima_actividad_id_expact"];
    if(is_null($id_actividad) OR strlen($id_actividad)<1){
        $id_actividad = -1;
        $id_exp_actividad = -1;
    }
    $imagen_perfil = $_SESSION["klwn_foto"]; //Ver esto
    $mensaje_a_guardar = $mensaje;
    //Mensajes compartidos
    if($compartido == 1){
        if (!is_null($mensaje_a_guardar) OR strlen($mensaje_a_guardar)>1) {
            $n = dbInsertarMensajeHistorialBitacora($_SESSION["klwn_nombre"],
                                               $_SESSION["klwn_usuario"],
                                               $imagen_perfil,
                                               $mensaje_a_guardar,
                                               $id_grupo,
                                               $nombre_grupo,
                                               $codeexp,
                                               $id_actividad,
                                               $id_exp_actividad,
                                               $es_producto,
                                               $etiqueta_experiencia_gemela,
                                               $etiqueta_grupo_gemelo,
                                               1,
                                               $conexion);
                echo "1";
            }
            else {
                    echo "0";
            }
    }
    else{ //Mensajes publicados en la Bitácora de una experiencia
   //Mensaje en respuesta a uno existente
    if(!is_null($_REQUEST["en_respuesta_a"])){
        if (!is_null($_REQUEST["textcontent"]) OR strlen($_REQUEST["textcontent"]>1)){
            $mensaje_a_guardar = $_REQUEST["textcontent"];
            //almacenar en la tabla correspondiente
            $en_respuesta_a = $_REQUEST["en_respuesta_a"];
            $n = dbInsertarMensajeRespuesta(   $en_respuesta_a,
                                               $_SESSION["klwn_nombre"],
                                               $_SESSION["klwn_usuario"],
                                               $imagen_perfil,
                                               $mensaje_a_guardar,
                                               $conexion);
            echo $n;
        }
        else {
                echo $n;
        }
    }
    //Mensaje nuevo
    else{
        if (!is_null($mensaje_a_guardar) OR strlen($mensaje_a_guardar)>1) {
            $n = dbInsertarMensajeHistorialBitacora($_SESSION["klwn_nombre"],
                                               $_SESSION["klwn_usuario"],
                                               $imagen_perfil,
                                               $mensaje_a_guardar,
                                               $id_grupo,
                                               $nombre_grupo,
                                               $codeexp,
                                               $id_actividad,
                                               $id_exp_actividad,
                                               $es_producto,
                                               $etiqueta_experiencia_gemela,
                                               $etiqueta_grupo_gemelo,
                                               0,
                                               $conexion);
                echo "1";
            }
            else {
                    echo "0";
            }

        }   
    }
    
    
}
else{
    echo "0";
}
dbDesconectarMySQL($conexion);
?>
