<?php
/**
 * Despliega la lista de etapas del diseño didáctico como varios acordiones
 * JQueryUI y en cada uno, la lista de actividades con su estado actual.
 * Para cada actividad se agregan botones para cambiar de estado y comentar.
 * El nombre de cada actividad despliega además los detalles de la actividad,
 * incluyendo sus documentos en un diálogo JQueryUI
 * Recibe:
 *  codexp: el id de la experiencia didáctica (tabla expereincia_didactica)
 *
 * @author  Daniel Guerra - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])

    )header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "revpares/inc/rp_db_functions.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "reco/inc/rec_db_functions.inc.php");
require_once($ruta_raiz . "reco/inc/rec_functions.inc.php");

$id_usuario = $_SESSION["klwn_id_usuario"];
$titulo_pagina = $lang_sufijo_titulo_paginas . $lang_experiencia_didactica;
$descripcion_pagina = $lang_descripcion_pagina_experiencia_didactica;
$id_experiencia = $_REQUEST["codexp"];
if (is_null($id_experiencia) or strlen($id_experiencia) == 0) {
    $error = 1;
    $error_msg = "";
} else {
    $conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $id_diseno = dbExpObtenerIdDiseno($id_experiencia, $conexion);
    $_etapas = dbExpObtenerEtapas($id_experiencia, $conexion);
    $info_experiencia = dbExpObtenerInfo($id_experiencia, $conexion);
    $profesor = dbExpObtenerProfesor($id_experiencia, $conexion);

    if($profesor["id"]== $_SESSION["klwn_id_usuario"]){
        $es_profesor_responsable =1;
    }

    //detección de actividad y su tipo (si publoica productos o no)
    $avance_exp = dbExpObtenerAvance($id_experiencia, $conexion);
    $estado = $avance_exp["estado_ultima_actividad"];
    $id_act = $avance_exp["ultima_actividad_id"];
    $t_ejecutado = $avance_exp["suma_t_actividades_finalizadas"] OR 0;
    $t_estimado = $avance_exp["suma_sesiones_estimadas"] * $config_minutos_sesion;
    $nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);
    $actividad = $avance_exp["ultima_actividad_id"];
    $info_actividad = dbExpObtenerActividad($actividad, $conexion);
    $bandera_publica_producto = $info_actividad['publica_producto'];
    //consulta en caso de ser actividad de publicación de producto
    if ($bandera_publica_producto == 1) {
        $trabajos_mi_clase = dbRPTrabajosClasePorActividad($id_experiencia, $actividad, $conexion);
        $nro_grupos_exp = dbRPCuentaGruposExp($id_experiencia, $conexion);
        $clase_completa = 0;
        if (count($trabajos_mi_clase) == $nro_grupos_exp) {
            $clase_completa = 1;
        }
    } else {
        $clase_completa = 0;
    }


//    //consulta si tiene gemelos o no
//    $etiqueta_gemela = $info_experiencia["etiqueta_gemela"];
//    $clases_gemelas = dbObtenerInfoClaseGemela($id_experiencia,$etiqueta_gemela,$conexion);
//    if($clases_gemelas){
//        $i = 0;
//        // para cada clase gemela se verifican sus vinculos
//        foreach ($clases_gemelas as $clase){
//            $_vinculo_clases_gemelas = dbRPObtieneVinculoGemelos($clase["id_experiencia"],$actividad,$conexion);
//            //echo "GEMELOS: ";
//            //print_r($_vinculo_clases_gemelas);
//            if($_vinculo_clases_gemelas){
//                if($_vinculo_clases_gemelas[0]["grevisor"] == null){
//                        //se guardan los vinculos que no tienen asignacion
//                        $_vinculos_gemelos_no_asignados[$i] = $_vinculo_clases_gemelas;
//                        $i++;
//                }
//                else{
//                    //caso en que las clases gemelas no esperaron
//                    $contador_no_espera++;
//                }
//            }
//            else{
//                $existen_gemelas_libres = 0;
//                //las gemelas no han termando
//            }
//        }
//        if($_vinculos_gemelos_no_asignados){
//            $existen_gemelas_libres = 1;
//            //definir logica de vinculacion
//        }
//        elseif(count($clases_gemelas) == $contador_no_espera){
//                //ninguna clase espero
//                $existen_gemelas_libres = -1;
//        }
//    }
//    else{
//        //no existen clases gemelas
//        $existen_gemelas_libres = -2;
//    }
//    //echo '  estado de las clases gemelas='.$existen_gemelas_libres;
//    /*
//     * -2 = no existen clases gemelas
//     * -1 = clase gemela no esperó
//     *  0 = clase gemela no ha publicado
//     *  1 = clase gemela lista
//     */
//




    if (is_null($_etapas)) {
        $error = 3;
        $error_msg = $lang_error_no_existen_etapas;
    }
    $rol_esta_experiencia = validaExperiencia($id_experiencia);
    $es_estudiante = ($rol_esta_experiencia == 2);
    $es_observador = ($rol_esta_experiencia == -1);
    $experiencia_finalizada = $info_experiencia["fecha_termino"];
}
$netapas = 0;
?>
<div class="contenido" >       
    <div class="contenido_izquierda">
        <div id="lista_etapas">
            <?php
            if ($error == 0) {
                echo "        <div id=\"accordion\">\n\r";
                $existe_act_comenzada = false;
                /* Cada etapa es un acordión */
                foreach ($_etapas as $etapa) {
                    if($netapas == 0){$nombre_etapa = $lang_motivacion;}
                    if($netapas == 1){$nombre_etapa = $lang_creacion;}
                    if($netapas == 2){$nombre_etapa = $lang_evaluacion;}
                    
                    echo "            <div>\n\r";
                    echo "            <h3><a href=\"#\">" . $lang_etapa . " " . ($netapas + 1) .": ".$nombre_etapa. "</a></h3>\n\r";
                    echo "            <div class=\"info_etapa\">\n\r";
                    // DESCRIPCIÓN, OBJETIVOS, APRENDIZAJES ESPERADOS, ETC.
                    echo "            <p>" . $etapa["descripcion"]."</p>\n\r";
                    // CUADRO CON ACTIVIDADES
                    if (!is_null($etapa["actividades"])) {
                        echo "<div class=\"lista_actividades\">\n\r";
                        echo "<table class=\"t_lista_actividades\">\n\r";
                        echo "<tr>\n\r";
                        echo "  <td class=\"titulo_lista_actividades tla1\">" . $lang_actividades_etapa . "</td>\n\r";
                        echo "  <td class=\"titulo_lista_actividades tla6\">" . $lang_materiales . "</td>\n\r";
                        echo "  <td class=\"titulo_lista_actividades tla2\">" . $lang_horas_estimadas_actividad . "</td>\n\r";
//                        echo "  <td class=\"titulo_lista_actividades tla3\">" . $lang_comentarios . "</td>\n\r";
                        echo "  <td class=\"titulo_lista_actividades tla4\">" . $lang_estado_actividad . "</td>\n\r";
                        echo "  <td class=\"titulo_lista_actividades tla5\">&nbsp;</td>\n\r";
                        echo "</tr>\n\r";
                        $impar = true;
                        foreach ($etapa["actividades"] as $actividad) {
                            /* Se agrega cada actividad de la etapa a la lista */
                            if ($impar)
                                $back_class = "filaimpar";
                            else
                                $back_class = "filapar";
                            $icono_rp = "";
                            if ($actividad["publica_producto"] == "1")
                                $icono_rp = "<img class=\"rp_icono_actividad\" src=\"img/act_publicacion.png\" title=\"" . $lang_icono_publicacion . "\" alt=\"" . $lang_icono_publicacion . "\" />";
                            if ($actividad["revisa_pares"] == "1")
                                $icono_rp .= " <img class=\"rp_icono_actividad\" src=\"img/act_revision.gif\" title=\"" . $lang_icono_revision . "\" alt=\"" . $lang_icono_revision . "\" />";

                            $rol_usuario = 1;
                            if ($es_estudiante)
                                $rol_usuario = 2;
                            if ($es_observador)
                                $rol_usuario = 0;
                            $horas = number_format($actividad["horas_estimadas"] / 45, 1);
                            echo "<tr>\n\r";
                            echo "  <td class=\"tla1 actividad " . $back_class . "\">";
                            echo "      <a class=\"link_ventana_actividad\" href=\"exp_actividad.php?codact=" . $actividad["id_actividad"] . "&rol=" . $rol_usuario ."&id_dd=".$id_diseno ."\" title=\"" . $actividad["nombre"] . "\">" . $actividad["nombre"] . "</a>\n\r";
                            if ($actividad["tipo"] == 2) {
            ?>
                                <img  src="img/laboratorio.png" title="<?php echo $lang_exp_lista_etapas_act_lab_computacion; ?>" >
            <?php
                            }
                            echo "      " . $icono_rp . "\n\r";
                            echo "  </td>\n\r";
                            echo "  <td class=\"tla6 actividad " . $back_class . "\">\n\r";
                            echo "      <a id=\"link_v_d_a_id_" . $actividad["id_actividad"] . "\" class=\"link_ventana_comentarios_actividad\" href=\"doc_actividad.php?codact=" . $actividad["id_actividad"] . "&rol=" . $rol_usuario . "\" title=\"" . $lang_tab_documentos . " - " . $actividad["nombre"] . "\">\n\r";
                            echo "          <img src=\"img/documentos_16.png\" alt=\"" . $lang_documentos . "-" . $actividad["nombre"] . "\" />\n\r";
                            echo "      </a>\n\r";
                            echo "  </td>\n\r";
                            echo "  <td class=\"tla2 actividad " . $back_class . "\">" . $horas . "\n\r";
                            echo "  </td>\n\r";
//                            echo "  <td class=\"tla3 actividad " . $back_class . "\">\n\r";
//                            $puede_comentar = '';
//                            if ($actividad["estado"] == '2' OR $actividad["estado"] == '3')
//                                $puede_comentar = '1';
//                            echo "      <a id=\"link_v_c_act_id_" . $actividad["id_actividad"] . "\" class=\"link_ventana_comentarios_actividad\" href=\"exp_comentarios_actividad.php?codexp=" . $id_experiencia . "&coddiseno=" . $id_diseno . "&codact=" . $actividad["id_actividad"] . "&rol=" . $rol_usuario . "&codexpact=" . $actividad["id_exp_actividad"] . "&comentar=" . $puede_comentar . "\" title=\"" . $lang_comentarios . " - " . $actividad["nombre"] . "\">\n\r";
//                            echo "          <img src=\"img/comentarios_16.png\" alt=\"" . $lang_comentarios . "\" />\n\r";
//                            echo "      </a>\n\r";
//                            echo "</td>\n\r";
                            if ($es_estudiante || $es_observador || $experiencia_finalizada) {
                                if ($actividad["estado"] == '1') {
                                    $boton_class = "";
                                    $boton_txt = "";
                                    $onclick = "";
                                }
                                if ($actividad["estado"] == '2') {
                                    $boton_class = "boton_finalizar";
                                    $boton_txt = $lang_boton_en_ejecucion;
                                    $existe_act_comenzada = true;
                                    $onclick = "";
                                }
                                if ($actividad["estado"] == '3') {
                                    $boton_class = "boton_finalizada";
                                    $boton_txt = "&nbsp;";
                                    $onclick = "";
                                }
                                $boton_deshacer_div = "";
                            } else {
                                $estado_txt = "&nbsp;";
                                $boton_class = "";
                                $boton_txt = "";
                                $onclick = "onclick=\"javascript: cambiarEstadoActividad();\"";
                                $onclick2 = "onclick=\"javascript: deshacerActividad('" . $actividad["id_actividad"] . "','" . $actividad["id_exp_actividad"] . "','" . $actividad["nombre"] . "','" . $etapa["id_exp_etapa"] . "', '" . $actividad["id_actividad"] . "', 'id_act_" . $actividad["id_actividad"] . "','" . $actividad["publica_producto"] . "','" . $clase_completa . "');\"";
                                $clase_visible = "bda_inactivo";
                                if ($actividad["estado"] == '1') {
                                    $boton_class = "boton_comenzar";
                                    $boton_txt = $lang_boton_comenzar;
                                    $onclick = "onclick=\"javascript: iniciarActividad('" . $actividad["id_actividad"] . "','" . $actividad["id_exp_actividad"] . "', '" . $actividad["nombre"] . "','" . $etapa["id_exp_etapa"] . "', '" . $actividad["id_actividad"] . "', 'id_act_" . $actividad["id_actividad"] . "','" . $actividad["publica_producto"] . "','" . $clase_completa . "');\"";
                                    $boton_deshacer_div = "";
                                }
                                if ($actividad["estado"] == '2') {
                                    $boton_class = "boton_finalizar";
                                    $boton_txt = $lang_boton_finalizar;
                                    $existe_act_comenzada = true;
                                    $onclick = "onclick=\"javascript: finalizarActividad('" . $actividad["id_actividad"] . "','" . $actividad["id_exp_actividad"] . "','" . $actividad["nombre"] . "', 'id_act_" . $actividad["id_actividad"] . "','" . $actividad["publica_producto"] . "','" . $clase_completa . "');\"";
                                    $boton_deshacer_div = "";
                                }
                                if ($actividad["estado"] == '3') {
                                    $boton_class = "boton_finalizada";
                                    $boton_txt = "&nbsp;";
                                    $onclick = "";
                                    $clase_visible = "bda_activo";
                                }
                                $boton_deshacer_div = "<div class=\"boton_deshacer_actividad " . $clase_visible . "\" id=\"id_desact_" . $actividad["id_actividad"] . "\" " . $onclick2 . ">&nbsp;</div>";
                            }
                            $boton_cambiar_estado_div = "<div class=\"" . $boton_class . "\" id=\"id_act_" . $actividad["id_actividad"] . "\" " . $onclick . ">" . $boton_txt . "</div>";
                            echo "            <td class=\"tla4 actividad " . $back_class . "\">" . $boton_cambiar_estado_div . "\n\r";
                            echo "            </td>\n\r";
                            echo "            <td class=\"tla5 actividad " . $back_class . "\">" . $boton_deshacer_div . "\n\r";
                            echo "            </td>\n\r";
                            echo "            </tr>\n\r";
                            $impar = !$impar;
                        }
                        echo "            </table>\n\r";
                        echo "            </div>\n\r";
                        //IDENTIFICAR LA ULTIMA ETAPA
                        if ($netapas == 2 && $es_profesor_responsable) {
            ?>
                            <button  id ="boton_finalizar_exp" class="finalizar_experiencia" onclick="javascript: finalizarExperiencia();"><?php echo $lang_exp_lista_etapas_finalizar_exp; ?></button>
            <?php
                        }
                    } else {
                        mostrarError($lang_error_no_existen_actividades);
                    }
                    echo "            </div>\n\r";
                    echo "            </div>\n\r";
                    $netapas++;
                }
                echo "        </div>\n\r";
            } else {
                echo "        <div id=\"accordion\">\n\r";
                mostrarError($error_msg);
                echo "        </div>\n\r";
            }
            ?>
        </div>
    </div>
    <div class="contenido_derecha" id="avance_exp">
        <?php
        if (!is_null($info_experiencia["objetivos_c"])) {
        ?>
        <div class="ficha_contenido">
            <div class="ficha_contenido_titulo_c " id ="ficha_curriculares_titulo_c">
                <div class="titulo_f">
                    <img id="img_c" src="img/flecha_c.png"></img>
                    <?php echo $lang_exp_lista_etapas_obj_curriculares; ?>
                </div>
            </div>
            <div class="ficha_contenido_titulo_a " id="ficha_curriculares_titulo_a">
                <div class="titulo_f">
                    <img id="img_a" src="img/flecha_a.png"></img>
                    <?php echo $lang_exp_lista_etapas_obj_curriculares; ?>
                </div>
            </div>  
            <div class="ficha_contenido_c" id="ficha_curriculares_c"> 
                <?php
                echo $info_experiencia["objetivos_c"];
                ?>
            </div>
        </div>
        <?php         
        }
        if (!is_null($info_experiencia["objetivos_t"])) {
        ?>
        <div class="ficha_contenido" >
            <div class="ficha_contenido_titulo_c " id ="ficha_transversales_titulo_c">
                <div class="titulo_f" id="">
                    <img id="img_c" src="img/flecha_c.png"></img>
                    <?php echo $lang_exp_lista_etapas_obj_transversales; ?>
                </div>
            </div>
            <div class="ficha_contenido_titulo_a " id="ficha_transversales_titulo_a">
                <div class="titulo_f">
                    <img id="img_a" src="img/flecha_a.png"></img>
                    <?php echo $lang_exp_lista_etapas_obj_transversales; ?>
                </div>
            </div>  
            <div class="ficha_contenido_c" id ="ficha_transversales_c"> 
                <?php
                echo $info_experiencia["objetivos_t"];
                ?>
            </div>
       </div>
        <?php
        }
        if (!is_null($info_experiencia["contenidos"])) {
        ?>
        <div class="ficha_contenido">
            <div class="ficha_contenido_titulo_c " id="ficha_contenidos_titulo_c">
                <div class="titulo_f">
                    <img id="img_c" src="img/flecha_c.png"></img>
                    <?php echo $lang_exp_lista_etapas_contenidos; ?>
                </div>
            </div>
            <div class="ficha_contenido_titulo_a " id="ficha_contenidos_titulo_a">
                <div class="titulo_f">
                    <img id="img_a" src="img/flecha_a.png"></img>
                    <?php echo $lang_exp_lista_etapas_contenidos; ?>
                </div>
            </div>  
            <div class="ficha_contenido_c" id="ficha_contenidos_c"> 
                <?php
                echo $info_experiencia["contenidos"];
                ?> 
            </div>
        </div>
        <?php
        }
        ?>
        <div id="rec_cuadro_recomendaciones">
        </div>
    </div>
</div>
<?php dbDesconectarMySQL($conexion); ?>
<script type="text/javascript">
<?php
    if (!$es_estudiante && !$es_observador) {
?>
            function despliegaCuadroRecomendaciones(id_actividad,estado){                
                url = '<?php echo $ruta_raiz?>reco/rec_despliega_cuadro_recomendacion.php?id_actividad='+id_actividad+'&estado='+estado;
                $.get(url, function(data) {
                  $('#rec_cuadro_recomendaciones').html(data);
                });
               return false;
            }

            function recIniciarCuadroRec() {
                //$('#rec_menu_despliegue_reco ul').hide();
                $('.rec_titulo_cuadroreco_c').hide();
                $('#rec_menu_despliegue_reco li a').click(function() {
                    var checkElement = $(this).next();
                    if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                        checkElement.slideUp('normal');
                        $('.rec_titulo_cuadroreco_a').hide();
                        $('.rec_titulo_cuadroreco_c').show();
                        return false;
                    }
                    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                        checkElement.slideDown('normal');
                        $('.rec_titulo_cuadroreco_a').show();
                        $('.rec_titulo_cuadroreco_c').hide();
                        return false;
                    }
                });
            }

            /* Muestra u oculta todos los botones comenzar actividad */
            function visibilidadNoComenzadas(mostrar){
                if (mostrar) $(".boton_comenzar").show();
                else $(".boton_comenzar").hide();
            }
            function finalizarExperiencia(){
                var $link = $(this);
                var $cambiar = false;
                var id_experiencia = '<?php echo $id_experiencia; ?>';
                var contenido = '<p class=\"resaltado\"><?php echo $lang_seguro_finalizar_experiencia1; ?></p><p><?php echo $lang_seguro_finalizar_experiencia2; ?>.</p>' ;
                var $dialog = $('<div id=\"dialogo_cambiando_estado\">'+contenido+'</div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_exp_lista_etapas_finalizar_exp; ?>',
                    width: 400,
                    height: 250,
                    modal: true,
                    buttons: {
                        '<?php echo $lang_exp_lista_etapas_finalizar; ?>': function() {
                            $.get(
                            'exp_finalizar_experiencia.php?codeexp='+id_experiencia,
                            function(data){
                                if (data !='-1'){
                                    location.reload();
                                }
                            }
                        );
                            $(this).dialog('close');
                        },
                        '<?php echo $lang_boton_cancelar; ?>': function() {
                            $(this).dialog('close');
                        }
                    },
                    close: function() {
                    }
                });
                $dialog.dialog('open');
            }
            
            /*
                    Ejecuta las acciones necesarias para deshacer la ejecución de una actividad:
                    - Despliega un diálogo de "Esta seguro que desea deshacer ..."
                    - Al cerrar el diálogo aceptando, se invoca exp_deshacer_actividad.php
                    - Si no hay error, se cambian los estados/funciones de los botones:
                        - Cambiar el botón de la actividad a "Comenzar"
                        - Deshabilitar el botón "Deshacer"
             */
            function deshacerActividad(id_act, id_exp_act, nombre_actividad, id_exp_etapa, etiqueta_act, id_div_boton, bandera_publica_producto, bandera_clase_completa){
                var $link = $(this);
                var contenido = '<p class=\"resaltado\"><?php echo $lang_seguro_de_deshacer_actividad1; ?></p>' +
                    '<p class=\"nombre_actividad\">' + nombre_actividad + '</p>' +
                    '<p><?php echo $lang_seguro_de_deshacer_actividad2; ?></p>';
                var $dialog = $('<div id=\"dialogo_deshaciendo_actividad\">'+contenido+'</div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_deshacer_actividad; ?>',
                    width: 400,
                    height: 250,
                    modal: true,
                    buttons: {
                        '<?php echo $lang_boton_deshacer; ?>': function() {
                            $.get(
                            'exp_deshacer_actividad.php?codexpact='+id_exp_act,
                            function(data){
                                if (data != '0' && data !='-1'){
                                    $('#'+id_div_boton).removeClass("boton_finalizada");
                                    $('#'+id_div_boton).addClass("boton_comenzar");
                                    $('#'+id_div_boton).html("<?php echo $lang_boton_comenzar; ?>");
                                    $('#'+id_div_boton).unbind('click');
                                    $('#'+id_div_boton).bind('click',function(){
                                        iniciarActividad(id_act,id_exp_act,nombre_actividad,id_exp_etapa,etiqueta_act,id_div_boton,bandera_publica_producto, bandera_clase_completa);
                                    });
                                    $('#'+'id_desact_'+id_act).removeClass('bda_activo');
                                    $('#'+'id_desact_'+id_act).addClass('bda_inactivo');
                                    $('#'+'id_desact_'+id_act).hide();
                                    cargarAvance();
                                }
                            }
                        );
                            $(this).dialog('close');
                        },
                        '<?php echo $lang_boton_cancelar; ?>': function() {
                            $(this).dialog('close');
                        }
                    },
                    close: function() {
                    }
                });
                $dialog.dialog('open');
                return false;
            }
            /*
                    Ejecuta las acciones necesarias para iniciar la ejecución de una actividad:
                    - Despliega un diálogo de "Esta seguro que desea iniciar ..."
                    - Al cerrar el diálogo aceptando, se invoca exp_comenzar_actividad.php
                    - Si no hay error, se cambian los estados/funciones de los botones:
                        - Cambiar el botón de la actividad a "Finalizar"
                        - Cambiar la función del botón "Deshacer", manteniéndolo oculto
             */
            function iniciarActividad(id_actividad,id_exp_act, nombre_actividad, id_exp_etapa, etiqueta_act, id_div_boton, bandera_publica_producto, bandera_clase_completa){
                var $link = $(this);
                var $cambiar = false;
                var id_experiencia = '<?php echo $id_experiencia; ?>';
                var contenido = '<p class=\"resaltado\"><?php echo $lang_seguro_de_comenzar_actividad1; ?>.</p>' +
                    '<p class=\"nombre_actividad\">' + nombre_actividad + '</p>' +
                    '<p><?php echo $lang_seguro_de_comenzar_actividad2; ?></p>';
                var $dialog = $('<div id=\"dialogo_cambiando_estado\">'+contenido+'</div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_comenzar_actividad; ?>',
                    width: 400,
                    height: 250,
                    modal: true,
                    buttons: {
                        '<?php echo $lang_boton_comenzar; ?>': function() {
                            $.get(
                            'exp_comenzar_actividad.php?codact='+id_actividad+'&codexpact='+id_exp_act+'&codexpetapa='+id_exp_etapa+'&etiquetaact='+etiqueta_act+'&codeexp='+id_experiencia+"&nombre_actividad="+nombre_actividad,
                            function(data){
                                if (data != '0' && data !='-1'){
                                    despliegaCuadroRecomendaciones(id_actividad,'2');
                                    v_href_com_act = "exp_comentarios_actividad.php?codact="+id_actividad+"&rol=<?php echo $rol_esta_experiencia; ?>&codexpact="+data+"&comentar=1";
                                    $('#link_v_c_act_id_'+id_actividad).attr("href",v_href_com_act);
                                    $('#'+id_div_boton).removeClass("boton_comenzar");
                                    $('#'+id_div_boton).addClass("boton_finalizar");
                                    $('#'+id_div_boton).html("<?php echo $lang_boton_finalizar; ?>");
                                    $link.unbind('click');
                                    $('#'+id_div_boton).attr('onclick','');
                                    $('#'+id_div_boton).unbind('click');
                                    $('#'+id_div_boton).bind('click', function(){
                                        finalizarActividad(id_actividad,data,nombre_actividad,id_div_boton,bandera_publica_producto, bandera_clase_completa);
                                        return false;
                                    });
                                    $('#id_desact_'+id_actividad).unbind('click');
                                    $('#id_desact_'+id_actividad).attr('onclick','');
                                    $('#id_desact_'+id_actividad).bind('click', function(){
                                        deshacerActividad(id_actividad,data,nombre_actividad,id_exp_etapa,etiqueta_act,id_div_boton,bandera_publica_producto, bandera_clase_completa);
                                        return false;
                                    });
                                    visibilidadNoComenzadas(false);
                                    $(".bda_activo").hide();
                                    cargarAvance();
                                }
                            }
                        );
                            $(this).dialog('close');
                        },
                        '<?php echo $lang_boton_cancelar; ?>': function() {
                            $(this).dialog('close');
                        }
                    },
                    close: function() {
                    }
                });
                $dialog.dialog('open');
            }
            /*
                    Ejecuta las acciones necesarias para finalizar la ejecución de una actividad:
                    - Despliega un diálogo de "Esta seguro que desea finalizar ..."
                    - Al cerrar el diálogo aceptando, se invoca exp_finalizar_actividad.php
                    - Si no hay error, se cambian los estados/funciones de los botones:
                        - Cambiar el botón de la actividad a "Finalizar"
                        - Muestra del botón "Deshacer"
             */
            // function finalizarActividad(id_actividad, id_exp_actividad,nombre_actividad,id_div_boton){
            //modificación hecha para que detecte si la actividad finalizó todos sus productos
            function finalizarActividad(id_actividad,id_exp_actividad,nombre_actividad,id_div_boton,bandera_publica_producto, bandera_clase_completa){
                var msg_adv = '';
                if(bandera_publica_producto == '1' && bandera_clase_completa != '1'){
                    //alert('<?php echo $lang_rp_clase_no_termina; ?>');
                    msg_adv = '<p class=\"texto_checkbox_rojo\"><?php echo $lang_rp_clase_no_termina; ?></p>';
                }
                //else{
                //var $link = $(this);
                //var $cambiar = false;
                var id_experiencia = '<?php echo $id_experiencia; ?>';
                var $dialog = $('<div id=\"dialogo_cambiando_estado\"></div>')
                .load('<?php echo $ruta_raiz;?>reco/rec_form_finalizar_actividad.php',{id_act:id_actividad,id_exp:id_experiencia,id_exp_act:parseInt(id_exp_actividad),id_usuario:<?php echo $id_usuario ?>,id_boton:id_div_boton,nombre_act:nombre_actividad})
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_terminar_actividad; ?>',
                    width: 500,
                    //height: 250,
                    modal: true,
                    resizable: true,
                    buttons: {
                        //ACEPTAR
                        '<?php echo $lang_boton_finalizar_actividad; ?>': function() {
                            $("#rec_form_recomienda").submit();
                            var bandera_vincula = '0';
                            if($('#checkbox_vincula:checked').val() == true){
                                var bandera_vincula = '1';
                            }
                            //ventana
                        },
                        //CANCELAR
                        '<?php echo $lang_boton_cancelar; ?>': function() {
                            $(this).dialog('close');
                            $("#dialogo_cambiando_estado").remove();
                        }
                    },
                    close: function() {
                        $("#dialogo_cambiando_estado").remove();
                    }

                });
                $dialog.dialog('open');
                //}
            }
            
            function cambiarEstadoActividad(){
                $(".boton_comenzar").unbind('click');
                $(".boton_comenzar").click(function(){
                    $(this).removeClass("boton_comenzar");
                    $(this).addClass("boton_finalizar");
                    $(this).html("<?php echo $lang_boton_finalizar; ?>");
                    visibilidadNoComenzadas(false);
                });
                $(".boton_finalizar").unbind('click');
                $(".boton_finalizar").click(function(){
                    $(this).removeClass("boton_finalizar");
                    $(this).addClass("boton_finalizada");
                    $(this).html("<?php echo $lang_boton_finalizada; ?>");
                    visibilidadNoComenzadas(true);

                });
            }
            /*
                    Invoca a exp_enviar_comentario_actividad.php haciendo POST con los elementos
                    del formulario desplegado en exp_comentarios_actividad.php
             */
            function enviarComentarioActividad(){
                url = 'exp_enviar_comentario_actividad.php?id_exp=<?php echo $id_experiencia;?>&id_diseno=<?php echo $id_diseno;?>';
                $.post(url, $("#form_comentar").serialize(), function() {
                    $("#pcomact_texto").val("");
                    $(".comentario_actividad_exitoso").html("<div><?php echo $lang_gracias_por_tu_comentario; ?></div>");
                });
                //leer();
                return false;
            }

            function leer(){
            url = 'exp_comentarios_actividad.php?codact=144&rol=1&codexpact=1146&coddiseno=17&codexp=118';
            $.get(url,function(data){
                $("#contenido_actividad").html(data);
            });
            return false;
            }

           
<?php
    }
?>
            $(document).ready(function(){
                <?php if(($_SESSION["klwn_inscribe_diseno"] == 1 AND ($rol_esta_experiencia == 1 OR $rol_esta_experiencia == 3)) OR ($_SESSION["klwn_inscribe_diseno"] == 0 AND ($rol_esta_experiencia == 1 OR $rol_esta_experiencia == 3))){ ?>
                    despliegaCuadroRecomendaciones('<?php echo $id_act?>','<?php echo $estado;?>');
                    recIniciarCuadroRec();
                <?php
                }
                ?>
                detenerBitacoraNM();
                detenerBitacoraCompartidaNM();
               
                detenerMuralDisenoNM();
                $('#ficha_curriculares_titulo_a').hide();
                $('#ficha_curriculares_c').hide();
                $('#ficha_transversales_titulo_a').hide();
                $('#ficha_transversales_c').hide();
                $('#ficha_contenidos_titulo_a').hide();
                $('#ficha_contenidos_c').hide();
                
                $('#ficha_contenidos_titulo_c').click(function(){
                    $('#ficha_contenidos_c').slideDown();
                    $('#ficha_contenidos_titulo_c').hide();
                    $('#ficha_contenidos_titulo_a').show();
                });
                $('#ficha_contenidos_titulo_a').click(function(){
                    $('#ficha_contenidos_c').slideUp();
                    $('#ficha_contenidos_titulo_a').hide();
                    $('#ficha_contenidos_titulo_c').show();
                });
                

//                
                $('#ficha_curriculares_titulo_c').click(function(){
                    $('#ficha_curriculares_c').slideDown();
                    $('#ficha_curriculares_titulo_c').hide();
                    $('#ficha_curriculares_titulo_a').show();
                });
                $('#ficha_curriculares_titulo_a').click(function(){
                    $('#ficha_curriculares_c').slideUp();
                    $('#ficha_curriculares_titulo_a').hide();
                    $('#ficha_curriculares_titulo_c').show();
                });
                $('#ficha_transversales_titulo_c').click(function(){
                    $('#ficha_transversales_c').slideDown();
                    $('#ficha_transversales_titulo_c').hide();
                    $('#ficha_transversales_titulo_a').show();
                });
                $('#ficha_transversales_titulo_a').click(function(){
                    $('#ficha_transversales_c').slideUp();
                    $('#ficha_transversales_titulo_a').hide();
                    $('#ficha_transversales_titulo_c').show();
                });
                $('.finalizar_experiencia').click(function(){

            });
<?php
    if ($experiencia_finalizada) {
?>
                $('#boton_finalizar_exp').removeClass("finalizar_experiencia");
                $('#boton_finalizar_exp').addClass("finalizar_experiencia_disabled");
                $('#boton_finalizar_exp').attr('disabled', true);
<?php
    }
?>
<?php
    if ($existe_act_comenzada && !$es_estudiante && !$es_observador) {
?>
                visibilidadNoComenzadas(false);
                $(".bda_activo").hide();
<?php
    }
?>
            /*
               oculta todos los elementos de la clase bda_inactivo que es asignada a
               botones deshacer actividad
             */
            $(".bda_inactivo").hide();
            $("#accordion").accordion({
                header: "h3",
                collapsible: true,
                active: -1,
                autoHeight: false,
                navigation: true
            });
            /*
                Redefine el click de los enlaces con clase link_ventana_actividad
                para que desplieguen una diálogo JQueryUI con el contenido del script
                enlazado exp_actividad.php
             */
            $('.link_ventana_actividad').each(function() {
                var $link = $(this);
                $link.click(function() {
                    var $dialog = $('<div></div>')
                    .load($link.attr('href'))
                    .dialog({
                        autoOpen: false,
                        title: $link.attr('title'),
                        width: 600,
                        height: 450,
                        modal: true
                    });
                    $dialog.dialog('open');
                    return false;
                });
            });
            /*
                Redefine el click de los enlaces con clase link_ventana_comentarios_actividad
                para que desplieguen una diálogo JQueryUI con el contenido del div
                #contenido_actividad del script enlazado exp_comentarios_actividad.php.
             */
            $('.link_ventana_comentarios_actividad').each(function() {
                var $linkc = $(this);
                $linkc.click(function() {
                    var $dialog = $('<div></div>')
                    .load($linkc.attr('href') + ' #contenido_actividad')
                    .dialog({
                        autoOpen: false,
                        title: $linkc.attr('title'),
                        width: 600,
                        height: 450,
                        modal: true,
                        close: function(ev, ui) {
                            $(this).remove();
                        }
                    });
                    $dialog.dialog('open');
                    return false;
                });
            });
<?php
    /*
      Inicializa cada elemento con id #ventana_etapa_X como un diálogo JQueryUI.
      Cada enlace asociado, #link_ventana_etapa_X, levanta el diálogo correspondiente.
     */
?>		
});
</script>
