<?php
/**
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Elson Gueregat - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 *
 * */
//if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:../ingresar.php");
?>
<?php
$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "concurso/inc/con_db_funciones.inc.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$idDiseno = -1;
$fcd_nombre = '';
$fcd_sector = -1;
$fcd_nivel = -1;
$fcd_descripcion = '';
$fcd_objetivos_curriculares = '';
$fcd_objetivos_transversales = '';
$fcd_contenidos = '';
$fcd_descripcion_etapa1 = '';
$fcd_descripcion_etapa2 = '';
$fcd_descripcion_etapa3 = '';
$fcd_web_20 = -1;
$fcd_id_autor = $_SESSION["klwn_id_usuario"];
$terminado = 0;

$_herramientas_web = obtenerHerramientasWebFuncion($conexion);

$h_web = array();
for ($i = 0; $i < count($_herramientas_web); $i++) {
    $h_web[$_herramientas_web[$i]['hw_id_herramienta']] = $_herramientas_web[$i]['hw_enlace'];
}

$_etapas = array();
$_actividades_etapa1 = array();
$_actividades_etapa2 = array();
$_actividades_etapa3 = array();

    $idUser = $_SESSION["klwn_id_usuario"];
    
    $_diseno = obtenerDisenoConFuncion($idUser, $conexion);
    if(count($_diseno) <= 0){
        crearDisenoFuncion($idUser, $conexion);
        $_diseno = obtenerDisenoConFuncion($idUser, $conexion);
    }
    $idDiseno                       = $_diseno[0]['dd_id_diseno_didactico_con'];
    $fcd_id_autor                   = $_diseno[0]['dd_id_autor'];
    $fcd_nombre                     = $_diseno[0]['dd_nombre'];
    $fcd_sector                     = $_diseno[0]['dd_subsector'];
    $fcd_nivel                      = $_diseno[0]['dd_nivel'];
    $fcd_descripcion                = $_diseno[0]['dd_descripcion'];
    $fcd_objetivos_curriculares     = $_diseno[0]['dd_objetivos_curriculares'];
    $fcd_objetivos_transversales    = $_diseno[0]['dd_objetivos_transversales'];
    $fcd_contenidos                 = $_diseno[0]['dd_contenidos'];
    $fcd_descripcion_etapa1         = $_diseno[0]['dd_descripcion_e1'];
    $fcd_descripcion_etapa2         = $_diseno[0]['dd_descripcion_e2'];
    $fcd_descripcion_etapa3         = $_diseno[0]['dd_descripcion_e3'];    
    $fcd_web_20                     = $_diseno[0]['hd_id_herramienta_con'];
    $terminado                      = $_diseno[0]['dd_terminado'];

    
    
    $_etapas = obtenerEtapasFuncion($idDiseno, $conexion);
    $_actividades_etapa1 = obtenerActividadesPorEtapaFuncion($_etapas[0]['e_id_etapa_con'], $conexion);
    $_actividades_etapa2 = obtenerActividadesPorEtapaFuncion($_etapas[1]['e_id_etapa_con'], $conexion);
    $_actividades_etapa3 = obtenerActividadesPorEtapaFuncion($_etapas[2]['e_id_etapa_con'], $conexion);

    $maxActividades = count($_actividades_etapa1);
    if (count($_actividades_etapa2) > $maxActividades)
        $maxActividades = count($_actividades_etapa2);
    if (count($_actividades_etapa3) > $maxActividades)
        $maxActividades = count($_actividades_etapa3);

?>      
        <div id="contentBody">
           <?php if($terminado == 1){?>
            <div><?php echo $lang_concurso_diseno_enviado; ?></div>
    <?php } else {?>
            <div class="inicio_izquierda">
                <div id="info_ayuda">
                    <div class="tit_paso"><?php echo $lang_concurso_paso; ?> 1:</div><br>
<?php echo $lang_concurso_completa_datos; ?><br><br>

<?php echo $lang_concurso_instruccion_guardar_cambios; ?>

<br><br>
<div class="tit_paso"><?php echo $lang_concurso_paso; ?> 2:</div><br>
<?php echo $lang_concurso_completa_datos; ?>
<?php echo $lang_concurso_editar_actividad; ?>
<br><br>
<?php echo $lang_concurso_acciones_adicionales; ?>
<br><br>
<div class="tit_paso"><?php echo $lang_concurso_paso; ?> 3:</div><br>
<?php echo $lang_concurso_instrucciones_enviar; ?>
<br><br>

<div class="tit_paso"><?php echo $lang_concurso_importante; ?></div><br>
<?php echo $lang_concurso_advertencia; ?>
            </div>
            </div>
            <div id="lado_derecho">
                <div id="dialog-confirm" title="Confirm" style="display:none;">
                    <p></p>
                </div>
                <div id="crear_diseno">
                    <div id="crear_diseno_titulo" class="estado_diseno modificar_diseno click titulo_editar_a" >
    <?php
    if ($idDiseno > 0) {
        echo $lang_concurso_inscribir_dd;
    } else {
        echo $lang_concurso_inscribir_dd;
    }
    ?>
                    </div>
                    <a id="descargaWordCon" href="./concurso/con_crearWord.php?idDiseno=<?php echo $idDiseno; ?>" target="_black"><?php echo $lang_concurso_descargar_word_dd; ?></a>                    
                    <div id="datos_diseno">
                        <div class="intro_crear_diseno"><?php echo ''; ?></div>
                        <div id="formulario_crear_diseno">
                            <div id="ayuda_di"><div id="ayuda_content_di"></div></div>
                            <div id="ayuda_d" onClick="mostrarDivAyuda();"></div>
                            <form id="form_crear_diseno" method="post" action="" accept-charset="UTF-8">
                                <div id="caja_form_crear_diseno">                    

                                    <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcd_id_autor"  name="fcd_id_autor" value="<?php echo $fcd_id_autor; ?>"/>
                                    <input tabindex="1" type="hidden" maxlenght="20" size="20" id="fcd_id_diseno"  name="fcd_id_diseno" value="<?php echo $idDiseno; ?>"/>
                    <table>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_nombre . " :"; ?></label></td>
                            <td><input tabindex="2" type="text" maxlenght="40" size="20" id="nicEdit-fcd_nombre"  name="fcd_nombre" value="<?php echo $fcd_nombre; ?>"/></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_sector . " :"; ?></label></td>
                            <td><select tabindex="3"  id="fcd_sector" name="fcd_sector" size="1" >
                                    <?php
                                    for ($i = 0; $i < count($_sectores); $i++) {
                                        if (strcmp($_sectores[$i]['valor'], $fcd_sector) == 0) {
                                            echo '<option value="' . $_sectores[$i]['valor'] . '" selected>' . $_sectores[$i]['nombre'] . '</option>';
                                        } else {
                                            echo '<option value="' . $_sectores[$i]['valor'] . '">' . $_sectores[$i]['nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><label ><?php echo $lang_crear_nuevo_diseno_nivel . " :"; ?></label></td>
                            <td><select tabindex="4"  id="fcd_nivel" name="fcd_nivel" size="1">
                                    <?php
                                    for ($i = 0; $i < count($_niveles); $i++) {
                                        if (strcmp($_niveles[$i], $fcd_nivel) == 0) {
                                            echo '<option value="' . $_niveles[$i] . '" selected>' . $_niveles[$i] . '</option>';
                                        } else {
                                            echo '<option value="' . $_niveles[$i] . '">' . $_niveles[$i] . '</option>';
                                        }
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_descripcion . " :"; ?></label></td>
                            <td><textarea tabindex="5" id="fcd_descripcion" name="fcd_descripcion"><?php echo $fcd_descripcion; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_obj_curriculares . " :"; ?></label></td>
                            <td><textarea tabindex="6" id="fcd_objetivos_curriculares" name="fcd_objetivos_curriculares" rows="3" cols="1"><?php echo $fcd_objetivos_curriculares; ?></textarea>
                                <a class="abrirObjetivos" title="Ver Objetivos Curriculares" onClick="abrirObjetivosCurriculares();"><img src="./concurso/img/pdf.png" class="icono_pdfObjCurr"></img></a></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_obj_transversales . " :"; ?></label></td>
                            <td><textarea tabindex="7" id="fcd_objetivos_transversales" name="fcd_objetivos_transversales" rows="3" cols="1"><?php echo $fcd_objetivos_transversales; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_contenidos . " :"; ?></label></td>
                            <td><textarea tabindex="8" id="fcd_contenidos" name="fcd_contenidos" rows="2" cols="1"><?php echo $fcd_contenidos; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa1 . " :"; ?></label></td>
                            <td><textarea tabindex="9" id="fcd_descripcion_etapa1" name="fcd_descripcion_etapa1" rows="3" cols="1"><?php echo $fcd_descripcion_etapa1; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa2 . " :"; ?></label></td>
                            <td><textarea tabindex="10" id="fcd_descripcion_etapa2" name="fcd_descripcion_etapa2" rows="3" cols="1"><?php echo $fcd_descripcion_etapa2; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa3 . " :"; ?></label></td>
                            <td><textarea tabindex="11" id="fcd_descripcion_etapa3" name="fcd_descripcion_etapa3" rows="3" cols="1"><?php echo $fcd_descripcion_etapa3; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_web_20 . " :"; ?></label></td>
                            <td><select tabindex="12"  id="fcd_web_20" name="fcd_web_20" size="1">
                                    <?php
                                    for ($i = 0; $i < count($_herramientas_web); $i++) {
                                        if ($_herramientas_web[$i]['hw_id_herramienta'] == $fcd_web_20) {
                                            echo '<option value="' . $_herramientas_web[$i]['hw_id_herramienta'] . '" selected>' . $_herramientas_web[$i]['hw_nombre'] . '</option>';
                                        } else {
                                            echo '<option value="' . $_herramientas_web[$i]['hw_id_herramienta'] . '">' . $_herramientas_web[$i]['hw_nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select></td>
                        </tr>
                    </table>        
                                    <input tabindex="13" id="fcd_submit" class="fcd_submit" type="submit" value="<?php echo $lang_crear_diseno_guardar; ?>">
                                    <div class="clear"></div>
                                    <div id="error_form_crear_diseno"></div>
                                    <div class="clear"></div>     
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="actividades_por_etapa">
                    <div id="modificar_act" class="estado_diseno modificar_actividades click modificar_imagen_a" >
                       <?php echo $lang_concurso_act_por_etapa; ?> 
                    </div>
                    <?php
                    $max_actividades = 4;
                    $maxLenghTitulo = 45;
                    $maxLenghDesc = 150;
                    ?>
                    <div id="etapas" class="etapas" style="height:<?php echo $max_actividades * 160 + 150; ?>px;">
                        <div id="etapa1" class="etapa1">
                            <div id="title_epata_1" class="titulo_etapa"><?php echo $lang_concurso_etapa_motivacion; ?></div>            
                            <?php
                            $count_etapa1 = count($_actividades_etapa1);
                            $orden_etapa = 1;
                            $id_etapa = $_etapas[0]['e_id_etapa_con'];
                            $string_actividad = "";


                            for ($i = 0; $i < count($_actividades_etapa1); $i++) {

                                $str_images = '';
                                $str_images2 = '';
                                $str_image_lab = '';
                                $comentarios = '';

                                if ($i == 0) {
                                    $arriba = 0;
                                    $id_etapa = $_actividades_etapa1[$i]['ac_id_etapa_con'];
                                } else {
                                    $arriba = 1;
                                }
                                if ($i == $count_etapa1 - 1) {
                                    $abajo = 0;
                                } else {
                                    $abajo = 1;
                                }
                                if ($_actividades_etapa1[$i]['ac_tipo'] == $actividad_laboratorio) {
                                    $class = '_lab';
                                    $str_image_lab = '<img src="../img/laboratorio.png" alt="">';
                                } else {
                                    $class = '_sala';
                                    $str_image_lab = '<img src="./img/transp_16.png" alt="">';
                                }
                                if (strlen($_actividades_etapa1[$i]['ac_nombre']) > $maxLenghTitulo)
                                    $_actividades_etapa1[$i]['ac_nombre'] = substr($_actividades_etapa1[$i]['ac_nombre'], 0, strrpos(substr($_actividades_etapa1[$i]['ac_nombre'], 0, $maxLenghTitulo), " "));
                                if (strlen($_actividades_etapa1[$i]['ac_descripcion']) > $maxLenghDesc)
                                    $_actividades_etapa1[$i]['ac_descripcion'] = substr($_actividades_etapa1[$i]['ac_descripcion'], 0, strrpos(substr($_actividades_etapa1[$i]['ac_descripcion'], 0, $maxLenghDesc), " "));

                                $string_actividad.= '<div id="actividad_' . $_actividades_etapa1[$i]['ac_id_actividad_con'] . '" class="actividad' . $class . '">';
                                $string_actividad.= '<table>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="2"><div class="titulo_actividad_mini">' . $_actividades_etapa1[$i]['ac_nombre'] . '</div></td>';
                                $string_actividad.= '<td colspan="2"><div class="iconos_actividad_mini">' . $str_images2 . $str_image_lab . '</div></td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="4">' . $str_images . '</td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="4"><div class="desc_actividad_mini">' . $_actividades_etapa1[$i]['ac_descripcion'] . '</div></td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td class="td1"><input id="modificar_actividad" title="Editar actividad" name="' . $_actividades_etapa1[$i]['ac_id_actividad_con'] . '" class="boton_modificar_actividad" type="button" value="" onClick="abrirActividad(' . $_actividades_etapa1[$i]['ac_id_actividad_con'] . ',' . $orden_etapa . ',' . $abajo . ',' . $arriba . ',' . $id_etapa . ');"/></td>';
                                $string_actividad.= '<td class="td2"><input id="eliminar_actividad" title="Eliminar actividad" name="' . $_actividades_etapa1[$i]['ac_id_actividad_con'] . '"  class="boton_eliminar_actividad" type="button" value="" onClick="eliminarActividad(' . $_actividades_etapa1[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa1[$i]['ac_orden'] . ');"/></td>';
                                if ($abajo == 1) {
                                    $string_actividad.= '<td class="td3" align=center><input name="mover_actividad_abajo" title="Mover abajo" class="boton_mover_abajo" type="button" value="" onClick="bajarActividad(' . $_actividades_etapa1[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa1[$i]['ac_orden'] . ');"/></td>';
                                } else {
                                    $string_actividad.= '<td class="td3" align=center></td>';
                                }
                                if ($arriba == 1) {
                                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_arriba" title="Mover arriba" class="boton_mover_arriba" type="button" value="" onClick="subirActividad(' . $_actividades_etapa1[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa1[$i]['ac_orden'] . ');"/></td>';
                                } else {
                                    $string_actividad.= '<td class="td4" align=center></td>';
                                }
                                $string_actividad.= '</tr>';
                                $string_actividad.= '</table>';
                                $string_actividad.= '</div>';
                            }

                            echo $string_actividad;
                            ?>          
                            <div class="agregar_actividad"><input id="agregar_actividad_etapa1" name="agregar_actividad_etapa1" class="boton_agregar_actividad" type="button" value="<?php echo $lang_concurso_actividad_etapa1; ?>" onClick="agregarActividad(<?php echo $id_etapa . ',' . $orden_etapa . ',' . $count_etapa1; ?>);"/></div>  
                        </div>
                        <div id="etapa2" class="etapa2">
                            <div id="title_epata_2" class="titulo_etapa"><?php echo $lang_concurso_etapa_desarrollo; ?></div>             
                            <?php
                            $count_etapa2 = count($_actividades_etapa2);
                            $orden_etapa = 2;
                            $id_etapa = $_etapas[1]['e_id_etapa_con'];
                            $string_actividad = "";

                            for ($i = 0; $i < count($_actividades_etapa2); $i++) {

                                $str_images = '';
                                $str_images2 = '';
                                $str_image_lab = '';
                                $comentarios = '';

                                if ($i == 0) {
                                    $arriba = 0;
                                    $id_etapa = $_actividades_etapa2[$i]['ac_id_etapa_con'];
                                } else {
                                    $arriba = 1;
                                }
                                if ($i == $count_etapa2 - 1) {
                                    $abajo = 0;
                                } else {
                                    $abajo = 1;
                                }
                                if ($_actividades_etapa2[$i]['ac_tipo'] == $actividad_laboratorio) {
                                    $class = '_lab';
                                    $str_image_lab = '<img src="../img/laboratorio.png" alt="">';
                                } else {
                                    $class = '_sala';
                                    $str_image_lab = '<img src="./img/transp_16.png" alt="">';
                                }
                                if (strlen($_actividades_etapa2[$i]['ac_nombre']) > $maxLenghTitulo)
                                    $_actividades_etapa2[$i]['ac_nombre'] = substr($_actividades_etapa2[$i]['ac_nombre'], 0, strrpos(substr($_actividades_etapa2[$i]['ac_nombre'], 0, $maxLenghTitulo), " "));
                                if (strlen($_actividades_etapa2[$i]['ac_descripcion']) > $maxLenghDesc)
                                    $_actividades_etapa2[$i]['ac_descripcion'] = substr($_actividades_etapa2[$i]['ac_descripcion'], 0, strrpos(substr($_actividades_etapa2[$i]['ac_descripcion'], 0, $maxLenghDesc), " "));

                                $string_actividad.= '<div id="actividad_' . $_actividades_etapa2[$i]['ac_id_actividad_con'] . '" class="actividad' . $class . '">';
                                $string_actividad.= '<table>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="2"><div class="titulo_actividad_mini">' . $_actividades_etapa2[$i]['ac_nombre'] . '</div></td>';
                                $string_actividad.= '<td colspan="2"><div class="iconos_actividad_mini">' . $str_images2 . $str_image_lab . '</div></td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="4">' . $str_images . '</td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="4"><div class="desc_actividad_mini">' . $_actividades_etapa2[$i]['ac_descripcion'] . '</div></td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td class="td1"><input id="modificar_actividad" title="<?php echo $lang_concurso_editar_actividad; ?>" name="' . $_actividades_etapa2[$i]['ac_id_actividad_con'] . '" class="boton_modificar_actividad" type="button" value="" onClick="abrirActividad(' . $_actividades_etapa2[$i]['ac_id_actividad_con'] . ',' . $orden_etapa . ',' . $abajo . ',' . $arriba . ',' . $id_etapa . ');"/></td>';
                                $string_actividad.= '<td class="td2"><input id="eliminar_actividad" title="<?php echo $lang_concurso_eliminar_actividad; ?>" name="' . $_actividades_etapa2[$i]['ac_id_actividad_con'] . '"  class="boton_eliminar_actividad" type="button" value="" onClick="eliminarActividad(' . $_actividades_etapa2[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa2[$i]['ac_orden'] . ');"/></td>';
                                if ($abajo == 1) {
                                    $string_actividad.= '<td class="td3" align=center><input name="mover_actividad_abajo" title="<?php echo $lang_concurso_mover_abajo; ?>" class="boton_mover_abajo" type="button" value="" onClick="bajarActividad(' . $_actividades_etapa2[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa2[$i]['ac_orden'] . ');"/></td>';
                                } else {
                                    $string_actividad.= '<td class="td3" align=center></td>';
                                }
                                if ($arriba == 1) {
                                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_arriba" title="<?php echo $lang_concurso_mover_arriba; ?>" class="boton_mover_arriba" type="button" value="" onClick="subirActividad(' . $_actividades_etapa2[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa2[$i]['ac_orden'] . ');"/></td>';
                                } else {
                                    $string_actividad.= '<td class="td4" align=center></td>';
                                }
                                $string_actividad.= '</tr>';
                                $string_actividad.= '</table>';
                                $string_actividad.= '</div>';
                            }

                            echo $string_actividad;
                            ?>      
                            <div class="agregar_actividad"><input id="agregar_actividad_etapa2" name="agregar_actividad_etapa2" class="boton_agregar_actividad" type="button" value="<?php echo $lang_concurso_actividad_etapa2; ?>" onClick="agregarActividad(<?php echo $id_etapa . ',' . $orden_etapa . ',' . $count_etapa2; ?>);"/></div>  
                        </div>
                        <div id="etapa3" class="etapa3">
                            <div id="title_epata_3" class="titulo_etapa">382</div>
                            <?php
                            $count_etapa3 = count($_actividades_etapa3);
                            $orden_etapa = 3;
                            $id_etapa = $_etapas[2]['e_id_etapa_con'];
                            $string_actividad = "";


                            for ($i = 0; $i < count($_actividades_etapa3); $i++) {

                                $str_images = '';
                                $str_images2 = '';
                                $str_image_lab = '';
                                $comentarios = '';

                                if ($i == 0) {
                                    $arriba = 0;
                                    $id_etapa = $_actividades_etapa3[$i]['ac_id_etapa_con'];
                                } else {
                                    $arriba = 1;
                                }
                                if ($i == $count_etapa3 - 1) {
                                    $abajo = 0;
                                } else {
                                    $abajo = 1;
                                }
                                if ($_actividades_etapa3[$i]['ac_tipo'] == $actividad_laboratorio) {
                                    $class = '_lab';
                                    $str_image_lab = '<img src="../img/laboratorio.png" alt="">';
                                } else {
                                    $class = '_sala';
                                    $str_image_lab = '<img src="./img/transp_16.png" alt="">';
                                }
                                if (strlen($_actividades_etapa3[$i]['ac_nombre']) > $maxLenghTitulo)
                                    $_actividades_etapa3[$i]['ac_nombre'] = substr($_actividades_etapa3[$i]['ac_nombre'], 0, strrpos(substr($_actividades_etapa3[$i]['ac_nombre'], 0, $maxLenghTitulo), " "));
                                if (strlen($_actividades_etapa3[$i]['ac_descripcion']) > $maxLenghDesc)
                                    $_actividades_etapa3[$i]['ac_descripcion'] = substr($_actividades_etapa3[$i]['ac_descripcion'], 0, strrpos(substr($_actividades_etapa3[$i]['ac_descripcion'], 0, $maxLenghDesc), " "));

                                $string_actividad.= '<div id="actividad_' . $_actividades_etapa3[$i]['ac_id_actividad_con'] . '" class="actividad' . $class . '">';
                                $string_actividad.= '<table>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="2"><div class="titulo_actividad_mini">' . $_actividades_etapa3[$i]['ac_nombre'] . '</div></td>';
                                $string_actividad.= '<td colspan="2"><div class="iconos_actividad_mini">' . $str_images2 . $str_image_lab . '</div></td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="4">' . $str_images . '</td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td colspan="4"><div class="desc_actividad_mini">' . $_actividades_etapa3[$i]['ac_descripcion'] . '</div></td>';
                                $string_actividad.= '</tr>';
                                $string_actividad.= '<tr>';
                                $string_actividad.= '<td class="td1"><input id="modificar_actividad" title="<?php echo $lang_concurso_editar_actividad; ?>" name="' . $_actividades_etapa3[$i]['ac_id_actividad_con'] . '" class="boton_modificar_actividad" type="button" value="" onClick="abrirActividad(' . $_actividades_etapa3[$i]['ac_id_actividad_con'] . ',' . $orden_etapa . ',' . $abajo . ',' . $arriba . ',' . $id_etapa . ');"/></td>';
                                $string_actividad.= '<td class="td2"><input id="eliminar_actividad" title="<?php echo $lang_concurso_eliminar_actividad; ?>" name="' . $_actividades_etapa3[$i]['ac_id_actividad_con'] . '"  class="boton_eliminar_actividad" type="button" value="" onClick="eliminarActividad(' . $_actividades_etapa3[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa3[$i]['ac_orden'] . ');"/></td>';
                                if ($abajo == 1) {
                                    $string_actividad.= '<td class="td3" align=center><input name="mover_actividad_abajo" title="<?php echo $lang_concurso_mover_abajo; ?>" class="boton_mover_abajo" type="button" value="" onClick="bajarActividad(' . $_actividades_etapa3[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa3[$i]['ac_orden'] . ');"/></td>';
                                } else {
                                    $string_actividad.= '<td class="td3" align=center></td>';
                                }
                                if ($arriba == 1) {
                                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_arriba" title="<?php echo $lang_concurso_mover_arriba; ?>" class="boton_mover_arriba" type="button" value="" onClick="subirActividad(' . $_actividades_etapa3[$i]['ac_id_actividad_con'] . ',' . $id_etapa . ',' . $orden_etapa . ',' . $_actividades_etapa3[$i]['ac_orden'] . ');"/></td>';
                                } else {
                                    $string_actividad.= '<td class="td4" align=center></td>';
                                }
                                $string_actividad.= '</tr>';
                                $string_actividad.= '</table>';
                                $string_actividad.= '</div>';
                            }

                            echo $string_actividad;

                            dbDesconectarMySQL($conexion);
                            ?>       
                            <div class="agregar_actividad"><input id="agregar_actividad_etapa3" name="agregar_actividad_etapa3" class="boton_agregar_actividad" type="button" value="<?php echo $lang_concurso_actividad_etapa3; ?>"  onClick="agregarActividad(<?php echo $id_etapa . ',' . $orden_etapa . ',' . $count_etapa3; ?>);"/></div>  

                        </div>
                    </div>
                </div>
                <div>                                    
                    <input id="diseno_listo" type="button" value="<?php echo $lang_concurso_enviar_dd; ?>" class="fcd_submit" onClick="terminarDiseno();" title="Enviar diseño">
                </div>
            </div>
            <div class="clear"></div>
            <?php }?>
        </div>
        <script type="text/javascript">
            //
            var estado = 0;
            var id_diseno_actual= <?php echo $idDiseno; ?>;
            var actividad_laboratorio = <?php echo $actividad_laboratorio; ?>;
            var $dialog;
            var input_ayuda_actual= '';
            var end_comentario=5;
            var ver_mas_comentario = 5;
            var timeoutComentario = 0;
            var objCurriculares = new Array();
            var urlObjCurriculares = '';
            objCurriculares['SLC'] = '<?php echo $rutaObjCurriculares_SLC; ?>';
            objCurriculares['SHG'] = '<?php echo $rutaObjCurriculares_SHG; ?>';
            objCurriculares['SCS'] = '<?php echo $rutaObjCurriculares_SCS; ?>';
            objCurriculares['SG'] = '<?php echo $rutaObjCurriculares_SG; ?>';

            function terminarDiseno(){
                var $dialog = $('<div><p><br></br><?php echo $lang_concurso_advertencia_diseno; ?></p></div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_concurso_enviar_dd; ?>',
                    dialogClass: 'uii-dialog',
                    width: 500,
                    height: 150,
                    zIndex: 3999,
                    modal: true,
                    close: function(ev, ui) {
                        $(this).remove();
                    },
                    buttons: {
                        "<?php echo $lang_concurso_cancelar; ?>": function() {
                            $(this).dialog("close");
                        }, 
                        "<?php echo $lang_concurso_aceptar; ?>": function() {
                            terminarDisenoOK();
                            $(this).dialog("close");
                        }
                    }            
                });
                $dialog.dialog('open');
                return false;               
            }
            function terminarDisenoOK(){
                $.get('./concurso/con_enviar_diseno.php?id_diseno='+id_diseno_actual, function(data) {
                    var $dialog = $('<div><p><br></br><?php echo $lang_concurso_dd_enviado; ?></p></div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_concurso_enviado_dd; ?>',
                        dialogClass: 'uii-dialog',
                        width: 500,
                        height: 150,
                        zIndex: 3999,
                        modal: true,
                        close: function(ev, ui) {
                            $(this).remove();
                        },
                        buttons: {
                            "<?php echo $lang_concurso_aceptar; ?>": function() {
                                $(this).dialog("close");
                                location.href="./ingresar.php";
                            }
                        }            
                    });
                    $dialog.dialog('open');
                    return false;         
                });    
            }

            function findPos(obj) {
                var off=document.getElementById(obj);
        //        var off=document.getElementById("nicEdit-fcd_objetivos_transversales");
                var curleft = curtop = 0;
                if (off.offsetParent) {
                    curleft = off.offsetLeft;
                    curtop = off.offsetTop;
                    while (off = off.offsetParent) {
                        curleft += off.offsetLeft;
                        curtop += off.offsetTop;
                    }
                }
                return [curleft,curtop];
            }
            function mostrarAyuda(texto, key){

//                left= document.getElementById(key).offsetLeft;
//                top_= document.getElementById(key).offsetTop;
//
//                document.getElementById('ayuda_d').style.left = (left+413)+"px";
//                document.getElementById('ayuda_d').style.top = (top_ +2)+"px";        
//                document.getElementById('ayuda_d').style.visibility = 'visible';
//                if(key != input_ayuda_actual)
//                    document.getElementById('ayuda_di').style.visibility = 'hidden';
//                input_ayuda_actual= key;
//                document.getElementById('ayuda_di').style.left = (left-5)+"px";
//                document.getElementById('ayuda_di').style.top = (top_ -105)+"px";
//                document.getElementById('ayuda_content_di').innerHTML = texto;

                var posiciones= findPos(key);
        //        anc = parseInt((window.document.body.clientWidth-960)/2);
        //        if(anc<0){ anc = 0;}        
        //        document.getElementById('ayuda_d').style.left = (posiciones[0]+400-anc)+"px";
                document.getElementById('ayuda_d').style.left = "870px";
                document.getElementById('ayuda_d').style.top = (posiciones[1]-60)+"px";
                if(key == "nicEdit-fcd_nombre"){
                    document.getElementById('ayuda_d').style.top = (posiciones[1]-58)+"px";
                    
                }
                document.getElementById('ayuda_d').style.visibility = 'visible';
                if(key != input_ayuda_actual)
                    document.getElementById('ayuda_di').style.visibility = 'hidden';
                input_ayuda_actual= key;
                document.getElementById('ayuda_di').style.left = "440px";
                document.getElementById('ayuda_di').style.top = (posiciones[1]-170)+"px";
                 if(key == "nicEdit-fcd_nombre"){
                document.getElementById('ayuda_di').style.top = (posiciones[1]-144)+"px";
                 }
                document.getElementById('ayuda_content_di').innerHTML = texto;

            }
            function mostrarDivAyuda(){
                if(document.getElementById('ayuda_di').style.visibility == 'hidden')
                //            document.getElementById('ayuda_di').style.visibility = 'visible';
                    $('#ayuda_di').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0});
                else
                    document.getElementById('ayuda_di').style.visibility = 'hidden';
            }    
    
            function actualizarActividad(idActividad, orden_etapa, abajo, arriba, tipo){
                $.get('./concurso/con_obtenerActividad.php?id_actividad='+idActividad+'&orden_etapa='+orden_etapa+'&abajo='+abajo+'&arriba='+arriba, function(data) {
                    $('#actividad_'+idActividad).html(data);
                    if(tipo == actividad_laboratorio) document.getElementById('actividad_'+idActividad).className = 'actividad_lab';
                    else document.getElementById('actividad_'+idActividad).className = 'actividad_sala';
                });
            }
            function actualizarEtapa(id_etapa, orden_etapa){
                $.get('./concurso/con_obtenerEtapa.php?id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
                    $('#etapa'+orden_etapa).html(data);
                });        
            }
            function actualizarComentariosDiseno(id_diseno){
        
                $.get('./concurso/con_obtenerComentarios.php?id_diseno='+id_diseno+'&tipo=0&end='+end_comentario, function(data) {
                    $('#con_lista_comentarios').html(data);
                });        
            }
            function verMasComentarios(){
                end_comentario= end_comentario + ver_mas_comentario;
                actualizarComentariosDiseno(id_diseno_actual);
            }
            function actualizarComentariosTimeout(){
//                clearTimeout(timeoutComentario);
//                actualizarComentariosDiseno(id_diseno_actual);
//                timeoutComentario = setTimeout("actualizarComentariosTimeout();",1000*3*60);
            }
            function ancho(a){
                if(a>=990)return a+60;
                else return 990+35;
            }
            function abrirActividad(idActividad, orden_etapa, abajo, arriba, id_etapa) {               

                tipo= 1;
                $.get('./concurso/con_existeActividad.php?id_actividad='+idActividad, function(data) {
                    if(data.indexOf("true") != -1){
                        editarActividad(idActividad, orden_etapa, abajo, arriba, id_etapa);
                    }else{
                        var $dialog2 = $('<div><p><br></br><?php echo $lang_concurso_act_eliminada_otro_usuario; ?></p></div>')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_concurso_editar_actividad; ?>',
                            dialogClass: 'uii-dialog',
                            width: 450,
                            height: 150,
                            zIndex: 3999,
                            modal: true,
                            close: function(ev, ui) {
                                $(this).remove();
                            },
                            buttons: {
                                "<?php echo $lang_concurso_aceptar; ?>": function() {
                                    actualizarEtapa(id_etapa, orden_etapa);
                                    $(this).dialog("close"); 
                                }
                            }            
                        });
                        $dialog2.dialog('open');
                        return false;                          
                    }
                });
                
            }
            var showHelp=false;
            function editarActividad(idActividad, orden_etapa, abajo, arriba, id_etapa) {
                scroll(0,0);
                if(document.getElementById("ayuda_d").style.visibility == "visible") showHelp=true;
                document.getElementById("ayuda_d").style.visibility = "hidden";
                $dialog = $('<div scrolling="auto" ></div>')
                .load('./concurso/con_form_crear_actividad.php?idActividad='+idActividad+'&orden_etapa='+orden_etapa+'&abajo='+abajo+'&arriba='+arriba+'&id_diseno='+id_diseno_actual)
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_concurso_crear_modif_act; ?>',
                    dialogClass: 'uii-dialog mono',
                    width: 700,
                    minHeight: 380,
                    position: [parseInt((ancho(window.document.body.clientWidth)-990)/2),10],
                    zIndex: 3999,
                    draggable: false,
                    modal: true,
                    open: function (event, ui) { window.setTimeout(function () { 
                            jQuery(document).unbind('mousedown.dialog-overlay').unbind('mouseup.dialog-overlay'); }, 100); 
                    },
                    close: function(ev, ui) {
                        if(showHelp)document.getElementById("ayuda_d").style.visibility = "visible";
                        actualizarEtapa(id_etapa, orden_etapa);
                        //cerrarAyuda();
                        $(this).remove();
                    }                
                });
                $dialog.dialog('open');
                return false;
            }    
    

            function abrirObjetivosCurriculares(){
                sector = document.getElementById('fcd_sector').value;
                urlObjCurriculares = objCurriculares[sector];
                if(urlObjCurriculares != ''){
                    window.open (urlObjCurriculares,"Objetivos Curriculares");
                }
            }
    
            function alturaInicial(){
                cantidad_actividades = <?php echo $maxActividades; ?>;
                altura = document.getElementById('etapas').style.height;
                altura = altura.split('px');        
                height = parseInt(altura);
                if(cantidad_actividades*160+150 >= height){
                    document.getElementById('etapas').style.height = (cantidad_actividades+1)*160+150+"px";
                }        
            }
    
            function agregarActividad(id_etapa, orden_etapa, cantidad_actividades){
                $.get('./concurso/con_agregarActividad.php?id_etapa='+id_etapa, function(data) {
                    actualizarEtapa(id_etapa, orden_etapa);          
                });
                altura = document.getElementById('etapas').style.height;
                altura = altura.split('px');        
                height = parseInt(altura);
                if(cantidad_actividades*160+150 >= height){
                    document.getElementById('etapas').style.height = (cantidad_actividades+1)*160+150+"px";
                }
            } 
    
            function eliminarActividad(idActividad, id_etapa, orden_etapa, actividad_orden){
    
                tipo= 1;
            
                var $dialog = $('<div><p><br></br><?php echo $lang_concurso_seguro_elim_act; ?></p></div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_concurso_eliminar_actividad; ?>',
                    dialogClass: 'uii-dialog',
                    width: 500,
                    height: 150,
                    zIndex: 3999,
                    modal: true,
                    close: function(ev, ui) {
                        $(this).remove();
                    },
                    buttons: {
                        "<?php echo $lang_concurso_cancelar; ?>": function() {
                            actualizarEtapa(id_etapa, orden_etapa);                           
                            $(this).dialog("close");
                        }, 
                        "<?php echo $lang_concurso_aceptar; ?>": function() {
                            eliminarActividadOK(idActividad, id_etapa, orden_etapa, actividad_orden);
                            $(this).dialog("close");
                        }
                    }            
                });
                $dialog.dialog('open');
                return false;

            }
            function eliminarActividadOK(idActividad, id_etapa, orden_etapa, actividad_orden){
                $.get('./concurso/con_eliminarActividad.php?id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa, function(data) {
                    actualizarEtapa(id_etapa, orden_etapa);          
                });    
            }
    
            function bajarActividad(idActividad, id_etapa, orden_etapa, actividad_orden){
                $.get('./concurso/con_moverActividad.php?mover=abajo&id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
                    actualizarEtapa(id_etapa, orden_etapa);            
                });
            }
    
            function subirActividad(idActividad, id_etapa, orden_etapa, actividad_orden){
                $.get('./concurso/con_moverActividad.php?mover=arriba&id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
                    actualizarEtapa(id_etapa, orden_etapa);            
                }); 
            }

            function actualizarCamposDiseno(){
                idDiseno = id_diseno_actual;
                $.get('./concurso/con_obtenerDiseno.php?id_diseno='+idDiseno, function(data) {
                    campos = data.split("$@%%@$");
                    $('#fcd_nombre').val(campos[0]);
                    $('#fcd_nivel').val(campos[1]);
                    $('#fcd_sector').val(campos[2]);
                    $('#fcd_descripcion').val(campos[3]);
                    $('#fcd_objetivos_curriculares').val(campos[4]);
                    $('#fcd_objetivos_transversales').val(campos[5]);
                    $('#fcd_contenidos').val(campos[6]);
                    $('#fcd_descripcion_etapa1').val(campos[7]);
                    $('#fcd_descripcion_etapa2').val(campos[8]);
                    $('#fcd_descripcion_etapa3').val(campos[9]);
                    $('#fcd_web_20').val(campos[10]);
                });
            }

            $(document).ready(function(){

                nicEditors.elemById({buttonList : ['bold','italic','underline','ol','ul','indent','outdent','link','unlink'], maxHeight : 80},['fcd_descripcion', 'fcd_objetivos_curriculares', 'fcd_objetivos_transversales', 'fcd_contenidos', 'fcd_descripcion_etapa1', 'fcd_descripcion_etapa2', 'fcd_descripcion_etapa3']);

       <?php
       
       foreach ($_ta_diseno as $key => $value) {

       //se pone el texto de ayuda en el textarea

        echo "if(nicEditors.findEditor('".$value."').getContent()==''|| nicEditors.findEditor('".$value."').getContent()=='<br>'){".
             " nicEditors.findEditor('".$value."').setContent('".$ayuda['diseno'][$value]."');".
             " $('#nicEdit-".$value."').css('color','#969696')};";

        //se define la funcion focus para los textarea
        echo "$('#nicEdit-".$value."').focus(function() {".
             "if (nicEditors.findEditor('".$value."').getContent() == '".$ayuda['diseno'][$value]."')".
             "     nicEditors.findEditor('".$value."').setContent('');".
//             "  $('#nicEdit-".$value."').css('color','#333333') });";
             "  });";

        //se define la funcion blur para los textarea
        echo "$('#nicEdit-".$value."').blur(function() {".
             "if (nicEditors.findEditor('".$value."').getContent() == ''|| nicEditors.findEditor('".$value."').getContent()=='<br>'){".
             "    nicEditors.findEditor('".$value."').setContent('".$ayuda['diseno'][$value]."');".
             "$('#nicEdit-".$value."').css('color','#969696');}".
             "  });";

       }

       ?>
        
        <?php
        //se crean las funciones para mostrar los textos de ayuda
        foreach ($ayuda['diseno'] as $key => $value) {
            echo "document.getElementById('nicEdit-" . $key . "').onclick = function(){mostrarAyuda('" . $value . "','nicEdit-" . $key . "');};";
        }
        ?>        
                scroll(0,0);
                alturaInicial();
        

                $("#error_form_crear_diseno").hide();

                $("#form_crear_diseno").validate({
                    rules:{
                        fcd_nombre:{
                            required: true,
                            minlength:6
                        }
                    },
                    messages:{
                        fcd_nombre:{
                            required: '<?php echo $lang_concurso_campo_obligatorio; ?>',
                            minlength: '<?php echo $lang_concurso_largo_min; ?>'
                        }                
                    },            
                    submitHandler: function() {
                        url = './concurso/con_guardar_diseno.php?';

                        $.post(url, $("#form_crear_diseno").serialize(), function(id) {

                            if (parseInt(id)< 0){
                                $("#error_form_crear_diseno").text('<?php echo $lang_concurso_no_guardado; ?>');
                                $("#error_form_crear_diseno").show();
                            }
                            else{
                                $(".intro_crear_diseno").html("<div><?php echo $lang_concurso_dd_guardado; ?> </div>");
                                $("#error_form_crear_diseno").show();
                                id_diseno_actual = parseInt(id);
                            }
                        });
                    }
                });
        
                $("#form_con_comentarios").validate({
                    rules:{
                        dc_texto_comentario:{
                            required: true,
                            minlength:6
                        }
                    },
                    messages:{
                        dc_texto_comentario:{
                            required: '<?php echo $lang_concurso_campo_obligatorio; ?>',
                            minlength: '<?php echo $lang_concurso_largo_min; ?>'
                        }               
                    },            
                    submitHandler: function() {
                        url = './concurso/con_enviarComentario.php?';

                        $.post(url, $("#form_con_comentarios").serialize(), function(data) {
                            document.getElementById('dc_texto_comentario').value = '';
                            actualizarComentariosDiseno(id_diseno_actual);
                        });
                    }
                }); 

                end = 20;

            });
        </script>


