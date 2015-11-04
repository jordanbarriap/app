<?php

    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/


$carpeta_subida_archivos = "dd/actividades";
$ruta_base= "./../";


function obtenerActividadFuncion($idActividad, $conexion){
     
        $consulta = "SELECT * ".
                    "FROM actividad_con a, etapa_con e ".
                    "WHERE ".
                        "ac_id_actividad_con = ".$idActividad." ".
                        "AND a.ac_id_etapa_con = e.e_id_etapa_con ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    return $_datos;             
}

function agregarActividadFuncion($idEtapa, $conexion){
     
        $consulta = "SELECT MAX(ac_orden) as max_orden ".
                    "FROM actividad_con ".
                    "WHERE ".
                        "ac_id_etapa_con = ".$idEtapa." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
        if(count($_datos)<1){$_datos[0]['max_orden']= 0;}
        $iniciadora=1;
        if($_datos[0]['max_orden']>0) $iniciadora=0;
        $consulta = "INSERT into actividad_con(".
                        "ac_nombre,ac_descripcion,ac_tipo,ac_id_etapa_con, ac_orden,ac_iniciadora,ac_fecha_creacion) ".
                    "VALUES('nombre actividad','descripción de actividad',1,".$idEtapa.", ".($_datos[0]['max_orden']+1).", ".$iniciadora.", NOW())";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
    return $_resultado;   
}

function obtenerActividadesPorEtapaFuncion($idEtapa, $conexion){    
     
        $consulta = "SELECT * ".
                    "FROM actividad_con a, etapa_con e ".
                    "WHERE ".
                        "ac_id_etapa_con = ".$idEtapa." ".
                        "AND a.ac_id_etapa_con = e.e_id_etapa_con ".
                    "ORDER by ac_orden asc";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    return $_datos;     
}

function bajarActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion){
    
        $consulta = "UPDATE actividad_con SET " .
                        "ac_orden = ac_orden -1 " .
                    "WHERE " .
                        "ac_id_etapa_con = ".$id_etapa." ".
                        "AND ac_orden = ".($actividad_orden+1)." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $consulta = "UPDATE actividad_con SET " .
                        "ac_orden = ac_orden +1 " .
                    "WHERE " .
                        "ac_id_actividad_con = ".$id_actividad." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 

        if($actividad_orden == 1){
            $consulta = "UPDATE actividad_con SET " .
                            "ac_iniciadora = 0 " .
                        "WHERE " .
                            "ac_id_etapa_con = ".$id_etapa." ";
            
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $consulta = "UPDATE actividad_con SET " .
                            "ac_iniciadora = 1 " .
                        "WHERE " .
                            "ac_id_etapa_con = ".$id_etapa." ".
                            "AND ac_orden = 1";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);   
        }
        
}
function subirActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion){
    
        $consulta = "UPDATE actividad_con SET " .
                        "ac_orden = ac_orden +1 " .
                    "WHERE " .
                        "ac_id_etapa_con = ".$id_etapa." ".
                        "AND ac_orden = ".($actividad_orden-1)." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 
 
        
        $consulta = "UPDATE actividad_con SET " .
                        "ac_orden = ac_orden -1 " .
                    "WHERE " .
                        "ac_id_actividad_con = ".$id_actividad." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion); 

        if($actividad_orden == 2){
            $consulta = "UPDATE actividad_con SET " .
                            "ac_iniciadora = 0 " .
                        "WHERE " .
                            "ac_id_etapa_con = ".$id_etapa." "; 
            
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $consulta = "UPDATE actividad_con SET " .
                            "ac_iniciadora = 1 " .
                        "WHERE " .
                            "ac_id_etapa_con = ".$id_etapa." ".
                            "AND ac_orden = 1";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);   
        }        
             
}
function eliminarActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $conexion){
    
        $consulta = "UPDATE actividad_con SET " .
                        "ac_orden = ac_orden -1 " .
                    "WHERE " .
                        "ac_id_etapa_con = ".$id_etapa." ".
                        "AND ac_orden > ".($actividad_orden)." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $consulta = "DELETE FROM actividad_con " .
                    "WHERE " .
                        "ac_id_actividad_con = ".$id_actividad." ";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        if($actividad_orden == 1){
            $consulta = "UPDATE actividad_con SET " .
                            "ac_iniciadora = 1 " .
                        "WHERE " .
                            "ac_id_etapa_con = ".$id_etapa." ".
                            "AND ac_orden = 1";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);   

        }

}

function obtenerDisenoFuncion($idDiseno, $conexion){
    
        $consulta = "SELECT * ".
                    "FROM diseno_didactico_con LEFT JOIN herramientas_diseno_con ".
                        "ON diseno_didactico_con.dd_id_diseno_didactico_con = herramientas_diseno_con.hd_id_diseno_didactico_con  ".
                    "WHERE ".
                        "dd_id_diseno_didactico_con = ".$idDiseno."";
        
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
    return $_datos;    
}
function obtenerDisenoConFuncion($idUser, $conexion){
    
        $consulta = "SELECT * ".
                    "FROM diseno_didactico_con LEFT JOIN herramientas_diseno_con ".
                        "ON diseno_didactico_con.dd_id_diseno_didactico_con = herramientas_diseno_con.hd_id_diseno_didactico_con  ".
                    "WHERE ".
                        "dd_id_autor = ".$idUser."";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
    return $_datos;    
}

function obtenerEtapasFuncion($idDiseno, $conexion){
   
        $consulta = "SELECT * ".
                    "FROM etapa_con ".
                    "WHERE ".
                        "e_id_diseno_didactico_con = ".$idDiseno." ".
                    "ORDER by e_orden asc";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }

    return $_datos; 
}

function guardarActividadFuncion( $id_actividad, $nombre, $descripcion_general, $aprendizaje_esperado, $tipo, $conexion){
    

//Actualizamos la actividad
$consulta =     "UPDATE actividad_con ".
                "SET ".
                    "ac_nombre = '$nombre', ".	
                    "ac_descripcion = '$descripcion_general', ". 	 	 	
                    "ac_aprendizaje_esperado = '$aprendizaje_esperado', ".
                    "ac_tipo = $tipo ".
                "WHERE  ".
                    "ac_id_actividad_con = $id_actividad ";    
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
}


function obtenerMisDisenosFuncion($idUsuario, $conexion){

        $consulta = "SELECT * ".
                    "FROM diseno_didactico_con ".
                    "WHERE ".
                        "dd_id_autor = '".$idUsuario."' ".
                    "ORDER BY dd_fecha_creacion DESC";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
        return $_datos;
      
}

function obtenerDisenosParticipoFuncion($idUsuario, $conexion){

        $consulta = "SELECT * ".
                    "FROM diseno_didactico_con d, tdd_autores t ".
                    "WHERE ".
                        "t.ta_id_autor = ".$idUsuario." ".
                        "AND t.ta_id_diseno_didactico = d.dd_id_diseno_didactico_con ".
                        "AND t.ta_invitacion = 1 ".
                    "ORDER BY d.dd_fecha_creacion DESC";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }

        return $_datos;
      
}


/*Funcion que agrega un nuevo diseño didactico y retorna el id con el cual fue creado*/
function crearDisenoFuncion($usuario, $conexion){
        //Creamos el nuevo Diseno
        $consulta =     "INSERT into diseno_didactico_con(dd_id_autor, dd_fecha_creacion) ".
                        "VALUES('".$usuario."', NOW())";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $result = $_resultado;
        $idDiseno = mysql_insert_id($conexion);
        //Creamos la relacion con la herramienta web
        $consulta =     "INSERT into herramientas_diseno_con(hd_id_herramienta_con,hd_id_diseno_didactico_con) ".
                        "VALUES(1,".$idDiseno.")";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Creamos la Etapa 1 para el diseno recien creado
        $consulta =     "INSERT into etapa_con(e_id_diseno_didactico_con,e_orden) ".
                        "VALUES(".$idDiseno.",1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapa1 = mysql_insert_id($conexion);

        //#Creamos la Etapa 2 para el diseno recien creado
        $consulta =     "INSERT into etapa_con(e_id_diseno_didactico_con,e_orden) ".
                        "VALUES(".$idDiseno.",2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapa2 = mysql_insert_id($conexion);

        //#Creamos la Etapa 3 para el diseno recien creado
        $consulta =     "INSERT into etapa_con(e_id_diseno_didactico_con,e_orden) ".
                        "VALUES(".$idDiseno.",3)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $idEtapa3 = mysql_insert_id($conexion);

        //#Por ultimo creamos las actividades por defecto para cada etapa;
        //#Actividades etapa 1
        $consulta =     "INSERT into actividad_con(ac_nombre,ac_descripcion, ac_id_etapa_con, ac_orden,ac_iniciadora,ac_fecha_creacion, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa1.", 1, 1, NOW(), 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $consulta =     "INSERT into actividad_con(ac_nombre,ac_descripcion, ac_id_etapa_con, ac_orden,ac_fecha_creacion,  ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa1.", 2, NOW(), 2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        //#Actividades etapa 2
        $consulta =     "INSERT into actividad_con(ac_nombre,ac_descripcion, ac_id_etapa_con, ac_orden,ac_fecha_creacion,  ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa2.", 1, NOW(), 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $consulta =     "INSERT into actividad_con(ac_nombre,ac_descripcion, ac_id_etapa_con, ac_orden,ac_fecha_creacion,  ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa2.", 2, NOW(), 2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $consulta =     "INSERT into actividad_con(ac_nombre,ac_descripcion, ac_id_etapa_con, ac_orden,ac_fecha_creacion,  ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa2.", 3, NOW(), 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Actividades etapa 3
        $consulta =     "INSERT into actividad_con(ac_nombre,ac_descripcion, ac_id_etapa_con, ac_orden,ac_fecha_creacion, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa3.", 1, NOW(), 1)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $consulta =     "INSERT into actividad_con(ac_nombre,ac_descripcion, ac_id_etapa_con, ac_orden,ac_fecha_creacion, ac_tipo) ".
                        "VALUES('nombre actividad','descripción de actividad',".$idEtapa3.", 2, NOW(), 2)";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        if($result>0){
            return $idDiseno;
        }else return -1;
}

/*Funcion que actualiza un Diseño Didáctico*/
function actualizarDisenoFuncion($id_autor, $nombre, $idDiseno, $sector, $nivel, $descripcion, $objCurriculares, $objTransversales,$contenidos, $descEtapa1, $descEtapa2, $descEtapa3, $web20, $conexion){
    
        //#Actualizamos el Diseno
        $consulta = "UPDATE diseno_didactico_con ".
                    "SET ".
                        "dd_nombre ='$nombre', ". 
                        "dd_nivel ='$nivel', ". 
                        "dd_subsector ='$sector', ". 
                        "dd_id_autor =$id_autor, ". 
                        "dd_descripcion ='$descripcion', ".
                        "dd_objetivos_curriculares ='$objCurriculares', ". 
                        "dd_objetivos_transversales ='$objTransversales', ". 
                        "dd_contenidos ='$contenidos', ".
                        "dd_descripcion_e1 ='$descEtapa1', ". 
                        "dd_descripcion_e2 ='$descEtapa2', ". 
                        "dd_descripcion_e3 ='$descEtapa3' ".                
                    "WHERE ". 
                    "dd_id_diseno_didactico_con = $idDiseno;";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        //#Actualizamos la relacion con la herramienta web
        $consulta = "UPDATE herramientas_diseno_con ".
                    "SET hd_id_herramienta_con = $web20 ".
                    "WHERE hd_id_diseno_didactico_con = $idDiseno";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);

}
function enviarDisenoFuncion($idDiseno, $conexion){
         //#Actualizamos el Diseno
        $consulta = "UPDATE diseno_didactico_con ".
                    "SET ".
                        "dd_terminado = 1 ". 
                    "WHERE ". 
                    "dd_id_diseno_didactico_con = $idDiseno;";
        $_resultado = dbEjecutarConsulta($consulta, $conexion);   
}

function obtenerHerramientasWebFuncion($conexion){

        $consulta = "SELECT * ".
                    "FROM herramientas_web ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }
        
    return $_datos;
        
}

function obtenerAutorFuncion($id_diseno, $conexion){

        $consulta = "SELECT u_nombre, u_url_imagen ".
                    "FROM diseno_didactico_con d, usuario u ".
                    "WHERE ".
                        "d.dd_id_autor = u.u_id_usuario ".
                        "AND d.dd_id_diseno_didactico_con = ".$id_diseno." ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }

    return $_datos;    
}

function obtenerHerramientaDisenoFuncion($id_diseno, $conexion){
    
        $consulta = "SELECT * ".
                    "FROM herramientas_diseno_con hd, herramientas_web hw ".
                    "WHERE ".
                        "hd_id_diseno_didactico_con = ".$id_diseno." ".
                        "AND hd.hd_id_herramienta_con = hw.hw_id_herramienta";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }

    return $_datos;   
}

function obtenerNombreUsuarioFuncion($id_usuario, $conexion){
    
        $consulta = "SELECT u_nombre ".
                    "FROM usuario ".
                    "WHERE ".
                        "u_id_usuario = ".$id_usuario." ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }

    return $_datos;       
}

function existeActividadFuncion($id_actividad, $conexion){
    
        $consulta = "SELECT ac_id_actividad_con ".
                    "FROM actividad_con ".
                    "WHERE ".
                        "ac_id_actividad_con = ".$id_actividad." ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
            $_datos[]=$fila;
        }

    return $_datos;     
}



?>