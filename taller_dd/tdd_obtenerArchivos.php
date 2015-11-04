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
    
    $id_actividad           = $_GET["id_actividad"];

    $_archivos = obtenerArchivosFuncion($id_actividad, $conexion);
    $maxLengh = 70;
    $tipo = $lang_tdd_obtar_solo_profesor;
    $totalArchivos= count($_archivos);
    
    echo '<ul>';
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
        }
        else{
        echo '<div title="'.$_archivos[$i]['a_descripcion'].'">'.$_archivos[$i]['a_nombre_archivo']." - ".$tipo.'</div>';
        }
        echo '<a id="eliminar_archivo" class="link_mis_archivos" name="eliminar_archivo" onClick="eliminarArchivo('.$_archivos[$i]['a_id_archivo'].',\''.$_archivos[$i]['a_nombre_archivo'].'\','.$_archivos[$i]['a_orden'].')">'.$lang_nueva_actividad_eliminar.'</a>';
        if ($_archivos[$i]['a_nombre_archivo']!=""){
        echo '<a id="ver_archivo" class="link_mis_archivos" name="ver_archivo" href="'.$config_ruta_actividades.$id_actividad."/".$_archivos[$i]['a_nombre_archivo'].'" target="_black">'.$lang_nueva_actividad_ver.'</a>';
        }
        if($_archivos[$i]['a_orden'] != $totalArchivos)echo '<input name="mover_archivo_abajo" class="boton_pauta_abajo" type="button" value="" onClick="bajarArchivo('.$_archivos[$i]['a_id_archivo'].','.$_archivos[$i]['a_orden'].','.$_archivos[$i]['a_id_actividad'].');"/>';
        if($_archivos[$i]['a_orden'] != 1)echo '<input name="mover_archivo_arriba" class="boton_pauta_arriba" type="button" value="" onClick="subirArchivo('.$_archivos[$i]['a_id_archivo'].','.$_archivos[$i]['a_orden'].','.$_archivos[$i]['a_id_actividad'].');"/>';
        echo '</li>';        
    }
    echo '</ul>';
    
dbDesconectarMySQL($conexion);    
?>