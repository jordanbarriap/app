<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$gm_origen = $_REQUEST["origen"]; // 0: Bitácora, 1: Muro diseño, 2: Muro usuario
$gm_id_mensaje = $_REQUEST["id_mensaje"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);


if($gm_origen == 0){
    $usuario_gusta_mensaje = dbUsuarioGustaMensaje($_SESSION["klwn_id_usuario"], $gm_id_mensaje, $conexion);
    $_valoracion_mensaje = dbObtenerMeGustaMensaje($gm_id_mensaje, $conexion);
    $num_valoraciones = count($_valoracion_mensaje);
?>
    <div class ="megusta_mensaje">
    <?php
    if($usuario_gusta_mensaje > 0){
        echo "      <button class =\"boton_comentar\" id =\"no_gusta".$gm_id_mensaje."\" >".$lang_usuarios_gusta_msj_no."</button>";
    }
    else{
        echo "      <button class =\"boton_comentar\" id =\"me_gusta".$gm_id_mensaje."\" >".$lang_usuarios_gusta_msj."</button> ";
    }
    ?>
      </div>
    <?php
    if($num_valoraciones>0){
    ?>
        <div class ="ver_megusta">
            <a class ="boton_ver_megusta" id ="usuarios_gusta<?php echo $gm_id_mensaje;?>" href= "usuarios_gusta_mensaje.php?">
                <img src="<?php echo $config_ruta_img;?>me_gusta.jpg" title ="<?php echo $lang_num_megusta;?>" alt= <?php echo $lang_num_megusta;?>/>
            </a>
            <span><?php echo $num_valoraciones;?></span>
        </div>
    <?php
    }
}
else if($gm_origen == 1){
    $usuario_gusta_mensaje = dbMuralDisenoGustaMensaje($_SESSION["klwn_id_usuario"], $gm_id_mensaje, $conexion);
    $_valoracion_mensaje = dbMuralDisenoObtenerMeGustaMensaje($gm_id_mensaje, $conexion);
    $num_valoraciones = count($_valoracion_mensaje);
    ?>
    <div class ="md_megusta_mensaje">
    <?php
    if($usuario_gusta_mensaje<1){
        echo "<button class = \"md_boton\" id = \"md_gusta".$gm_id_mensaje."\" > ".$lang_gusta_msj_mg." </button> ";
    }
    else{
        echo "<button class = \"md_boton\" id = \"md_nogusta".$gm_id_mensaje."\" > ".$lang_gusta_msj_ya_no_mg." </button> ";
    }
    ?>
    <?php
    if($num_valoraciones>0){
    ?>
    <div class ="md_ver_megusta">
        <a class ="boton_ver_megusta" id ="md_usuarios_gusta<?php echo $gm_id_mensaje;?>" href= "usuarios_gusta_mensaje.php?">
          <img src="<?php echo $config_ruta_img;?>me_gusta_dm.png" title ="<?php echo $lang_num_megusta;?>" alt= <?php echo $lang_num_megusta;?>/>
        </a>
        <span><?php echo $num_valoraciones;?></span>
    </div>
    <?php
    }
    ?>
    </div>
<?php
}
else if($gm_origen == 2){
     $usuario_gusta_mensaje = dbMuralUsuarioGustaMensaje($_SESSION["klwn_id_usuario"], $gm_id_mensaje, $conexion);
    $_valoracion_mensaje = dbMuralUsuarioObtenerMeGustaMensaje($gm_id_mensaje, $conexion);
    $num_valoraciones = count($_valoracion_mensaje);
    ?>
        <div class ="mu_megusta_mensaje">
        <?php
        if($usuario_gusta_mensaje<1){
            echo "<button  class = \"mu_megusta_msj_boton\" id = \"mu_gusta".$gm_id_mensaje."\" > ".$lang_gusta_msj_mg." </button> ";
        }
        else{
            echo "<button class = \"mu_megusta_msj_boton\" id = \"mu_nogusta".$gm_id_mensaje."\" > ".$lang_gusta_msj_ya_no_mg." </button> ";
        }
        ?>
        </div>
        <?php
        if($num_valoraciones>0){
        ?>
        <div class ="mu_ver_megusta">
            <a class ="boton_ver_megusta" id ="mu_usuarios_gusta<?php echo $gm_id_mensaje;?>" href= "usuarios_gusta_mensaje.php?">
              <img src="<?php echo $config_ruta_img;?>me_gusta_dm.png" title ="<?php echo $lang_num_megusta;?>" alt= <?php echo $lang_num_megusta;?>/>
            </a>
            <span><?php echo $num_valoraciones;?></span>
        </div>
        <?php
        }
}
dbDesconectarMySQL($conexion);
?>


<script type="text/javascript">
    $(document).ready(function(){
        $('#md_gusta<?php echo $gm_id_mensaje; ?>').click(function(){
            id_mensaje_valoracion = <?php echo $gm_id_mensaje; ?>;
            url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=1&origen=2';
            $.get(url_valoracion, function() {
                mdMeGustaMensaje(<?php echo $gm_id_mensaje; ?>);
            });            
        });
        $('#md_nogusta<?php echo $gm_id_mensaje; ?>').click(function(){
            id_mensaje_valoracion = <?php echo $gm_id_mensaje; ?>;
            url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=0&origen=2';
            $.get(url_valoracion, function() {
                mdMeGustaMensaje(<?php echo $gm_id_mensaje; ?>);
            });
        });
        $('#md_usuarios_gusta<?php echo $gm_id_mensaje; ?>').each(function() {
            var $linkc = $(this);
             $linkc.click(function() {
                var $dialog = $('<div></div>')
                .load($linkc.attr('href')+'id_mensaje=<?php echo $gm_id_mensaje; ?>'+'&origen=2')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_usuarios_gusta_msj_titulo_ventana;?>',
                    width: 600,
                    height: 400,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_gusta_msj_cerrar; ?>": function() {
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
        $('#me_gusta<?php echo $gm_id_mensaje; ?>').click(function(){
            id_mensaje_valoracion = <?php echo $gm_id_mensaje; ?>;
            url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=1&origen=0';
            $.get(url_valoracion, function() {
                btMeGustaMensaje(<?php echo $gm_id_mensaje; ?>);
                //Código agregado por Jordan Barría el 12-11-14
                var id_exp="<?php echo $_SESSION['id_exp_seleccionada'];?>";
                var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";
                var id_sesion="<?php echo $_SESSION['id_sesion'];?>";//Código agregado por Jordan Barría el 13-12-14
                var tipo_bitacora="";
                if ($('#link_recarga_timeline').is(":visible")){
                    tipo_bitacora="Clase";
                }else{
                    if($('#link_recarga_timeline_compartida').is(":visible")){
                        tipo_bitacora="Compartida";
                    }
                }
                //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);
                enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);//Código agregado por Jordan Barría el 13-12-14
                //Fin código agregado por Jordan Barría el 12-11-14
            });

        });
        $('#no_gusta<?php echo $gm_id_mensaje; ?>').click(function(){
            id_mensaje_valoracion = <?php echo $gm_id_mensaje; ?>;
            url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=0&origen=0';
            $.get(url_valoracion, function() {
                btMeGustaMensaje(<?php echo $gm_id_mensaje; ?>);
                //Código agregado por Jordan Barría el 12-11-14
                var id_exp="<?php echo $_SESSION['id_exp_seleccionada'];?>";
                var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";
                var id_sesion= "<?php echo $_SESSION['id_sesion'];?>";//Código agregado por Jordan Barría el 13-12-14
                var tipo_bitacora="";
                if ($('#link_recarga_timeline').is(":visible")){
                    tipo_bitacora="Clase";
                }else{
                    if($('#link_recarga_timeline_compartida').is(":visible")){
                        tipo_bitacora="Compartida";
                    }
                }
                //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);
                enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);//Código agregado por Jordan Barría el 13-12-14
                //Fin código agregado por Jordan Barría el 12-11-14
            });

        });
        $('#usuarios_gusta<?php echo $gm_id_mensaje; ?>').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href')+'id_mensaje=<?php echo $gm_id_mensaje; ?>'+'&origen=0')
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_usuarios_gusta_msj_titulo_ventana;?>',
                width: 600,
                height: 400,
                modal: true,
                buttons: {
                    "<?php echo $lang_gusta_msj_cerrar; ?>": function() {
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
        $('#mu_gusta<?php echo $gm_id_mensaje; ?>').click(function(){
            id_mensaje_valoracion = <?php echo $gm_id_mensaje; ?>;
            url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=1&origen=1';
            $.get(url_valoracion, function() {
                muMeGustaMensaje(<?php echo $gm_id_mensaje; ?>);
            });
            
        });
        $('#mu_nogusta<?php echo $gm_id_mensaje; ?>').click(function(){
            id_mensaje_valoracion = <?php echo $gm_id_mensaje; ?>;
            url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=0&origen=1';
            $.get(url_valoracion, function() {
                muMeGustaMensaje(<?php echo $gm_id_mensaje; ?>);
            });
        });
        $('#mu_usuarios_gusta<?php echo $gm_id_mensaje; ?>').each(function() {
            var $linkc = $(this);
             $linkc.click(function() {
                var $dialog = $('<div></div>')
                .load($linkc.attr('href')+'id_mensaje=<?php echo $gm_id_mensaje; ?>'+'&origen=1')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_usuarios_gusta_msj_titulo_ventana;?>',
                    width: 600,
                    height: 400,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_gusta_msj_cerrar; ?>": function() {
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
