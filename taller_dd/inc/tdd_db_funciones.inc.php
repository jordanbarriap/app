<?php

    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

//$ruta_raiz = "./../";
$ruta_raiz = (dirname(__FILE__))."/../../";

require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");

$carpeta_subida_archivos = "dd/actividades";
$ruta_base= "./../";


function full_copy( $id_act_origen, $id_actividad_destino ) {
    global $ruta_base, $carpeta_subida_archivos;
    $source = $ruta_base.$carpeta_subida_archivos.'/'.$id_act_origen;
    $target = $ruta_base.$carpeta_subida_archivos.'/'.$id_actividad_destino;
    if ( is_dir( $source ) ) {
        @mkdir( $target );
        $d = dir( $source );
        while ( FALSE !== ( $entry = $d->read() ) ) {
            if ( $entry == '.' || $entry == '..' ) {
                continue;
            }
            $Entry = $source . '/' . $entry;
            if ( is_dir( $Entry ) ) {
                full_copy( $Entry, $target . '/' . $entry );
                continue;
            }
            copy( $Entry, $target . '/' . $entry );
        }

        $d->close();
    }else {
        copy( $source, $target );
    }
}

function obtenerActividadFuncion($idActividad, $conexion){
    $consulta = "SELECT * ".
                "FROM actividad a, etapa e ".
                "WHERE ".
                    "ac_id_actividad = ".$idActividad." ".
                    "AND a.ac_id_etapa = e.e_id_etapa ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    return $_datos;             
}

function agregarActividadFuncion($idEtapa, $conexion){
     
        $consulta = "SELECT MAX(ac_orden) as max_orden ".
                    "FROM actividad ".
                    "WHERE ".
                        "ac_id_etapa = ".$idEtapa." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        if(count($_datos)<1){$_datos[0]['max_orden']= 0;}
        $iniciadora=1;
        if($_datos[0]['max_orden']>0) $iniciadora=0;
        $consulta = "INSERT into actividad(".
                        "ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_iniciadora,ac_fecha_creacion, ac_horas_estimadas) ".
                    "VALUES('nombre actividad','descripción de actividad', ".$idEtapa.", ".($_datos[0]['max_orden']+1).", ".$iniciadora.", NOW(), 0)";        
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
                    
        if($_resultado){
            $consulta = "UPDATE etapa SET " .
                            "e_sesiones_estimadas = e_sesiones_estimadas +1 " .
                        "WHERE " .
                            "e_id_etapa = ".$idEtapa." ";
            $_result = dbEjecutarConsulta($consulta, $conexion);
            
        }
        
    return $_resultado;   
}

function obtenerActividadesPorEtapaFuncion($idEtapa, $conexion){    
     
    $consulta = "SELECT * ".
                "FROM actividad a, etapa e ".
                "WHERE ".
                    "ac_id_etapa = ".$idEtapa." ".
                    "AND a.ac_id_etapa = e.e_id_etapa ".
                "ORDER by ac_orden asc";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    return $_datos;     
}

function bajarActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion){
    
        $consulta = "UPDATE actividad SET " .
                        "ac_orden = ac_orden -1 " .
                    "WHERE " .
                        "ac_id_etapa = ".$id_etapa." ".
                        "AND ac_orden = ".($actividad_orden+1)." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $consulta = "UPDATE actividad SET " .
                        "ac_orden = ac_orden +1 " .
                    "WHERE " .
                        "ac_id_actividad = ".$id_actividad." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 

        if($actividad_orden == 1 && $orden_etapa == 1){
            $consulta = "UPDATE actividad SET " .
                            "ac_iniciadora = 0 " .
                        "WHERE " .
                            "ac_id_etapa = ".$id_etapa." ";
            
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $consulta = "UPDATE actividad SET " .
                            "ac_iniciadora = 1 " .
                        "WHERE " .
                            "ac_id_etapa = ".$id_etapa." ".
                            "AND ac_orden = 1";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);   
        }
        
}
function subirActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion){
    
        $consulta = "UPDATE actividad SET " .
                        "ac_orden = ac_orden +1 " .
                    "WHERE " .
                        "ac_id_etapa = ".$id_etapa." ".
                        "AND ac_orden = ".($actividad_orden-1)." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 
 
        
        $consulta = "UPDATE actividad SET " .
                        "ac_orden = ac_orden -1 " .
                    "WHERE " .
                        "ac_id_actividad = ".$id_actividad." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 

        if($actividad_orden == 2 && $orden_etapa == 1){
            $consulta = "UPDATE actividad SET " .
                            "ac_iniciadora = 0 " .
                        "WHERE " .
                            "ac_id_etapa = ".$id_etapa." "; 
            
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $consulta = "UPDATE actividad SET " .
                            "ac_iniciadora = 1 " .
                        "WHERE " .
                            "ac_id_etapa = ".$id_etapa." ".
                            "AND ac_orden = 1";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);   
        }        
             
}
function moverDerechaActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion){
    if($orden_etapa < 3){
        $consulta = "SELECT e_id_etapa FROM etapa ".
                    "WHERE e_id_diseno_didactico IN (SELECT e_id_diseno_didactico FROM `etapa` WHERE e_id_etapa = ".$id_etapa.") ".
                    "AND e_orden > ".$orden_etapa." ORDER BY e_orden ASC LIMIT 0,1";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $id_etapa_derecha = 0;
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $id_etapa_derecha= $fila['e_id_etapa'];
            }
        }
        
        if($id_etapa_derecha > 0){
            $consulta = "UPDATE actividad SET " .
                "ac_orden = ac_orden +1 " .
            "WHERE " .
                "ac_id_etapa = ".$id_etapa_derecha." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion); 
            
            $consulta = "UPDATE actividad SET " .
                            "ac_orden = ac_orden -1 " .
                        "WHERE " .
                            "ac_id_etapa = ".$id_etapa." ".
                            "AND ac_orden > ".$actividad_orden." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);            
            
            $consulta = "UPDATE actividad SET " .
                "ac_orden = 1, " .
                "ac_id_etapa = ".$id_etapa_derecha.", " .
                "ac_iniciadora = 0 " .
            "WHERE " .
                "ac_id_actividad = ".$id_actividad." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion); 
            
            if($orden_etapa == 1){
                $consulta = "UPDATE actividad SET " .
                                "ac_iniciadora = 1 " .
                            "WHERE " .
                                "ac_id_etapa = ".$id_etapa." ".
                                "AND ac_orden = 1 ";
                $_resultado = dbEjecutarConsulta($consulta, $conexion); 
            }
        }        
    }
}
function mueveIzquierdaActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion){
    if($orden_etapa > 1){
        $consulta = "SELECT e_id_etapa FROM etapa ".
                    "WHERE e_id_diseno_didactico IN (SELECT e_id_diseno_didactico FROM `etapa` WHERE e_id_etapa = ".$id_etapa.") ".
                    "AND e_orden < ".$orden_etapa." ORDER BY e_orden DESC LIMIT 0,1";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $id_etapa_izquierda = 0;
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $id_etapa_izquierda= $fila['e_id_etapa'];
            }
        }
        
        if($id_etapa_izquierda > 0){ 
            $consulta = "UPDATE actividad SET " .
                            "ac_orden = ac_orden - 1 " .
                        "WHERE " .
                            "ac_id_etapa = ".$id_etapa." ".
                            "AND ac_orden > ".$actividad_orden." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion); 

            $consulta = "SELECT MAX(ac_orden) as max_orden FROM actividad WHERE ac_id_etapa = ".$id_etapa_izquierda." ";

            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $maxOrden = 1;
            if($_resultado){
                while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $maxOrden= $fila['max_orden'];
                }
            }            
            
            $consulta = "UPDATE actividad SET " .
                "ac_orden = ".$maxOrden." +1, " .
                "ac_id_etapa = ".$id_etapa_izquierda." " .
            "WHERE " .
                "ac_id_actividad = ".$id_actividad." ";

            $_resultado = dbEjecutarConsulta($consulta, $conexion);            
        }
    }    
}

function eliminarActividadFuncion($id_diseno, $id_usuario, $id_actividad, $actividad_orden, $id_etapa, $conexion){

        $consulta = "SELECT ac_nombre from actividad " .
                    "WHERE " .
                    "ac_id_actividad = ".$id_actividad." AND ac_orden = ".$actividad_orden." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }    
        if(count($_datos) > 0){
            $consulta = "UPDATE actividad SET " .
                            "ac_orden = ac_orden -1 " .
                        "WHERE " .
                            "ac_id_etapa = ".$id_etapa." ".
                            "AND ac_orden > ".($actividad_orden)." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $consulta = "DELETE FROM archivo " .
                        "WHERE " .
                            "a_id_actividad = ".$id_actividad." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);        
            $consulta = "DELETE FROM por_evaluacion_actividad " .
                        "WHERE " .
                            "evac_id_actividad = ".$id_actividad." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);         
            $consulta = "DELETE FROM actividad " .
                        "WHERE " .
                            "ac_id_actividad = ".$id_actividad." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            if($_resultado){
                $nombre = '';
                if(isset($_datos[0]['ac_nombre'])) $nombre = $_datos[0]['ac_nombre'];
                agregarRegistroCambio($id_usuario, $id_diseno, $id_actividad, 1, 2, 'Se eliminó la actividad "'.$nombre.'"', $consulta, $conexion);
                $consulta = "UPDATE etapa SET " .
                                "e_sesiones_estimadas = e_sesiones_estimadas -1 " .
                            "WHERE " .
                                "e_id_etapa = ".$id_etapa." ";
                $_result = dbEjecutarConsulta($consulta, $conexion);
            }
            if($actividad_orden == 1){
                $consulta = "UPDATE actividad SET " .
                                "ac_iniciadora = 1 " .
                            "WHERE " .
                                "ac_id_etapa = ".$id_etapa." ".
                                "AND ac_orden = 1";
                $_resultado = dbEjecutarConsulta($consulta, $conexion);   
            }         
            return $_resultado;
        }else return false;
}

function obtenerDisenoFuncion($idDiseno, $conexion){
    
    $consulta = "SELECT * ".
                "FROM diseno_didactico LEFT JOIN herramientas_diseno ".
                    "ON diseno_didactico.dd_id_diseno_didactico = herramientas_diseno.hd_id_diseno_didactico  ".
                "WHERE ".
                    "dd_id_diseno_didactico = ".$idDiseno."";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    return $_datos;    
}

function obtenerEscalaPuntajeFuncion($escala, $conexion){
    $consulta = "SELECT esva_valor_numerico, eval_valor ".
                "FROM por_escala_valores pev, por_escalaev_escalaval eee ".
                "LEFT JOIN por_escala_evaluacion esc ON (esc.esc_id_escala_evaluacion = eee.id_escala_evaluacion) ".
                ", por_escala_valor ev ".
                "WHERE pev.esva_id_escala_valores = eee.id_escala_valores ".
                "AND esc.esc_tipo_escala = ".$escala." ".
                "AND ev.eval_id_escala_valor = esva_id_escala_valor ".
                "ORDER BY esva_orden ASC  ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    return $_datos; 
    
}

function obtenerEtapasFuncion($idDiseno, $conexion){
   
        $consulta = "SELECT * ".
                    "FROM etapa ".
                    "WHERE ".
                        "e_id_diseno_didactico = ".$idDiseno." ".
                    "ORDER by e_orden asc";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }

    return $_datos; 
}

function guardarActividadFuncion(   $id_usuario, $id_diseno, $id_actividad, $nombre, $horas, $inicio, $desarrollo, $cierre, $descripcion_general, 
                                    $publica_producto, $revisa_pares, $instrucciones_producto, $instrucciones_revision,
                                    $id_complementaria, $aprendizaje_esperado, $evidencia_aprendizaje, $medios, $tipo,  
                                    $medios_bitacora, $medios_trabajos, $medios_web2, $medios_otros, $consejos, $eval,
                                    $conexion){
    

    //Actualizamos la actividad
    $consulta = "UPDATE actividad ".
                "SET ".
                    "ac_nombre = '$nombre', ".	
                    "ac_horas_estimadas = $horas, ". 	 	 	 	
                    "ac_instrucciones_inicio = '$inicio', ".	 	 	
                    "ac_instrucciones_desarrollo = '$desarrollo', ". 	 	 	 	 	 	
                    "ac_instrucciones_cierre = '$cierre', ".	 	 	 	 	 	
                    "ac_descripcion = '$descripcion_general', ". 	 	 	
                    "ac_publica_producto = $publica_producto, ". 	 	 	 	
                    "ac_revisa_pares = $revisa_pares, ".	 	 	
                    "ac_instrucciones_producto = '$instrucciones_producto', ".	 	 	 	 	
                    "ac_instrucciones_revision = '$instrucciones_revision', ".	 	 	 	 	
                    "ac_id_complementaria = $id_complementaria, ".
                    "ac_aprendizaje_esperado = '$aprendizaje_esperado', ".
                    "ac_evidencia_aprendizaje = '$evidencia_aprendizaje', ".
                    "ac_medios = '$medios', ".
                    "ac_tipo = $tipo, ".
                    "ac_medios_bitacora = $medios_bitacora, ".
                    "ac_medios_trabajos = $medios_trabajos, ".
                    "ac_medios_web2 = $medios_web2, ".
                    "ac_consejos_practicos = '$consejos', ".
                    "ac_medios_otros = '$medios_otros', ".
                    "ac_eval_autoyco = ".$eval['autoyco'].", ".
                    "ac_eval_evaleco = ".$eval['evaleco'].", ".
                    "ac_eval_prodhetero = ".$eval['prodhetero']." ".
                "WHERE  ".
                    "ac_id_actividad = $id_actividad ";  
    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    if($_resultado == 1){
        agregarRegistroCambio($id_usuario, $id_diseno, $id_actividad, 1, 1, 'Se modificó la actividad "'.$nombre.'"', $consulta, $conexion);
    }
    return $_resultado;      

}

function guardarActividadFuncionZZZ($id_actividad, $nombre, $aprendizaje_esperado, $evidencia_aprendizaje, $descripcion_general, $tipo_lugar, $medios, $materiales, $inicio, $desarrollo, $cierre, $conexion){

        $consulta = "UPDATE actividad SET " .
                        "ac_nombre= '".$nombre."', " .
                        "ac_aprendizaje_esperado= '".$aprendizaje_esperado."', " .
                        "ac_evidencia_aprendizaje= '".$evidencia_aprendizaje."', " .
                        "ac_descripcion= '".$descripcion_general."', " .
                        "ac_tipo= '".$tipo_lugar."', " .
                        "ac_medios= '".$medios."', " .
                        "ac_material_requerido= '".$materiales."', " .
                        "ac_instrucciones_inicio= '".$inicio."', " .
                        "ac_instrucciones_desarrollo= '".$desarrollo."', " .
                        "ac_instrucciones_cierre= '".$cierre."' " .
                    "WHERE " .
                    "ac_id_actividad=".$id_actividad .
                    " ";
        $resultado = dbEjecutarConsulta($consulta, $conexion);          
    
}

function obtenerMisDisenosFuncion($idUsuario, $conexion){

        $consulta = "SELECT * ".
                    "FROM diseno_didactico d LEFT OUTER JOIN (herramientas_diseno HD LEFT OUTER JOIN herramientas_web HW ".
                        "ON HD.hd_id_herramienta = HW.hw_id_herramienta  ) " .
                        "ON d.dd_id_diseno_didactico = HD.hd_id_diseno_didactico ". 
                    "WHERE ".
                        "d.dd_id_autor = '".$idUsuario."' ".
                        "AND d.dd_publicado = 0 ".
                    "ORDER BY d.dd_nivel ASC, d.dd_fecha_creacion DESC";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        return $_datos;
      
}
function obtenerDisenosParticPublFuncion($idUsuario, $conexion){

        $consulta = "SELECT * ".
                    "FROM diseno_didactico d LEFT OUTER JOIN (herramientas_diseno HD LEFT OUTER JOIN herramientas_web HW ".
                        "ON HD.hd_id_herramienta = HW.hw_id_herramienta  ) " .
                        "ON d.dd_id_diseno_didactico = HD.hd_id_diseno_didactico ". 
                    "WHERE ".
                        "d.dd_id_autor = '".$idUsuario."' ".
                        "AND d.dd_publicado = 1 ".
                    "ORDER BY d.dd_nivel ASC, d.dd_fecha_creacion DESC";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        return $_datos;
      
}
function obtenerDisenosPublicadosFuncion($conexion){

        $consulta = "SELECT * ".
                    "FROM diseno_didactico d ". 
                    "WHERE ".
                        "d.dd_publicado = 1 ".
                    "ORDER BY d.dd_fecha_creacion DESC";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        return $_datos;
      
}
function obtenerDisenosPublicadosPorSectorFuncion($sector, $conexion){

        $consulta = "SELECT * ".
                    "FROM diseno_didactico d LEFT OUTER JOIN (herramientas_diseno HD LEFT OUTER JOIN herramientas_web HW ".
                        "ON HD.hd_id_herramienta = HW.hw_id_herramienta  ) " .
                        "ON d.dd_id_diseno_didactico = HD.hd_id_diseno_didactico ".
                    "WHERE ".
                        "d.dd_publicado = 1 ".
                        "AND d.dd_subsector = '".$sector."' ".
                    "ORDER BY d.dd_nivel ASC, d.dd_fecha_creacion DESC";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        return $_datos;
      
}

function obtenerDisenosParticipoFuncion($idUsuario, $conexion){
/*
            "LEFT OUTER JOIN (herramientas_diseno HD LEFT OUTER JOIN herramientas_web HW ".
            "ON HD.hd_id_herramienta = HW.hw_id_herramienta  ) " .
            "ON D.dd_id_diseno_didactico = HD.hd_id_diseno_didactico ".    
    */
        $consulta = "SELECT * ".
                    "FROM tdd_autores t, diseno_didactico d LEFT OUTER JOIN (herramientas_diseno HD LEFT OUTER JOIN herramientas_web HW ".
                        "ON HD.hd_id_herramienta = HW.hw_id_herramienta  ) " .
                        "ON d.dd_id_diseno_didactico = HD.hd_id_diseno_didactico ".                  
                    "WHERE ".
                        "t.ta_id_autor = ".$idUsuario." ".
                        "AND t.ta_id_diseno_didactico = d.dd_id_diseno_didactico ".
                        "AND t.ta_invitacion = 1 ".
                        "AND dd_publicado = 0 ".
                    "ORDER BY dd_nivel ASC, d.dd_fecha_creacion DESC";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        return $_datos;
      
}
function eliminarDisenoAdminFuncion($id_diseno, $conexion){
        $consulta = "SELECT COUNT(ed_id_diseno_didactico) as count_exp ".
                    "FROM experiencia_didactica ".
                    "WHERE ".
                        "ed_id_diseno_didactico = ".$id_diseno." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
    
        if($_datos[0]['count_exp'] == 0){
            //obtenemos las etapas
            $consulta = "SELECT e_id_etapa ".
                        "FROM etapa ".
                        "WHERE ".
                            "e_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $_datos_e=array();
            if($_resultado){
                while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $_datos_e[]=$fila;
                }
            }
            //Obtenemos las actividades
            //Quitamos  asociaciones con actividades
            $_datos_acti= array();
            for($i=0; $i< count($_datos_e); $i++){
                $consulta = "SELECT ac_id_actividad ".
                            "FROM actividad ".
                            "WHERE ".
                                "ac_id_etapa = ".$_datos_e[$i]['e_id_etapa']." ";
                $_resultado = dbEjecutarConsulta($consulta, $conexion);
                $_datos_acti[$i]=array();
                if($_resultado){
                    while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                        $_datos_acti[$i][]=$fila;
                    }
                }
            }
            for($i=0; $i< count($_datos_acti); $i++){
                for($j=0; $j< count($_datos_acti[$i]); $j++){
                    $consulta = "DELETE FROM archivo " .
                                "WHERE " .
                                    "a_id_actividad = ".$_datos_acti[$i][$j]['ac_id_actividad']." ";
                    $_resultado = dbEjecutarConsulta($consulta, $conexion);        

                    $consulta = "DELETE FROM rp_pauta_evaluacion " .
                                "WHERE " .
                                    "rpe_id_actividad = ".$_datos_acti[$i][$j]['ac_id_actividad']." ";
                    $_resultado = dbEjecutarConsulta($consulta, $conexion);         
                }            
            }     
            //Borramos actividades
            for($i=0; $i < count($_datos_e); $i++){    
                $consulta = "DELETE FROM actividad " .
                            "WHERE " .
                                "ac_id_etapa = ".$_datos_e[$i]['e_id_etapa']." ";
                $_resultado = dbEjecutarConsulta($consulta, $conexion);
            }
            //Borramos las etapas
            $consulta = "DELETE FROM etapa " .
                        "WHERE " .
                            "e_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            //Borramos las herramientas de esta diseño
            $consulta = "DELETE FROM herramientas_diseno " .
                        "WHERE " .
                            "hd_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            //Borramos los co-autores
            $consulta = "DELETE FROM tdd_autores " .
                        "WHERE " .
                            "ta_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            //Borramos el diseño
            $consulta = "DELETE FROM diseno_didactico " .
                        "WHERE " .
                            "dd_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion); 
        }else{
            $_resultado = 99;
        }
        return $_resultado;
   
}
function eliminarDisenoFuncion($id_usuario, $nombre, $id_diseno, $conexion){
   
        //obtenemos las etapas
        $consulta = "SELECT e_id_etapa ".
                    "FROM etapa ".
                    "WHERE ".
                        "e_id_diseno_didactico = ".$id_diseno." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos_e=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos_e[]=$fila;
            }
        }
        //Obtenemos las actividades
        //Quitamos  asociaciones con actividades
        $_datos_acti= array();
        for($i=0; $i< count($_datos_e); $i++){
            $consulta = "SELECT ac_id_actividad ".
                        "FROM actividad ".
                        "WHERE ".
                            "ac_id_etapa = ".$_datos_e[$i]['e_id_etapa']." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $_datos_acti[$i]=array();
            if($_resultado){
                while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $_datos_acti[$i][]=$fila;
                }
            }
        }
        for($i=0; $i< count($_datos_acti); $i++){
            for($j=0; $j< count($_datos_acti[$i]); $j++){
                $consulta = "DELETE FROM archivo " .
                            "WHERE " .
                                "a_id_actividad = ".$_datos_acti[$i][$j]['ac_id_actividad']." ";
                $_resultado = dbEjecutarConsulta($consulta, $conexion);  
                
                $consulta = "DELETE FROM por_evaluacion_actividad " .
                        "WHERE " .
                            "evac_id_actividad = ".$_datos_acti[$i][$j]['ac_id_actividad']." ";
                $_resultado = dbEjecutarConsulta($consulta, $conexion);  
            }            
        }     
        //Borramos actividades
        for($i=0; $i < count($_datos_e); $i++){    
            $consulta = "DELETE FROM actividad " .
                        "WHERE " .
                            "ac_id_etapa = ".$_datos_e[$i]['e_id_etapa']." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
        }
        //Borramos las etapas
            $consulta = "DELETE FROM etapa " .
                        "WHERE " .
                            "e_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
        //Borramos las herramientas de esta diseño
            $consulta = "DELETE FROM herramientas_diseno " .
                        "WHERE " .
                            "hd_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
        //Borramos los co-autores
            $consulta = "DELETE FROM tdd_autores " .
                        "WHERE " .
                            "ta_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
        //Borramos el diseño
            $consulta = "DELETE FROM diseno_didactico " .
                        "WHERE " .
                            "dd_id_diseno_didactico = ".$id_diseno." ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion); 

            if($_resultado == 1){
                agregarRegistroCambio($id_usuario, $id_diseno, 0, 0, 2, 'Se eliminó el diseño "'.$nombre.'"', $consulta, $conexion);
            }            
            
        return $_resultado;
   
}

/*Funcion que agrega un nuevo diseño didactico y retorna el id con el cual fue creado*/
function crearDisenoNuevaVersionFuncion($usuario, $idDisenoOriginal, $conexion){
    
        $consulta = "INSERT INTO diseno_didactico(dd_nombre, dd_nivel, dd_subsector, dd_id_autor, dd_descripcion, dd_manejo_tecnologico, dd_publicado, dd_objetivos_curriculares,dd_objetivos_transversales, dd_contenidos, dd_fecha_creacion,  dd_descripcion_e1, dd_descripcion_e2, dd_descripcion_e3, dd_tipo, dd_escala) ".
                    "SELECT dd_nombre, dd_nivel, dd_subsector, dd_id_autor, dd_descripcion, dd_manejo_tecnologico, dd_publicado, dd_objetivos_curriculares,dd_objetivos_transversales, dd_contenidos, dd_fecha_creacion,  dd_descripcion_e1, dd_descripcion_e2, dd_descripcion_e3, dd_tipo, dd_escala FROM diseno_didactico WHERE dd_id_diseno_didactico=".$idDisenoOriginal;

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_resultadoReturn = $_resultado;
        $idDisenoNuevo = mysql_insert_id($conexion);

        $consulta = "SELECT * ".
                    "FROM diseno_didactico ".
                        "LEFT OUTER JOIN herramientas_diseno ON dd_id_diseno_didactico = hd_id_diseno_didactico ".
                    "WHERE dd_id_diseno_didactico =".$idDisenoOriginal."";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_disenoOriginal=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_disenoOriginal[]=$fila;
            }
        }

        $consulta = "UPDATE diseno_didactico ".
                    "SET ".
                        "dd_nombre = '".$_disenoOriginal[0]['dd_nombre']." versión 2"."', ".
                        "dd_id_autor = ".$usuario.", ".
                        "dd_publicado = 0, ".
                        "dd_fecha_creacion = NOW(), ".
                        "dd_id_diseno_previo = ".$idDisenoOriginal." ".
                    "WHERE  ".
                        "dd_id_diseno_didactico = ".$idDisenoNuevo;  
        

        $_resultado = dbEjecutarConsulta($consulta, $conexion);        

        //Creamos la relacion con la herramienta web
        $consulta =     "INSERT into herramientas_diseno(hd_id_herramienta,hd_id_diseno_didactico) ".
                        "VALUES(".$_disenoOriginal[0]['hd_id_herramienta'].",".$idDisenoNuevo.")";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_etapas_ = obtenerEtapasFuncion($idDisenoOriginal, $conexion);        
        //#Creamos la Etapa 1 para el diseno recien creado
        $consulta =     "INSERT into etapa(e_id_diseno_didactico, e_nombre, e_tipo, e_sesiones_estimadas, e_descripcion,e_orden) ".
                        "VALUES(".$idDisenoNuevo.",'Motivación',1,'".$_etapas_[0]['e_sesiones_estimadas']."', '".$_disenoOriginal[0]['dd_descripcion_e1']."',1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapaNueva1 = mysql_insert_id($conexion);

        //#Creamos la Etapa 2 para el diseno recien creado
        $consulta =     "INSERT into etapa(e_id_diseno_didactico, e_nombre, e_tipo, e_sesiones_estimadas, e_descripcion,e_orden) ".
                        "VALUES(".$idDisenoNuevo.",'Desarrollo',2,'".$_etapas_[1]['e_sesiones_estimadas']."', '".$_disenoOriginal[0]['dd_descripcion_e2']."',2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapaNueva2 = mysql_insert_id($conexion);

        //#Creamos la Etapa 3 para el diseno recien creado
        $consulta =     "INSERT into etapa(e_id_diseno_didactico, e_nombre, e_tipo, e_sesiones_estimadas, e_descripcion,e_orden) ".
                        "VALUES(".$idDisenoNuevo.",'Evaluación',3,'".$_etapas_[2]['e_sesiones_estimadas']."', '".$_disenoOriginal[0]['dd_descripcion_e3']."',3)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapaNueva3 = mysql_insert_id($conexion);        
    
        //Creamos las copias para actividades del diseño recien creado.
        $_etapasOriginal = obtenerEtapasFuncion($idDisenoOriginal, $conexion);
        
        $_actividadesEtapa1= obtenerActividadesPorEtapaFuncion($_etapasOriginal[0]['e_id_etapa'], $conexion);
        $_actividadesEtapa2= obtenerActividadesPorEtapaFuncion($_etapasOriginal[1]['e_id_etapa'], $conexion);
        $_actividadesEtapa3= obtenerActividadesPorEtapaFuncion($_etapasOriginal[2]['e_id_etapa'], $conexion);
        
        $_idActividadNuevaEtapa1 = array(); //para guardar id de nuevas actividades
        $_idActividadNuevaEtapa2 = array();
        $_idActividadNuevaEtapa3 = array();

        for($i=0; $i<count($_actividadesEtapa1); $i++){
            if($_actividadesEtapa1[$i]['ac_medios_bitacora'] <= 0) $_actividadesEtapa1[$i]['ac_medios_bitacora'] =0;
            if($_actividadesEtapa1[$i]['ac_medios_trabajos'] <= 0) $_actividadesEtapa1[$i]['ac_medios_trabajos'] =0;
            if($_actividadesEtapa1[$i]['ac_medios_web2'] <= 0) $_actividadesEtapa1[$i]['ac_medios_web2'] =0;              
            if($_actividadesEtapa1[$i]['ac_horas_estimadas'] <= 0) $_actividadesEtapa1[$i]['ac_horas_estimadas'] =0;              
            if($_actividadesEtapa1[$i]['ac_publica_producto'] <= 0) $_actividadesEtapa1[$i]['ac_publica_producto'] =0;              
            if($_actividadesEtapa1[$i]['ac_revisa_pares'] <= 0) $_actividadesEtapa1[$i]['ac_revisa_pares'] =0;              
            if($_actividadesEtapa1[$i]['ac_tipo'] <= 0) $_actividadesEtapa1[$i]['ac_tipo'] =1;
            if($_actividadesEtapa1[$i]['ac_id_complementaria'] <= 0) $_actividadesEtapa1[$i]['ac_id_complementaria'] =0;
            $consulta = "INSERT INTO actividad(ac_nombre, ac_horas_estimadas, ac_instrucciones_inicio, ac_instrucciones_desarrollo, ac_instrucciones_cierre, ac_descripcion, ac_orden, ac_publica_producto, ac_revisa_pares, ac_instrucciones_producto, ac_instrucciones_revision, ac_id_complementaria, ac_aprendizaje_esperado, ac_evidencia_aprendizaje, ac_medios, ac_tipo, ac_medios_bitacora, ac_medios_trabajos, ac_material_requerido, ac_medios_web2, ac_consejos_practicos, ac_medios_otros, ac_eval_autoyco, ac_eval_evaleco, ac_eval_prodhetero, ac_id_etapa) ".
                        "VALUES( ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_nombre'])."', ".
                            strip_tags($_actividadesEtapa1[$i]['ac_horas_estimadas']).", ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_instrucciones_inicio'])."', ".	 	 	
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_instrucciones_desarrollo'])."', ".	 	 	 	 	 	
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_instrucciones_cierre'])."', ". 	 	 	 	 	
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_descripcion'])."', ". 	 	 	
                            strip_tags($_actividadesEtapa1[$i]['ac_orden']).", ". 	 	 	
                            strip_tags($_actividadesEtapa1[$i]['ac_publica_producto']).", ".	 	 	 	
                            strip_tags($_actividadesEtapa1[$i]['ac_revisa_pares']).", ".	 	 	
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_instrucciones_producto'])."', ". 	 	 	 	
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_instrucciones_revision'])."', ". 	 	 	 	
                            "".strip_tags($_actividadesEtapa1[$i]['ac_id_complementaria']).", ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_aprendizaje_esperado'])."', ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_evidencia_aprendizaje'])."', ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_medios'])."', ".
                            strip_tags($_actividadesEtapa1[$i]['ac_tipo']).", ".
                            strip_tags($_actividadesEtapa1[$i]['ac_medios_bitacora']).", ".
                            strip_tags($_actividadesEtapa1[$i]['ac_medios_trabajos']).", ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_material_requerido'])."', ".
                            strip_tags($_actividadesEtapa1[$i]['ac_medios_web2']).", ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_consejos_practicos'])."', ".
                            "'".strip_tags($_actividadesEtapa1[$i]['ac_medios_otros'])."', ".
                            strip_tags($_actividadesEtapa1[$i]['ac_eval_autoyco']).", ".
                            strip_tags($_actividadesEtapa1[$i]['ac_eval_evaleco']).", ".
                            strip_tags($_actividadesEtapa1[$i]['ac_eval_prodhetero']).", ".
                            $idEtapaNueva1.
                        " )";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $_idActividadNuevaEtapa1[$i][0] = mysql_insert_id($conexion); //id clon (nueva)
            $_idActividadNuevaEtapa1[$i][1] = $_actividadesEtapa1[$i]['ac_id_actividad']; //id original

            
            $idComplementaria='';
            if($_actividadesEtapa1[$i]['ac_id_complementaria'] != '' && $_actividadesEtapa1[$i]['ac_id_complementaria'] >0){
                for($j=0; $j<count($_idActividadNuevaEtapa1); $j++){
                    if($_idActividadNuevaEtapa1[$j][1] == $_actividadesEtapa1[$i]['ac_id_complementaria']){
                        $idComplementaria = ", ac_id_complementaria = ".$_idActividadNuevaEtapa1[$j][0];
                    }
                }
            }
            
            $consulta = "UPDATE actividad ".
                        "SET ".
                            "ac_id_etapa = ".$idEtapaNueva1." ".
                            $idComplementaria." ".
                        "WHERE  ".
                            "ac_id_actividad = ".$_idActividadNuevaEtapa1[$i][0];    
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            
            //actualizamos pautas de evaluacion
            /*
            $_pautas  =  obtenerPautasFuncion($_idActividadNuevaEtapa1[$i][1], $conexion);
            for($z=0; $z<count($_pautas); $z++){
                $consulta = "INSERT INTO rp_pauta_evaluacion(rpe_enunciado, rpe_orden, rpe_id_actividad) ".
                            "SELECT rpe_enunciado, rpe_orden, rpe_id_actividad FROM rp_pauta_evaluacion WHERE rpe_id=".$_pautas[$z]['rpe_id'];
                $_resultado = dbEjecutarConsulta($consulta, $conexion);
                $_idPautaNueva = mysql_insert_id($conexion); //id clon (nueva pauta)
                
                $consulta = "UPDATE rp_pauta_evaluacion ".
                            "SET ".
                                "rpe_id_actividad = ".$_idActividadNuevaEtapa1[$i][0]." ".
                            "WHERE  ".
                                "rpe_id = ".$_idPautaNueva;    
                $_resultado = dbEjecutarConsulta($consulta, $conexion);                
            }
            */
            
            //actualizamos pautas de evaluacion
            $_pautasAutoEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa1[$i][1], 1, $conexion );
            $_pautasProdHetEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa1[$i][1], 3, $conexion );
            $_pautasEcoEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa1[$i][1], 5, $conexion );
            
            if(count($_pautasAutoEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa1[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 1, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa1[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasAutoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasAutoEva[$z]['enu_id_enunciado'], $_pautasAutoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 2, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa1[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasAutoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasAutoEva[$z]['enu_id_enunciado'], $_pautasAutoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                    }
                }
            }
            
            if(count($_pautasProdHetEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa1[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 3, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa1[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasProdHetEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasProdHetEva[$z]['enu_id_enunciado'], $_pautasProdHetEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 4, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa1[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasProdHetEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasProdHetEva[$z]['enu_id_enunciado'], $_pautasProdHetEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                    }
                }
            }
            
            if(count($_pautasEcoEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa1[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 5, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa1[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasEcoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasEcoEva[$z]['enu_id_enunciado'], $_pautasEcoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                    }
                }
            }
            
            
            //actualizamos archivos
            $_archivos = obtenerArchivosFuncion($_idActividadNuevaEtapa1[$i][1], $conexion);
            for($z=0; $z<count($_archivos); $z++){
                $consulta = "INSERT INTO archivo(a_nombre_archivo, a_solo_profesor, a_descripcion, a_id_actividad) ".
                            "SELECT a_nombre_archivo, a_solo_profesor, a_descripcion, a_id_actividad FROM archivo WHERE a_id_archivo=".$_archivos[$z]['a_id_archivo'];
                            $_resultado = dbEjecutarConsulta($consulta, $conexion);
                            $_idArchivoNuevo = mysql_insert_id($conexion); //id clon
                
                $consulta = "UPDATE archivo ".
                            "SET ".
                                "a_id_actividad = ".$_idActividadNuevaEtapa1[$i][0]." ".
                            "WHERE  ".
                                "a_id_archivo = ".$_idArchivoNuevo;    
                $_resultado = dbEjecutarConsulta($consulta, $conexion);                
            }
            //copiamos los archivos contenidos en la carpeta de la actividad
            full_copy($_idActividadNuevaEtapa1[$i][1], $_idActividadNuevaEtapa1[$i][0]);
            
        }
        for($i=0; $i<count($_actividadesEtapa2); $i++){
            if($_actividadesEtapa2[$i]['ac_medios_bitacora'] <= 0) $_actividadesEtapa2[$i]['ac_medios_bitacora'] =0;
            if($_actividadesEtapa2[$i]['ac_medios_trabajos'] <= 0) $_actividadesEtapa2[$i]['ac_medios_trabajos'] =0;
            if($_actividadesEtapa2[$i]['ac_medios_web2'] <= 0) $_actividadesEtapa2[$i]['ac_medios_web2'] =0;              
            if($_actividadesEtapa2[$i]['ac_horas_estimadas'] <= 0) $_actividadesEtapa2[$i]['ac_horas_estimadas'] =0;              
            if($_actividadesEtapa2[$i]['ac_publica_producto'] <= 0) $_actividadesEtapa2[$i]['ac_publica_producto'] =0;              
            if($_actividadesEtapa2[$i]['ac_revisa_pares'] <= 0) $_actividadesEtapa2[$i]['ac_revisa_pares'] =0;              
            if($_actividadesEtapa2[$i]['ac_tipo'] <= 0) $_actividadesEtapa2[$i]['ac_tipo'] =1;
            if($_actividadesEtapa2[$i]['ac_id_complementaria'] <= 0) $_actividadesEtapa2[$i]['ac_id_complementaria'] =0;
            $consulta = "INSERT INTO actividad(ac_nombre, ac_horas_estimadas, ac_instrucciones_inicio, ac_instrucciones_desarrollo, ac_instrucciones_cierre, ac_descripcion, ac_orden, ac_publica_producto, ac_revisa_pares, ac_instrucciones_producto, ac_instrucciones_revision, ac_id_complementaria, ac_aprendizaje_esperado, ac_evidencia_aprendizaje, ac_medios, ac_tipo, ac_medios_bitacora, ac_medios_trabajos, ac_material_requerido, ac_medios_web2, ac_consejos_practicos, ac_medios_otros, ac_eval_autoyco, ac_eval_evaleco, ac_eval_prodhetero, ac_id_etapa) ".
                        "VALUES( ".
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_nombre'])."', ".	
                            strip_tags($_actividadesEtapa2[$i]['ac_horas_estimadas']).", ".	 	 	 	
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_instrucciones_inicio'])."', ".	 	 	
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_instrucciones_desarrollo'])."', ".	 	 	 	 	 	
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_instrucciones_cierre'])."', ". 	 	 	 	 	
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_descripcion'])."', ". 
                            strip_tags($_actividadesEtapa2[$i]['ac_orden']).", ".                     
                            strip_tags($_actividadesEtapa2[$i]['ac_publica_producto']).", ".	 	 	 	
                            strip_tags($_actividadesEtapa2[$i]['ac_revisa_pares']).", ".	 	 	
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_instrucciones_producto'])."', ". 	 	 	 	
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_instrucciones_revision'])."', ". 	 	 	 	
                            "".strip_tags($_actividadesEtapa2[$i]['ac_id_complementaria']).", ".
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_aprendizaje_esperado'])."', ".
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_evidencia_aprendizaje'])."', ".
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_medios'])."', ".
                            strip_tags($_actividadesEtapa2[$i]['ac_tipo']).", ".
                            strip_tags($_actividadesEtapa2[$i]['ac_medios_bitacora']).", ".
                            strip_tags($_actividadesEtapa2[$i]['ac_medios_trabajos']).", ".
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_material_requerido'])."', ".
                            strip_tags($_actividadesEtapa2[$i]['ac_medios_web2']).", ".
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_consejos_practicos'])."', ".
                            "'".strip_tags($_actividadesEtapa2[$i]['ac_medios_otros'])."', ".
                            strip_tags($_actividadesEtapa2[$i]['ac_eval_autoyco']).", ".
                            strip_tags($_actividadesEtapa2[$i]['ac_eval_evaleco']).", ".
                            strip_tags($_actividadesEtapa2[$i]['ac_eval_prodhetero']).", ".
                            $idEtapaNueva2.
                        " )";
            
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $_idActividadNuevaEtapa2[$i][0] = mysql_insert_id($conexion);
            $_idActividadNuevaEtapa2[$i][1] = $_actividadesEtapa2[$i]['ac_id_actividad'];

            $idComplementaria='';
            if($_actividadesEtapa2[$i]['ac_id_complementaria'] != '' && $_actividadesEtapa2[$i]['ac_id_complementaria'] >0){
                for($j=0; $j<count($_idActividadNuevaEtapa1); $j++){
                    if($_idActividadNuevaEtapa1[$j][1] == $_actividadesEtapa2[$i]['ac_id_complementaria']){
                        $idComplementaria = ", ac_id_complementaria = ".$_idActividadNuevaEtapa1[$j][0];
                    }
                }
                for($j=0; $j<count($_idActividadNuevaEtapa2); $j++){
                    if($_idActividadNuevaEtapa2[$j][1] == $_actividadesEtapa2[$i]['ac_id_complementaria']){
                        $idComplementaria = ", ac_id_complementaria = ".$_idActividadNuevaEtapa2[$j][0];
                    }
                }                
            }
            
            $consulta = "UPDATE actividad ".
                        "SET ".
                            "ac_id_etapa = ".$idEtapaNueva2." ".
                            $idComplementaria." ".
                        "WHERE  ".
                            "ac_id_actividad = ".$_idActividadNuevaEtapa2[$i][0];    
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            
            //actualizamos pautas de evaluacion
            /*
            $_pautas  =  obtenerPautasFuncion($_idActividadNuevaEtapa2[$i][1], $conexion);
            for($z=0; $z<count($_pautas); $z++){
                $consulta = "INSERT INTO rp_pauta_evaluacion(rpe_enunciado, rpe_orden, rpe_id_actividad) ".
                            "SELECT rpe_enunciado, rpe_orden, rpe_id_actividad FROM rp_pauta_evaluacion WHERE rpe_id=".$_pautas[$z]['rpe_id'];
                $_resultado = dbEjecutarConsulta($consulta, $conexion);
                $_idPautaNueva = mysql_insert_id($conexion); //id clon (nueva pauta)
                
                $consulta = "UPDATE rp_pauta_evaluacion ".
                            "SET ".
                                "rpe_id_actividad = ".$_idActividadNuevaEtapa2[$i][0]." ".
                            "WHERE  ".
                                "rpe_id = ".$_idPautaNueva;    
                $_resultado = dbEjecutarConsulta($consulta, $conexion);                
            } */
            
            $_pautasAutoEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa2[$i][1], 1, $conexion );
            $_pautasProdHetEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa2[$i][1], 3, $conexion );
            $_pautasEcoEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa2[$i][1], 5, $conexion );
            
            if(count($_pautasAutoEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa2[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 1, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa2[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasAutoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasAutoEva[$z]['enu_id_enunciado'], $_pautasAutoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 2, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa2[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasAutoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasAutoEva[$z]['enu_id_enunciado'], $_pautasAutoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                    }
                }
            }
            
            if(count($_pautasProdHetEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa2[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 3, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa2[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasProdHetEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasProdHetEva[$z]['enu_id_enunciado'], $_pautasProdHetEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 4, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa2[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasProdHetEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasProdHetEva[$z]['enu_id_enunciado'], $_pautasProdHetEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }                        
                    }
                }
            }
            
            if(count($_pautasEcoEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa2[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 5, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa2[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasEcoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasEcoEva[$z]['enu_id_enunciado'], $_pautasEcoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                    }
                }
            }
            
            
            //actualizamos archivos
            $_archivos = obtenerArchivosFuncion($_idActividadNuevaEtapa2[$i][1], $conexion);
            for($z=0; $z<count($_archivos); $z++){
                $consulta = "INSERT INTO archivo(a_nombre_archivo, a_solo_profesor, a_descripcion, a_id_actividad) ".
                            "SELECT a_nombre_archivo, a_solo_profesor, a_descripcion, a_id_actividad FROM archivo WHERE a_id_archivo=".$_archivos[$z]['a_id_archivo'];
                            $_resultado = dbEjecutarConsulta($consulta, $conexion);
                            $_idArchivoNuevo = mysql_insert_id($conexion); //id clon
                
                $consulta = "UPDATE archivo ".
                            "SET ".
                                "a_id_actividad = ".$_idActividadNuevaEtapa2[$i][0]." ".
                            "WHERE  ".
                                "a_id_archivo = ".$_idArchivoNuevo;    
                $_resultado = dbEjecutarConsulta($consulta, $conexion);                
            }
            //copiamos los archivos contenidos en la carpeta de la actividad
            full_copy($_idActividadNuevaEtapa2[$i][1], $_idActividadNuevaEtapa2[$i][0]);            
        }

        for($i=0; $i<count($_actividadesEtapa3); $i++){
            if($_actividadesEtapa3[$i]['ac_medios_bitacora'] <= 0) $_actividadesEtapa3[$i]['ac_medios_bitacora'] =0;
            if($_actividadesEtapa3[$i]['ac_medios_trabajos'] <= 0) $_actividadesEtapa3[$i]['ac_medios_trabajos'] =0;
            if($_actividadesEtapa3[$i]['ac_medios_web2'] <= 0) $_actividadesEtapa3[$i]['ac_medios_web2'] =0;              
            if($_actividadesEtapa3[$i]['ac_horas_estimadas'] <= 0) $_actividadesEtapa3[$i]['ac_horas_estimadas'] =0;              
            if($_actividadesEtapa3[$i]['ac_publica_producto'] <= 0) $_actividadesEtapa3[$i]['ac_publica_producto'] =0;              
            if($_actividadesEtapa3[$i]['ac_revisa_pares'] <= 0) $_actividadesEtapa3[$i]['ac_revisa_pares'] =0;              
            if($_actividadesEtapa3[$i]['ac_tipo'] <= 0) $_actividadesEtapa3[$i]['ac_tipo'] =1;
            if($_actividadesEtapa3[$i]['ac_id_complementaria'] <= 0) $_actividadesEtapa3[$i]['ac_id_complementaria'] =0;
            
            $consulta = "INSERT INTO actividad(ac_nombre, ac_horas_estimadas, ac_instrucciones_inicio, ac_instrucciones_desarrollo, ac_instrucciones_cierre, ac_descripcion, ac_orden, ac_publica_producto, ac_revisa_pares, ac_instrucciones_producto, ac_instrucciones_revision, ac_id_complementaria, ac_aprendizaje_esperado, ac_evidencia_aprendizaje, ac_medios, ac_tipo, ac_medios_bitacora, ac_medios_trabajos, ac_material_requerido, ac_medios_web2, ac_consejos_practicos, ac_medios_otros, ac_eval_autoyco, ac_eval_evaleco, ac_eval_prodhetero, ac_id_etapa) ".
                        "VALUES( ".
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_nombre'])."', ".	
                            strip_tags($_actividadesEtapa3[$i]['ac_horas_estimadas']).", ".	 	 	 	
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_instrucciones_inicio'])."', ".	 	 	
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_instrucciones_desarrollo'])."', ".	 	 	 	 	 	
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_instrucciones_cierre'])."', ". 	 	 	 	 	
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_descripcion'])."', ". 
                            strip_tags($_actividadesEtapa3[$i]['ac_orden']).", ".                     
                            strip_tags($_actividadesEtapa3[$i]['ac_publica_producto']).", ".	 	 	 	
                            strip_tags($_actividadesEtapa3[$i]['ac_revisa_pares']).", ".	 	 	
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_instrucciones_producto'])."', ". 	 	 	 	
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_instrucciones_revision'])."', ". 	 	 	 	
                            "".strip_tags($_actividadesEtapa3[$i]['ac_id_complementaria']).", ".
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_aprendizaje_esperado'])."', ".
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_evidencia_aprendizaje'])."', ".
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_medios'])."', ".
                            strip_tags($_actividadesEtapa3[$i]['ac_tipo']).", ".
                            strip_tags($_actividadesEtapa3[$i]['ac_medios_bitacora']).", ".
                            strip_tags($_actividadesEtapa3[$i]['ac_medios_trabajos']).", ".
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_material_requerido'])."', ".
                            strip_tags($_actividadesEtapa3[$i]['ac_medios_web2']).", ".
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_consejos_practicos'])."', ".
                            "'".strip_tags($_actividadesEtapa3[$i]['ac_medios_otros'])."', ".
                            strip_tags($_actividadesEtapa3[$i]['ac_eval_autoyco']).", ".
                            strip_tags($_actividadesEtapa3[$i]['ac_eval_evaleco']).", ".
                            strip_tags($_actividadesEtapa3[$i]['ac_eval_prodhetero']).", ".
                            $idEtapaNueva3.
                        " )";
            
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $_idActividadNuevaEtapa3[$i][0] = mysql_insert_id($conexion);
            $_idActividadNuevaEtapa3[$i][1] = $_actividadesEtapa3[$i]['ac_id_actividad'];

            $idComplementaria='';
            if($_actividadesEtapa3[$i]['ac_id_complementaria'] != '' && $_actividadesEtapa3[$i]['ac_id_complementaria'] >0){
                for($j=0; $j<count($_idActividadNuevaEtapa1); $j++){
                    if($_idActividadNuevaEtapa1[$j][1] == $_actividadesEtapa3[$i]['ac_id_complementaria']){
                        $idComplementaria = ", ac_id_complementaria = ".$_idActividadNuevaEtapa1[$j][0];
                    }
                }
                for($j=0; $j<count($_idActividadNuevaEtapa2); $j++){
                    if($_idActividadNuevaEtapa2[$j][1] == $_actividadesEtapa3[$i]['ac_id_complementaria']){
                        $idComplementaria = ", ac_id_complementaria = ".$_idActividadNuevaEtapa2[$j][0];
                    }
                }
                for($j=0; $j<count($_idActividadNuevaEtapa3); $j++){
                    if($_idActividadNuevaEtapa3[$j][1] == $_actividadesEtapa3[$i]['ac_id_complementaria']){
                        $idComplementaria = ", ac_id_complementaria = ".$_idActividadNuevaEtapa3[$j][0];
                    }
                }               
            }  
            
            $consulta = "UPDATE actividad ".
                        "SET ".
                            "ac_id_etapa = ".$idEtapaNueva3." ".
                            $idComplementaria." ".
                        "WHERE  ".
                            "ac_id_actividad = ".$_idActividadNuevaEtapa3[$i][0];  
            //echo $consulta;
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            
            //actualizamos pautas de evaluacion
            /*
            $_pautas  =  obtenerPautasFuncion($_idActividadNuevaEtapa3[$i][1], $conexion);
            for($z=0; $z<count($_pautas); $z++){
                $consulta = "INSERT INTO rp_pauta_evaluacion(rpe_enunciado, rpe_orden, rpe_id_actividad) ".
                            "SELECT rpe_enunciado, rpe_orden, rpe_id_actividad FROM rp_pauta_evaluacion WHERE rpe_id=".$_pautas[$z]['rpe_id'];
                $_resultado = dbEjecutarConsulta($consulta, $conexion);
                $_idPautaNueva = mysql_insert_id($conexion); //id clon (nueva pauta)
                
                $consulta = "UPDATE rp_pauta_evaluacion ".
                            "SET ".
                                "rpe_id_actividad = ".$_idActividadNuevaEtapa3[$i][0]." ".
                            "WHERE  ".
                                "rpe_id = ".$_idPautaNueva;    
                $_resultado = dbEjecutarConsulta($consulta, $conexion);                
            } */

            $_pautasAutoEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa3[$i][1], 1, $conexion );
            $_pautasProdHetEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa3[$i][1], 3, $conexion );
            $_pautasEcoEva = obtenerPautasPorTipoFuncion($_idActividadNuevaEtapa3[$i][1], 5, $conexion );
            
            if(count($_pautasAutoEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa3[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 1, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa3[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasAutoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasAutoEva[$z]['enu_id_enunciado'], $_pautasAutoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 2, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa3[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasAutoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasAutoEva[$z]['enu_id_enunciado'], $_pautasAutoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }                        
                    }
                }
            }
            
            if(count($_pautasProdHetEva) > 0){
                //error_log(print_r($_pautasProdHetEva,true));
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa3[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 3, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa3[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasProdHetEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasProdHetEva[$z]['enu_id_enunciado'], $_pautasProdHetEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 4, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa3[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasProdHetEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasProdHetEva[$z]['enu_id_enunciado'], $_pautasProdHetEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }                        
                    }
                }
            }
            
            if(count($_pautasEcoEva) > 0){
                $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($_idActividadNuevaEtapa3[$i][1], $conexion);                
                if(count($_escalaDiseno) > 0){
                    $idNuevaRubrica = -1;
                    $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);                    
                    if($idNuevaRubrica > 0){
                        $idNuevaEvaluacion = -1;
                        $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, 5, $conexion);                        
                        if($idNuevaEvaluacion > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $_idActividadNuevaEtapa3[$i][0], $conexion);
                            if($resul){            
                                for($z=0; $z<count($_pautasEcoEva); $z++){
                                    $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $_pautasEcoEva[$z]['enu_id_enunciado'], $_pautasEcoEva[$z]['rbenu_orden'], $conexion);
                                }
                            }
                        }
                    }
                }
            }
            
            //actualizamos archivos
            $_archivos = obtenerArchivosFuncion($_idActividadNuevaEtapa3[$i][1], $conexion);

            for($z=0; $z<count($_archivos); $z++){
                $consulta = "INSERT INTO archivo(a_nombre_archivo, a_solo_profesor, a_descripcion, a_id_actividad) ".
                            "SELECT a_nombre_archivo, a_solo_profesor, a_descripcion, a_id_actividad FROM archivo WHERE a_id_archivo=".$_archivos[$z]['a_id_archivo'];
                            $_resultado = dbEjecutarConsulta($consulta, $conexion);
                            $_idArchivoNuevo = mysql_insert_id($conexion); //id clon
                
                $consulta = "UPDATE archivo ".
                            "SET ".
                                "a_id_actividad = ".$_idActividadNuevaEtapa3[$i][0]." ".
                            "WHERE  ".
                                "a_id_archivo = ".$_idArchivoNuevo;   
                $_resultado = dbEjecutarConsulta($consulta, $conexion);                
            }
            //copiamos los archivos contenidos en la carpeta de la actividad
            full_copy($_idActividadNuevaEtapa3[$i][1], $_idActividadNuevaEtapa3[$i][0]);            
        }
        $consulta =   "SELECT dd_nombre FROM diseno_didactico WHERE dd_id_diseno_didactico=".$idDisenoOriginal;
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        agregarRegistroCambio($usuario, $idDisenoNuevo, 0, 0, 0, 'Se creo éste diseño a partir del "'.$_datos[0]['dd_nombre'].'"', '', $conexion);
        
        return $_resultadoReturn;
}

/*Funcion que agrega un nuevo diseño didactico y retorna el id con el cual fue creado*/
function crearDisenoFuncion($usuario, $nombre, $sector, $nivel, $descripcion, $objCurriculares, $objTransversales,$contenidos, $descEtapa1, $descEtapa2, $descEtapa3, $web20, $escala, $conexion){
        global $_niveles, $_niveles_universitario;
        
        $index_ = 0;
        foreach ($_niveles as $key => $value) {
            if($value == $nivel){$index_ = $key;}
        }
        
        $tipoDiseno = 1;
        for($i=0; $i<count($_niveles_universitario); $i++){        
            if($_niveles_universitario[$i] == $index_){$tipoDiseno = 2;}
        }
        
        //Creamos el nuevo Diseno
        $consulta =     "INSERT into diseno_didactico(dd_nombre, dd_nivel, dd_subsector, dd_id_autor, dd_descripcion, dd_manejo_tecnologico, dd_publicado, dd_objetivos_curriculares,dd_objetivos_transversales, dd_contenidos, dd_fecha_creacion,  dd_descripcion_e1, dd_descripcion_e2, dd_descripcion_e3, dd_tipo, dd_escala) ".
                        "VALUES('".$nombre."','".$nivel."','".$sector."',".$usuario.",'".$descripcion."','', 0,'".$objCurriculares."','".$objTransversales."','".$contenidos."', NOW(), '".$descEtapa1."','".$descEtapa2."','".$descEtapa3."',".$tipoDiseno.", ".$escala.")";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $result = $_resultado;
        $idDiseno = mysql_insert_id($conexion);

        if($_resultado == 1){
            agregarRegistroCambio($usuario, $idDiseno, 0, 0, 1, 'Se creó el diseño "'.$nombre.'"', $consulta, $conexion);
        }        

        //Creamos la relacion con la herramienta web
        $consulta =     "INSERT into herramientas_diseno(hd_id_herramienta,hd_id_diseno_didactico) ".
                        "VALUES(".$web20.",".$idDiseno.")";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Creamos la Etapa 1 para el diseno recien creado
        $consulta =     "INSERT into etapa(e_id_diseno_didactico, e_nombre, e_sesiones_estimadas, e_descripcion,e_orden, e_tipo) ".
                        "VALUES(".$idDiseno.", 'Motivación', 2, '".$descEtapa1."',1, 1)";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapa1 = mysql_insert_id($conexion);

        //#Creamos la Etapa 2 para el diseno recien creado
        $consulta =     "INSERT into etapa(e_id_diseno_didactico, e_nombre, e_sesiones_estimadas, e_descripcion,e_orden, e_tipo) ".
                        "VALUES(".$idDiseno.", 'Desarrollo', 3, '".$descEtapa2."',2, 2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapa2 = mysql_insert_id($conexion);

        //#Creamos la Etapa 3 para el diseno recien creado
        $consulta =     "INSERT into etapa(e_id_diseno_didactico, e_nombre, e_sesiones_estimadas, e_descripcion,e_orden, e_tipo) ".
                        "VALUES(".$idDiseno.", 'Evaluación', 2, '".$descEtapa3."',3, 3)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapa3 = mysql_insert_id($conexion);

        //#Por ultimo creamos las actividades por defecto para cada etapa;
        //#Actividades etapa 1
        $consulta =     "INSERT into actividad(ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_iniciadora,ac_fecha_creacion, ac_horas_estimadas, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa1.", 1, 1, NOW(), 0, 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $consulta =     "INSERT into actividad(ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_fecha_creacion, ac_horas_estimadas, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa1.", 2, NOW(), 0, 2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        //#Actividades etapa 2
        $consulta =     "INSERT into actividad(ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_fecha_creacion, ac_horas_estimadas, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa2.", 1, NOW(), 0, 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $consulta =     "INSERT into actividad(ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_fecha_creacion, ac_horas_estimadas, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa2.", 2, NOW(), 0, 2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $consulta =     "INSERT into actividad(ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_fecha_creacion, ac_horas_estimadas, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa2.", 3, NOW(), 0, 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Actividades etapa 3
        $consulta =     "INSERT into actividad(ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_fecha_creacion, ac_horas_estimadas, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa3.", 1, NOW(), 0, 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $consulta =     "INSERT into actividad(ac_nombre,ac_descripcion, ac_id_etapa, ac_orden,ac_fecha_creacion, ac_horas_estimadas, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa3.", 2, NOW(), 0, 2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        if($result>0){
            return $idDiseno;
        }else return -1;
}

/*Funcion que actualiza un Diseño Didáctico*/
function actualizarDisenoFuncion($usuario, $nombre, $idDiseno, $sector, $nivel, $descripcion, $objCurriculares, $objTransversales,$contenidos, $descEtapa1, $descEtapa2, $descEtapa3, $web20, $conexion){
    
        //#Actualizamos el Diseno
        $consulta_1 = "UPDATE diseno_didactico ".
                    "SET ".
                        "dd_nombre ='$nombre', ". 
                        "dd_nivel ='$nivel', ". 
                        "dd_subsector ='$sector', ". 
                        "dd_id_autor =$usuario, ". 
                        "dd_descripcion ='$descripcion', ". 
                        "dd_manejo_tecnologico ='', ". 
                        "dd_objetivos_curriculares ='$objCurriculares', ". 
                        "dd_objetivos_transversales ='$objTransversales', ". 
                        "dd_contenidos ='$contenidos', ".
                        "dd_fecha_modificacion = NOW(), ". 
                        "dd_descripcion_e1 ='$descEtapa1', ". 
                        "dd_descripcion_e2 ='$descEtapa2', ". 
                        "dd_descripcion_e3 ='$descEtapa3' ".
                    "WHERE ". 
                    "dd_id_diseno_didactico = $idDiseno;";
        $_resultado_1 = dbEjecutarConsulta($consulta_1, $conexion);

        //#Actualizamos la relacion con la herramienta web
        $consulta = "UPDATE herramientas_diseno ".
                    "SET hd_id_herramienta = $web20 ".
                    "WHERE hd_id_diseno_didactico = $idDiseno";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Actualizamos Etapa 1 
        $consulta = "UPDATE etapa ".
                    "SET ".
                    "e_descripcion='$descEtapa1' ".
                    "WHERE ". 
                    "e_id_diseno_didactico=$idDiseno ". 
                    "AND e_orden= 1";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Actualizamos Etapa 2
        $consulta = "UPDATE etapa ".
                    "SET ".
                    "e_descripcion='$descEtapa2' ".
                    "WHERE ". 
                    "e_id_diseno_didactico=$idDiseno ". 
                    "AND e_orden= 2";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Actualizamos Etapa 3
        $consulta = "UPDATE etapa ".
                    "SET ".
                    "e_descripcion='$descEtapa3' ".
                    "WHERE ". 
                    "e_id_diseno_didactico=$idDiseno ". 
                    "AND e_orden= 3";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
    if($_resultado_1 == 1){
        agregarRegistroCambio($usuario, $idDiseno, 0, 0, 1, 'Se modificó el diseño "'.$nombre.'"', $consulta_1, $conexion);
    }
    return $_resultado_1;

}
function actualizarDisenoAdminFuncion($id_autor, $nombre, $idDiseno, $descripcion, $objCurriculares, $objTransversales, $contenidos, $descEtapa1, $descEtapa2, $descEtapa3, $conexion){
        //#Actualizamos el Diseno
        $consulta = "UPDATE diseno_didactico ".
                    "SET ".
                        "dd_nombre ='$nombre', ". 
                        "dd_id_autor =$id_autor, ". 
                        "dd_descripcion ='$descripcion', ".
                        "dd_objetivos_curriculares ='$objCurriculares', ". 
                        "dd_objetivos_transversales ='$objTransversales', ". 
                        "dd_contenidos ='$contenidos', ".
                        "dd_descripcion_e1 ='$descEtapa1', ". 
                        "dd_descripcion_e2 ='$descEtapa2', ". 
                        "dd_descripcion_e3 ='$descEtapa3' ".                
                    "WHERE ". 
                    "dd_id_diseno_didactico = $idDiseno;";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
}
function obtenerHerramientasWebFuncion($conexion){

        $consulta = "SELECT * ".
                    "FROM herramientas_web ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        
    return $_datos;
        
}

function obtenerTiposEscala($conexion){
 
        $consulta = "SELECT * ".
                    "FROM por_tipo_escala ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        
    return $_datos;   
}

function obtenerArchivosFuncion($id_actividad, $conexion){

    $consulta = "SELECT * ".
                "FROM archivo ".
                "WHERE ".
                    "a_id_actividad = ".$id_actividad." ".
                "ORDER BY a_orden ASC ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    $null_orden = false;
    if ($_resultado) {
        if (mysql_num_rows($_resultado) > 0) {
            while ($fila = mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                if ($fila['a_orden'] == '' || $fila['a_orden'] == null || $fila['a_orden'] == 0) {
                    $null_orden = true;
                }
                $_datos[] = $fila;
            }
            if ($null_orden) {
                for ($i = 0; $i < count($_datos); $i++) {
                    $_datos[$i]['a_orden'] = ($i + 1);
                    $_res = dbEjecutarConsulta("UPDATE archivo SET a_orden=" . ($i + 1) . " WHERE a_id_archivo=" . $_datos[$i]['a_id_archivo'], $conexion);
                }
            }
        }
    }

    return $_datos;    
}
function obtenerArchivosEjemploFuncion($conexion){

    $consulta = "SELECT * ".
                "FROM archivo_ejemplo ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if ($_resultado) {
        if (mysql_num_rows($_resultado) > 0) {
            while ($fila = mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[] = $fila;
            }
        }
    }
    return $_datos;    
}

function subirArchivoFuncion($nombre_archivo, $descripcion, $solo_profesor, $id_actividad, $conexion){

    $orden = 1;
    $consulta = "SELECT MAX(a_orden) as max_a_orden " .
            "FROM archivo " .
            "WHERE a_id_actividad = " . $id_actividad . " ";
    
    $_res = dbEjecutarConsulta($consulta, $conexion);
    if ($_res) {
        if (mysql_num_rows($_res) > 0) {
            $_datos = array();
            while ($fila = mysql_fetch_array($_res, MYSQL_ASSOC)) {
                $_datos[] = $fila;
            }
            if (count($_datos) > 0)
                $orden = ($_datos[0]['max_a_orden']) + 1;
        }
    }
    $consulta = "INSERT into archivo(" .
            "a_nombre_archivo, a_solo_profesor, a_descripcion, a_id_actividad, a_orden) " .
            "VALUES('" . $nombre_archivo . "'," . $solo_profesor . ", '" . $descripcion . "', " . $id_actividad . ", " . $orden . " )";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
  
    return $_resultado;     
}
function subirArchivoEjemploFuncion($nombre_archivo, $descripcion, $conexion){

    $consulta = "INSERT into archivo_ejemplo(" .
            "ae_nombre, ae_descripcion) " .
            "VALUES('" . $nombre_archivo . "','" . $descripcion . "' )";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
    return $_resultado;     
}

function eliminarArchivoFuncion($id_archivo, $id_actividad, $orden, $conexion){
        //corregimos los ordenes de los demas archivos de la actividad
        $consulta = "UPDATE archivo SET " .
                        "a_orden = a_orden -1 " .
                    "WHERE " .
                        "a_id_actividad = ".$id_actividad." ".
                        "AND a_orden > ".$orden." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);  
        $consulta = "DELETE FROM archivo " .
                    "WHERE " .
                        "a_id_archivo = ".$id_archivo." ";  

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

    return $_resultado;     
}
function eliminarArchivoEjemploFuncion($id_archivo, $conexion){
        $consulta = "DELETE FROM archivo_ejemplo " .
                    "WHERE " .
                        "ae_id = ".$id_archivo." ";  

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

    return $_resultado;     
}
function bajarMArchivoFuncion($id_archivo, $orden, $id_actividad, $conexion){
    
        $consulta = "UPDATE archivo SET " .
                        "a_orden = a_orden -1 " .
                    "WHERE " .
                        "a_id_actividad = ".$id_actividad." ".
                        "AND a_orden = ".($orden+1)." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $consulta = "UPDATE archivo SET " .
                        "a_orden = a_orden +1 " .
                    "WHERE " .
                        "a_id_archivo = ".$id_archivo." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);         

}
function subirMArchivoFuncion($id_archivo, $orden, $id_actividad, $conexion){  
        
        $consulta = "UPDATE archivo SET " .
                        "a_orden = a_orden +1 " .
                    "WHERE " .
                        "a_id_actividad = ".$id_actividad." ".
                        "AND a_orden = ".($orden-1)." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 
        
        $consulta = "UPDATE archivo SET " .
                        "a_orden = a_orden -1 " .
                    "WHERE " .
                        "a_id_archivo = ".$id_archivo." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);          
   
}
function obtenerPautasFuncion($id_actividad, $conexion){

        $consulta = "SELECT * ".
                    "FROM rp_pauta_evaluacion ".
                    "WHERE ".
                        "rpe_id_actividad = ".$id_actividad." ".
                     "ORDER BY rpe_orden ASC ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }

    return $_datos;    
}
function obtenerPautaFuncion($id_pauta, $conexion){

        $consulta = "SELECT * ".
                    "FROM rp_pauta_evaluacion ".
                    "WHERE ".
                        "rpe_id = ".$id_pauta." ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }

    return $_datos;    
}
function agregarPautaFuncion($enunciado, $id_actividad, $conexion){

        $orden = 1;
        $consulta = "SELECT MAX(rpe_orden) as max_rpe_orden ".
                    "FROM rp_pauta_evaluacion ".
                    "WHERE rpe_id_actividad = ".$id_actividad." ";

        $_res = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if($_res){
            while ($fila=mysql_fetch_array($_res, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        if(count($_datos)>0)
            $orden = ($_datos[0]['max_rpe_orden'])+1;
        
        $consulta = "INSERT into rp_pauta_evaluacion(".
                        "rpe_enunciado, rpe_orden, rpe_id_actividad) ".
                    "VALUES('".$enunciado."',".$orden.", ".$id_actividad." )";        

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
    return $_resultado;     
}

function eliminarEnunciadoFuncion($id_pauta, $id_actividad, $id_rubrica, $orden, $conexion){
    //corregimos el orden de las demas enunciados de la rubrica
    $consulta = "UPDATE por_rubrica_enunciado SET " .
                    "rbenu_orden = rbenu_orden -1 " .
                "WHERE " .
                    "rbenu_id_rubrica = ".$id_rubrica." ".
                    "AND rbenu_orden > ".($orden)." ";    
    
    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    
    //Eliminamos la relacion rubrica-enunciado
    $consulta = "DELETE FROM por_rubrica_enunciado " .
                "WHERE " .
                    "rbenu_id_rubrica = ".$id_rubrica." ".
                    "AND rbenu_id_enunciado = ".$id_pauta." ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);
}

function eliminarPautaFuncion($id_pauta, $id_actividad, $orden, $conexion){

    //corregimos el orden de las demas pautas de la actividad
    $consulta = "UPDATE rp_pauta_evaluacion SET " .
                    "rpe_orden = rpe_orden -1 " .
                "WHERE " .
                    "rpe_id_actividad = ".$id_actividad." ".
                    "AND rpe_orden > ".$orden." ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    //Eliminamos la pauta
    $consulta = "DELETE FROM rp_pauta_evaluacion " .
                "WHERE " .
                        "rpe_id = ".$id_pauta." ";       

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
    return $_resultado;     
}
function bajarPautaFuncion($id_pauta, $pauta_orden, $id_actividad, $id_rubrica, $conexion){

    $consulta = "UPDATE por_rubrica_enunciado SET " .
                    "rbenu_orden = rbenu_orden -1 " .
                "WHERE " .
                    "rbenu_id_rubrica = ".$id_rubrica." ".
                    "AND rbenu_orden = ".($pauta_orden+1)." ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion); 

    $consulta = "UPDATE por_rubrica_enunciado SET " .
                    "rbenu_orden = rbenu_orden +1 " .
                "WHERE " .
                    "rbenu_id_rubrica = ".$id_rubrica." ".
                    "AND rbenu_id_enunciado = ".$id_pauta." ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);
}
function subirPautaFuncion($id_pauta, $pauta_orden, $id_actividad, $id_rubrica, $conexion){  
    $consulta = "UPDATE por_rubrica_enunciado SET " .
                    "rbenu_orden = rbenu_orden +1 " .
                "WHERE " .
                    "rbenu_id_rubrica = ".$id_rubrica." ".
                    "AND rbenu_orden = ".($pauta_orden-1)." ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion); 

    $consulta = "UPDATE por_rubrica_enunciado SET " .
                    "rbenu_orden = rbenu_orden -1 " .
                "WHERE " .
                    "rbenu_id_rubrica = ".$id_rubrica." ".
                    "AND rbenu_id_enunciado = ".$id_pauta." ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);         
}
function obtenerEnunciadosFuncion($conexion){
        $consulta = "SELECT * ".
                    "FROM por_enunciado ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }

    return $_datos;     
}
function agregarEnunciadoFuncion($enunciado, $conexion){
    $idNuevo = -1;
    $consulta = "INSERT into por_enunciado(".
                    "enu_contenido) ".
                "VALUES('".$enunciado."' )";   

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $idNuevo = mysql_insert_id($conexion);
        
    return $idNuevo;     
}

function obtenerIdPrevioEnun($fca_id_diseno, $fca_id_actividad, $tipo, $conexion){
    
    $consulta = "SELECT ac_id_actividad ".
                "FROM actividad a, etapa e ".
                "WHERE  ".
                    "a.ac_id_etapa = e.e_id_etapa  ".
                    "AND e.e_id_diseno_didactico = ".$fca_id_diseno." ".
                    "AND ac_id_actividad IN (SELECT evac_id_actividad FROM por_evaluacion_actividad evac LEFT JOIN por_evaluacion ev ON (evac.evac_id_evaluacion = ev.ev_id_evaluacion) WHERE ev.ev_id_tipoevaluacion = ".$tipo.") ".
                    "AND ac_id_actividad != ".$fca_id_actividad." ".
                "ORDER BY ac_id_actividad ASC ".    
                "LIMIT 0,1 ";
    
    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    
    if(isset($_datos[0]['ac_id_actividad']) && $_datos[0]['ac_id_actividad'] > 0 ){
        return $_datos[0]['ac_id_actividad'];
    }else return -1;    
}

function obtenerPautasPorTipoFuncion($id_actividad, $tipo, $conexion){

    $consulta = 
            "SELECT * FROM ". 
            "( ".
                "SELECT * FROM por_evaluacion ev LEFT JOIN por_evaluacion_actividad evac ON (ev.ev_id_evaluacion = evac.evac_id_evaluacion) ".
                "WHERE evac.evac_id_actividad = ".$id_actividad." ".
                "AND ev.ev_id_tipoevaluacion = ".$tipo." ".
            ") evevac ".
            "LEFT JOIN ".
            "( ".
                "SELECT * ".
                "FROM por_rubrica_enunciado rbenu ".
                "LEFT JOIN por_enunciado enu ON ( rbenu.rbenu_id_enunciado = enu.enu_id_enunciado ) ".    
            ") rbenuenu ".
            "ON (evevac.ev_id_rubrica = rbenuenu.rbenu_id_rubrica ) ".
            "WHERE rbenuenu.rbenu_id_rubrica IS NOT NULL ".
            "ORDER BY rbenuenu.rbenu_orden ASC ";


    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;    
}

function obtenerMaxPautasPorTipoFuncion($id_actividad, $tipo, $conexion){

    $consulta = 
            "SELECT * FROM ". 
            "( ".
                "SELECT * FROM por_evaluacion ev LEFT JOIN por_evaluacion_actividad evac ON (ev.ev_id_evaluacion = evac.evac_id_evaluacion) ".
                "WHERE evac.evac_id_actividad = ".$id_actividad." ".
                "AND ev.ev_id_tipoevaluacion = ".$tipo." ".
            ") evevac ".
            "LEFT JOIN ".
            "( ".
                "SELECT * ".
                "FROM por_rubrica_enunciado rbenu ".
                "LEFT JOIN por_enunciado enu ON ( rbenu.rbenu_id_enunciado = enu.enu_id_enunciado ) ".    
            ") rbenuenu ".
            "ON (evevac.ev_id_rubrica = rbenuenu.rbenu_id_rubrica ) ".
            "ORDER BY rbenuenu.rbenu_orden DESC ".
            "LIMIT 0,1 ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;    
}
function obtenerDisenoEscalaByActividadFuncion($id_actividad, $conexion){
    $consulta = "SELECT dd_escala, dd_id_diseno_didactico ".
                "FROM actividad, etapa LEFT JOIN diseno_didactico ON (e_id_diseno_didactico = dd_id_diseno_didactico) ".
                "WHERE ac_id_actividad = ".$id_actividad." AND ac_id_etapa = e_id_etapa ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[] = $fila;
        }
    }

    return $_datos;     
}

function agregarRubricaFuncion($escala, $conexion){
    $consulta = "INSERT into por_rubrica(".
                    "rub_id_escala_evaluacion, rub_fecha_creacion) ".
                "VALUES(".$escala.", NOW() )";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    
    $idNuevo = mysql_insert_id($conexion);        
    return $idNuevo;
}
function agregarEvaluacionFuncion($idRubrica, $id_tipo, $conexion){
    $consulta = "INSERT into por_evaluacion(".
                    "ev_id_tipoevaluacion, ev_id_rubrica) ".
                "VALUES(".$id_tipo.", ".$idRubrica." )";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $idNuevo = mysql_insert_id($conexion);
        
    return $idNuevo;
}
function agregarEvaluacionActividadFuncion($idEvaluacion, $id_actividad, $conexion){
    $consulta = "INSERT into por_evaluacion_actividad(".
                    "evac_id_actividad, evac_id_evaluacion) ".
                "VALUES(".$id_actividad.", ".$idEvaluacion." )";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
    return $_resultado;    
}
function agregarRubricaEnunciadoFuncion($idRubrica, $idEnunciado, $orden, $conexion){
    $consulta = "INSERT into por_rubrica_enunciado(".
                    "rbenu_id_rubrica, rbenu_id_enunciado, rbenu_orden) ".
                "VALUES(".$idRubrica.",".$idEnunciado.", ".$orden." )";
    
    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    
    $idNuevo = mysql_insert_id($conexion);
        
    return $idNuevo;
}

function obtenerTiposEvalFunction($id_actividad, $conexion){
    $consulta = 
            "SELECT DISTINCT(ev.ev_id_tipoevaluacion) ".
            "FROM `por_evaluacion_actividad` evac ".
            "LEFT JOIN por_evaluacion ev ".
            "ON (evac.evac_id_evaluacion = ev.ev_id_evaluacion ) ".
            "WHERE evac.evac_id_actividad = ".$id_actividad." ";
    
    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;    
}

function obtenerAutorFuncion($id_diseno, $conexion){

    $consulta = "SELECT u_nombre, u_usuario, u_usuario, u_url_imagen ".
                "FROM diseno_didactico d, usuario u ".
                "WHERE ".
                    "d.dd_id_autor = u.u_id_usuario ".
                    "AND d.dd_id_diseno_didactico = ".$id_diseno." ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;    
}
function obtenerParticipantesFuncion($id_diseno, $conexion){

    $consulta = "SELECT u_id_usuario, u_nombre, u_url_imagen, t.ta_color ".
                "FROM tdd_autores t, usuario u ".
                "WHERE ".
                    "t.ta_id_autor = u.u_id_usuario ".
                    "AND t.ta_id_diseno_didactico = ".$id_diseno." ".
                    "AND t.ta_invitacion = 1 ".
                "ORDER BY u_nombre ASC";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
        
    return $_datos;     
}
function obtenerParticipanteColorFuncion($id_diseno, $id_participante, $conexion){

    $consulta = "SELECT ta_color ".
                "FROM tdd_autores t ".
                "WHERE ".
                    "t.ta_id_autor = ".$id_participante." ".
                    "AND t.ta_id_diseno_didactico = ".$id_diseno." ".
                    "AND t.ta_invitacion = 1 ".
                "";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            return $fila['ta_color'];
        }
    }
        
    return "#000000";     
}
function dejarColaboracionFuncion($id_diseno, $id_usuario, $conexion){

        $consulta = "DELETE FROM tdd_autores ".
                    "WHERE ".
                        "ta_id_autor = ".$id_usuario." ".
                        "AND ta_id_diseno_didactico = ".$id_diseno;

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

    return $_resultado;     
}
function obtenerInvitacionesFuncion($id_diseno, $conexion){

    $consulta = "SELECT u.u_nombre, u.u_url_imagen ".
                "FROM tdd_autores t, usuario u ".
                "WHERE ".
                    "t.ta_id_autor = u.u_id_usuario ".
                    "AND t.ta_id_diseno_didactico = ".$id_diseno." ".
                    "AND t.ta_invitacion = 0 ".
                "ORDER BY u.u_nombre ASC";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    return $_datos;     
}
function obtenerInvitacionesRecibFuncion($invitado, $conexion){

    $consulta = "SELECT t.ta_id_autor, t.ta_usu_invita, t.ta_id_diseno_didactico, d.dd_nombre, d.dd_nivel, d.dd_subsector, u.u_nombre, u_url_imagen ".
                "FROM tdd_autores t LEFT JOIN usuario u ON (u.u_id_usuario = t.ta_usu_invita), ".
                    "diseno_didactico d ".
                "WHERE ".
                    "t.ta_id_autor = ".$invitado." ".
                    "AND t.ta_invitacion = 0 ".
                    "AND d.dd_id_diseno_didactico = t.ta_id_diseno_didactico ".
                "ORDER BY u.u_nombre ASC";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    return $_datos;     
}
function buscarColaboradoresFuncion($id_diseno, $sector, $end, $conexion){
    
    $consulta = "SELECT d.dd_id_autor ".
                "FROM diseno_didactico d ".
                "WHERE ".
                    "d.dd_id_diseno_didactico = ".$id_diseno." ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos_temp=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos_temp[]=$fila;
        }
    }
    if($_datos_temp[0]['dd_id_autor']+0 <= 0) $_datos_temp[0]['dd_id_autor'] = -1;
    $consulta = "SELECT SQL_CALC_FOUND_ROWS u.u_nombre, u.u_usuario, u.u_id_usuario, u.u_url_imagen ".
                "FROM usuario u ".
                "WHERE ".
                "u.u_id_usuario IN (SELECT DISTINCT(ue.ue_id_usuario) FROM usuario_experiencia ue WHERE ue.ue_id_experiencia IN ( SELECT e.ed_id_experiencia FROM experiencia_didactica e,  diseno_didactico d WHERE e.ed_publicado = 1 AND ( d.dd_subsector = '".$sector."' OR ue.ue_rol_usuario=3 ) AND d.dd_id_diseno_didactico = e.ed_id_diseno_didactico ) AND (ue.ue_rol_usuario = 1 OR  ue.ue_rol_usuario=3) ) ".
                "AND u.u_id_usuario NOT IN (SELECT DISTINCT(us.u_id_usuario) FROM tdd_autores t, usuario us WHERE t.ta_id_autor = us.u_id_usuario AND t.ta_id_diseno_didactico = ".$id_diseno.") ".
                "AND u.u_id_usuario <> ".$_datos_temp[0]['dd_id_autor']." ".
                "ORDER BY u.u_nombre ASC ".
                "LIMIT 0, ".$end." ";    

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_rowsCount = mysql_fetch_row(mysql_query("SELECT FOUND_ROWS()"));

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
    $_datos[] = $_rowsCount[0];

    return $_datos;    
}
function agregarInvitacionFuncion($id_diseno, $id_usuario_destino, $id_usuario_publica, $mensaje, $conexion){

        $consulta = "INSERT into mu_mensajes(".
                        "mumj_id_usuario_muro, mumj_id_usuario_publica, mumj_fecha, mumj_mensaje) ".
                    "VALUES(".$id_usuario_destino.",".$id_usuario_publica.", NOW(), '".$mensaje."')";        
        
        $_resultado = dbEjecutarConsulta($consulta, $conexion);    
    
        $consulta = "INSERT into tdd_autores(".
                        "ta_id_autor, ta_id_diseno_didactico, ta_usu_invita, ta_invitacion, ta_colaboraciones) ".
                    "VALUES(".$id_usuario_destino.",".$id_diseno.",".$id_usuario_publica.", 0, 0)";        
echo $consulta;
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

    return $_resultado;        
}
function aceptarInvitacionFuncion($id_diseno, $id_usuario, $conexion){
    global $_ta_colaborador_color;
    
    //$_ta_colaborador_color
        $count = 1;
        $consulta_count = "SELECT count(*) as 'count' FROM `tdd_autores` WHERE ta_id_diseno_didactico = ".$id_diseno." ";    
        $_resultado_count = dbEjecutarConsulta($consulta_count, $conexion);
        $_rowsCount = mysql_fetch_row(mysql_query("SELECT FOUND_ROWS()"));

        if($_resultado_count){
            while ($fila=mysql_fetch_array($_resultado_count, MYSQL_ASSOC)) {
                $_rowsCount= $fila['count'];
            }
        } 
        
        $consulta = "UPDATE tdd_autores SET " .
                        "ta_invitacion = 1, " .
                        "ta_color = '".$_ta_colaborador_color[$count-1]."' ".
                    "WHERE " .
                        "ta_id_autor = ".$id_usuario." ".
                        "AND ta_id_diseno_didactico = ".$id_diseno." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 

}
function obtenerHerramientaDisenoFuncion($id_diseno, $conexion){
    
    $consulta = "SELECT * ".
                "FROM herramientas_diseno hd, herramientas_web hw ".
                "WHERE ".
                    "hd_id_diseno_didactico = ".$id_diseno." ".
                    "AND hd.hd_id_herramienta = hw.hw_id_herramienta";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;   
}
function buscarActividadComplemFuncion($idDiseno, $etapa_orden, $actividad_orden, $conexion){
    
    $consulta = "SELECT ac_id_actividad, ac_nombre, ac_id_etapa, e_orden ".
                "FROM actividad a, etapa e ".
                "WHERE ".
                    "a.ac_id_etapa = e.e_id_etapa ".
                    "AND (e.e_orden*10 + a.ac_orden) < (".($etapa_orden*10+$actividad_orden).") ".
                    "AND e.e_id_diseno_didactico = ".$idDiseno." ".
                    "AND a.ac_publica_producto = 1";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);

    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;       
}
function buscarExistenciaComentariosFuncion($id, $tipo, $conexion){
    
    $consulta = "SELECT count(tc_id_comentario) as cont ".
                "FROM tdd_comentarios ".
                "WHERE ".
                    "tc_tipo = ".$tipo." ".
                    "AND  tc_id_relacion = ".$id." ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;       
}
function bloquearDisenoFuncion($tipo, $id_bloqueo, $id_usuario, $conexion){

        $bloqueada= false;
        dbEjecutarConsulta("LOCK TABLES tdd_bloqueo WRITE", $conexion);
    
        $consulta = "SELECT * ".
                    "FROM tdd_bloqueo ".
                    "WHERE ".
                        "tb_tipo = ".$tipo." ".
                        "AND  tb_id_bloqueo = ".$id_bloqueo." ";

            $_datos=array();
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        if($_resultado){

            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                        
                $_datos[]=$fila;
            }
            if( count($_datos) > 0){
                $bloqueada= true;
            }
            else{
                $bloqueada= false;
                $consulta = "INSERT into tdd_bloqueo(".
                                "tb_tipo, tb_id_bloqueo, tb_fecha, tb_id_usuario) ".
                            "VALUES(".$tipo.",".$id_bloqueo.", NOW(), ".$id_usuario.")";        
                
                $_resultado2 = dbEjecutarConsulta($consulta, $conexion);            
            }
    }
        
        dbEjecutarConsulta("UNLOCK TABLES", $conexion);

    return $_datos;     
}
function desbloquearDisenoFuncion($tipo, $id_bloqueo, $id_usuario, $conexion){

        dbEjecutarConsulta("LOCK TABLES tdd_bloqueo WRITE", $conexion);
    
         $consulta = "DELETE FROM tdd_bloqueo " .
                    "WHERE " .
                        "tb_tipo = ".$tipo." ".
                        "AND tb_id_bloqueo= ".$id_bloqueo." ".
                        "AND tb_id_usuario= ".$id_usuario." ";       

        $_resultado = dbEjecutarConsulta($consulta, $conexion);       
       
        dbEjecutarConsulta("UNLOCK TABLES", $conexion);

    return;     
}
function bloquearActividadFuncion($tipo, $id_bloqueo, $id_usuario, $conexion){

        $bloqueada= false;
        dbEjecutarConsulta("LOCK TABLES tdd_bloqueo WRITE", $conexion);
    
        $consulta = "SELECT * ".
                    "FROM tdd_bloqueo ".
                    "WHERE ".
                        "tb_tipo = ".$tipo." ".
                        "AND  tb_id_bloqueo = ".$id_bloqueo." ";

            $_datos=array();
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        if($_resultado){

            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                        
                $_datos[]=$fila;
            }
            if( count($_datos) > 0){
                $bloqueada= true;
            }
            else{
                $bloqueada= false;
                $consulta = "INSERT into tdd_bloqueo(".
                                "tb_tipo, tb_id_bloqueo, tb_fecha, tb_id_usuario) ".
                            "VALUES(".$tipo.",".$id_bloqueo.", NOW(), ".$id_usuario.")";        
                
                $_resultado2 = dbEjecutarConsulta($consulta, $conexion);            
            }
    }
        
        dbEjecutarConsulta("UNLOCK TABLES", $conexion);

    return $_datos;     
}
function desbloquearActividadFuncion($tipo, $id_bloqueo, $id_usuario, $conexion){

        dbEjecutarConsulta("LOCK TABLES tdd_bloqueo WRITE", $conexion);
    
         $consulta = "DELETE FROM tdd_bloqueo " .
                    "WHERE " .
                        "tb_tipo = ".$tipo." ".
                        "AND tb_id_bloqueo= ".$id_bloqueo." ".
                        "AND tb_id_usuario= ".$id_usuario." ";       

        $_resultado = dbEjecutarConsulta($consulta, $conexion);       
       
        dbEjecutarConsulta("UNLOCK TABLES", $conexion);

    return;     
}
function desbloquearTodoFuncion($id_usuario, $conexion){

        dbEjecutarConsulta("LOCK TABLES tdd_bloqueo WRITE", $conexion);
    
         $consulta = "DELETE FROM tdd_bloqueo " .
                    "WHERE " .
                        "tb_id_usuario= ".$id_usuario." ";       

        $_resultado = dbEjecutarConsulta($consulta, $conexion);       
       
        dbEjecutarConsulta("UNLOCK TABLES", $conexion);

    return;     
}
function desbloquearTodasActividadesFuncion($tipo, $id_usuario, $conexion){

        dbEjecutarConsulta("LOCK TABLES tdd_bloqueo WRITE", $conexion);
    
         $consulta = "DELETE FROM tdd_bloqueo " .
                    "WHERE " .
                        "tb_tipo = ".$tipo." ".
                        "AND tb_id_usuario= ".$id_usuario." ";       

        $_resultado = dbEjecutarConsulta($consulta, $conexion);       
       
        dbEjecutarConsulta("UNLOCK TABLES", $conexion);

    return;     
}
function obtenerNombreUsuarioFuncion($id_usuario, $conexion){
    
    $consulta = "SELECT u_nombre ".
                "FROM usuario ".
                "WHERE ".
                    "u_id_usuario = ".$id_usuario." ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;       
}

function obtenerCorreoUsuarioFuncion($id_usuario, $conexion){
    
    $consulta = "SELECT u_email ".
                "FROM usuario ".
                "WHERE ".
                    "u_id_usuario = ".$id_usuario." ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;       
}

function existeActividadFuncion($id_actividad, $conexion){
    
    $consulta = "SELECT ac_id_actividad ".
                "FROM actividad ".
                "WHERE ".
                    "ac_id_actividad = ".$id_actividad." ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }

    return $_datos;     
}

function obtenerComentariosFuncion($id_relacion, $tipo, $end, $conexion){
    
        $consulta = "SELECT SQL_CALC_FOUND_ROWS tc_fecha, u.u_nombre, tc_texto_comentario, u_url_imagen ".
                    "FROM tdd_comentarios tc, usuario u ".
                    "WHERE ".
                        "tc.tc_id_relacion = ".$id_relacion." ".
                        "AND tc.tc_tipo = ".$tipo." ".
                        "AND u.u_id_usuario = tc.tc_id_usuario ".
                    "ORDER BY tc_fecha DESC ".
                    "LIMIT 0,".$end." ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_rowsCount = mysql_fetch_row(mysql_query("SELECT FOUND_ROWS()"));
        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        $_datos[] = $_rowsCount[0];
        
    return $_datos;     
}

function agregarComentarioFuncion($id_diseno, $id_usuario_publica, $mensaje, $tipo, $conexion){  

        $consulta = "INSERT into tdd_comentarios(".
                        "tc_tipo, tc_id_relacion, tc_id_usuario, tc_fecha, tc_texto_comentario) ".
                    "VALUES(".$tipo.",".$id_diseno.",".$id_usuario_publica.", NOW(), '".$mensaje."')";        
        
        $_resultado = dbEjecutarConsulta($consulta, $conexion);    
    
    return $_resultado;        
}
function limpiarBloqueosAntiguosFuncion($conexion){

        dbEjecutarConsulta("LOCK TABLES tdd_bloqueo WRITE", $conexion);
    
         $consulta = "DELETE FROM tdd_bloqueo " .
                    "WHERE " .
                        "tb_fecha < NOW() - INTERVAL 3 HOUR ";       

        $_resultado = dbEjecutarConsulta($consulta, $conexion);       
        dbEjecutarConsulta("UNLOCK TABLES", $conexion);

    return;     
}
function agregarRegistroCambio($idUsuario, $idDiseno, $idActividad, $tipoElemento, $accion, $texto, $consulta_r, $conexion){

        $consulta_r = str_replace("'", '"', $consulta_r);
        $consulta = "INSERT into tdd_registro_cambios(".
                        "trc_id_diseno, trc_id_actividad, trc_id_usuario, trc_fecha, trc_tipo, trc_accion, trc_texto, trc_consulta) ".
                    "VALUES(".$idDiseno.",".$idActividad.",".$idUsuario.", NOW(),".$tipoElemento.", ".$accion.", '".$texto."', '".$consulta_r."')";        

        $_resultado = dbEjecutarConsulta($consulta, $conexion);    
    
    return false;    
}
function obtenerCambiosFuncion($id_diseno, $todos, $conexion){
    
    $consulta = "SELECT rc.trc_texto, u.u_nombre, rc.trc_fecha FROM tdd_registro_cambios rc LEFT JOIN usuario u ".
                    "ON(rc.trc_id_usuario = u.u_id_usuario) ".
                "WHERE rc.trc_id_diseno = ".$id_diseno." ".
                "ORDER BY trc_fecha DESC ";
    if($todos == 0){ 
        $consulta .= "LIMIT 0, 2 ";
    }

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    }
        
    return $_datos;    
}
function obtenerCorreosAdminFuncion($conexion){
    
    
    $consulta = "SELECT * FROM usuario WHERE u_administrador = 1 AND u_activo = 1 AND u_email <> '' ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila['u_email'];
        }
    }
        
    return $_datos;    
}
function tddBuscarColaboradores($nombre,$conexion){
  $consulta = "SELECT " .
            "U.u_id_usuario, " .
            "U.u_usuario, " .
            "U.u_nombre, " .
            "U.u_email, " .
            "U.u_url_imagen ".         
            "FROM usuario U " .
            "WHERE " .
            "U.u_nombre like '%" . $nombre . "%' AND " .
            "U.u_inscribe_diseno = 1 AND ". 
            "U.u_activo = 1;";
  
  
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    $_resp = null;

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"] = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]    = $_fila["u_usuario"];            
                $_resp[$i]["nombre"] = $_fila["u_nombre"];
                $_resp[$i]["email"] = $_fila["u_email"];
                $_resp[$i]["url_imagen"] = $_fila["u_url_imagen"];
                $i++;
            }
        }
    }

    return $_resp;
    
}
function obtenerCorreosColabFuncion($idDiseno, $conexion){

    $consulta = "SELECT u_email FROM usuario WHERE u_email <> '' AND u_id_usuario IN (".
                    "SELECT ta_id_autor as id_us FROM tdd_autores WHERE ta_id_diseno_didactico = ".$idDiseno." AND ta_invitacion = 1) ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[] = $fila['u_email'];
        }
    }

    return $_datos;    
}
function obtenerCorreosProfeFuncion($conexion){

    $consulta = "SELECT u_email FROM usuario WHERE u_email <> '' AND u_inscribe_diseno = 1 || u_administrador = 1 ";
    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[] = $fila['u_email'];
        }
    }

    return $_datos;    
}
function obtenerDisenoActividadFuncion($id_actividad, $conexion){
    $consulta = "SELECT ac_nombre, dd_id_diseno_didactico, dd_nombre ".
                "FROM actividad, etapa LEFT JOIN diseno_didactico ON (e_id_diseno_didactico = dd_id_diseno_didactico) ".
                "WHERE ac_id_actividad = ".$id_actividad." AND ac_id_etapa = e_id_etapa ";

    $_resultado = dbEjecutarConsulta($consulta, $conexion);
    $_datos=array();
    if($_resultado){
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[] = $fila;
        }
    }

    return $_datos;     
}

function enviarRevision($id_diseno,$conexion){
        $resp=0;
        $consulta = "UPDATE diseno_didactico SET " .
                      "dd_revision= 1 ".
                    "WHERE " .
                      "dd_id_diseno_didactico=".$id_diseno;
        $resultado = dbEjecutarConsulta($consulta, $conexion);

        if($resultado){

            if(mysql_affected_rows()>0){
              $resp=1;
            }
            
        }
        return $resp;
}

function removeHtmlColorTags($id_diseno, $conexion){
    $_diseno = obtenerDisenoFuncion($id_diseno, $conexion);

    /*Diseno*/
    $consulta_1 = "UPDATE diseno_didactico ".
                "SET ".
                    "dd_descripcion ='".strip_color_tags($_diseno[0]['dd_descripcion'])."', ". 
                    "dd_objetivos_curriculares ='".strip_color_tags($_diseno[0]['dd_objetivos_curriculares'])."', ". 
                    "dd_objetivos_transversales ='".strip_color_tags($_diseno[0]['dd_objetivos_transversales'])."', ". 
                    "dd_contenidos ='".strip_color_tags($_diseno[0]['dd_contenidos'])."', ".
                    "dd_descripcion_e1 ='".strip_color_tags($_diseno[0]['dd_descripcion_e1'])."', ". 
                    "dd_descripcion_e2 ='".strip_color_tags($_diseno[0]['dd_descripcion_e2'])."', ". 
                    "dd_descripcion_e3 ='".strip_color_tags($_diseno[0]['dd_descripcion_e3'])."' ".
                "WHERE ". 
                "dd_id_diseno_didactico = ".$id_diseno." ";
    $_resultado_1 = dbEjecutarConsulta($consulta_1, $conexion); 
    
    /*Etapas*/
    $etapas = obtenerEtapasFuncion($id_diseno, $conexion);
    for($i=0; $i<count($etapas); $i++){
        $consulta = "UPDATE etapa ".
                    "SET ".
                    "e_descripcion = '".strip_color_tags($etapas[$i]['e_descripcion'])."' ".
                    "WHERE ". 
                    "e_id_etapa = " .$etapas[$i]['e_id_etapa']. " ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        /*Actividades*/
        $actividades = null;
        $actividades = obtenerActividadesPorEtapaFuncion($etapas[$i]['e_id_etapa'], $conexion);        
        for($j=0; $j<count($actividades); $j++){            
                
            $consulta = "UPDATE actividad ".
                    "SET ".
                        "ac_instrucciones_inicio = '".strip_color_tags($actividades[$j]['ac_instrucciones_inicio'])."', ".	 	 	
                        "ac_instrucciones_desarrollo = '".strip_color_tags($actividades[$j]['ac_instrucciones_desarrollo'])."', ". 	 	 	 	 	 	
                        "ac_instrucciones_cierre = '".strip_color_tags($actividades[$j]['ac_instrucciones_cierre'])."', ".	 	 	 	 	 	
                        "ac_descripcion = '".strip_color_tags($actividades[$j]['ac_descripcion'])."', ".                                
                  //       "ac_instrucciones_producto = '".strip_color_tags($actividades[$j]['ac_instrucciones_producto'])."', ".	 	 	 	 	
                  //      "ac_instrucciones_revision = '".strip_color_tags($actividades[$j]['ac_instrucciones_revision'])."', ".
                        "ac_aprendizaje_esperado = '".strip_color_tags($actividades[$j]['ac_aprendizaje_esperado'])."', ".
                        "ac_evidencia_aprendizaje = '".strip_color_tags($actividades[$j]['ac_evidencia_aprendizaje'])."', ".
                        "ac_medios = '".strip_color_tags($actividades[$j]['ac_medios'])."', ".
                        "ac_consejos_practicos = '".strip_color_tags($actividades[$j]['ac_consejos_practicos'])."', ".
                        "ac_medios_otros = '".strip_color_tags($actividades[$j]['ac_medios_otros'])."' ".
                    "WHERE  ".
                        "ac_id_actividad = ".strip_color_tags($actividades[$j]['ac_id_actividad'])." ";  
            $_resultado = dbEjecutarConsulta($consulta, $conexion);

        }
    }

    
}

function strip_color_tags($string){
    return strip_tags($string, "<b><i><u><br><ol><li><ul>");
}

?>