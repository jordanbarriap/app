<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])
    )header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

//$rol = $_SESSION["klwn_inscribe_diseno"];
$tipo= $_REQUEST["tipo"];
$id_usuario = $_SESSION["klwn_id_usuario"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$class_vacio = 0;

if ($tipo == 1) {
    $_ultimos_mensajes = dbMuralDisenoUltimosMensajes($id_usuario, $conexion);
    $class_modal = "inicio_link_perfil_muro"; 
    $id= "muro";
} else {
    $_ultimos_mensajes = dbBitacoraUltimosMensajes($id_usuario, $conexion);
    $class_modal = "inicio_link_perfil_bitacora";
    $id = "bitacora";
}
if(is_null($_ultimos_mensajes)){
    $class_vacio = 1;
}
$i = 0;
?>
<div id="scrollbar_<?php echo $id;?>" >
    <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
    <div class="viewport">
        <div class="overview">
            <?php
            while ($_ultimos_mensajes[$i]) {
                $_up_imagenes_usuario = darFormatoImagen($_ultimos_mensajes[$i]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
                $up_imagen = $_up_imagenes_usuario["imagen_usuario"];
            ?>
                <div class="inicio_lu_md_mensaje ">
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_ultimos_mensajes[$i]["usuario"]; ?>" class ="<?php echo $class_modal;?>" title ="<?php echo $_ultimos_mensajes[$i]["usuario"]; ?>" >
                        <div class="inicio_lu_avatar">
                            <img class="inicio_lu_img"src= "<?php echo $up_imagen; ?> " >
                        </div>
                    </a>
                    
                    <div class="inicio_lu_texto">
                        <p>
                            <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_ultimos_mensajes[$i]["usuario"]; ?>" class ="<?php echo $class_modal;?>" title ="<?php echo $_ultimos_mensajes[$i]["usuario"]; ?>" >
                                <?php echo $_ultimos_mensajes[$i]["nombre"];?>
                            </a>
                            <?php echo " : " . enlazarURLs($_ultimos_mensajes[$i]["mensaje"]);?>
                        </p>
                        <div class="md_msg_datos">
                        <?php echo relativeTime($_ultimos_mensajes[$i]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
                    </div>
                </div>
                <div class="clear"></div>
                </div>
            <?php
            $i++;
            }
            ?>
            <div class="clear"></div>
        </div>
    </div>
</div>
<?php
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    $(document).ready(function(){
        
        <?php 
        if($class_vacio!=1){
            if($tipo == 1){
            ?>
                $('.bloque_lo_ultimo_muro').show();
            <?php
            }
            else{
            ?>
                $('.bloque_lo_ultimo_bitacora').show();
            <?php
                
            }
        }
        
        ?>
            $('#scrollbar_<?php echo $id;?>').tinyscrollbar();
        $('.<?php echo $class_modal;?>').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_inicio_lo_ultimo_perfil_usuario; ?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_inicio_lo_ultimo_cerrar; ?>": function() {
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

