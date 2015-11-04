<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

require_once($ruta_raiz."inc/class.phpmailer.php");
date_default_timezone_set('America/Toronto');

$id_mensaje = $_REQUEST["id_mensaje"];
$id_diseno = $_REQUEST["id_diseno"];
$usuario_sesion = $_SESSION["klwn_usuario"];
$notificacion = false;
if(($nivel_notificacion == 2 && $_REQUEST["id_mensaje"]!= "")||($nivel_notificacion==3)){

    $notificacion = true;
}
if($notificacion){    
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $_detalle_dd =dbObtenerDetalleDDidacticos($conexion, $id_diseno);
    $nombre_dd = $_detalle_dd["nombre_dd"];
    if(!is_null($id_mensaje)){
        $_usuarios_corversacion = dbMuralDisenoObtenerUsuariosConversacion($id_mensaje, $conexion);
        $i = 0;
        while($_usuarios_corversacion[$i]){
            $mail = new PHPMailer();
            $mail->From       = "no-contestar@kelluwen.cl";
            $mail->FromName   = "Kelluwen";
            $mail->Subject = utf8_decode($lang_notificacion_muro_diseno_subject);
            $mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);
            //no mandarme una notificacion a mi mismo
            if($_usuarios_corversacion[$i]["usuario"]!= $usuario_sesion){

                $correo = $_usuarios_corversacion[$i]["email"];
                $body  = $lang_notificacion_muro_body_estimado.$_usuarios_corversacion[$i]["nombre"]."<br><br>";
                if($i==0){
                    $body .= $_SESSION["klwn_nombre"]." ".$lang_notificacion_muro_diseno_body_prop_encabezado.$nombre_dd." <br>";
                }
                else{
                    $body .= $_SESSION["klwn_nombre"]." ".$lang_notificacion_muro_diseno_respuesta_body_encabezado.$nombre_dd." <br>";
                }
                $body .= $lang_notificacion_muro_diseno_body_enlace_kelluwen."<br>";
                $body .= $lang_notificacion_muro_diseno_body_estimado_atentamente.", <br>";
                $body .= $lang_notificacion_muro_diseno_body_estimado_despedida;
                $mail->AltBody = utf8_decode($body);
                $mail->MsgHTML(utf8_decode($body));
                $mail->AddAddress($correo, $datos_usuario_muro["nombre"]);
                $mail->IsHTML(true);
                if(!$mail->Send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
                $mail= '';
            }
            $i++;
        }
    }
    else{       
        $_profesores_participantes = dbMuralDisenoObtenerProfesoresEjecutando($id_diseno, $conexion);
        $_colaboradores_participantes = dbMuralDisenoObtenerColaboradores($id_diseno, $conexion);
        if(!is_null($_colaboradores_participantes)){
             $_participantes = array_merge($_profesores_participantes,$_colaboradores_participantes);
        }
        else{
            $_participantes = $_profesores_participantes;
        }
        $j = 0;
        while($_participantes[$j]){
            
            if($usuario_sesion!= $_participantes[$j]["usuario"]){
                if($j==0){
                    $_correos[$j]= $_participantes[$j]["email"];
                }
                else{
                    if(!in_array($_participantes[$j]["email"], $_correos)){
                        $mail = new PHPMailer();
                        $mail->From       = "no-contestar@kelluwen.cl";
                        $mail->FromName   = "Kelluwen";
                        $mail->Subject = utf8_decode($lang_notificacion_muro_diseno_subject);
                        $mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);
                        $_correos[$j]= $_participantes[$j]["email"];
                        $correo = $_participantes[$j]["email"];
                        $body  = $lang_notificacion_muro_body_estimado.$_participantes[$j]["nombre"]."<br><br>";
                        $body .= $_SESSION["klwn_nombre"]." ".$lang_notificacion_muro_diseno_body_encabezado_dd.$nombre_dd." <br>";
                        $body .= $lang_notificacion_muro_diseno_body_enlace_kelluwen."<br>";
                        $body .= $lang_notificacion_muro_diseno_body_estimado_atentamente.", <br>";
                        $body .= $lang_notificacion_muro_diseno_body_estimado_despedida;
                        $mail->AltBody = utf8_decode($body);
                        $mail->MsgHTML(utf8_decode($body));
                        $mail->AddAddress($correo, $_datos_usuario_muro_profesores_participantes[$j]["nombre"]);
                        $mail->IsHTML(true);
                        if(!$mail->Send()) {
                            echo "Mailer Error: " . $mail->ErrorInfo;
                        }
                        $mail= '';
                        
                    }
                } 
            }
            $j++;
        }
    }
}
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
</script>