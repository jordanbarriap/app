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
$origen_consulta = $_REQUEST["origen"]; /* bitacora = 0, mural usuario = 1, mural diseño = 2 */
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
if($origen_consulta == 0){
    $_usuario_gusta_mensaje = dbObtenerMeGustaMensaje($id_mensaje, $conexion);
}
if($origen_consulta == 1){
    $_usuario_gusta_mensaje = dbMuralUsuarioObtenerMeGustaMensaje($id_mensaje, $conexion);
}
if($origen_consulta == 2){
    $_usuario_gusta_mensaje = dbMuralDisenoObtenerMeGustaMensaje($id_mensaje, $conexion);
}
$num_usuario_gm = count($_usuario_gusta_mensaje);
?>

<table class="t_usuarios_gustamsj" id="tabla_resumen">
    <thead>
        <tr>
            <td class="col1_gustamsj"><?php echo $lang_usuarios_gusta_msj_nombre;?> </td>
            <td class="col2_gustamsj"><?php echo $lang_usuarios_gusta_msj_establecimiento;?></td>
            <td class="col3_gustamsj"><?php echo $lang_usuarios_gusta_msj_comuna;?> </td>
        </tr>
    </thead>
       <tbody>
<?php
$i=0;
while ($_usuario_gusta_mensaje[$i]){
    $imagen_usuario = darFormatoImagen($_usuario_gusta_mensaje[$i]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
    if($i <3){        
    ?>
        <tr>
            <td class="col1_gustamsj">
                <div>
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo$_usuario_gusta_mensaje[$i]["usuario"];?>" class="img_integrantes_ug" title="<?php echo $_usuario_gusta_mensaje[$i]["usuario"];?>">
                        <img src="<?php echo $imagen_usuario["imagen_usuario"];?>" />
                    </a>
                </div>
                <div class="nombre_usuario_gustamsj">
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo$_usuario_gusta_mensaje[$i]["usuario"];?>" class="img_integrantes_ug" title="<?php echo $_usuario_gusta_mensaje[$i]["usuario"];?>">
                        <?php echo ucwords(utf8_strtolower($_usuario_gusta_mensaje[$i]["nombre"]));?>
                    </a>
                </div>
            </td>
            <td class="col2_gustamsj">
                <p><?php echo ucwords(utf8_strtolower($_usuario_gusta_mensaje[$i]["establecimiento"]));?></p>
            </td>
            <td class="col3_gustamsj">
                <p><?php echo ucwords(utf8_strtolower($_usuario_gusta_mensaje[$i]["localidad"]));?></p>
            </td>
        </tr>
    <?php

    }
    else{
        ?>
        <tr class="tabla_completa">
            <td class="col1_gustamsj">
                <div>
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo$_usuario_gusta_mensaje[$i]["usuario"];?>" class="img_integrantes_ug" title="<?php echo $_usuario_gusta_mensaje[$i]["usuario"];?>">
                        <img src="<?php echo $imagen_usuario["imagen_usuario"];?>" />
                    </a>
                </div>
                <div class="nombre_usuario_gustamsj">
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo$_usuario_gusta_mensaje[$i]["usuario"];?>" class="img_integrantes_ug" title="<?php echo $_usuario_gusta_mensaje[$i]["usuario"];?>">
                        <?php echo ucwords(utf8_strtolower($_usuario_gusta_mensaje[$i]["nombre"]));?>
                    </a>
                </div>
            </td>
            <td class="col2_gustamsj">
                <?php echo ucwords(utf8_strtolower($_usuario_gusta_mensaje[$i]["establecimiento"]));?>
            </td>
            <td class="col3_gustamsj">
                <?php echo ucwords(utf8_strtolower($_usuario_gusta_mensaje[$i]["localidad"]));?>
            </td>
        </tr>
      <?php  
    }
    $i++;
}
?>
    </tbody>
</table>
<?php
if($num_usuario_gm > 3){
?>
<div class="ver_mas_gm">
    <a id="ver_mas_usuarios_gm"><?php echo $lang_usuarios_gusta_msj_ver_todos; ?></a>
</div>

<?php
}
?>
<script type="text/javascript">

    $(document).ready(function(){
        $(".tabla_completa").fadeOut(1);
        $("#ver_mas_usuarios_gm").click(function (){
            $(".tabla_completa").fadeIn(500);
            $("#ver_mas_usuarios_gm").hide();
        });
        $('.img_integrantes_ug').click(function() {
                var $linkc = $(this);
                var $dialog = $('<div></div>')
                .load($linkc.attr('href'))
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_perfil_usuario_titulo_ventana;?>',
                    width: 800,
                    height: 600,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_usuario_gusta_msj_cerrar; ?>": function() {
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