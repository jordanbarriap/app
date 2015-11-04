<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$cont_datos = count(dbObtenerComunidadProfesores($conexion));
$cant_grupos = (int) ($cont_datos / 50);
$resto = $cont_datos % 50;
if ($resto != 0) {
    $cant_grupos = $cant_grupos + 1;
}
$lim_inf=0;
$lim_sup=50;
$cont=2;
$datos=dbObtenerComunidadProfesoresLimite($conexion,$lim_inf,$lim_sup);
?>

<div class="inicio_comunidad">
    <p id="somos"><?php echo $lang_inicio_comun_ya_somos; ?><?php echo " ".$cont_datos." "?> <?php echo $lang_inicio_comun_prof_colab; ?></p>
    <ul id="comunidad" class="imagen_profesor_exp">
        <?php
        foreach ($datos as $dato) {
            $_imagenes = darFormatoImagen($dato["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
            ?>
            <li>
                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $dato["nombre_usuario"]; ?>" alt="<?php echo $dato["nombre"]; ?>" title="<?php echo $dato["nombre"]; ?>" class ="nombre_profesor_exp_todas md_link_nombre">
                    <img class="admin_avatar" src="<?php echo $_imagenes[imagen_usuario]; ?>"/></a>
            </li>
            <li>
                <a class="nombre_profesor_exp_todas"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $dato["nombre_usuario"]; ?>" alt="<?php echo $dato["nombre"]; ?>" title="<?php echo $dato["nombre"]; ?>" class ="link_perfil md_link_nombre"></a>
            </li>
            <?php
        }
        
                    if ($cont < $cant_grupos) {
?>

                <div id="cont">
                    <button id="ver" class="vermascomunidad" onclick="javascript:verMas();"><?php echo $lang_inicio_comun_ver_mas; ?> »</button>
                    <input id="li" type="hidden" value="<?php echo $lim_inf+$lim_sup + 1; ?>">
                    <input id="ls"  type="hidden" value="<?php echo $lim_sup?>">
                    <input id="contador" type="hidden" value="<?php echo $cont; ?>">
                    <input id="cant_grupos" type="hidden" value="<?php echo $cant_grupos; ?>">
                </div>
<?php
            }?>
            
    </ul>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.md_link_nombre').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_perfil_usuario_titulo_ventana; ?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_inicio_comun_cerrar; ?>": function() {
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
            function verMas(){
            cont=$("#contador").val();
            url = 'inicio_comunidad_mas.php?lim_inf='+$("#li").val()+'&lim_sup='+$("#ls").val()+'&cont='+cont+'&cant_grupos='+$("#cant_grupos").val();
            $.get(url,function(data){
                $("#cont").remove();
                $("#comunidad").append(data).show('slow');
                return false;
            });
        
            return false;
        }
</script>

