<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$semestre = $_REQUEST["semestre"];
$anio = $_REQUEST["anio"];
$subsector = $_REQUEST["subsector"];
if($semestre == 1){
    $semestre = "1° Semestre";
}
else{
    if($semestre == 2){
        $semestre = "2° Semestre";
    }
}
$num_disenos = 1;
$lim_inf = 0;
$lim_sup = 2;
?>
<div class="admin_contenido">
    <div id="admin_listado_experiencias">
        <?php
            $subsector = $_sectores[$subsector]["valor"];
            $_disenosdidacticos = dbAdminObtenerDisenosSubsector($conexion, $subsector);
            $i=0;
            foreach ($_disenosdidacticos as $diseno) {
                $_experiencias = dbAdminObtenerExpDisenoPeriodo($conexion, $diseno["id_dd"], $semestre, $anio);
                if(!is_null($_experiencias)){
                    $i++;
                    foreach ($_experiencias as $_experiencia) {
                        $_imagenes = darFormatoImagen($_experiencia["url_avatar_profesor"], $config_ruta_img_perfil, $config_ruta_img);
                        $_avance_experiencia = dbExpObtenerAvance($_experiencia["id_experiencia"], $conexion);
                        $t_estimado = $_avance_experiencia["suma_sesiones_estimadas"] * $config_minutos_sesion;
                        $t_ejecutado = $_avance_experiencia["suma_t_actividades_finalizadas"] OR 0;
                        $nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);
                        $actividad_terminada = $_avance_experiencia["estado_ultima_actividad"] == '3';
                        $experiencia_finalizada = $_experiencia["fecha_termino"] != '';
                        $fecha = formatearFecha($_experiencia["fecha_ultimo_acceso"]);
                        $lang_fecha_titulo = "Última sesión";
                        $ultima_titulo = $lang_ultima_actividad_finalizada;

                        if (!$actividad_terminada) {
                            $ultima_titulo = $lang_actividad_actual;
                        }
                        if ($experiencia_finalizada) {
                            $fecha = formatearFecha($_experiencia["fecha_termino"]);
                            $lang_fecha_titulo = "Fecha de término";
                        }
                        if ($subsector == $_experiencia["subsector"]) {
                            ?>
                            <div class="admin_cuadro_experiencia">
                                <table class="t_experiencia_cabecera <?php if(!$experiencia_finalizada){echo "en_curso";}?>" id="t_<?php echo $_experiencia["id_experiencia"];?>">
                                    <tr>
                                        <td>
                                            <p onclick="javascript: irAdminExperiencias(<?php echo $_experiencia["id_experiencia"];?>);" class="admin_cuadro_exp_nombre_dd">
                                                <?php echo $_experiencia["nombre_dd"]; ?>
                                            </p>
                                            <div class="info_exp">(<?php echo $_experiencia["curso"] . ", " . $_experiencia["colegio"] . ", " . $_experiencia["localidad"]; ?>)</div>
                                            <ul class="imagen_profesor_exp">
                                                <li>
                                                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia["usuario_profesor"]; ?>" alt="<?php echo $_experiencia["nombre_profesor"]; ?>" title="<?php echo $_experiencia["nombre_profesor"]; ?>" class ="nombre_profesor_exp_todas">
                                                        <img class="admin_avatar" src="<?php echo $_imagenes[imagen_usuario]; ?>"/></a>
                                                </li>
                                                <li>
                                                    <a class="nombre_profesor_exp_todas"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia["usuario_profesor"]; ?>" alt="<?php echo $_experiencia["nombre_profesor"]; ?>" title="<?php echo $_experiencia["nombre_profesor"]; ?>" class ="link_perfil"><?php echo ucwords($_experiencia["nombre_profesor"]); ?></a>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <div class="avance_exp"><?php echo number_format($nivel_avance, 0) ?>%</div>
                                        </td>
                                        <td>
                                            <input class="admin_ir" type="button" onclick="javascript: adminEliminarExperiencia(<?php echo $_experiencia["id_experiencia"];?>);" value="<?php echo $lang_admin_eliminar; ?>"><br>
                                        </td>
                                        <td>
                                            <input class="admin_ir" type="button" onclick="javascript: irAdminExperiencias(<?php echo $_experiencia["id_experiencia"];?>);" value="<?php echo $lang_le_ir; ?>"><br>
                                        </td>

                                    </tr>
                                </table>
                            </div>
                            <?php

                        }

                    }

                }

            }
            if($i==0){
                echo $lang_admin_no_exp_disponibles;
            }

        dbDesconectarMySQL($conexion);
        ?>
    </div>
</div>
    <script type="text/javascript">

        function adminCargarExperiencias(subsector){
            if (subsector == ""){
                subsector =0;
            }
            $.get('admin/admin_exp_todas_experiencias.php?semestre=<?php echo $semestre;?>&anio=<?php echo $anio;?>&subsector='+subsector, function(data) {
                  $('.admin_exp_contenido').html(data);
            });
        }
        function irAdminExperiencias(id_experiencia){
            $.get('admin/admin_experiencias.php?origen=1&codeexp='+id_experiencia, function(data) {
                $('#admin_contenido').html(data);
            });

        }
        $(document).ready(function(){
            $('.nombre_profesor_exp_todas').click(function() {
                var $linkc = $(this);
                var $dialog = $('<div></div>')
                .load($linkc.attr('href'))
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_admin_perfil_usuario;?>',
                    width: 800,
                    height: 600,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_admin_cerrar; ?>": function() {
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

    function adminEliminarExperiencia(id_experiencia){
        var contenido = '<p><?php echo $lang_admin_seguro_eliminar_exp; ?></p>';
        var $dialog = $('<div class=\"dialogo_eliminar_experiencia\" id=\"dialogo_eliminar_experiencia\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_eliminar_exp; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesExperienciaEliminar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_si; ?>": function() {
                    url = 'admin/admin_exp_editar_info_general.php?&codeexp='+id_experiencia+'&eliminar=1';
                    $.post(url,  function(data) {
                        if(data == 1){
                            $('#dialogo_eliminar_experiencia').html('<p><?php echo $lang_admin_exp_did_eliminada; ?></p>');
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(3)").show();
                            $("#t_"+id_experiencia).hide();
//                            adminAdministrarExperiencias();
                            
                        }
                        else{
                            $('#dialogo_eliminar_experiencia').html('<p><?php echo $lang_admin_problema_cambio; ?></p>');
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(3)").show();
                        }
                    });
                },
                //CANCELAR
                "<?php echo $lang_admin_no; ?>": function() {
                    $(this).dialog('destroy').remove();
                },
                //CERRAR
                "<?php $lang_admin_cerrar; ?>": function() {
                    $(this).dialog('destroy').remove();
                }
            },
            close: function() {
            }
            });
        $("div.dialogBotonesExperienciaEliminar div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }
</script>