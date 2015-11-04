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
require_once($ruta_raiz."inc/class.phpmailer.php");
require_once($ruta_raiz."inc/class.smtp.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");
date_default_timezone_set('America/Toronto');

if(existeSesion()){
    $id_usuario_muro = $_REQUEST["id_usuario_muro"];
    $id_usuario_publica = $_REQUEST["id_usuario_publica"];
    $nombre_usuario_muro = $_REQUEST["usuario_muro"];
    $id_experiencia_mensaje = $_SESSION["id_exp_seleccionada"];
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $datos_usuario_muro = dbObtenerInfoUsuario($nombre_usuario_muro, $conexion);
    $mensaje = $_REQUEST["txt_nuevo_post_muro"];
    $mensaje_modal = $_REQUEST["txt_nuevo_post_muro_modal"];
    if($mensaje_modal){
        $mensaje_a_guardar=$mensaje_modal;
    }
    else{
        $mensaje_a_guardar = $mensaje;
    }
    
    $mail = new PHPMailer();
    $mail->From       = "no-contestar@kelluwen.cl";
    $mail->FromName   = "Kelluwen";
    $mail->SetFrom('no-contestar@kelluwen.cl', $lang_recupera_registro_body_estimado_despedida);
//    $mail->AddBCC("katherineinalef@gmail.com");
    $mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);
    
    if(!is_null($_REQUEST["en_respuesta_a"])){
        if (!is_null($_REQUEST["textcontent"]) OR strlen($_REQUEST["textcontent"]>1)){
            //mandarle la notificacion a todos los usuarios ligados al mensaje
            $mensaje_a_guardar = $_REQUEST["textcontent"];
            $en_respuesta_a = $_REQUEST["en_respuesta_a"];
            $id_usuario_responde = $_SESSION["klwn_id_usuario"];
            $id_usuario_autor = dbRECObtenerIdUsuarioAutorMuralUsuario($en_respuesta_a,$conexion);
            $n = dbMuralUsuarioInsertarMensajeRespuesta($en_respuesta_a,$_SESSION["klwn_nombre"], $id_usuario_autor, $id_usuario_responde, $_SESSION["klwn_usuario"],$imagen_perfil, $mensaje_a_guardar, $conexion);
            $id_mensaje_md = dbMuralUsuarioObtenerIdMensajeMd($en_respuesta_a, $conexion);
            if(!is_null($id_mensaje_md) && $id_mensaje_md!=0){
                 $m = dbMuralDisenoInsertarMensajeRespuesta ($id_mensaje_md, $_SESSION["klwn_nombre"],$id_usuario_autor, $id_usuario_responde, $_SESSION["klwn_usuario"], $imagen_perfil, $mensaje_a_guardar, $conexion);
            }
            if($nivel_notificacion > 1){
                if($n > 0){
                    $_usuarios_corversacion = dbMuralUsuarioObtenerUsuariosConversacion($en_respuesta_a, $conexion);
                    $i = 0;
                    while($_usuarios_corversacion[$i]){
                        //no mandarme una notificacion a mi mismo
                        if($_usuarios_corversacion[$i]["usuario"]!= $_SESSION["klwn_usuario"]){
                            $correo = $_usuarios_corversacion[$i]["email"];
                            $body  = $lang_notificacion_muro_body_estimado.$_usuarios_corversacion[$i]["nombre"]."<br><br>";
                            if($i==0){
                                $body .= $_SESSION["klwn_nombre"]." ".$lang_notificacion_muro_body_prop_encabezado." <br>";
                            }
                            else{
                                $body .= $_SESSION["klwn_nombre"]." ".$lang_notificacion_muro_respuesta_body_encabezado." ".$datos_usuario_muro["nombre"]." ".$lang_notificacion_muro_respuesta_body_encabezado." <br>";
                            }
                            $body .= $lang_notificacion_muro_body_enlace_kelluwen."<br>";
                            $body .= $lang_notificacion_muro_body_estimado_atentamente.", <br>";
                            $body .= $lang_notificacion_muro_body_estimado_despedida;
                            $mail->Subject = utf8_decode($lang_notificacion_muro_subject);
                            $mail->AltBody = utf8_decode($body);
                            $mail->MsgHTML(utf8_decode($body));
                            $mail->AddAddress($correo, $datos_usuario_muro["nombre"]);
                            $mail->IsHTML(true);
                            if(!$mail->Send()) {
                                echo "Mailer Error: " . $mail->ErrorInfo;
                            }
                        }
                        $i++;
                    }
                }
            }
            echo $n;
        }

    }
    //Mensaje nuevo
    else{
        if (!is_null($mensaje_a_guardar) OR strlen($mensaje_a_guardar)>1) {            
            $n = dbMuralUsuarioInsertarMensaje($id_usuario_muro,$id_usuario_publica,$mensaje_a_guardar,$conexion);
            if($n>0){
                echo "1";
            }
            if($id_experiencia_mensaje!= null){
                $id_diseno_d = dbExpObtenerIdDiseno($id_experiencia_mensaje, $conexion);
                $avance = dbExpObtenerAvance($id_experiencia_mensaje, $conexion);
                $id_actividad = $avance["ultima_actividad_id"];
                if($id_usuario_muro == $id_usuario_publica){
                    $tipo_mensaje =1;
                    $m = dbMuralDisenoInsertarMensaje($id_diseno_d, $id_experiencia_mensaje, $id_usuario_publica, $mensaje_a_guardar, $tipo_mensaje,$id_actividad, $conexion, $n);
                }
            }
            if($nivel_notificacion > 0){
                if($id_usuario_muro!= $id_usuario_publica){
                    $correo = $datos_usuario_muro["email"];
                    $body  = $lang_notificacion_muro_body_estimado.$datos_usuario_muro["nombre"]."<br><br>";
                    $body .= $_SESSION["klwn_nombre"]." ".$lang_notificacion_muro_body_encabezado." <br>";
                    $body .= $lang_notificacion_muro_body_enlace_kelluwen."<br>";
                    $body .= $lang_notificacion_muro_body_estimado_atentamente.", <br>";
                    $body .= $lang_notificacion_muro_body_estimado_despedida;
                    $mail->Subject = utf8_decode($lang_notificacion_muro_subject);
                    $mail->AltBody = utf8_decode($body);
                    $mail->MsgHTML(utf8_decode($body));
                    $mail->AddAddress($correo, $datos_usuario_muro["nombre"]);
                    $mail->IsHTML(true);
                    if(!$mail->Send()) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    }
                }

            }

            
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
