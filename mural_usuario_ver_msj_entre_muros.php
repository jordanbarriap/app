<?php
/**
 *
 */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_usuario = $_SESSION["klwn_id_usuario"];
$id_mensaje = $_REQUEST["id_mensaje"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$mensaje = dbMuralUsuarioObtenerMensaje($id_mensaje, $conexion);
$_up_imagenes_usuario = darFormatoImagen($mensaje["url_imagen_usuario_dueno"], $config_ruta_img_perfil, $config_ruta_img);
$up_imagen_propietario = $_up_imagenes_usuario["imagen_usuario"];
$_up_imagenes_usuario_publica = darFormatoImagen($mensaje["url_imagen_usuario_publica"], $config_ruta_img_perfil, $config_ruta_img);
$up_imagen_publica = $_up_imagenes_usuario_publica["imagen_usuario"];
dbDesconectarMySQL($conexion);
?>
<div class = "mu_mensaje_completo_pu">
    <div class="mu_mensaje_pu ">
        <div class="mu_msg_avatar_pu">
            <img src= "<?php echo $up_imagen_publica;?> " >
        </div>
        <div class="mu_msg_texto_pu">
            <p>
                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $mensaje["usuario_publica"];?>" class ="link_nombre" title ="<?php echo $mensaje["usuario_publica"];?>" ><?php echo $mensaje["nombre_usuario_publica"];?></a> <?php echo " ".$lang_mural_usuario_ver_msj_dice; ?>: <?php echo enlazarURLs($mensaje["mensaje"]);?>
            </p>
        </div>        
        <div class="mu_msg_datos_pu">
            <?php echo relativeTime($mensaje["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales);?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
    });
</script>
