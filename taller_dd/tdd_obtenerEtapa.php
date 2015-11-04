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
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    $id_etapa           = $_GET["id_etapa"];
    $orden_etapa        = $_GET["orden_etapa"];
    $abajo=0;
    $arriba=0;

    $acceso_admin = false;
    if(isset($_GET["admin"])){
        $acceso_admin = true;
    }
    $_act_etapa = obtenerActividadesPorEtapaFuncion($id_etapa, $conexion);
    $count_etapa = count($_act_etapa);
    $txt = "";
    if($orden_etapa == 1) $txt = $lang_crear_diseno_admin_etapa1;
    if($orden_etapa == 2) $txt = $lang_crear_diseno_admin_etapa2;
    if($orden_etapa == 3) $txt = $lang_crear_diseno_admin_etapa3;

    echo '<div id="title_etapa_'.$orden_etapa.'" class="titulo_etapa">'.$txt.'</div> ';
    
            $string_actividad="";
            
            $maxLenghTitulo = 45;
            $maxLenghDesc = 150;
            
            for($i=0 ; $i<count($_act_etapa); $i++){
                
                $str_images = '';
                $str_image_lab= '';
                $str_images2= '';
                $comentarios = buscarExistenciaComentariosFuncion($_act_etapa[$i]['ac_id_actividad'], 1, $conexion);
                if($comentarios[0]['cont']>0){
                    $str_images2= '<img src="img/comentarios_16.png" alt="">';
                }else{
                    $str_images2= '<img src="taller_dd/img/transp_16.png" alt="">';
                }                 
                if($_act_etapa[$i]['ac_medios_bitacora'] == 1){
                    $str_images .= '<img src="img/bitacora.png" class="web20_actividad_mini" alt="">';
                }
                if($_act_etapa[$i]['ac_medios_web2'] == 1){
                    $herram = obtenerHerramientaDisenoFuncion($_act_etapa[$i]['e_id_diseno_didactico'], $conexion);        
                    if(isset($herram[0]['hw_imagen']))$str_images .= '<img src="img_herramientas/'.$herram[0]['hw_imagen'].'" class="web20_actividad_mini" alt="">';
                }
                if($_act_etapa[$i]['ac_medios_trabajos'] > 1){
                    if($_act_etapa[$i]['ac_medios_trabajos'] == 2){
                    $str_images .= '<img src="img/act_publicacion.png" class="web20_actividad_mini" alt="">';
                    }
                    if($_act_etapa[$i]['ac_medios_trabajos'] == 3){
                    $str_images .= '<img src="img/act_revision.gif" class="web20_actividad_mini" alt="">';
                    }
                }
                if($str_images != ''){
                    $str_images = '<div class="web20_actividad_div">'.$str_images.'</div>';
                }else{
                    $str_images = '<div class="web20_actividad_div"></div>';
                }
                
                
                if($i == 0){ $arriba=0; $id_etapa=$_act_etapa[$i]['ac_id_etapa'];}else{ $arriba=1; }
                if($i == $count_etapa-1){ $abajo=0; }else{ $abajo=1; }
                if($_act_etapa[$i]['ac_tipo']==$actividad_laboratorio || $_act_etapa[$i]['ac_tipo']==$actividad_casa){$class='_lab';$str_image_lab='<img src="img/laboratorio.png" alt="">';}else{$class='_sala';}
                if(strlen($_act_etapa[$i]['ac_nombre']) > $maxLenghTitulo) $_act_etapa[$i]['ac_nombre']= substr($_act_etapa[$i]['ac_nombre'],0,strrpos(substr($_act_etapa[$i]['ac_nombre'],0,$maxLenghTitulo)," "));
                if(strlen($_act_etapa[$i]['ac_descripcion']) > $maxLenghDesc) $_act_etapa[$i]['ac_descripcion']= substr($_act_etapa[$i]['ac_descripcion'],0,strrpos(substr($_act_etapa[$i]['ac_descripcion'],0,$maxLenghDesc)," "));
                           
                $string_actividad.= '<div id="actividad_'.$_act_etapa[$i]['ac_id_actividad'].'" class="actividad'.$class.'">';
                $string_actividad.= '<table style="max-width: 200px;">';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="4"><a style="cursor:pointer;" title="'.$lang_tdd_obtetapa_editar_actividad.'" onClick="abrirActividad('.$_act_etapa[$i]['ac_id_actividad'].','.$orden_etapa.','.$abajo.','.$arriba.','.$id_etapa.');"><div class="titulo_actividad_mini">'.$_act_etapa[$i]['ac_nombre'].'</div></a></td>';
                $string_actividad.= '<td colspan="2"><div class="iconos_actividad_mini">'.$str_images2.$str_image_lab.'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="6">'.$str_images.'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="6"><div class="desc_actividad_mini">'.$_act_etapa[$i]['ac_descripcion'].'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td class="td1"><input id="modificar_actividad" title="'.$lang_crear_diseno_editar_act.'"  name="'.$_act_etapa[$i]['ac_id_actividad'].'" class="boton_modificar_actividad" type="button" value="" onClick="abrirActividad('.$_act_etapa[$i]['ac_id_actividad'].','.$orden_etapa.','.$abajo.','.$arriba.','.$id_etapa.');"/></td>';
                if($acceso_admin){
                    $string_actividad.= '<td class="td2"></td>';
                }else{
                    $string_actividad.= '<td class="td2"><input id="eliminar_actividad" title="'.$lang_crear_diseno_elim_act.'" name="'.$_act_etapa[$i]['ac_id_actividad'].'"  class="boton_eliminar_actividad" type="button" value="" onClick="eliminarActividad('.$_act_etapa[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                }
                
                if($orden_etapa > 1){
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_izquierda" title="'.$lang_tdd_obtetapa_mover_izquierda .'" class="boton_mover_izquierda" type="button" value="" onClick="moverIzquierdaActividad('.$_act_etapa[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                if($abajo == 1){                
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_abajo" title="'.$lang_crear_diseno_admin_abajo.'" class="boton_mover_abajo" type="button" value="" onClick="bajarActividad('.$_act_etapa[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                if($arriba == 1){
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_arriba" title="'.$lang_crear_diseno_admin_arriba.'" class="boton_mover_arriba" type="button" value="" onClick="subirActividad('.$_act_etapa[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                if($orden_etapa < 3){
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_derecha" title="'.$lang_tdd_obtetapa_mover_derecha .'" class="boton_mover_derecha" type="button" value="" onClick="moverDerechaActividad('.$_act_etapa[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_act_etapa[$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }                
                $string_actividad.= '</tr>';
                $string_actividad.= '</table>';                
                $string_actividad.= '</div>';                
            }
            
    echo $string_actividad;
    echo '<div class="agregar_actividad"><input id="agregar_actividad_etapa1" name="agregar_actividad_etapa1" class="boton_agregar_actividad" type="button" value="'.$lang_crear_diseno_agregar_etapa.' '.$orden_etapa.'" onClick="agregarActividad('.$id_etapa.','.$orden_etapa.','.$count_etapa.');"/></div>';

    dbDesconectarMySQL($conexion);    
?>