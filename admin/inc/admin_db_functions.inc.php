<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function dbAdminExpSemestresExperiencias($conexion){
    $consulta = "SELECT DISTINCT ".
                "ED.ed_semestre, ED.ed_anio ".
                "FROM experiencia_didactica ED ".
                "WHERE (ED.ed_semestre IS NOT NULL AND ED.ed_semestre!=0) ".
                "AND (ED.ed_anio IS NOT NULL AND ED.ed_anio !=0 ) ".
                "ORDER BY ED.ed_anio DESC, ED.ed_semestre DESC ";
//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
     if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["semestre"]= $_fila["ed_semestre"];
                $_resp[$i]["anio"]= $_fila["ed_anio"];
                $i++;
            }
        }
        else {
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerExpDisenoPeriodo($conexion,$id_dd, $semestre,$anio){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "D.dd_nombre, ".
                    "D.dd_id_diseno_didactico, ".
                    "D.dd_nivel, ".
                    "D.dd_subsector, ".
                    "E.ed_id_experiencia, ".
                    "E.ed_localidad, ".
                    "E.ed_curso, ".
                    "E.ed_colegio, ".
                    "E.ed_fecha_inicio, " .
                    "E.ed_fecha_termino, " .
                    "E.ed_fecha_ultima_sesion, " .
                    "E.ed_experiencia_profesor, " .
                    "E.ed_id_profesor, " .
                    "U.u_nombre, " .
                    "U.u_usuario, " .
                    "U.u_url_imagen " .
                "FROM ".
                    "experiencia_didactica E, ".
                    "usuario U, ".
                    "diseno_didactico D ".
                "WHERE ".
                    "D.dd_id_diseno_didactico=".$id_dd." AND ".
                    "E.ed_semestre='".$semestre."' AND ".
                    "E.ed_anio=".$anio." AND ".
                    "D.dd_id_diseno_didactico = E.ed_id_diseno_didactico AND ".
                    "U.u_id_usuario = E.ed_id_profesor ";
   
    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){

            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["nombre_dd"]         = $_fila["dd_nombre"];
                $_resp[$i]["nivel"]             = $_fila["dd_nivel"];
                $_resp[$i]["subsector"]         = $_fila["dd_subsector"];
                $_resp[$i]["id_experiencia"]    = $_fila["ed_id_experiencia"];
                $_resp[$i]["localidad"]         = $_fila["ed_localidad"];
                $_resp[$i]["curso"]             = $_fila["ed_curso"];
                $_resp[$i]["colegio"]           = $_fila["ed_colegio"];
                $_resp[$i]["usuario_profesor"]  = $_fila["u_usuario"];
                $_resp[$i]["nombre_profesor"]   = $_fila["u_nombre"];
                $_resp[$i]["url_avatar_profesor"]= $_fila["u_url_imagen"];
                $_resp[$i]["fecha_termino"]      = $_fila["ed_fecha_termino"];
                $_resp[$i]["fecha_ultimo_acceso"]= $_fila["ed_fecha_ultima_sesion"];
                
                $i++;
            }
        }
    }
    return $_resp;

}
function dbAdminObtenerDisenosSubsector($conexion, $subsector) {
    $_resp = array();
    $i = 0;
    $consulta = "SELECT " .
            "D.dd_id_diseno_didactico " .
            "FROM " .
            "diseno_didactico D ". 
            "WHERE D.dd_subsector ='" . $subsector . "' " .
//            "AND D.dd_publicado=1 " .
            "ORDER BY D.dd_nivel ASC";
    
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_dd"] = $_fila["dd_id_diseno_didactico"];
                $i++;
            }
        }
    }
    return $_resp;
}
function dbAdminExpObtenerInfo($id_experiencia, $conexion) {
    $_resp = array();

    $consulta = "SELECT " .
            "DD.dd_nombre, " .
            "U.u_nombre, " .
            "U.u_usuario, " .
            "U.u_url_imagen, " .
            "ED.ed_localidad, " .
            "ED.ed_curso, " .
            "ED.ed_colegio, " .
            "ED.ed_fecha_inicio, " .
            "ED.ed_fecha_termino, " .
            "ED.ed_fecha_ultima_sesion, " .
            "ED.ed_experiencia_profesor, " .
            "ED.ed_id_profesor, " .
            "ED.ed_publicado, " .
            "ED.ed_id_diseno_didactico " .
            "FROM " .
            "experiencia_didactica ED, diseno_didactico DD, usuario U " .
            "WHERE " .
            "ED.ed_id_experiencia = '" . $id_experiencia . "' AND " .
            "ED.ed_id_diseno_didactico = DD.dd_id_diseno_didactico AND " .
            "U.u_id_usuario = ED.ed_id_profesor ;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
//    echo $consulta;
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp["nombre_dd"]             = $_fila["dd_nombre"];
                $_resp["nombre_profesor"]       = $_fila["u_nombre"];
                $_resp["usuario_profesor"]      = $_fila["u_usuario"];
                $_resp["url_avatar_profesor"]   = $_fila["u_url_imagen"];
                $_resp["localidad"]             = $_fila["ed_localidad"];
                $_resp["curso"]                 = $_fila["ed_curso"];
                $_resp["colegio"]               = $_fila["ed_colegio"];
                $_resp["fecha_inicio"]          = $_fila["ed_fecha_inicio"];
                $_resp["fecha_termino"]         = $_fila["ed_fecha_termino"];
                $_resp["fecha_ultimo_acceso"]   = $_fila["ed_fecha_ultima_sesion"];
                $_resp["experiencia_profesor"]  = $_fila["ed_experiencia_profesor"];
                $_resp["id_profesor"]           = $_fila["ed_id_profesor"];
                $_resp["publicado"]             = $_fila["ed_publicado"];
                
            }

        } else {
            //No existe experiencia didáctica.
            $_resp = null;
        }
        mysql_free_result($resultado);
    } else {
        // ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerEstudiantesExperiencia($limite_inf,$id_experiencia,$conexion) {
    $_resp=null;

    $consulta = "SELECT U.u_id_usuario, U.u_nombre, U.u_url_imagen, U.u_usuario ".
                "FROM usuario_experiencia UE, usuario U ".
                "WHERE ".
                    "UE.ue_id_experiencia =".$id_experiencia." AND ".
                    "UE.ue_rol_usuario = 2 AND ".
                    "UE.ue_id_usuario = U.u_id_usuario ".
                    "ORDER BY U.u_nombre ASC ".
                "LIMIT ".$limite_inf.", 10";
//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"]            = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]               = $_fila["u_usuario"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]            = $_fila["u_url_imagen"];
                $i++;
            }
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
    
}
function dbAdminObtenerNumeroEstudiantesExperiencia($id_experiencia, $conexion) {
    $_resp=null;

    $consulta = "SELECT count(*) as total ".
                "FROM usuario_experiencia UE, usuario U ".
                "WHERE ".
                    "UE.ue_id_experiencia =".$id_experiencia." AND ".
                    "UE.ue_rol_usuario = 2 AND ".
                    "UE.ue_id_usuario = U.u_id_usuario ";

//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp     = $_fila["total"];
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerColaboradoresExperiencia($id_experiencia, $conexion) {
    $_resp=array();

    //Obtiene información sobre los estudiantes de una expriencia
    $consulta = "SELECT distinct ".
                    "U.u_nombre, ".
                    "U.u_id_usuario, ".
                    "U.u_usuario, ".
                    "U.u_localidad, ".
                    "U.u_email, ".
                    "U.u_url_imagen ".
                "FROM ".
                    " usuario U, usuario_experiencia UE ".
                "WHERE ".
                    "UE.ue_id_experiencia = ".$id_experiencia." AND ".
                    "UE.ue_id_usuario = U.u_id_usuario AND ".
                    "UE.ue_rol_usuario In(3,4) ".
                    "GROUP BY U.u_id_usuario";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminEditarEstudiante($id_usuario, $nombre, $usuario, $conexion){
    
    $consulta1 = "SELECT u_nombre, u_usuario FROM usuario WHERE u_id_usuario = ".$id_usuario;
    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    if($resultado1) {
        $_fila=mysql_fetch_array($resultado1,MYSQL_BOTH);
        $nombre_actual = $_fila["u_nombre"];
        $usuario_actual = $_fila["u_usuario"];
    }
    // ver que el nombre de usuario no esté ocupado
    if($nombre_actual != $nombre ){
        if($usuario_actual != $usuario){
            //hay que actualizar ambos campos (nombre, usuario)
            //ver que el nombre de usuario no esté ocupado
            $consulta2 = "SELECT count(*)as total FROM usuario WHERE u_usuario = '".$usuario."'";
            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
            if($resultado2) {
                $_fila=mysql_fetch_array($resultado2,MYSQL_BOTH);
                $total = $_fila["total"];
            }
            if($total !=0){
                return 3;
                break;
            }
            $consulta = "UPDATE usuario ".
                        "SET u_nombre = '".$nombre."' , ".
                        "u_usuario = '".$usuario."'  ".
                        "WHERE u_id_usuario = '".$id_usuario."';";                        
        }

        else{
            //actualizo el nombre
            $consulta = "UPDATE usuario ".
                    "SET u_nombre = '".$nombre."'  ".

                    "WHERE u_id_usuario = '".$id_usuario."';";   
                        
        } 
    }         
    else{
        //El nombre es el mismo
        if($usuario_actual != $usuario){
            $consulta2 = "SELECT count(*)as total FROM usuario WHERE u_usuario = '".$usuario."'";
            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
            if($resultado2) {
                $_fila=mysql_fetch_array($resultado2,MYSQL_BOTH);
                $total = $_fila["total"];
            }
            if($total !=0){
                return 3;
                break;
            }
            $consulta = "UPDATE usuario ".
                    "SET u_usuario = '".$usuario."'  ".
                    "WHERE u_id_usuario = '".$id_usuario."';";                        

        }

    }
    if(!is_null($consulta)){
        $resultado = dbEjecutarConsulta($consulta, $conexion);
        if($resultado) {
            return mysql_affected_rows();
        }
        else {
            return -1;
        }
        mysql_free_result($resultado);
        
    }
    else{
        return 2;
    }   
}
function dbAdminResetearContrasena($id_usuario, $conexion){
    
    $pass = generar_clave_aleatoria();
    $consulta =  "UPDATE usuario ".
                 "SET u_password =  '".$pass."' ".
                 "WHERE u_id_usuario = '".$id_usuario."';";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return $pass;
    }
    else {
        return -1;
    }
    
    mysql_free_result($resultado1);
    mysql_free_result($resultado);

}
function dbAdminEstudianteEstadoExperiencia($id_usuario, $id_experiencia, $conexion){
    /*0: GRUPO AL QUE PERTENECE EL USUARIO*/ 
    /*1: NÚMERO DE MENSAJES PUBLICADOS EN LA BITÁCORA*/
    /*2: NÚMERO DE VALORACIONES*/
    /*3: NÚMERO DE RESPUESTA*/
    $consulta1 = "SELECT g.g_nombre as nombre_grupo ".
                 "FROM usuario_grupo ug, grupo g ".
                 "WHERE g.g_id_experiencia = '".$id_experiencia."' AND ".
                  "ug.ug_id_grupo  = g.g_id_grupo AND ".
                  "ug_id_usuario ='".$id_usuario."' ";
    
   $consulta2 =   "SELECT count(*) AS num_mensajes FROM bt_historial_mensajes, usuario  ".
                 "WHERE u_id_usuario = '".$id_usuario."' ".
                 "AND u_usuario = bthm_usuario ".
                 "AND bthm_id_experiencia ='".$id_experiencia."' ";
    
   $consulta3 =  "SELECT count(*)AS num_megusta FROM bt_megusta_mensaje, bt_historial_mensajes  ".
                 "WHERE bthm_id_experiencia = '".$id_experiencia."' ".
                 "AND btmg_id_mensaje = bthm_id_mensaje ".
                 "AND btmg_id_usuario ='".$id_usuario."' ";
    
   $consulta4 = "SELECT count(*)AS num_respuestas FROM bt_respuesta_mensajes, usuario, bt_historial_mensajes  ".
                 "WHERE u_id_usuario = '".$id_usuario."' ".
                 "AND bthm_id_experiencia ='".$id_experiencia."' ".
                 "AND btrm_id_mensaje_original = bthm_id_mensaje ".
                 "AND u_usuario = btrm_usuario ";
    
    $resultado[0] = dbEjecutarConsulta($consulta1, $conexion);
    $resultado[1] = dbEjecutarConsulta($consulta2, $conexion);
    $resultado[2] = dbEjecutarConsulta($consulta3, $conexion);
    $resultado[3] = dbEjecutarConsulta($consulta4, $conexion);
    
    if($resultado[0]){
        $_fila=mysql_fetch_array($resultado[0],MYSQL_BOTH);
        $_resp["grupo"]           = $_fila["nombre_grupo"];
    }
    else{
        $_resp =null;
    }
    if($resultado[1]){
        $_fila=mysql_fetch_array($resultado[1],MYSQL_BOTH);
        $_resp["num_mensajes"]    = $_fila["num_mensajes"];
    }
    else{
        $_resp =null;
    }
    if($resultado[2]){
        $_fila=mysql_fetch_array($resultado[2],MYSQL_BOTH);
        $_resp["num_megusta"]     = $_fila["num_megusta"];
    }
    else{
        $_resp =null;
    }
    if($resultado[3]){
        $_fila=mysql_fetch_array($resultado[3],MYSQL_BOTH);
        $_resp["num_respuestas"]  = $_fila["num_respuestas"];
    }
    else{
        $_resp =null;
    }
    mysql_free_result($resultado[0]);
    mysql_free_result($resultado[1]);
    mysql_free_result($resultado[2]);
    mysql_free_result($resultado[3]);
    
    return $_resp;    
}
function dbAdminEliminarEstudianteExperiencia($id_usuario,$id_experiencia, $conexion){

    /*ELIMINAR DE LA EXPERIENCIA*/
    $consulta =  "DELETE FROM usuario_experiencia ".
                 "WHERE ue_id_usuario = '".$id_usuario."' ".
                 "AND ue_id_experiencia = '".$id_experiencia."' ";

//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_resp = mysql_affected_rows();
    }
    else {
        $_resp = -1;
    }
    
    return $_resp;
    mysql_free_result($resultado);

}
function dbAdminEliminarEstudianteGrupo($id_usuario,$id_experiencia, $conexion){                 
    /*ELIMINAR  DE GRUPOS DE LA EXPERINCIA*/
    $consulta =  "DELETE FROM usuario_grupo ".
                 "WHERE ug_id_usuario = '".$id_usuario."' ".
                 "AND ug_id_grupo IN ( ".
                 "  SELECT g_id_grupo FROM grupo WHERE g_id_experiencia ='".$id_experiencia."') ";
      
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_resp = mysql_affected_rows();
    }
    else {
        $_resp = -1;
    }
    return $_resp;
    mysql_free_result($resultado);

}
function dbAdminEliminarEstudianteMensajesBitacora($id_usuario,$id_experiencia, $conexion){

    $consulta = "DELETE FROM bt_historial_mensajes ".
                 "WHERE  bthm_id_experiencia ='".$id_experiencia."' ".
                 "AND bthm_usuario IN( ".
                    "SELECT u_usuario FROM usuario ".
                    "WHERE u_id_usuario ='".$id_usuario."')";
                 
//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_resp = mysql_affected_rows();
    }
    else {
        $_resp = -1;
    }
    return $_resp;
    mysql_free_result($resultado);

}
function dbAdminEliminarEstudianteMegusta($id_usuario,$id_experiencia, $conexion){;

    $consulta = "DELETE FROM bt_megusta_mensaje ".
                "WHERE btmg_id_usuario ='".$id_usuario."' ".
                "AND btmg_id_mensaje IN( ".
                    "SELECT bthm_id_mensaje FROM bt_historial_mensajes ".
                    "WHERE bthm_id_experiencia ='".$id_experiencia."')";
   
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_resp = mysql_affected_rows();
    }
    else {
        $_resp = -1;
    }
    return $_resp;
    mysql_free_result($resultado);

}
function dbAdminEliminarEstudianteRespuestas($id_usuario,$id_experiencia, $conexion){
     /*ELIMINAR RESPUESTA A MENSAJES DE LA EXPERIENCIA*/
     $consulta = "DELETE FROM bt_respuesta_mensajes ".
                 "WHERE  btrm_id_mensaje_original IN( ".
                    "SELECT bthm_id_mensaje FROM bt_historial_mensajes ".
                    "WHERE bthm_id_experiencia ='".$id_experiencia."')".
                 "AND btrm_usuario IN( ".
                    "SELECT u_usuario FROM usuario ".
                    "WHERE u_id_usuario ='".$id_usuario."')";
                 
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_resp = mysql_affected_rows();
    }
    else {
        $_resp = -1;
    }
    return $_resp;
    mysql_free_result($resultado);

}
function dbAdminEliminarColaboradorExperiencia($id_usuario,$id_experiencia, $conexion){

    /*ELIMINAR DE LA EXPERIENCIA*/
    $consulta =  "DELETE FROM usuario_experiencia ".
                 "WHERE ue_id_usuario = '".$id_usuario."' ".
                 "AND ue_rol_usuario = 3 ".
                 "AND ue_id_experiencia = '".$id_experiencia."' ";

//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_resp = mysql_affected_rows();
    }
    else {
        $_resp = -1;
    }
    
    return $_resp;
    mysql_free_result($resultado);

}
function dbAdminObtenerPosiblesColaboradores($limite_inf,$id_experencia,$conexion){
    $consulta = "SELECT DISTINCT u_id_usuario,u_usuario, u_nombre, u_email, u_mostrar_email, u_localidad, u_establecimiento, u_url_imagen, u_inscribe_diseno ".
                "FROM usuario ".
                "WHERE u_inscribe_diseno = 1 ".
                " AND u_id_usuario NOT IN( ".
                "   SELECT ue_id_usuario ".
                "   FROM  usuario_experiencia ".
                "   WHERE ue_id_experiencia = '".$id_experencia."' ".
                " ) ".
                "LIMIT ".$limite_inf.",10";


//    echo $consulta;
    
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["muestra_email"]     = $_fila["u_mostrar_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerPosiblesColaboradoresSolicitud($limite_inf,$id_experencia,$conexion){
    $consulta = "SELECT DISTINCT u_id_usuario, u_nombre, u_usuario, u_email, u_mostrar_email, u_localidad, u_establecimiento, u_url_imagen, u_inscribe_diseno ".
                "FROM usuario ".
                "WHERE u_inscribe_diseno = 1 ".
                " AND u_id_usuario NOT IN( ".
                "   SELECT ue_id_usuario ".
                "   FROM  usuario_experiencia, colaborador ".
                "   WHERE ue_id_experiencia = '".$id_experencia."' ".
                "   AND ue_rol_usuario = 3 ".
                "   OR ue_id_usuario = c_id_colaborador ".
                " ) ".
                "LIMIT ".$limite_inf.",10";


//    echo $consulta;
    
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["muestra_email"]     = $_fila["u_mostrar_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerNumeroPosiblesColaboradoresExperiencia($id_experiencia, $conexion) {
    $_resp=null;

    $consulta = "SELECT count(distinct u_id_usuario) as total  ".
                "FROM usuario ".
                "WHERE u_inscribe_diseno = 1 ".
                " AND u_id_usuario NOT IN( ".
                "   SELECT ue_id_usuario ".
                "   FROM  usuario_experiencia ".
                "   WHERE ue_id_experiencia = '".$id_experiencia."' ".
                "   AND ue_rol_usuario = 3 )";

//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp     = $_fila["total"];
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerNumeroPosiblesColaboradoresExperienciaSolicitud($id_experiencia, $conexion) {
    $_resp=null;

    $consulta = "SELECT count(distinct u_id_usuario) as total  ".
                "FROM usuario ".
                "WHERE u_inscribe_diseno = 1 ".
                " AND u_id_usuario NOT IN( ".
                "   SELECT ue_id_usuario ".
                "   FROM  usuario_experiencia, colaborador ".
                "   WHERE ue_id_experiencia = '".$id_experiencia."' ".
                "   AND ue_rol_usuario = 3 ".
                "   OR ue_id_usuario = c_id_colaborador ".
                " )";

//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp     = $_fila["total"];
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminAgregarColaborador($id_usuario,$id_experiencia, $conexion){
    $resp= 0;
    $consulta = "INSERT INTO usuario_experiencia(".
                    "ue_id_usuario, ".
                    "ue_id_experiencia, ".
                    "ue_rol_usuario) ".
                 "VALUES (".
                     "'".$id_usuario."' ,".
                     "'".$id_experiencia."' ,".
                     "3 );";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_affected_rows($conexion) >0) {
            $resp = 1;
            }
        }
    else {
        //ERROR MYSQL
    }
    return $resp;
    
}

function dbAdminEditarInfoExperiencia($id_experiencia, $localidad, $curso, $colegio, $publicado, $estado,$estado_actual, $conexion){
    //actualizar solo los campos que no sean nulos
    $consulta =  "UPDATE ".
                            "experiencia_didactica ".
                 "SET ";

    if(!is_null($localidad) &&  strlen($localidad) > 2){
        $consulta .= "ed_localidad = '".$localidad."' ";
    }
    if(!is_null($curso) &&  strlen($curso) > 0){
        $consulta .= "ed_curso = '".$curso."' ";
    }
    if(!is_null($colegio) &&  strlen($colegio) >0){
        $consulta .= "ed_colegio = '".$colegio."' ";
    }
    if(!is_null($publicado) &&  strlen($publicado) >0){
        $consulta .= "ed_publicado = '".$publicado."' ";
    }
    if(!is_null($estado) &&  strlen($estado) >0){
        /* Estados
         * 1 = No comenzado : fecha inicio y termino = null
         * 2 = En curso: fecha inicio no nula y fecha de termino nula
         * 3 = Fecha de inicio fecha de inicio y termino no nula
         */
        if($estado == 1){
            if($estado_actual == 2){
                $consulta .= "ed_fecha_inicio = null ";
            }
            else{
                if($estado_actual == 3){
                    $consulta .= "ed_fecha_inicio = null, ed_fecha_termino = null ";
                }
            }
            
        }
        if($estado == 2){
            if($estado_actual == 1){
                $consulta .= "ed_fecha_inicio = now() ";
            }
            else{
                if($estado_actual == 3){
                    $consulta .= "ed_fecha_termino = null ";
                }
            }
            
        }
        if($estado == 3){
            if($estado_actual == 1){
                $consulta .= "ed_fecha_inicio = now(), ed_fecha_termino = now() ";
            }else{
                if($estado_actual == 2){
                    $consulta .= "ed_fecha_termino = now() ";
                }
            }
            
        }
    }    
    $consulta .= "WHERE ".
                     "ed_id_experiencia = '".$id_experiencia."';";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);

}
function dbAdminObtenerNumeroUsuariosPlataforma($conexion) {
    $_resp=null;

    $consulta = "SELECT count(*) as total ".
                "FROM usuario U ";

//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp     = $_fila["total"];
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerUsuariosPlataforma($limite_inf, $conexion) {
    $_resp=null;

    $consulta = "SELECT * ".
                "FROM usuario U ".
                "ORDER BY u_id_usuario DESC ".
                "LIMIT ".$limite_inf.", 10 ";                

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"]            = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]               = $_fila["u_usuario"];
                $_resp[$i]["inscribe_diseno"]       = $_fila["u_inscribe_diseno"];
                $_resp[$i]["administrador"]         = $_fila["u_administrador"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["email"]                 = $_fila["u_email"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["fecha_nacimiento"]      = $_fila["u_fecha_nacimiento"];
                $_resp[$i]["localidad"]             = $_fila["u_localidad"];
                $_resp[$i]["establecimiento"]       = $_fila["u_establecimiento"];
                $_resp[$i]["mostrar_correo"]        = $_fila["u_mostrar_email"];
                $_resp[$i]["mostrar_fecha"]         = $_fila["u_mostrar_fecha_nacimiento"];
                $_resp[$i]["activo"]                = $_fila["u_activo"];
                $_resp[$i]["fecha_ultimo_acceso"]   = $_fila["u_ultimo_acceso"];
                $i++;
            }
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}

function dbAdminObtenerNumeroProfesores($conexion) {
    $_resp=null;

    $consulta = "SELECT count(*) as total ".
                "FROM usuario U ".
                "WHERE u_inscribe_diseno =1 ";

//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp           = $_fila["total"];
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerProfesores($limite_inf, $conexion){
    $consulta = "SELECT u_id_usuario,u_usuario, u_nombre, u_email, u_mostrar_email, u_localidad, u_establecimiento,". "
                        u_url_imagen, u_inscribe_diseno, u_activo, u_establecimiento, u_administrador ".
                "FROM usuario ".
                "WHERE u_inscribe_diseno =1 ".
                "ORDER BY u_id_usuario DESC ".
                "LIMIT ".$limite_inf.", 10";
    
    
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["muestra_email"]     = $_fila["u_mostrar_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["inscribe_diseno"]   = $_fila["u_inscribe_diseno"];
                $_resp[$i]["administrador"]     = $_fila["u_administrador"];
                $_resp[$i]["activo"]            = $_fila["u_activo"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerNumeroColaboradores($conexion) {
    
    $_resp = null;
    $consulta = "SELECT count(distinct ue_id_usuario) as total ".
                "FROM usuario_experiencia ".
                "WHERE ue_rol_usuario = 3 ";

//    echo $consulta;
    $resultado= dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp           = $_fila["total"];
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
    }
    return $_resp;

}
function dbAdminObtenerColaboradores($limite_inf, $conexion){
    $consulta = "SELECT distinct(ue_id_usuario),u_usuario, u_nombre, u_email, u_mostrar_email, u_localidad, u_establecimiento,". "
                        u_url_imagen, u_inscribe_diseno, u_activo, u_establecimiento, u_administrador ".
                "FROM usuario, usuario_experiencia ".
                "WHERE ue_rol_usuario = 3 ".
                "AND ue_id_usuario = u_id_usuario ".
                "ORDER BY u_id_usuario DESC ".
                "LIMIT ".$limite_inf.", 10";
    
    
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["ue_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["muestra_email"]     = $_fila["u_mostrar_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["inscribe_diseno"]   = $_fila["u_inscribe_diseno"];
                $_resp[$i]["administrador"]     = $_fila["u_administrador"];
                $_resp[$i]["activo"]            = $_fila["u_activo"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminEliminarUsuarioPlataforma($id_usuario, $conexion){

    /*Borrado en casacada de las tablas: USUARIO_EXPERIENCIA, USUARIO_GRUPO, 
     * BT_MEGUSTA_MENSAJE, MD_MENSAJES, MD_MEGUSTA_MENSAJE, MU_MEGUSTA_MENSAJE, MU_MEGUSTA_MENSAJE*/
    /*Verificar si el usuario es un profesor con experiencias inscritas*/
    $consulta_previa = "SELECT count(*)as total FROM usuario_experiencia ".
                        "WHERE ue_id_usuario ='".$id_usuario."' ".
                        "AND ue_rol_usuario = 1";
  //  echo $consulta_previa;
    $resultado_previo = dbEjecutarConsulta($consulta_previa, $conexion);
    $_fila=mysql_fetch_array($resultado_previo,MYSQL_BOTH);
    $total = $_fila["total"];
    
    if($total>0){ // Poner un usuario como inactivo
        $consulta = "UPDATE usuario ".
                    "SET u_activo = 0 ".
                    "WHERE u_id_usuario = '".$id_usuario."';";
        $resultado = dbEjecutarConsulta($consulta, $conexion);
        if($resultado) {
//            $resp = mysql_affected_rows();
            $resp = 3;
        }
        else {
            $resp = -1;
        }
    }
    else{//Eliminar un usuario
       $consulta1="SELECT u_usuario as usuario FROM usuario ".
                  "WHERE u_id_usuario='".$id_usuario."'";

       $resultado1=dbEjecutarConsulta($consulta1, $conexion);

       $_fila1=mysql_fetch_array($resultado1,MYSQL_BOTH);
       $usuario = $_fila1["usuario"];

       $consulta =  "DELETE FROM usuario ".
                     "WHERE u_id_usuario = '".$id_usuario."' ";

        $resultado = dbEjecutarConsulta($consulta, $conexion);
        if($resultado) {
            $resp = mysql_affected_rows();
        }
        else {
            $resp = -1;
        }
        if($resp > 0){
            $consulta2 = "DELETE FROM bt_historial_mensajes ".
                         "WHERE bthm_usuario = '".$usuario."' ";

            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
            if($resultado2) {
                $resp2 = mysql_affected_rows();
            }
            else {
                $resp2= -1;
            }
            if($resp2 == -1){
                return 2;
            }
        } 
    }
    
    return $resp;
}
function dbAdminFiltroBusquedaUsuario($limite_inf, $nombre, $localidad, $establecimiento, $conexion){
    $consulta = "SELECT DISTINCT u_id_usuario, u_nombre, u_usuario, u_email, u_mostrar_email, u_localidad, u_establecimiento, u_url_imagen, u_inscribe_diseno, u_administrador, u_activo  ".
                "FROM usuario ".
                "WHERE u_nombre like '%".$nombre."%' ";
    
    if(!is_null($localidad)&& strlen($localidad)>3){
       $consulta.=  "AND u_localidad like '%".$localidad."%' ";
    }
    if(!is_null($establecimiento)&& strlen($establecimiento)>3){
        $consulta.= "AND u_establecimiento like '%".$establecimiento."%' ";
    }
        $consulta.= "LIMIT ".$limite_inf.", 10";

    
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["inscribe_diseno"]   = $_fila["u_inscribe_diseno"];
                $_resp[$i]["administrador"]     = $_fila["u_administrador"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["muestra_email"]     = $_fila["u_mostrar_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["activo"]            = $_fila["u_activo"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminObtenerNumeroResultadosBusqueda($nombre, $localidad, $establecimiento, $conexion) {
    $_resp=null;

    $consulta = "SELECT COUNT(DISTINCT u_id_usuario)as resultado ".
                "FROM usuario ".
                "WHERE u_nombre like '%".$nombre."%' ";
    
    if(!is_null($localidad)&& strlen($localidad)>3){
       $consulta.=  "and u_localidad like '%".$localidad."%' ";
    }
    if(!is_null($establecimiento)&& strlen($establecimiento)>3){
        $consulta.= "and u_establecimiento like '%".$establecimiento."%' ";
    }

   /* echo $consulta;*/

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp           = $_fila["resultado"];
        }
        else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminEditarUsuario($id_usuario, $nombre, $usuario, $rol, $conexion){
    
    $consulta1 = "SELECT u_nombre, u_usuario, u_inscribe_diseno, u_administrador FROM usuario WHERE u_id_usuario = ".$id_usuario;
    
    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    if($resultado1) {
        $_fila=mysql_fetch_array($resultado1,MYSQL_BOTH);
        $nombre_actual = $_fila["u_nombre"];
        $usuario_actual = $_fila["u_usuario"];
        $inscribe_diseno_actual = $_fila["u_inscribe_diseno"];
        $administrador_actual = $_fila["u_administrador"];
    }
    mysql_free_result($resultado1);
    // ver que el nombre de usuario no esté ocupado
  
    if($nombre_actual != $nombre ){
        if($usuario_actual != $usuario){
            //hay que actualizar ambios campos (nombre, usuario)
            //ver que el nombre de usuario no esté ocupado
            $consulta2 = "SELECT count(*)as total FROM usuario WHERE u_usuario = '".$usuario."'";
            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
            if($resultado2) {
                $_fila=mysql_fetch_array($resultado2,MYSQL_BOTH);
                $total = $_fila["total"];
            }
            if($total !=0){
                return 3;
                break;
            }
    // ver que el nombre de usuario no esté ocupado
            if($rol == 1){// estudiante
                if($inscribe_diseno_actual !=0){
                    //Poner este valor en cero
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."' , ".
                                "u_usuario = '".$usuario."' , ".
                                "u_inscribe_diseno = 0 ,".
                                "u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."' , ".
                                "u_usuario = '".$usuario."' , ".
                                "u_inscribe_diseno = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";                        
                    }
                }
                else{
                    //ver que no sea un administrador
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."' , ".
                                "u_usuario = '".$usuario."' , ".
                                "u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."' , ".
                                "u_usuario = '".$usuario."'  ".
                                "WHERE u_id_usuario = '".$id_usuario."';";                        
                    }
                }
            }
            if($rol == 2){//profesor
                if($inscribe_diseno_actual == 0){ //cambiar u_inscribe_diseno a 1
                    if($administrador_actual != 0){ //carbiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1, ".
                                    "u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{
                    if($administrador_actual != 0){ //cambiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' , ".
                                    "u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }
            if($rol == 3){//administrador
                if($administrador_actual == 0){ //cambiar a administrador
                    if($inscribe_diseno_actual != 0){ 
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' , ".
                                    "u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1 , ".
                                    "u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{ //el usuario ya tiene rol de administrador
                    if($inscribe_diseno_actual == 0){
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_usuario = '".$usuario."' ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }

        }
        else{
            //hay que actualizar solo el nombre
            if($rol == 1){// estudiante
                if($inscribe_diseno_actual !=0){
                    //Poner este valor en cero
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."' , ".
                                "u_inscribe_diseno = 0 ,".
                                "u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."' , ".
                                "u_inscribe_diseno = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";                        
                    }
                }
                else{
                    //ver que no sea un administrador
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."' , ".
                                "u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                "SET u_nombre = '".$nombre."'  ".
                                "WHERE u_id_usuario = '".$id_usuario."';";                        
                    }
                }
            }
            if($rol == 2){//profesor
                if($inscribe_diseno_actual == 0){ //cambiar u_inscribe_diseno a 1
                    if($administrador_actual != 0){ //carbiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_inscribe_diseno = 1, ".
                                    "u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{
                    if($administrador_actual != 0){ //cambiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."'  ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }
            if($rol == 3){//administrador
                if($administrador_actual == 0){ //cambiar a administrador
                    if($inscribe_diseno_actual != 0){ 
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_inscribe_diseno = 1 , ".
                                    "u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{ //el usuario ya tiene rol de administrador
                    if($inscribe_diseno_actual == 0){
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."' , ".
                                    "u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_nombre = '".$nombre."'  ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }
        }
        
    }
    else{
        if($usuario_actual != $usuario){
            //hay que actualizar ambios campos (nombre, usuario)
            $consulta2 = "SELECT count(*)as total FROM usuario WHERE u_usuario = '".$usuario."'";
            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
            if($resultado2) {
                $_fila=mysql_fetch_array($resultado2,MYSQL_BOTH);
                $total = $_fila["total"];
            }
            if($total !=0){
                return 3;
                break;
            }
            if($rol == 1){// estudiante
                if($inscribe_diseno_actual !=0){
                    //Poner este valor en cero
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_usuario = '".$usuario."' , ".
                                "u_inscribe_diseno = 0 ,".
                                "u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                "SET u_usuario = '".$usuario."' , ".
                                "u_inscribe_diseno = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";                        
                    }
                }
                else{
                    //ver que no sea un administrador
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_usuario = '".$usuario."' , ".
                                "u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                "SET u_usuario = '".$usuario."'  ".
                                "WHERE u_id_usuario = '".$id_usuario."';";                        
                    }
                }
            }
            if($rol == 2){//profesor
                if($inscribe_diseno_actual == 0){ //cambiar u_inscribe_diseno a 1
                    if($administrador_actual != 0){ //carbiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1, ".
                                    "u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{
                    if($administrador_actual != 0){ //cambiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' , ".
                                    "u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }
            if($rol == 3){//administrador
                if($administrador_actual == 0){ //cambiar a administrador
                    if($inscribe_diseno_actual != 0){ 
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' , ".
                                    "u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1 , ".
                                    "u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{ //el usuario ya tiene rol de administrador
                    if($inscribe_diseno_actual == 0){
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' , ".
                                    "u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_usuario = '".$usuario."' ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }

        }
        else{
            if($rol == 1){// estudiante
                if($inscribe_diseno_actual !=0){
                    //Poner este valor en cero
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_inscribe_diseno = 0 ,".
                                "u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                "SET u_inscribe_diseno = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";                        
                    }
                }
                else{
                    //ver que no sea un administrador
                    if($administrador_actual != 0){
                        //poner este valor en cero
                        $consulta = "UPDATE usuario ".
                                "SET u_administrador = 0 ".
                                "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }
            if($rol == 2){//profesor
                if($inscribe_diseno_actual == 0){ //cambiar u_inscribe_diseno a 1
                    if($administrador_actual != 0){ //carbiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_inscribe_diseno = 1, ".
                                    "u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{
                    if($administrador_actual != 0){ //cambiar u_administrador a 0
                        $consulta = "UPDATE usuario ".
                                    "SET u_administrador = 0 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }
            if($rol == 3){//administrador
                if($administrador_actual == 0){ //cambiar a administrador
                    if($inscribe_diseno_actual != 0){ 
                        $consulta = "UPDATE usuario ".
                                    "SET u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                    else{
                        $consulta = "UPDATE usuario ".
                                    "SET u_inscribe_diseno = 1 , ".
                                    "u_administrador = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
                else{ //el usuario ya tiene rol de administrador
                    if($inscribe_diseno_actual == 0){
                        $consulta = "UPDATE usuario ".
                                    "SET u_inscribe_diseno = 1 ".
                                    "WHERE u_id_usuario = '".$id_usuario."';";
                    }
                }
            }
        }
        
        
    }
//    echo $consulta;
    if(!is_null($consulta)){
        $resultado = dbEjecutarConsulta($consulta, $conexion);
        if($resultado) {
            return mysql_affected_rows();
        }
        else {
            return -1;
        }
        mysql_free_result($resultado);
        
    }
    else{
        return 2;
    }   
}
function dbAdminInsertarNuevoUsuario(  $nombre,
                                  $apellido,
                                  $nombre_usuario,
                                  $contrasena,
                                  $fecha_nacimiento,
                                  $correo,
                                  $inscribe_diseno,
                                  $localidad,
                                  $establecimiento,
                                  $conexion) {

    $nombre = $nombre." ".$apellido;
    $activo = 1;
    $consulta = "INSERT INTO usuario(".
                    "u_inscribe_diseno,".
                    "u_nombre,".
                    "u_email,".
                    "u_fecha_nacimiento, ".
                    "u_localidad, ".
                    "u_establecimiento, ".
                    "u_usuario, ".
                    "u_password, ".
                    "u_activo ".
                    ") ".
                 "VALUES (".
                     "'".$inscribe_diseno."', ".
                     "'".$nombre."', ".
                     "'".$correo."', ".
                     "'".$fecha_nacimiento."', ".
                     "'".$localidad."', ".
                     "'".$establecimiento."', ".
                     "'".$nombre_usuario."', ".
                     "'".$contrasena."', ".
                     $activo." ) ; ";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_insert_id();
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
function dbAdminObtenerExperienciasEnCurso($conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "D.dd_nombre, ".
                    "D.dd_id_diseno_didactico, ".
                    "D.dd_nivel, ".
                    "D.dd_subsector, ".
                    "E.ed_id_experiencia, ".
                    "E.ed_localidad, ".
                    "E.ed_curso, ".
                    "E.ed_colegio, ".
                    "E.ed_fecha_inicio, " .
                    "E.ed_fecha_termino, " .
                    "E.ed_fecha_ultima_sesion, " .
                    "E.ed_experiencia_profesor, " .
                    "E.ed_id_profesor, " .
                    "U.u_nombre, " .
                    "U.u_usuario, " .
                    "U.u_url_imagen " .
                "FROM ".
                    "experiencia_didactica E, ".
                    "usuario U, ".
                    "diseno_didactico D ".
                "WHERE ".
                    "D.dd_id_diseno_didactico = E.ed_id_diseno_didactico AND ".
                    "E.ed_fecha_inicio is not null AND ".
                    "E.ed_fecha_termino is null AND ".
                    "U.u_id_usuario = E.ed_id_profesor ";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){

            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["nombre_dd"]         = $_fila["dd_nombre"];
                $_resp[$i]["nivel"]             = $_fila["dd_nivel"];
                $_resp[$i]["subsector"]         = $_fila["dd_subsector"];
                $_resp[$i]["id_experiencia"]    = $_fila["ed_id_experiencia"];
                $_resp[$i]["localidad"]         = $_fila["ed_localidad"];
                $_resp[$i]["curso"]             = $_fila["ed_curso"];
                $_resp[$i]["colegio"]           = $_fila["ed_colegio"];
                $_resp[$i]["usuario_profesor"]  = $_fila["u_usuario"];
                $_resp[$i]["nombre_profesor"]   = $_fila["u_nombre"];
                $_resp[$i]["url_avatar_profesor"]= $_fila["u_url_imagen"];
                $_resp[$i]["fecha_termino"]      = $_fila["ed_fecha_termino"];
                $_resp[$i]["fecha_ultimo_acceso"]= $_fila["ed_fecha_ultima_sesion"];
                
                $i++;
            }
        }
    }
    return $_resp;
}
function dbAdminObtenerInfoUsuario($id_usuario, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "U.u_nombre, ".
                    "U.u_usuario, ".
                    "U.u_email , ".
                    "U.u_inscribe_diseno, ".
                    "U.u_localidad, ".
                    "U.u_establecimiento, ".
                    "U.u_id_usuario, ".
                    "U.u_url_imagen ".
                 "FROM ".
                    "usuario U ".
                 "WHERE ".
                   "U.u_id_usuario  = '".$id_usuario."'";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $_resp["nombre"]            = $_fila["u_nombre"];
        $_resp["inscribe_diseno"]   = $_fila["u_inscribe_diseno"];
        $_resp["email"]             = $_fila["u_email"];
        $_resp["localidad"]         = $_fila["u_localidad"];
        $_resp["establecimiento"]   = $_fila["u_establecimiento"];
        $_resp["imagen"]            = $_fila["u_url_imagen"];
        $_resp["usuario"]           = $_fila["u_usuario"];

        }
        else {
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        $_resp =null;
    }
    return $_resp;
}
function dbAdminVincularUsuarioExperiencia($id_usuario, $id_experiencia, $rol, $conexion){
    $resp=-1;
    $consulta = "INSERT INTO usuario_experiencia (" .
                    "ue_id_usuario, " .
                    "ue_id_experiencia, " .
                    "ue_rol_usuario " .
                    ") " .
                 "VALUES (" .
                    $id_usuario . ", " .
                    $id_experiencia . ", " .
                    $rol.")";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_affected_rows($conexion) > 0) {
            $resp = 1;
        }
    }
    return $resp;
}
function dbAdminBusquedaEstudiante($nombre, $apellido, $id_experiencia,$conexion){
    $consulta = "SELECT u_id_usuario, u_nombre,u_usuario, u_email, u_mostrar_email, u_localidad, u_establecimiento,". "
                        u_url_imagen, u_inscribe_diseno, u_activo, u_establecimiento, u_administrador ".
                "FROM usuario, usuario_experiencia ".
                "WHERE ue_id_experiencia = '".$id_experiencia."' ".
                "AND ue_rol_usuario = 2 ".
                "AND u_id_usuario = ue_id_usuario ".
                "AND  u_nombre like '%".$nombre."%' ";
    if(!is_null($apellido)&& strlen($apellido)){
        $consulta.= "OR  u_apellido like '%".$apellido."%' ";
    }
            
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["muestra_email"]     = $_fila["u_mostrar_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["inscribe_diseno"]   = $_fila["u_inscribe_diseno"];
                $_resp[$i]["administrador"]     = $_fila["u_administrador"];
                $_resp[$i]["activo"]            = $_fila["u_activo"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminBusquedaColaboradores($nombre, $apellido,$id_experiencia,$conexion){
    $consulta = "SELECT u_id_usuario, u_nombre,u_usuario, u_email, u_mostrar_email, u_localidad, u_establecimiento,". "
                        u_url_imagen, u_inscribe_diseno, u_activo, u_establecimiento, u_administrador ".
                "FROM usuario ".
                "WHERE u_inscribe_diseno = 1 ";
    
    if(!is_null($nombre) && strlen($nombre)>3){
        if(!is_null($apellido)&& strlen($apellido)>3){
            $consulta.= "AND  (u_nombre like '%".$nombre."%' OR u_nombre like '%".$apellido."%') ".
                "AND u_id_usuario NOT IN( ".
                "   SELECT ue_id_usuario ".
                "   FROM  usuario_experiencia ".
                "   WHERE ue_id_experiencia = '".$id_experiencia."' ".
                "   AND ue_rol_usuario = 3 ".
                "   UNION  ".
                "   SELECT c_id_colaborador ".
                "   FROM colaborador WHERE c_id_experiencia ='".$id_experiencia."' ".
                "   AND c_accion = 1 ".
                "   AND c_estado = 0 ". 
                ") ";
        }
        else{
            $consulta.= "AND  u_nombre like '%".$nombre."%' ".
                "AND u_id_usuario NOT IN( ".
                "   SELECT ue_id_usuario ".
                "   FROM  usuario_experiencia ".
                "   WHERE ue_id_experiencia = '".$id_experiencia."' ".
                "   AND ue_rol_usuario = 3 ".
                "   UNION  ".
                "   SELECT c_id_colaborador ".
                "   FROM colaborador WHERE c_id_experiencia ='".$id_experiencia."' ".
                "   AND c_accion = 1 ".
                "   AND c_estado = 0 ". 
                ") ";
        }
        
        
    }
    else{
        $consulta.= "AND  u_nombre like '%".$apellido."%' ".
                "AND u_id_usuario NOT IN( ".
                "   SELECT ue_id_usuario ".
                "   FROM  usuario_experiencia ".
                "   WHERE ue_id_experiencia = '".$id_experiencia."' ".
                "   AND ue_rol_usuario = 3 ".
                "   UNION  ".
                "   SELECT c_id_colaborador ".
                "   FROM colaborador WHERE c_id_experiencia ='".$id_experiencia."' ".
                "   AND c_accion = 1 ".
                "   AND c_estado = 0 ". 
                ") ";
    }
                    
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["muestra_email"]     = $_fila["u_mostrar_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["inscribe_diseno"]   = $_fila["u_inscribe_diseno"];
                $_resp[$i]["administrador"]     = $_fila["u_administrador"];
                $_resp[$i]["activo"]            = $_fila["u_activo"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminEliminarExperiencia($id_experiencia, $conexion){

    /*ELIMINAR DE LA EXPERIENCIA*/
    $consulta =  "DELETE FROM experiencia_didactica ".
                 "WHERE ed_id_experiencia = '".$id_experiencia."' ";

  //  echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_resp = mysql_affected_rows();
    }
    else {
        $_resp = -1;
    }
    return $_resp;
    mysql_free_result($resultado);

}

function dbAdminConsultarSolicitudesColaboradoresExperiencia($id_experiencia, $conexion){
    $consulta = "SELECT c_id_colaborador, c_accion, c_estado, u_nombre  ".
                "FROM colaborador, usuario ".  
                "WHERE c_id_experiencia = '".$id_experiencia."' ".
                "AND c_id_colaborador = u_id_usuario ".
                "ORDER BY " .
                "c_id_colaborador_solicitud DESC";
            
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_colaborador"]   = $_fila["c_id_colaborador"];
                $_resp[$i]["accion"]           = $_fila["c_accion"];
                $_resp[$i]["estado"]           = $_fila["c_estado"];
                $_resp[$i]["nombre_colaborador"] = $_fila["u_nombre"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminConsultarSolicitudesColaboradoresPendientes($conexion){
    $consulta = "SELECT c_id_colaborador_solicitud, c_id_colaborador,c_nombre_solicitante, c_id_experiencia, c_accion, c_estado, c_fecha_envio, u_nombre, dd_nombre ".
                "FROM colaborador, usuario, experiencia_didactica, diseno_didactico ".
                "WHERE c_estado = 0 ".
                "AND c_id_colaborador = u_id_usuario ".
                "AND c_id_experiencia = ed_id_experiencia ".
                "AND ed_id_diseno_didactico = dd_id_diseno_didactico";
            
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_solicitud"]          = $_fila["c_id_colaborador_solicitud"];
                $_resp[$i]["id_colaborador"]        = $_fila["c_id_colaborador"];
                $_resp[$i]["nombre_solicitante"]    = $_fila["c_nombre_solicitante"];
                $_resp[$i]["id_experiencia"]        = $_fila["c_id_experiencia"];
                $_resp[$i]["accion"]                = $_fila["c_accion"];
                $_resp[$i]["estado"]                = $_fila["c_estado"];
                $_resp[$i]["fecha_envio"]           = $_fila["c_fecha_envio"];
                $_resp[$i]["nombre_colaborador"]    = $_fila["u_nombre"];
                $_resp[$i]["nombre_dd"]             = $_fila["dd_nombre"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminConsultarNumSolicitudesColaboradoresRespondidas($conexion){
    $consulta = "SELECT count(*) as total ".
                "FROM colaborador ".
                "WHERE c_estado IN (1,2) ";
            
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
            $resp = $_fila["total"];
        }
        else {
            //No existen estudiantes para esta experiencia
            $resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $resp;
}
function dbAdminConsultarSolicitudesColaboradoresRespondidasAgrupadas($limite_inferior,$conexion){
    $consulta = "SELECT c_id_colaborador_solicitud, c_id_colaborador,c_nombre_solicitante, c_id_experiencia, c_accion, c_estado,c_nombre_admin_responde, c_fecha_envio,c_fecha_respuesta, c_nombre_admin_responde, u_nombre, dd_nombre ".
                "FROM colaborador, usuario, experiencia_didactica, diseno_didactico ".
                "WHERE c_estado IN (1,2) ".
                "AND c_id_colaborador = u_id_usuario ".
                "AND c_id_experiencia = ed_id_experiencia ".
                "AND ed_id_diseno_didactico = dd_id_diseno_didactico ".
                "ORDER BY c_fecha_respuesta ASC ".
                "LIMIT ".$limite_inferior.", 10";
               
            
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_solicitud"]          = $_fila["c_id_colaborador_solicitud"];
                $_resp[$i]["id_colaborador"]        = $_fila["c_id_colaborador"];
                $_resp[$i]["nombre_solicitante"]    = $_fila["c_nombre_solicitante"];
                $_resp[$i]["id_experiencia"]        = $_fila["c_id_experiencia"];
                $_resp[$i]["accion"]                = $_fila["c_accion"];
                $_resp[$i]["estado"]                = $_fila["c_estado"];
                $_resp[$i]["fecha_envio"]           = $_fila["c_fecha_envio"];
                $_resp[$i]["fecha_responde"]        = $_fila["c_fecha_responde"];
                $_resp[$i]["nombre_admin_responde"] = $_fila["c_nombre_admin_responde"];
                $_resp[$i]["nombre_colaborador"]    = $_fila["u_nombre"];
                $_resp[$i]["nombre_dd"]             = $_fila["dd_nombre"];
                $i++;
            }
        }
        else {
            //No existen estudiantes para esta experiencia
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbAdminInsertarSolicitudColaborador($id_colaborador, $id_experiencia, $accion,$nombre_solicitante, $conexion){
    $resp=-1;
    $consulta = "INSERT INTO colaborador (" .
                    "c_id_colaborador, " .
                    "c_nombre_solicitante, " .
                    "c_id_experiencia, " .
                    "c_accion, " .
                    "c_fecha_envio ".
                    ") " .
                 "VALUES (" .
                    $id_colaborador. ", " .
                    "'".$nombre_solicitante. "', " .
                    $id_experiencia . ", " .
                    $accion.", ".
                    "now() ".
                    ")";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_affected_rows($conexion) > 0) {
            $resp = 1;
        }
    }
    return $resp;
}
function dbAdminResponderSolicitud($id_solicitud,$estado,$accion,$id_colaborador, $id_experiencia, $nombre_admin,$conexion){
    if($estado ==2){ //Rechazada
        $consulta =  "UPDATE colaborador ".
                     "SET c_estado =  '".$estado."', ".
                     "c_nombre_admin_responde = '".$nombre_admin."', ".
                     "c_fecha_respuesta = now() ".
                     "WHERE c_id_colaborador_solicitud = '".$id_solicitud."';";
     //   echo "consulta estado 2: ".$consulta;
        $resultado = dbEjecutarConsulta($consulta, $conexion);
        if($resultado) {
            return 1;
        }
        else {
            return -1;
        }
    }
    else{ //aceptado
        if($accion == 0){
            $consulta1 = dbAdminEliminarColaboradorExperiencia($id_colaborador, $id_experiencia, $conexion);
        }
        else{
            $consulta1 = dbAdminAgregarColaborador($id_colaborador, $id_experiencia, $conexion);
        }
        if($consulta1 == 1){
            $consulta =  "UPDATE colaborador ".
                         "SET c_estado =  '".$estado."', ".
                         "c_nombre_admin_responde = '".$nombre_admin."', ".
                         "c_fecha_respuesta = now() ".
                         "WHERE c_id_colaborador_solicitud = '".$id_solicitud."';";
//            echo "consulta estado != 2 : ".$consulta;
            $resultado = dbEjecutarConsulta($consulta, $conexion);
            if($resultado) {
                return 1;
            }
            else {
                return -1;
            }
        }
        else{
            return -1;
        }
        
    }
}

/*ADMINISTRAR DISENOS*/  
    function dbAdminObtenerDisenos($agno, $sector, $conexion){
        $fechaMin = $agno.'-01-01 00:00';
        $fechaMax = $agno.'-12-31 23:59';

        $consulta = "SELECT dd_id_diseno_didactico, dd_nombre, dd_nivel, dd_subsector, dd_fecha_creacion, dd_publicado, dd_revision ".
                    "FROM diseno_didactico ".
                    "WHERE ".
                        "dd_subsector = '".$sector."' ".
                        "AND dd_fecha_creacion > '".$fechaMin."' ".
                        "AND dd_fecha_creacion < '".$fechaMax."' ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $_datos=array();
        if($_resultado){
            while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                $_datos[]=$fila;
            }
        }
        return $_datos;  
    }  
    function dbAdminObtenerDisenosAgnoMin($conexion){

        $consulta = "SELECT dd_fecha_creacion ".
                    "FROM diseno_didactico ".
                    "WHERE dd_fecha_creacion <> 'NULL' ".
                    "WHERE dd_fecha_creacion <> '' ".
                    "ORDER BY  dd_fecha_creacion asc ".
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
    function dbAdminPublicarDiseno($id_diseno, $accion, $conexion){
        if($accion == 0){
            $consulta = "SELECT COUNT(ed_id_diseno_didactico) as count_exp ".
                        "FROM experiencia_didactica ".
                        "WHERE ".
                            "ed_id_diseno_didactico = ".$id_diseno." ";
                            "AND ed_fecha_inicio > NOW() ";
                            "AND (ed_fecha_termino = NULL OR ed_fecha_termino = '') ";
            $_resultado = dbEjecutarConsulta($consulta, $conexion);
            $_datos=array();
            if($_resultado){
                while ($fila=mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $_datos[]=$fila;
                }
                if($_datos[0]['count_exp'] >0){
                    return 99;
                }
            }
        }      
        
        //Actualizamos el Diseno
        $consulta = "UPDATE diseno_didactico ".
                    "SET ".
                        "dd_publicado = $accion ,".
                        "dd_revision = 0 ".
                    "WHERE ". 
                        "dd_id_diseno_didactico = $id_diseno ";
        
        $resultado = dbEjecutarConsulta($consulta, $conexion);        
        if($resultado) {
            return 1;
        }
        else {
            return -1;
        }    
    }