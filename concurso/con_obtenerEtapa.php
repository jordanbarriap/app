<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "concurso/inc/con_db_funciones.inc.php");
    require_once($ruta_raiz . "concurso/conf/con_config.php");

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    $id_etapa           = $_GET["id_etapa"];
    $orden_etapa        = $_GET["orden_etapa"];
    $abajo=0;
    $arriba=0;


    $_act_etapa = obtenerActividadesPorEtapaFuncion($id_etapa, $conexion);
    $count_etapa = count($_act_etapa);
    $txt = "";
    if($orden_etapa == 1) $txt = $lang_concurso_motivacion;
    if($orden_etapa == 2) $txt = $lang_concurso_desarrollo;
    if($orden_etapa == 3) $txt = $lang_concurso_evaluacion;

    echo '<div id="title_etapa_'.$orden_etapa.'" class="titulo_etapa">'.$lang_concurso_etapa.' '.$orden_etapa.': '.$txt.'</div> ';
    
            $string_actividad="";
            
            $maxLenghTitulo = 45;
            $maxLenghDesc = 150;
            
            for($i=0 ; $i<count($_act_etapa); $i++){
                
                $str_images = '';
                $str_images2 = '';
                $str_image_lab = '';
                $comentarios = '';
                
                
                if($i == 0){ $arriba=0; $id_etapa=$_act_etapa[$i]['ac_id_etapa_con'];}else{ $arriba=1; }
                if($i == $count_etapa-1){ $abajo=0; }else{ $abajo=1; }
                if($_act_etapa[$i]['ac_tipo']==$actividad_laboratorio){$class='_lab';$str_image_lab='<img src="../img/laboratorio.png" alt="">';}else{$class='_sala';$str_image_lab = '<img src="./img/transp_16.png" alt="">';}
                if(strlen($_act_etapa[$i]['ac_nombre']) > $maxLenghTitulo) $_act_etapa[$i]['ac_nombre']= substr($_act_etapa[$i]['ac_nombre'],0,strrpos(substr($_act_etapa[$i]['ac_nombre'],0,$maxLenghTitulo)," "));
                if(strlen($_act_etapa[$i]['ac_descripcion']) > $maxLenghDesc) $_act_etapa[$i]['ac_descripcion']= substr($_act_etapa[$i]['ac_descripcion'],0,strrpos(substr($_act_etapa[$i]['ac_descripcion'],0,$maxLenghDesc)," "));
                           
                $string_actividad.= '<div id="actividad_'.$_act_etapa[$i]['ac_id_actividad_con'].'" class="actividad'.$class.'">';
                $string_actividad.= '<table>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="2"><div class="titulo_actividad_mini">'.$_act_etapa[$i]['ac_nombre'].'</div></td>';
                $string_actividad.= '<td colspan="2"><div class="iconos_actividad_mini">'.$str_images2.$str_image_lab.'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="4">'.$str_images.'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="4"><div class="desc_actividad_mini">'.$_act_etapa[$i]['ac_descripcion'].'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td class="td1"><input id="modificar_actividad" title="'.$lang_concurso_editar_act.'" name="'.$_act_etapa[$i]['ac_id_actividad_con'].'" class="boton_modificar_actividad" type="button" value="" onClick="abrirActividad('.$_act_etapa[$i]['ac_id_actividad_con'].','.$orden_etapa.','.$abajo.','.$arriba.','.$id_etapa.');"/></td>';
                $string_actividad.= '<td class="td2"><input id="eliminar_actividad" title="'.$lang_concurso_eliminar_act.'" name="'.$_act_etapa[$i]['ac_id_actividad_con'].'"  class="boton_eliminar_actividad" type="button" value="" onClick="eliminarActividad('.$_act_etapa[$i]['ac_id_actividad_con'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                if($abajo == 1){                
                    $string_actividad.= '<td class="td3" align=center><input name="mover_actividad_abajo" title="'.$lang_concurso_mover_abajo.'" class="boton_mover_abajo" type="button" value="" onClick="bajarActividad('.$_act_etapa[$i]['ac_id_actividad_con'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td3" align=center></td>';
                }
                if($arriba == 1){
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_arriba" title="'.$lang_concurso_mover_arriba.'" class="boton_mover_arriba" type="button" value="" onClick="subirActividad('.$_act_etapa[$i]['ac_id_actividad_con'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                $string_actividad.= '</tr>';
                $string_actividad.= '</table>';                
                $string_actividad.= '</div>';                
            }
            
    echo $string_actividad;
    echo '<div class="agregar_actividad"><input id="agregar_actividad_etapa1" name="agregar_actividad_etapa1" class="boton_agregar_actividad" type="button" value="'.$lang_concurso_agregar_act_etapa.' '.$orden_etapa.'" onClick="agregarActividad('.$id_etapa.','.$orden_etapa.','.$count_etapa.');"/></div>';

    dbDesconectarMySQL($conexion);    
?>