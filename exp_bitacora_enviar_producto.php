<?php
/*
 * Se define el formulario para el envío del producto y se hacen las validaciones correspondientes
 * boton enviar y textarea de mensaje desactivados hasta que se ingrese la url, recalcular el largo max del mensaje
 * segun el largo de la url ingresada
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Katherine Inalef - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 */

//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
?>
<div id="ventana_envio_producto">
    <form id="form_posteo_producto" action="">
        <div id="caja_texto">
            <label><?php echo $lang_url_producto." :";?> </label>
            <input id="txt_url_producto_id" name="txt_url_producto"/>
            <p id="error_url"></p>
            <label><?php echo $lang_mensaje_producto." :";?> </label>
            <textarea id="txt_post_id" name="txt_post" cols="25" rows="6"></textarea>
            <p id="error_mensaje"></p>
        </div>
        <div class="clear"></div>
        <div class="opciones_mensaje">
        <div id="caracteres_restantes_producto"><span id="n_caracteres_restantes_producto"><?php echo $config_char_disponibles;?></span><?php echo " ".$lang_caracteres_restantes.".";?></div>
        <div class="clear"></div>
        <div id="enviar_mensaje">
            <button id="boton_producto" onclick="javascript: enviarProducto(); return false;"><?php echo $lang_boton_enviar_mensaje;?></button>
        </div>
        <div class="clear"></div>
        </div>
    </form>
</div>
<script type="text/javascript">
     function validaURL(url){
        var re1 = /^www.[a-zA-Z0-9-. ]+\..+$/;
        var re2 = /^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,3}|info|mobi|aero|asia|name)(:\d{2,5})?(\/)?((\/).+)?$/i;
        var modo1 =  re1.test(url);
        var modo2 = re2.test(url);
        if(modo1 || modo2){
            return true;
        }
        else{
            return false;
        }
     }
    function validaCampoURL(){
         if($("#txt_url_producto_id").val().length == 0 || !validaURL($('#txt_url_producto_id').val()) ){
             $("#error_url").text('<?php echo $lang_fallo_url_producto;?>');
             $("#error_url").show();
             return false;
            }
            else{
                $("#error_url").hide();
                return true;
            }
     }
     function validaEnvioProducto(){
        var largo_mensaje = $('#txt_post_id').val().length;
        var largo_url = $('#txt_url_producto_id').val().length;
        var car_disponibles = <?php echo $config_char_disponibles;?>;
        var car_restantes = car_disponibles - largo_mensaje -largo_url;
        $('#n_caracteres_restantes_producto').html(car_restantes);
            if ( !validaCampoURL()||(largo_mensaje > car_disponibles) || (largo_mensaje < 1) ){
               if((largo_mensaje < 1)){
                  $("#error_mensaje").text('<?php echo $lang_fallo_producto_mensaje;?>');
                  $("#error_mensaje").show();
              }
              $('#boton_producto').attr('disabled', true);
            }else{
                $("#error_mensaje").hide();
                $('#boton_producto').attr('disabled', false);
            }
     
     }
     $(document).ready(function(){
         $("#error_url").hide();
         $("#error_mensaje").hide();
         $('#boton_producto').attr('disabled', true);
         $('#txt_post_id').keyup(validaEnvioProducto);
         $('#txt_url_producto_id').keyup(validaEnvioProducto);
         $('#txt_post_id').blur(validaEnvioProducto);
         $('#txt_url_producto_id').blur(validaEnvioProducto);

     });
</script>

