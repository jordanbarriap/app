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
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

    $id_actividad   = $_GET["id_actividad"];
    $orden_etapa    = $_GET["orden_etapa"];
    $abajo          = $_GET["abajo"];
    $arriba         = $_GET["arriba"];
        
    $_actividad = obtenerActividadFuncion($id_actividad, $conexion);    
  
    $string_actividad= '';
    $i=0;
    $id_etapa=$_actividad[$i]['ac_id_etapa'];    
    
    $maxLenghTitulo = 45;
    $maxLenghDesc = 150;
    
    $str_images = '';
    if($_actividad[$i]['ac_medios_bitacora'] == 1){
        $str_images .= '<img src="img/bitacora.png" class="web20_actividad_mini" alt="">';
    }
    if($_actividad[$i]['ac_medios_web2'] == 1){
        $herram = obtenerHerramientaDisenoFuncion($_actividad[$i]['e_id_diseno_didactico'], $conexion);        
        $str_images .= '<img src="img_herramientas/'.$herram[0]['hw_imagen'].'" class="web20_actividad_mini" alt="">';
    }
    if($_actividad[$i]['ac_medios_trabajos'] > 1){
        if($_actividad[$i]['ac_medios_trabajos'] == 2){
        $str_images .= '<img src="img/act_publicacion.png" class="web20_actividad_mini" alt="">';
        }
        if($_actividad[$i]['ac_medios_trabajos'] == 3){
        $str_images .= '<img src="img/act_revision.gif" class="web20_actividad_mini" alt="">';
        }
    }
    if($str_images != ''){
        $str_images = '<div class="web20_actividad_div">'.$str_images.'</div>';
    }else{
        $str_images = '<div class="web20_actividad_div"></div>';
    }    
    
    if(count($_actividad)>0){
        if(strlen($_actividad[$i]['ac_nombre']) > $maxLenghTitulo) $_actividad[$i]['ac_nombre']= substr($_actividad[$i]['ac_nombre'],0,strrpos(substr($_actividad[$i]['ac_nombre'],0,$maxLenghTitulo)," "));
        if(strlen($_actividad[$i]['ac_descripcion']) > $maxLenghDesc) $_actividad[$i]['ac_descripcion']= substr($_actividad[$i]['ac_descripcion'],0,strrpos(substr($_actividad[$i]['ac_descripcion'],0,$maxLenghDesc)," "));
                          
        $string_actividad.= '<table>';
        $string_actividad.= '<tr>';
        $string_actividad.= '<td colspan="2"><div class="titulo_actividad_mini">'.$_actividad[$i]['ac_nombre'].'</div></td>';
        $string_actividad.= '<td colspan="2"><div class="iconos_actividad_mini">'.'<img src="img/comentarios_16.png" alt="">'.'</div></td>';
        $string_actividad.= '</tr>';
        $string_actividad.= '<tr>';
        $string_actividad.= '<td colspan="4">'.$str_images.'</td>';
        $string_actividad.= '</tr>';
        $string_actividad.= '<tr>';
        $string_actividad.= '<td colspan="4"><div class="desc_actividad_mini">'.$_actividad[$i]['ac_descripcion'].'</div></td>';
        $string_actividad.= '</tr>';
        $string_actividad.= '<tr>';
        $string_actividad.= '<td class="td1"><input id="modificar_actividad" name="'.$_actividad[$i]['ac_id_actividad'].'" class="boton_modificar_actividad" type="button" value="" onClick="abrirActividad('.$_actividad[$i]['ac_id_actividad'].','.$orden_etapa.','.$abajo.','.$arriba.');"/></td>';
        $string_actividad.= '<td class="td2"><input id="eliminar_actividad" name="'.$_actividad[$i]['ac_id_actividad'].'"  class="boton_eliminar_actividad" type="button" value="" onClick="eliminarActividad('.$_actividad[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividad[$i]['ac_orden'].');"/></td>';
        if($abajo == 1){                
            $string_actividad.= '<td class="td3" align=center><input name="mover_actividad_abajo" class="boton_mover_abajo" type="button" value="" onClick="bajarActividad('.$_actividad[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividad[$i]['ac_orden'].');"/></td>';
        }else{
            $string_actividad.= '<td class="td3" align=center></td>';
        }
        if($arriba == 1){
            $string_actividad.= '<td class="td4" align=center><input name="mover_actividad_arriba" class="boton_mover_arriba" type="button" value="" onClick="subirActividad('.$_actividad[$i]['ac_id_actividad'].','.$id_etapa.','.$orden_etapa.','.$_actividad[$i]['ac_orden'].');"/></td>';
        }else{
            $string_actividad.= '<td class="td4" align=center></td>';
        }
        $string_actividad.= '</tr>';
        $string_actividad.= '</table>';                
    }

    dbDesconectarMySQL($conexion);
    
    echo $string_actividad;   

?>