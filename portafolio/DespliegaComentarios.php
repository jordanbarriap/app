<?php

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "portafolio/inc/por_funciones_db.inc.php");

$id_producto = $_REQUEST["id_producto"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$comentarios = dbPObtenerComentarios($id_producto, $conexion);
$n_comentarios = count($comentarios);
?>
<div id="timeline">
    <div id="rp_titulo_timeline">
                <div id="rp_titulo_lista_comentarios"><?php echo $lang_por_comentarios_realizados;?><span style="font-size:smaller"><?php echo ' ('.$n_comentarios.')'; ?></span></div>
                <div class="clear"></div>
            </div>
    <?php
    if ($comentarios) {
        foreach ($comentarios as $comentario) {

        $_imagen_usuario = darFormatoImagen($comentario["u_url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
        $imagen = $_imagen_usuario["imagen_usuario"];
    ?>
            <div class="mensaje_completo">
                <div class="msg_avatar_rp">
                    <a class="rp_img_integrantes" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $comentario['u_usuario']; ?>">
                        <img src= "<?php echo $imagen; ?>" alt="<?php echo $comentario["u_nombre"]; ?>" title="<?php echo $comentario["u_nombre"]; ?>" />
                    </a>
                </div>
                <div class="msg_texto">
                    <p>
                        <a  id="ventana_perfil<?php echo $comentario["id_comentario"]; ?>" href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $comentario["u_usuario"]; ?>" class ="rp_img_integrantes" title ="<?php echo $nombre['nombre']; ?>" > <?php echo $comentario["u_nombre"]; ?></a> 
                        <?php echo $lang_por_dice.": "; ?>
                        <?php echo enlazarURLs($comentario["contenido"]); ?>
                    </p>
            <div id= "time" class="msg_datos ">
                <div class="fecha">
                    <?php echo relativeTime($comentario['fecha'], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <?php
                }
            }
    ?>
        </div>
        <script type="text/javascript">

            $("#n_comentarios").html("<?php echo $n_comentarios ?>");
            document.rp_form_comentario.txt_nuevo_post.value='';

            $(document).ready(function(){
                $('.rp_img_integrantes').click(function() {
                    var $linkc = $(this);
                    var $dialog = $('<div></div>')
                    .load($linkc.attr('href'))
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_por_perfil_usuario_titulo_ventana; ?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "Cerrar": function() {
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
</script>

