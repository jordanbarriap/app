<?php
/**
 * Contiene las funciones de manejo de la Base de Datos
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Cristian Miranda - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 **/


/*
 * Obtiene la recomendacion y el valor de la evaluacion ingresada al finalizar
 * una actividad anteriormente.
 * En caso que exista dicha recomendación y evaluación, los retorna.
 * En caso que no existan (es decir, no se haya finalizado anteriormente la
 * actividad), se retorna null.
 *
 * @param Integer   $id_actividad       Identificador de actividad
 * @param Integer   $id_experiencia     Identificador de experiencia
 * @param Integer   $id_usuario         Identificador de usuario
 * @param resource  $conexion           Identificador de enlace a MySQL
 * @return Array    (rec_mensaje    => String,
 *                  rec_evaluacion  => String)
 *         o
 *         Integer  null;
 */
function dbRECBuscaRecomendacionAnterior($id_actividad, $id_experiencia, $id_usuario, $conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "REVACT.rec_mensaje, ".
                    "REVACT.rec_evaluacion ".
                 "FROM ".
                    " rec_evaluacion_actividad REVACT ".
                 "WHERE ".
                    "REVACT.rec_id_usuario = '".$id_usuario."' AND ".
                    "REVACT.rec_id_experiencia = '".$id_experiencia."' AND ".
                    "REVACT.rec_id_actividad = '".$id_actividad. "';";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
            $_resp[$i]["mensaje"]           = $_fila["rec_mensaje"];
            $_resp[$i]["evaluacion"]        = $_fila["rec_evaluacion"];
            $i++;
        }
    }
    return $_resp;
}


/*
 * Actualiza los registros de las tablas md_mensajes y rec_evaluacion_actividad
 * en caso que se ingrese una recomendacion y evaluacion de actividad por segunda
 * o mas veces, es decir, cuando se finaliza una actividad dos o más veces.
 * Los campos actualizados son:
 *      - Campo evaluacion (Muy Bien, Bien, Mal, Muy Mal)
 *      - Campo mensaje (texto recomendacion)
 *      - Fecha
 *
 * @param Integer   $id_actividad       Identificador de actividad
 * @param Integer   $id_experiencia     Identificador de experiencia
 * @param Integer   $id_usuario         Identificador de usuario
 * @param String    $evaluacion         Evaluacion de actividad (Muy Bien, Bien, Mal, Muy Mal)
 * @param String    $mensaje            Mensaje (recomendacion)
 * @param resource  $conexion           Identificador de enlace a MySQL
 */
function dbRECActualizaRecomendacionFinal($id_actividad, $id_experiencia, $id_usuario, $evaluacion, $mensaje, $conexion){
    $resp = null;
    $consulta1 = "UPDATE ".
                    " rec_evaluacion_actividad ".
                 "SET ".
                     "  rec_evaluacion = '".$evaluacion."' ,".
                     "  rec_mensaje = '".$mensaje."' ,".
                     "  rec_fecha = now() ".
                 "WHERE ".
                    "rec_id_actividad = ".$id_actividad." AND ".
                    "rec_id_experiencia = ".$id_experiencia." AND ".
                    "rec_id_usuario = ".$id_usuario;

    $consulta2 = "UPDATE ".
                    " md_mensajes ".
                 "SET ".
                     "  mdmj_mensaje = '".$mensaje."' ,".
                     "  mdmj_fecha = now() ".
                 "WHERE ".
                    "mdmj_id_actividad = ".$id_actividad." AND ".
                    "mdmj_id_experiencia = ".$id_experiencia." AND ".
                    "mdmj_id_usuario = ".$id_usuario." AND ".
                    "mdmj_tipo_mensaje = 6";

    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
    if($resultado1 && $resultado2) {
        $resp = 1;
    }
    else {
        $resp = -1;
    }
    return $resp;
}


/*
 * Inserta la evaluacion ingresada al finalizar una actividad en la tabla
 * rec_evaluacion_actividad.

 * @param Integer   $id_actividad       Identificador de actividad
 * @param Integer   $id_experiencia     Identificador de experiencia
 * @param Integer   $id_usuario         Identificador de usuario
 * @param Integer   $id_mensaje         Identificador de mensaje (recomendacion)
 * @param Integer   $id_diseno          Identificador de diseño
 * @param Integer   $id_exp_act         Identificador de experiencia-actividad
 * @param String    $evaluacion         Evaluacion de actividad (Muy Bien, Bien, Mal, Muy Mal)
 * @param String    $mensaje            Mensaje (recomendacion)
 * @param resource  $conexion           Identificador de enlace a MySQL
 * @return integer
 */
function dbRECInsertarEvaluacionActividad(  $id_actividad,
                                            $id_experiencia,
                                            $id_usuario,
                                            $id_mensaje,
                                            $id_diseno,
                                            $id_exp_act,
                                            $evaluacion,
                                            $mensaje,
                                            $conexion) {
    $resp = null;
    $consulta = "INSERT INTO rec_evaluacion_actividad(".
                    "rec_id_actividad, ".
                    "rec_id_experiencia, ".
                    "rec_id_usuario, ".
                    "rec_id_mensaje, ".
                    "rec_id_diseno, ".
                    "rec_id_exp_act, ".
                    "rec_evaluacion, ".
                    "rec_mensaje, ".
                    "rec_fecha ".
                    ") ".
                 "VALUES (".
                            $id_actividad.", ".
                            $id_experiencia.", ".
                            $id_usuario.", ".
                            $id_mensaje.", ".
                            $id_diseno.", ".
                            $id_exp_act.", ".
                            "'".$evaluacion."', ".
                            "'".$mensaje."', ".
                            "now() ) ; ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $resp = 1;
    }
    else {
        $resp = -1;
    }
    return $resp;
}

/*
 * Obtiene el nombre de la actividad que se finaliza, para desplegar en el muro del diseño
 * (kellumuro) la recomendación de la forma:
 * "XXX dejó la siguiente recomendación al finalizar la actividad YYY: (recomendación)"
 * donde XXX es el nombre del usuario, YYY es el nombre de la actividad.
 * Esto para asociar la recomendación dejada con la actividad finalizada.
 *
 * @param       Integer     $id_actividad       Identificador de actividad
 * @param       resource    $conexion           Identificador de enlace a MySQL
 * @return      String      $nombre_actividad   Nombre de actividad 
 */
 
function dbRECObtenerNombreAct($id_actividad,$conexion){
    $nombre_actividad= null;
    $consulta = "SELECT ".
                "AC.ac_nombre ".
            "FROM ".
                " actividad AC ".
            "WHERE ".
                "AC.ac_id_actividad = ".$id_actividad."  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $nombre_actividad = $_fila["ac_nombre"];
       
    }
    return $nombre_actividad;
}

/*
 * Selecciona todas los mensajes ingresados al finalizar las actividades y los comentarios
 * realizados sobre las actividades para desplegar como recomendaciones.
 * mdmj_tipo_mensaje = '6' son las recomendaciones ingresadas al finalizar una actividad.
 * mdmj_tipo_mensaje = '7' son los comentarios ingresados en cada actividad por medio del icono.
 * Se hace este filtro (tipos) porque los comentarios realizados en el muro del usuario
 * tambien se almacenan en la tabla del muro del diseño, pero con tipo=1.
 * Asimismo las notificaciones de comienzo y fin de actividades y diseños (tipos 2,3,4 y 5)
 * Existe solo un comentario tipo 6 para cada actividad.
 *
 * @param       Integer     $id_actividad       Identificador de actividad
 * @param       resource    $conexion           Identificador de enlace a MySQL
 * @return      Array (  id_mensaje         => Integer,
 *                       id_diseno          => Integer,
 *                       id_espriencia      => Integer,
 *                       id_usuario         => Integer,
 *                       fecha              => Date,
 *                       mensaje            => String,
 *                       tipo               => Integer,
 *                       id_mensaje_mu      => Integer,
 *                       id_actividad       => Integer
 *                       nombre             => String,
 *                       imagen             => String,
 *                       usuario            => String )
 
function dbRECObtenerRecomendaciones($id_actividad, $conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "MDM.mdmj_id_mensaje, ".
                    "MDM.mdmj_id_diseno, ".
                    "MDM.mdmj_id_experiencia, ".
                    "MDM.mdmj_id_usuario, ".
                    "MDM.mdmj_fecha, ".
                    "MDM.mdmj_mensaje, ".
                    "MDM.mdmj_tipo_mensaje, ".
                    "MDM.mdmj_id_mensaje_mu, ".
                    "MDM.mdmj_id_actividad, ".
                    "U.u_nombre, ".
                    "U.u_url_imagen, ".
                    "U.u_usuario ".
                "FROM ".
                    "md_mensajes MDM, usuario U ".
                "WHERE ".
                    "(MDM.mdmj_tipo_mensaje = '6' OR
                      MDM.mdmj_tipo_mensaje = '7') AND  ".
                     "MDM.mdmj_id_usuario = U.u_id_usuario AND  ".
                     "MDM.mdmj_id_actividad =".$id_actividad.";";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]            = $_fila["mdmj_id_mensaje"];
                $_resp[$i]["id_diseno"]             = $_fila["mdmj_id_diseno"];
                $_resp[$i]["id_experiencia"]        = $_fila["mdmj_id_experiencia"];
                $_resp[$i]["id_usuario"]            = $_fila["mdmj_id_usuario"];
                $_resp[$i]["fecha"]                 = $_fila["mdmj_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["mdmj_mensaje"];
                $_resp[$i]["tipo"]                  = $_fila["mdmj_tipo_mensaje"];
                $_resp[$i]["id_mensaje_mu"]         = $_fila["mdmj_id_mensaje_mu"];
                $_resp[$i]["id_actividad"]          = $_fila["mdmj_id_actividad"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["imagen"]                = $_fila["u_url_imagen"];
                $_resp[$i]["usuario"]               = $_fila["u_usuario"];
                $i++;
            }
        }
    }
    return $_resp;
}
*/


/*
 * Función que obtiene el numero de mensajes valorados por el usuario de sesion.
 *
 * @param       Integer     $id_usuario_valora  Identificador de usuario que valora mensaje
 * @param       Integer     $id_mensaje         Identificador de mensaje
 * @param       resource    $conexion           Identificador de enlace a MySQL
 * @return      Integer
 * 
 */
function dbRECObtenerMeGustaMensaje($id_usuario_valora, $id_mensaje, $conexion){
    $resp = null;
    $consulta=      "SELECT count(*) as cont ".
                    "FROM rec_megusta_mensaje recmg ".
                    "WHERE ".
                    "recmg.rec_mg_id_mensaje = '".$id_mensaje."' AND  ".
                    "recmg.rec_mg_id_usuario_valora =  '".$id_usuario_valora."' ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp               = $_fila["cont"];
             }
        }
    }
    else {
        $resp = -1;
    }
    return $resp;
   
}


/*
 * Inserta en la tabla rec_megusta_mensaje los datos pasados como parametros
 * al momento que se presiona Me gusta sobre una recomendacion.
 *
 * @param       Integer     $id_mensaje         Identificador de mensaje
 * @param       Integer     $id_usuario_valora  Identificador de usuario que valora mensaje
 * @param       Integer     $id_usuario_autor   Identificador de usuario autor del mensaje
 * @param       resource    $conexion           Identificador de enlace a MySQL
 * 
 */
function dbRECInsertarMeGustaMensaje(  $id_mensaje,
                                       $id_usuario_valora,
                                       $id_usuario_autor,
                                       $conexion) {
    $resp = null;
    $consulta = "INSERT INTO rec_megusta_mensaje (".
                    "rec_mg_id_mensaje, ".
                    "rec_mg_id_usuario_valora, ".
                    "rec_mg_id_usuario_autor ".
                    ") ".
                 "VALUES (".
                     $id_mensaje.", ".
                     $id_usuario_valora.", ".
                     $id_usuario_autor." ) ; ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $resp = 1;
    }
    else {
        $resp = -1;
    }
    return $resp;
}


/*
 * Elimina la tabla rec_megusta_mensaje el registro al momento que se presionar
 * Ya no me gusta sobre una recomendacion.
 *
 * @param       Integer     $id_mensaje             Identificador de mensaje
 * @param       Integer     $id_usuario_valora      Identificador de usuario que valora mensaje
 * @param       resource    $conexion               Identificador de enlace a MySQL
 *
 */
function dbRECEliminarMeGustaMensaje (  $id_mensaje,
                                        $id_usuario_valora,
                                        $conexion){
    $resp = null;
    $consulta = "DELETE FROM ".
                 " rec_megusta_mensaje ".
                 "WHERE ".
                    "rec_mg_id_mensaje = ".$id_mensaje." AND ".
                    "rec_mg_id_usuario_valora = ".$id_usuario_valora.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $resp = 1;
    }
    else {
        $resp = -1;
    }
    return $resp;
}


/*
 * Obtiene todos los usuarios que valoran una recomendacion para desplegar el
 * listado con todos sus datos al presionar en el icono del dedo pulgar.
 *
 * @param       Integer     $id_mensaje       Identificador de mensaje
 * @param       resource    $conexion         Identificador de enlace a MySQL
 * @return      Array(  nombre             => String,
 *                      usuario            => String,
 *                      url_imagen         => String,
 *                      establecimiento    => String,
 *                      localidad          => String)
 */
function dbRECObtenerUsuariosGustaMensaje($id_mensaje, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "recmg.rec_mg_id_usuario_valora, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen, ".
                    "u.u_establecimiento, ".
                    "u.u_localidad, ".
                    "u.u_usuario ".
                    "FROM rec_megusta_mensaje recmg, usuario u ".
                    "WHERE ".
                    "recmg.rec_mg_id_mensaje = '".$id_mensaje."' AND  ".
                    "u.u_id_usuario =  recmg.rec_mg_id_usuario_valora ;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["url_imagen"]        = $_fila["u_url_imagen"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $i++;
            }
        }
    }
    return $_resp;
}


/*
 * Función que obtiene el ID del usuario autor de una recomendacion
 * en la tabla md_mensajes para almacenarlo junto al ID del usuario
 * que valora una recomendacion.
 *
 * @param       Integer     $id_mensaje         Identificador de mensaje
 * @param       resource    $conexion           Identificador de enlace a MySQL
 * @return      Integer
 *
 */
function dbRECObtenerIdUsuarioAutorMuralDiseno($id_mensaje,$conexion){
    $id_usuario_autor= null;
    $consulta = "SELECT ".
                "MDMJ.mdmj_id_usuario ".
            "FROM ".
                " md_mensajes MDMJ ".
            "WHERE ".
                "MDMJ.mdmj_id_mensaje = ".$id_mensaje."  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $id_usuario_autor = $_fila["mdmj_id_usuario"];
        mysql_free_result($resultado);
    }
    return $id_usuario_autor;
}


/*
 * Función que obtiene el ID del usuario autor de una recomendacion
 * en la tabla mu_mensajes para almacenarlo junto al ID del usuario
 * que valora una recomendacion.
 *
 * @param       Integer     $id_mensaje         Identificador de mensaje
 * @param       resource    $conexion           Identificador de enlace a MySQL
 * @return      Integer
 *
 */
function dbRECObtenerIdUsuarioAutorMuralUsuario($id_mensaje,$conexion){
    $id_usuario_autor= null;
    $consulta = "SELECT ".
                "MUMJ.mumj_id_usuario_publica ".
            "FROM ".
                " mu_mensajes MUMJ ".
            "WHERE ".
                "MUMJ.mumj_id_mensaje = ".$id_mensaje."  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $id_usuario_autor = $_fila["mumj_id_usuario_publica"];
        mysql_free_result($resultado);
    }
    return $id_usuario_autor;
}

/**********************************************************************************/
/**********************************************************************************/
/**********************************************************************************/
/**********************************************************************************/
/**********************************************************************************/
/**********************************************************************************/

/*
 * Obtiene la tabla rec_megusta_mensaje para construir matriz numero me gusta
 */
function dbRECObtenerTablaRECMG($conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT * FROM rec_megusta_mensaje";
    
    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]            = $_fila["rec_mg_id_mensaje"];
                $_resp[$i]["id_usuario_valora"]     = $_fila["rec_mg_id_usuario_valora"];
                $_resp[$i]["id_usuario_autor"]      = $_fila["rec_mg_id_usuario_autor"];
                $i++;
            }
        }
    }
    return $_resp;
}

/*
 * Obtiene la tabla md_megusta_mensaje para construir matriz numero me gusta
 */
function dbRECObtenerTablaMDMG($conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT mdmg.mdmg_id_mensaje,
                        mdmg.mdmg_id_usuario_valora,
                        mdmg.mdmg_id_usuario_autor                        
                 FROM md_megusta_mensaje mdmg";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]            = $_fila["mdmg_id_mensaje"];
                $_resp[$i]["id_usuario_valora"]     = $_fila["mdmg_id_usuario_valora"];
                $_resp[$i]["id_usuario_autor"]      = $_fila["mdmg_id_usuario_autor"];
                $i++;
            }
        }
    }
    return $_resp;
}

/*
 * Obtiene la tabla mu_megusta_mensaje para construir matriz numero me gusta
 */
function dbRECObtenerTablaMUMG($conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT mumg.mumg_id_mensaje,
                        mumg.mumg_id_usuario_valora,
                        mumg.mumg_id_usuario_autor
                 FROM mu_megusta_mensaje mumg";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]            = $_fila["mumg_id_mensaje"];
                $_resp[$i]["id_usuario_valora"]     = $_fila["mumg_id_usuario_valora"];
                $_resp[$i]["id_usuario_autor"]      = $_fila["mumg_id_usuario_autor"];
                $i++;
            }
        }
    }
    return $_resp;
}

/*
 * Obtiene la tabla md_respuesta_mensajes para construir matriz numero de comentarios
 */
function dbRECObtenerTablaMDRespuestas($conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT mdrm.mdrm_id_mensaje_original,
                        mdrm.mdrm_id_usuario_responde,
                        mdrm.mdrm_id_usuario_autor
                 FROM md_respuesta_mensajes mdrm";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje_original"]       = $_fila["mdrm_id_mensaje_original"];
                $_resp[$i]["id_usuario_responde"]       = $_fila["mdrm_id_usuario_responde"];
                $_resp[$i]["id_usuario_autor"]          = $_fila["mdrm_id_usuario_autor"];
                $i++;
            }
        }
    }
    return $_resp;
}

function dbRECObtenerProfesoresDistintos($id_actividad, $conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT DISTINCT ".
                    "MDM.mdmj_id_usuario ".
                "FROM ".
                    "md_mensajes MDM ".
                "WHERE ".
                    "(MDM.mdmj_tipo_mensaje = '6' OR
                     MDM.mdmj_tipo_mensaje = '7') AND
                     MDM.mdmj_id_actividad = ".$id_actividad.";";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"]            = $_fila["mdmj_id_usuario"];
                $i++;
            }
        }
    }
    return $_resp;
}

function dbRECObtenerDatosProfesor($id_usuario, $conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "RECP.rec_niv_socioeco, ".
                    "RECP.rec_cal_tic, ".
                    "RECP.rec_tam_loc, ".
                    "RECP.rec_npromedio_alumnos ".
                "FROM ".
                    "rec_profesores RECP ".
                "WHERE ".
                    "RECP.rec_id_profesor = ".$id_usuario.";";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["NS"]            = $_fila["rec_niv_socioeco"];
                $_resp[$i]["CT"]            = $_fila["rec_cal_tic"];
                $_resp[$i]["TL"]            = $_fila["rec_tam_loc"];
                $_resp[$i]["N"]             = $_fila["rec_npromedio_alumnos"];
                $i++;
            }
        }
    }
    return $_resp;
}




/*
 * Obtiene todos los mensajes tipo 6 y 7 que son aquellos que se pueden recomendar.
 */
function dbRECObtenerMensajes($id_actividad, $conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "MDM.mdmj_id_mensaje, ".
                    "MDM.mdmj_id_usuario, ".
                    "MDM.mdmj_tipo_mensaje ".
                "FROM ".
                    "md_mensajes MDM ".
                "WHERE ".
                    "(MDM.mdmj_tipo_mensaje = '6' OR
                     MDM.mdmj_tipo_mensaje = '7') AND
                     MDM.mdmj_id_actividad = ".$id_actividad.";";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]            = $_fila["mdmj_id_mensaje"];
                $_resp[$i]["id_usuario"]            = $_fila["mdmj_id_usuario"];
                $_resp[$i]["tipo"]                  = $_fila["mdmj_tipo_mensaje"];
                $i++;
            }
        }
    }
    return $_resp;
}

/*
 * Obtiene el numero de valoraciones que posee un mensaje en particular.
 */
function dbRECNumMGComentario($id_mensaje, $conexion){
    $resp = 0;
    $consulta1 = "SELECT * FROM rec_megusta_mensaje
                 WHERE rec_mg_id_mensaje =".$id_mensaje.";";

    $consulta2 = "SELECT * FROM md_megusta_mensaje
                 WHERE mdmg_id_mensaje =".$id_mensaje.";";

    $consulta3 = "SELECT * FROM mu_megusta_mensaje
                 WHERE mumg_id_mensaje =".$id_mensaje.";";

    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
    $resultado3 = dbEjecutarConsulta($consulta3, $conexion);
    if ($resultado1 && $resultado2 && $resultado3) {
        $c1 = mysql_num_rows($resultado1);
        $c2 = mysql_num_rows($resultado2);
        $c3 = mysql_num_rows($resultado3);
        $resp = $c1 + $c2 + $c3;
    }
    return $resp;
    mysql_free_result($resultado);
}

/*
 * Obtiene la evaluacion de una actividad.
 */
function dbRECObtieneEvaluacionActividad($id_mensaje, $conexion){
    $evaluacion= null;
    $consulta = "SELECT REVA.rec_evaluacion ".
            "FROM ".
                "rec_evaluacion_actividad REVA ".
            "WHERE ".
                "REVA.rec_id_mensaje = ".$id_mensaje.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $evaluacion = $_fila["rec_evaluacion"];
        mysql_free_result($resultado);
    }
    return $evaluacion;
}

/*
 * Obtiene el numero de valoraciones que posee un profesor en particular.
 */
function dbRECNumMGProf($id_usuario, $conexion){
    $resp = 0;
    $consulta1 = "SELECT * FROM rec_megusta_mensaje
                 WHERE rec_mg_id_usuario_autor =".$id_usuario.";";

    $consulta2 = "SELECT * FROM md_megusta_mensaje
                 WHERE mdmg_id_usuario_autor =".$id_usuario.";";

    $consulta3 = "SELECT * FROM mu_megusta_mensaje
                 WHERE mumg_id_usuario_autor =".$id_usuario.";";

    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
    $resultado3 = dbEjecutarConsulta($consulta3, $conexion);
    if ($resultado1 && $resultado2 && $resultado3) {
        $c1 = mysql_num_rows($resultado1);
        $c2 = mysql_num_rows($resultado2);
        $c3 = mysql_num_rows($resultado3);
        $resp = $c1 + $c2 + $c3;
    }
    return $resp;
    mysql_free_result($resultado);
}

/*
 * Obtiene el numero de experiencias ejecutadas (finalizadas) por un profesor en particular.
 */
function dbRECNumExpEjecutadas($id_usuario, $conexion){
    $resp = 0;
    $consulta = "SELECT * FROM experiencia_didactica
                 WHERE ed_id_profesor =".$id_usuario."
                   AND ed_publicado = 1 
                   AND ed_fecha_termino <> '';";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        $resp = mysql_num_rows($resultado);
    }
    return $resp;
    mysql_free_result($resultado);
}



/*
 * RELLENO DE TABLA PROFESORES

// Obtiene todos los usuarios con inscribe_diseno = 1 desde tabla usuario.
function obtenerProfesores($conexion){
    $_resp= null;
    $i = 0;
    $consulta = "SELECT u_id_usuario, u_nombre
                 FROM usuario
                 WHERE u_inscribe_diseno = 1;";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"] = $_fila["u_id_usuario"];
                $_resp[$i]["nombre"] = $_fila["u_nombre"];
                $i++;
            }
        }
    }
    return $_resp;
}

// Inserta en la tabla rec_profesores el id_usuario y nombre.
function dbRECInsertarProfesores($id_usuario, $nombre, $conexion) {
    $resp = null;
    $consulta = "INSERT INTO rec_profesores (".
                    "rec_id_profesor, ".
                    "rec_nombre_profesor ".
                    ") ".
                 "VALUES (".
                            $id_usuario.", ".
                            "'".$nombre."');";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $resp = 1;
    }
    else {
        $resp = -1;
    }
    return $resp;
}

*/

// ACTUALIZA COLUMNA REC_NPROMEDIO_ALUMNOS

// Obtiene el ID de todos los profesores de la tabla rec_profesores.
function dbRECObtenerProfesores($conexion){
    $_resp= null;
    $i = 0;
    $consulta = "SELECT rec_id_profesor
                 FROM rec_profesores;";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_profesor"] = $_fila["rec_id_profesor"];
                $i++;
            }
        }
    }
    return $_resp;
}

// Obtiene el numero de experiencias en las que participa un usuario.
function dbRECObtieneNumeroExperiencias($id_usuario, $conexion){
    $_resp= null;
    $i = 0;
    $consulta = "SELECT *
                 FROM experiencia_didactica
                 WHERE ed_id_profesor = ".$id_usuario." AND
                 ed_publicado = 1;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        $resp = mysql_num_rows($resultado);
    }
    return $resp;
    mysql_free_result($resultado);
}

// Obtiene el ID de todas las experiencias en que participa un usuario.
function dbRECObtieneExperienciasProfesor($id_usuario, $conexion){
    $_resp= null;
    $i = 0;
    $consulta = "SELECT ed_id_experiencia
                 FROM experiencia_didactica
                 WHERE ed_id_profesor = ".$id_usuario." AND
                 ed_publicado = 1;";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_experiencia"] = $_fila["ed_id_experiencia"];
                $i++;
            }
        }
    }
    return $_resp;
}

//Obtiene el numero de alumnos participantes de una experiencia didactica.
function dbRECObtieneNumeroAlumnos($id_experiencia, $conexion){
    $_resp= null;
    $i = 0;
    $consulta = "SELECT *
                 FROM usuario_experiencia
                 WHERE ue_id_experiencia = ".$id_experiencia."
                 AND ue_rol_usuario = 2;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        $resp = mysql_num_rows($resultado);
    }
    return $resp;
    mysql_free_result($resultado);
}

// Actualiza el campo rec_npromedio_alumnos de la tabla rec_profesores.
function dbRECActualizaNumeroPromedioAlumnos($id_usuario, $promedio_alumnos, $conexion) {
    $resp = null;
    $consulta = "UPDATE rec_profesores
                SET rec_npromedio_alumnos = ".$promedio_alumnos."
                WHERE rec_id_profesor = ".$id_usuario.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        $resp = 1;
    }
    else {
        $resp = -1;
    }
    return $resp;
}


//Obtiene los datos para su despliegue de los id_mensajes devueltos por python
function dbRECObtenerDatosMsgRecomendar($id_actividad, $id_mensaje, $conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "MDM.mdmj_id_usuario, ".
                    "MDM.mdmj_fecha, ".
                    "MDM.mdmj_mensaje, ".
                    "MDM.mdmj_tipo_mensaje, ".
                    "U.u_nombre, ".
                    "U.u_url_imagen, ".
                    "U.u_usuario ".
                "FROM ".
                    "md_mensajes MDM, usuario U ".
                "WHERE ".
                     "MDM.mdmj_id_mensaje = ".$id_mensaje." AND  ".
                     "MDM.mdmj_id_usuario = U.u_id_usuario AND  ".
                     "MDM.mdmj_id_actividad =".$id_actividad.";";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"]            = $_fila["mdmj_id_usuario"];
                $_resp[$i]["fecha"]                 = $_fila["mdmj_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["mdmj_mensaje"];
                $_resp[$i]["tipo"]                  = $_fila["mdmj_tipo_mensaje"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["imagen"]                = $_fila["u_url_imagen"];
                $_resp[$i]["usuario"]               = $_fila["u_usuario"];
                $i++;
            }
        }
    }
    return $_resp;
}
?>
