<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_mensaje_original        = $_REQUEST["id_mensaje"];
$conexion                   = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_md_mensajes_en_respuesta  = dbMuralDisenoObtenerMensajesEnRespuesta($id_mensaje_original, $conexion);
dbDesconectarMySQL($conexion);
?>
<ul class="md_listado_mensajes_respuesta" >
    <?php
    $j=0;
    while($_md_mensajes_en_respuesta[$j]){
        $_md_imagenes_usuario = darFormatoImagen($_md_mensajes_en_respuesta[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
        ?>
        <li class="md_listado_respuestas">
            <div  class="md_respuesta_mensaje">
                <div class="md_respuesta_msg_avatar">
                    <img class="md_imagen_respuesta" src= "<?php echo $_md_imagenes_usuario["imagen_usuario"] ;?> "/>
                </div>
                <div  class="md_respuesta_msg_texto">
                    <p>
                        <a href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $_md_mensajes_en_respuesta[$j]["usuario"];?>" class ="link_nombre" title ="<?php echo $_md_mensajes_en_respuesta[$j]["usuario"];?>" ><?php echo $_md_mensajes_en_respuesta[$j]["nombre"];?></a> <?php echo $lang_mural_diseno_dice.': ';?>
                        <?php echo enlazarURLs($_md_mensajes_en_respuesta[$j]["mensaje"]);?>
                    </p>
                    <div id= "md_time" class="md_respuesta_msg_datos">
                    <?php echo relativeTime($_md_mensajes_en_respuesta[$j]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
                    </div>
                 </div>
            </div>
        </li>
        <?php
        $j++;
    }
    ?>
</ul>
<script type="text/javascript">
$(document).ready(function(){
    $('.link_nombre').each(function() {
            var $linkc = $(this);
             $linkc.click(function() {
                var $dialog = $('<div></div>')
                .load($linkc.attr('href'))
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_perfil_usuario_titulo_ventana;?>',
                    width: 800,
                    height: 600,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_mural_dis_resp_msj_cerrar; ?>": function() {
                        $(this).dialog("close");
                        }
                    },
                    close: function(ev, ui) {
                        $(this).remove();
                    }
                    });
                $dialog.dialog('open');
                return false;
            });
    });

});
</script>
