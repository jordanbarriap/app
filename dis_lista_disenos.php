<?php
/**
 * Despliega la información correspodiente a los Diseños Didáctico existente actualmente,
 * entre estos se encuentra informacion tecnica, testimonios sobre los DD (3 testimonios al azar),
 * documentos asociados a cada actividad, de cada etapa del DD, y los comentarios respectivos a cada
 * actividad por parte de los usuarios.
 *
 * Utiliza las funciones:
 *      dbDisObtenerDisenosSubsector
 *      dbDisObtenerComentariosAleatorios
 *      dbDisObtenerEtapas
 *      dbDisObtenerActividadesE
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  José Carrasco - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])
    )header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

$titulo_pagina = $lang_sufijo_titulo_paginas . $lang_experiencia_didactica;
$descripcion_pagina = $lang_descripcion_pagina_experiencia_didactica;

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$ncom = 0;
?>
<div class="intro" style="margin-left:10px;">
<?php echo $lang_pestana_dd_intro;?>
</div>
<div class="contenido">
    <div id="lista_disenos" style="margin-left:10px;">
        <?php
        if ($error == 0) {
            echo "<div id=\"accordion\">\n\r";

            /* Cada diseño didactico es un acordeón */
            $ndisenos = 1;
            foreach ($_sectores as $_sec) {
                $subsector = $_sec["valor"];
                $titulo_subsector = $lang_le_subsector . " " . $_sec["nombre"];
                $_disenosdidacticos = dbDisObtenerDisenosSubsector($conexion, $subsector);
                if (count($_disenosdidacticos)>0) {
                    echo "<p class=\"titulo_subsector\">" . $titulo_subsector . "</p>";
                
                foreach ($_disenosdidacticos as $diseno) {
                    $_comentarios = dbDisObtenerComentariosAleatorios($conexion, $diseno["id_dd"]);
        ?>
                    <div>
                        <h3>
                            <a href="#">
                    <?php echo $lang_diseno . " " . $diseno["nivel"] . ": " . $diseno["nombre_dd"]; ?>
                    <?php
                        if(!is_null($diseno["herramienta_nombre"])){
                    ?>
                        <img src="<?php echo $config_ruta_img_herramientas.$diseno["herramienta_imagen"];?>" alt="<?php echo $diseno["herramienta_nombre"];?>" title="<?php echo $diseno["herramienta_nombre"];?>"></img>
                    <?php
                    }
                    else{
                    ?>
                        <img src="<?php echo $config_ruta_img_herramientas."no_herramienta.png";?>" alt="<?php echo $lang_plataforma_kelluwen;?>" title="<?php echo $lang_plataforma_kelluwen;?>"></img>
                    <?php
                    }
                    $mes = substr($diseno["fecha_creacion"], 5, 2);
                    $anio = substr($diseno["fecha_creacion"], 0, 4);
                    if ($mes > 6 && $anio == 2011) {
                    ?>
                        <label class="nuevo">&nbsp;&nbsp;&nbsp;¡<?php echo $lang_dis_lista_dis_nuevo; ?></label>
<?php } ?>
                </a>
            </h3>
            <div class="info_etapa_ed">
                <div class="contenido_izquierda_ed">
                    <p><?php echo $diseno["descripcion_dd"]; ?></p>
                    <?php
                    $_etapas = dbDisObtenerEtapas($conexion, $diseno["id_dd"]);
                    $netap = 1;
                    foreach ($_etapas as $etapa) {
                        if($netap == 1){$nombre_etapa = $lang_motivacion;}
                        if($netap == 2){$nombre_etapa = $lang_creacion;}
                        if($netap == 3){$nombre_etapa = $lang_evaluacion;}
                    ?>
                        <div class="lista_actividades_ed">
                            <div class="t_etapa_ed">
                                <div class="titulo_lista_actividades_etapa">
                                    <?php echo $lang_etapa . " " . $netap . " : " . $nombre_etapa; ?>
                            </div>
                            <div class="descripcion_etapa"><?php echo $diseno["des_etapa" . $netap]; ?></div>
                            <table class="t_lista_actividades_ed">
                                <tr>
                                    <td class="titulo_lista_actividades_ed tla1"><?php echo $lang_dis_lista_dis_actividades; ?></td>
                                    <td class="titulo_lista_actividades_ed tla2"><?php echo $lang_materiales; ?></td>
<!--                                    <td class="titulo_lista_actividades_ed tla3"><?php //echo $lang_comentarios; ?></td>-->
                                </tr>
                                <?php
                                $_actividades = dbDisObtenerActividades($conexion, $etapa["id_e"]);
                                $impar = true;
                                foreach ($_actividades as $actividad) {
                                    if ($impar) {
                                        $back_class = "filaimpar";
                                        $impar = false;
                                    } else {
                                        $back_class = "filapar";
                                        $impar = true;
                                    }
                                ?>
                                    <tr>
                                        <td class="tla1 actividad <?php echo $back_class; ?>">
                                        <a class="link_ventana_actividad" href="exp_actividad.php?codact=<?php echo $actividad["id_a"]; ?>&id_dd=<?php echo $diseno["id_dd"]; ?>" title="<?php echo $actividad["nombre_a"]; ?>">
                                            <?php
                                            echo $actividad["nombre_a"];

                                            if ($actividad["tipo"] == 2) {
                                            ?>
                                                <img  src="img/laboratorio.png" title="<?php echo $lang_act_lab; ?>" >
<?php
                                            }
?>
                                        </a>
                                    </td>
                                    <td class="tla2 actividad <?php echo $back_class; ?>">
                                        <a id="link_v_c_a_id_<?php echo $actividad["id_actividad"]; ?>" class="link_ventana_documentos_actividad" href="dis_doc_actividad.php?codact=<?php echo $actividad["id_a"]; ?>" title="<?php echo $lang_tab_documentos; ?> - <?php echo $actividad["nombre_a"]; ?>">
                                            <img  src="img/documentos_16.png" alt="<?php echo $lang_tab_documentos; ?>">
                                        </a>
                                    </td>
<!--                                    <td class="tla3 actividad <?php// echo $back_class; ?>">
                                        <a id="link_v_c_a_id_<?php //echo $actividad["id_a"]; ?>" class="link_ventana_comentarios_actividad" href="exp_comentarios_actividad.php?codact=<?php echo $actividad["id_a"]; ?>"  title="<?php echo $lang_comentarios; ?> - <?php echo $actividad["nombre_a"]; ?>">
                                            <img  src="img/comentarios_16.png" alt="<?php //echo $lang_comentarios; ?>">
                                        </a>
                                    </td>-->
                                </tr>
<?php } ?>
                                    </table>
                                </div>
                            </div>
<?php
                                        $netap+=1;
                                    }
?>
                                </div> <!--contenido izquierda-->
                                <div class="contenido_derecha_ed" >
                                    <div class="ficha_tecnica_ed">
                                        <table class="t_ficha_tecnica_ed">
                                            <tr>
                                                <td class="titulo_ficha_tecnica_ed"><?php echo $lang_ficha_tecnica ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?php echo $lang_le_subsector; ?>:</b> <?php echo $_sec["nombre"]; ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?php echo $lang_nivel; ?>:</b> <?php echo $diseno["nivel"]; ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?php echo $lang_ficha_tecnica_aprendizajes; ?>:</b> <?php echo $lang_ficha_tecnica_aprendizajes_texto; ?></td>
                                            </tr>
                                            <?php 
                                            if (!is_null($diseno["obj_curriculares"])) {
                                            ?>
                                                <tr class="ft_img_objc_c click" name="<?php echo $diseno["id_dd"]; ?>" id="ft_img_objc_c<?php echo $diseno["id_dd"]; ?>" >
                                                    <td>
                                                        <img  src="img/flecha_c.png"></img>
                                                        <b><?php echo $lang_objetivos_c; ?></b>
                                                    </td>
                                                </tr>
                                                <tr class="ft_img_objc_a click"  id="ft_img_objc_a<?php echo $diseno["id_dd"]; ?>" name="<?php echo $diseno["id_dd"]; ?>" style="display:none">
                                                    <td>
                                                        <img src="img/flecha_a.png"></img>
                                                        <b><?php echo $lang_objetivos_c; ?></b>
                                                        <div id="ft_objetivos_c">
                                                            <?php echo $diseno["obj_curriculares"];?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            if (!is_null($diseno["obj_transversales"])) {
                                            ?>
                                                <tr class="ft_img_objt_c click" id="ft_img_objt_c<?php echo $diseno["id_dd"]; ?>" name="<?php echo $diseno["id_dd"]; ?>">
                                                    <td  >
                                                        <img src="img/flecha_c.png"></img>
                                                        <b><?php echo $lang_objetivos_t; ?></b>
                                                    </td>
                                                </tr>
                                                <tr class="ft_img_objt_a click" id="ft_img_objt_a<?php echo $diseno["id_dd"]; ?>" name="<?php echo $diseno["id_dd"]; ?>" style="display:none">
                                                    <td >
                                                        <img  src="img/flecha_a.png"></img>
                                                        <b><?php echo $lang_objetivos_t; ?></b>
                                                        <div id="ft_objetivos_t">
                                                            <?php echo $diseno["obj_transversales"];?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            if (!is_null($diseno["contenidos"])) {
                                            ?>
                                                <tr class="ft_img_cont_c click" id="ft_img_cont_c<?php echo $diseno["id_dd"]; ?>" name="<?php echo $diseno["id_dd"]; ?>">
                                                    <td>
                                                        <img  src="img/flecha_c.png"></img>
                                                        <b><?php echo $lang_contenidos; ?></b>
                                                    </td>
                                                </tr>
                                                <tr class="ft_img_cont_a click"  id="ft_img_cont_a<?php echo $diseno["id_dd"]; ?>" name="<?php echo $diseno["id_dd"]; ?>" style="display:none">
                                                    <td>
                                                        <img  src="img/flecha_a.png"></img>
                                                        <b><?php echo $lang_contenidos; ?></b>
                                                        <div id="ft_contenidos">
                                                                <?php echo $diseno["contenidos"];?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            } 
                                            ?>                                           
                            <tr>
                                <td class="ultima_fila">
                                    <b><?php echo $lang_herramienta; ?>:</b>
                                    <?php
                                    if(!is_null($diseno["herramienta_nombre"])){
                                    ?>
                                        <a href="<?php echo $diseno["herramienta_enlace"]; ?>"target="_blank" title="<?php echo $diseno["herramienta_nombre"]; ?>">
                                            <img src="<?php echo $config_ruta_img_herramientas . $diseno["herramienta_imagen"]; ?>"></img>
                                        </a>
                                    <?php
                                    }
                                    else{
                                    ?>
                                        <img src="<?php echo $config_ruta_img_herramientas."no_herramienta.png";?>" alt="<?php echo $lang_plataforma_kelluwen;?>" title="<?php echo $lang_plataforma_kelluwen;?>"></img>
                                    <?php
                                    }
                                    ?>    
                                                </td>
                                            </tr>

                                        </table>
                                    </div>

                                    <div class="testimonios_ed">
                                        <div class="titulo_lista_testimonios_ed" ><?php echo $lang_profesores_ejecutaron; ?></div>


                        <?php
                                        $_profesores_ejecutaron = dbDisObtenerProfesoresEjecutaron($diseno["id_dd"], $conexion);
                                        $n_profesores = count($_profesores_ejecutaron);
                                        $grupos_profesores[$ndisenos] = count($_profesores_ejecutaron) / 3;
                                        $j = 0;
                                        if (is_null($_profesores_ejecutaron)) {
                                            echo "<div class= \"no_profesores\">" . $lang_no_profesores_ejecutaron . "</div>";
                                        }
                                        $_cont[$ndisenos] = 0;
                                        while ($_profesores_ejecutaron[$j]) {
                                            $_cont[$ndisenos]++;
                                            $imagen_usuario = darFormatoImagen($_profesores_ejecutaron[$j]["imagen"], $config_ruta_img_perfil, $config_ruta_img);
                                            $class = intval($j / 3);
                                            $resto = $j % 3;
                                            if ($class < 1) {
                                                $class = "";
                                            }
                                            if ($j > 0) {
                        ?>
                                                <div class="info<?php echo $ndisenos . $class . " profesor" . $ndisenos . $_cont[$ndisenos]; ?>" >
                                                    <img class="dis_imagen_profesor_p" src="<?php echo $imagen_usuario["imagen_usuario"]; ?>" />
                                                    <div class="dis_datos_profesores">
                                                        <p class="dis_datos_profesor">
                                                            <?php echo ucwords(utf8_strtolower($_profesores_ejecutaron[$j]["nombre"])); ?>
                                                        </p>
                                                        <p>
                                                            <?php if ($_profesores_ejecutaron[$j]["establecimiento"] == "") {
                                                    echo $lang_mural_diseno_sin_informacion;
                                                } else {
                                                    echo ucwords(utf8_strtolower($_profesores_ejecutaron[$j]["establecimiento"]));
                                                } ?>
                                            </p>
                                            <p>
                                                </b> <?php if ($_profesores_ejecutaron[$j]["localidad"] == "") {
                                                    echo $lang_mural_diseno_sin_informacion;
                                                } else {
                                                    echo ucwords(utf8_strtolower($_profesores_ejecutaron[$j]["localidad"]));
                                                } ?>
                                                <a class="dis_ver_perfil" id="dis_ver_perfil<?php echo $_profesores_ejecutaron[$j]["id_usuario"]; ?>" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_profesores_ejecutaron[$j]["usuario"]; ?>"><?php echo $lang_ver_perfil; ?></a>
                                            </p>
                                        </div>
                                    </div>
                        <?php
                                            } else {
                        ?>

                                                <div class="info<?php echo $ndisenos . $class; ?>">
                                                    <img class="dis_imagen_profesor_p" src="<?php echo $imagen_usuario["imagen_usuario"]; ?>" />
                                                    <div class="dis_datos_profesores">
                                                        <p class="dis_datos_profesor">
                                                            <?php echo ucwords(utf8_strtolower($_profesores_ejecutaron[$j]["nombre"])); ?>
                                                        </p>
                                                        <p>
                                                            <?php if ($_profesores_ejecutaron[$j]["establecimiento"] == "") {
                                                    echo $lang_mural_diseno_sin_informacion;
                                                } else {
                                                    echo ucwords(utf8_strtolower($_profesores_ejecutaron[$j]["establecimiento"]));
                                                } ?>
                                                                </p>
                                                                <p>
                                                                    </b> <?php if ($_profesores_ejecutaron[$j]["localidad"] == "") {
                                                    echo $lang_mural_diseno_sin_informacion;
                                                } else {
                                                    echo ucwords(utf8_strtolower($_profesores_ejecutaron[$j]["localidad"]));
                                                } ?>
                                                                            <a class="dis_ver_perfil" id="dis_ver_perfil<?php echo $_profesores_ejecutaron[$j]["id_usuario"]; ?>" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_profesores_ejecutaron[$j]["usuario"]; ?>"><?php echo $lang_ver_perfil; ?></a>
                                                                        </p>
                                                                    </div>
                                                                </div>

                                            <?php
                                            }
                                            $j++;
                                            if ($resto == 0 && $class > 0) {
                                            ?>
                                                <tr><td><button class="dis_boton_ver_mas" id="dis_ver_mas<?php echo $ndisenos . $class; ?>"><?php echo $lang_mural_diseno_ver_mas; ?></button></td></tr>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div><!--contenido derecha-->
                                <div class="clear"></div>
                                <?php if ($_SESSION["klwn_inscribe_diseno"] == 1) { ?>
                                    <input type="button" class="inscribir_experiencia" href="<?php echo $diseno["id_dd"]; ?>" title="<?php echo $diseno["nombre_dd"]; ?>" value="<?php echo $lang_input_inscribir ;?>">
                                    <?php } else { ?>
                                    <input type="button" class="solicitar_inscripcion" href="<?php echo $diseno["id_dd"]; ?>" title="<?php echo $diseno["nombre_dd"]; ?>" value="<?php echo $lang_input_solicitar;?>">
                                    <?php } ?>
                            </div>
                        </div>
                        <?php
                                        $ndisenos++;
                                    }
            }
                                }
                ?>
            </div>
<?php
                            }
                            dbDesconectarMySQL($conexion);
?>
    </div>
    <div id="form_inscribir_experiencia" title="<?php echo $diseno["nombre_dd"]; ?>">
        <form id="form_registro_dis" name="form_registro_dis" method="post" action="" >
            <table class="caja_form_registro">
                <tr>
                    <td>
                        <label class="etiqueta_campo">
                            <?php echo $lang_colegio . " :"; ?>
                        </label>
                    </td>
                    <td>
                        <input tabindex="1" type="text" maxlenght="20" class="caja_texto"  size="15" id="fr_campo_colegio" name="fr_campo_colegio" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="etiqueta_campo">
                            <?php echo $lang_curso . " :"; ?>
                        </label>
                    </td>
                    <td>
                        <input tabindex ="2" type="text" maxlenght="20" class="caja_texto" size="20" id="fr_campo_curso" name="fr_campo_curso" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="etiqueta_campo">
                            <?php echo $lang_registro_localidad . " :"; ?>
                    </label>
                </td>
                <td>
                    <input tabindex ="3" type="text" maxlenght="20" class="caja_texto" size="20" id="fr_campo_localidad" name="fr_campo_localidad" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class="etiqueta_campo">
                        <?php echo $lang_terminos_condiciones . " :"; ?>
                    </label>
                </td>
                <td>
                    <textarea rows="10" cols="30" readonly="readonly" class="caja_texto_area"><?php echo $lang_los_terminos_condiciones; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>&nbsp;
                </td>
                <td>
                    <label id="acepto" for="check_acepto">
                        <input tabindex ="4" type="checkbox" id="check_acepto"  name="check_acepto"  />
                        <span id="terminos">
                            <?php echo $lang_acepto_terminos; ?>
                        </span>
                    </label>
                    <br>
                </td>
            </tr>
        </table>
    </form>
    <div id="contenido_inscrito">
        <div id="intro_inscrito" ><?php echo $lang_felicitaciones_inscribir_d; ?></div>
        <div id="intro_inscrito_msj" ><?php echo $lang_comparta_codigo_estudiantes; ?></div>
        <div id="caja_codigo"></div>
    </div>
</div>
<div id="form_solicitar_inscripcion">
    <form id="form_solicita_insc" name="form_solicita_insc" method="post" action="" >
        <table class="caja_form_solicita">
            <tr><td colspan="2">
                    <p>
                        <?php echo $lang_form_solicitar_inscribir;?>
                    </p>
                </td>
            </tr>
            <tr>
                <td><label class="etiqueta_campo"><?php echo $lang_form_solicitar_inscribir_nombre.": ";?></label></td>
                <td><input id="fsi_campo_nombre" name="fsi_campo_nombre" type="text" class="caja_texto" /></td>
            </tr>
            <tr><td><label class="etiqueta_campo"><?php echo $lang_form_solicitar_inscribir_email.": ";?></label></td>
                <td><input id="fsi_campo_email" name="fsi_campo_email" type="text" class="caja_texto" /></td>
            </tr>
            <tr><td><label class="etiqueta_campo"><?php echo $lang_form_solicitar_inscribir_telefono.": ";?></label></td>
                <td><input id="fsi_campo_telefono" name="fsi_campo_telefono" type="text" class="caja_texto telefono" /></td>
            </tr>
            <tr><td><label class="etiqueta_campo"><?php echo $lang_form_solicitar_inscribir_establecimiento.": ";?></label></td>
                <td><input id="fsi_campo_establecimiento" name="fsi_campo_establecimiento"type="text" class="caja_texto" /></td>
            </tr>
            <tr><td><label class="etiqueta_campo"><?php echo $lang_form_solicitar_inscribir_comuna.": ";?></label></td>
                <td><input id="fsi_campo_comuna" name="fsi_campo_comuna" type="text" class="caja_texto" /></td>
            </tr>
        </table>
    </form>
    <div id="contenido_inscrito_2">
        <div id="prueba"></div>
    </div>
</div>
<script type="text/javascript">
    var $id_diseno;
    var $nombre_diseno;
    $(document).ready(function(){
        $('table tr.ft_img_objc_c').click(function() {
            var element = $(this);
            var I = element.attr("name");
            $("table tr#ft_img_objc_a"+I).show();
            $("table tr#ft_img_objc_c"+I).hide();
        });
        $('table tr.ft_img_objc_a').click(function() {
            var element = $(this);
            var I = element.attr("name");
            $("table tr#ft_img_objc_c"+I).show();
            $("table tr#ft_img_objc_a"+I).hide();
        });
        $('table tr.ft_img_objt_c').click(function() {
            var element = $(this);
            var I = element.attr("name");
            $("table tr#ft_img_objt_a"+I).show();
            $("table tr#ft_img_objt_c"+I).hide();
        });
        $('table tr.ft_img_objt_a').click(function() {
            var element = $(this);
            var I = element.attr("name");
            $("table tr#ft_img_objt_c"+I).show();
            $("table tr#ft_img_objt_a"+I).hide();
        });
        $('table tr.ft_img_cont_c').click(function() {
            var element = $(this);
            var I = element.attr("name");
            $("table tr#ft_img_cont_a"+I).show();
            $("table tr#ft_img_cont_c"+I).hide();
        });
        $('table tr.ft_img_cont_a').click(function() {
            var element = $(this);
            var I = element.attr("name");
            $("table tr#ft_img_cont_c"+I).show();
            $("table tr#ft_img_cont_a"+I).hide();
        });
<?php
                            $i = 1;
                            while ($i < $ndisenos) {
?>
                    $('.info<?php echo $i; ?>').addClass("dis_fondo_prof_ejecutando");
<?php
                                $i++;
                            }
?>
                jQuery.fn.reset = function () {
                    $(this).each (function() { this.reset(); });
                }
                $('#scrollbar_dis').tinyscrollbar();
                $("div.invisible").hide();
                $("#ver").click(function (){
                    $(".invisible").slideDown("slow");
                });
                //Autocompletación de los campos establecimiento y comuna
                $("#fr_campo_colegio").autocomplete(establecimientos, {
                    width: 258,
                    max: 4,
                    highlight: false,
                    multiple: true,
                    multipleSeparator:"",
                    scroll: true,
                    scrollHeight: 300
                });
                $("#fsi_campo_establecimiento").autocomplete(establecimientos, {
                    width: 258,
                    max: 4,
                    highlight: false,
                    multiple: true,
                    multipleSeparator:"",
                    scroll: true,
                    scrollHeight: 300
                });

                $("#fr_campo_localidad").autocomplete(comunas, {
                    width: 258,
                    max: 4,
                    highlight: false,
                    multiple: true,
                    multipleSeparator:"",
                    scroll: true,
                    scrollHeight: 300
                });
                $("#fsi_campo_comuna").autocomplete(comunas, {
                    width: 258,
                    max: 4,
                    highlight: false,
                    multiple: true,
                    multipleSeparator:"",
                    scroll: true,
                    scrollHeight: 300
                });
                $.validator.addMethod("telefono", function(value, element) {
                    return this.optional(element) || /^[(|)|+|-| |0-9]+$/i.test(value);
                }, "<?php echo $lang_dis_lista_dis_ingrese_numero; ?>");

                $("#accordion").accordion({
                    header: "h3",
                    collapsible: true,
                    active: -1,
                    autoHeight: false,
                    navigation: true
                });
<?php
                            $i = 0;
                            while ($i < $ndisenos) {
                                $num_grupos_profesores = 1;
                                $n = $i + 1;
?>     
                            $('.<?php echo "profesor" . $n . $_cont[$n]; ?>').addClass('ultima_fila');
<?php
                                while ($num_grupos_profesores < $grupos_profesores[$i]) {
?>
                                    $("#dis_ver_mas<?php echo $i . $num_grupos_profesores + 1; ?>").hide();
                                    $('.info<?php echo $i . $num_grupos_profesores; ?>').hide();
                                    $('#dis_ver_mas<?php echo $i . $num_grupos_profesores; ?>').click(function(){
                                        $('.info<?php echo $i . $num_grupos_profesores; ?>').addClass('dis_fondo_prof_ejecutando');
                                        $('.info<?php echo $i . $num_grupos_profesores; ?>').show();
                                        $('#dis_ver_mas<?php echo $i . $num_grupos_profesores; ?>').hide();
                                        $('#dis_ver_mas<?php echo $i . $num_grupos_profesores + 1; ?>').show();
                                    });
<?php
                                    $num_grupos_profesores++;
                                }
                                $i++;
                            }
?>
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
                    $('#form_solicitar_inscripcion').dialog({
                        title: '<?php echo $lang_dis_lista_dis_ejecucion_dd; ?>',
                        autoOpen: false,
                        width: 510,
                        height: 340,
                        modal: true,
                        dialogClass:'dialogBotones',
                        buttons: {
                            "<?php echo $lang_dis_lista_dis_enviar; ?>": function() {
                                $("#form_solicita_insc").submit();
                            },
                            "<?php echo $lang_dis_lista_dis_cancelar; ?>": function() {
                                $(this).dialog("close");
                                $("#fsi_campo_nombre").removeClass("error");
                                $("#fsi_campo_email").removeClass("error");
                                $("#fsi_campo_telefono").removeClass("error");
                                $("#fsi_campo_comuna").removeClass("error");
                                $("#fsi_campo_establecimiento").removeClass("error");
                                $("label.error").hide();
                                $("#form_solicita_insc").reset();
                                $("#form_solicita_insc").show();

                            },
                            "<?php echo $lang_dis_lista_dis_aceptar; ?>":function(){
                                $(this).dialog("close");
                                $("#fsi_campo_nombre").removeClass("error");
                                $("#fsi_campo_email").removeClass("error");
                                $("#fsi_campo_telefono").removeClass("error");
                                $("#fsi_campo_comuna").removeClass("error");
                                $("#fsi_campo_establecimiento").removeClass("error");
                                $("label.error").hide();
                                $("#form_solicita_insc").reset();
                                $("#form_solicita_insc").show();
                                $("div.dialogBotones div button:nth-child(1)").show();
                                $("div.dialogBotones div button:nth-child(2)").show();
                                $("div.dialogBotones div button:nth-child(3)").hide();
                            }
                        },
                        close: function() {
                            $("#fr_campo_colegio").removeClass("error");
                            $("#fr_campo_curso").removeClass("error");
                            $("#fr_campo_localidad").removeClass("error");
                            $("#check_acepto").removeClass("error");
                            $("label.error").hide();
                            $("#form_registro_dis").reset();
                            $("#form_registro_dis").show();
                        }
                    });
                    $('#form_inscribir_experiencia').dialog({
                        title: '<?php echo $lang_dis_lista_dis_iniciar_ed; ?>',
                        autoOpen: false,
                        width: 510,
                        height: 450,
                        modal: true,
                        dialogClass:'dialogBotones',
                        buttons: {
                            "<?php echo $lang_dis_lista_dis_enviar; ?>": function() {
                                $("#form_registro_dis").submit();
                            },
                            "<?php echo $lang_dis_lista_dis_cancelar; ?>": function() {
                                $(this).dialog("close");
                                $("#fr_campo_colegio").removeClass("error");
                                $("#fr_campo_curso").removeClass("error");
                                $("#fr_campo_localidad").removeClass("error");
                                $("#check_acepto").removeClass("error");
                                $("label.error").hide();
                                $("#form_registro_dis").reset();
                                $("#form_registro_dis").show();

                            },
                            "<?php echo $lang_dis_lista_dis_aceptar; ?>":function(){
                                $(this).dialog("close");
                                $("#fr_campo_colegio").removeClass("error");
                                $("#fr_campo_curso").removeClass("error");
                                $("#fr_campo_localidad").removeClass("error");
                                $("#check_acepto").removeClass("error");
                                $("label.error").hide();
                                $("#form_registro_dis").reset();
                                $("#form_registro_dis").show();
                                $("div.dialogBotones div button:nth-child(1)").show();
                                $("div.dialogBotones div button:nth-child(2)").show();
                                $("div.dialogBotones div button:nth-child(3)").hide();
                            }
                        },
                        close: function() {
                            $("#fr_campo_colegio").removeClass("error");
                            $("#fr_campo_curso").removeClass("error");
                            $("#fr_campo_localidad").removeClass("error");
                            $("#check_acepto").removeClass("error");
                            $("label.error").hide();
                            $("#form_registro_dis").reset();
                            $("#form_registro_dis").show();
                        }
                    });
                    $('.solicitar_inscripcion').click(function() {
                        $("#contenido_inscrito_2").hide();
                        $('#form_solicitar_inscripcion').dialog('open');
                        $id_diseno = $(this).attr('href');
                        $("div.dialogBotones div button:nth-child(1)").show();
                        $("div.dialogBotones div button:nth-child(2)").show();
                        $("div.dialogBotones div button:nth-child(3)").hide();
                    });
                    $("#form_registro_dis").validate({
                        rules:{
                            fr_campo_colegio:{
                                required:true,
                                minlength:3
                            },
                            fr_campo_curso:{
                                required:true,
                                minlength:3
                            },
                            fr_campo_localidad:{
                                required:true,
                                minlength:3
                            },
                            check_acepto:"required"
                        },
                        messages:{
                            fr_campo_colegio: {required:"<?php echo $lang_form_establecimiento_requerido; ?>",
                                minlength:"<?php echo $lang_form_establecimiento_caracteres; ?>"
                            },
                            fr_campo_curso:{required:"<?php echo $lang_form_curso_requerido; ?>",
                                minlength:"<?php echo $lang_form_curso_caracteres; ?>"
                            }
                            ,
                            fr_campo_localidad:{required:"<?php echo $lang_form_comuna_requerido; ?>",
                                minlength:"<?php echo $lang_form_comuna_caracteres; ?>"
                            },
                            check_acepto:"<?php echo $lang_form_terminos_requerido; ?>"
                        },
                        submitHandler: function() {
                            var $id_usuario_inscribir = <?php echo $_SESSION["klwn_id_usuario"]; ?> ;
                            url = 'dis_ingresa_diseno.php?user='+$id_usuario_inscribir+"&dd="+$id_diseno+"&iexp=1";
                            $.post(url, $("#form_registro_dis").serialize(), function(data) {
                                $("#form_registro_dis").reset();
                                if (data=="0"){
                                    alert("<?php echo $lang_dis_lista_dis_mal_insertado; ?>");
                                    window.location.replace("ingresar.php");
                                }
                                else{
                                    $("#form_registro_dis").hide();
                                    $("#caja_codigo").html(data);
                                    $("#contenido_inscrito").show();
                                    $("div.dialogBotones div button:nth-child(1)").hide();
                                    $("div.dialogBotones div button:nth-child(2)").hide();
                                    $("div.dialogBotones div button:nth-child(3)").show();
                                }
                            });
                        }
                    });
                    $("#form_solicita_insc").validate({
                        rules:{
                            fsi_campo_nombre:{
                                required:true,
                                minlength:3
                            },
                            fsi_campo_email:{
                                required:true,
                                minlength:3,
                                email:true
                            },
                            fsi_campo_telefono:{
                                required:true,
                                minlength:3
                            },
                            fsi_campo_establecimiento:{
                                required:true,
                                minlength:3
                            },
                            fsi_campo_comuna:{
                                required:true,
                                minlength:3
                            }
                        },
                        messages:{
                            fsi_campo_nombre: {
                                required:"<?php echo $lang_form_solicitar_inscribir_nombre_required ;?>",
                                minlength:"<?php echo $lang_form_solicitar_inscribir_nombre_minlength; ?>"
                            },
                            fsi_campo_email:{
                                required:"<?php echo $lang_form_solicitar_inscribir_email_required;?>",
                                minlength:"<?php echo $lang_form_solicitar_inscribir_email_minlength;?>",
                                email:"<?php echo $lang_form_solicitar_inscribir_email_valido;?>"
                            },
                            fsi_campo_telefono:{
                                required:"<?php echo $lang_form_solicitar_inscribir_telefono_required;?>",
                                minlength:"<?php echo $lang_form_solicitar_inscribir_telefono_minlength;?>"
                            },
                            fsi_campo_establecimiento:{
                                required:"<?php echo $lang_form_solicitar_inscribir_establecimiento_required ;?>",
                                minlength:"<?php echo $lang_form_solicitar_inscribir_establecimiento_minlength ;?>"
                            },
                            fsi_campo_comuna:{
                                required:"<?php echo $lang_form_solicitar_inscribir_comuna_required;?>",
                                minlength:"<?php echo $lang_form_solicitar_inscribir_comuna_minlength;?>"
                            }
                        },
                        submitHandler: function() {
                            var $id_usuario_inscribir = <?php echo $_SESSION["klwn_id_usuario"]; ?> ;
                            url = 'dis_ingresa_diseno.php?user='+$id_usuario_inscribir+"&dd="+$id_diseno+"&iexp=0";
                            $.post(url, $("#form_solicita_insc").serialize(), function(data) {
                                $("#form_solicita_insc").reset();
                                if (data=="0"){
                                    alert("<?php echo $lang_dis_lista_dis_mal_insertado; ?>");
                                    window.location.replace("ingresar.php");
                                }
                                else{
                                    $("#form_solicita_insc").hide();
                                    $("#prueba").html(data);
                                    $("#contenido_inscrito_2").show();
                                    $("div.dialogBotones div button:nth-child(1)").hide();
                                    $("div.dialogBotones div button:nth-child(2)").hide();
                                    $("div.dialogBotones div button:nth-child(3)").show();
                                }
                            });
                        }
                    });
                    $('.dis_ver_perfil').click(function() {
                        var $linkc = $(this);
                        var $dialog = $('<div></div>')
                        .load($linkc.attr('href'))
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_mural_diseno_ventana_perfil; ?>',
                            width: 900,
                            height: 600,
                            modal: true,
                            buttons: {
                                "<?php echo $lang_dis_lista_dis_cerrar; ?>": function() {
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
                    $('.inscribir_experiencia').click(function() {
                        $("#contenido_inscrito").hide();
                        $('#form_inscribir_experiencia').dialog('open');
                        $id_diseno = $(this).attr('href');
                        $nombre_diseno =$(this).attr('title');
                        $("div.dialogBotones div button:nth-child(1)").show();
                        $("div.dialogBotones div button:nth-child(2)").show();
                        $("div.dialogBotones div button:nth-child(3)").hide();
                    });
                    $('.boton_inscribir').each(function() {
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
                    $('.link_ventana_documentos_actividad').each(function() {
                        var $linkc = $(this);
                        $linkc.click(function() {
                            var $dialog = $('<div></div>')
                            .load($linkc.attr('href') + ' #contenido_actividad')
                            .dialog({
                                autoOpen: false,
                                title: $linkc.attr('title'),
                                width: 600,
                                height: 220,
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
                            for ($i = 0; $i <= $ncom; $i++) {
?>
            $('#ventana_comentario_<?php echo $i; ?>').dialog({
                autoOpen: false,
                width: 500,
                modal: true,
                buttons: {
                    "<?php echo $lang_cerrar; ?>": function() {
                        $(this).dialog("close");
                    }
                }
            });
            $('#link_ventana_comentario_<?php echo $i; ?>').click(function(){
                var $link = $(this);
                $('#ventana_comentario_<?php echo $i; ?>').dialog({
                    title: $link.attr('title')
                });
                $('#ventana_comentario_<?php echo $i; ?>').dialog('open');
                return false;
            });
<?php
                            }
?>
    });
</script>