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
    $tipo = $_REQUEST["tipo"];
    $id_mensaje = $_REQUEST["id_mensaje"];
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    //Eliminar un mensaje de la tabla bt_historial_mensajes y todos las respuestas relacionadas y "me gusta"
    if($tipo==1){
        $elimina = dbBitacoraEliminarMensaje($id_mensaje, $conexion);
        echo $elimina;
    }
    //Eliminar un mensaje en respuesta
    else{
        if ($tipo==2) {
            $elimina = dbBitacoraEliminarRespuestaMensaje($id_mensaje, $conexion);
            echo $elimina;
        }   
    
    }
}
else{
    echo "0";
}
dbDesconectarMySQL($conexion);
?>

