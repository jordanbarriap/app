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
    
    $idDiseno           = $_GET["id_diseno"];

    $acceso_admin = false;
    if(isset($_GET["admin"])){
        $acceso_admin = true;
    }

    $_etapas = obtenerEtapasFuncion($idDiseno, $conexion);
    $_actividades_etapa[1] = obtenerActividadesPorEtapaFuncion($_etapas[0]['e_id_etapa'], $conexion);
    $_actividades_etapa[2] = obtenerActividadesPorEtapaFuncion($_etapas[1]['e_id_etapa'], $conexion);
    $_actividades_etapa[3] = obtenerActividadesPorEtapaFuncion($_etapas[2]['e_id_etapa'], $conexion);
    
    $maxActividades = count($_actividades_etapa[1]);
    if(count($_actividades_etapa[2]) > $maxActividades) $maxActividades = count($_actividades_etapa[2]);
    if(count($_actividades_etapa[3]) > $maxActividades) $maxActividades = count($_actividades_etapa[3]);    

    echo $maxActividades.'$@%%@$';
    
    //$max_actividades=4; 
    $maxLenghTitulo = 45;
    $maxLenghDesc = 150;         
    
    for($j=1; $j<4; $j++){
        $etapa_text = $lang_crear_diseno_admin_etapa1;
        if($j==2)$etapa_text = $lang_crear_diseno_admin_etapa2;
        if($j==3)$etapa_text = $lang_crear_diseno_admin_etapa3;
        
?>
        <div id="etapa<?php echo $j; ?>" class="etapa<?php echo $j; ?>">
            <div id="title_epata_<?php echo $j; ?>" class="titulo_etapa"><?php echo $etapa_text; ?></div>            
<?php

            $orden_etapa = $j;
            $count_etapa1= count($_actividades_etapa[$j]);
            $id_etapa = $_etapas[0]['e_id_etapa'];
            $string_actividad = "";
            

            for($i=0 ; $i<count($_actividades_etapa[$j]); $i++){
                
                $str_images = '';
                $str_images2= '';
                $str_image_lab= '';
                $comentarios = buscarExistenciaComentariosFuncion($_actividades_etapa[$j][$i]['ac_id_actividad'], $j, $conexion);
                if(isset($comentarios[0]) && $comentarios[0]['cont']>0){
                    $str_images2= '<img src="img/comentarios_16.png" alt="" title="">';
                }else{
                    $str_images2= '<img src="taller_dd/img/transp_16.png" alt="">';
                }                 
                if($_actividades_etapa[$j][$i]['ac_medios_bitacora'] == 1){
                    $str_images .= '<img src="img/bitacora.png" class="web20_actividad_mini" alt="">';
                }
                if($_actividades_etapa[$j][$i]['ac_medios_web2'] == 1){
                    $herram = obtenerHerramientaDisenoFuncion($_actividades_etapa[$j][$i]['e_id_diseno_didactico'], $conexion);        
                    if(isset($herram[0]['hw_imagen']))$str_images .= '<img src="img_herramientas/'.$herram[0]['hw_imagen'].'" class="web20_actividad_mini" alt="">';
                }
                if($_actividades_etapa[$j][$i]['ac_medios_trabajos'] > 1){
                    if($_actividades_etapa[$j][$i]['ac_medios_trabajos'] == 2){
                    $str_images .= '<img src="img/act_publicacion.png" class="web20_actividad_mini" alt="">';
                    }
                    if($_actividades_etapa[$j][$i]['ac_medios_trabajos'] == 3){
                    $str_images .= '<img src="img/act_revision.gif" class="web20_actividad_mini" alt="">';
                    }
                }
                if($str_images != ''){
                    $str_images = '<div class="web20_actividad_div">'.$str_images.'</div>';
                }else{
                    $str_images = '<div class="web20_actividad_div"></div>';
                }                
                
                if($i == 0){ $arriba=0; $id_etapa=$_actividades_etapa[$j][$i]['ac_id_etapa'];}else{ $arriba=1; }
                if($i == $count_etapa1-1){ $abajo=0; }else{ $abajo=1; }
                if($_actividades_etapa[$j][$i]['ac_tipo']==$actividad_laboratorio || $_actividades_etapa[$j][$i]['ac_tipo']==$actividad_casa){$class='_lab';$str_image_lab='<img src="img/laboratorio.png" alt="">';}else{$class='_sala';}
                if(strlen($_actividades_etapa[$j][$i]['ac_nombre']) > $maxLenghTitulo) $_actividades_etapa[$j][$i]['ac_nombre']= substr($_actividades_etapa[$j][$i]['ac_nombre'],0,strrpos(substr($_actividades_etapa[$j][$i]['ac_nombre'],0,$maxLenghTitulo)," "));
                if(strlen($_actividades_etapa[$j][$i]['ac_descripcion']) > $maxLenghDesc) $_actividades_etapa[$j][$i]['ac_descripcion']= substr($_actividades_etapa[$j][$i]['ac_descripcion'],0,strrpos(substr($_actividades_etapa[$j][$i]['ac_descripcion'],0,$maxLenghDesc)," "));

                $string_actividad.= '<div id="actividad_'.$_actividades_etapa[$j][$i]['ac_id_actividad'].'" class="actividad'.$class.'">';
                $string_actividad.= '<table style="max-width: 200px;">';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="4"><a style="cursor:pointer;" title="'.$lang_tdd_obtetapas_editar_actividad.'" onClick="abrirActividad('.$_actividades_etapa[$j][$i]['ac_id_actividad'].','.$orden_etapa.','.$abajo.','.$arriba.','.$id_etapa.');"><div class="titulo_actividad_mini">'.$_actividades_etapa[$j][$i]['ac_nombre'].'</div></a></td>';
                $string_actividad.= '<td colspan="2"><div class="iconos_actividad_mini">'.$str_images2.$str_image_lab.'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="6">'.$str_images.'</td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td colspan="6"><div class="desc_actividad_mini">'.$_actividades_etapa[$j][$i]['ac_descripcion'].'</div></td>';
                $string_actividad.= '</tr>';
                $string_actividad.= '<tr>';
                $string_actividad.= '<td class="td1"><input id="modificar_actividad" title="'.$lang_tdd_obtetapas_editar_actividad.'" name="'.$_actividades_etapa[$j][$i]['ac_id_actividad'].'" class="boton_modificar_actividad" type="button" value="" onClick="abrirActividad('.$_actividades_etapa[$j][$i]['ac_id_actividad'].','.$orden_etapa.','.$abajo.','.$arriba.','.$id_etapa.');"/></td>';
                $string_actividad.= '<td class="td2"><input id="eliminar_actividad" title="'.$lang_tdd_obtetapas_eliminar_actividad.'" name="'.$_actividades_etapa[$j][$i]['ac_id_actividad'].'"  class="boton_eliminar_actividad" type="button" value="" onClick="eliminarActividad('.$_actividades_etapa[$j][$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividades_etapa[$j][$i]['ac_orden'].');"/></td>';
                
                if($orden_etapa > 1){
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_izquierda" title="'.$lang_tdd_obtetapa_mover_izquierda.'" class="boton_mover_izquierda" type="button" value="" onClick="moverIzquierdaActividad('.$_actividades_etapa[$j][$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividades_etapa[$j][$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                if($abajo == 1){                
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_abajo" title="'.$lang_tdd_obtetapas_mover_abajo.'" class="boton_mover_abajo" type="button" value="" onClick="bajarActividad('.$_actividades_etapa[$j][$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividades_etapa[$j][$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                if($arriba == 1){
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_arriba" title="'.$lang_tdd_obtetapas_mover_arriba.'" class="boton_mover_arriba" type="button" value="" onClick="subirActividad('.$_actividades_etapa[$j][$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividades_etapa[$j][$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                if($orden_etapa < 3){
                    $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_derecha" title="'.$lang_tdd_obtetapa_mover_derecha.'" class="boton_mover_derecha" type="button" value="" onClick="moverDerechaActividad('.$_actividades_etapa[$j][$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividades_etapa[$j][$i]['ac_orden'].');"/></td>';
                }else{
                    $string_actividad.= '<td class="td4" align=center></td>';
                }
                $string_actividad.= '</tr>';
                $string_actividad.= '</table>';                
                $string_actividad.= '</div>';                
            }
            
    echo $string_actividad;             
?>          
            <div class="agregar_actividad"><input id="agregar_actividad_etapa<?php echo $j;?>" name="agregar_actividad_etapa<?php echo $j;?>" class="boton_agregar_actividad" type="button" value="<?php echo $lang_crear_diseno_agregar_etapa; ?> <?php echo $j;?>" onClick="agregarActividad(<?php echo $id_etapa.','.$orden_etapa.','.$count_etapa1;?>);"/></div>  
        </div>
<?php
}
    dbDesconectarMySQL($conexion);
   
?> 