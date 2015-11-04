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
    require_once($ruta_raiz . "inc/class.phpmailer.php");
    require_once($ruta_raiz . "inc/class.smtp.php");    
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
    
    require_once($ruta_raiz."inc/class.phpmailer.php");
    date_default_timezone_set('America/Toronto');    

    $id_diseno = $_GET["id_diseno"];
    $texto_diseno = $_GET["texto_diseno"];
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_correos = obtenerCorreosAdminFuncion($conexion);
    $_diseno = obtenerDisenoFuncion($id_diseno, $conexion);
    
    $mensaje_correo = $_SESSION["klwn_nombre"]." dice: ".$texto_diseno." para el diseño: ' ".$_diseno[0]['dd_nombre']." ' .";
    $correo_destino = implode(",", $_correos);

    if($mensaje_correo != '' && $correo_destino != ''){   
            $mail = new PHPMailer();
            $mail->From       = "no-contestar@kelluwen.cl";
            $mail->FromName   = "Kelluwen";
            $mail->Subject = utf8_decode($correo_destino);
            $mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);

            $correo = $correo_destino;

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
    
    dbDesconectarMySQL($conexion); 