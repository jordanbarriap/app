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
 **/

//if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./../";
require_once($ruta_raiz ."conf/config.php");
require_once($ruta_raiz ."inc/all.inc.php");
require_once($ruta_raiz ."inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz ."taller_dd/inc/tdd_db_funciones.inc.php");
require_once($ruta_raiz ."taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$fca_id_actividad           =-1;
$fca_nombre                 = "";	
$fca_aprendizaje_esperado   = "";	
$fca_evidencia_aprendizaje  = "";
$fca_descripcion_general    = "";
$fca_horas                  = 0;
$fca_tipo_lugar             = 1;
//$fca_materiales             = "";
$fca_medios                 = "";
$fca_inicio                 = "";
$fca_desarrollo             = "";
$fca_cierre                 = "";
$fca_medios_otros           = 1;
$fca_medios_trabajos        = 1;
$fca_id_complementaria      = -1;
$fca_medios_bitacora        = 0;
$fca_medios_web20           = 0;

$id_diseno_acti = $_GET['id_diseno'];

$_diseno= obtenerDisenoFuncion($id_diseno_acti, $conexion);
$escala = 0;

$stringPuntaje = "";

if(isset($_diseno[0]['dd_escala']) ){ 
    $escala = $_diseno[0]['dd_escala']; 
    $_puntajesEscala= obtenerEscalaPuntajeFuncion($escala, $conexion);
    
} 

if(isset($_GET['idActividad'])){
    $fca_id_actividad           = $_GET['idActividad'];
    $_actividad = obtenerActividadFuncion($fca_id_actividad, $conexion);

    $fca_nombre                 = $_actividad[0]['ac_nombre'];	
    $fca_aprendizaje_esperado   = $_actividad[0]['ac_aprendizaje_esperado'];	
    $fca_evidencia_aprendizaje  = $_actividad[0]['ac_evidencia_aprendizaje'];
    $fca_descripcion_general    = $_actividad[0]['ac_descripcion'];
    $fca_horas                  = $_actividad[0]['ac_horas_estimadas'];

    if($fca_horas != '' && $fca_horas>=45){
        $fca_horas = $fca_horas/45;
    }
//    if($fca_horas != '' && $fca_horas < 1){
//        $fca_horas = 0;
//    }

    $fca_tipo_lugar             = $_actividad[0]['ac_tipo'];
//    $fca_materiales             = $_actividad[0]['ac_material_requerido'];
    $fca_medios                 = $_actividad[0]['ac_medios'];
    $fca_inicio                 = $_actividad[0]['ac_instrucciones_inicio'];
    $fca_desarrollo             = $_actividad[0]['ac_instrucciones_desarrollo'];
    $fca_cierre                 = $_actividad[0]['ac_instrucciones_cierre'];   
    $fca_medios_otros           = $_actividad[0]['ac_medios_otros']; 
    $fca_medios_trabajos        = $_actividad[0]['ac_medios_trabajos']; 
    $fca_id_complementaria      = $_actividad[0]['ac_id_complementaria']; 
    $fca_medios_bitacora        = $_actividad[0]['ac_medios_bitacora']; 
    $fca_medios_web20           = $_actividad[0]['ac_medios_web2']; 
    $fca_consejos               = $_actividad[0]['ac_consejos_practicos']; 
    
    $orden_etapa                = $_GET['orden_etapa'];
    $abajo                      = $_GET['abajo'];
    $arriba                     = $_GET['arriba'];
   
}

$class_laboratorio = 'oculto';
$class_sala        = 'oculto';

$_archivos = obtenerArchivosFuncion($fca_id_actividad, $conexion);

$_pautas = obtenerPautasFuncion($fca_id_actividad, $conexion);
$_complementarias = buscarActividadComplemFuncion($id_diseno_acti, $orden_etapa, $_actividad[0]['ac_orden'], $conexion);

$boolTipoAutoEva = false;
$boolTipoProdHetEva = false;
$boolTipoEcoEva = false;

$_tiposEvaluacion = obtenerTiposEvalFunction($fca_id_actividad, $conexion);

foreach ($_tiposEvaluacion as $key => $value) {
    if($value['ev_id_tipoevaluacion'] == 1){ $boolTipoAutoEva = true; }
    if($value['ev_id_tipoevaluacion'] == 4){ $boolTipoProdHetEva = true; }
    if($value['ev_id_tipoevaluacion'] == 5){ $boolTipoEcoEva = true; }
}

if(isset($_GET['idActividad'])){
    if( $_actividad[0]['ac_eval_autoyco'] ){ $boolTipoAutoEva = true; }    
    if( $_actividad[0]['ac_eval_prodhetero'] ){ $boolTipoProdHetEva = true; }
    if( $_actividad[0]['ac_eval_evaleco'] ){ $boolTipoEcoEva = true; }
}

$_pautasAutoEva = obtenerPautasPorTipoFuncion($fca_id_actividad, 1, $conexion );
$_pautasProdHetEva = obtenerPautasPorTipoFuncion($fca_id_actividad, 4, $conexion );
$_pautasEcoEva = obtenerPautasPorTipoFuncion($fca_id_actividad, 5, $conexion );

if(count($_pautasAutoEva <=0) ){
    $_pautasAutoEva = obtenerPautasPorTipoFuncion($fca_id_actividad, 2, $conexion );    
}
if(count($_pautasAutoEva <=0) ){
    $_pautasProdHetEva = obtenerPautasPorTipoFuncion($fca_id_actividad, 3, $conexion );    
}


$enunciados = obtenerEnunciadosFuncion($conexion);

dbDesconectarMySQL($conexion);        
?>
<!doctype html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset;?>" >
	<title><?php //echo $actividad;?></title> 
</head> 
<body>
<div id="contenido_actividad"> 
    <div id="info_actividad">
        <div id="ayuda_ac"><div id="ayuda_content_ac"></div></div>
            <form id="form_crear_actividad" method="post" action="" accept-charset="UTF-8">
                <div id="caja_form_crear_actividad">
                    <input id="cerrar_2" class="cerrar_actividad" type="button" onclick="cerrarActividad();" value="<?php echo $lang_nueva_actividad_cerrar; ?>" name="cerrar">
                    <input tabindex="11" class="fca_submit" type="submit" value="<?php echo $lang_crear_actividad_guardar; ?>">
                    <div class="clear"></div>
                    <br></br>
                    <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fca_id_diseno"  name="fca_id_diseno" value="<?php echo $id_diseno_acti; ?>"/>
                    <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fca_id_actividad"  name="fca_id_actividad" value="<?php echo $fca_id_actividad; ?>"/>
                    <label><?php echo $lang_nueva_actividad_nombre . " :"; ?></label>
                    <input tabindex="1" type="text" maxlenght="20" size="20" id="fca_nombre"  name="fca_nombre" value="<?php echo $fca_nombre; ?>"/>
                    <img class="imagen_ayuda" id="fca_nombre_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_aprendizaje_esperado . " :"; ?></label>
                    <textarea tabindex="2" id="fca_aprendizaje_esperado" name="fca_aprendizaje_esperado" rows="2" cols="1"><?php echo $fca_aprendizaje_esperado; ?></textarea>
                    <img class="imagen_ayuda" id="fca_aprendizaje_esperado_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>
                    <label ><?php echo $lang_nueva_actividad_evidencia_aprendizaje . " :"; ?></label>
                    <textarea tabindex="3" id="fca_evidencia_aprendizaje" name="fca_evidencia_aprendizaje" rows="2" cols="1"><?php echo $fca_evidencia_aprendizaje; ?></textarea>
                    <img class="imagen_ayuda" id="fca_evidencia_aprendizaje_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_descripcion_general . " :"; ?></label>
                    <textarea tabindex="4" id="fca_descripcion_general" name="fca_descripcion_general" rows="3" cols="1"><?php echo $fca_descripcion_general; ?></textarea>
                    <img class="imagen_ayuda" id="fca_descripcion_general_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_horas . " :"; ?></label>
                    <input tabindex="5" type="text" maxlenght="20" size="20" id="fca_horas"  name="fca_horas" value="<?php echo $fca_horas; ?>"/>
                    <img class="imagen_ayuda" id="fca_horas_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_tipo_lugar . " :"; ?></label>
                    <select tabindex="6"  id="fca_tipo_lugar" name="fca_tipo_lugar" size="1" onChange="tipoOnSelect();"> 
                    <?php
                        for($i=0; $i<count($_act_tipo); $i++){
                            if($_act_tipo[$i]['valor'] == $fca_tipo_lugar){
                                echo '<option value="'.$_act_tipo[$i]['valor'].'" selected>'.$_act_tipo[$i]['nombre'].'</option>';
                            }else{
                                echo '<option value="'.$_act_tipo[$i]['valor'].'">'.$_act_tipo[$i]['nombre'].'</option>';  }
                        }                    
                    ?>
                    </select>
                    <div class="clear"></div> 
                    <?php if($fca_tipo_lugar == $actividad_laboratorio || $fca_tipo_lugar == $actividad_casa){
                        $class_laboratorio = "visible";
                        $class_sala = "oculto";
                    }else{
                        $class_laboratorio = "oculto";
                        $class_sala = "visible";                        
                    }
                    ?>
                    <div id="laboratorio" class="<?php echo $class_laboratorio;?>">
                        <label><?php echo $lang_nueva_actividad_herramienta_medios . " :"; ?></label>
                        <div class="clear"></div> 
                        <div class="medios_lab">
                            <input type="checkbox" id="fca_medios_bitacora" class ="checkbox" name="fca_medios_bitacora" <?php if($fca_medios_bitacora == 1) echo 'checked="yes"'; ?>/><div class="checkbox_text"><?php echo $lang_nueva_actividad_medios_bitacora; ?></div>
                            <div class="clear"></div>
                            <input type="checkbox" id="fca_medios_web2" class ="checkbox" name="fca_medios_web2" <?php if($fca_medios_web20 == 1) echo 'checked="yes"'; ?>/><div class="checkbox_text"><?php echo $lang_nueva_actividad_medios_web2; ?></div>
                            <div class="clear"></div>                            
                            <input type="checkbox" id="fca_medios_trabajos_no" class ="checkbox" name="fca_medios_trabajos_no" onChange="trabajosNoChange();" <?php if($fca_medios_trabajos == $actividad_revision || $fca_medios_trabajos == $actividad_publicacion || $fca_medios_trabajos == $actividad_otros) echo 'checked="yes"'; ?>/><div class="checkbox_text"><?php echo $lang_nueva_actividad_medios_trabajos; ?></div>
                            <!--<label><?php echo $lang_nueva_actividad_medios_trabajos . " :"; ?></label> -->
                            <div class="clear"></div>
                            <div id="control_trabajos">
                                <?php
                                    for($i=0; $i<count($_act_medios_trabajos); $i++){
                                        if($_act_medios_trabajos[$i]['valor'] != 1){
                                            if($_act_medios_trabajos[$i]['valor'] == $fca_medios_trabajos){
                                                echo '<input type="radio" class="radio_medios" onChange="medioOnSelect();" name="fca_medios_trabajos" value="'.$_act_medios_trabajos[$i]['valor'].'" checked="checked">'.$_act_medios_trabajos[$i]['nombre'].'&nbsp;&nbsp;&nbsp;&nbsp;';
                                                //echo '<option value="'.$_act_medios_trabajos[$i]['valor'].'" selected>'.$_act_medios_trabajos[$i]['nombre'].'</option>';
                                            }else{
                                                echo '<input type="radio" class="radio_medios" onChange="medioOnSelect();" name="fca_medios_trabajos" value="'.$_act_medios_trabajos[$i]['valor'].'">'.$_act_medios_trabajos[$i]['nombre'].'&nbsp;&nbsp;&nbsp;&nbsp;';
                                                //echo '<option value="'.$_act_medios_trabajos[$i]['valor'].'">'.$_act_medios_trabajos[$i]['nombre'].'</option>';                            
                                            }
                                        }
                                    }                    
                                ?>

                            </div>
                            

                            </select> 
                            <div class="clear"></div>
                            <?php 
                            if($fca_medios_trabajos == $actividad_revision || $fca_medios_trabajos == $actividad_publicacion || $fca_medios_trabajos == $actividad_otros){
                                $class_pautas = "visible";
                            }else{
                                $class_pautas = "oculto";
                            } 
                            ?>                            
                            <div id="control_pautas" class="<?php echo $class_pautas;?>">
                                <div id="control_eval">
                                    <div id="control_eval_autoyco">
                                        <input type="checkbox" id="fca_eval_autoyco" class ="checkbox" onChange="evalAutoyCoOnChange();" name="fca_eval_autoyco" <?php if($boolTipoAutoEva == true) echo 'checked="yes"'; ?>/><div class="checkbox_text_90"><?php echo $lang_nueva_actividad_eval_autoyco; ?></div>
                                        <div class="clear"></div>
                                        <div id="pauta_autoyco">
                                            <div class="complementaria">
                                                <div><?php echo $lang_crear_nuevo_tipo_escala; ?>:</div>
                                                <table class="tabla_criterios" style="margin-bottom: 5px;">
                                                   <tr>
                                                       <?php for($jjj = 0; $jjj< count($_puntajesEscala); $jjj++ ){ ?>
                                                       <td><?php echo $_puntajesEscala[$jjj]['eval_valor']  ?></td>
                                                       <?php } ?>
                                                   </tr>
                                               </table>
                                               <label ><?php echo $lang_nueva_actividad_criterios . " :"; ?></label>
                                               <div class="clear"></div>
                                           </div>
                                           <div id="agregar_pauta_autoyco" class="complementaria">
                                               <ul style="margin-bottom: 0px;">
                                               <?php
                                               $maxLengh = 100;
                                               $totalPautas = count($_pautasAutoEva);
                                               for($i=0 ; $i< $totalPautas; $i++){
						   $cut = false;
						   $tooltipText = "";
                                                   $enunciado = $_pautasAutoEva[$i]['enu_contenido'];
                                                   if(strlen($enunciado) > $maxLengh) {
							$enunciado= substr($enunciado,0,strrpos(substr($enunciado,0,$maxLengh-3)," "))."...";
							$cut = true;
							$tooltipText = str_replace(array("\\n","\\r","'", '"'), array(" "," ","\'", '\"'), $_pautasAutoEva[$i]['enu_contenido']);
						   }
                                                   echo '<li class ="li_mis_pautas" id="'.$_pautasAutoEva[$i]['rbenu_id_enunciado'].'" '.(($cut)?("title=\"".$tooltipText."\""):"").'>';
                                                   echo '<div>'.$enunciado.'</div>';
                                                   echo '<a class="link_mis_pautas" name="eliminar_pauta" onClick="eliminarPauta('.$_pautasAutoEva[$i]['rbenu_id_enunciado'].','.$fca_id_actividad.','.$_pautasAutoEva[$i]['rbenu_id_rubrica'].','.$_pautasAutoEva[$i]['rbenu_orden'].',1)">'.$lang_nueva_actividad_eliminar.'</a>';
                                                   if($_pautasAutoEva[$i]['rbenu_orden'] != $totalPautas)echo '<input name="mover_pauta_abajo" class="boton_pauta_abajo" type="button" value="" onClick="bajarPauta('.$_pautasAutoEva[$i]['rbenu_id_enunciado'].','.$_pautasAutoEva[$i]['rbenu_orden'].','.$_pautasAutoEva[$i]['rbenu_id_rubrica'].','.$fca_id_actividad.','.'1'.');"/>';
                                                   if($_pautasAutoEva[$i]['rbenu_orden'] != 1)echo '<input name="mover_pauta_arriba" class="boton_pauta_arriba" type="button" value="" onClick="subirPauta('.$_pautasAutoEva[$i]['rbenu_id_enunciado'].','.$_pautasAutoEva[$i]['rbenu_orden'].','.$_pautasAutoEva[$i]['rbenu_id_rubrica'].','.$fca_id_actividad.','.'1'.');"/>';
                                                   echo '</li>';
                                               }
                                               ?>
                                               </ul>
                                           </div>
                                           <div id="div_iframe_ap_autoyco">
                                                <div class="separador"></div>
                                                <form></form>
                                                <form id="form_agregar_pauta_autoyco" action="tdd_agregarEnunciado.php" method="post"  accept-charset="UTF-8" enctype="multipart/form-data">
                                                    <div id="caja_agregar_pauta">
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_actividad"  name="fcp_id_actividad" value="<?php echo $fca_id_actividad; ?>"  class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_diseno"  name="fcp_id_diseno" value="<?php echo $id_diseno_acti; ?>"  class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_enunciado"  name="fcp_id_enunciado" value="-1"  class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_tipo"  name="fcp_tipo" value="autoyco"  class="ignore-validation" />
                                                        <label class="label_agregar_pauta"><?php echo $lang_nueva_actividad_pauta_enunciado . " :"; ?></label>
                                                        <input type="text" tabindex="1" id="fcp_enunciado_autoyeco" name="fcp_enunciado" class="ignore-validation" />
                                                        <input tabindex="2" id="agregarPauta" type="button" value="<?php echo $lang_nueva_actividad_pauta_agregar; ?>" onclick="formEnunciadoSubmit('autoyco');" class="fcp_submit" />
                                                        <input tabindex="2" id="agregarPauta" type="submit" style="display: none; visibility: hidden;" />
                                                    </div>         
                                                </form>
                                           </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div id="control_eval_prodhetero" class="div-control-pautas">
                                        <input type="checkbox" id="fca_eval_prodhetero" class ="checkbox" onChange="evalProdHeteroOnChange();" name="fca_eval_prodhetero" <?php if($boolTipoProdHetEva == true) echo 'checked="yes"'; ?>/><div class="checkbox_text_90"><?php echo $lang_nueva_actividad_eval_prodhetero; ?></div>
                                        <div class="clear"></div>
                                        <div id="pauta_prodhetero">
                                            <div class="complementaria">
                                                <label ><?php echo $lang_nueva_actividad_complementaria . " :"; ?></label>
                                                <select tabindex="5"  id="fca_complementaria" name="fca_complementaria" size="1"  onChange="complemOnSelect();"> 
                                                <?php
                                                    echo '<option value="0">'.$lang_nueva_actividad_ninguna.'</option>';                            

                                                    for($i=0; $i<count($_complementarias); $i++){
                                                        if($_complementarias[$i]['ac_id_actividad'] == $fca_id_complementaria){
                                                            echo '<option value="'.$_complementarias[$i]['ac_id_actividad'].'" selected>('.$lang_nueva_actividad_etapa2.' '.$_complementarias[$i]['e_orden'].") ".$_complementarias[$i]['ac_nombre'].'</option>';
                                                        }else{
                                                            echo '<option value="'.$_complementarias[$i]['ac_id_actividad'].'">('.$lang_nueva_actividad_etapa2.' '.$_complementarias[$i]['e_orden'].") ".$_complementarias[$i]['ac_nombre'].'</option>';                            

                                                            }
                                                    }                    
                                                ?>
                                                </select>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="complementaria">
                                                <div><?php echo $lang_crear_nuevo_tipo_escala; ?>:</div>
                                                <table class="tabla_criterios" style="margin-bottom: 5px;" >
                                                   <tr>
                                                       <?php for($jjj = 0; $jjj< count($_puntajesEscala); $jjj++ ){ ?>
                                                       <td><?php echo $_puntajesEscala[$jjj]['eval_valor']  ?></td>
                                                       <?php } ?>
                                                   </tr>
                                               </table>  
                                               <label ><?php echo $lang_nueva_actividad_criterios . " :"; ?></label>
                                               <div class="clear"></div>
                                           </div>
                                            <div id="agregar_pauta_prodhetero" class="complementaria">                                              
                                               <ul style="margin-bottom: 0px;">
                                               <?php
                                               $maxLengh = 100;
                                               $totalPautas = count($_pautasProdHetEva);
                                               for($i=0 ; $i< $totalPautas; $i++){
						   $cut = false;
						   $tooltipText = "";
                                                   $enunciado = $_pautasProdHetEva[$i]['enu_contenido'];
                                                   if(strlen($enunciado) > $maxLengh) {
							$enunciado= substr($enunciado,0,strrpos(substr($enunciado,0,$maxLengh-3)," "))."...";
							$cut = true;
							$tooltipText = str_replace(array("\\n","\\r","'", '"'), array(" "," ","\'", '\"'), $_pautasProdHetEva[$i]['enu_contenido']);
						   }

                                                   echo '<li class ="li_mis_pautas" id="'.$_pautasProdHetEva[$i]['rbenu_id_enunciado'].'" '.(($cut)?("title=\"".$tooltipText."\""):"").'>';
                                                   echo '<div>'.$enunciado.'</div>';
                                                   echo '<a class="link_mis_pautas" name="eliminar_pauta" onClick="eliminarPauta('.$_pautasProdHetEva[$i]['rbenu_id_enunciado'].','.$fca_id_actividad.','.$_pautasProdHetEva[$i]['rbenu_id_rubrica'].','.$_pautasProdHetEva[$i]['rbenu_orden'].',4)">'.$lang_nueva_actividad_eliminar.'</a>';
                                                   if($_pautasProdHetEva[$i]['rbenu_orden'] != $totalPautas)echo '<input name="mover_pauta_abajo" class="boton_pauta_abajo" type="button" value="" onClick="bajarPauta('.$_pautasProdHetEva[$i]['rbenu_id_enunciado'].','.$_pautasProdHetEva[$i]['rbenu_orden'].','.$_pautasProdHetEva[$i]['rbenu_id_rubrica'].','.$fca_id_actividad.','.'4'.');"/>';
                                                   if($_pautasProdHetEva[$i]['rbenu_orden'] != 1)echo '<input name="mover_pauta_arriba" class="boton_pauta_arriba" type="button" value="" onClick="subirPauta('.$_pautasProdHetEva[$i]['rbenu_id_enunciado'].','.$_pautasProdHetEva[$i]['rbenu_orden'].','.$_pautasProdHetEva[$i]['rbenu_id_rubrica'].','.$fca_id_actividad.','.'4'.');"/>';
                                                   echo '</li>';
                                               }
                                               ?>
                                               </ul>
                                           </div>
                                           <div id="div_iframe_ap_prodhetero">
                                                <form></form>
                                                <form id="form_agregar_pauta_prodhetero" action="tdd_agregarEnunciado.php" method="post"  accept-charset="UTF-8" enctype="multipart/form-data">
                                                    <div id="caja_agregar_pauta">
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_actividad"  name="fcp_id_actividad" value="<?php echo $fca_id_actividad; ?>" class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_diseno"  name="fcp_id_diseno" value="<?php echo $id_diseno_acti; ?>" class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_enunciado"  name="fcp_id_enunciado" value="-1" class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_tipo"  name="fcp_tipo" value="prodhetero"  class="ignore-validation" />
                                                        <label class="label_agregar_pauta"><?php echo $lang_nueva_actividad_pauta_enunciado . " :"; ?></label>
                                                        <input type="text" tabindex="1" id="fcp_enunciado_prodhetero" name="fcp_enunciado"  class="ignore-validation" />
                                                        <input tabindex="2" id="agregarPauta" type="button" value="<?php echo $lang_nueva_actividad_pauta_agregar; ?>" onclick="formEnunciadoSubmit('prodhetero');" class="fcp_submit" />
                                                        <input tabindex="2" id="agregarPauta" type="submit" style="display: none; visibility: hidden;" />
                                                    </div>         
                                                </form>
                                           </div>
                                            
                                        </div>

                                    </div>
                                    <div id="control_eval_eco" class="div-control-pautas">
                                        <input type="checkbox" id="fca_eval_eco" class ="checkbox" onChange="evalEcoOnChange();" name="fca_eval_eco" <?php if($boolTipoEcoEva == true) echo 'checked="yes"'; ?>/><div class="checkbox_text_90"><?php echo $lang_nueva_actividad_eval_eco; ?></div>
                                        <div class="clear"></div>
                                        <div id="pauta_eco">
                                            <div class="complementaria">
                                                <div><?php echo $lang_crear_nuevo_tipo_escala; ?>:</div>
                                                <table class="tabla_criterios" style="margin-bottom: 5px;" >
                                                   <tr>
                                                       <?php for($jjj = 0; $jjj< count($_puntajesEscala); $jjj++ ){ ?>
                                                       <td><?php echo $_puntajesEscala[$jjj]['eval_valor']  ?></td>
                                                       <?php } ?>
                                                   </tr>
                                               </table>  
                                               <label ><?php echo $lang_nueva_actividad_criterios . " :"; ?></label>
                                               <div class="clear"></div>
                                           </div>
                                           <div id="agregar_pauta_eco" class="complementaria">
                                             
                                               <ul style="margin-bottom: 0px;">
                                               <?php
                                               $maxLengh = 100;
                                               $totalPautas = count($_pautasEcoEva);
                                               for($i=0 ; $i< $totalPautas; $i++){
						   $cut = false;
						   $tooltipText = "";
                                                   $enunciado = $_pautasEcoEva[$i]['enu_contenido'];
                                                   if(strlen($enunciado) > $maxLengh) {
							$enunciado= substr($enunciado,0,strrpos(substr($enunciado,0,$maxLengh-3)," "))."...";
							$cut = true;
							$tooltipText = str_replace(array("\\n","\\r","'", '"'), array(" "," ","\'", '\"'), $_pautasEcoEva[$i]['enu_contenido']);
						   }

                                                   echo '<li class ="li_mis_pautas" id="'.$_pautasEcoEva[$i]['rbenu_id_enunciado'].'" '.(($cut)?("title=\"".$tooltipText."\""):"").'>';
                                                   echo '<div>'.$enunciado.'</div>';
                                                   echo '<a class="link_mis_pautas" name="eliminar_pauta" onClick="eliminarPauta('.$_pautasEcoEva[$i]['rbenu_id_enunciado'].','.$fca_id_actividad.','.$_pautasEcoEva[$i]['rbenu_id_rubrica'].','.$_pautasEcoEva[$i]['rbenu_orden'].',5)">'.$lang_nueva_actividad_eliminar.'</a>';
                                                   if($_pautasEcoEva[$i]['rbenu_orden'] != $totalPautas)echo '<input name="mover_pauta_abajo" class="boton_pauta_abajo" type="button" value="" onClick="bajarPauta('.$_pautasEcoEva[$i]['rbenu_id_enunciado'].','.$_pautasEcoEva[$i]['rbenu_orden'].','.$_pautasEcoEva[$i]['rbenu_id_rubrica'].','.$fca_id_actividad.','.'5'.');"/>';
                                                   if($_pautasEcoEva[$i]['rbenu_orden'] != 1)echo '<input name="mover_pauta_arriba" class="boton_pauta_arriba" type="button" value="" onClick="subirPauta('.$_pautasEcoEva[$i]['rbenu_id_enunciado'].','.$_pautasEcoEva[$i]['rbenu_orden'].','.$_pautasEcoEva[$i]['rbenu_id_rubrica'].','.$fca_id_actividad.','.'5'.');"/>';
                                                   echo '</li>';
                                               }
                                               ?>
                                               </ul>
                                           </div>
                                           <div id="div_iframe_ap_eco">
                                                <div class="separador"></div>
                                                <form></form>
                                                <form id="form_agregar_pauta_eco" action="tdd_agregarEnunciado.php" method="post"  accept-charset="UTF-8" enctype="multipart/form-data">
                                                    <div id="caja_agregar_pauta">
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_actividad"  name="fcp_id_actividad" value="<?php echo $fca_id_actividad; ?>" class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_diseno"  name="fcp_id_diseno" value="<?php echo $id_diseno_acti; ?>" class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_enunciado"  name="fcp_id_enunciado" value="-1" class="ignore-validation" />
                                                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_tipo"  name="fcp_tipo" value="eco"  class="ignore-validation" />
                                                        <label class="label_agregar_pauta"><?php echo $lang_nueva_actividad_pauta_enunciado . " :"; ?></label>
                                                        <input type="text" tabindex="1" id="fcp_enunciado_eco" name="fcp_enunciado" class="ignore-validation" />
                                                        <input tabindex="2" id="agregarPauta" type="button" value="<?php echo $lang_nueva_actividad_pauta_agregar; ?>" onclick="formEnunciadoSubmit('eco');" class="fcp_submit" />
                                                        <input tabindex="2" id="agregarPauta" type="submit" style="display: none; visibility: hidden;" />
                                                    </div>         
                                                </form>
                                           </div
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>                        
                            </div>
                            <div class="clear"></div>
                            <br/>
                            <label class="labelRight"><?php echo $lang_nueva_actividad_otros . " :"; ?></label>
                            <textarea tabindex="99" id="fca_medios_otros" class ="marginLeft" name="fca_medios_otros" rows="3" cols="1"><?php echo $fca_medios_otros; ?></textarea>
                            <img class="imagen_ayuda" id="fca_medios_otros_ayuda" src="./taller_dd/img/help.png"></img>
                            <div class="clear"></div> 
                        </div>
                    </div>
                </div>
                <div>
                    <div id="sala"  class="<?php echo $class_sala;?>">
                        <div class="clear"></div> 
                        <label><?php echo $lang_nueva_actividad_medios . " :"; ?></label>
                        <textarea tabindex="7" id="fca_medios" name="fca_medios" rows="2" cols="1"><?php echo $fca_medios;?></textarea>
                        <img class="imagen_ayuda" id="fca_medios_ayuda" src="./taller_dd/img/help.png"></img>
                        <div class="clear"></div>
                    </div>
                    <label><?php echo $lang_nueva_actividad_inicio . " :"; ?></label>
                    <textarea tabindex="8" id="fca_inicio" name="fca_inicio" rows="2" cols="1"><?php echo $fca_inicio; ?></textarea>
                    <img class="imagen_ayuda" id="fca_inicio_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>                    
                    <label><?php echo $lang_nueva_actividad_desarrollo . " :"; ?></label>
                    <textarea tabindex="9" id="fca_desarrollo" name="fca_desarrollo" rows="2" cols="1"><?php echo $fca_desarrollo; ?></textarea>
                    <img class="imagen_ayuda" id="fca_desarrollo_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>                    
                    <label><?php echo $lang_nueva_actividad_cierre . " :"; ?></label>
                    <textarea tabindex="10" id="fca_cierre" name="fca_cierre" rows="2" cols="1"><?php echo $fca_cierre; ?></textarea>
                    <img class="imagen_ayuda" id="fca_cierre_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_consejos_practicos . " :"; ?></label>
                    <textarea tabindex="11" id="fca_consejos" name="fca_consejos" rows="2" cols="1"><?php echo $fca_consejos; ?></textarea>
                    <img class="imagen_ayuda" id="fca_consejos_ayuda" src="./taller_dd/img/help.png"></img>
                    <div class="clear"></div>                    
                    <label><?php echo $lang_nueva_actividad_archivos . " :"; ?></label>
                    <div class="clear"></div>
                    <div id="control_archivos">
                        <div id="subir_archivo">
                            <ul>
                            <?php
                            $maxLengh = 70;
                            $tipo = $lang_tdd_ca_solo_profesor;
                            $totalArchivos = count($_archivos);
                            for($i=0 ; $i< $totalArchivos; $i++){
                                if($_archivos[$i]['a_solo_profesor'] == 1){
                                    $tipo = $lang_nueva_actividad_tipo1;
                                }else{
                                    $tipo = $lang_nueva_actividad_tipo2;
                                }                            
                                $descripcion = $_archivos[$i]['a_descripcion'];
                                if(strlen($descripcion) > $maxLengh) $descripcion= substr($descripcion,0,strrpos(substr($descripcion,0,$maxLengh-3)," "))."...";

                                echo '<li class ="li_mis_archivo" id="'.$_archivos[$i]['a_id_archivo'].'" >';
                                if ($_archivos[$i]['a_nombre_archivo']==""){
                                      echo '<div title="'.$_archivos[$i]['a_descripcion'].'">'.$_archivos[$i]['a_descripcion']." - ".$tipo.'</div>';
                                }else{
                                echo '<div title="'.$_archivos[$i]['a_descripcion'].'">'.$_archivos[$i]['a_nombre_archivo']." - ".$tipo.'</div>';
                                }
                                echo '<a id="eliminar_archivo" class="link_mis_archivos" name="eliminar_archivo" onClick="eliminarArchivo('.$_archivos[$i]['a_id_archivo'].',\''.$_archivos[$i]['a_nombre_archivo'].'\','.$_archivos[$i]['a_orden'].')">'.$lang_nueva_actividad_eliminar.'</a>';
                                if ($_archivos[$i]['a_nombre_archivo']!=""){
                                echo '<a id="ver_archivo" class="link_mis_archivos" name="ver_archivo" href="'.$config_ruta_actividades.$fca_id_actividad."/".$_archivos[$i]['a_nombre_archivo'].'" target="_black">'.$lang_nueva_actividad_ver.'</a>';
                                }
                                if($_archivos[$i]['a_orden'] != $totalArchivos)echo '<input name="mover_archivo_abajo" class="boton_pauta_abajo" type="button" value="" onClick="bajarArchivo('.$_archivos[$i]['a_id_archivo'].','.$_archivos[$i]['a_orden'].','.$_archivos[$i]['a_id_actividad'].');"/>';
                                if($_archivos[$i]['a_orden'] != 1)echo '<input name="mover_archivo_arriba" class="boton_pauta_arriba" type="button" value="" onClick="subirArchivo('.$_archivos[$i]['a_id_archivo'].','.$_archivos[$i]['a_orden'].','.$_archivos[$i]['a_id_actividad'].');"/>';
                                echo '</li>';
                            }
                            ?>
                            </ul>
                        </div>
                        <div id="aa">
                            <iframe  id="iframe-agregar-archivo" src="taller_dd/tdd_form_subir_archivo.php?idActividad=<?php echo $fca_id_actividad;?>&error=-1" width="100%" height="220" align="center" frameborder=’0′ marginwidth=’0′ marginheight =’0′>       
                            </iframe> 
                        </div>                        
                        
                        <div class="clear"></div>                        
                    </div>
                    <input id="cerrar" class="cerrar_actividad" type="button" onclick="cerrarActividad();" value="<?php echo $lang_nueva_actividad_cerrar; ?>" name="cerrar">
                    <input tabindex="11" class="fca_submit" type="submit" value="<?php echo $lang_crear_actividad_guardar; ?>">
                    <div class="clear"></div>
                    <div id="error_form_crear_actividad"></div>
                    <div class="clear"></div>     
                </div>
            </form>        
    </div>
</div>
<div class="separador_vertical"></div>
<div id="tdd_comentariosAct" >
    <div class="titulo_tdd_comentarios"><?php echo $lang_nueva_actividad_coment; ?></div>
    <div class="caja_form_comentariosAct">
    <form id="form_tdd_comentariosAct" method="post" action="" accept-charset="UTF-8">

            <input tabindex="0" type="hidden" maxlenght="20" size="20" id="dc_tipo_comentario_act"  name="dc_tipo_comentario_act" value="1"/>
            <input tabindex="0" type="hidden" maxlenght="20" size="20" id="dc_id_comentario_act"  name="dc_id_comentario_act" value="<?php echo $fca_id_actividad; ?>"/>
            <textarea tabindex="1" id="dc_texto_comentario_act" name="dc_texto_comentario_act" rows="2" cols="1"></textarea>
            <div class="clear"></div>    
            <input tabindex="2" id="agregarComentarioAct" type="submit" value="<?php echo $lang_nueva_actividad_enviar; ?>" class="dc_submit" />
       
    </form>
    </div>
    <div class="separador"></div>
    <div id="tdd_lista_comentarios_act">
    </div>
</div>  

<!--<script type="text/javascript" src="./taller_dd/nicEdit.js"></script>    -->
<script type="text/javascript">
 
    var actividad_laboratorio = <?php echo $actividad_laboratorio; ?>;
    var actividad_casa = <?php echo $actividad_casa; ?>;
    var actividad_revision = <?php echo $actividad_revision; ?>;

    var input_ayuda_actual_acti = "fca_nombre";
    var end_comentario_act= 5;
    var ver_mas_comentario_act= 5;
    
    var escala = <?php echo $escala; ?>;
    
    enunciadosArray = new Array();
    enunArray = new Array();
<?php
    
    foreach ($enunciados as $key => $value){
	$enunciadoText = str_replace(array("\r\n", "\r", "\n"), " ", $value["enu_contenido"]);
	$enunciadoText = str_replace(array('"'), '\"', $enunciadoText);
        echo 'enunciadosArray.push({enu_id_enunciado:'.$value["enu_id_enunciado"].', enu_contenido:"'.$enunciadoText.'"});';
        echo 'enunArray.push("'.$enunciadoText.'");';
    }
  
?>
    
    function mostrarAyudaActividad(texto, key){
        left= document.getElementById(key).offsetLeft;
        if(key=='fca_nombre' || key=='fca_horas'){
            top_= document.getElementById(key).offsetTop +25;
            document.getElementById(key+'_ayuda').style.marginTop = "5px";
        }
        else{
            top_= document.getElementById("nicEdit-"+key).offsetTop;
//            top_= document.getElementById(key).offsetTop +25;
            document.getElementById(key+'_ayuda').style.marginTop = "-90px";
        }

        document.getElementById(key+'_ayuda').style.visibility = 'visible';
        if(key != input_ayuda_actual_acti){
            document.getElementById(input_ayuda_actual_acti+'_ayuda').style.visibility = 'hidden';
            document.getElementById('ayuda_ac').style.visibility = 'hidden';
        }
        
        input_ayuda_actual_acti= key;
        if(key=="fca_medios_otros"){
            document.getElementById('ayuda_ac').style.width = 390+"px";
            if(document.getElementById("laboratorio").style.visibility == "visible")
                document.getElementById(key+'_ayuda').style.marginTop = "-25px";
        }
//        document.getElementById('ayuda_ac').style.left = (left)+"px";
        document.getElementById('ayuda_ac').style.left = "208px";
        document.getElementById('ayuda_ac').style.top = (top_ -82)+"px";
        document.getElementById('ayuda_content_ac').innerHTML = texto;
    }
    function mostrarDivAyudaActividad(){
        if(document.getElementById('ayuda_ac').style.visibility == 'hidden'){
            //$('#ayuda_ac').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0});
            document.getElementById('ayuda_ac').style.visibility = "visible";
        }
        else
            document.getElementById('ayuda_ac').style.visibility = 'hidden';
    }     
    
    function cerrarActividad(){
        
        var $dialogCerrar = $('<div><p><br></br><?php echo $lang_tdd_form_desea_guardar_cambios; ?></p></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_tdd_form_cerrar_act; ?>',
            dialogClass: 'uii-dialog',
            width: 500,
            height: 150,
            zIndex: 3999,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            },
             buttons: {
                "<?php echo $lang_nueva_actividad_dialog_no; ?>": function() { 
                   $(this).dialog("close"); 
                    scroll(0,0);
                    id_etapa= <?php echo $_actividad[0]['ac_id_etapa']; ?>;
                    orden_etapa= <?php echo $orden_etapa; ?>;        
                    actualizarEtapa(id_etapa, orden_etapa);
                    $dialog.dialog('close');
                },
                "<?php echo $lang_nueva_actividad_dialog_si; ?>": function() {
                    $("#form_crear_actividad").submit();
                    $(this).dialog("close");
                    scroll(0,0);
                    id_etapa= <?php echo $_actividad[0]['ac_id_etapa']; ?>;
                    orden_etapa= <?php echo $orden_etapa; ?>;        
                    actualizarEtapa(id_etapa, orden_etapa);
                    $dialog.dialog('close');
                } 
             }            
        });
        $dialogCerrar.dialog('open');
        return false;
        

    }
    /*bajarPauta('.$_pautasAutoEva[$i]['rbenu_id_enunciado'].','.$_pautasAutoEva[$i]['rbenu_orden'].','.$fca_id_actividad.')*/
    function bajarPauta(idEnunciado, ordenPauta, idRubrica, idActividad, tipo){
        idActividad = document.getElementById('fca_id_actividad').value;
        $.get('./taller_dd/tdd_moverPauta.php?id_actividad='+idActividad+"&id_rubrica="+idRubrica+"&id_pauta="+idEnunciado+"&orden="+ordenPauta+"&mover=abajo", function(data) {
            actualizarPautas(tipo);
        });         
    }
    function subirPauta(idEnunciado, ordenPauta, idRubrica, idActividad, tipo){
        idActividad = document.getElementById('fca_id_actividad').value;
        $.get('./taller_dd/tdd_moverPauta.php?id_actividad='+idActividad+"&id_rubrica="+idRubrica+"&id_pauta="+idEnunciado+"&orden="+ordenPauta+"&mover=arriba", function(data) {
            actualizarPautas(tipo);
        });        
    }    
    function bajarArchivo(idArchivo, ordenArchivo, idActividad){
        idActividad = document.getElementById('fca_id_actividad').value;
        $.get('./taller_dd/tdd_moverArchivo.php?id_actividad='+idActividad+"&id_archivo="+idArchivo+"&orden="+ordenArchivo+"&mover=abajo", function(data) {
            actualizarArchivos();
        });         
    }
    function subirArchivo(idArchivo, ordenArchivo, idActividad){
        idActividad = document.getElementById('fca_id_actividad').value;
        $.get('./taller_dd/tdd_moverArchivo.php?id_actividad='+idActividad+"&id_archivo="+idArchivo+"&orden="+ordenArchivo+"&mover=arriba", function(data) {
            actualizarArchivos();
        });        
    }    
    function tipoOnSelect(){
        valor = document.getElementById('fca_tipo_lugar').value;
        if(valor == actividad_laboratorio || valor== actividad_casa){
            document.getElementById('sala').className = 'oculto';
            document.getElementById('laboratorio').className = 'visible';
        }else{
            document.getElementById('laboratorio').className = 'oculto';
            document.getElementById('sala').className = 'visible';            
        }
    }
    function getMediosTrabajosValue(){
        var rates = document.getElementsByName('fca_medios_trabajos');
        var valor = -1;
        for(var i = 0; i < rates.length; i++){
            if(rates[i].checked){
                valor = rates[i].value;
            }
        }
        return valor;      
    }
    function medioOnSelect(){
        //valor = document.getElementById('fca_medios_trabajos').value;
        valor = getMediosTrabajosValue();
        document.getElementById('control_pautas').className = 'visible';
//        if(valor == actividad_revision){
//            document.getElementById('control_pautas').className = 'visible';
//        }else{
//            document.getElementById('control_pautas').className = 'oculto';
//        }
        if(valor == 2){
            $("#control_eval_prodhetero").hide();
            $("#control_eval_autoyco").show();
            $("#control_eval_eco").show();
        }
        if(valor == 3){
            $("#control_eval_autoyco").hide();
            $("#control_eval_prodhetero").show();            
            $("#control_eval_eco").show();
            $("#fca_eval_prodhetero").attr('checked',true);
            evalProdHeteroOnChange();
        }
        if(valor == 4){
            $("#control_eval_autoyco").hide();
            $("#control_eval_prodhetero").hide();            
            $("#control_eval_eco").show();
            $("#fca_eval_eco").attr('checked',true);
            evalEcoOnChange();
        }
        
        /*
        valor = document.getElementById('fca_complementaria').value;
        if(valor == 0){
            document.getElementById('div_iframe_ap').className = 'oculto';
            document.getElementById('agregar_pauta').className = 'oculto';
        }else{
            document.getElementById('div_iframe_ap').className = 'visible';
            document.getElementById('agregar_pauta').className = 'visible';
        }
        */
    }
    
    function complemOnSelect(){
        valor = document.getElementById('fca_complementaria').value;
        if(valor == 0){
            document.getElementById('div_iframe_ap').className = 'oculto';
            document.getElementById('agregar_pauta').className = 'oculto';
        }else{
            document.getElementById('div_iframe_ap').className = 'visible';
            document.getElementById('agregar_pauta').className = 'visible';
        }
    }
    
    function reutilizarRubrica(dataHTML, tipo, id_actividad, id_diseno){
        var textHTML = "<div><p><br/><?php echo $lang_nueva_actividad_reutiliz_rubrica_preg; ?></p><br/>"+dataHTML+"</div>";
        
        var $dialogReutilizar = $(textHTML).dialog({
            autoOpen: false,
            title: '<?php echo $lang_nueva_actividad_reutiliz_rubrica; ?>',
            dialogClass: 'uii-dialog',
            width: 500,
            height: 300,
            maxHeight: 450,
            zIndex: 3999,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            },
             buttons: {
                "<?php echo $lang_mis_disenos_cancelar; ?>": function() { 
                   $(this).dialog("close"); 
                },
                "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                    $.get("./taller_dd/tdd_reutilizarRubrica.php?id_actividad="+id_actividad+"&id_tipo="+tipo+"&id_diseno="+id_diseno, function(data) {
                        actualizarPautas(tipo);
                    });
                    $(this).dialog("close");
                } 
             }            
        });
        $dialogReutilizar.dialog('open');
        return false;    
    }
    
    
    function evalAutoyCoOnChange(){
        countAuto = $('#agregar_pauta_autoyco ul li').size();
        
        var checked = $("#fca_eval_autoyco").attr('checked');
        if(checked == true){
            $("#pauta_autoyco").show();
            if(countAuto == 0){
                $.get("./taller_dd/tdd_obtenerEnunciados.php?id_actividad="+$("#fca_id_actividad").val()+"&id_tipo=1&id_diseno="+$("#fcp_id_diseno").val()+"&previos=1", function(data) {
                    if(data.length > 10){ reutilizarRubrica(data, 1, $("#fca_id_actividad").val(), +$("#fcp_id_diseno").val() ); }
                });  
            }
        }else{
            $("#pauta_autoyco").hide();            
        }
    }

    function evalProdHeteroOnChange(){
        countProdHet = $('#agregar_pauta_prodhetero ul li').size();
    
        var checked = $("#fca_eval_prodhetero").attr('checked');
        if(checked == true){
            $("#pauta_prodhetero").show();
            if(countProdHet == 0){
                $.get("./taller_dd/tdd_obtenerEnunciados.php?id_actividad="+$("#fca_id_actividad").val()+"&id_tipo=4&id_diseno="+$("#fcp_id_diseno").val()+"&previos=1", function(data) {
                    if(data.length > 10){ reutilizarRubrica(data, 4, $("#fca_id_actividad").val(), +$("#fcp_id_diseno").val() );  }
                });    
            }
        }else{
            $("#pauta_prodhetero").hide();            
        }
    }

    function evalEcoOnChange(){
        countEco = $('#agregar_pauta_eco ul li').size();
        
        var checked = $("#fca_eval_eco").attr('checked');
        if(checked == true){
            $("#pauta_eco").show();
            if(countEco == 0){
                $.get("./taller_dd/tdd_obtenerEnunciados.php?id_actividad="+$("#fca_id_actividad").val()+"&id_tipo=5&id_diseno="+$("#fcp_id_diseno").val()+"&previos=1", function(data) {
                    if(data.length > 10){ reutilizarRubrica(data, 5, $("#fca_id_actividad").val(), +$("#fcp_id_diseno").val() ); }
                }); 
            }
        }else{
            $("#pauta_eco").hide();            
        }
    }   
    
    function trabajosNoChange(){
        var checked = $("#fca_medios_trabajos_no").attr('checked');
        if(checked == true){
            var valor = getMediosTrabajosValue();
            if(valor == -1){
                $("input[name='fca_medios_trabajos'][value='2']").attr('checked', true);
                medioOnSelect();
            }
            $("#control_trabajos").show();
            $("#control_eval").show();
        }else{
            $("#control_trabajos").hide();            
            $("#control_eval").hide();            
        }
    }
    function actualizarArchivos(){
        idActividad = document.getElementById('fca_id_actividad').value;
        $.get('./taller_dd/tdd_obtenerArchivos.php?id_actividad='+idActividad, function(data) {
            $('#subir_archivo').html(data);
        }); 
    }
    function actualizarPautas(tipo){
        idActividad = document.getElementById('fca_id_actividad').value;
        $.get('./taller_dd/tdd_obtenerEnunciados.php?id_actividad='+idActividad+'&id_tipo='+tipo+'&id_diseno='+$("#fcp_id_diseno").val(), function(data) {
            if(tipo == 1){
                $('#agregar_pauta_autoyco').html(data);
            }else if(tipo == 4){
                $('#agregar_pauta_prodhetero').html(data);
            }else if(tipo == 5){
                $('#agregar_pauta_eco').html(data);
            }
        }); 
    }
    
    function actualizarComentariosActividad(){
        idActividad = document.getElementById('fca_id_actividad').value;
        $.get('./taller_dd/tdd_obtenerComentariosActividad.php?id_actividad='+idActividad+'&tipo=1&end='+end_comentario_act, function(data) {
            $('#tdd_lista_comentarios_act').html(data);
        });        
    }
    function verMasComentariosActividad(){
        end_comentario_act= end_comentario_act + ver_mas_comentario_act;
        actualizarComentariosActividad();
    }    
    
    function eliminarArchivo(idArchivo, nombreArchivo, orden){        
        idActividad = document.getElementById('fca_id_actividad').value;        
     
        var $dialog = $('<div><p><br></br><?php echo $lang_nueva_actividad_elim_arch1; ?></p></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_nueva_actividad_elim_arch2; ?>',
            dialogClass: 'uii-dialog',
            width: 500,
            height: 150,
            zIndex: 3999,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            },
             buttons: {
                "<?php echo $lang_mis_disenos_cancelar; ?>": function() { 
                   $(this).dialog("close"); 
                },                 
                "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                    eliminarArchivoOK(idArchivo, nombreArchivo, idActividad, orden);
                    $(this).dialog("close");
                } 
             }            
        });
        $dialog.dialog('open');
        return false; 
    }
    
    function eliminarArchivoOK(idArchivo, nombreArchivo, idActividad, orden){
        $.get('./taller_dd/tdd_eliminarArchivo.php?id_archivo='+idArchivo+"&id_actividad="+idActividad+"&nombre_archivo="+nombreArchivo+"&orden="+orden, function(data) {
            actualizarArchivos();
        });        
    }
    function formEnunciadoSubmit(tipo){ 
        
        url = './taller_dd/tdd_agregarEnunciado.php';
        var enunText = "";
        if(tipo == 'autoyco'){ enunText = $("#fcp_enunciado_autoyeco").val();}
        else if(tipo == 'prodhetero'){enunText = $("#fcp_enunciado_prodhetero").val(); }
        else if(tipo == 'eco'){enunText = $("#fcp_enunciado_eco").val(); }
        
        if(enunText.length <= 3){ alert("<?php echo $lang_tdd_form_debe_ingresar_enunciado; ?>"); return false;}
        
        var ununId = -1;

        for(var i=0; i<enunciadosArray.length; i++ ){
            if(enunciadosArray[i].enu_contenido == enunText){ ununId = enunciadosArray[i].enu_id_enunciado; }
        }
        
        var form = null;
        if(tipo == 'autoyco'){
            $("#form_agregar_pauta_autoyco #fcp_id_enunciado").val(ununId);
            form = $("#form_agregar_pauta_autoyco");
        }else if(tipo == 'prodhetero'){
            $("#form_agregar_pauta_prodhetero #fcp_id_enunciado").val(ununId);
            form = $("#form_agregar_pauta_prodhetero");
        }else if(tipo == 'eco'){
            $("#form_agregar_pauta_eco #fcp_id_enunciado").val(ununId);
            form = $("#form_agregar_pauta_eco");            
        }
        $.post(url, form.serialize(), function(result) {            
            if(result == '1'){
                if(tipo == 'autoyco'){
                    $("#fcp_enunciado_autoyeco").val("");
                }else if(tipo == 'prodhetero'){
                    $("#fcp_enunciado_prodhetero").val("");
                }else if(tipo == 'eco'){
                    $("#fcp_enunciado_eco").val("");
                }
                
                $.get("./taller_dd/tdd_obtenerEnunciados.php?id_actividad="+idActividad+"&id_tipo="+tipo+'&id_diseno='+$("#fcp_id_diseno").val(), function(data) {
                    if(tipo == 'autoyco'){
                        $("#agregar_pauta_autoyco").html(data);
                    }else if(tipo == 'prodhetero'){
                        $("#agregar_pauta_prodhetero").html(data);
                    }else if(tipo == 'eco'){
                        $("#agregar_pauta_eco").html(data);
                    }

                });
                
            }
        });       
    }
    
    function eliminarPauta(idPauta, idActividad, idRubrica, orden, tipo){
        
        var $dialog = $('<div><p><br></br><?php echo $lang_nueva_actividad_elim_pauta1; ?></p></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_nueva_actividad_elim_pauta2; ?>',
            dialogClass: 'uii-dialog',
            width: 500,
            height: 150,
            zIndex: 3999,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            },
             buttons: {
                "<?php echo $lang_mis_disenos_cancelar; ?>": function() { 
                   $(this).dialog("close"); 
                },
                "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                    eliminarPautaOK(idPauta, idActividad, idRubrica, orden, tipo);
                    $(this).dialog("close");
                } 
             }            
        });
        $dialog.dialog('open');
        return false;    
    }
  
    function eliminarPautaOK(idPauta, idActividad, idRubrica, orden, tipo){ 
        $.get('./taller_dd/tdd_eliminarEnunciado.php?id_pauta='+idPauta+'&id_actividad='+idActividad+'&id_rubrica='+idRubrica+'&orden='+orden, function(data) {
            actualizarPautas(tipo);
        });        
    }
 
    $(document).ready(function(){
        //nicEditors.allTextAreas({buttonList : ['bold','italic','underline','ol','ul','indent','outdent','link','unlink'], maxHeight : 80});
        nicEditors.elemById({buttonList : ['bold','italic','underline','ol','ul','indent','outdent','link','unlink','removeformat'], maxHeight : 80},['fca_aprendizaje_esperado','fca_evidencia_aprendizaje','fca_descripcion_general','fca_medios_otros','fca_inicio','fca_desarrollo','fca_cierre','fca_consejos','fca_medios']);
        
        <?php
            foreach ($_ta_actividad as $key => $value) {

            //se pone el texto de ayuda en el textarea
            echo "if(nicEditors.findEditor('".$value."').getContent()==''|| nicEditors.findEditor('".$value."').getContent()=='<br>'){".
             " nicEditors.findEditor('".$value."').setContent('".$ayuda['actividad'][$value]."');".
             " $('#nicEdit-".$value."').css('color','#969696')};";

            //se define la funcion focus para los textarea
            echo "$('#nicEdit-".$value."').focus(function() {".
             "if (nicEditors.findEditor('".$value."').getContent() == '".$ayuda['actividad'][$value]."')".
             "     nicEditors.findEditor('".$value."').setContent('');".
             "$('#nicEdit-".$value."').css('color','#333333');".
             "  });";

            //se define la funcion blur para los textarea
            echo "$('#nicEdit-".$value."').blur(function() {".
             "if (nicEditors.findEditor('".$value."').getContent() == ''|| nicEditors.findEditor('".$value."').getContent()=='<br>'){".
             "    nicEditors.findEditor('".$value."').setContent('".$ayuda['actividad'][$value]."');".
             "$('#nicEdit-".$value."').css('color','#969696');}".
             "  });";
            
            }
        ?>
         $('.nicEdit-contenedor').css("min-height","40px");
        <?php
        //se crean las funciones para mostrar los textos de ayuda
//        foreach ($ayuda['actividad'] as $key => $value) {
//            echo "document.getElementById('nicEdit-" . $key . "').onclick = function(){mostrarAyudaActividad('" . $value . "','nicEdit-" . $key . "');};";
//        }
        for($z=0; $z<count($_ta_actividad); $z++){
            echo "document.getElementById('nicEdit-" . $_ta_actividad[$z] . "').onclick = function(){mostrarAyudaActividad('" . $ayuda['actividad'][$_ta_actividad[$z]] . "','" . $_ta_actividad[$z] . "');};";
            
        }
        foreach ($ayuda['actividad'] as $key => $value){
            if($key=='fca_nombre' || $key=='fca_horas'){
                echo "document.getElementById('".$key."').onclick = function(){mostrarAyudaActividad('".$value."','".$key."');};";
            }
            echo "document.getElementById('".$key."_ayuda').onclick = function(){mostrarDivAyudaActividad();};";
        }        
        ?>        
        trabajosNoChange();
        medioOnSelect();
        evalAutoyCoOnChange();
        evalProdHeteroOnChange();
        evalEcoOnChange();
        actualizarComentariosActividad();
        
        $("#form_crear_actividad").validate({
            rules:{
                fca_nombre:{
                    required: true,
                    minlength:6
                },
                fca_horas:{
                    required: true,
                    min: 0,
                    max: 6
                }
            },
            messages:{
                fca_nombre:{
                    required: '<?php echo $lang_nueva_actividad_requerido1; ?>',
                    minlength: '<?php echo $lang_nueva_actividad_requerido2; ?>'
                },
                fca_horas:{
                    required: '<?php echo $lang_nueva_actividad_requerido3; ?>',
                    min: '<?php echo $lang_nueva_actividad_requerido4; ?>',
                    max: '<?php echo $lang_nueva_actividad_requerido4; ?>'
                }                
            },
            ignore: ".ignore-validation",
            submitHandler: function() {
                //if($("#fca_medios_trabajos").val() == 3 && $("#fca_complementaria").val() == 0){
                if(getMediosTrabajosValue() == 3 && $("#fca_complementaria").val() == 0){
                    var $dialog999 = $('<div><p><br><?php echo $lang_nueva_actividad_revis1; ?>.</p></div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_nueva_actividad_revis2; ?>',
                        dialogClass: 'uii-dialog',
                        width: 450,
                        height: 150,
                        zIndex: 3999,
                        modal: true,
                        close: function(ev, ui) {
                            $(this).remove();
                        },
                        buttons: {
                            "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                    $dialog999.dialog('open');
                    return false;
                }
            
            <?php 
            foreach ($_ta_actividad as $key => $value) {  
                echo "nicEditors.findEditor('" . $value . "').saveContent();";
            }
            ?>
                url = './taller_dd/tdd_guardar_actividad.php?';

                $.post(url, $("#form_crear_actividad").serialize(), function(result) {

                    if (parseInt(result)< 0){
                        $(".error_form_crear_actividad").html("<div><?php echo $lang_nueva_actividad_guardar_err; ?> </div>");
                        mensajeEvento("<?php echo $lang_nueva_actividad_guardar; ?>", "<?php echo $lang_nueva_actividad_guardar_err; ?>");
                        $("#error_form_crear_diseno").show();
                    }
                    else{
                        $(".error_form_crear_actividad").html("<div><?php echo $lang_nueva_actividad_guardar_ok ?> </div>");
                        mensajeEvento("<?php echo $lang_nueva_actividad_guardar; ?>", "<?php echo $lang_nueva_actividad_guardar_ok; ?>");
                        $("#error_form_crear_diseno").show();
                        id_etapa= <?php echo $_actividad[0]['ac_id_etapa']; ?>;
                        orden_etapa= <?php echo $orden_etapa; ?>;
                        //abajo= <?php echo $abajo;  ?>;
                        //arriba= <?php echo $arriba;  ?>;   
                        actualizarEtapa(id_etapa, orden_etapa);
                        //actualizarActividad(document.getElementById('fca_id_actividad').value, orden_etapa, abajo, arriba, document.getElementById('fca_tipo_lugar').value);
                    }
                });
            }
        });
        
        $("#form_subir_archivo").validate({
            submitHandler: function() {
                url = './taller_dd/tdd_subirArchivo.php?';

                $.post(url, $("#form_subir_archivo").serialize(), function(data) {
                    
                });
            }
        }); 
        
        $("#form_tdd_comentariosAct").validate({
            rules:{
                dc_texto_comentario_act:{
                    required: true,
                    minlength:6
                }
            },
            messages:{
                dc_texto_comentario_act:{
                    required: '<?php echo $lang_nueva_actividad_requerido1; ?>',
                    minlength: '<?php echo $lang_nueva_actividad_requerido5; ?>'
                }                
            },            
            submitHandler: function() {
                url = './taller_dd/tdd_enviarComentarioActividad.php?';

                $.post(url, $("#form_tdd_comentariosAct").serialize(), function(data) {
                     document.getElementById('dc_texto_comentario_act').value = '';
                     actualizarComentariosActividad();
                });
            }
        });
        
        $("#fcp_enunciado_autoyeco").autocomplete(enunArray, {
            minChars: 0,
            width: 258,
            max: 12,
            autoFill: false,
            matchContains: true,
            highlight: false,
            multiple: false,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $("#fcp_enunciado_prodhetero").autocomplete(enunArray, {
            minChars: 0,
            width: 258,
            max: 12,
            autoFill: false,
            matchContains: true,
            highlight: false,
            multiple: false,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $("#fcp_enunciado_eco").autocomplete(enunArray, {
            minChars: 0,
            width: 258,
            max: 12,
            autoFill: false,
            matchContains: true,
            highlight: false,
            multiple: false,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
    });
    
</script>    
</body>
</html>
