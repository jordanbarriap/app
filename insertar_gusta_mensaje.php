<?php
/**
 *
 * Los parámetros necesarios pasados son:
 * $_REQUEST[""] :
 * $_REQUEST[""]:
 *
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

if(existeSesion()){
    $respuesta=0;
    $id_usuario_valora = $_SESSION["klwn_id_usuario"];
    $id_mensaje = $_REQUEST["id_mensaje"];
    $megusta = $_REQUEST["megusta"];
    $origen = $_REQUEST["origen"];/* bitacora = 0, mural usuario = 1, mural diseño = 2 */
    
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    //Mensaje en respuesta a uno existente
    if($megusta == 1){ //"Me gusta"
        if($origen==0){
            $n = dbInsertarMeGustaMensaje($id_mensaje, $id_usuario_valora, $conexion);
        }
        if($origen==1){
            //MURAL Usuario
            $id_md = dbMuralDisenoObtenerIdMensaje($id_mensaje, $conexion);
            echo $id_md;
            $id_usuario_autor = dbRECObtenerIdUsuarioAutorMuralUsuario($id_mensaje,$conexion);
            $n = dbMuralUsuarioInsertarMeGustaMensaje($id_mensaje, $id_usuario_valora, $id_usuario_autor, $conexion);
            if(!is_null($id_md)&& $id_md !=0){
               $m = dbMuralDisenoInsertarMeGustaMensaje($id_md, $id_usuario_valora, $id_usuario_autor, $conexion);
            }          
        }
        if($origen==2){

                $id_mu = dbMuralDisenoObtenerIdMensajeMu($id_mensaje, $conexion);
                echo "id_mu".$id_mu;
                $id_usuario_autor = dbRECObtenerIdUsuarioAutorMuralDiseno($id_mensaje,$conexion);
                $n = dbMuralDisenoInsertarMeGustaMensaje($id_mensaje, $id_usuario_valora, $id_usuario_autor, $conexion);
                if(!is_null($id_mu)&& $id_mu !=0){
                   $m = dbMuralUsuarioInsertarMeGustaMensaje($id_mu, $id_usuario_valora, $id_usuario_autor, $conexion);
               }
        }
    }// Ya no me gusta
    else{
        if($origen==0){
            $n = dbNoGustaMensaje($id_mensaje, $id_usuario_valora, $conexion);
        }
        if($origen==1){
            //MURAL Usuario
            $id_md = dbMuralDisenoObtenerIdMensaje($id_mensaje, $conexion);
            $n = dbMuralUsuarioNoGustaMensaje($id_mensaje, $id_usuario_valora, $conexion);
            if(!is_null($id_md)){
               $m = dbMuralDisenoNoGustaMensaje($id_md, $id_usuario_valora, $conexion);
           }
        }
        if($origen==2){
            //MURAL Diseño
            $id_mu = dbMuralDisenoObtenerIdMensajeMu($id_mensaje, $conexion);
            $n = dbMuralDisenoNoGustaMensaje($id_mensaje, $id_usuario_valora, $conexion);
            if(!is_null($id_md)){
               $m = dbMuralUsuarioNoGustaMensaje($id_md, $id_usuario_valora, $conexion);
           }
        }
    }
    echo $n;
    dbDesconectarMySQL($conexion);
}
else{
    echo "0";
}
?>