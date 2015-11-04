<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])

    )header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
$es_profesor_o_col = ($_SESSION["klwn_inscribe_diseno"] == 1);
?>
<div id="contenido_mis_experiencias">
    <?php
    $estado = $_REQUEST['estado'];
    //var_dump($grupos);
    $conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_todas_experiencias = dbObtenerExpUsuarioMin($_SESSION["klwn_usuario"], $conexion);
    if($estado==1){
        $titulo_experiencia = $lang_exp_lista_exp_mis_exp_curso;
    }
    if($estado ==2){
        $titulo_experiencia = $lang_exp_lista_exp_mis_exp_dd_fin;
    }
    ?><div class="estado_experiencia"><?php echo $titulo_experiencia ?></div><?php
    if (is_null($_todas_experiencias)) {
        if($es_profesor_o_col && $estado ==1){
            //Invitar a inscribir una experiencia
            echo $lang_parrafo_no_experiencias_profesor;
        }
        else{
            if($estado==1){
                //Puede ser estudiante o potencial porfesor, hace una invitación a revisar los DD si es profesor y si estrudiante decirle que necesita
                //codigo para inscribir una experiencia
                echo $lang_parrafo_no_experiencias;
            }
            else{
                echo $lang_no_finalizadas;
            }
        }   
    } 
    else {
        agregarExperienciasSesion($_todas_experiencias);

        if (isset($estado)) {
            if ($estado == 1) {
                $_experiencias = dbObtenerExpCursoUsuario($_SESSION["klwn_usuario"], $conexion);
                $mensaje_no_experiencias = $lang_no_en_curso;
                $lang_le_participacion = $lang_le_participas;
                
            } elseif ($estado == 2) {
                $_experiencias = dbObtenerExpFinalizadasUsuario($_SESSION["klwn_usuario"], $conexion);
                $mensaje_no_experiencias = $lang_no_finalizadas;
                $lang_le_participacion = $lang_le_participaste;
            }

            if (count($_experiencias) > 0) {
                $cantidad_subsectores = contarSubsectoresExperiencias($_todas_experiencias, $_lang_le_subsectores);
                //Si hay a lo menos una experiencia imprime el titulo experiencias en curso o experiencias finalizadas
                foreach($_sectores as $sec) {
                    $subsector = $sec["valor"];
                    $titulo_subsector = $lang_le_subsector . " " . $sec["nombre"];
                    $cantidad_exp_subsector = contarExperienciasSubsector($_experiencias, $subsector);
                    if ($cantidad_exp_subsector > 0) {
    ?>
                        <!--Si existe mas de un subsector se imprime el titulo de subsector que corresponda!-->
                        <div class="titulo_subsector"><?php echo $titulo_subsector ?></div>
    <?
                        foreach ($_experiencias as $_experiencia) {
                            $_experiencia_info = dbExpObtenerInfo($_experiencia["id_experiencia"], $conexion);
                            $_imagenes = darFormatoImagen($_experiencia_info["url_avatar_profesor"], $config_ruta_img_perfil, $config_ruta_img);

                            $_avance_experiencia = dbExpObtenerAvance($_experiencia["id_experiencia"], $conexion);
                            $t_estimado = $_avance_experiencia["suma_sesiones_estimadas"] * $config_minutos_sesion;
                            $t_ejecutado = $_avance_experiencia["suma_t_actividades_finalizadas"] OR 0;
                            $nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);

                            $actividad_terminada = $_avance_experiencia["estado_ultima_actividad"] == '3';
                            $experiencia_finalizada = $_experiencia_info["fecha_termino"] != '';
                            $fecha = formatearFecha($_experiencia_info["fecha_ultimo_acceso"]);
                            $lang_fecha_titulo = $lang_exp_lista_exp_ult_sesion;
                            $ultima_titulo = $lang_ultima_actividad_finalizada;
                            $rol = $_experiencia["rol"];

                            if (!$actividad_terminada) {
                                $ultima_titulo = $lang_actividad_actual;
                            }
                            if ($experiencia_finalizada) {
                                $fecha = formatearFecha($_experiencia_info["fecha_termino"]);
                                $lang_fecha_titulo = $lang_exp_lista_exp_fecha_termino;
                            }
                            if ($subsector == $_experiencia_info["subsector"]) {
    ?>
                                <div class="cuadro_experiencia">
                                    <table class="t_experiencia_cabecera">
                                        <tr>
                                            <td>
                                                &raquo; <a class="titulo_exp"href="experiencia.php?codexp=<?php echo $_experiencia["id_experiencia"] ?>" alt="<?php echo $_experiencia["nombre_dd"] ?>" title="<?php echo $_experiencia["nombre_dd"] ?>" name="Experiencia <?php echo $_experiencia["id_experiencia"] ?>" ><?php echo $_experiencia["nombre_dd"] ?></a>
                                                <img src="<?php echo $config_ruta_img . $_lang_le_roles_img[$rol - 1] ?>" title="<?php echo $lang_le_participacion . $_lang_le_roles[$rol - 1] ?>" alt="<?php echo $lang_le_participacion . $_lang_le_roles[$rol - 1] ?>">
                                                <div class="info_exp">(<?php echo $_experiencia_info["curso"] . ", " . $_experiencia_info["colegio"] . ", " . $_experiencia_info["localidad"]; ?>)</div>
                                                <ul class="imagen_profesor_exp">
                                                    <li>
                                                        <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia_info["usuario_profesor"]; ?>" alt="<?php echo $_experiencia_info["nombre_profesor"]; ?>" title="<?php echo $_experiencia_info["nombre_profesor"]; ?>" class ="link_perfil"><img class="inicio_lu_img" src="<?php echo $_imagenes[imagen_usuario]; ?>"/></a>
                                                    </li>
                                                    <li>
                                                        <a class="nombre_profesor_exp"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia_info["usuario_profesor"]; ?>" alt="<?php echo $_experiencia_info["nombre_profesor"]; ?>" title="<?php echo $_experiencia_info["nombre_profesor"]; ?>" class ="link_perfil"><?php echo ucwords($_experiencia_info["nombre_profesor"]); ?></a>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <input class="ir" type="button" onclick="location.href='experiencia.php?codexp=<?php echo $_experiencia["id_experiencia"] ?>'" value="<?php echo $lang_le_ir; ?>" name="Experiencia <?php echo $_experiencia["id_experiencia"] ?>"><br><!--atrib. name agregado por Jordan Barría el 12-04-15 para almacenar el click de este botón-->
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="t_experiencia">
                                        <tr>
                                            <td>
                                                <?php
                                                if ($_avance_experiencia["ultima_actividad"] != "") {
                                                    echo $ultima_titulo . ": " . $_avance_experiencia["ultima_actividad"];
                                                } else {
                                                    echo $lang_sin_actividades_comenzadas;
                                                }
                                        ?>      <br>
                                                <?php echo $lang_fecha_titulo . ": " . $fecha ?>
                                            </td>
                                            <td><div class="avance_exp"><?php echo number_format($nivel_avance, 0) ?>%</div></td>
                                        </tr>
                                    </table>
                                </div>
    <?php
                            }
                        }
                    }
                }
            }
            else{
                echo $mensaje_no_experiencias;
                
            }
        }
    }
    dbDesconectarMySQL($conexion);
    if ($_SESSION["klwn_inscribe_diseno"] == 1) {
    ?>
        <div id="pretest">
            <a href="#" id="enlace_prestest" rel="pretest_modal" alt="<?php echo $lang_pretest; ?>" title="<?php echo $lang_pretest; ?>">
            <?php echo $lang_pretest; ?>
        </a>
    </div>
    <?php } ?>
    </div>
    <div id="pretest_modal">
        <p><?php echo $lang_exp_lista_exp_estr_evaluacion; ?>
            <?php echo $lang_exp_lista_exp_kellu_item1; ?>
            <?php echo $lang_exp_lista_exp_kellu_item2; ?></p>
        <p>- <?php echo $lang_exp_lista_exp_kellu_item3; ?><br>
            - <?php echo $lang_exp_lista_exp_kellu_item4; ?>
            <?php echo $lang_exp_lista_exp_kellu_item5; ?></p>
        <p><?php echo $lang_exp_lista_exp_kellu_item6; ?>
            <?php echo $lang_exp_lista_exp_kellu_item7; ?>
            <?php echo $lang_exp_lista_exp_kellu_item8; ?>
            <?php echo $lang_exp_lista_exp_kellu_item9; ?></p>
        <p><?php echo $lang_exp_lista_exp_kellu_item10; ?>
            <?php echo $lang_exp_lista_exp_kellu_item11; ?>
            <?php echo $lang_exp_lista_exp_kellu_item12; ?>
        </p>
        <br>
        <p><a href="<?php echo $config_ruta_documentos; ?>diseno_y_validacion_pre_y_post_test.pdf" alt="<?php echo $lang_exp_lista_exp_descargar_doc; ?>" title="<?php echo $lang_exp_lista_exp_descargar_doc; ?>" target="_blank"><?php echo $lang_exp_lista_exp_descargar_doc; ?></a></p>
    </div>
    <script type="text/javascript">
        $('.link_perfil').click(function() {
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
                "<?php echo $lang_exp_lista_exp_cerrar; ?>": function() {
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
    $(document).ready(function(){
        var $tabes = $('#tabs2').tabs();

        $('a#link_tab_inscribir_experiencia').click(function() {
            $tabes.tabs('select', 3);
            return false;
        });

        $('a#link_tab_disenos_didacticos').click(function() { // bind click event to link
            $tabes.tabs('select', 2);
            return false;
        });
        $('a#link_tab_todas_experiencias').click(function() { // bind click event to link
            $tabes.tabs('select', 1);
            return false;
        });

        $('#pretest_modal').dialog({
            title: '<?php echo $lang_exp_lista_exp_medicion_competencias; ?>',
            autoOpen: false,
            width: 550,
            height: 450,
            modal: true,
            buttons: {
                "<?php echo $lang_exp_lista_exp_cerrar; ?>": function() {
                    $(this).dialog("close");
                }
            }
        });
        $('#enlace_prestest').click(function(){
            $('#pretest_modal').dialog('open');
        });
        $('.nombre_profesor_exp').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_exp_lista_exp_perfil_usuario;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_exp_lista_exp_cerrar; ?>": function() {
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
