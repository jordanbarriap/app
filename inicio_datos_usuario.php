<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$datos = dbObtenerInfoUsuario($_SESSION["klwn_usuario"], $conexion);
dbDesconectarMySQL($conexion);
$_imagen = darFormatoImagen($datos["imagen"], $config_ruta_img_perfil, $config_ruta_img);
$imagen_normal = $_imagen["imagen_usuario"];
$imagen_grande = $_imagen["imagen_grande"];
actualizarSesion($datos["nombre"],$datos["imagen"], $datos["email"], $datos["mostrar_correo"], $datos["mostrar_fecha"]);
?>
<div class="inicio_img">
    <img alt="<?php echo $perfil_usuario;?>" src="<?php echo $imagen_normal.'?'.rand(1,999999);?>" height="50"/>
</div>
<div class="inicio_datos">
    <?php echo $datos["nombre"];?> <br/>
    <button id="inicio_editar_perfil"><?php echo $lang_inicio_datos_usuario_editar_perfil; ?></button>
</div> 
<script type="text/javascript">
$(document).ready(function(){
        $('#inicio_editar_perfil').click(function(){
            $('#inicio_edit_perfil').parents().addClass('selected');
            $('#inicio_mis_exp').parents().removeClass('selected');
            $('#inicio_mis_exp_fin').parents().removeClass('selected');
            $('#inicio_mi_muro').parents().removeClass('selected');
            $('#inicio_ini_exp').parents().removeClass('selected');
            $.get('modificar_perfil.php', function(data) {
              $('.inicio_bloque_central').html(data);
            });
        });    
    });
</script>