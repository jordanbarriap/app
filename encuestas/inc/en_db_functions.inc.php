<?php
/**
* Contiene las funciones de manejo de la Base de Datos para el gestor de encuestas de Kelluwen
*
* LICENSE: código fuente distribuido con licencia LGPL
*
* @author  Sergio Bustamante M. - Kelluwen
* @copyleft Kelluwen, Universidad Austral de Chile
* @license www.kelluwen.cl/app/licencia_kelluwen.txt
* @version 0.1
*
**/
//$ruta_raiz = "../../";
//require_once($ruta_raiz . "reportes/inc/re_db_functions.inc.php");
//require_once($ruta_raiz . "conf/config.php");
//require_once($ruta_raiz . "inc/all.inc.php");
/**

/**
 * @author Sergio Bustamante - Kelluwen
 * @param string $id_usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array ( nombre                     =>    String,
 *                              inscribe_diseno        =>    Integer,
 *                              email                        =>    String,
 *                              fecha_nacimiento     =>     Date,
 *                              localidad                   =>    String,
 *                              establecimiento        =>    String,
 *                              id                               =>    Integer,
 *                              imagen                      =>    String,
 *                              ultimo_acceso           =>    Date,
 *                              usuario                      =>    String)
 */
function dbENInfoUsuario($id_usuario, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "U.u_nombre, ".
                    "U.u_email , ".
                    "U.u_inscribe_diseno, ".
                    "U.u_fecha_nacimiento , ".
                    "U.u_localidad, ".
                    "U.u_establecimiento, ".
                    "U.u_id_usuario, ".
                    "U.u_url_imagen, ".
                    "U.u_fecha_ultimo_acceso, ".
                    "U.u_usuario, ".
                    "U.u_password ".
                 "FROM ".
                    "usuario U ".
                 "WHERE ".
                   "U.u_id_usuario  = '".$id_usuario."'";
    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
            $_resp["nombre"]                    =    $_fila["u_nombre"];
            $_resp["inscribe_diseno"]       =    $_fila["u_inscribe_diseno"];
            $_resp["email"]                       =     $_fila["u_email"];
            $_resp["fecha_nacimiento"]    =     $_fila["u_fecha_nacimiento"];
            $_resp["localidad"]                 =     $_fila["u_localidad"];
            $_resp["establecimiento"]       =    $_fila["u_establecimiento"];
            $_resp["id"]                             =    $_fila["u_id_usuario"];
            $_resp["imagen"]                    =    $_fila["u_url_imagen"];
            $_resp["ultimo_acceso"]         =    $_fila["u_fecha_ultimo_acceso"];
            $_resp["usuario"]                    =    $_fila["u_usuario"];
            $_resp["password"]                 =    $_fila["u_password"];
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

/**
* Obtiene productos asociados al grupo en la tabla rp_producto
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.07.03
* @param       Array  $a_rol arreglo de los encuestados, para separar publico objetivo de la encuesta (1:estudiante 2:profesor 3:colaborador)
* @param       Integer $avance porcentaje de avance exigido por filtro
* @param       Integer $anio de los experiencia de la encuesta (desde)
* @param       Integer $anio1 de los experiencia de la encuesta (hasta)
* @param       Integer $semestre de las experiencias seleccionadas
* @param       Integer $id_encuesta a la que seran enlazados los encuestados
* @param       resource $conexion Identificador de enlace a MySQL
* @return       $_resp null/true para verificar si hubo
*/

function dbENVolcarUsuariosAEncuesta($a_rol,$avance,$anio,$anio1,$semestre, $id_encuesta,$config_host_bd, $config_bd_ls, $config_usuario_bd_ls, $config_password_bd_ls,$conexion){
    $_resp=null;


    //generamos parte de la consulta dinamicamente deendiendo de la cantidad de elementos del arreglo
    $cadena_consulta_grupos = '(';
    $cadena_consulta_avance = '';
    foreach($a_rol as $key =>$rol ){
        $cadena_consulta_grupos .= " UE.ue_rol_usuario =" .$rol;
        if($a_rol[$key+1]){
            $cadena_consulta_grupos .= " OR ";
        }
    }
     $cadena_consulta_grupos .= ')';

     //verificamos la seleccion de semestre para configurar la consulta
     if($semestre == 0){//caso donde no se filtra por semestre
         $rango_semestre = '';
     }
     else{//caso donde si se filtra por semestre
         $rango_semestre = "AND substring(ED.ed_semestre,1,1) = '".$semestre."'";
     }

     //verificamos la sleeccion de anio para configurar la consulta
     if($anio == $anio1){//caso del mismo anio
         $rango_anio = "ED.ed_anio = '".$anio."'";
     }
     else{
         $rango_anio = "ED.ed_anio BETWEEN '".$anio."' AND '".$anio1."'";
     }
    //consulta para seleccionar usuarios de la plataforma
    $consulta = "SELECT DISTINCT ".
                                                            "UE.ue_id_usuario, UE.ue_rol_usuario ".
                        "FROM ".
                                                            "usuario_experiencia UE, experiencia_didactica ED ".
                        "WHERE ".
                                                            $cadena_consulta_grupos.' AND '.
                                                            'UE.ue_id_experiencia = ED.ed_id_experiencia '.
                                                            $rango_semestre."  AND ".
                                                            $rango_anio;
//
//echo $consulta;
//    $cambio = mysql_select_db($config_bd_ls,$conexion);//

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado){
        //definimos estructira en encuesta a ingressar en BD limesurvey
        $consulta_acumulada = '';
        $consulta2 = "INSERT INTO lime_tokens_".$id_encuesta." (tid,
                                                                                                         firstname,
                                                                                                         lastname,
                                                                                                         email,
                                                                                                         emailstatus,
                                                                                                         token,
                                                                                                         language,
                                                                                                         sent,
                                                                                                        remindersent,
                                                                                                        remindercount,
                                                                                                        completed,
                                                                                                        usesleft) ".
                                "VALUES ";

        //arreglo de ids para no duplicar
        $a_evita_duplicados = Array();
                                
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp['id_usuario']     =  $_fila["ue_id_usuario"];
                $_resp['rol_usuario']     =  $_fila["ue_rol_usuario"];

                //true en defecto para todos los usuarios
                $bandera = true;

                //consultamos por el id de usuario
                $datos_usuario = dbENInfoUsuario($_resp['id_usuario'], $conexion);    

                //caso de usuario profesor y se exija avance
                if($_resp['rol_usuario'] == 1 && $avance != 0){

                    //false en defecto para los profesores
                    $bandera = false;

                    //consultamos por las experiencias del usuario
                     $experiencias_profesor = dbObtenerExpUsuarioMin($datos_usuario['usuario'], $conexion);

                    //para cada experiencia del usuario verificamos su avance
                    if($experiencias_profesor){
                        foreach($experiencias_profesor as $experiencia){
                            if($experiencia['rol'] == 1){
                                if($experiencia["anio"] >= $anio && $experiencia["anio"] <= $anio1){
                                     if($semestre != 0){//caso donde pide semestre y cumple con requisito
                                        if($semestre == substr($experiencia["semestre"], 0, 1)){//caso donde pide semestre y cumple con requisito

                                            //se obtiene el nivel de avance de la experiencia
                                            $_avance_experiencia = dbExpObtenerAvance($experiencia["id_experiencia"], $conexion);
                                            //calculo de nivel de avance
                                            $t_estimado = $_avance_experiencia["suma_sesiones_estimadas"] * 90; //tiempo sesion
                                            $t_ejecutado = $_avance_experiencia["suma_t_actividades_finalizadas"] OR 0;
                                            $nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);
                                        }
                                    }
                                    else{//caso donde no pide semestre y cumple con requisito
                                        //se obtiene el nivel de avance de la experiencia
                                        $_avance_experiencia = dbExpObtenerAvance($experiencia["id_experiencia"], $conexion);
                                        //calculo de nivel de avance
                                        $t_estimado = $_avance_experiencia["suma_sesiones_estimadas"] * 90; //tiempo sesion
                                        $t_ejecutado = $_avance_experiencia["suma_t_actividades_finalizadas"] OR 0;
                                        $nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);
                                    }
                                    if($nivel_avance >= $avance){
                                        $bandera = true;
                                    }
                                }
                            }
                        }
                    }
                }


                //evitamos duplicados en el insert
                if(in_array($_resp['id_usuario'], $a_evita_duplicados)){
                    $bandera = false;
                    //echo $_resp['id_usuario'];
                }
                else{
                    $push = array_push($a_evita_duplicados, $_resp['id_usuario']);
                    //echo $push.'</br>';
                  //  var_dump($a_evita_duplicados);
                }

                //si bandera es verdadero
                if($bandera){
                    if(!$datos_usuario['email']){
                        $datos_usuario['email'] = 'nulo@default.com';
                    }
                    $datos_usuario['nombre'] = str_replace("'", '', $datos_usuario['nombre']);
                    $consulta_contenido = "(".$_resp['id_usuario'].",".
                                                "'".$datos_usuario['nombre']."',".
                                                "' '".",".
                                                "'".$datos_usuario['email']."',".
                                                "'OK'".",".
                                                "'".$_resp['id_usuario'].str_replace('.','_',$datos_usuario['usuario'])."',".
                                                "'es'".",".
                                                "'N'".",".
                                                "'N'".",".
                                                "0".",".
                                                "'N'".",".
                                                "1"."),";
                    $consulta_acumulada = $consulta_acumulada.$consulta_contenido;
                }
            }
            $consultaimpresa = $consulta2.$consulta_acumulada;
            //echo $consultaimpresa.'</br>';

            //cortamos la ultima , (coma)
            $consulta2= substr ($consultaimpresa, 0, -1);


            $conexion_ls = dbConectarMySQL($config_host_bd, $config_usuario_bd_ls, $config_password_bd_ls,$config_bd_ls);
            //selecciona base de datos LS

            //ejecutar insercion
            $resultado2 = dbEjecutarConsulta($consulta2,$conexion_ls);
            
             if (mysql_affected_rows() > 0) {
                 $_resp = true;
             }
        }
        else{
            echo '0';
        }
    }
    else{
        echo '0';
    }
    return $_resp;
}


/**
* Activa la encuesta
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.09.03
* @param       resource $conexion Identificador de enlace a MySQL ( no recibe otro parametro pues ocnsulta osbre una tabla entera)
* @param       Integer $activa Y/N para activar o desactivar encuesta
* @param       Integer $id_encuesta encuesta
* @param       Integer $grupo  grupos de encuestados
* @param       Integer $periodo de los encuestados
* @return       $_resp true/null
*/

function dbENSetEncuesta($id_encuesta,$activa,$grupo,$encuestados_semestre,$encuestados_anio,$encuestados_anio1,$conexion){
    $_resp=null;

    $consulta = "UPDATE lime_surveys SET active='".$activa."' , encuestados='".$grupo."', encuestados_semestre = '".$encuestados_semestre."' , encuestados_anio = '".$encuestados_anio."' , encuestados_anio1 = '".$encuestados_anio1."' , startdate=sysdate() WHERE sid =".$id_encuesta;
// echo $consulta;
    $resultado = dbEjecutarConsulta($consulta,$conexion);

    if($resultado){
        if (mysql_affected_rows() > 0) {
            $_resp = true;
        }
    }
    return $_resp;
}

/**
* Verifica si la encuesta ha sido asignada
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.22.05
* @param       resource $conexion Identificador de enlace a MySQL ( no recibe otro parametro pues ocnsulta osbre una tabla entera)
* @param       Integer $id_encuesta encuesta
* @return       $_resp true/null
*/

function dbENVerificaAsignacion($id_encuesta,$conexion){
    $_resp=0;

    $consulta = "SELECT count(*) FROM lime_tokens_".$id_encuesta;
    // echo $consulta;
    $resultado = dbEjecutarConsulta($consulta,$conexion);

    if($resultado){
         if (mysql_num_rows($resultado) > 0) {
            while($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)){
                if($_fila['count(*)'] == 0){//caso que la tabla existe y esta libre
                    $_resp = 1;
                }
                else{//caso que tabla existe pero se asigno
                    $_resp = -1;
                }
            }
        }
    }
    return $_resp;
}

/**
* Verifica si existe encuesta
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.12.03
* @param       resource $conexion Identificador de enlace a MySQL ( no recibe otro parametro pues ocnsulta osbre una tabla entera)
* @param       Integer $id_encuesta que se quiere conocer
* @return       $_resp true/null
*/

function dbENVerificaEncuesta($id_encuesta,$conexion){
    $_resp= 0;//caso que la encuesta no existe

    $consulta = "SELECT * FROM lime_surveys WHERE ".
                                "sid=".$id_encuesta;
 //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta,$conexion);

    //verificamos si la en cuesta existe
    if($resultado){
        if (mysql_num_rows($resultado) > 0) {
            $bandera_verifica = dbENVerificaAsignacion($id_encuesta,$conexion);
            if($bandera_verifica == 1){
                $_resp = 1; //disponible y aceptada
            }
            elseif($bandera_verifica == -1){
                $_resp = -1;//tabla asignada
            }
            elseif($bandera_verifica == 0){
                $_resp = -2;//tabla no creada
            }
        }
    }
    return $_resp;
}

/**
* Consulta por encuestas asignadas a un ID de usuario 
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.12.03
* @param       resource $conexion Identificador de enlace a MySQL ( no recibe otro parametro pues ocnsulta osbre una tabla entera)
* @param       Integer $id_usuario que se quiere consultar
* @param       Integer $bandera que indica si se quieren solo las incompletas o todas
* @return       $_resp true/null
*/

function dbENEncuestasPorUsuario($id_usuario,$bandera,$conexion){
    $_resp=array();

    if($bandera == 1) {//caso que selecciona encuestas no contestadas
        $condicion_bandera = 'usesleft > 0';
    }
    elseif($bandera == 0){//caso que se selecciona encuestas contestadas
        $condicion_bandera = 'usesleft = 0';
    }
    $consulta = "SELECT LS.sid, LSLS.surveyls_title FROM lime_surveys LS, lime_surveys_languagesettings LSLS WHERE expires >= sysdate() AND active = 'Y' AND LS.sid = LSLS.surveyls_survey_id";
     
    $resultado = dbEjecutarConsulta($consulta,$conexion);

    //obtencion de arreglo de encuestas de limesurvey
    if($resultado){
        $i = 0;
        if (mysql_num_rows($resultado) > 0) {
            while($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)){
                $a_encuestas[$i] ['id']           = $_fila['sid'];
                $a_encuestas[$i] ['nombre']  = $_fila['surveyls_title'];
                $i++;
            }
        }
    }
    $i=0;
    if($a_encuestas){
    //para cada encuesta buscar el usuario en cuestion
        foreach($a_encuestas as $encuesta){
            $consulta_encuesta = 'SELECT DISTINCT * FROM lime_tokens_'.$encuesta['id'].' WHERE tid='.$id_usuario.
                                                                                                                                           ' AND '.$condicion_bandera;
            //echo $consulta_encuesta;
            $resultado2 = dbEjecutarConsulta($consulta_encuesta,$conexion);

            if($resultado2){
                if (mysql_num_rows($resultado2) > 0) {
                    $_resp[$i]['id'] = $encuesta['id'];
                    $_resp[$i]['nombre'] = $encuesta['nombre'];
                    $i++;
                }
            }
        }
    }

    if(count($_resp) == 0){
        $_resp = null;
    }

    return $_resp;
}

/**
* Obtener encuestas de la plataforma en curso
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.13.03
* @param       resource $conexion Identificador de enlace a MySQL ( no recibe otro parametro pues ocnsulta osbre una tabla entera)
* @return       $_resp Array(Integer  =>   sid)
*/

function dbENObtenerEncuestas($bandera_tipo, $conexion){
    $_resp=null;

    //para obtener encuestas activas
    if($bandera_tipo == 1) $consulta = "SELECT LSL.surveyls_title, LS.sid FROM lime_surveys LS, lime_surveys_languagesettings LSL WHERE LS.expires > sysdate() AND LS.active = 'Y' AND LS.sid = LSL.surveyls_survey_id";

    //para obtener encuestas inactivas
    if($bandera_tipo == 0) $consulta = "SELECT LSL.surveyls_title, LS.sid FROM lime_surveys LS, lime_surveys_languagesettings LSL WHERE (LS.expires < sysdate() OR LS.active = 'N') AND LS.sid = LSL.surveyls_survey_id";
    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta,$conexion);

    if($resultado){
        $i=0;
        if (mysql_num_rows($resultado) > 0) {
            while($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)){
                $_resp[$i]['id_encuesta']               =       $_fila['sid'];
                $_resp[$i]['nombre_encuesta']      =       $_fila['surveyls_title'];
                $i++;
            }
        }
    }
    return $_resp;
}

/**
* Obtener info de una encuesta
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.13.05
* @param       resource $conexion Identificador de enlace a MySQL
* @param       Integer $id_encuesta
* @return       $_resp Array(Integer  =>   activa)
*/

function dbENEncuestaInfo($id_encuesta, $conexion){
    $_resp=null;

    $consulta = "SELECT * FROM lime_surveys WHERE sid=".$id_encuesta;

    $resultado = dbEjecutarConsulta($consulta,$conexion);

    if($resultado){
        if (mysql_num_rows($resultado) > 0) {
            while($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)){
                $_resp['activa']                             =       $_fila['active'];
                $_resp['fecha_expira']                   =        $_fila['expires'];
                $_resp['fecha_comienzo']             =       $_fila['startdate'];
                $_resp['encuestados']                    =       $_fila['encuestados'];
                $_resp['encuestados_semestre']   =       $_fila['encuestados_semestre'];
                $_resp['encuestados_anio']        =       $_fila['encuestados_anio'];
                $_resp['encuestados_anio1']      =       $_fila['encuestados_anio1'];
            }
        }
    }
    return $_resp;
}

/**
* Obtener arreglo de anios en los que hay experiencias didacticas
*
* @author      Sergio Bustamante M. - Kelluwen
* @version     2012.29.05
* @param       resource $conexion Identificador de enlace a MySQL
* @return       $_resp Array(Integer  =>   ed_anio)
*/

function dbENAniosExperiencias($conexion){
    $_resp=null;

    $consulta = "SELECT DISTINCT ed_anio FROM experiencia_didactica WHERE ed_anio != 0  ORDER BY ed_anio";

    $resultado = dbEjecutarConsulta($consulta,$conexion);

    if($resultado){
        $i=0;
        if (mysql_num_rows($resultado) > 0) {
            while($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)){
                $_resp[$i]                   =       $_fila['ed_anio'];
                $i++;
            }
        }
    }
    return $_resp;
}


?>
