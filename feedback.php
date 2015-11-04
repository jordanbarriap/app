<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

require_once($ruta_raiz."inc/class.phpmailer.php");
date_default_timezone_set('America/Toronto');

$nombre = $_REQUEST["fb_nombre"];
$correo = $_REQUEST["fb_correo"];
$mensaje = $_REQUEST["fb_mensaje"];
$id_usuario_sesion = $_SESSION["klwn_usuario"];
$nombre_destino = $lang_feedback_contacto_kellu;

$mail = new PHPMailer();
$mail->From       = "no-contestar@kelluwen.cl";
$mail->FromName   = "Kelluwen";
$mail->Subject = utf8_decode($lang_feedback_ayuda_kellu);
$mail->AddBCC("blana.carlos@gmail.com");
$mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);

$correo_destino = $lang_feedback_contacto."@kelluwen.cl";
$correo_origen = $correo;
$body  = $lang_feedback_msj_enviado_por.": ".$nombre."<br><br>";
$body .= $lang_feedback_correo.": ".$correo_origen." <br>";
$body .= $lang_feedback_mensaje.":"." <br>";
$body .= $mensaje." <br>";
$mail->AltBody = utf8_decode($body);
$mail->MsgHTML(utf8_decode($body));
$mail->AddAddress($correo_destino, $nombre_destino);
$mail->IsHTML(true);
if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
else{
    echo "1";
}
?>
