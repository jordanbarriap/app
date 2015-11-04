<?php
/**
 * Contiene las funciones para la comunicación con Twitter a través de su API
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Katherine Inalef - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 **/

/**
 * Obtiene los mensajes de la Bitácora según el modo ingreso
 * @author Katherine Inalef - Kelluwen
 * @param resource $conexion Identificador de enlace a MySQL
 * @param Integer $modo
 * @param Integer $id_experiencia
 * @param Integer $id_actividad
 * @param String $etiqueta_gemela
 * @param Integer $id_grupo
 * @param String $etiqueta_grupo_gemelo
 * @param String $usuario
 * @return Array (  id => Integer,
 *                  nombre_usuario => String,
 *                  usuario => String,
 *                  url_imagen => String,
 *                  fecha => Date,
 *                  mensaje => String,
 *                  id_grupo => Integer,
 *                  nombre_grupo => Integer,
 *                  id_actividad => Integer,
 *                  id_exp_actividad => Integer,
 *                  id_experiencia => Integer,
 *                  producto => Integer,
 *                  etiqueta_gemela_ed => String,
 *                  etiqueta_gemela_g => String,
 *                  en_respuesta_a => Integer,
 *                  texto => String,
 *                  usuario_kelluwen => String,
 *                  desde_usuario => Integer,
 *                  url_imagen_perfil => String,
 *                  creado_el => Date)
 */
function dbTimeLine (  $conexion,
                        $modo,
                        $id_experiencia,
                        $id_actividad,
                        $id_exp_actividad,
                        $etiqueta_gemela,
                        $id_grupo,
                        $etiqueta_grupo_gemelo,
                        $producto,
                        $usuario){
    /**Obtener los mensajes de una experiencia (incluye mi clase y la clase gemela)
     * Necesita el id de la experiencia y el id de la actividad, el resto de los campos puede ser nulo
     */
    $_resp = null;
    $consulta_parte_1 = "SELECT ".
                    "bthm.bthm_id_mensaje, ".
                    "bthm.bthm_usuario, ".
                    "bthm.bthm_fecha, ".
                    "bthm.bthm_mensaje, ".
                    "bthm.bthm_id_grupo, ".
                    "bthm.bthm_nombre_grupo, ".
                    "bthm.bthm_id_actividad, ".
                    "bthm.bthm_id_exp_actividad, ".
                    "bthm.bthm_id_experiencia, ".
                    "bthm.bthm_producto, ".
                    "bthm.bthm_etiqueta_gemela_ed, ".
                    "bthm.bthm_etiqueta_gemela_g, ".
                    "bthm.bthm_en_respuesta_a, ".
                    "bthm.bthm_compartido, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM bt_historial_mensajes bthm , usuario u ";
    
    $consulta_parte_2 = "";    
    /** Obtener los mensajes sólo de mi clase
     * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la clase gemela
     */
    if($modo == 0 AND !is_null($id_experiencia) AND !is_null($id_exp_actividad)){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_experiencia = '".$id_experiencia."' AND ".
                            "bthm.bthm_id_exp_actividad = ".$id_exp_actividad." AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ";
    }
    /*Obtener mensajes solo de la clase gemela
    * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la experiencia, el resto de los campos es nulo
    */
    if($modo == 1 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_experiencia = '".$id_experiencia."' AND ".
                            "bthm.bthm_id_actividad    = ".$id_actividad."   AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ";
    }
     /**Obtener los mensajes solo de mi grupo
     * 
     */
    if($modo == 3 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_grupo         = '".$id_grupo."' AND ".
                            "bthm.bthm_id_actividad    = '".$id_actividad."' AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ";
    }
    /**Obtener los mensajes de un usuario en particular
     * Necesita el nombre del usuario, el id de la experiencia y el id de la actividad
     */
    if(!is_null($usuario) AND !is_null($id_exp_actividad)){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_usuario = '".$usuario."' AND ".
                            "bthm.bthm_id_actividad = '".$id_actividad."' AND ".
                            "bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."'    AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ";

    }
    $consulta = $consulta_parte_1.$consulta_parte_2;

   
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id"]                    = $_fila["bthm_id_mensaje"];
                $_resp[$i]["nombre_usuario"]        = $_fila["u_nombre"];
                $_resp[$i]["usuario"]               = $_fila["bthm_usuario"];
                $_resp[$i]["url_imagen"]            = $_fila["bthm_url_imagen"];
                $_resp[$i]["fecha"]                 = $_fila["bthm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["bthm_mensaje"];
                $_resp[$i]["id_grupo"]              = $_fila["bthm_id_grupo"];
                $_resp[$i]["nombre_grupo"]          = $_fila["bthm_nombre_grupo"];
                $_resp[$i]["id_actividad"]          = $_fila["bthm_id_actividad"];
                $_resp[$i]["id_exp_actividad"]      = $_fila["bthm_id_exp_actividad"];
                $_resp[$i]["id_experiencia"]        = $_fila["bthm_id_experiencia"];
                $_resp[$i]["producto"]              = $_fila["bthm_producto"];
                $_resp[$i]["etiqueta_gemela_ed"]    = $_fila["bthm_etiqueta_gemela_ed"];
                $_resp[$i]["etiqueta_gemela_g"]     = $_fila["bthm_etiqueta_gemela_g"];
                $_resp[$i]["en_respuesta_a"]        = $_fila["bthm_en_respuesta_a"];
                $_resp[$i]["texto"]               = $_fila["bthm_mensaje"];
                $_resp[$i]["usuario_kelluwen"]    = $_fila["bthm_nombre"];
                $_resp[$i]["desde_usuario"]       = $_fila["bthm_usuario_tw"];
                $_resp[$i]["compartido"]          = $_fila["bthm_compartido"];
                $_resp[$i]["url_imagen_perfil"]   = $_fila["u_url_imagen"];
                $_resp[$i]["creado_el"]           = $_fila["bthm_fecha"];
                $i++;
            }
        }
    }
    return $_resp;
}
function dbNumMensajesTimeLine (  $conexion,
                        $modo,
                        $id_experiencia,
                        $id_actividad,
                        $id_exp_actividad,
                        $etiqueta_gemela,
                        $id_grupo,
                        $etiqueta_grupo_gemelo,
                        $producto,
                        $usuario){
    /**Obtener los mensajes de una experiencia (incluye mi clase y la clase gemela)
     * Necesita el id de la experiencia y el id de la actividad, el resto de los campos puede ser nulo
     */
    $resp   = -1;
    $consulta_parte_1 = "SELECT count(*) as total ".
                        "FROM bt_historial_mensajes bthm  ";
    
    $consulta_parte_2 = "";    
    /** Obtener los mensajes sólo de mi clase
     * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la clase gemela
     */
    if($modo == 0 AND !is_null($id_experiencia) AND !is_null($id_exp_actividad)){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_experiencia = '".$id_experiencia."' AND ".
                            "bthm.bthm_id_exp_actividad = ".$id_exp_actividad." ";
    }
    /*Obtener mensajes solo de la clase gemela
    * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la experiencia, el resto de los campos es nulo
    */
    if($modo == 1 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_experiencia = '".$id_experiencia."' AND ".
                            "bthm.bthm_id_actividad    = ".$id_actividad."    ";

    }
     /**Obtener los mensajes solo de mi grupo
     * 
     */
    if($modo == 3 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_grupo         = '".$id_grupo."' AND ".
                            "bthm.bthm_id_actividad    = '".$id_actividad."'  ";
    }
    /**Obtener los mensajes de un usuario en particular
     * Necesita el nombre del usuario, el id de la experiencia y el id de la actividad
     */
    if(!is_null($usuario) AND !is_null($id_exp_actividad)){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_usuario = '".$usuario."' AND ".
                            "bthm.bthm_id_actividad = '".$id_actividad."' AND ".
                            "bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."'   ";

    }
    $consulta = $consulta_parte_1.$consulta_parte_2;
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
        $resp= $_fila["total"];
    }
        
    return $resp;
}
function dbTimeLineBloque ( $conexion,
                            $limite_inferior,
                            $modo,
                            $id_experiencia,
                            $id_actividad,
                            $id_exp_actividad,
                            $etiqueta_gemela,
                            $id_grupo,
                            $etiqueta_grupo_gemelo,
                            $producto,
                            $usuario){
    /**Obtener los mensajes de una experiencia (incluye mi clase y la clase gemela)
     * Necesita el id de la experiencia y el id de la actividad, el resto de los campos puede ser nulo
     */
    $_resp = null;
    $consulta_parte_1 = "SELECT ".
                    "bthm.bthm_id_mensaje, ".
                    "bthm.bthm_usuario, ".
                    "bthm.bthm_fecha, ".
                    "bthm.bthm_mensaje, ".
                    "bthm.bthm_id_grupo, ".
                    "bthm.bthm_nombre_grupo, ".
                    "bthm.bthm_id_actividad, ".
                    "bthm.bthm_id_exp_actividad, ".
                    "bthm.bthm_id_experiencia, ".
                    "bthm.bthm_producto, ".
                    "bthm.bthm_etiqueta_gemela_ed, ".
                    "bthm.bthm_etiqueta_gemela_g, ".
                    "bthm.bthm_en_respuesta_a, ".
                    "bthm.bthm_compartido, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM bt_historial_mensajes bthm , usuario u ";
    
    $consulta_parte_2 = "";    
    /** Obtener los mensajes sólo de mi clase
     * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la clase gemela
     */
    if($modo == 0 AND !is_null($id_experiencia) AND !is_null($id_exp_actividad)){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_experiencia = '".$id_experiencia."' AND ".
                            "bthm.bthm_id_exp_actividad = ".$id_exp_actividad." AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ".
                            "LIMIT ".$limite_inferior.", 20 ";
    }
    /*Obtener mensajes solo de la clase gemela
    * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la experiencia, el resto de los campos es nulo
    */
    if($modo == 1 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_experiencia = '".$id_experiencia."' AND ".
                            "bthm.bthm_id_actividad    = ".$id_actividad."   AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ".
                            "LIMIT ".$limite_inferior.", 20 ";
    }
     /**Obtener los mensajes solo de mi grupo
     * 
     */
    if($modo == 3 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_grupo         = '".$id_grupo."' AND ".
                            "bthm.bthm_id_actividad    = '".$id_actividad."' AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ".
                            "LIMIT ".$limite_inferior.", 20 ";
    }
    /**Obtener los mensajes de un usuario en particular
     * Necesita el nombre del usuario, el id de la experiencia y el id de la actividad
     */
    if(!is_null($usuario) AND !is_null($id_exp_actividad)){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_usuario = '".$usuario."' AND ".
                            "bthm.bthm_id_actividad = '".$id_actividad."' AND ".
                            "bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."'    AND ".
                            "bthm.bthm_usuario = u.u_usuario  ".
                            "ORDER BY bthm.bthm_fecha DESC ".
                            "LIMIT ".$limite_inferior.", 20 ";

    }
    $consulta = $consulta_parte_1.$consulta_parte_2;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id"]                    = $_fila["bthm_id_mensaje"];
                $_resp[$i]["nombre_usuario"]        = $_fila["u_nombre"];
                $_resp[$i]["usuario"]               = $_fila["bthm_usuario"];
                $_resp[$i]["url_imagen"]            = $_fila["bthm_url_imagen"];
                $_resp[$i]["fecha"]                 = $_fila["bthm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["bthm_mensaje"];
                $_resp[$i]["id_grupo"]              = $_fila["bthm_id_grupo"];
                $_resp[$i]["nombre_grupo"]          = $_fila["bthm_nombre_grupo"];
                $_resp[$i]["id_actividad"]          = $_fila["bthm_id_actividad"];
                $_resp[$i]["id_exp_actividad"]      = $_fila["bthm_id_exp_actividad"];
                $_resp[$i]["id_experiencia"]        = $_fila["bthm_id_experiencia"];
                $_resp[$i]["producto"]              = $_fila["bthm_producto"];
                $_resp[$i]["etiqueta_gemela_ed"]    = $_fila["bthm_etiqueta_gemela_ed"];
                $_resp[$i]["etiqueta_gemela_g"]     = $_fila["bthm_etiqueta_gemela_g"];
                $_resp[$i]["en_respuesta_a"]        = $_fila["bthm_en_respuesta_a"];
                $_resp[$i]["texto"]               = $_fila["bthm_mensaje"];
                $_resp[$i]["usuario_kelluwen"]    = $_fila["bthm_nombre"];
                $_resp[$i]["desde_usuario"]       = $_fila["bthm_usuario_tw"];
                $_resp[$i]["compartido"]          = $_fila["bthm_compartido"];
                $_resp[$i]["url_imagen_perfil"]   = $_fila["u_url_imagen"];
                $_resp[$i]["creado_el"]           = $_fila["bthm_fecha"];
                $i++;
            }
        }
    }
    return $_resp;
}
/**
 * Retorna el numero de mensajes sin leer asociados a la actividad que se esta ejecutando
 * @author Katherine Inalef - Kelluwen
 * @param resource $conexion Identificador de enlace a MySQL
 * @param Integer $modo
 * @param Integer $id_mensaje
 * @param Integer $id_experiencia
 * @param Integer $id_actividad
 * @param Integer $id_exp_actividad
 * @param String $etiqueta_gemela
 * @param Integer $id_grupo
 * @param String $etiqueta_grupo_gemelo
 * @param Integer $producto
 * @param String $usuario
 * @param Integer $id_clase_gemela
 * @return Integer
 */
function dbTimeLineMensajesNuevos (     $conexion,
                                        $modo,
                                        $id_mensaje,
                                        $id_experiencia,
                                        $id_actividad,
                                        $id_exp_actividad,
                                        $etiqueta_gemela,
                                        $id_grupo,
                                        $etiqueta_grupo_gemelo,
                                        $producto,
                                        $usuario,
                                        $id_clase_gemela){
    /**Obtener los mensajes de una experiencia (incluye mi clase y la clase gemela)
     * Necesita el id de la experiencia y el id de la actividad, el resto de los campos puede ser nulo
     */
    $consulta_parte_1 = "SELECT COUNT(*) AS total ".
                    "FROM bt_historial_mensajes bthm ";

    $consulta_parte_2 = "";
    if (is_null($id_mensaje)){
        $id_mensaje = -1;
    }

    /*Obtener mensajes solo de la clase gemela
    * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la experiencia, el resto de los campos es nulo
    */
    if($modo == 1 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_mensaje > ".$id_mensaje." AND ".
                            "bthm.bthm_id_actividad    = ".$id_actividad."  AND ".
                            "bthm.bthm_id_clase_gemela     = ".$id_clase_gemela." ";
    }
    /** Obtener los mensajes de una experiencia (solo los de mi clase)
     * Necesita el id de la experiencia, el id de la actividad y la etiqueta de la clase gemela
     */
    if($modo == 0 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_mensaje > ".$id_mensaje." AND ".
                            "bthm.bthm_id_experiencia = '".$id_experiencia."' AND ".
                            "bthm.bthm_id_exp_actividad = ".$id_exp_actividad."  ";
    }
     /**Obtener los mensajes solo de mi grupo
     * Necesita
     */
    if($modo == 3 ){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_mensaje > '".$id_mensaje."' AND ".
                            "bthm.bthm_id_grupo         = '".$id_grupo."' AND ".
                            "bthm.bthm_id_actividad    = '".$id_actividad."'  ";
    }
    /**Obtener los mensajes de un usuario en particular
     * Necesita el nombre del usuario, el id de la experiencia y el id de la actividad
     */
    if(!is_null($usuario)){
        $consulta_parte_2 = "WHERE ".
                            "bthm.bthm_id_mensaje > '".$id_mensaje."' AND ".
                            "bthm.bthm_usuario = '".$usuario."' AND ".
                            "bthm.bthm_id_actividad = '".$id_actividad."' AND ".
                            "bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."'     ";
    }
    $consulta = $consulta_parte_1.$consulta_parte_2;
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $resp             = $_fila["total"];
            }
        }
    }
    return $resp;
}



function dbTimeLineCompartido (  $conexion,
                                $limite_inferior,
                                $modo,
                                $id_experiencia,
                                $etiqueta_gemela,
                                $usuario){
   
    /* CONSIDERAR EL ORDEN EN LA PETICIÓN
     */
    $consulta_previa =  "SELECT ed_id_diseno_didactico ".
                        "FROM experiencia_didactica ".
                        "WHERE `ed_id_experiencia` ='".$id_experiencia."' "; 
    $resultado = dbEjecutarConsulta($consulta_previa, $conexion);
    if($resultado) {
        $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
        $id_diseno = $_fila["ed_id_diseno_didactico"];
    }
    $_resp = null;
    $_resp["id_diseno"] =$id_diseno;
    $consulta_parte1 = "SELECT ".
                    "bthm.bthm_id_mensaje, ".
                    "bthm.bthm_usuario, ".
                    "bthm.bthm_fecha, ".
                    "bthm.bthm_mensaje, ".
                    "bthm.bthm_id_grupo, ".
                    "bthm.bthm_nombre_grupo, ".
                    "bthm.bthm_id_actividad, ".
                    "bthm.bthm_id_exp_actividad, ".
                    "bthm.bthm_id_experiencia, ".
                    "bthm.bthm_producto, ".
                    "bthm.bthm_etiqueta_gemela_ed, ".
                    "bthm.bthm_etiqueta_gemela_g, ".
                    "bthm.bthm_en_respuesta_a, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen, ".
                    "ed.ed_localidad, ".
                    "ed.ed_curso, ".
                    "ed.ed_colegio ".
                    "FROM bt_historial_mensajes bthm , usuario u, experiencia_didactica ed ".
                    "WHERE u_usuario = bthm_usuario ".
                    "AND bthm_id_experiencia = ed.ed_id_experiencia ".
                    "AND bthm_compartido = 1 ";
                    
                    
    $consulta_parte2= ""  ;
    if($modo == 0){//Todos
        $consulta_parte2=    "AND bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."' ".
                            "ORDER BY bthm.bthm_fecha DESC ".
                            "LIMIT ".$limite_inferior.", 20 ";
    }
    else{
        if($modo == 1){//Mensajes de mi clase
            $consulta_parte2 =  "AND bthm_id_experiencia = '".$id_experiencia."' ".
                                "ORDER BY bthm.bthm_fecha DESC ".
                                "LIMIT ".$limite_inferior.", 20 ";
        }
        else{
            if($modo == 2){//mis mensajes
                $consulta_parte2=  "AND bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."' ". 
                                    "AND bthm_usuario ='".$usuario."' ".
                                    "ORDER BY bthm.bthm_fecha DESC ".
                                    "LIMIT ".$limite_inferior.", 20 ";
            }
        }
    }
    
    
    $consulta = $consulta_parte1.$consulta_parte2;
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id"]                    = $_fila["bthm_id_mensaje"];
                $_resp[$i]["nombre_usuario"]        = $_fila["u_nombre"];
                $_resp[$i]["usuario"]               = $_fila["bthm_usuario"];
                $_resp[$i]["url_imagen"]            = $_fila["bthm_url_imagen"];
                $_resp[$i]["fecha"]                 = $_fila["bthm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["bthm_mensaje"];
                $_resp[$i]["id_grupo"]              = $_fila["bthm_id_grupo"];
                $_resp[$i]["nombre_grupo"]          = $_fila["bthm_nombre_grupo"];
                $_resp[$i]["id_actividad"]          = $_fila["bthm_id_actividad"];
                $_resp[$i]["id_exp_actividad"]      = $_fila["bthm_id_exp_actividad"];
                $_resp[$i]["id_experiencia"]        = $_fila["bthm_id_experiencia"];
                $_resp[$i]["etiqueta_gemela_ed"]    = $_fila["bthm_etiqueta_gemela_ed"];
                $_resp[$i]["etiqueta_gemela_g"]     = $_fila["bthm_etiqueta_gemela_g"];
                $_resp[$i]["texto"]               = $_fila["bthm_mensaje"];
                $_resp[$i]["usuario_kelluwen"]    = $_fila["bthm_nombre"];
                $_resp[$i]["url_imagen_perfil"]   = $_fila["u_url_imagen"];
                $_resp[$i]["creado_el"]           = $_fila["bthm_fecha"];
                $_resp[$i]["localidad"]           = $_fila["ed_localidad"];
                $_resp[$i]["curso"]               = $_fila["ed_curso"];
                $_resp[$i]["colegio"]             = $_fila["ed_colegio"];
                
                $i++;
            }
        }
    }
    return $_resp;
}
function dbNumMensajesTimeLineCompartido (  $conexion,
                                            $modo,
                                            $id_experiencia,
                                            $etiqueta_gemela,
                                            $usuario){
   
    $resp = -1;
    $consulta_parte1 = "SELECT count(*) as total ".
                        "FROM bt_historial_mensajes bthm ".
                        "WHERE bthm_compartido = 1 ";
                    
    $consulta_parte2= ""  ;
    if($modo == 0){//Todos
        $consulta_parte2=    "AND bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."' ";
    }
    else{
        if($modo == 1){//Mensajes de mi clase
            $consulta_parte2 =  "AND bthm_id_experiencia = '".$id_experiencia."' ";
        }
        else{
            if($modo == 2){//mis mensajes
                $consulta_parte2=  "AND bthm.bthm_etiqueta_gemela_ed = '".$etiqueta_gemela."' ". 
                                    "AND bthm_usuario ='".$usuario."' ";
            }
        }
    }
    $consulta = $consulta_parte1.$consulta_parte2;
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
        $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
        $resp = $_fila["total"];

        }
    }
    return $resp;
}
function dbTimeLineCompartidaMensajesNuevos (   $conexion,
                                                $modo,
                                                $id_mensaje,
                                                $id_experiencia,
                                                $usuario,
                                                $id_diseno){

    $consulta_parte_1 = "SELECT COUNT(*) AS total ".
                    "FROM bt_historial_mensajes bthm ".
                    "WHERE bthm_compartido = 1 ".
                     "AND bthm.bthm_id_mensaje > '".$id_mensaje."'  ";

    $consulta_parte_2 = "";
    if (is_null($id_mensaje)){
        $id_mensaje = -1;
    }
    if($modo == 0 ){
        $consulta_parte_2 = "AND bthm_id_experiencia IN( ".
                            "   SELECT ed_id_experiencia ".
                            "   FROM experiencia_didactica ".
                            "   WHERE ed_id_diseno_didactico = '".$id_diseno."' ".
                            "   AND ed_fecha_termino IS NULL ".
                            " ) ";
    }
    if($modo == 1 ){
        $consulta_parte_2 =  "AND bthm_id_experiencia = '".$id_experiencia."' ";
    }
    if($modo == 2){
        $consulta_parte_2 = "AND bthm_usuario ='".$usuario."' ".
                            "AND bthm_id_experiencia IN( ".
                                    "   SELECT ed_id_experiencia ".
                                    "   FROM experiencia_didactica ".
                                    "   WHERE ed_id_diseno_didactico = '".$id_diseno."' ".
                                    "   AND ed_fecha_termino IS NULL ".
                                    " ) ";
                                    
    }
    $consulta = $consulta_parte_1.$consulta_parte_2;
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $resp             = $_fila["total"];
            }
        }
    }
    return $resp;
}
?>
