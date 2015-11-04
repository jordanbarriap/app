<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
$id_mensaje_original = $_REQUEST["id_mensaje"];
$usuario_sesion         = $_SESSION["klwn_usuario"];
$conexion               = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_mensajes_en_respuesta = dbMuralUsuarioObtenerMensajesEnRespuesta($id_mensaje_original, $conexion);
dbDesconectarMySQL($conexion);
?>
<ul class="mu_listado_mensajes_respuesta" >
    <?php
    $j=0;
    while($_mensajes_en_respuesta[$j]){
        $_imagenes_usuario = darFormatoImagen($_mensajes_en_respuesta[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
        ?>
        <li class="mu_listado_respuestas">
            <div  class="mu_respuesta_mensaje">
                <div class="mu_respuesta_msg_avatar">
                    <img class="mu_imagen_respuesta" src= "<?php echo $_imagenes_usuario["imagen_usuario"] ;?> "/>
                </div>
                <div  class="mu_respuesta_msg_texto">
                    <p>
                        <?php
                        if($usuario_sesion == $_mensajes_en_respuesta[$j]["usuario"]){
                        ?>
                        <b><?php echo $_mensajes_en_respuesta[$j]["nombre"];?></b> <?php echo $lang_mural_usuario_resp_msj_dice; ?>:
                        <?php
                        }
                        else{
                        ?>
                        <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes_en_respuesta[$j]["usuario"];?>" class ="link_nombre" title ="<?php echo $_mensajes_en_respuesta[$j]["usuario"];?>" ><?php echo $_mensajes_en_respuesta[$j]["nombre"];?></a> <?php echo $lang_mural_usuario_dice.': ';?>
                        <?php
                        }
                        ?>
                        <?php echo enlazarURLs($_mensajes_en_respuesta[$j]["mensaje"]);?>
                    </p>
                    <div id= "mu_time" class="mu_respuesta_msg_datos">
                    <?php echo relativeTime($_mensajes_en_respuesta[$j]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
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
                        "<?php echo $lang_mural_usuario_resp_msj_cerrar; ?>": function() {
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

