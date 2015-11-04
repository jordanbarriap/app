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
    

    $id_actividad        = $_POST["dc_id_comentario_act"];
    $id_usuario_publica  = $_SESSION["klwn_id_usuario"];
    $mensaje             = $_POST["dc_texto_comentario_act"];
    $tipo                = $_POST['dc_tipo_comentario_act'];

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_resultado = agregarComentarioFuncion($id_actividad, $id_usuario_publica, $mensaje, $tipo, $conexion);
    
    if($_resultado){
        $_diseno = obtenerDisenoActividadFuncion($id_actividad, $conexion);
        if(isset($_diseno[0]['dd_id_diseno_didactico'])){
            $_correos = obtenerCorreosColabFuncion($_diseno[0]['dd_id_diseno_didactico'], $conexion);
            $_usuario = obtenerNombreUsuarioFuncion($id_usuario_publica, $conexion);

            $correo_destino = '';
            $correo_destino = implode(",", $_correos);
            if(count($_correos >0) && isset($_diseno[0]['dd_nombre']) && isset($_usuario[0]['u_nombre'])){
                $mensaje_correo = $lang_tdd_env_com_act_actividad.' '.$_diseno[0]['ac_nombre'].' '.$lang_tdd_env_com_act_diseño.' '.
                                  $_diseno[0]['dd_nombre'].' '.$lang_tdd_env_com_act_usuario.' '.
                                  $_usuario[0]['u_nombre'].' '.$lang_tdd_env_com_act_comento.': "'.$mensaje.'"';

                if($mensaje_correo != '' && $correo_destino != ''){   
                        $mail = new PHPMailer();
                        $mail->From       = "no-contestar@kelluwen.cl";
                        $mail->FromName   = "Kelluwen";
                        $mail->Subject = $lang_tdd_env_com_act_nuevo_comentario;
                        $mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);

                        $correo = $correo_destino;
                        $body = '';
                        $body .= $mensaje_correo;

                        $mail->AltBody = utf8_decode($body);
                        $mail->MsgHTML(utf8_decode($body));
                        for($c=0; $c<count($_correos); $c++){
                            $mail->AddAddress($_correos[$c]);
                        }
                        $mail->IsHTML(true);
                        if(!$mail->Send()) {
                            echo "false";
                        }else echo 'true';
                        $mail= '';

                }
            }
        }
    }    
    
    
    
    dbDesconectarMySQL($conexion);

?>