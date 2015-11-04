<?php
/**
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
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/class.phpmailer.php");
date_default_timezone_set('America/Toronto');

$mostrar_formulario = true;
if (existeSesion()) {
    $mostrar_formulario = false;
}
/* Se ha hecho post */
if (!is_null($_REQUEST["rc_correo"])) {
    $mostrar_formulario = false;
    $error = null;
    $correo = $_REQUEST["rc_correo"];
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $_datos_usuario = dbRecuperarContrasena($correo, $conexion);;
    dbDesconectarMySQL($conexion);

    $mail = new PHPMailer();
    $body = $lang_recupera_registro_body_estimado.$_datos_usuario["nombre"]."<br><br>";
    $body .= $lang_recupera_registro_body_encabezado.": <br>";
    $body .= $lang_recupera_registro_body_estimado_nombre_usuario.$_datos_usuario["usuario"]."<br>";
    $body .= $lang_recupera_registro_body_estimado_contrasena.$_datos_usuario["contrasena"]."<br><br>";
    $body .= $lang_recupera_registro_body_estimado_atentamente.", <br>";
    $body .= $lang_recupera_registro_body_estimado_despedida;

    $mail->From       = "no-contestar@kelluwen.cl";
    $mail->FromName   = "Kelluwen";
    $mail->Subject    = utf8_decode($lang_recupera_registro_subject);
//    $mail->AddBCC("carolinaaros@gmail.com");
//    $mail->AddBCC("katherineinalef@gmail.com");
    $mail->AddReplyTo("no-contestar@kelluwen.cl",$lang_recupera_registro_body_estimado_despedida);

    $mail->AltBody = utf8_decode($body);
    $mail->MsgHTML(utf8_decode($body));
    $mail->AddAddress($correo, $_datos_usuario["nombre"]);
    $mail->IsHTML(true);
    if(!$mail->Send()) {
        echo $lang_recupera_registro_mailer_error.': '. $mail->ErrorInfo;
    }
}
require_once($ruta_raiz."inc/header.inc.php");
if ($mostrar_formulario) {
    ?>
<div class="container_12">
    <div class="grid_2">&nbsp;</div>
    <div class="grid_8">
        <div id="contenido_recupera_registro">
            <div id="titulo_recupera_registro"><?php echo $lang_recupera_registro_titulo;?></div>
            <div id="intro_recupera_registro"><?php echo $lang_recupera_registro_intro;?></div>
            <form id="form_recupera_registro" method="post" action="">
                <div id="caja_form_recupera_registro">
                    <label><?php echo $lang_recupera_registro_correo_electronico.': '; ?></label>
                    <input type="text" maxlenght="20" size="20" id="rc_correo" name="rc_correo" />
                    <div class="clear"></div>
                    <br>
                    <button type="submit" id="btn_recuperar"><?php echo $lang_recupera_registro_enviar;?></button>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
    </div>
    <div class="grid_2">&nbsp;</div>
</div>
    <?php
}
require_once($ruta_raiz."inc/footer.inc.php");
?>
<script type="text/javascript">

    $(document).ready(function(){
        $.validator.addMethod("noCaracteresEspeciales", function(value, element) {
            return this.optional(element) || /^[.|_a-zA-Z|0-9]+$/i.test(value);
        }, "<?php echo $lang_registro_mensaje_caracteres_especiales;?>");

        $.validator.addMethod("noCaracteresEspecialesContrasena", function(value, element) {
            return this.optional(element) || /^[a-zA-Z|0-9]+$/i.test(value);
        }, "<?php echo $lang_registro_mensaje_caracteres_especiales_contrasena;?>");

        $("#form_recupera_registro").validate({
            rules:{
                rc_correo: {
                    required: true,
                    email: true,
                    minlength: 5,
                    remote:"verifica_campo.php"
                }
            },
            messages:{
                rc_correo: {
                    required: "<?php echo $lang_recupera_registro_valida_vacio; ?>",
                    email: "<?php echo $lang_recupera_registro_valida_correo; ?>",
                    minlength: "<?php echo $lang_recupera_registro_valida_largo; ?>",
                    remote: "<?php echo $lang_recupera_registro_valida_correo_bd; ?>"
                }
            },
            submitHandler: function() {
                $("#btn_recuperar").attr("disabled", "true");
                url = 'recuperar_contrasena.php?';
                $.post(url, $("#form_recupera_registro").serialize(), function(data) {
                    $("#rc_correo").html("");
                    $("#rc_correo").val("");
                    if (data != ""){
                        $("#titulo_recupera_registro").html("<div></div>");
                        $("#intro_recupera_registro").html("<div><?php echo $lang_recupera_registro_mensaje_exito;?> <a href=\"ingresar.php\"><?php echo $lang_ingresar;?></a></div>");
                        $("#form_recupera_registro").html("<div></div>");
                    }
                    else{
                        $("#titulo_recupera_registro").html("<div></div>");
                        $("#intro_recupera_registro").html("<div><?php echo $lang_recupera_registro_msg_error;?> </div>");
                        $("#form_recupera_registro").html("<div></div>");
                    }
                });
            }
        });
    });
</script>
