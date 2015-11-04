<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
    
    require_once($ruta_raiz."inc/class.phpmailer.php");
    date_default_timezone_set('America/Toronto');    

    $id_diseno           = $_GET["id_diseno"];
    $id_usuario_destino  = $_GET["id_usuario"];
    $id_usuario_publica  = $_SESSION["klwn_id_usuario"];
    
    $texto_diseno        = $_GET["texto_diseno"];
    
    $mensaje = $lang_tdd_invito_participar_dd." ".$texto_diseno.", ".$lang_tdd_invito_participar_dd_click
        .'<a onClick="aceptaInvitacion('.$id_usuario_destino.','.$id_diseno.');" >'.$lang_tdd_invito_participar_dd_aqui.'</a>';
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_resultado = agregarInvitacionFuncion($id_diseno, $id_usuario_destino, $id_usuario_publica, $mensaje, $conexion);
    
    $_nombre_usuario = obtenerNombreUsuarioFuncion($id_usuario_destino, $conexion);
    $nombre_usuario = $_nombre_usuario[0]['u_nombre'];
    
    $_correo_destino = obtenerCorreoUsuarioFuncion($id_usuario_destino, $conexion);
    $correo_destino = $_correo_destino[0]['u_email'];
    
    $mensaje_correo = $_SESSION["klwn_nombre"]." ".$lang_tdd_invitado_participar_dd." ".$texto_diseno.". ".$lang_tdd_aceptar_invitacion.".";
    
    if($mensaje != '' && $correo_destino != ''){    
                $mail = new PHPMailer();
                $mail->From       = "no-contestar@kelluwen.cl";
                $mail->FromName   = "Kelluwen";
                $mail->Subject = utf8_decode($correo_destino);
                $mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);

                $correo = $correo_destino;

                $body  = $lang_notificacion_muro_body_estimado.$nombre_usuario."<br><br>";
                $body .= $mensaje_correo.", <br>";
                $body .= $lang_notificacion_muro_diseno_body_enlace_kelluwen."<br>";
                $body .= $lang_notificacion_muro_diseno_body_estimado_despedida;

                $mail->AltBody = utf8_decode($body);
                $mail->MsgHTML(utf8_decode($body));
                $mail->AddAddress($correo, $_SESSION["klwn_nombre"]);
                $mail->IsHTML(true);
                if(!$mail->Send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
                $mail= '';

    }
    
    
    dbDesconectarMySQL($conexion);    
?>