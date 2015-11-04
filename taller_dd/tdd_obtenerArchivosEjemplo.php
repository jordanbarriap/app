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
    
    $_archivos = obtenerArchivosEjemploFuncion($conexion);
    $maxLengh = 70;
    $totalArchivos= count($_archivos);
    echo "<br><div>".$lang_tdd_obtarcej_lista_archivos."</div><br>";
    if($totalArchivos> 0){
        echo '<ul>';
        for($i=0 ; $i< $totalArchivos; $i++){

            $descripcion = $_archivos[$i]['ae_descripcion'];
            if(strlen($descripcion) > $maxLengh) $descripcion= substr($descripcion,0,strrpos(substr($descripcion,0,$maxLengh-3)," "))."...";

            echo '<li class ="li_mis_archivo" id="'.$_archivos[$i]['ae_id'].'" >';
            if ($_archivos[$i]['ae_nombre']==""){
               echo '<div title="'.$_archivos[$i]['ae_descripcion'].'">'.$_archivos[$i]['ae_descripcion'].'</div>';
            }
            else{
            echo '<div title="'.$_archivos[$i]['ae_descripcion'].'">'.$_archivos[$i]['ae_nombre'].'</div>';
            }
            echo '<a id="eliminar_archivo" class="link_mis_archivos" name="eliminar_archivo" onClick="eliminarArchivoEjemplo('.$_archivos[$i]['ae_id'].',\''.$_archivos[$i]['ae_nombre'].'\')">'.$lang_nueva_actividad_eliminar.'</a>';
            if ($_archivos[$i]['ae_nombre']!=""){
            echo '<a id="ver_archivo" class="link_mis_archivos" name="ver_archivo" href="'.$config_ruta_archivo_ejemplo."/".$_archivos[$i]['ae_nombre'].'" target="_black">'.$lang_nueva_actividad_ver.'</a>';
            }
            echo '</li>';        
        }
        echo '</ul>';
    }else{
        echo '<div>'.$lang_tdd_obtarcej_archivos_no_agregados.'</div>';
    }
    
dbDesconectarMySQL($conexion);    
?>