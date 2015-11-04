<?php
/**
 * Formulario de insripción de diseño didáctico a través de un código secreto
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author Carolina Aros - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
?>
<div id="contenido_inscribe_experiencia">
    <p><?php echo $lang_fid_inicio; ?></p>
    <form id="form_inscribir_dis" name="form_inscribir_dis" method="post">
        <label class="etiqueta_codigo"><?php echo $lang_dis_inscribir_dis_codigo; ?></label>
        <input tabindex="1" type="text" maxlenght="20" class="caja_texto"  size="15" id="fr_campo_codigo" name="fr_campo_codigo" /><br>
        <input type="submit" class="submit" value="<?php echo $lang_dis_inscribir_dis_enviar; ?>">
    </form>
</div>
<div id="modal_mensaje_fid">
    <p></p>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#modal_mensaje_fid").dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            buttons: {
                '<?php echo $lang_dis_inscribir_dis_aceptar; ?>': function() {
                    $(this).dialog( "close" );
                }
            }
        });
        $("#form_inscribir_dis").validate({
            rules:{
                fr_campo_codigo:{
                    required:true,
                    minlength:8,
                    remote:"verifica_campo.php"
                }
            },
            messages:{
                fr_campo_codigo: {required:"<?php echo $lang_fid_codigo; ?>",
                    minlength:"<?php echo $lang_fid_codigo_largo; ?>",
                    remote:"<?php echo $lang_fid_codigo_existe; ?>"
                }
            },
            submitHandler: function() {
                url = 'ingresa_codigo.php?';

                $.post(url, $("#form_inscribir_dis").serialize(), function(data) {
                    $("#fr_campo_codigo").html("");
                    $("#fr_campo_codigo").val("");
                    data = parseInt(data);
                    if (data == "1"){
                        $("#modal_mensaje_fid").dialog("option", "title", "<?php echo $lang_exito;?>");
                        $("#modal_mensaje_fid p").attr("class","msg_exito");
                        $("#modal_mensaje_fid p").html("<?php echo $lang_fid_exito ?>");
                        $("#modal_mensaje_fid").dialog("open");
                    }
                    else{
                        $("#modal_mensaje_fid").dialog("option", "title", "<?php echo $lang_error;?>");
                        $("#modal_mensaje_fid p").attr("class","msg_error");
                        $("#modal_mensaje_fid p").html("<?php echo $lang_fid_error ?>");
                        $("#modal_mensaje_fid").dialog("open");
                    }
                });    
            }
        });
    });
</script>