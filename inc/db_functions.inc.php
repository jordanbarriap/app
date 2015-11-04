<?php

/**
 * Contiene las funciones de manejo de la Base de Datos
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Carolina Aros - Kelluwen
 *          Daniel Guerra - Kelluwen
 *          Katherine Inalef - Kelluwen
 *          José Carrasco - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */

/**
 * Realiza conexión con MySQL
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.02.09
 * @param       String   $host Nombre de host
 * @param       String   $usuario Nombre de usuario MySQL
 * @param       String   $password Contraseña de usuario MySQL
 * @param       String   $bd Nombre de base de datos MySQL
 * @return      resource $conexion Identificador de enlace a MySQL
 */
function dbConectarMySQL($host, $usuario, $password, $bd) {
    if ($host != "" && $usuario != "" && $password != "" && $bd != "") {
        if (($conexion = mysql_connect($host, $usuario, $password))) {
            if (( mysql_select_db($bd, $conexion))) {
                mysql_set_charset('utf8');
                return $conexion;
            }
        }
    }
    return false;
}

/**
 * Realiza desconexión con MySQL
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.02.09
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Boolean  Estado de la conexión
 */
function dbDesconectarMySQL($conexion) {
    if ((mysql_close($conexion))) {
        return true;
    }
    return false;
}

/**
 * Ejecuta una consulta MySQL
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.02.09
 * @param       String   $consulta Consulta MySQL
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Boolean  Estado de ejecución de la consulta
 */
function dbEjecutarConsulta($consulta, $conexion) {
    global $global_error_bd;
    $global_error_bd = "";
    if (($resultado = mysql_query($consulta, $conexion))) {
        return $resultado;
    }
    $global_error_bd = mysql_error();
    return false;
}

/**
 * Valida si los datos ingresados por el usuario corresponde a un usuario registrado
 *
 * @author Daniel Guerra - Kelluwen
 * @param string    $v_usuario nombre  usuario
 * @param string    $v_password contraseña usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array(   id_usuario          => Integer,
 *                  usuario             => String,
 *                  inscribe_diseno     =>Integer,
 *                  nombre              => String,
 *                  email               => String,
 *                  url_imagen          => String)
 */
function dbValidarUsuario($v_usuario, $v_password, $conexion) {
    $consulta = "SELECT " .
            "U.u_id_usuario, " .
            "U.u_usuario, " .
            "U.u_inscribe_diseno, " .
            "U.u_nombre, " .
            "U.u_email, " .
            "U.u_url_imagen, " .
            "U.u_mostrar_email, " .
            "U.u_mostrar_fecha_nacimiento, " .
            "U.u_administrador ".
            "FROM usuario U " .
            "WHERE " .
            "U.u_usuario = '" . $v_usuario . "' AND " .
            "md5(U.u_password) = '" . $v_password . "' AND " .
            "U.u_activo = 1;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    $_resp = null;

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp["id_usuario"] = $_fila["u_id_usuario"];
                $_resp["usuario"]    = $_fila["u_usuario"];
                $_resp["inscribe_diseno"] = $_fila["u_inscribe_diseno"];
                $_resp["nombre"] = $_fila["u_nombre"];
                $_resp["email"] = $_fila["u_email"];
                $_resp["url_imagen"] = $_fila["u_url_imagen"];
                $_resp["mostrar_correo"] = $_fila["u_mostrar_email"];
                $_resp["mostrar_fecha"] = $_fila["u_mostrar_fecha_nacimiento"];
                $_resp["administrador"] = $_fila["u_administrador"];
            }
        }
    }

    return $_resp;
}

/**
 * Obtiene las experiencias en las que participa un usuario
 *
 * @author Daniel Guerra - Kelluwen
 * @param string    $v_usuario nombre de usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array()
 */
function dbObtenerExpUsuario($v_usuario, $conexion) {
    $_resp = null;
    $i = 0;
    $consulta = "SELECT " .
            "D.dd_nombre, " .
            "D.dd_nivel, " .
            "D.dd_subsector, " .
            "E.ed_id_experiencia, " .
            "E.ed_localidad, " .
            "E.ed_curso, " .
            "E.ed_colegio, " .
            "UE.ue_rol_usuario " .
            "FROM " .
            "experiencia_didactica E, " .
            "usuario U, " .
            "usuario_experiencia UE, " .
            "diseno_didactico D " .
            "WHERE " .
            "UE.ue_id_usuario = U.u_id_usuario AND " .
            "UE.ue_id_experiencia =E.ed_id_experiencia AND " .
            "E.ed_publicado =1 AND " .
            "U.u_usuario = '" . $v_usuario . "' AND " .
            "D.dd_id_diseno_didactico = E.ed_id_diseno_didactico";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["nombre_dd"] = $_fila["dd_nombre"];
                $_resp[$i]["nivel"] = $_fila["dd_nivel"];
                $_resp[$i]["subsector"] = $_fila["dd_subsector"];
                $_resp[$i]["id_experiencia"] = $_fila["ed_id_experiencia"];
                $_resp[$i]["localidad"] = $_fila["ed_localidad"];
                $_resp[$i]["curso"] = $_fila["ed_curso"];
                $_resp[$i]["colegio"] = $_fila["ed_colegio"];
                $_resp[$i]["rol"] = $_fila["ue_rol_usuario"];
                $i++;
            }
        }
    }

    return $_resp;
}

function dbObtenerExpUsuarioMin($v_usuario, $conexion) {
    $_resp = null;
    $i = 0;
    $consulta = "SELECT " .
            "E.ed_id_experiencia, " .
            "E.ed_semestre, " .
            "E.ed_anio, " .
            "UE.ue_rol_usuario " .
            "FROM " .
            "experiencia_didactica E, " .
            "usuario U, " .
            "usuario_experiencia UE " .
            "WHERE " .
            "UE.ue_id_usuario = U.u_id_usuario AND " .
            "UE.ue_id_experiencia =E.ed_id_experiencia AND " .
            "U.u_usuario = '" . $v_usuario . "'";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_experiencia"] = $_fila["ed_id_experiencia"];
                $_resp[$i]["rol"] = $_fila["ue_rol_usuario"];
                $_resp[$i]['semestre'] = $_fila["ed_semestre"];
                $_resp[$i]['anio'] = $_fila["ed_anio"];
                $i++;
            }
        }
    }

    return $_resp;
}

/**
 * Obtiene las experiencias en curso en las que participa un usuario
 *
 * @author Carolina Aros - Kelluwen
 * @param string    $v_usuario nombre de usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array()
 */
function dbObtenerExpCursoUsuario($v_usuario, $conexion) {
    $_resp = null;
    $i = 0;
    $consulta = "SELECT " .
            "D.dd_nombre, " .
            "D.dd_nivel, " .
            "D.dd_subsector, " .
            "E.ed_id_experiencia, " .
            "E.ed_localidad, " .
            "E.ed_curso, " .
            "E.ed_colegio, " .
            "UE.ue_rol_usuario " .
            "FROM " .
            "experiencia_didactica E, " .
            "usuario U, " .
            "usuario_experiencia UE, " .
            "diseno_didactico D " .
            "WHERE " .
            "UE.ue_id_usuario = U.u_id_usuario AND " .
            "UE.ue_id_experiencia =E.ed_id_experiencia AND " .
            "U.u_usuario = '" . $v_usuario . "' AND " .
            "D.dd_id_diseno_didactico = E.ed_id_diseno_didactico AND " .
            "E.ed_fecha_termino is NULL AND " .
            "E.ed_publicado=1 ".
            "ORDER BY " .
            "E.ed_fecha_inicio DESC";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["nombre_dd"] = $_fila["dd_nombre"];
                $_resp[$i]["nivel"] = $_fila["dd_nivel"];
                $_resp[$i]["subsector"] = $_fila["dd_subsector"];
                $_resp[$i]["id_experiencia"] = $_fila["ed_id_experiencia"];
                $_resp[$i]["localidad"] = $_fila["ed_localidad"];
                $_resp[$i]["curso"] = $_fila["ed_curso"];
                $_resp[$i]["colegio"] = $_fila["ed_colegio"];
                $_resp[$i]["rol"] = $_fila["ue_rol_usuario"];
                $i++;
            }
        }
    }

    return $_resp;
}

/**
 * Obtiene las experiencias finalizadas en las que participa un usuario
 *
 * @author Carolina Aros - Kelluwen
 * @param string    $v_usuario nombre de usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array()
 */
function dbObtenerExpFinalizadasUsuario($v_usuario, $conexion) {
    $_resp = null;
    $i = 0;
    $consulta = "SELECT " .
            "D.dd_nombre, " .
            "D.dd_nivel, " .
            "D.dd_subsector, " .
            "E.ed_id_experiencia, " .
            "E.ed_localidad, " .
            "E.ed_curso, " .
            "E.ed_colegio, " .
            "UE.ue_rol_usuario " .
            "FROM " .
            "experiencia_didactica E, " .
            "usuario U, " .
            "usuario_experiencia UE, " .
            "diseno_didactico D " .
            "WHERE " .
            "UE.ue_id_usuario = U.u_id_usuario AND " .
            "UE.ue_id_experiencia =E.ed_id_experiencia AND " .
            "U.u_usuario = '" . $v_usuario . "' AND " .
            "D.dd_id_diseno_didactico = E.ed_id_diseno_didactico AND " .
            "E.ed_fecha_termino is not NULL AND " .
            "E.ed_publicado=1 ".
            "ORDER BY " .
            "E.ed_fecha_termino DESC";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["nombre_dd"] = $_fila["dd_nombre"];
                $_resp[$i]["nivel"] = $_fila["dd_nivel"];
                $_resp[$i]["subsector"] = $_fila["dd_subsector"];
                $_resp[$i]["id_experiencia"] = $_fila["ed_id_experiencia"];
                $_resp[$i]["localidad"] = $_fila["ed_localidad"];
                $_resp[$i]["curso"] = $_fila["ed_curso"];
                $_resp[$i]["colegio"] = $_fila["ed_colegio"];
                $_resp[$i]["rol"] = $_fila["ue_rol_usuario"];
                $i++;
            }
        }
    }

    return $_resp;
}

/**
 * Obtiene las clases gemelas
 *
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.10.02
 * @param       String   $v_usuario nombre de usuario
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      $_resp  Array() con los datos de las experiencias didacticas
 */
function dbObtenerClasesGem($id_exp, $et_gem, $conexion) {
    $_resp = null;
    $i = 0;

    $consulta = "SELECT " .
            "E.ed_localidad, " .
            "E.ed_curso, " .
            "E.ed_colegio " .
            "FROM " .
            "experiencia_didactica E " .
            "WHERE " .
            "E.ed_etiqueta_gemela ='" . $et_gem . "' AND " .
            "E.ed_id_experiencia <> " . $id_exp . ";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["localidad_cg"] = $_fila["ed_localidad"];
                $_resp[$i]["curso_cg"] = $_fila["ed_curso"];
                $_resp[$i]["colegio_cg"] = $_fila["ed_colegio"];
                $i++;
            }
        }
    }
    return $_resp;
}

/**
 * Obtiene la 'etiqueta gemela' de una experiencia didactica
 *
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer   $id_experiencia identificador de la experiencia didactica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      $resp  etiqueta gemela de la experiencia didactica
 */
function dbObtenerEtiqGemExpDidac($id_experiencia, $conexion) {
    $_resp = null;
    $i = 0;
    $consulta = "SELECT " .
            "ED.ed_etiqueta_gemela " .
            "FROM " .
            "experiencia_didactica ED " .
            "WHERE " .
            "ED.ed_id_experiencia = " . $id_experiencia . ";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $resp = $_fila["ed_etiqueta_gemela"];
                $i++;
            }
        }
    }
    return $resp;
}

/**
 * Obtiene la 'etiqueta ' de una experiencia didactica
 *
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer   $id_experiencia identificador de la experiencia didactica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      $resp  etiqueta de la experiencia didactica
 */
function dbObtenerEtiqExpDidac($id_experiencia, $conexion) {
    $_resp = null;
    $i = 0;
    /* los diseños como profesor */
    $consulta = "SELECT " .
            "ED.ed_etiqueta " .
            "FROM " .
            "experiencia_didactica ED " .
            "WHERE " .
            "ED.ed_id_experiencia = " . $id_experiencia . ";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);


    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {

            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $resp = $_fila["ed_etiqueta"];
                $i++;
            }
        }
    }

    return $resp;
}

/**
 * Asigna a un grupo, un grupo gemelo.
 * Elimina cualquier conexion anterior con otro grupo
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       Integer  $id_migrupo Código en la base de datos para la instancia grupo
 * @param       Integer  $id_grupogem Código en la base de datos para la instancia grupo (gemelo a asignar)
 * @param       Integer  $id_miggem Código en la base de datos para la instancia grupo (gemelo actual)
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Integer $resp si se realizo la operacion
 */
function dbAsignGrupoGem($id_experiencia, $id_migrupo, $id_grupogem, $id_miggem, $conexion) {
    $resp1 = null;
    $resp = null;
    $codigo = "";
    /* 1.- OBTENER ETIQUETA DE MI GRUPO */
    $consulta1 = "SELECT G.g_etiqueta_gemela " .
            "FROM grupo G " .
            "WHERE G.g_id_experiencia = " . $id_experiencia . " " .
            "AND G.g_id_grupo= " . $id_migrupo . "; ";
    echo $consulta1;
    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    $filas_afectadas = 1;
    /* 2.- GENERAR CODIGO PARA MI ASIGNACION ACTUAL */
    while ($filas_afectadas != 0) {
        $codigo = generarCodigo(8);
        $consulta3 = "SELECT " .
                "g_id_grupo " .
                "FROM " .
                "grupo " .
                "WHERE " .
                "g_etiqueta_gemela ='" . $codigo . "'";
        echo $consulta3;
        $resultado3 = dbEjecutarConsulta($consulta3, $conexion);
        if ($resultado3) {
            $filas_afectadas = mysql_num_rows($resultado3);
            echo $filas_afectadas;
        } else {
            //ERROR MYSQL
        }
    }
    /* 3.- ACTUALIZAR SU ETIQUETA PARA DEJARLO DISPONIBLE (ELIMINANDO TRANSITIVIDAD) */
    $consulta4 = "UPDATE  grupo " .
            "SET g_etiqueta_gemela = '" . $codigo . "' " .
            "WHERE g_id_grupo = " . $id_miggem . "; ";
    echo $consulta4;
    $resultado4 = dbEjecutarConsulta($consulta4, $conexion);

    if ($resultado1) {
        if (mysql_num_rows($resultado1) > 0) {
            while ($_fila = mysql_fetch_array($resultado1, MYSQL_BOTH)) {
                $resp1 = $_fila["g_etiqueta_gemela"];
            }
            /* 4.- ASIGNO MI NUEVO GRUPO GEMELO */
            if ($id_grupogem > 0) {

                $consulta2 = "UPDATE  grupo " .
                        "SET g_etiqueta_gemela = '" . $resp1 . "' " .
                        "WHERE g_id_grupo = " . $id_grupogem . "; ";
                echo $consulta2;
                $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
                if ($resultado2) {
                    return mysql_affected_rows();
                } else {
                    $resp = -1;
                }
            }
        }
    }
    return $resp;
}

/**
 * Actualiza la etiqueta gemela de mi experiencia didactica.
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       String   $etiqueta_gem etiqueta gemela de mi clase gemela
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Integer $resp -1 si no se realizo la operacion ,<> -1 si se realizo
 */
function dbSetEtiquetaGem($id_experiencia, $etiqueta_gem, $conexion) {
    $resp = null;
    $codigo = 0;
    $filas_afectadas = 1;

    /* Obtiene el identificador del diseño didáctico al que pertenece la experiencia */
    /* Para verificar que no se inserte un código gemelo de una experiencia con otro diseño */

    $consulta = "SELECT " .
            "ed_id_diseno_didactico " .
            "FROM " .
            "experiencia_didactica " .
            "WHERE " .
            "ed_id_experiencia=" . $id_experiencia . ";";
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if (mysql_num_rows($resultado) > 0) {
        while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
            $id_diseno = $_fila["ed_id_diseno_didactico"];
        }
        /* Selecciona las experiencias que tienen la misma etiqueta y que pertenecen al mismo diseño */
        $consulta1 = "SELECT " .
                "ED.ed_id_experiencia " .
                "FROM " .
                "experiencia_didactica ED " .
                "WHERE " .
                "ED.ed_etiqueta_gemela='" . $etiqueta_gem . "' AND " .
                "ED.ed_id_diseno_didactico=" . $id_diseno . ";";

        $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
        if (mysql_num_rows($resultado1) > 0) {
            while ($_fila = mysql_fetch_array($resultado1, MYSQL_BOTH)) {
                $resp = $_fila["ed_id_experiencia"];
                if ($resp != $id_experiencia) {
                    $cont++;
                }
            }

            /* Si existe a lo menos una experiencia con la etiqueta ingresada se buscan los grupos de la experiencia actual */
            /* Y se les reasigna un código gemelo */
            if ($cont > 0) {
                $consulta4 = "SELECT " .
                        "g_id_grupo " .
                        "FROM " .
                        "grupo G " .
                        "WHERE " .
                        "G.g_id_experiencia=" . $id_experiencia . ";";

                $resultado4 = dbEjecutarConsulta($consulta4, $conexion);

                if (mysql_num_rows($resultado4) > 0) {
                    while ($_fila = mysql_fetch_array($resultado4, MYSQL_BOTH)) {
                        $respg = $_fila["g_id_grupo"];
                        while ($filas_afectadas != 0) {
                            $codigo = generarCodigo(8);
                            $consulta3 = "SELECT " .
                                    "g_id_grupo " .
                                    "FROM " .
                                    "grupo " .
                                    "WHERE " .
                                    "g_etiqueta_gemela ='" . $codigo . "'";
                            $resultado3 = dbEjecutarConsulta($consulta3, $conexion);
                            if ($resultado3) {
                                $filas_afectadas = mysql_num_rows($resultado3);
                            } else {
                                //ERROR MYSQL
                            }
                        }
                        $filas_afectadas = 1;

                        /* Se asigna un código nuevo a los grupos que peretenecen a la experiencia */
                        $consulta5 = "UPDATE " .
                                "grupo " .
                                "SET " .
                                "g_etiqueta_gemela ='" . $codigo . "' " .
                                "WHERE " .
                                "g_id_grupo =" . $respg . ";";

                        $resultado5 = dbEjecutarConsulta($consulta5, $conexion);
                    }
                    /* Se actualiza la etiqueta de la experiencia didáctica actual */
                    $consulta2 = "UPDATE " .
                            "experiencia_didactica " .
                            "SET " .
                            "ed_etiqueta_gemela ='" . $etiqueta_gem . "' " .
                            "WHERE " .
                            "ed_id_experiencia = " . $id_experiencia . ";";
                    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
                    if ($resultado2) {
                        $resp = mysql_affected_rows();
                    }
                }
            }
        }
    }
    return $resp;
}

/**
 * Devuelve todos los grupos de mis clases gemelas que se encuentran disponibles.
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       Sring    $etiqueta_gem  etiqueta gemela correspondiente a la experiencia didactica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array $_gruposdisponibles Datos de los grupos disponibles
 */
function dbObtenerGruposClaseGem($id_experiencia, $etiqueta_gem, $conexion) {
    $_resp = null;
    $resp = null;
    $i = 0;
    $resp1 = null;
    $_gruposdisponibles = null;
    /* SELECCIONO EL ID DE MI DISEÑO DIDACTICO, DE MI EXPERIENCIA DIDACTICA */

    $consulta1 = "SELECT ED.ed_id_diseno_didactico " .
            "FROM experiencia_didactica ED " .
            "WHERE ED.ed_id_experiencia=" . $id_experiencia . "; ";
    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    if ($resultado1) {
        if (mysql_num_rows($resultado1) > 0) {
            while ($_fila = mysql_fetch_array($resultado1, MYSQL_BOTH)) {
                $resp1 = $_fila["ed_id_diseno_didactico"];
            }
            /* OBTENGO LOS GRUPOS GEMELOS QUE CORRESPONDEN A MI EXPERIENCIA */
            $consulta = "SELECT " .
                    "G.g_id_grupo, " .
                    "G.g_nombre, " .
                    "ED.ed_localidad, " .
                    "ED.ed_colegio, " .
                    "G.g_etiqueta_gemela  " .
                    "FROM " .
                    "grupo G, " .
                    "experiencia_didactica ED " .
                    "WHERE " .
                    "G.g_id_experiencia <>" . $id_experiencia . " " .
                    "AND ED.ed_etiqueta_gemela = '" . $etiqueta_gem . "' " .
                    "AND G.g_id_experiencia = ED.ed_id_experiencia " .
                    "AND ED.ed_id_diseno_didactico=" . $resp1 . " ";

            $resultado = dbEjecutarConsulta($consulta, $conexion);
            if ($resultado) {
                if (mysql_num_rows($resultado) > 0) {
                    while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                        $_resp[$i]["id_grupo"] = $_fila["g_id_grupo"];
                        $_resp[$i]["nombre_g"] = $_fila["g_nombre"];
                        $_resp[$i]["localidad_g"] = $_fila["ed_localidad"];
                        $_resp[$i]["colegio_g"] = $_fila["ed_colegio"];
                        $_resp[$i]["etiqueta_g"] = $_fila["g_etiqueta_gemela"];
                        $i++;
                    }
                    $j = 0;
                    foreach ($_resp as $ggem) {
                        /* FILTRO LOS QUE YA ESTAN OCUPADOS */
                        $consulta2 = "SELECT * " .
                                "FROM grupo G " .
                                "WHERE G.g_etiqueta_gemela = '" . $ggem["etiqueta_g"] . "' AND " .
                                " G.g_id_grupo <> " . $ggem["id_grupo"] . ";";

                        $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
                        if ($resultado2) {
                            /* GUARDO LOS QUE ESTAN DISPONIBLES */
                            if (mysql_num_rows($resultado2) == 0) {
                                $_gruposdisponibles[$j]["id_grupo"] = $ggem["id_grupo"];
                                $_gruposdisponibles[$j]["nombre_g"] = $ggem["nombre_g"];
                                $_gruposdisponibles[$j]["localidad_g"] = $ggem["localidad_g"];
                                $_gruposdisponibles[$j]["colegio_g"] = $ggem["colegio_g"];
                                $_gruposdisponibles[$j]["etiqueta_g"] = $ggem["etiqueta_g"];
                                $j++;
                            }
                        }
                    }
                }
            }
        }
    }
    return $_gruposdisponibles;
}

/**
 * Entrega los Diseños Didácticos disponibles y su información correspondiente dado un subsector
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.02.13
 * @param       Conexion   $conexion conexion a la Base de Datos
 * @param       String $subsector código de subsector según base de datos
 * @return      Array (  id_dd              => Integer,
 *                       nombre_dd          => String,
 *                       descripcion_dd     => String,
 *                       nivel_dd           => String,
 *                       subsector          => String,
 *                       fecha_creacion     => Date,
 *                       manejo_tecnologico => String)
 */
function dbDisObtenerDisenosSubsector($conexion, $subsector) {
    $_resp = array();
    $i = 0;
    $consulta = "SELECT " .
            "D.dd_id_diseno_didactico, " .
            "D.dd_nombre, " .
            "D.dd_descripcion, " .
            "D.dd_nivel, " .
            "D.dd_subsector, " .
            "D.dd_fecha_creacion, " .
            "D.dd_descripcion_e1, " .
            "D.dd_descripcion_e2, " .
            "D.dd_descripcion_e3, " .
            "D.dd_objetivos_curriculares, " .
            "D.dd_objetivos_transversales, " .
            "D.dd_contenidos, " .
            "D.dd_manejo_tecnologico, " .
            "HW.hw_nombre, " .
            "HW.hw_enlace, " .
            "HW.hw_imagen " .
            "FROM " .
            "diseno_didactico D ". 
            "LEFT OUTER JOIN (herramientas_diseno HD LEFT OUTER JOIN herramientas_web HW ".
            "ON HD.hd_id_herramienta = HW.hw_id_herramienta  ) " .
            "ON D.dd_id_diseno_didactico = HD.hd_id_diseno_didactico ".
            "WHERE D.dd_subsector ='" . $subsector . "' " .
            "AND D.dd_publicado=1 " .
            "ORDER BY D.dd_nivel ASC";
    

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_dd"] = $_fila["dd_id_diseno_didactico"];
                $_resp[$i]["nombre_dd"] = $_fila["dd_nombre"];
                $_resp[$i]["descripcion_dd"] = $_fila["dd_descripcion"];
                $_resp[$i]["nivel"] = $_fila["dd_nivel"];
                $_resp[$i]["subsector"] = $_fila["dd_subsector"];
                $_resp[$i]["fecha_creacion"] = $_fila["dd_fecha_creacion"];
                $_resp[$i]["des_etapa1"] = $_fila["dd_descripcion_e1"];
                $_resp[$i]["des_etapa2"] = $_fila["dd_descripcion_e2"];
                $_resp[$i]["des_etapa3"] = $_fila["dd_descripcion_e3"];
                $_resp[$i]["manejo_tecno"] = $_fila["dd_manejo_tecnologico"];
                $_resp[$i]["obj_curriculares"] = $_fila["dd_objetivos_curriculares"];
                $_resp[$i]["obj_transversales"] = $_fila["dd_objetivos_transversales"];
                $_resp[$i]["contenidos"] = $_fila["dd_contenidos"];
                $_resp[$i]["herramienta_nombre"] = $_fila["hw_nombre"];
                $_resp[$i]["herramienta_enlace"] = $_fila["hw_enlace"];
                $_resp[$i]["herramienta_imagen"] = $_fila["hw_imagen"];

                $i++;
            }
        }
    }
    return $_resp;
}

function dbDisObtenerDisenosGeneral($conexion, $subsector) {
    $_resp = array();
    $i = 0;
    $consulta = "SELECT " .
            "D.dd_id_diseno_didactico, " .
            "D.dd_nombre, " .
            "D.dd_descripcion, " .
            "D.dd_nivel, " .
            "D.dd_subsector, " .
            "D.dd_fecha_creacion, " .
            "D.dd_descripcion_e1, " .
            "D.dd_descripcion_e2, " .
            "D.dd_descripcion_e3, " .
            "D.dd_objetivos_curriculares, " .
            "D.dd_objetivos_transversales, " .
            "D.dd_contenidos, " .
            "D.dd_manejo_tecnologico, " .
            "HW.hw_nombre, " .
            "HW.hw_enlace, " .
            "HW.hw_imagen " .
            "FROM " .
            "diseno_didactico D ".
            "LEFT OUTER JOIN (herramientas_diseno HD LEFT OUTER JOIN herramientas_web HW ".
            "ON HD.hd_id_herramienta = HW.hw_id_herramienta  ) " .
            "ON D.dd_id_diseno_didactico = HD.hd_id_diseno_didactico ".
            "WHERE D.dd_subsector ='" . $subsector . "' " .
            "ORDER BY D.dd_nivel ASC";


    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_dd"] = $_fila["dd_id_diseno_didactico"];
                $_resp[$i]["nombre_dd"] = $_fila["dd_nombre"];
                $_resp[$i]["descripcion_dd"] = $_fila["dd_descripcion"];
                $_resp[$i]["nivel"] = $_fila["dd_nivel"];
                $_resp[$i]["subsector"] = $_fila["dd_subsector"];
                $_resp[$i]["fecha_creacion"] = $_fila["dd_fecha_creacion"];
                $_resp[$i]["des_etapa1"] = $_fila["dd_descripcion_e1"];
                $_resp[$i]["des_etapa2"] = $_fila["dd_descripcion_e2"];
                $_resp[$i]["des_etapa3"] = $_fila["dd_descripcion_e3"];
                $_resp[$i]["manejo_tecno"] = $_fila["dd_manejo_tecnologico"];
                $_resp[$i]["obj_curriculares"] = $_fila["dd_objetivos_curriculares"];
                $_resp[$i]["obj_transversales"] = $_fila["dd_objetivos_transversales"];
                $_resp[$i]["contenidos"] = $_fila["dd_contenidos"];
                $_resp[$i]["herramienta_nombre"] = $_fila["hw_nombre"];
                $_resp[$i]["herramienta_enlace"] = $_fila["hw_enlace"];
                $_resp[$i]["herramienta_imagen"] = $_fila["hw_imagen"];

                $i++;
            }
        }
    }
    return $_resp;
}

/**
 * Entrega tres comentarios aleatorios para un diseño didáctico dado
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.02.13
 * @param       Conexion   $conexion conexion a la Base de Datos
 * @return      Array ( comentario       => String,
 *                      nombre_usuario   => String,
 *                      username         => String,
 *                      fecha            => Date,
 *                      url_imagen       => String,
 *                      localidad        => String,
 *                      curso            => String,
 *                      colegio          => String)
 */
function dbDisObtenerComentariosAleatorios($conexion, $id_disenod) {
    $_resp = array();
    $i = 0;

    $consulta = "SELECT " .
            "CD.cdd_comentario, " .
            "CD.cdd_nombre_usuario, " .
            "CD.cdd_usuario, " .
            "CD.cdd_fecha, " .
            "U.u_url_imagen, " .
            "ED.ed_localidad, " .
            "ED.ed_curso, " .
            "ED.ed_colegio " .
            "FROM " .
            "diseno_didactico DD, " .
            "comentario_dd CD, " .
            "usuario U, " .
            "experiencia_didactica ED " .
            "WHERE " .
            "DD.dd_id_diseno_didactico=" . $id_disenod . " " .
            "AND CD.cdd_id_diseno_didactico = DD.dd_id_diseno_didactico " .
            "AND CD.cdd_usuario = U.u_usuario " .
            "AND DD.dd_id_diseno_didactico = ED.ed_id_diseno_didactico " .
            "AND CD.cdd_id_experiencia = ED.ed_id_experiencia " .
            "ORDER BY Rand() " .
            "LIMIT 3 ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["comentario"] = $_fila["cdd_comentario"];
                $_resp[$i]["nombre_usuario"] = $_fila["cdd_nombre_usuario"];
                $_resp[$i]["username"] = $_fila["cdd_usuario"];
                $_resp[$i]["fecha"] = $_fila["cdd_fecha"];
                $_resp[$i]["url_imagen"] = $_fila["u_url_imagen"];
                $_resp[$i]["localidad"] = $_fila["ed_localidad"];
                $_resp[$i]["curso"] = $_fila["ed_curso"];
                $_resp[$i]["colegio"] = $_fila["ed_colegio"];
                $i++;
            }
        }
    }
    return $_resp;
}

/**
 * Devuelve los datos de un grupo gemelo en particular.
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       String  $etiquetagem etiqueta gemela del grupo
 * @param       Integer $id_migrupo identificar del grupo
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array $resp Datos referentes al grupo gemelo
 */
function dbObtenerMigrupoGem($conexion, $etiquetagem, $id_migrupo) {
    $resp = Array();
    $consulta = "SELECT G.g_id_grupo,G.g_nombre,ED.ed_localidad, ED.ed_curso, ED.ed_colegio " .
            "FROM grupo G,experiencia_didactica ED " .
            "WHERE G.g_etiqueta_gemela='" . $etiquetagem . "' " .
            "AND G.g_id_grupo <>" . $id_migrupo . " " .
            "AND G.g_id_experiencia = ED.ed_id_experiencia ;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $resp["id_grupo"] = $_fila["g_id_grupo"];
                $resp["nombre_g"] = $_fila["g_nombre"];
                $resp["localidad_g"] = $_fila["ed_localidad"];
                $resp["curso_g"] = $_fila["ed_curso"];
                $resp["colegio_g"] = $_fila["ed_colegio"];
            }
        }
    }
    return $resp;
}

function dbDisObtenerUltimaEtiqueta($conexion, $id_profesor, $id_diseno_didactico) {
    $_resp = array();
    $i = 0;
    $consulta = "SELECT " .
            "ED.ed_id_experiencia, " .
            "ED.ed_etiqueta " .
            "FROM " .
            "experiencia_didactica ED " .
            "WHERE " .
            "ED.ed_id_profesor =" . $id_profesor . " " .
            "AND ED.ed_id_diseno_didactico =" . $id_diseno_didactico . " " .
            "ORDER BY ED.ed_id_experiencia DESC " .
            "LIMIT 1 ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_experiencia"] = $_fila["ed_id_experiencia"];
                $_resp[$i]["etiqueta"] = $_fila["ed_etiqueta"];
                $i++;
            }
        }
    }
    return $_resp;
}

/**
 * Devuelve información sobre las etapas de cada Diseño Didactico
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.08.27
 * @param       Integer  $id_diseno Código en la base de datos para la instancia diseño didactico
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( id_e       => Integer,
 *                      nombre_e   => String)
 */
function dbDisObtenerEtapas($conexion, $id_diseno) {
    $_resp = array();
    $i = 0;

    $consulta = "SELECT " .
            "E.e_id_etapa, " .
            "E.e_nombre " .
            "FROM " .
            "diseno_didactico DD," .
            "etapa E " .
            "WHERE " .
            "DD.dd_id_diseno_didactico=E.e_id_diseno_didactico " .
            "AND  DD.dd_id_diseno_didactico =" . $id_diseno . ";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_e"] = $_fila["e_id_etapa"];
                $_resp[$i]["nombre_e"] = $_fila["e_nombre"];
                $i++;
            }
        }
    }
    return $_resp;
}

/**
 * Devuelve información sobre las actividades de una etapa
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.08.27
 * @param       Integer  $id_etapa Código en la base de datos para la instancia etapa de un diseño didactico
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( id_a      => Integer,
 *                      nombre_a  => String)
 */
function dbDisObtenerActividades($conexion, $id_etapa) {
    $_resp = array();
    $i = 0;

    $consulta = "SELECT " .
            "A.ac_id_actividad, " .
            "A.ac_tipo, " .
            "A.ac_nombre " .
            "FROM " .
            "etapa E, " .
            "actividad A " .
            "WHERE " .
            "E.e_id_etapa = A.ac_id_etapa " .
            "AND  E.e_id_etapa = " . $id_etapa . " ORDER BY ac_orden ASC;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_a"] = $_fila["ac_id_actividad"];
                $_resp[$i]["tipo"] = $_fila["ac_tipo"];
                $_resp[$i]["nombre_a"] = $_fila["ac_nombre"];
                $i++;
            }
        }
    }
    return $_resp;
}

function dbObtenerComentariosActividades($conexion, $id_actividad) {
    $_resp = array();
    $i = 0;
    /* los diseños como profesor */
    $consulta = "SELECT " .
            "CEA.cea_comentario " .
            "FROM " .
            "comentario_ea CEA, " .
            "exp_actividad EA, " .
            "actividad A " .
            "WHERE " .
            "A.ac_id_actividad = EA.ea_id_actividad " .
            "AND EA.ea_id_exp_actividad = CEA.cea_id_exp_actividad " .
            "AND  A.ac_id_actividad = " . $id_actividad . ";";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["comentario"] = $_fila["cea_comentario"];
                $i++;
            }
        }
    }
    return $_resp;
}

/**
 * Entrega el Detalle de un Diseño Didáctico en particular
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.02.13
 * @param       Conexion   $conexion conexion a la Base de Datos
 * @return      Array   $_resp  Arreglo con los datos de Diseños Didácticos
 */
function dbObtenerDetalleDDidacticos($conexion, $id_dd) {
    $_resp = array();
    $i = 0;
    /* los diseños como profesor */
    $consulta = "SELECT " .
            "D.dd_nombre, " .
            "D.dd_nivel, " .
            "D.dd_subsector, " .
            "D.dd_descripcion, " .
            "D.dd_manejo_tecnologico " .
            "FROM " .
            "diseno_didactico D " .
            "WHERE " .
            "D.dd_id_diseno_didactico =" . $id_dd . ";";
//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {

            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {

                $_resp[$i]["nombre_dd"] = $_fila["dd_nombre"];
                $_resp[$i]["nivel"] = $_fila["dd_nivel"];
                $_resp[$i]["subsector"] = $_fila["dd_subsector"];
                $_resp[$i]["descripcion"] = $_fila["dd_descripcion"];
                $_resp[$i]["manejo_tecnologico"] = $_fila["dd_manejo_tecnologico"];
                $i++;
            }
        }
    }
    return $_resp[0];
}

/**
 * Devuelve información general sobre una experiencia didáctica (dd en ejecución)
 *
 * @author      Daniel Guerra - Kelluwen
 * @version     2010.02.15
 * @param       Integer   $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource  $conexion Identificador de enlace a MySQL
 * @return      array (  nombre_dd                  => String,
 *                       nivel                      => String,
 *                       subsector                  => String,
 *                       autores                    => String,
 *                       fecha_creacion             => Date,
 *                       etiqueta                   => String,
 *                       etiqueta_gemela            => String,
 *                       nombre_profesor            => String,
 *                       usuario_profesor           => String,
 *                       usuario_twitter_profesor   => String,
 *                       url_avatar_profesor        => String,
 *                       localidad                  => String,
 *                       curso                      => String,
 *                       colegio                    => String,
 *                       fecha_inicio               => Date,
 *                       fecha_termino              => Date,
 *                       fecha_ultimo_acceso        => Date,
 *                       experiencia_profesor       => String,
 *                       archivos_dd                => Array (id_archivo    => Integer,
 *                                                            url           => String,
 *                                                            tipo          => String,
 *                                                            descripcion   => String,
 *                                                            observaciones => String ))
 */
function dbExpObtenerInfo($id_experiencia, $conexion) {
    $_resp = array();

    $consulta = "SELECT " .
            "DD.dd_nombre, " .
            "DD.dd_nivel, " .
            "DD.dd_subsector, " .
            "DD.dd_autores, " .
            "DD.dd_fecha_creacion, " .
            "DD.dd_descripcion, " .
            "DD.dd_objetivos_curriculares, " .
            "DD.dd_objetivos_transversales, " .
            "DD.dd_contenidos, " .
            "DD.dd_manejo_tecnologico, " .
            "DD.dd_id_diseno_didactico, ".
            "ED.ed_etiqueta, " .
            "ED.ed_etiqueta_gemela, " .
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
                $_resp["id_dd"]                 = $_fila["ed_id_diseno_didactico"];
                $_resp["nivel"]                 = $_fila["dd_nivel"];
                $_resp["subsector"]             = $_fila["dd_subsector"];
                $_resp["autores"]               = $_fila["dd_autores"];
                $_resp["fecha_creacion"]        = $_fila["dd_fecha_creacion"];
                $_resp["descripcion"]           = $_fila["dd_descripcion"];
                $_resp["objetivos_c"]           = $_fila["dd_objetivos_curriculares"];
                $_resp["objetivos_t"]           = $_fila["dd_objetivos_transversales"];
                $_resp["contenidos"]            = $_fila["dd_contenidos"];
                $_resp["manejo_tecnologico"]    = $_fila["dd_manejo_tecnologico"];
                $_resp["id_dd"]                 = $_fila["dd_id_diseno_didactico"];
                $_resp["etiqueta"]              = $_fila["ed_etiqueta"];
                $_resp["etiqueta_gemela"]       = $_fila["ed_etiqueta_gemela"];
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
                $_resp["archivos_dd"]           = null;

                $id_dd = trim($_fila["ed_id_diseno_didactico"]);
            }
            $consulta2 = "SELECT " .
                    "a_id_archivo, " .
                    "a_nombre_archivo, " .
                    "a_solo_profesor, " .
                    "a_descripcion, " .
                    "a_observaciones " .
                    "FROM " .
                    "archivo " .
                    "WHERE " .
                    "a_id_diseno_didactico = '" . $id_dd . "' AND " .
                    "(a_id_actividad is null OR a_id_actividad = '') ORDER BY a_orden ASC";

            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);

            if ($resultado2) {
                if (mysql_num_rows($resultado2) > 0) {
                    $_resp["archivos_dd"] = array();
                    $i = 0;
                    while ($_fila2 = mysql_fetch_array($resultado2, MYSQL_BOTH)) {
                        $_resp["archivos_dd"][$i]["id_archivo"] = $_fila2["a_id_archivo"];
                        $_resp["archivos_dd"][$i]["nombre"] = $_fila2["a_nombre_archivo"];
                        $_resp["archivos_dd"][$i]["solo_profesor"] = $_fila2["a_solo_profesor"];
                        $_resp["archivos_dd"][$i]["descripcion"] = $_fila2["a_descripcion"];
                        $_resp["archivos_dd"][$i]["observaciones"] = $_fila2["a_observaciones"];
                        $i++;
                    }
                }
                mysql_free_result($resultado2);
            } else {
                // ERROR MYSQL
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

/**
 * Devuelve la lista de etapas con sus actividades
 * @author      Carolina Aros - Kelluwen
 * @version     2010.03.04
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( id_etapa                => Integer,
 *                      nombre_etapa            => String,
 *                      objetivo                => String,
 *                      sesiones_estimadas      => Integer
 *                      aprendizajes_esperados  => String,
 *                      contenidos              => String,
 *                      material_requerido      => String,
 *                      descripcion             => String,
 *                      observaciones           => String,
 *                      id_experiencia_etapa    => Integer,
 *                      horas_invertidas        => Integer,
 *                      actividades             => Array (  id_actividad      => Integer,
 *                                                          id_exp_actividad  => Integer,
 *                                                          estado            => Integer,
 *                                                          etiqueta          => String,
 *                                                          horas_estimadas   => Integer,
 *                                                          descripcion       => String,
 *                                                          tiene_comentarios => Boolean,
 *                                                          disponible        => Boolean,
 *                                                          prioridad         => Integer )
 */
function dbExpObtenerEtapas($id_experiencia, $conexion) {
    $_resp = array();

    //Obtiene la información sobre las etapas en las tablas ETAPA Y EXP_ETAPA
    $consulta = "SELECT " .
            "E.e_id_etapa, " .
            "E.e_nombre, " .
            "E.e_objetivo, " .
            "E.e_sesiones_estimadas, " .
            "E.e_aprendizajes_esperados, " .
            "E.e_contenidos, " .
            "E.e_material_requerido, " .
            "E.e_descripcion, " .
            "E.e_observaciones, " .
            "E.e_orden, " .
            "EE.ee_id_exp_etapa " .
            "FROM " .
            "etapa E, exp_etapa EE " .
            "WHERE " .
            "E.e_id_diseno_didactico IN (" .
            "SELECT " .
            "ed_id_diseno_didactico " .
            "FROM " .
            "experiencia_didactica " .
            "WHERE " .
            "ed_id_experiencia=$id_experiencia) AND " .
            "EE.ee_id_etapa=E.e_id_etapa AND " .
            "EE.ee_id_experiencia=$id_experiencia " .
            "ORDER BY " .
            "E.e_orden ASC";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $i = 0;

            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_etapa"] = $_fila["e_id_etapa"];
                $_resp[$i]["nombre_etapa"] = $_fila["e_nombre"];
                $_resp[$i]["objetivo"] = $_fila["e_objetivo"];
                $_resp[$i]["sesiones_estimadas"] = $_fila["e_sesiones_estimadas"];
                $_resp[$i]["aprendizajes_esperados"] = $_fila["e_aprendizajes_esperados"];
                $_resp[$i]["contenidos"] = $_fila["e_contenidos"];
                $_resp[$i]["material_requerido"] = $_fila["e_material_requerido"];
                $_resp[$i]["descripcion"] = $_fila["e_descripcion"];
                $_resp[$i]["observaciones"] = $_fila["e_observaciones"];
                $_resp[$i]["id_exp_etapa"] = $_fila["ee_id_exp_etapa"];
                $_resp[$i]["horas_invertidas"] = null;
                $_resp[$i]["actividades"] = null;

                //Obtiene la lista de actividades de una etapa
                $consulta2 = "SELECT " .
                        "AC.ac_id_actividad, " .
                        "AC.ac_nombre, " .
                        "EA.ea_id_exp_actividad, " .
                        "COALESCE(EA.ea_estado,'1') as ea_estado, " .
                        "EA.ea_etiqueta, " .
                        "AC.ac_horas_estimadas, " .
                        "AC.ac_descripcion, " .
                        "AC.ac_publica_producto, " .
                        "AC.ac_aprendizaje_esperado, " .
                        "AC.ac_evidencia_aprendizaje, " .
                        "AC.ac_medios, " .
                        "AC.ac_tipo, " .
                        "AC.ac_revisa_pares " .
                        "FROM " .
                        "actividad AC " .
                        "LEFT JOIN " .
                        "exp_actividad EA " .
                        "ON " .
                        "AC.ac_id_actividad = EA.ea_id_actividad AND EA.ea_id_exp_etapa = " . $_resp[$i]["id_exp_etapa"] . " " .
                        "WHERE " .
                        "AC.ac_id_etapa = " . $_resp[$i]["id_etapa"] . " ORDER BY ac_orden ASC;";

                $resultado2 = dbEjecutarConsulta($consulta2, $conexion);

                if ($resultado2) {
                    if (mysql_num_rows($resultado2) > 0) {
                        $j = 0;
                        $_actividades = array();
                        while ($_fila2 = mysql_fetch_array($resultado2, MYSQL_BOTH)) {
                            $_actividades[$j]["id_actividad"] = $_fila2["ac_id_actividad"];
                            $_actividades[$j]["nombre"] = $_fila2["ac_nombre"];
                            $_actividades[$j]["id_exp_actividad"] = $_fila2["ea_id_exp_actividad"];
                            $_actividades[$j]["estado"] = $_fila2["ea_estado"];
                            $_actividades[$j]["etiqueta"] = $_fila2["ea_etiqueta"];
                            $_actividades[$j]["horas_estimadas"] = $_fila2["ac_horas_estimadas"];
                            $_actividades[$j]["descripcion"] = $_fila2["ac_descripcion"];
                            $_actividades[$j]["publica_producto"] = $_fila2["ac_publica_producto"];
                            $_actividades[$j]["revisa_pares"] = $_fila2["ac_revisa_pares"];
                            $_actividades[$j]["aprendizaje_esperado"] = $_fila2["ac_aprendizaje_esperado"];
                            $_actividades[$j]["evidencia_aprendizaje"] = $_fila2["ac_evidencia_aprendizaje"];
                            $_actividades[$j]["medios"] = $_fila2["ac_medios"];
                            $_actividades[$j]["tipo"] = $_fila2["ac_tipo"];
                            $_actividades[$j]["tiene_comentarios"] = 0;
                            $_actividades[$j]["disponible"] = 1;
                            $_actividades[$j]["prioridad"] = 1;
                            $j++;
                        }
                        $_resp[$i]["actividades"] = $_actividades;
                    }
                    mysql_free_result($resultado2);
                } else {
                    // ERROR MYSQL
                }

                //Obtiene las horas invertidas de una etapa
                $consulta3 = "SELECT " .
                        "A.ac_horas_estimadas " .
                        "FROM " .
                        "actividad A, exp_actividad EA, exp_etapa EE " .
                        "WHERE " .
                        "A.ac_id_actividad=EA.ea_id_actividad AND " .
                        "EA.ea_estado = 3 AND " .
                        "EA.ea_id_exp_etapa = EE.ee_id_exp_etapa AND " .
                        "EE.ee_id_exp_etapa=" . $_resp[$i]["id_exp_etapa"] . "";

                $resultado3 = dbEjecutarConsulta($consulta3, $conexion);

                if ($resultado3) {
                    if (mysql_num_rows($resultado3) > 0) {
                        $horas_invertidas = 0;

                        while ($_fila3 = mysql_fetch_array($resultado3, MYSQL_BOTH)) {
                            $horas_invertidas = $horas_invertidas + $_fila3["ac_horas_estimadas"];
                        }
                        $_resp[$i]["horas_invertidas"] = $horas_invertidas;
                    }
                    mysql_free_result($resultado3);
                } else {
                    //ERROR MYSQL
                }

                $i++;
            }
        } else {
            //No existen etapas para esta experiencia
            $_resp = null;
        }
        mysql_free_result($resultado);
    } else {
        //ERROR MYSQL
    }
    return $_resp;
}

/**
 * Obtiene la informacion sobre una actividad específica
 *
 * @author      Katherine Inalef - Kelluwen
 * @version     2010.03.04
 * @param       Integer  $id_actividad Código en la base de datos para la instancia actividad
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      array ( nombre              => String,
 *                      version             => Integer,
 *                      horas_estimadas     => Integer,
 *                      material_requerido  => String,
 *                      instrucciones       => String,
 *                      descripcion         => String,
 *                      fecha_creacion      => Date,
 *                      archivos_actividad  => Array (  id_archivo    => Integer,
 *                                                      url           => String,
 *                                                      tipo          => Integer,
 *                                                      descripcion   => String,
 *                                                      observaciones => String )
 */
function dbExpObtenerActividad($id_actividad, $conexion) {

    $_resp = array();

    //Obtiene información sobre la actividad
    $consulta = "SELECT " .
            "ac_nombre, " .
            "ac_version, " .
            "ac_horas_estimadas, " .
            "ac_material_requerido, " .
            "ac_medios, " .
            "ac_instrucciones_grales, " .
            "ac_instrucciones_inicio, " .
            "ac_instrucciones_desarrollo, " .
            "ac_instrucciones_cierre, " .
            "ac_descripcion, " .
            "ac_consejos_practicos, " .
            "ac_fecha_creacion, " .
            "ac_publica_producto, " .
            "ac_instrucciones_producto, " .
            "ac_instrucciones_revision, " .
            "ac_id_complementaria, " .
            "ac_aprendizaje_esperado, " .
            "ac_evidencia_aprendizaje, " .
            "ac_tipo, " .
            "ac_medios_bitacora, " .
            "ac_medios_trabajos, " .
            "ac_medios_web2, " .
            "ac_medios_otros, " .
            "ac_revisa_pares " .
            "FROM " .
            "actividad " .
            "WHERE " .
            "ac_id_actividad = " . $id_actividad . ";";
//      echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp["nombre"] = $_fila["ac_nombre"];
                $_resp["version"] = $_fila["ac_version"];
                $_resp["horas_estimadas"] = $_fila["ac_horas_estimadas"];
                $_resp["material_requerido"] = $_fila["ac_material_requerido"];
                $_resp["medios"] = $_fila["ac_medios"];
                $_resp["medios_bitacora"] = $_fila["ac_medios_bitacora"];
                $_resp["medios_trabajos"] = $_fila["ac_medios_trabajos"];
                $_resp["medios_web2"] = $_fila["ac_medios_web2"];
                $_resp["medios_otros"] = $_fila["ac_medios_otros"];
                $_resp["instrucciones_grales"] = $_fila["ac_instrucciones_grales"];
                $_resp["instrucciones_inicio"] = $_fila["ac_instrucciones_inicio"];
                $_resp["instrucciones_desarrollo"] = $_fila["ac_instrucciones_desarrollo"];
                $_resp["instrucciones_cierre"] = $_fila["ac_instrucciones_cierre"];
                $_resp["descripcion"] = $_fila["ac_descripcion"];
                $_resp["consejos_practicos"] = $_fila["ac_consejos_practicos"];
                $_resp["fecha_creacion"] = $_fila["ac_fecha_creacion"];
                $_resp["publica_producto"] = $_fila["ac_publica_producto"];
                $_resp["revisa_pares"] = $_fila["ac_revisa_pares"];
                $_resp["id_complementaria"] = $_fila["ac_id_complementaria"];
                $_resp["instrucciones_producto"] = $_fila["ac_instrucciones_producto"];
                $_resp["instrucciones_revision"] = $_fila["ac_instrucciones_revision"];
                $_resp["aprendizaje_esperado"] = $_fila["ac_aprendizaje_esperado"];
                $_resp["evidencia_aprendizaje"] = $_fila["ac_evidencia_aprendizaje"];
                $_resp["tipo"] = $_fila["ac_tipo"];
                $_resp["archivos_actividad"] = null;
            }

            //Obtiene los archivos de la actividad
            $consulta2 = "SELECT " .
                    "a_id_archivo, " .
                    "a_nombre_archivo, " .
                    "a_solo_profesor, " .
                    "a_descripcion, " .
                    "a_observaciones " .
                    "FROM " .
                    "archivo " .
                    "WHERE " .
                    "a_id_actividad = '" . $id_actividad . "' AND " .
                    "(a_id_diseno_didactico is null OR a_id_diseno_didactico = '') ORDER BY a_orden ASC";

            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);

            if ($resultado2) {
                if (mysql_num_rows($resultado2) > 0) {
                    $_resp["archivos_actividad"] = array();
                    $i = 0;

                    while ($_fila2 = mysql_fetch_array($resultado2, MYSQL_BOTH)) {
                        $_resp["archivos_actividad"][$i]["id_archivo"] = $_fila2["a_id_archivo"];
                        $_resp["archivos_actividad"][$i]["nombre"] = $_fila2["a_nombre_archivo"];
                        $_resp["archivos_actividad"][$i]["solo_profesor"] = $_fila2["a_solo_profesor"];
                        $_resp["archivos_actividad"][$i]["descripcion"] = $_fila2["a_descripcion"];
                        $_resp["archivos_actividad"][$i]["observaciones"] = $_fila2["a_observaciones"];
                        $i++;
                    }
                }
                mysql_free_result($resultado2);
            } else {
                // ERROR MYSQL
            }
        } else {
            //No existe esta actividad
            $_resp = null;
        }
        mysql_free_result($resultado);
    } else {
        //ERROR MYSQL
    }
    return $_resp;
}

/**
 * Devuelve la lista de estudiantes asociados con la experiencia didáctica
 *
 * @author      Katherine Inalef - Kelluwen
 * @version     2010.03.04
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( nombre          => String,
 *                      usuario_twitter => String,
 *                      url_avatar      => String,
 *                      id_grupo        => Integer)
 */
function dbExpObtenerEstudiantesGrupo($id_experiencia, $id_grupo, $conexion) {
    $_resp = array();

    //Obtiene información sobre los estudiantes de una expriencia
    $consulta = "SELECT " .
            "U.u_nombre, " .
            "U.u_usuario, " .
            "U.u_url_imagen, " .
            "G.g_id_grupo " .
            "FROM " .
            "grupo G, usuario_grupo UG, usuario U " .
            "WHERE " .
            "G.g_id_experiencia = " . $id_experiencia . " AND " .
            "G.g_id_grupo = " . $id_grupo . " AND " .
            "G.g_id_grupo = UG.ug_id_grupo AND " .
            "UG.ug_id_usuario = U.u_id_usuario";
    
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["nombre"] = $_fila["u_nombre"];
                $_resp[$i]["usuario"] = $_fila["u_usuario"];
                $_resp[$i]["url_avatar"] = $_fila["u_url_imagen"];
                $i++;
            }
        } else {
            //No existen estudiantes para esta experiencia
            $_resp = null;
        }
        mysql_free_result($resultado);
    } else {
        //ERROR MYSQL
    }
    return $_resp;
}

function dbExpObtenerUsuarios($id_experiencia, $et_gemela, $conexion) {
    $_resp = array();

    $consulta = "(SELECT " .
            "U.u_id_usuario AS id, " .
            "U.u_nombre AS nombre, " .
            "U.u_usuario AS usuario, " .
            "U.u_usuario_tw AS usuario_tw, " .
            "U.u_url_imagen AS imagen, " .
            "U.u_email AS email, " .
            "E.ed_id_experiencia AS id_experiencia, " .
            "1 AS rol, " .
            "0 AS gemelo, " .
            "-1 AS id_grupo, " .
            "'' AS nombre_grupo, " .
            "'' AS et_grupo, " .
            "'' AS et_grupo_gemelo " .
            "FROM " .
            "experiencia_didactica E, usuario U " .
            "WHERE " .
            "E.ed_id_experiencia = " . $id_experiencia . " AND " .
            "E.ed_id_profesor = U.u_id_usuario AND " .
            "U.u_activo = 1) ";
    $consulta .= "UNION " .
            "(SELECT " .
            "U.u_id_usuario AS id, " .
            "U.u_nombre AS nombre, " .
            "U.u_usuario AS usuario, " .
            "U.u_usuario_tw AS usuario_tw, " .
            "U.u_url_imagen AS imagen, " .
            "U.u_email AS email,  " .
            "G.g_id_experiencia AS id_experiencia, " .
            "2 AS rol, " .
            "0 AS gemelo, " .
            "G.g_id_grupo AS id_grupo, " .
            "G.g_nombre AS nombre_grupo, " .
            "G.g_etiqueta AS et_grupo, " .
            "G.g_etiqueta_gemela AS et_grupo_gemelo " .
            "FROM " .
            "grupo G, usuario_grupo UG, usuario U " .
            "WHERE " .
            "G.g_id_experiencia = " . $id_experiencia . " AND " .
            "G.g_id_grupo = UG.ug_id_grupo AND " .
            "UG.ug_id_usuario = U.u_id_usuario AND " .
            "U.u_activo = 1 " .
            "ORDER BY G.g_nombre ASC, U.u_nombre ASC) ";
    $consulta .= "UNION " .
            "(SELECT " .
            "U.u_id_usuario AS id, " .
            "U.u_nombre AS nombre, " .
            "U.u_usuario AS usuario, " .
            "U.u_usuario_tw AS usuario_tw, " .
            "U.u_url_imagen AS imagen, " .
            "U.u_email AS email, " .
            "E.ed_id_experiencia AS id_experiencia, " .
            "1 AS rol, " .
            "1 AS gemelo, " .
            "-1 AS id_grupo, " .
            "'' AS nombre_grupo, " .
            "'' AS et_grupo, " .
            "'' AS et_grupo_gemelo " .
            "FROM " .
            "experiencia_didactica E, usuario U " .
            "WHERE " .
            "E.ed_id_experiencia <> " . $id_experiencia . " AND " .
            "E.ed_etiqueta_gemela = '" . $et_gemela . "' AND " .
            "E.ed_id_profesor = U.u_id_usuario AND " .
            "U.u_activo = 1) ";
    $consulta .= "UNION " .
            "(SELECT " .
            "U.u_id_usuario AS id, " .
            "U.u_nombre AS nombre, " .
            "U.u_usuario AS usuario, " .
            "U.u_usuario_tw AS usuario_tw, " .
            "U.u_url_imagen AS imagen, " .
            "U.u_email AS email,  " .
            "E.ed_id_experiencia AS id_experiencia, " .
            "2 AS rol, " .
            "1 AS gemelo, " .
            "G.g_id_grupo AS id_grupo, " .
            "G.g_nombre AS nombre_grupo, " .
            "G.g_etiqueta AS et_grupo, " .
            "G.g_etiqueta_gemela AS et_grupo_gemelo " .
            "FROM " .
            "grupo G, usuario_grupo UG, usuario U, experiencia_didactica E " .
            "WHERE " .
            "E.ed_id_experiencia <> " . $id_experiencia . " AND " .
            "E.ed_etiqueta_gemela = '" . $et_gemela . "' AND " .
            "G.g_id_experiencia = E.ed_id_experiencia AND " .
            "G.g_id_grupo = UG.ug_id_grupo AND " .
            "UG.ug_id_usuario = U.u_id_usuario AND " .
            "U.u_activo = 1 " .
            "ORDER BY G.g_nombre ASC, U.u_nombre ASC) ";
    $consulta .= "UNION " .
            "(SELECT " .
            "U.u_id_usuario AS id, " .
            "U.u_nombre AS nombre, " .
            "U.u_usuario AS usuario, " .
            "U.u_usuario_tw AS usuario_tw, " .
            "U.u_url_imagen AS imagen, " .
            "U.u_email AS email, " .
            "C.c_id_experiencia AS id_experiencia, " .
            "3 AS rol, " .
            "0 AS gemelo, " .
            "-1 AS id_grupo, " .
            "'' AS nombre_grupo, " .
            "'' AS et_grupo, " .
            "'' AS et_grupo_gemelo " .
            "FROM " .
            "usuario U, " .
            "colaborador C " .
            "WHERE " .
            "C.c_id_experiencia = " . $id_experiencia . " AND " .
            "C.c_id_colaborador = U.u_id_usuario);";
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id"] = $_fila["id"];
                $_resp[$i]["nombre"] = $_fila["nombre"];
                $_resp[$i]["usuario"] = $_fila["usuario"];
                $_resp[$i]["usuario_tw"] = $_fila["usuario_tw"];
                $_resp[$i]["imagen"] = $_fila["imagen"];
                $_resp[$i]["rol"] = $_fila["rol"];
                $_resp[$i]["gemelo"] = $_fila["gemelo"];
                $_resp[$i]["id_grupo"] = $_fila["id_grupo"];
                $_resp[$i]["nombre_grupo"] = $_fila["nombre_grupo"];
                $_resp[$i]["et_grupo"] = $_fila["et_grupo"];
                $_resp[$i]["et_grupo_gemelo"] = $_fila["et_grupo_gemelo"];
                $_resp[$i]["id_experiencia"] = $_fila["id_experiencia"];
                $i++;
            }
        } else {
            //No existen estudiantes para esta experiencia
            $_resp = null;
        }
        mysql_free_result($resultado);
    } else {
        //ERROR MYSQL
    }
    return $_resp;
}

function dbExpObtenerGrupos($id_experiencia, $conexion) {
    $_resp = array();

    //Obtiene información sobre los grupos de una expriencia

    $consulta = "SELECT " .
            "g_id_grupo, " .
            "g_nombre, " .
            "g_etiqueta, " .
            "g_etiqueta_gemela " .
            "FROM " .
            "grupo " .
            "WHERE " .
            "g_id_experiencia = " . $id_experiencia . ";";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id"] = $_fila["g_id_grupo"];
                $_resp[$i]["nombre"] = $_fila["g_nombre"];
                $_resp[$i]["etiqueta"] = $_fila["g_etiqueta"];
                $_resp[$i]["etiqueta_gemela"] = $_fila["g_etiqueta_gemela"];
                $i++;
            }
        } else {
            //No existen estudiantes para esta experiencia
            $_resp = null;
        }
        mysql_free_result($resultado);
    } else {
        $_resp = null;
    }
    return $_resp;
}

function dbExpObtenerActividadesRealizadas($id_experiencia, $conexion) {
    $_resp = null;

    //Obtiene la ultima actividad realizada y su estado
    $consulta = "SELECT " .
            "AC.ac_id_actividad," .
            "AC.ac_nombre, " .
            "AC.ac_iniciadora, " .
            "AC.ac_finalizadora, " .
            "EA.ea_id_exp_actividad, " .
            "EA.ea_estado," .
            "EA.ea_fecha_termino " .
            "FROM " .
            "actividad AC, experiencia_didactica ED, exp_etapa EE, exp_actividad EA " .
            "WHERE " .
            "ED.ed_id_experiencia = " . $id_experiencia . " AND " .
            "ED.ed_id_experiencia = EE.ee_id_experiencia AND " .
            "EE.ee_id_exp_etapa = EA.ea_id_exp_etapa AND " .
            "EA.ea_id_actividad = AC.ac_id_actividad AND " .
            "EA.ea_estado = 3 " .
            "ORDER BY " .
            "EA.ea_estado ASC, EA.ea_fecha_termino DESC";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_actividad"] = $_fila["ac_id_actividad"];
                $_resp[$i]["iniciadora"] = $_fila["ac_iniciadora"];
                $_resp[$i]["finalizadora"] = $_fila["ac_finalizadora"];
                $_resp[$i]["nombre"] = $_fila["ac_nombre"];
                $_resp[$i]["id_exp_act"] = $_fila["ea_id_exp_actividad"];
                $_resp[$i]["estado"] = $_fila["ea_estado"];
                $i++;
            }
        }
        mysql_free_result($resultado);
    } else {
        //ERROR MYSQL
    }
    return $_resp;
}

/**
 * Función que devuelves las actividades finalizadas y la actividad en curso, para ser 
 * desplegadas en la Bitácora
 * @author Katherine Inalef - Kelluwen
 * @param int $id_experiencia
 * @param resource $conexion
 * @return array 
 */
function dbExpObtenerActividades($id_experiencia, $conexion) {
    $_resp = array();

    //Obtiene la ultima actividad realizada y su estado
    $consulta = "SELECT " .
            "AC.ac_id_actividad," .
            "AC.ac_nombre, " .
            "EA.ea_id_exp_actividad, " .
            "EA.ea_estado," .
            "EA.ea_fecha_termino, " .
            "AC.ac_publica_producto, " .
            "AC.ac_revisa_pares " .
            "FROM " .
            "actividad AC, experiencia_didactica ED, exp_etapa EE, exp_actividad EA " .
            "WHERE " .
            "ED.ed_id_experiencia = " . $id_experiencia . " AND " .
            "ED.ed_id_experiencia = EE.ee_id_experiencia AND " .
            "EE.ee_id_exp_etapa = EA.ea_id_exp_etapa AND " .
            "EA.ea_id_actividad = AC.ac_id_actividad AND " .
            "EA.ea_fecha_inicio IS NOT NULL " .
            "ORDER BY " .
            "EA.ea_estado ASC, EA.ea_fecha_termino DESC";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    $i = 0;
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["actividad_id"] = $_fila["ac_id_actividad"];
                $_resp[$i]["nombre_actividad"] = $_fila["ac_nombre"];
                $_resp[$i]["id_expact"] = $_fila["ea_id_exp_actividad"];
                $_resp[$i]["estado"] = $_fila["ea_estado"];
                $_resp[$i]["fecha_termino"] = $_fila["ea_fecha_termino"];
                $_resp[$i]["ultima_publica_producto"] = $_fila["ac_publica_producto"];
                $_resp[$i]["ultima_revisa_pares"] = $_fila["ac_revisa_pares"];
                $i++;
            }
        } else {
            //No existen actividades comenzadas o finalizadas en esta experiencia
            $_resp[$i]["actividad_id"] = -1;
            $_resp[$i]["id_expact"] = -1;
            $_resp[$i]["nombre_actividad"] = "";
            $_resp[$i]["estado"] = 1;
        }
        mysql_free_result($resultado);
    } else {
        //ERROR MYSQL
    }
    return $_resp;
}

/**
 * Devuelve información sobre el avance de la experiencia didáctica
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.03.17
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( ultima_actividad               => String,
 *                      estado_ultima_actividad        => Integer,
 *                      cant_actividades_finalizadas   => Integer,
 *                      suma_t_actividades_finalizadas => Integer)
 */
function dbExpObtenerAvance($id_experiencia, $conexion) {
    $_resp = array();

    //Obtiene la ultima actividad realizada y su estado
    $consulta = "SELECT " .
            "AC.ac_id_actividad," .
            "AC.ac_nombre, " .
            "EA.ea_id_exp_actividad, " .
            "EA.ea_estado," .
            "EA.ea_fecha_termino, " .
            "AC.ac_publica_producto, " .
            "AC.ac_revisa_pares " .
            "FROM " .
            "actividad AC, experiencia_didactica ED, exp_etapa EE, exp_actividad EA " .
            "WHERE " .
            "ED.ed_id_experiencia = " . $id_experiencia . " AND " .
            "ED.ed_id_experiencia = EE.ee_id_experiencia AND " .
            "EE.ee_id_exp_etapa = EA.ea_id_exp_etapa AND " .
            "EA.ea_id_actividad = AC.ac_id_actividad AND " .
            "EA.ea_fecha_inicio IS NOT NULL " .
            "ORDER BY " .
            "EA.ea_estado ASC, EA.ea_fecha_termino DESC";
   
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
            if ($_fila) {
                $_resp["ultima_actividad_id"] = $_fila["ac_id_actividad"];
                $_resp["ultima_actividad"] = $_fila["ac_nombre"];
                $_resp["ultima_actividad_id_expact"]= $_fila["ea_id_exp_actividad"];
                $_resp["estado_ultima_actividad"]       = $_fila["ea_estado"];
                $_resp["fecha_termino"]                 = $_fila["ea_fecha_termino"];
                $_resp["ultima_publica_producto"]              = $_fila["ac_publica_producto"];
                $_resp["ultima_revisa_pares"]                  = $_fila["ac_revisa_pares"];
                $_resp["suma_sesiones_estimadas"]       = null;
                $_resp["cant_actividades_finalizadas"]  = null;
                $_resp["suma_t_actividades_finalizadas"]= null;
            }
        }
        else {
            //No existen actividades comenzadas o finalizadas en esta experiencia
            $_resp["ultima_actividad_id"]           = -1;
            $_resp["ultima_actividad_id_expact"]    = -1;
            $_resp["ultima_actividad"]              = "";
            $_resp["estado_ultima_actividad"]       = 1;
            $_resp["suma_sesiones_estimadas"]       = null;
            $_resp["cant_actividades_finalizadas"]  = null;
            $_resp["suma_t_actividades_finalizadas"]= null;

        }
        mysql_free_result($resultado);
    }
    else {
        //ERROR MYSQL
    }

    //Obtiene las sesiones estimadas por cada etapa
    $consulta2 = "SELECT ".
                    "E.e_sesiones_estimadas ".
                "FROM ".
                    "etapa E, experiencia_didactica ED, diseno_didactico DD ".
                "WHERE ".
                    "ED.ed_id_experiencia = $id_experiencia AND ".
                    "ED.ed_id_diseno_didactico = DD.dd_id_diseno_didactico AND ".
                    "DD.dd_id_diseno_didactico = E.e_id_diseno_didactico";

    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);

    if($resultado2) {
        if(mysql_num_rows($resultado2) >0) {
            $suma_sesiones=0;
            while ($_fila2=mysql_fetch_array($resultado2,MYSQL_BOTH)) {
                $suma_sesiones = $suma_sesiones +$_fila2["e_sesiones_estimadas"];
            }
            $_resp["suma_sesiones_estimadas"] = $suma_sesiones;
        }
        else {
            //No existen sesiones estimadas para las etapas del diseño didáctico
        }
        mysql_free_result($resultado2);
    }
    else {
        //ERROR MYSQL
    }

    //Obtiene las actividades finalizadas en la experiencia didactica
    $consulta3 = "SELECT ".
                    "AC.ac_horas_estimadas ".
                 "FROM ".
                    "actividad AC, experiencia_didactica ED, exp_etapa EE, exp_actividad EA ".
                 "WHERE ".
                    "ED.ed_id_experiencia = $id_experiencia AND ".
                    "ED.ed_id_experiencia = EE.ee_id_experiencia AND ".
                    "EE.ee_id_exp_etapa = EA.ea_id_exp_etapa AND ".
                    "EA.ea_estado = 3 AND ".
                    "EA.ea_id_actividad = AC.ac_id_actividad";

    $resultado3 = dbEjecutarConsulta($consulta3, $conexion);

    if($resultado3) {
        if(mysql_num_rows($resultado3) >0) {
            $cantidad_actividades=0;
            $suma_t_actividades=0;
            while ($_fila3=mysql_fetch_array($resultado3,MYSQL_BOTH)) {
                $suma_t_actividades = $suma_t_actividades +$_fila3["ac_horas_estimadas"];
                $cantidad_actividades++;

            }
            $_resp["cant_actividades_finalizadas"]  = $cantidad_actividades;
            $_resp["suma_t_actividades_finalizadas"]= $suma_t_actividades;
        }
        else {
            //No existen actividades finalizadas
        }
        mysql_free_result($resultado3);
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}

/**
 * Escribe en las tablas correspondientes la experiencia, sus etapas y actividades
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.13.08
 * @param       Integer  $id_profesor Idenificador de usuario profesor
 * @param       Integer  $id_diseno_didactico Identificador de diseño didáctico
 * @param       String   $ed_localidad Localidad
 * @param       String   $ed_curso Curso
 * @param       String   $ed_colegio Colegio
 * @param       resource $conexion Identificador de enlace a MySQL
 *
 */
function dbExpInscribirExperiencia($id_profesor, $id_diseno_didactico, $ed_localidad, $ed_curso, $ed_colegio, $ed_semestre, $ed_anio, $conexion) {
    $resp = false;
    $codigo_gemelo="";

    $consulta1 = "SELECT distinct(ed_etiqueta_gemela) " .
                "FROM experiencia_didactica " .
                "WHERE ed_id_diseno_didactico = " . $id_diseno_didactico . " " .
                "AND ed_semestre = '" . $ed_semestre . "' " .
                "AND ed_anio =" . $ed_anio;

    $resultado = dbEjecutarConsulta($consulta1, $conexion);
    if ($resultado) {
        if (mysql_affected_rows($conexion) == 1) {
            if($fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $codigo_gemelo = $fila["ed_etiqueta_gemela"];
            }
        }
    }
    else {
        //ERROR MYSQL
    }
    $consulta = "INSERT INTO experiencia_didactica (" .
            "ed_id_profesor, " .
            "ed_id_diseno_didactico, " .
            "ed_localidad, " .
            "ed_curso, " .
            "ed_colegio, " .
            "ed_semestre, " .
            "ed_anio " .
            ") " .
            "VALUES (" .
            $id_profesor . ", " .
            $id_diseno_didactico . ", " .
            "'" . $ed_localidad . "', " .
            "'" . $ed_curso . "', " .
            "'" . $ed_colegio . "', ".
            "'" . $ed_semestre . "', ".
            $ed_anio." )";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_affected_rows($conexion) > 0) {
            $id_experiencia = mysql_insert_id($conexion);
            $filas_afectadas = 1;
            $filas_afectadas_gem = 1;

            $consulta9 = "INSERT INTO usuario_experiencia(" .
                    "ue_id_usuario, " .
                    "ue_id_experiencia, " .
                    "ue_rol_usuario )" .
                    "VALUES (" .
                    $id_profesor . ", " .
                    $id_experiencia . ", " .
                    "1 )";
            $resultado9 = dbEjecutarConsulta($consulta9, $conexion);

            while ($filas_afectadas != 0) {
                $codigo = generarCodigo(8);

                $consulta2 = "SELECT " .
                        "ed_id_experiencia " .
                        "FROM " .
                        "experiencia_didactica " .
                        "WHERE " .
                        "ed_etiqueta ='" . $codigo . "'";

                $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
                if ($resultado2) {
                    $filas_afectadas = mysql_num_rows($resultado2);
                } else {
                    //ERROR MYSQL
                }
            }
            if ($codigo_gemelo == "") {
                while ($filas_afectadas_gem != 0) {
                    $codigo_gemelo = generarCodigo(8);

                    $consulta8 = "SELECT " .
                            "ed_id_experiencia " .
                            "FROM " .
                            "experiencia_didactica " .
                            "WHERE " .
                            "ed_etiqueta_gemela ='" . $codigo_gemelo . "'";

                    $resultado8 = dbEjecutarConsulta($consulta8, $conexion);
                    if ($resultado8) {
                        $filas_afectadas_gem = mysql_num_rows($resultado8);
                    } else {
                        //ERROR MYSQL
                    }
                }
            }
            $consulta3 = "UPDATE " .
                    "experiencia_didactica " .
                    "SET " .
                    "ed_etiqueta = '" . $codigo . "' ," .
                    "ed_etiqueta_gemela = '" . $codigo_gemelo . "' " .
                    "WHERE " .
                    "ed_id_experiencia =" . $id_experiencia;

            $resultado3 = dbEjecutarConsulta($consulta3, $conexion);
            if ($resultado3) {
                if (mysql_affected_rows($conexion) > 0) {
                    $consulta4 = "SELECT " .
                            "e_id_etapa " .
                            "FROM " .
                            "etapa " .
                            "WHERE " .
                            "e_id_diseno_didactico=" . $id_diseno_didactico;
                    $resultado4 = dbEjecutarConsulta($consulta4, $conexion);
                    if ($resultado4) {
                        if (mysql_num_rows($resultado4) > 0) {
                            while ($_fila = mysql_fetch_array($resultado4, MYSQL_BOTH)) {
                                $id_etapa = $_fila["e_id_etapa"];
                                $consulta5 = "INSERT INTO exp_etapa (" .
                                        "ee_id_experiencia, " .
                                        "ee_id_etapa " .
                                        ")" .
                                        "VALUES (" .
                                        $id_experiencia . ", " .
                                        $id_etapa . ")";
                                $resultado5 = dbEjecutarConsulta($consulta5, $conexion);
                                if ($resultado5) {
                                    if (mysql_affected_rows($conexion) > 0) {
                                        $id_exp_etapa = mysql_insert_id($conexion);
                                        $consulta6 = "SELECT " .
                                                "ac_id_actividad " .
                                                "FROM " .
                                                "actividad " .
                                                "WHERE " .
                                                "ac_id_etapa =" . $id_etapa;
                                        $resultado6 = dbEjecutarConsulta($consulta6, $conexion);
                                        if ($resultado6) {
                                            if (mysql_num_rows($resultado6) > 0) {
                                                while ($_fila2 = mysql_fetch_array($resultado6, MYSQL_BOTH)) {
                                                    $id_actividad = $_fila2["ac_id_actividad"];
                                                    $consulta7 = "INSERT INTO exp_actividad (" .
                                                            "ea_estado ," .
                                                            "ea_id_exp_etapa, " .
                                                            "ea_id_actividad " .
                                                            ")" .
                                                            "VALUES (" .
                                                            "1, " .
                                                            $id_exp_etapa . ", " .
                                                            $id_actividad . ")";
                                                    $resultado7 = dbEjecutarConsulta($consulta7, $conexion);
                                                }
                                            }
                                        } else {
                                            //ERROR MYSQL
                                        }
                                    }
                                } else {
                                    //ERROR MYSQL
                                }
                            }
                            $resp = true;
                        }
                    } else {
                        //ERROR MYSQL
                    }
                }
            } else {
                //ERROR MYSQL
            }
        }
    } else {
        //ERROR MYSQL
    }
    return $resp;
}

/**
 * Escribe en la tabla usuario_experiencia el usuario correspondiente con rol de estudiante
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.13.08
 * @param       Integer  $id_profesor Idenificador de usuario profesor
 * @param       Integer  $id_diseno_didactico Identificador de diseño didáctico
 * @param       String   $ed_localidad Localidad
 * @param       String   $ed_curso Curso
 * @param       String   $ed_colegio Colegio
 * @param       resource $conexion Identificador de enlace a MySQL
 *
 */
function dbExpInscribirExperienciaCodigo($id_usuario,$codigo_secreto,$conexion) {
    $resp=-1;
    $consulta = "SELECT ".
                    "ed_id_experiencia ".
                "FROM ".
                    "experiencia_didactica ".
                "WHERE ".
                    "ed_etiqueta ='".$codigo_secreto."'";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_affected_rows($conexion) == 1) {
            if ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $codigo_experiencia = $_fila["ed_id_experiencia"];
                $consulta2 = "INSERT INTO usuario_experiencia (" .
                                "ue_id_usuario, " .
                                "ue_id_experiencia, " .
                                "ue_rol_usuario " .
                                ") " .
                             "VALUES (" .
                                $id_usuario . ", " .
                                $codigo_experiencia . ", " .
                                "2)";

                $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
                if ($resultado2) {
                    if (mysql_affected_rows($conexion) > 0) {
                        $resp = 1;
                    }
                }
            }
        }
    }

    return $resp;
}

/**
 * Devuelve la lista de usuarios asociados a una experiencia, antes de que sea asignado a algún grupo.
 * @author      Carolina Aros - Kelluwen
 * @version     2010.09.01
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( id_usuario                => Integer,
 *                      nombre_usuario            => String,
 *                      url_imagen                => String)
 */
function dbExpObtenerEstudiantesRegistrados($id_experiencia,$conexion) {
    $_resp=null;

    $consulta = "SELECT U.u_id_usuario, U.u_nombre, U.u_url_imagen ".
                "FROM usuario_experiencia UE, usuario U ".
                "WHERE ".
                    "UE.ue_id_experiencia =".$id_experiencia." AND ".
                    "UE.ue_rol_usuario = 2 AND ".
                    "UE.ue_id_usuario = U.u_id_usuario;";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"]      =  $_fila["u_id_usuario"];
                $_resp[$i]["nombre_usuario"]  =  $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]      =  $_fila["u_url_imagen"];
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

/**
 * Devuelve los alumnos asociados a un grupo.
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer  $id_grupo Código en la base de datos para la instancia grupo
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array $_resp datos de los alumnos asociados al grupo
 */
function dbExpObtenerEstudiantesPorGrupo($id_grupo,$conexion) {
    $_resp=null;

    $consulta = "SELECT ".
                    "U.u_id_usuario, ".
                    "U.u_nombre, ".
                    "U.u_url_imagen, ".
                    "U.u_usuario ".
                "FROM ".
                    "usuario U, ".
                    "usuario_grupo UG ".
                "WHERE ".
                    "UG.ug_id_grupo =".$id_grupo." AND ".
                    "UG.ug_id_usuario = U.u_id_usuario ";

    $resultado = dbEjecutarConsulta($consulta,$conexion);

    if($resultado) {

        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"]      =  $_fila["u_id_usuario"];
                $_resp[$i]["nombre_usuario"]  =  $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]      =  $_fila["u_url_imagen"];
                $_resp[$i]["usuario"]         =  $_fila["u_usuario"];
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

/**
 * Devuelve los usuarios asignados en un grupo
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer  $id_grupo Código en la base de datos para la instancia grupo
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array $_resp informacion de los estudiantes y el grupo
 *
 */
function dbExpObtenerEstudiantesAsignados($id_grupo, $conexion) {
    $_resp = null;

    $consulta = "SELECT " .
                    "UG.ug_id_usuario, " .
                    "UG.ug_id_grupo " .
                "FROM " .
                    "usuario_grupo UG " .
                "WHERE " .
                    "UG.ug_id_grupo =" . $id_grupo . " ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"] = $_fila["ug_id_usuario"];
                $_resp[$i]["id_grupo"] = $_fila["ug_id_grupo"];
                $i++;
            }
        } else {
            // No hay estudiantes asociados a la experiencia o no existe la experiencia.
        }
    } else {
        //ERROR MYSQL
    }
    return $_resp;
}
function dbExpObtenerTotalEstudiantesAsignados($_grupos,$conexion) {
    $contador = 0;
    foreach ($_grupos as $grupo) {
        $estu = dbExpObtenerEstudiantesAsignados($grupo["id_grupo"], $conexion);

        $contador+=count($estu);
    }
    return $contador;
}
/**
 * Obtiene los grupos asociados a una experiencia
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array $_resp informacion de cada grupo
 *
 *
 */
function dbExpGruposExperiencia($id_experiencia,$conexion) {
    $_resp=null;

    $consulta = "SELECT ".
                    "G.g_id_grupo, ".
                    "G.g_nombre, ".
                    "G.g_etiqueta, ".
                    "G.g_etiqueta_gemela ".
                "FROM ".
                    "grupo G ".
                "WHERE ".
                    "G.g_id_experiencia = ".$id_experiencia." ".
                "ORDER BY G.g_id_grupo ASC";
//echo $consulta;
    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {

        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_grupo"]      =  $_fila["g_id_grupo"];
                $_resp[$i]["nombre_g"]  =  $_fila["g_nombre"];
                $_resp[$i]["etiqueta_g"]  =  $_fila["g_etiqueta"];
                $_resp[$i]["etiqueta_gemela_g"]  =  $_fila["g_etiqueta_gemela"];
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

/**
 * Genera los grupos de una experiencia determinada
 * @author      Carolina Aros - Kelluwen
 * @version     2010.09.02
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       Integer  $cantidad_grupos Cantidad de grupos que se generarán para esa experiencia
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Boolean true si fue realizada con éxito y false si no
 */
function dbExpGenerarGrupos($id_experiencia,$cantidad_grupos,$conexion) {
    $resp=false;
    $contador=0;

    for ($i=1; $i<=$cantidad_grupos ; $i++) {

        $filas_afectadas=1;
        while($filas_afectadas!=0) {
            $etiqueta_gemela=generarCodigo(8);

            $consulta = "SELECT ".
                            "g_id_grupo ".
                        "FROM ".
                            "grupo ".
                        "WHERE ".
                            "g_etiqueta_gemela ='".$etiqueta_gemela."'";
            $resultado = dbEjecutarConsulta($consulta, $conexion);

            if($resultado) {
                $filas_afectadas=mysql_num_rows($resultado);
            }
            else {
                //ERROR MYSQL
            }
        }

        $consulta2="INSERT INTO grupo (".
                "g_nombre, ".
                "g_etiqueta_gemela, ".
                "g_id_experiencia )".
                "VALUES (".

                "'G".$i."', '".
                $etiqueta_gemela."', ".

                $id_experiencia.") ";

        $resultado2 = dbEjecutarConsulta($consulta2, $conexion);

        if($resultado2) {
            if(mysql_affected_rows($conexion) > 0) {
                $contador++;
            }
        }
        else {
            //ERROR MYSQL
        }
    }
    if ($contador==$cantidad_grupos){
        $resp=true;
    }
    else{
        $resp=false;
    }

    return $resp;
}

/**
 * Elimina los grupos de una experiencia determinada
 * @author      Carolina Aros - Kelluwen
 * @version     2010.09.03
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Boolean true si la operación fue realizada con éxito y false si no
 */

function dbExpEliminarGrupos($id_experiencia,$conexion) {
    $resp=false;

    $consulta = "DELETE FROM usuario_grupo ".
                "WHERE ug_id_grupo IN (".
                    "SELECT g_id_grupo ".
                    "FROM grupo ".
                    "WHERE g_id_experiencia =".$id_experiencia." )";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {

            $consulta2 = "DELETE FROM grupo ".
                         "WHERE g_id_experiencia =".$id_experiencia."";

            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
            if($resultado2) {
                if(mysql_affected_rows($conexion) > 0) {
                    $resp=true;
                }
            }
            else {
                //ERROR MYSQL
            }
    }
    else {
        //ERROR MYSQL
    }
    return $resp;
}

/**
 * Elimina las asignaciones de los estudiantes a los grupos de una experiencia didactica.
 * @author      Jose Carrasco - Kelluwen
 * @version     2010.09.22
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Boolean $resp devuelve si la Eliminacion fue realizada con exito
 */
function dbExpEliminarAsignacion($id_experiencia,$conexion) {
    $resp=false;

    $consulta = "DELETE FROM usuario_grupo ".
                "WHERE ug_id_grupo IN (".
                    "SELECT g_id_grupo ".
                    "FROM grupo ".
                    "WHERE g_id_experiencia =".$id_experiencia." )";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
                if(mysql_affected_rows($conexion) > 0) {
                    $resp=true;
                }

            else {
                //ERROR MYSQL
            }


    }
    else {
        //ERROR MYSQL
    }
    return $resp;
}

/**
 * Asigna los usuarios a sus respectivos grupos
 * @author      Carolina Aros - Jose Carrasco - Kelluwen
 * @version     2010.09.03
 * @param       Array ( $id_usuario => Integer,
 *                      $id_grupo   => Integer)
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Boolean true si la operación fue realizada con éxito y false si no
 */
function dbExpAsignarUsuarioGrupo($id_experiencia,$_usuario_grupo,$conexion) {
    $resp=false;
    $n_transacciones = count($_usuario_grupo);
    $i=0;
    foreach($_usuario_grupo as $usuarios){
         $consulta = "INSERT INTO usuario_grupo(".
                    "ug_id_usuario, ".
                    "ug_id_grupo) ".
                 "VALUES (".
                     "".$usuarios["est"].",".
                     $usuarios["gru"].");";
         echo "\n".$consulta."\n";
        $resultado = dbEjecutarConsulta($consulta, $conexion);
        if($resultado){
            $i+=1;
        }
    }
    echo " CONSULTAS HECHAS : ".$i;
    echo " N TRANS: ".$n_transacciones;
    if($i==$n_transacciones) {
        $resp=true;
    }
    else {
        //ERROR MYSQL
    }
    return $resp;
}

/**
 * Devuelve link referido a los Documentos de una Actividad en Particular
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.08.27
 * @param       Integer  $id_actividad Código en la base de datos para una actividad
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( id_archivo             => Integer,
 *                      nombre                  => String,
 *                      solo_profesor           => Integer,
 *                      descripcion             => String,
 *                      observaciones           => String)
 */
function dbDisObtieneArchivosActividad($id_actividad,$conexion) {
    $_resp=null;

    $consulta = "SELECT ".
                    "a_id_archivo, ".
                    "a_nombre_archivo, ".
                    "a_solo_profesor, ".
                    "a_descripcion, ".
                    "a_observaciones ".
                "FROM ".
                    "archivo ".
                "WHERE ".
                    "a_id_actividad = '".$id_actividad."' AND ".
                    "(a_id_diseno_didactico is null OR a_id_diseno_didactico = '') ORDER BY a_orden ASC";

    $resultado = dbEjecutarConsulta($consulta,$conexion);

    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_archivo"]      =  $_fila["a_id_archivo"];
                $_resp[$i]["nombre"]          =  $_fila["a_nombre_archivo"];
                $_resp[$i]["solo_profesor"]   =  $_fila["a_solo_profesor"];
                $_resp[$i]["descripcion"]     =  $_fila["a_descripcion"];
                $_resp[$i]["observaciones"]   =  $_fila["a_observaciones"];
                $i++;
            }
        }
        else {
            // No documentos para la actividad
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}

/**
 * Obtiene los grupos creados desde la Base de Datos
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.08.27
 * @param       Integer  $id_exp_didac Código en la base de datos para una experiencia didactica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array $_resp Identificadores y Nombres de los grupos
 */
function dbConfObtieneGrupos($id_exp_didac,$conexion) {
    $_resp=null;

    $consulta = "SELECT ".
                    "G.g_id_grupo, ".
                    "G.g_nombre ".
                "FROM ".
                    "grupo G ".
                "WHERE ".
                    "G.g_id_experiencia = '".$id_exp_didac."' ".
                "ORDER BY G.g_id_grupo ASC";
echo $consulta;
    $resultado = dbEjecutarConsulta($consulta,$conexion);
echo 'numero filas='.mysql_num_rows($resultado);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_grupo"]      =  $_fila["g_id_grupo"];
                $_resp[$i]["nombre_grupo"]          =  $_fila["g_nombre"];
                $i++;
            }
        }
        else {
        }
    }
    else {
        //ERROR MYSQL
    }
    return $_resp;
}

/**
 * CAMBIA LAS ETIQUETA GEMELAS REQUERIDAS
 * @param integer $id_exp_didac
 * @param String $etiqueta_gemela
 * @param resource $conexion Identificador de enlace a MySQL
 * @return integer
 */
function dbCambiarEtiquetaGemela($id_exp_didac,$etiqueta_gemela,$conexion){

    $consulta = "UPDATE ".
                 " experiencia_didactica ".
                 "SET ".
                 "  ea_estado = ".$etiqueta_gemela." ".
                 "WHERE ".
                    "ed_id_experiencia = ".$id_exp_didac.";";
    $resultado1 = dbEjecutarConsulta($consulta, $conexion);
    if($resultado1) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}

/**
 * Inserta exp_actividad en tabla re_act_desecha
 * @author Sergio Bustamante  - Kelluwen
 * @param integer $id_exp_act
 * @param resource $conexion Identificador de enlace a MySQL
 * @return integer
 */
// retorna la cantidad de filas afectadas o -1 en caso de error
function dbInsertaDeshecha($id_exp_act,$conexion){
    $consulta = "INSERT INTO re_act_deshecha(".
                                                                                "rad_id_exp_actividad, ".
                                                                                "rad_fecha) ".
                                         "VALUES (".
                                                                "".$id_exp_act.",
                                                                     sysdate());";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado1) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}

/**
 * Deshace la ejecución de una actividad
 * @author Daniel Guerra - Kelluwen
 * @param integer $id_exp_act
 * @param resource $conexion Identificador de enlace a MySQL
 * @return integer
 */
// retorna la cantidad de filas afectadas o -1 en caso de error
function dbDeshacerActividad($id_exp_act,$conexion){
    $consulta1 = "DELETE FROM ".
                 " comentario_ea ".
                 "WHERE ".
                    "cea_id_exp_actividad = ".$id_exp_act.";";
    $consulta2 = "UPDATE ".
                    "exp_actividad ".
                 "SET ".
                    "ea_estado = 1, ".
                    "ea_fecha_inicio = NULL, ".
                    "ea_fecha_termino = NULL ".
                 "WHERE ".
                    "ea_id_exp_actividad = ".$id_exp_act.";";

    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);
    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);

    dbInsertaDeshecha($id_exp_act,$conexion);
    if($resultado2) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
/**
 * Cambia el estado de una actividad a finalizada
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_exp_act
 * @param resource $conexion Identificador de enlace a MySQL
 * @return integer
 */
// retorna la cantidad de filas afectadas o -1 en caso de error
function dbTerminarActividad($id_exp_act,$conexion){
    $consulta = "UPDATE ".
                    "exp_actividad ".
                "SET ".
                    "ea_estado = 3, ".
                    "ea_fecha_termino = now() ".
                "WHERE ".
                    "ea_id_exp_actividad = ".$id_exp_act.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
/**
 * Finaliza la ejecución de un DD poniendo la fecha de termino
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_exp_act
 * @param integer $id_experiencia
 * @param resource $conexion Identificador de enlace a MySQL
 * @return integer
 */
function dbTerminarEjecucionDD($id_experiencia, $conexion){
    $consulta = "UPDATE ".
                    "experiencia_didactica ".
                "SET ".
                    "ed_fecha_termino = now() ".
                "WHERE ".
                    "ed_id_experiencia = ".$id_experiencia."; ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
/**
 * Inicia la ejecución de un DD poniendo la fecha de inicio
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_experiencia
 * @param resource $conexion Identificador de enlace a MySQL
 * @return integer
 */
function dbIniciarEjecucionDD($id_experiencia, $conexion){
    $consulta = "UPDATE ".
                    "experiencia_didactica ".
                "SET ".
                    "ed_fecha_inicio = now() ".
                "WHERE ".
                    "ed_id_experiencia = ".$id_experiencia."; ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
/**
 * Verifica para una actividad, si es que esta es una actividad finalizadora
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_exp_actividad identificador de la actividad de la experiencia
 * @param resource $conexion Identificador de enlace a MySQL
 * @return Array (finalizadora => integer)
 */
function dbVerificarActividadFinalizadora($id_exp_actividad, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "ac.ac_finalizadora ".
                 "FROM ".
                    "exp_actividad ea, actividad ac ".
                 "WHERE ".
                   "ea.ea_id_exp_actividad  = '".$id_exp_actividad."' AND ".
                   "ea.ea_id_actividad      = ac.ac_id_actividad  ";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp["finalizadora"]      = $_fila["ac_finalizadora"];
            }
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
 * Verifica si la actividad corresponde a una actividada iniciadora
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_exp_actividad
 * @param resource $conexion Identificador de enlace a MySQL
 * @return Array (iniciadora => integer)
 */
function dbVerificarActividadIniciadora($id_exp_actividad, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "ac.ac_iniciadora ".
                 "FROM ".
                    "exp_actividad ea, actividad ac ".
                 "WHERE ".
                   "ea.ea_id_exp_actividad  = '".$id_exp_actividad."' AND ".
                   "ea.ea_id_actividad      = ac.ac_id_actividad  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp["iniciadora"]      = $_fila["ac_iniciadora"];
            }
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
 * Inicia una actividad, retorna la cantidad de filas afectadas o -1 en caso de error
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.08.19
 * @param       Integer  $id_experiencia Código en la base de datos para la instancia experiencia didáctica
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( ultima_actividad               => String,
 *                      estado_ultima_actividad        => Integer,
 *                      cant_actividades_finalizadas   => Integer,
 *                      suma_t_actividades_finalizadas => Integer)
 */
function dbIniciarActividad($id_actividad,$id_exp_act, $id_exp_etapa,$etiqueta_act,$conexion) {
    $consulta = "UPDATE ".
                    "exp_actividad ".
                 "SET ".
                    "ea_estado = 2, ".
                    "ea_fecha_inicio = NOW() ,".
                    "ea_etiqueta = ".$etiqueta_act." ".
                 "WHERE ".
                    "ea_id_actividad =".$id_actividad." AND ".
                    "ea_id_exp_etapa=".$id_exp_etapa;
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado && mysql_affected_rows() == 1) {
        return $id_exp_act;
    }
    else {
        return -1;
    }
}

/**
 * Devuelve lista de comentarios asociados a una actividad
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.04.12
 * @param       Integer  $id_actividad Código en la base de datos para la instancia actividad
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Array ( id_comentario  => Integer,
 *                      nombre_usuario => String,
 *                      comentario     => String,
 *                      fecha          => Date )
 */
function dbObtenerComentariosActividad($id_actividad, $conexion){
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
                     "MDM.mdmj_tipo_mensaje = '7' AND  ".
                     "MDM.mdmj_id_usuario = U.u_id_usuario AND  ".
                     "MDM.mdmj_id_actividad =".$id_actividad."
                     ORDER BY mdmj_fecha DESC;";

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
/**
 * Obtiene la lista de comentarios asociados a un Diseño Didáctico
 * @author Daniel Guerra - Kelluwen
 * @param integer $id_dd
 * @param integer $id_exp
 * @param resource $conexion Identificador de enlace a MySQL
 * @return Array (id_ comentario    => integer,
 *                  usuario         => String,
 *                  nombre_usuario  => String,
 *                  url_imagen      => String,
 *                  localidad       => String,
 *                  curso           => String,
 *                  colegio         => String,
 *                  comentario      => String,
 *                  fecha           => String)
 */

function dbObtenerComentariosDD($id_dd, $id_exp, $conexion) {
    $_resp=array();
    $consulta = "SELECT ".
                    "CDD.cdd_id_comentario, ".
                    "CDD.cdd_usuario, ".
                    "CDD.cdd_nombre_usuario, ".
                    "U.u_url_imagen, ".
                    "COALESCE(ED.ed_localidad,'') as localidad, ".
                    "COALESCE(ED.ed_curso,'') as curso, ".
                    "COALESCE(ED.ed_colegio,'') as colegio, ".
                    "CDD.cdd_comentario, ".
                    "CDD.cdd_fecha ".
                 "FROM ".
                    "usuario U, comentario_dd CDD ".
                 "LEFT JOIN ".
                    "experiencia_didactica ED ".
                 "ON ".
                    "ED.ed_id_experiencia = CDD.cdd_id_experiencia ".
                 "WHERE ".
                    "CDD.cdd_id_diseno_didactico = ".$id_dd." AND ".
                    "U.u_usuario = CDD.cdd_usuario ".
                    "ORDER BY cdd_fecha DESC;";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_comentario"]  = $_fila["cdd_id_comentario"];
                $_resp[$i]["usuario"]        = $_fila["cdd_usuario"];
                $_resp[$i]["nombre_usuario"] = $_fila["cdd_nombre_usuario"];
                $_resp[$i]["url_imagen"]     = $_fila["u_url_imagen"];
                $_resp[$i]["localidad"]      = $_fila["localidad"];
                $_resp[$i]["curso"]          = $_fila["curso"];
                $_resp[$i]["colegio"]        = $_fila["colegio"];
                $_resp[$i]["comentario"]     = $_fila["cdd_comentario"];
                $_resp[$i]["fecha"]          = $_fila["cdd_fecha"];
                $i++;
            }
        }
        else {
            //No existen comentarios para esta actividad
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
 * Inserta un comentario a la tabla comentario_ea
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.04.12
 * @param       array  $_comentario arreglo que contiene toda la información con respecto a un comentario
 * @param       resource $conexion Identificador de enlace a MySQL
 * @return      Boolean $resp True en caso de éxito, False en caso de fallo
 */
function dbInsertarComentarioExpActividad($_comentario, $conexion) {
    $resp=false;

    //Inserta un comentario a una actividad asociada a una experiencia
    $consulta = "INSERT INTO comentario_ea(".
                    "cea_usuario, ".
                    "cea_nombre_usuario, ".
                    "cea_comentario, ".
                    "cea_fecha, ".
                    "cea_id_exp_actividad) ".
                 "VALUES (".
                     "'".$_comentario["usuario"]."' ,".
                     "'".$_comentario["nombre_usuario"]."' ,".
                     "'".$_comentario["comentario"]."' ,".
                     "now() ,".
                     $_comentario["id_exp_actividad"].");";
    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_affected_rows($conexion) >0) {
            $resp =true;
            }
        }
    else {
        //ERROR MYSQL
    }
    return $resp;
}
/**
 * Inserta un comentario a una actividad asociada a una experiencia
 *
 * @author Daniel Guerra - Kelluwen
 * @param String $_comentario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return boolean
 */
function dbInsertarComentarioDD($_comentario, $conexion) {
    $resp=false;

    $consulta = "INSERT INTO comentario_dd(".
                    "cdd_usuario, ".
                    "cdd_nombre_usuario, ".
                    "cdd_comentario, ".
                    "cdd_fecha, ".
                    "cdd_id_diseno_didactico, ".
                    "cdd_id_experiencia) ".
                 "VALUES (".
                     "'".$_comentario["usuario"]."' ,".
                     "'".$_comentario["nombre_usuario"]."' ,".
                     "'".$_comentario["comentario"]."' ,".
                     "now() ,".
                     $_comentario["id_dd"]." ,".
                     $_comentario["id_exp"].");";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_affected_rows($conexion) >0) {
            $resp =true;
        }
    }
    else {
        //ERROR MYSQL
    }
    return $resp;
}


/**
 * Inserta un mensaje publicado en la Bitácora en la Base de datos en la tabla Hitorial Mensajes
 *
 * @author Katherine Inalef - Kelluwen
 * @param String $nombre
 * @param String $usuario
 * @param String $url_imagen
 * @param String $mensaje
 * @param integer $id_grupo
 * @param String $nombre_grupo
 * @param integer $id_experiencia
 * @param integer $id_actividad
 * @param integer $id_exp_actividad
 * @param integer $es_producto
 * @param String $et_clase_gemela
 * @param String $et_grupo_gemelo
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return integer
 */
function dbInsertarMensajeHistorialBitacora($nombre,
                                          $usuario,
                                          $url_imagen,
                                          $mensaje,
                                          $id_grupo,
                                          $nombre_grupo,
                                          $id_experiencia,
                                          $id_actividad,
                                          $id_exp_actividad,
                                          $es_producto,
                                          $et_clase_gemela,
                                          $et_grupo_gemelo,
                                          $compartido,
                                          $conexion) {

    $_datos_consulta = array();
    //$nombre = ereg_replace("[^A-Za-z0-9]", "", $nombre);
    //Inserta un mensaje al historial de bitácora asociado a una experiencia
    $consulta = "INSERT INTO bt_historial_mensajes(".
                    "bthm_nombre,".
                    "bthm_usuario,".
                    "bthm_url_imagen,".
                    "bthm_fecha, ".
                    "bthm_mensaje, ".
                    "bthm_id_grupo, ".
                    "bthm_nombre_grupo, ".
                    "bthm_id_actividad, ".
                    "bthm_id_exp_actividad, ".
                    "bthm_id_experiencia, ".
                    "bthm_producto, ".
                    "bthm_etiqueta_gemela_ed, ".
                    "bthm_etiqueta_gemela_g, ".
                    "bthm_compartido ".
                    ") ".
                 "VALUES (".
                     "'".$nombre."', ".
                     "'".$usuario."', ".
                     "'".$url_imagen."', ".
                     "now(), ".
                     "'".$mensaje."', ".
                     $id_grupo.", ".
                     "'".$nombre_grupo."', ".
                     $id_actividad.", ".
                     $id_exp_actividad.", ".
                     $id_experiencia.", ".
                     $es_producto.", ".
                     "'".$et_clase_gemela."', ".
                     "'".$et_grupo_gemelo."', " .
                     $compartido." ".
                     ") ; ";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    $numero = mysql_affected_rows();
    mysql_free_result($resultado);
    if($numero) {
        return $numero;
    }
    else {
        return -1;
    }

}

/**
 * Inserta un nuevo usuario a la Base de Datos
 * @author Katherine Inalef - Kelluwen
 * @param String $nombre
 * @param String $apellido
 * @param String $nombre_usuario
 * @param String $contrasena
 * @param String $fecha_nacimiento
 * @param String $correo
 * @param integer $rol
 * @param String $localidad
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return integer
 */
function dbInsertarNuevoUsuario(  $nombre,
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
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
/**
 * Devuelve el número de veces que aparece un nombre de usuario en la tabla usuarios en la base de datos. Esto
 * es para saber si el nombre de usuario ya existe en la base de datos.
 *
 * @author Katherine Inalef - Kelluwen
 * @param String $usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Integer
 */
function dbValidaNombreUsuario($usuario, $conexion) {
    $consulta = "SELECT count(*) as cont " .
                "FROM " .
                    "usuario " .
                "WHERE " .
                    "u_usuario = '" . $usuario . "' ";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $resp = $_fila["cont"];
            }
        }
        return $resp;
    } else {
        return -1;
    }
    mysql_free_result($resultado);
}
/**
 * Devuelve el número de veces que aparece un nombre de usuario en la tabla usuarios en la base de datos. Esto
 * es para saber si el nombre de usuario ya existe en la base de datos.
 *
 * @author Katherine Inalef - Kelluwen
 * @param String $usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Integer
 */
function dbValidaCodigoSecreto($codigo, $conexion) {
    $consulta = "SELECT count(*) as cont ".
                "FROM ".
                    "experiencia_didactica ".
                "WHERE ".
                    "ed_etiqueta = '".$codigo."' ";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
            $resp = $_fila["cont"];
        }
        return $resp;
    } else {
        return -1;
    }
    mysql_free_result($resultado);
}
function dbValidaCodigoGemelo($codigo, $conexion) {
    $consulta = "SELECT count(*) as cont ".
                "FROM ".
                    "experiencia_didactica ".
                "WHERE ".
                    "ed_etiqueta_gemela = '".$codigo."' ";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if ($resultado) {
        while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
            $resp = $_fila["cont"];
        }
        return $resp;
    } else {
        return -1;
    }
    mysql_free_result($resultado);
}

/**
 * Devuelve el número de veces que aparece un correo en la tabla usuarios en la base de datos. Esto
 * es para saber si el correo ya existe en la base de datos.
 *
 * @author Katherine Inalef - Kelluwen
 * @param String $correo
 * @param resource $conexion Identificador de enlace a MySQL
 * @return Integer
 */
function dbValidaCorreoUsuario($correo,
                               $conexion) {
    $consulta = "SELECT count(*) as cont ".
                "FROM ".
                    "usuario ".
                 "WHERE ".
                     "u_email = '".$correo."' ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp= $_fila["cont"];
             }
        }
        return $resp;
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
/**
 * Valida que la combinación nombre usuario y contraseña correspondan
 * @author Katherine Inalef - Kelluwen
 * @param String $usuario
 * @param String $contrasena
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return integer
 */
function dbValidaContrasenaUsuario($usuario,$contrasena,$conexion) {
    $consulta = "SELECT count(*) as cont ".
                "FROM ".
                    "usuario ".
                 "WHERE ".
                     "u_usuario = '".$usuario."' AND ".
                     "u_password = '".$contrasena."' ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp               = $_fila["cont"];
             }
        }

        return $resp;
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
/**
 * Obtiene información de las clases gemelas, incluido los datos del profesor, de una clase
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_clase
 * @param string $et_clase_gemela
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array (  id_experiencia  => integer,
 *                  localidad       => String,
 *                  colegio         => String)
 */
function dbObtenerInfoClaseGemela($id_clase, $et_clase_gemela, $conexion){
    $_resp=array();

    $consulta = "SELECT ".
                    "ED.ed_id_experiencia, ED.ed_localidad, ED.ed_colegio, ED.ed_curso, U.u_nombre, U.u_usuario, U.u_url_imagen  ".
                 "FROM ".
                    "experiencia_didactica ED,usuario U, usuario_experiencia UE ".
                 "WHERE ".
                    "ED.ed_etiqueta_gemela = '".$et_clase_gemela."' AND ".
                    "ED.ed_id_experiencia != '".$id_clase. "' AND ".
                    "ED.ed_id_experiencia = UE.ue_id_experiencia  AND ".
                    "ED.ed_publicado = 1 AND ".
                    "UE.ue_rol_usuario =1 AND ".
                    "UE.ue_id_usuario = U.u_id_usuario ;";

//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_experiencia"]    = $_fila["ed_id_experiencia"];
                $_resp[$i]["localidad"]         = $_fila["ed_localidad"];
                $_resp[$i]["colegio"]           = $_fila["ed_colegio"];
                $_resp[$i]["curso"]             = $_fila["ed_curso"];
                $_resp[$i]["nombre_profesor"]   = $_fila["u_nombre"];
                $_resp[$i]["usuario_profesor"]  = $_fila["u_usuario"];
                $_resp[$i]["imagen_profesor"]   = $_fila["u_url_imagen"];
                $i++;
            }
        }
        else {
            //No existe clase gemela
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
 * Obtiene los datos del profesor a cargo de una Experiencia Didáctica
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_experiencia
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array (  id          => integer,
 *                  nombre      => String,
 *                  usuario     => String,
 *                  imagen      => String)
 */
function dbExpObtenerProfesor($id_experiencia, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "ED.ed_id_profesor, U.u_nombre , U.u_usuario , U.u_url_imagen ".
                 "FROM ".
                    "experiencia_didactica ED , usuario U ".
                 "WHERE ".
                    "ED.ed_id_experiencia = '".$id_experiencia."' AND ".
                    "U.u_id_usuario = ED.ed_id_profesor;";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {

            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp["id"]        = $_fila["ed_id_profesor"];
                $_resp["nombre"]    = $_fila["u_nombre"];
                $_resp["usuario"]   = $_fila["u_usuario"];
                $_resp["imagen"]    = $_fila["u_url_imagen"];
            }
        }
        else {
            //No existe clase gemela
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
 * @author Katherine Inalef - Kelluwen
 * @param string $nombre_usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array ( nombre               => String,
 *                  inscribe_diseno     => integer,
 *                  email               => string,
 *                  fecha_nacimiento    => date,
 *                  localidad           => string,
 *                  establecimiento     => String,
 *                  id                  => integer,
 *                  imagen              => String,
 *                  contrasena          => String)
 */
function dbObtenerInfoUsuario($nombre_usuario, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "U.u_nombre, ".
                    "U.u_email , ".
                    "U.u_inscribe_diseno, ".
                    "U.u_fecha_nacimiento , ".
                    "U.u_localidad, ".
                    "U.u_establecimiento, ".
                    "U.u_id_usuario, ".
                    "U.u_password, ".
                    "U.u_url_imagen, ".
                    "U.u_mostrar_email, ".
                    "U.u_mostrar_fecha_nacimiento ".
                 "FROM ".
                    "usuario U ".
                 "WHERE ".
                   "U.u_usuario  = '".$nombre_usuario."'";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $_resp["nombre"]                = $_fila["u_nombre"];
        $_resp["inscribe_diseno"]       = $_fila["u_inscribe_diseno"];
        $_resp["email"]                 = $_fila["u_email"];
        $_resp["fecha_nacimiento"]      = $_fila["u_fecha_nacimiento"];
        $_resp["localidad"]             = $_fila["u_localidad"];
        $_resp["establecimiento"]       = $_fila["u_establecimiento"];
        $_resp["id"]                    = $_fila["u_id_usuario"];
        $_resp["imagen"]                = $_fila["u_url_imagen"];
        $_resp["contrasena"]            = $_fila["u_password"];
        $_resp["mostrar_correo"]        = $_fila["u_mostrar_email"];
        $_resp["mostrar_fecha"]         = $_fila["u_mostrar_fecha_nacimiento"];

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
 * Actualiza los datos correspondientes al perfil de usuario en la Base de datos
 * @author Katherine Inalef - Kelluwen
 * @param integer   $id_usuario
 * @param string    $email
 * @param date      $fecha_nacimiento
 * @param string    $localidad
 * @param string    $imagen
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return integer
 */
function dbActualizaDatosUsuario($id_usuario, $nombre, $apellido, $email,$fecha_nacimiento, $comuna,$establecimiento, $contrasena, $imagen,$mostrar_correo,$mostrar_fecha, $conexion){
    //actualizar solo los campos que no sean nulos
    $n = 0;
    $nombre.= " ".$apellido;
    $consulta =  "UPDATE ".
                            "usuario ".
                 "SET ";

    if(!is_null($nombre) &&  strlen($nombre) > 2){
        $consulta .= "u_nombre = '".$nombre."' ";
        $n++;
    }
    if(!is_null($email) &&  strlen($email) > 0){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_email = '".$email."' ";
        $n++;
    }
    if(!is_null($fecha_nacimiento) &&  strlen($fecha_nacimiento) >0){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_fecha_nacimiento = '".$fecha_nacimiento."' ";
        $n++;
    }
    if(!is_null($comuna) &&  strlen($comuna) >0){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_localidad = '".$comuna."' ";
        $n++;
    }
    if(!is_null($establecimiento) &&  strlen($establecimiento) >0){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_establecimiento = '".$establecimiento."' ";
        $n++;
    }
    if(!is_null($contrasena) &&  strlen($contrasena) >0){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_password = '".$contrasena."' ";
        $n++;
    }
    if(!is_null($imagen) &&  strlen($imagen) >0){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_url_imagen = '".$imagen."' ";
        $n++;
        }
     if(!is_null($mostrar_correo)){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_mostrar_email = ".$mostrar_correo." ";
        $n++;
        }
     if(is_null($mostrar_correo)){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_mostrar_email = 0 ";
        $n++;
        }
        if(!is_null($mostrar_fecha)){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_mostrar_fecha_nacimiento = ".$mostrar_fecha." ";
        $n++;
        }
     if(is_null($mostrar_fecha)){
        if($n > 0){
            $consulta .= " , ";
        }
        $consulta .= "u_mostrar_fecha_nacimiento = 0 ";
        $n++;
        }
     

    $consulta .= "WHERE ".
                     "u_id_usuario = '".$id_usuario."';";
    
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);

}
/**
 * Obtiene los comentarios de un usuario de la tabla Historial_mensajes (utilizada en el perfil de usuario)
 * @author Katherine Inalef - kelluwen
 * @param String    $nombre_usuario
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array (  mensaje         => String,
 *                  fecha           => String,
 *                  id_experiencia  => integer,
 *                  id_actividad    => integer,
 *                  producto        => integer)
 */
function dbObtenerComentariosUsuario($nombre_usuario, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "bthm.bthm_mensaje, bthm.bthm_fecha , bthm.bthm_id_experiencia , bthm.bthm_id_actividad, bthm.bthm_producto  ".
                 "FROM ".
                    "bt_historial_mensajes bthm ".
                 "WHERE ".
                   "bthm.bthm_usuario  = '".$nombre_usuario."' ".
                 "ORDER BY bthm.bthm_fecha DESC ".
                 "LIMIT 5";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["mensaje"]             = $_fila["bthm_mensaje"];
                $_resp[$i]["fecha"]               = $_fila["bthm_fecha"];
                $_resp[$i]["id_experiencia"]      = $_fila["bthm_id_experiencia"];
                $_resp[$i]["id_actividad"]        = $_fila["bthm_id_actividad"];
                $_resp[$i]["producto"]            = $_fila["bthm_producto"];
                $i++;
            }
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
 * Obtiene los datos del grupo al que pertenece un usuario
 * @param String    $nombre_usuario
 * @param Integer   $id_experiencia
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array (  id_grupo    => integer,
 *                  et_gemela   => String,
 *                  et_grupo    => String )
 */
function dbObtenerGrupoUsuario($nombre_usuario,$id_experiencia, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "ug.ug_id_grupo, g.g_etiqueta_gemela, g.g_etiqueta, g.g_nombre ".
                 "FROM ".
                    "usuario_grupo ug, grupo g, usuario u ".
                 "WHERE ".
                   "u.u_usuario  = '".$nombre_usuario."' AND ".
                   "u.u_id_usuario  = ug.ug_id_usuario AND ".
                   "ug.ug_id_grupo  = g.g_id_grupo AND ".
                   "g.g_id_experiencia = '".$id_experiencia."'  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp["id_grupo"]      = $_fila["ug_id_grupo"];
                $_resp["et_gemela"]     = $_fila["g_etiqueta_gemela"];
                $_resp["et_grupo"]      = $_fila["g_etiqueta"];
                $_resp["nombre"]        = $_fila["g_nombre"];
            }
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
function dbObtenerGrupoyGrupoGemeloUsuario($nombre_usuario,$id_experiencia, $conexion){
//    $_resp=array();
//    $consulta = "SELECT ".
//                    "ug.ug_id_grupo, g.g_etiqueta_gemela, g.g_etiqueta, g.g_nombre ".
//                 "FROM ".
//                    "usuario_grupo ug, grupo g, usuario u ".
//                 "WHERE ".
//                   "u.u_usuario  = '".$nombre_usuario."' AND ".
//                   "u.u_id_usuario  = ug.ug_id_usuario AND ".
//                   "ug.ug_id_grupo  = g.g_id_grupo AND ".
//                   "g.g_id_experiencia = '".$id_experiencia."'  ";
//
//    $resultado = dbEjecutarConsulta($consulta, $conexion);
//    if($resultado) {
//        if(mysql_num_rows($resultado) >0) {
//            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
//                $_resp["id_grupo"]      = $_fila["ug_id_grupo"];
//                $_resp["et_gemela"]     = $_fila["g_etiqueta_gemela"];
//                $_resp["et_grupo"]      = $_fila["g_etiqueta"];
//                $_resp["nombre"]        = $_fila["g_nombre"];
//            }

}
/**
 * Obtiene los id de los grupos gemelos de un grupo
 * @author Katherine Inalef - Kelluwen
 * @param integer   $id_grupo
 * @param String    $et_gemela
 * @param resource  $conexion Identificador de enlace a MySQL
 * @return Array (id_grupo  => integer)
 */
function dbObtenerGrupoGemeloUsuario($id_grupo,$et_gemela, $conexion){
    $_resp=array();
    //Obtener el grupo del usuario
    $consulta = "SELECT ".
                    "g.g_id_grupo, g.g_nombre, ed.ed_colegio ".
                 "FROM ".
                    "grupo g, experiencia_didactica ed ".
                 "WHERE ".
                   "g.g_etiqueta_gemela  = '".$et_gemela."'  AND ".
                   "g.g_id_grupo != '".$id_grupo."'  AND ".
                   "g.g_id_experiencia = ed.ed_id_experiencia";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_grupo"]          = $_fila["g_id_grupo"];
                $_resp[$i]["nombre"]            = $_fila["g_nombre"];
                $_resp[$i]["establecimiento"]   = $_fila["ed_colegio"];
            }
            $i++;
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
 * Obtiene todas las Experiencias Didácticas que han sido finalizadas
 * @author Katherine Inalef- Kelluwen
 * @param resource $conexion Identificador de enlace a MySQL
 * @return Array (  nombre_dd => String,
 *                  nivel => String,
 *                  subsector => String,
 *                  id_experiencia => String,
 *                  localidad => String,
 *                  curso => String,
 *                  colegio => String)
 */
function dbDisObtenerExpFinalizadasDisenoPeriodo($conexion,$id_dd, $semestre,$anio){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "D.dd_nombre, ".
                    "D.dd_nivel, ".
                    "D.dd_subsector, ".
                    "E.ed_id_experiencia, ".
                    "E.ed_localidad, ".
                    "E.ed_curso, ".
                    "E.ed_colegio ".
                "FROM ".
                    "experiencia_didactica E, ".
                    "diseno_didactico D ".
                "WHERE ".
                    "D.dd_id_diseno_didactico=".$id_dd." AND ".
                    "E.ed_semestre='".$semestre."' AND ".
                    "E.ed_anio=".$anio." AND ".
                    "D.dd_id_diseno_didactico = E.ed_id_diseno_didactico AND ".
                    "E.ed_fecha_termino is not null;";
    
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
                $i++;
            }
        }
    }
    return $_resp;

}
function dbDisObtenerExpFinalizadasDisenoLimitePeriodo($conexion,$id_dd,$lim_inf,$lim_sup,$semestre,$anio){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT D.dd_nombre, ".
                   "D.dd_nivel, ".
                   "D.dd_subsector, ".
                   "E.ed_id_experiencia, ".
                   "E.ed_localidad, ".
                   "E.ed_curso, ".
                   "E.ed_colegio, ".
                   "COUNT(BT.bthm_id_experiencia) AS total ".
                "FROM ".
                    "experiencia_didactica E, ".
                    "diseno_didactico D, ".
                    "bt_historial_mensajes BT ".
                "WHERE ".
                    "D.dd_id_diseno_didactico =".$id_dd." ".
                   "AND D.dd_id_diseno_didactico = E.ed_id_diseno_didactico ".
                   "AND E.ed_semestre ='".$semestre."' ".
                   "AND E.ed_anio =".$anio." ".
                   "AND E.ed_id_experiencia = BT.bthm_id_experiencia ".
                   "AND E.ed_fecha_termino IS NOT NULL ".
               "GROUP  BY BT.bthm_id_experiencia ".
               "ORDER  BY total DESC ".
               "LIMIT ".$lim_inf." ,".$lim_sup;
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
                $i++;
            }
        }
    }
    return $_resp;

}
/**
 *
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbObtenerExpFinalizadas($conexion){
    $_resp = null;
    $i = 0;
    $consulta = "SELECT ".
                    "D.dd_nombre, ".
                    "D.dd_nivel, ".
                    "D.dd_subsector, ".
                    "E.ed_id_experiencia, ".
                    "E.ed_localidad, ".
                    "E.ed_curso, ".
                    "E.ed_colegio ".
                "FROM ".
                    "experiencia_didactica E, ".
                    "diseno_didactico D ".
                "WHERE ".
                    "D.dd_id_diseno_didactico = E.ed_id_diseno_didactico AND ".
                    "E.ed_fecha_termino is not null;";
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
                $i++;
            }
        }
    }
    return $_resp;

}
/**
 * Recupera los datos del usuario correspondientes al correo electrónico ingresado
 * @author Katherine Inalef - Kelluwen
 * @param String $correo
 * @param resource $conexion Identificador de enlace a MySQL
 * @return Array (  nombre      => String,
 *                  usuario     => String,
 *                  contrasena  => String )
 */
function dbRecuperarContrasena($correo, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "u.u_nombre, u.u_usuario, u.u_password ".
                 "FROM ".
                    "usuario u ".
                 "WHERE ".
                   "u.u_email  = '".$correo."' ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp["nombre"]            = $_fila["u_nombre"];
                $_resp["usuario"]           = $_fila["u_usuario"];
                $_resp["contrasena"]        = $_fila["u_password"];
            }
        }
        else {
            $_resp ="";
        }
        mysql_free_result($resultado);
    }
    else {
        $_resp ="";
    }
    return $_resp;
}
/**
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_mensaje_original
 * @param string $nombre
 * @param string $usuario
 * @param string $url_imagen
 * @param string $mensaje
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbInsertarMensajeRespuesta(  $id_mensaje_original,
                                      $nombre,
                                      $usuario,
                                      $url_imagen,
                                      $mensaje,
                                      $conexion) {


    $_datos_consulta = array();


    $fecha = date("Y-m-d H:i:s");

    $consulta1 = "SELECT count(btrm_id_mensaje_respuesta) as num FROM bt_respuesta_mensajes WHERE ".
                 "btrm_id_mensaje_original = ".$id_mensaje_original." AND ".
                 "btrm_nombre = '".$nombre. "' AND ".
                 "btrm_usuario = '".$usuario. "' AND ".
                 "btrm_url_imagen = '".$url_imagen. "' AND ".
                 "btrm_fecha = '".$fecha. "' AND ".
                 "btrm_mensaje = '".$mensaje."';";


    $resultado1 = dbEjecutarConsulta($consulta1, $conexion);             
    if($resultado1){
        if(mysql_num_rows($resultado1) > 0) {
            $row = mysql_fetch_array($resultado1, MYSQL_BOTH);
            $num = $row["num"];

            if($num > 0){
                return -1;
            }
            else{
                $consulta = "INSERT INTO bt_respuesta_mensajes (".
                        "btrm_id_mensaje_original, ".
                        "btrm_nombre, ".
                        "btrm_usuario, ".
                        "btrm_url_imagen, ".
                        "btrm_fecha, ".
                        "btrm_mensaje ".
                        ") ".
                     "VALUES (".
                         $id_mensaje_original.", ".
                         "'".$nombre."', ".
                         "'".$usuario."', ".
                         "'".$url_imagen."',".
                         "'".$fecha."', ".
                         /*"now(), ".*/
                         "'".$mensaje."' );";

                //echo $consulta;
                $resultado = dbEjecutarConsulta($consulta, $conexion);
                return mysql_affected_rows();
                mysql_free_result($resultado);
            }
        }
        return -2;
    }
    return -3;                     
}
/**
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_mensaje_original
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbObtenerMensajesEnRespuesta($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "btrm.btrm_id_mensaje_respuesta, ".
                    "btrm.btrm_usuario, ".
                    "btrm.btrm_fecha, ".
                    "btrm.btrm_mensaje, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM bt_respuesta_mensajes btrm, usuario u ".
                    "WHERE ".
                    "btrm.btrm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "btrm.btrm_usuario = u.u_usuario  ".
                    "ORDER BY btrm.btrm_fecha ASC ";
                    //Es mas comodo en orden temporal "ORDER BY rm.rm_fecha DESC ";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje_respuesta"]  = $_fila["btrm_id_mensaje_respuesta"];
                $_resp[$i]["fecha"]                 = $_fila["btrm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["btrm_mensaje"];
                $_resp[$i]["usuario"]               = $_fila["btrm_usuario"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]            = $_fila["u_url_imagen"];

                $i++;
            }
        }
    }
    return $_resp;
}
/**
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_mensaje_original
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbObtenerNumMensajesEnRespuesta($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT count(*) as cont ".
                    "FROM bt_respuesta_mensajes btrm, usuario u ".
                    "WHERE ".
                    "btrm.btrm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "btrm.btrm_usuario = u.u_usuario  ";
 //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
            $_resp  = $_fila["cont"];
        }
    }
    return $_resp;
}
/**
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_mensaje_original
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbObtenerMensajesEnRespuestaResumen($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "btrm.btrm_id_mensaje_respuesta, ".
                    "btrm.btrm_usuario, ".
                    "btrm.btrm_fecha, ".
                    "btrm.btrm_mensaje, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM bt_respuesta_mensajes btrm, usuario u ".
                    "WHERE ".
                    "btrm.btrm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "btrm.btrm_usuario = u.u_usuario  ".
                    "ORDER BY btrm.btrm_fecha DESC ".
                    "LIMIT 3";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje_respuesta"]  = $_fila["btrm_id_mensaje_respuesta"];
                $_resp[$i]["fecha"]                 = $_fila["btrm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["btrm_mensaje"];
                $_resp[$i]["usuario"]               = $_fila["btrm_usuario"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]            = $_fila["u_url_imagen"]; //
                $i++;
            }
        }
    }
    return $_resp;
}
/**
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_mensaje
 * @param integer $id_usuario
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbInsertarMeGustaMensaje(    $id_mensaje,
                                      $id_usuario,
                                      $conexion) {

    $_datos_consulta = array();
    $consulta = "INSERT INTO bt_megusta_mensaje (".
                    "btmg_id_mensaje, ".
                    "btmg_id_usuario, ".
                    "btmg_fecha".//Código agregado por Jordan Barría el 28-10-14
                    ") ".
                 "VALUES (".
                     $id_mensaje.", ".
                     $id_usuario.", ".
                     "now() ) ; ";//Código agregado por Jordan Barría el 28-10-14
//    echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    return mysql_affected_rows();
    mysql_free_result($resultado);
}
/**
 *
 * @param integer $id_mensaje
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbObtenerMeGustaMensaje($id_mensaje, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "btmg.btmg_id_usuario, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen, ".
                    "u.u_establecimiento, ".
                    "u.u_localidad, ".
                    "u.u_usuario ".
                    "FROM bt_megusta_mensaje btmg, usuario u ".
                    "WHERE ".
                    "btmg.btmg_id_mensaje = '".$id_mensaje."' AND  ".
                    "u.u_id_usuario =  btmg.btmg_id_usuario ;";
    //echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                //$_resp[$i]["id_mensaje_respuesta"]  = $_fila["rm_id_mensaje_respuesta"];
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
/**
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_usuario
 * @param integer $id_mensaje
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbUsuarioGustaMensaje ($id_usuario, $id_mensaje, $conexion){
    $consulta=      "SELECT count(*) as cont ".
                    "FROM bt_megusta_mensaje btmg ".
                    "WHERE ".
                    "btmg.btmg_id_mensaje = '".$id_mensaje."' AND  ".
                    "btmg.btmg_id_usuario =  '".$id_usuario."' ";
    //echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp               = $_fila["cont"];
             }
        }

        return $resp;
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);

}
/**
 * @author Katherine Inalef - Kelluwen
 * @param integer $id_mensaje
 * @param integer $id_usuario
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbNoGustaMensaje ($id_mensaje, $id_usuario, $conexion){
    $consulta = "DELETE FROM ".
                 " bt_megusta_mensaje ".
                 "WHERE ".
                    "btmg_id_mensaje = ".$id_mensaje." AND ".
                    "btmg_id_usuario = ".$id_usuario.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
/**
 *
 * @param integer $id_experiencia
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbObtenerUsuariosGrupo($id_experiencia, $conexion) {
    $_resp=array();
    //Obtiene información sobre los grupos de una expriencia
    $consulta = "SELECT ".
                    "g_id_grupo, ".
                    "g_nombre, ".
                    "g_etiqueta, ".
                    "g_etiqueta_gemela ".
                "FROM ".
                    "grupo ".
                "WHERE ".
                    "g_id_experiencia = ".$id_experiencia.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id"]               = $_fila["g_id_grupo"];
                $_resp[$i]["nombre"]           = $_fila["g_nombre"];
                $_resp[$i]["etiqueta"]         = $_fila["g_etiqueta"];
                $_resp[$i]["etiqueta_gemela"]  = $_fila["g_etiqueta_gemela"];
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
        $_resp =null;
    }
    return $_resp;
}
/**
 *
 * @param integer $id_experiencia
 * @param resource $conexion Identificador de enlace a MySQL
 * @return <type>
 */
function dbObtenerUsuariosGrupoGemelo($id_experiencia, $conexion) {
    $_resp=array();

    //Obtiene información sobre los grupos de una expriencia
    $consulta = "SELECT ".
                    "g_id_grupo, ".
                    "g_nombre, ".
                    "g_etiqueta, ".
                    "g_etiqueta_gemela ".
                "FROM ".
                    "grupo ".
                "WHERE ".
                    "g_id_experiencia = ".$id_experiencia.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id"]               = $_fila["g_id_grupo"];
                $_resp[$i]["nombre"]           = $_fila["g_nombre"];
                $_resp[$i]["etiqueta"]         = $_fila["g_etiqueta"];
                $_resp[$i]["etiqueta_gemela"]  = $_fila["g_etiqueta_gemela"];
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
        $_resp =null;
    }
    return $_resp;
}

function dbUsuarioOpcionMuro($id_usuario, $conexion){
    $consulta=      "SELECT count(*) as cont ".
                    "FROM usuario_experiencia ue ".
                    "WHERE ".
                    "ue.ue_id_usuario = '".$id_usuario."' AND  ".
                    "ue.ue_rol_usuario In (1,3,4) ";
    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp               = $_fila["cont"];
             }
        }
        if($resp > 0){
            $resp = true;
        }
        else{
            $resp= false;
        }
        return $resp;
    }
    mysql_free_result($resultado);
}
function dbMuralUsuarioInsertarMensaje($id_usuario_muro,
                                      $id_usuario_publica,
                                      $mensaje,
                                      //etiqueta o clasificación del mensaje
                                      $conexion) {

    //Inserta un mensaje al historial de bitácora asociado a una experiencia
    $consulta = "INSERT INTO mu_mensajes(".
                    "mumj_id_usuario_muro, ".
                    "mumj_id_usuario_publica, ".
                    "mumj_fecha, ".
                    "mumj_mensaje ".
                    ") ".
                 "VALUES (".
                     $id_usuario_muro.", ".
                     $id_usuario_publica.", ".
                     "now(), ".
                     "'".$mensaje."') ; ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_insert_id();
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
function dbMuralUsuarioMensajes ($conexion, $id_usuario){
    $_resp = null;
    $consulta= "SELECT ".
                    "mumj.mumj_id_mensaje, ".
                    "mumj.mumj_id_usuario_muro, ".
                    "mumj.mumj_id_usuario_publica, ".
                    "mumj.mumj_mensaje , ".
                    "mumj.mumj_fecha ".
                    "FROM mu_mensajes mumj ".
                    "WHERE ".
                    "mumj.mumj_id_usuario_muro = '".$id_usuario."'  ".
                    " OR ".
                    "mumj.mumj_id_usuario_publica = '".$id_usuario."' ".
                    "ORDER BY mumj.mumj_fecha DESC ";

//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]            = $_fila["mumj_id_mensaje"];
                $_resp[$i]["id_usuario_muro"]       = $_fila["mumj_id_usuario_muro"];
                $_resp[$i]["id_usuario_publica"]    = $_fila["mumj_id_usuario_publica"];
                $_resp[$i]["mensaje"]               = $_fila["mumj_mensaje"];
                $_resp[$i]["fecha"]                 = $_fila["mumj_fecha"];

                $consulta2 = "SELECT ".
                    "u.u_nombre as nombre_usuario_publica, ".
                    "u.u_usuario as usuario_publica, ".
                    "u.u_url_imagen as url_usuario_publica ".
                    "FROM usuario u ".
                    "WHERE ".
                    "u.u_id_usuario = '".$_resp[$i]["id_usuario_publica"] ."' ";

                $consulta3 = "SELECT ".
                    "u.u_nombre as nombre_usuario_muro, ".
                    "u.u_usuario as usuario_muro, ".
                    "u.u_url_imagen as url_usuario_muro ".
                    "FROM usuario u ".
                    "WHERE ".
                    "u.u_id_usuario = '".$_resp[$i]["id_usuario_muro"] ."' ";

                //echo $consulta2;
                //echo $consulta3;

                $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
                $resultado3 = dbEjecutarConsulta($consulta3, $conexion);
                $_fila2 = mysql_fetch_array($resultado2, MYSQL_BOTH);
                $_fila3 = mysql_fetch_array($resultado3, MYSQL_BOTH);

                $_resp[$i]["nombre_usuario_publica"]        = $_fila2["nombre_usuario_publica"];
                $_resp[$i]["usuario_publica"]               = $_fila2["usuario_publica"];
                $_resp[$i]["url_imagen_usuario_publica"]    = $_fila2["url_usuario_publica"];
                $_resp[$i]["nombre_usuario_dueno"]          = $_fila3["nombre_usuario_muro"];
                $_resp[$i]["usuario_dueno"]                 = $_fila3["usuario_muro"];
                $_resp[$i]["url_imagen_usuario_dueno"]      = $_fila3["url_usuario_muro"];
                $i++;
            }
        }
    }

    return $_resp;
}
function dbMuralUsuarioInsertarMeGustaMensaje(  $id_mensaje,
                                                $id_usuario_valora,
                                                $id_usuario_autor,
                                                $conexion) {

    $_datos_consulta = array();
    $consulta = "INSERT INTO mu_megusta_mensaje (".
                    "mumg_id_mensaje, ".
                    "mumg_id_usuario_valora, ".
                    "mumg_id_usuario_autor ".
                    ") ".
                 "VALUES (".
                     $id_mensaje.", ".
                     $id_usuario_valora.", ".
                     $id_usuario_autor." ) ; ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    return mysql_affected_rows();
    mysql_free_result($resultado);
}
function dbMuralUsuarioObtenerMeGustaMensaje($id_mensaje, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "mumg.mumg_id_usuario_valora, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen, ".
                    "u.u_establecimiento, ".
                    "u.u_localidad, ".
                    "u.u_usuario ".
                    "FROM mu_megusta_mensaje mumg, usuario u ".
                    "WHERE ".
                    "mumg.mumg_id_mensaje = '".$id_mensaje."' AND  ".
                    "u.u_id_usuario =  mumg.mumg_id_usuario_valora ;";
    //echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                //$_resp[$i]["id_mensaje_respuesta"]  = $_fila["rm_id_mensaje_respuesta"];
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
/**
 * @author Katherine - Inalef
 * @param <type> $id_usuario
 * @param <type> $id_mensaje
 * @param <type> $conexion
 * @return <type>
 */
function dbMuralUsuarioGustaMensaje ($id_usuario_valora, $id_mensaje, $conexion){
    $consulta=      "SELECT count(*) as cont ".
                    "FROM mu_megusta_mensaje mumg ".
                    "WHERE ".
                    "mumg.mumg_id_mensaje = '".$id_mensaje."' AND  ".
                    "mumg.mumg_id_usuario_valora =  '".$id_usuario_valora."' ";
    //echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp               = $_fila["cont"];
             }
        }
        return $resp;
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
function dbMuralUsuarioNoGustaMensaje ($id_mensaje, $id_usuario_valora, $conexion){
    $consulta = "DELETE FROM ".
                 " mu_megusta_mensaje ".
                 "WHERE ".
                    "mumg_id_mensaje = ".$id_mensaje." AND ".
                    "mumg_id_usuario_valora = ".$id_usuario_valora.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
function dbMuralUsuarioInsertarMensajeRespuesta(  $id_mensaje_original,
                                                  $nombre,
                                                  $id_usuario_autor,
                                                  $id_usuario_responde,
                                                  $usuario,
                                                  $url_imagen,
                                                  $mensaje,
                                                  $conexion) {


    $_datos_consulta = array();
    $consulta = "INSERT INTO mu_respuesta_mensajes (".
                    "murm_id_mensaje_original, ".
                    "murm_nombre, ".
                    "murm_id_usuario_autor, ".
                    "murm_id_usuario_responde, ".
                    "murm_usuario, ".
                    "murm_url_imagen, ".
                    "murm_fecha, ".
                    "murm_mensaje ".
                    ") ".
                 "VALUES (".
                     $id_mensaje_original.", ".
                     "'".$nombre."', ".
                     "'".$id_usuario_autor."', ".
                     "'".$id_usuario_responde."', ".
                     "'".$usuario."', ".
                     "'".$url_imagen."', ".
                     "now(), ".
                     "'".$mensaje."' ) ; ";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    return mysql_affected_rows();
    mysql_free_result($resultado);
}
function dbMuralUsuarioObtenerMensajesEnRespuesta($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "murm.murm_id_mensaje_respuesta, ".
                    "murm.murm_usuario, ".
                    "murm.murm_fecha, ".
                    "murm.murm_mensaje, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM mu_respuesta_mensajes murm, usuario u ".
                    "WHERE ".
                    "murm.murm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "murm.murm_usuario = u.u_usuario  ".
                    "ORDER BY murm.murm_fecha ASC ";
                    //Es mas comodo en orden temporal "ORDER BY rm.rm_fecha DESC ";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje_respuesta"]  = $_fila["murm_id_mensaje_respuesta"];
                $_resp[$i]["fecha"]                 = $_fila["murm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["murm_mensaje"];
                $_resp[$i]["usuario"]               = $_fila["murm_usuario"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]            = $_fila["u_url_imagen"];

                $i++;
            }
        }
    }
    return $_resp;
}
function dbMuralUsuarioObtenerMensajesEnRespuestaResumen($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "murm.murm_id_mensaje_respuesta, ".
                    "murm.murm_usuario, ".
                    "murm.murm_fecha, ".
                    "murm.murm_mensaje, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM mu_respuesta_mensajes murm, usuario u ".
                    "WHERE ".
                    "murm.murm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "murm.murm_usuario = u.u_usuario  ".
                    "ORDER BY murm.murm_fecha DESC ".
                    "LIMIT 3";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje_respuesta"]  = $_fila["murm_id_mensaje_respuesta"];
                $_resp[$i]["fecha"]                 = $_fila["murm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["murm_mensaje"];
                $_resp[$i]["usuario"]               = $_fila["murm_usuario"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]            = $_fila["u_url_imagen"];
                $i++;
            }
        }
    }
    return $_resp;
}
function dbMuralUsuarioObtenerNumMensajesEnRespuesta($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT count(*) as cont ".
                    "FROM mu_respuesta_mensajes murm, usuario u ".
                    "WHERE ".
                    "murm.murm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "murm.murm_usuario = u.u_usuario  ";
 //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
            $_resp  = $_fila["cont"];
        }
    }
    return $_resp;
}
function dbMuralUsuarioObtenerMensaje ($id_mensaje, $conexion){
    $_resp = null;
    $consulta= "SELECT ".
                    "mumj.mumj_id_mensaje, ".
                    "mumj.mumj_id_usuario_muro, ".
                    "mumj.mumj_id_usuario_publica, ".
                    "mumj.mumj_mensaje , ".
                    "mumj.mumj_fecha ".
                    "FROM mu_mensajes mumj ".
                    "WHERE ".
                    "mumj.mumj_id_mensaje = '".$id_mensaje."'  ";

//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
            $_resp["id_mensaje"]            = $_fila["mumj_id_mensaje"];
            $_resp["id_usuario_muro"]       = $_fila["mumj_id_usuario_muro"];
            $_resp["id_usuario_publica"]    = $_fila["mumj_id_usuario_publica"];
            $_resp["mensaje"]               = $_fila["mumj_mensaje"];
            $_resp["fecha"]                 = $_fila["mumj_fecha"];

            $consulta2 = "SELECT ".
                "u.u_nombre as nombre_usuario_publica, ".
                "u.u_usuario as usuario_publica, ".
                "u.u_url_imagen as url_usuario_publica ".
                "FROM usuario u ".
                "WHERE ".
                "u.u_id_usuario = '".$_resp["id_usuario_publica"] ."' ";

            $consulta3 = "SELECT ".
                "u.u_nombre as nombre_usuario_muro, ".
                "u.u_usuario as usuario_muro, ".
                "u.u_url_imagen as url_usuario_muro ".
                "FROM usuario u ".
                "WHERE ".
                "u.u_id_usuario = '".$_resp["id_usuario_muro"] ."' ";

            $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
            $resultado3 = dbEjecutarConsulta($consulta3, $conexion);
            $_fila2 = mysql_fetch_array($resultado2, MYSQL_BOTH);
            $_fila3 = mysql_fetch_array($resultado3, MYSQL_BOTH);

            $_resp["nombre_usuario_publica"]        = $_fila2["nombre_usuario_publica"];
            $_resp["usuario_publica"]               = $_fila2["usuario_publica"];
            $_resp["url_imagen_usuario_publica"]    = $_fila2["url_usuario_publica"];
            $_resp["nombre_usuario_dueno"]          = $_fila3["nombre_usuario_muro"];
            $_resp["usuario_dueno"]                 = $_fila3["usuario_muro"];
            $_resp["url_imagen_usuario_dueno"]      = $_fila3["url_usuario_muro"];
        }
    }
    return $_resp;
}
function dbMuralUsuarioObtenerUsuariosConversacion($id_mensaje_original, $conexion){
    $_resp=array();
    //Personas que han respondido al mensaje
    $consulta = "SELECT DISTINCT ".
                    "u.u_id_usuario, u.u_nombre, u.u_usuario,  u.u_email, u.u_localidad, u.u_establecimiento, u.u_url_imagen ".
                 "FROM ".
                    "mu_respuesta_mensajes murm, usuario u ".
                 "WHERE ".
                   "murm.murm_id_mensaje_original  = '".$id_mensaje_original."'  AND ".
                   "murm.murm_usuario = u.u_usuario  ";
    //Usuario dueño del muro
    $consulta2 = "SELECT ".
                    "u.u_id_usuario, u.u_nombre, u.u_usuario,  u.u_email, u.u_localidad, u.u_establecimiento, u.u_url_imagen ".
                 "FROM ".
                    "mu_mensajes mumj, usuario u ".
                 "WHERE ".
                   "mumj.mumj_id_mensaje  = '".$id_mensaje_original."'  AND ".
                   "mumj.mumj_id_usuario_muro = u.u_id_usuario  ";

    //usuario dueño del mensaje
//    echo $consulta;
//    echo $consulta2;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
    $i=0;
    if(mysql_num_rows($resultado2) >0) {
        $_fila2=mysql_fetch_array($resultado2,MYSQL_BOTH);
                $_resp[$i]["id"]                = $_fila2["u_id_usuario"];
                $_resp[$i]["nombre"]            = $_fila2["u_nombre"];
                $_resp[$i]["usuario"]           = $_fila2["u_usuario"];
                $_resp[$i]["email"]             = $_fila2["u_email"];
                $_resp[$i]["localidad"]         = $_fila2["u_localidad"];
                $_resp[$i]["establecimiento"]   = $_fila2["u_establecimiento"];
                $_resp[$i]["url_imagen"]        = $_fila2["u_url_imagen"];
                $i++;
    }
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                if($_fila["u_id_usuario"] != $_resp[0]["id"] ){
                    $_resp[$i]["id"]                = $_fila["u_id_usuario"];
                    $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                    $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                    $_resp[$i]["email"]             = $_fila["u_email"];
                    $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                    $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                    $_resp[$i]["url_imagen"]        = $_fila["u_url_imagen"];
                    $i++;
                }
            }

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
function dbMuralDisenoInsertarMensaje($id_diseno,
                                      $id_experiencia,
                                      $id_usuario,
                                      $mensaje,
                                      $tipo,
                                      $id_actividad,
                                      $conexion,
                                      $id_mensaje_mu = null) {

    $consulta = "INSERT INTO md_mensajes(".
                    "mdmj_id_diseno, ".
                    "mdmj_id_experiencia, ".
                    "mdmj_id_usuario, ".
                    "mdmj_fecha, ".
                    "mdmj_mensaje, ".
                    "mdmj_tipo_mensaje, ".
                    "mdmj_id_actividad, ".
                    "mdmj_id_mensaje_mu ".
                    ") ".
                 "VALUES (".
                     $id_diseno.", ".
                     $id_experiencia.", ".
                     $id_usuario.", ".
                     "now(), ".
                     "'".$mensaje."',  ".
                     "'".$tipo."', ".
                     "'".$id_actividad."', ".
                     "'".$id_mensaje_mu."') ; ";
   // echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_insert_id();
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
function dbMuralDisenoMensajes ( $conexion,$id_diseno, $tipo){
    $_resp = null;
    $consulta= "SELECT ".
                    "mdmj.mdmj_id_mensaje, ".
                    "mdmj.mdmj_id_experiencia, ".
                    "mdmj.mdmj_id_usuario , ".
                    "mdmj.mdmj_fecha, ".
                    "mdmj.mdmj_mensaje, ".
                    "mdmj.mdmj_tipo_mensaje, ".
                    "mdmj.mdmj_id_actividad, ".
                    "u.u_nombre, ".
                    "u.u_email, ".
                    "u.u_localidad, ".
                    "u.u_establecimiento, ".
                    "u.u_usuario, ".
                    "u.u_url_imagen ".
                    "FROM md_mensajes mdmj, usuario u ".
                    "WHERE ".
                    "mdmj.mdmj_id_diseno = '".$id_diseno."'  AND ".
                    "mdmj.mdmj_id_usuario = u.u_id_usuario  ";

    if($tipo ==0 ){
        $consulta2 = "AND (mdmj_tipo_mensaje =0 OR mdmj_tipo_mensaje = 6 OR mdmj_tipo_mensaje = 7)".
                     "ORDER BY mdmj.mdmj_fecha DESC ";
    }
    if($tipo==1){
        $consulta2 = "AND (mdmj_tipo_mensaje =0 OR mdmj_tipo_mensaje =1 OR mdmj_tipo_mensaje = 6 OR mdmj_tipo_mensaje = 7) ".
                     "ORDER BY mdmj.mdmj_fecha DESC ";
    }
    if($tipo == 2){
        $consulta2 = "AND (mdmj_tipo_mensaje = 0 OR mdmj_tipo_mensaje = 1 OR mdmj_tipo_mensaje = 2 OR mdmj_tipo_mensaje = 3 OR mdmj_tipo_mensaje = 6 OR mdmj_tipo_mensaje = 7) ".
                     "ORDER BY mdmj.mdmj_fecha DESC ";
    }
    if($tipo == 3){
        $consulta2 = "ORDER BY mdmj.mdmj_fecha DESC ";
    }
    $consulta= $consulta.$consulta2;
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]        = $_fila["mdmj_id_mensaje"];
                $_resp[$i]["id_experiencia"]    = $_fila["mdmj_id_experiencia"];
                $_resp[$i]["id_usuario"]        = $_fila["mdmj_id_usuario"];
                $_resp[$i]["fecha"]             = $_fila["mdmj_fecha"];
                $_resp[$i]["mensaje"]           = $_fila["mdmj_mensaje"];
                $_resp[$i]["tipo"]              = $_fila["mdmj_tipo_mensaje"];
                $_resp[$i]["id_actividad"]      = $_fila["mdmj_id_actividad"];
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["url_imagen"]        = $_fila["u_url_imagen"];
                $i++;
            }
        }
    }

    return $_resp;
}
function dbMuralDisenoMensajesFiltro ( $conexion,$id_diseno, $tipo){
    $_resp = null;
    $consulta= "SELECT ".
                    "mdmj.mdmj_id_mensaje, ".
                    "mdmj.mdmj_id_experiencia, ".
                    "mdmj.mdmj_id_usuario , ".
                    "mdmj.mdmj_fecha, ".
                    "mdmj.mdmj_mensaje, ".
                    "mdmj.mdmj_tipo_mensaje, ".
                    "mdmj.mdmj_id_actividad, ".
                    "u.u_nombre, ".
                    "u.u_email, ".
                    "u.u_localidad, ".
                    "u.u_establecimiento, ".
                    "u.u_usuario, ".
                    "u.u_url_imagen ".
                    "FROM md_mensajes mdmj, usuario u ".
                    "WHERE ".
                    "mdmj.mdmj_id_diseno = '".$id_diseno."'  AND ".
                    "mdmj.mdmj_id_usuario = u.u_id_usuario  ";

    if($tipo ==0 ){ /*Solo mensajes escritos en el Kellu- muro*/
        $consulta2 = "AND mdmj_tipo_mensaje =0 ".
                     "ORDER BY mdmj.mdmj_fecha DESC ";
    }
    if($tipo==1){ /*Solo mensajes escritos en muros personales*/
        $consulta2 = "AND  mdmj_tipo_mensaje =1  ".
                     "ORDER BY mdmj.mdmj_fecha DESC ";
    }
    if($tipo == 2){ /*Solo mensajes de avance*/
        $consulta2 = "AND ( mdmj_tipo_mensaje = 2 OR mdmj_tipo_mensaje = 3 OR mdmj_tipo_mensaje = 4 OR mdmj_tipo_mensaje = 5) ".
                     "ORDER BY mdmj.mdmj_fecha DESC ";
    }
    if($tipo == 3){ /*Solo recomendaciones*/
        $consulta2 =" AND mdmj_tipo_mensaje = 6 ". 
                    " ORDER BY mdmj.mdmj_fecha DESC ";
    }
    if($tipo == 4){ /*Solo comentarios de actividades*/
        $consulta2 =" AND  mdmj_tipo_mensaje = 7 ". 
                    " ORDER BY mdmj.mdmj_fecha DESC ";
    }

    $consulta= $consulta.$consulta2;
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]        = $_fila["mdmj_id_mensaje"];
                $_resp[$i]["id_experiencia"]    = $_fila["mdmj_id_experiencia"];
                $_resp[$i]["id_usuario"]        = $_fila["mdmj_id_usuario"];
                $_resp[$i]["fecha"]             = $_fila["mdmj_fecha"];
                $_resp[$i]["mensaje"]           = $_fila["mdmj_mensaje"];
                $_resp[$i]["tipo"]              = $_fila["mdmj_tipo_mensaje"];
                $_resp[$i]["id_actividad"]      = $_fila["mdmj_id_actividad"];
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["url_imagen"]        = $_fila["u_url_imagen"];
                $i++;
            }
        }
    }

    return $_resp;
}
function dbMuralDisenoInsertarMeGustaMensaje(  $id_mensaje,
                                                $id_usuario_valora,
                                                $id_usuario_autor,
                                                $conexion) {

    $consulta = "INSERT INTO md_megusta_mensaje (".
                    "mdmg_id_mensaje, ".
                    "mdmg_id_usuario_valora, ".
                    "mdmg_id_usuario_autor ".
                    ") ".
                 "VALUES (".
                     $id_mensaje.", ".
                     $id_usuario_valora.", ".
                     $id_usuario_autor." ) ; ";
					 
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    return mysql_affected_rows();
    mysql_free_result($resultado);
}
function dbMuralDisenoObtenerMeGustaMensaje($id_mensaje, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "mdmg.mdmg_id_usuario_valora, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen, ".
                    "u.u_establecimiento, ".
                    "u.u_localidad, ".
                    "u.u_usuario ".
                    "FROM md_megusta_mensaje mdmg, usuario u ".
                    "WHERE ".
                    "mdmg.mdmg_id_mensaje = '".$id_mensaje."' AND  ".
                    "u.u_id_usuario =  mdmg.mdmg_id_usuario_valora ;";
    //echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                //$_resp[$i]["id_mensaje_respuesta"]  = $_fila["rm_id_mensaje_respuesta"];
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
function dbMuralDisenoGustaMensaje ($id_usuario_valora, $id_mensaje, $conexion){
    $consulta=      "SELECT count(*) as cont ".
                    "FROM md_megusta_mensaje mdmg ".
                    "WHERE ".
                    "mdmg.mdmg_id_mensaje = '".$id_mensaje."' AND  ".
                    "mdmg.mdmg_id_usuario_valora =  '".$id_usuario_valora."' ";
    //echo $consulta;

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp               = $_fila["cont"];
             }
        }
        return $resp;
    }
    else {
        return -1;
    }
    mysql_free_result($resultado);
}
function dbMuralDisenoNoGustaMensaje ($id_mensaje, $id_usuario_valora, $conexion){
    $consulta = "DELETE FROM ".
                 " md_megusta_mensaje ".
                 "WHERE ".
                    "mdmg_id_mensaje = ".$id_mensaje." AND ".
                    "mdmg_id_usuario_valora = ".$id_usuario_valora.";";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
function dbMuralDisenoInsertarMensajeRespuesta(  $id_mensaje_original,
                                                  $nombre,
                                                  $id_usuario_autor,
                                                  $id_usuario_responde,
                                                  $usuario,
                                                  $url_imagen,
                                                  $mensaje,
                                                  $conexion) {

    $_datos_consulta = array();
    $consulta = "INSERT INTO md_respuesta_mensajes (".
                    "mdrm_id_mensaje_original, ".
                    "mdrm_nombre, ".
                    "mdrm_id_usuario_autor, ".
                    "mdrm_id_usuario_responde, ".
                    "mdrm_usuario, ".
                    "mdrm_url_imagen, ".
                    "mdrm_fecha, ".
                    "mdrm_mensaje ".
                    ") ".
                 "VALUES (".
                     $id_mensaje_original.", ".
                     "'".$nombre."', ".
                     "'".$id_usuario_autor."', ".
                     "'".$id_usuario_responde."', ".
                     "'".$usuario."', ".
                     "'".$url_imagen."', ".
                     "now(), ".
                     "'".$mensaje."' ) ; ";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    return mysql_affected_rows();
    mysql_free_result($resultado);
}
function dbMuralDisenoObtenerMensajesEnRespuesta($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "mdrm.mdrm_id_mensaje_respuesta, ".
                    "mdrm.mdrm_usuario, ".
                    "mdrm.mdrm_fecha, ".
                    "mdrm.mdrm_mensaje, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM md_respuesta_mensajes mdrm, usuario u ".
                    "WHERE ".
                    "mdrm.mdrm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "mdrm.mdrm_usuario = u.u_usuario  ".
                    "ORDER BY mdrm.mdrm_fecha ASC ";
                    //Es mas comodo en orden temporal "ORDER BY rm.rm_fecha DESC ";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje_respuesta"]  = $_fila["mdrm_id_mensaje_respuesta"];
                $_resp[$i]["fecha"]                 = $_fila["mdrm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["mdrm_mensaje"];
                $_resp[$i]["usuario"]               = $_fila["mdrm_usuario"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]            = $_fila["u_url_imagen"];

                $i++;
            }
        }
    }
    return $_resp;
}
function dbMuralDisenoObtenerMensajesEnRespuestaResumen($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT ".
                    "mdrm.mdrm_id_mensaje_respuesta, ".
                    "mdrm.mdrm_usuario, ".
                    "mdrm.mdrm_fecha, ".
                    "mdrm.mdrm_mensaje, ".
                    "u.u_nombre, ".
                    "u.u_url_imagen ".
                    "FROM md_respuesta_mensajes mdrm, usuario u ".
                    "WHERE ".
                    "mdrm.mdrm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "mdrm.mdrm_usuario = u.u_usuario  ".
                    "ORDER BY mdrm.mdrm_fecha DESC ".
                    "LIMIT 3";

    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje_respuesta"]  = $_fila["mdrm_id_mensaje_respuesta"];
                $_resp[$i]["fecha"]                 = $_fila["mdrm_fecha"];
                $_resp[$i]["mensaje"]               = $_fila["mdrm_mensaje"];
                $_resp[$i]["usuario"]               = $_fila["mdrm_usuario"];
                $_resp[$i]["nombre"]                = $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]            = $_fila["u_url_imagen"];
                $i++;
            }
        }
    }
    return $_resp;
}
function dbMuralDisenoObtenerNumMensajesEnRespuesta($id_mensaje_original, $conexion){
    $_resp = null;
    $consulta=      "SELECT count(*) as cont ".
                    "FROM md_respuesta_mensajes mdrm, usuario u ".
                    "WHERE ".
                    "mdrm.mdrm_id_mensaje_original = '".$id_mensaje_original."' AND  ".
                    "mdrm.mdrm_usuario = u.u_usuario  ";
 //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $_fila = mysql_fetch_array($resultado, MYSQL_BOTH);
            $_resp  = $_fila["cont"];
        }
    }
    return $_resp;
}
function dbMuralDisenoObtenerUsuariosConversacion($id_mensaje_original, $conexion){
    $_resp=array();
    $consulta = "SELECT DISTINCT  ".
                    "u.u_id_usuario, u.u_nombre, u.u_usuario,  u.u_email, u.u_localidad, u.u_establecimiento, u.u_url_imagen ".
                 "FROM ".
                    "md_respuesta_mensajes mdrm, usuario u ".
                 "WHERE ".
                   "mdrm.mdrm_id_mensaje_original  = '".$id_mensaje_original."'  AND ".
                   "mdrm.mdrm_usuario = u.u_usuario  ";

    $consulta2 = "SELECT ".
                    "u.u_id_usuario, u.u_nombre, u.u_usuario,  u.u_email, u.u_localidad, u.u_establecimiento, u.u_url_imagen ".
                 "FROM ".
                    "md_mensajes mdmj, usuario u ".
                 "WHERE ".
                   "mdmj.mdmj_id_mensaje  = '".$id_mensaje_original."'  AND ".
                   "mdmj.mdmj_id_usuario = u.u_id_usuario  ";
//    echo $consulta;
//    echo $consulta2;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);
    $i=0;
    if($resultado2){
        if(mysql_num_rows($resultado2) >0) {
            $_fila2=mysql_fetch_array($resultado2,MYSQL_BOTH);
            $_resp[$i]["id"]                = $_fila2["u_id_usuario"];
            $_resp[$i]["nombre"]            = $_fila2["u_nombre"];
            $_resp[$i]["usuario"]           = $_fila2["u_usuario"];
            $_resp[$i]["email"]             = $_fila2["u_email"];
            $_resp[$i]["localidad"]         = $_fila2["u_localidad"];
            $_resp[$i]["establecimiento"]   = $_fila2["u_establecimiento"];
            $_resp[$i]["url_imagen"]        = $_fila2["u_url_imagen"];
            $i++;
        }
    }
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                if($_fila["u_id_usuario"]!= $_resp[0]["id"] ){
                    $_resp[$i]["id"]                = $_fila["u_id_usuario"];
                    $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                    $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                    $_resp[$i]["email"]             = $_fila["u_email"];
                    $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                    $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                    $_resp[$i]["url_imagen"]        = $_fila["u_url_imagen"];
                    $i++;
                }
            }

        }
        else {
            $_resp =null;
        }

    }
    else {
        $_resp =null;
    }
    mysql_free_result($resultado);
    return $_resp;
}
function dbMuralDisenoObtenerProfesoresEjecutando($id_diseno, $conexion) {
    $_resp=array();

    //Obtiene información sobre los estudiantes de una expriencia
    $consulta = "SELECT distinct ". 
                    "U.u_id_usuario, ".
                    "U.u_nombre, ".
                    "U.u_email, ".
                    "U.u_usuario, ".
                    "U.u_establecimiento, ".
                    "U.u_localidad, ".
                    "U.u_url_imagen, ".
                    "ED.ed_fecha_termino ".
                "FROM ".
                    " experiencia_didactica ED, usuario_experiencia UE, usuario U ".
                "WHERE ".
                    "ED.ed_id_diseno_didactico = ".$id_diseno." AND ".
                    "ED.ed_id_experiencia = UE.ue_id_experiencia AND ".
                    "ED.ed_publicado=1 AND ".
                    "UE.ue_id_usuario = U.u_id_usuario  AND ".
                    "ED.ed_id_profesor = U.u_id_usuario  ".
                    "AND ED.ed_fecha_termino is null  ".
                    "GROUP BY U.u_id_usuario ".
                    "ORDER BY ED.ed_fecha_termino, U.u_nombre ";

   $resultado = dbEjecutarConsulta($consulta, $conexion);
//   echo $consulta;
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["fecha_termino"]     = $_fila["ed_fecha_termino"];
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
function dbMuralDisenoObtenerProfesoresEjecutaron($id_diseno, $conexion) {
    $_resp=array();

    //Obtiene información sobre los estudiantes de una expriencia
    $consulta = "SELECT distinct ". 
                    "U.u_id_usuario, ".
                    "U.u_nombre, ".
                    "U.u_usuario, ".
                    "U.u_establecimiento, ".
                    "U.u_localidad, ".
                    "U.u_url_imagen, ".
                    "ED.ed_fecha_termino ".
                "FROM ".
                    " experiencia_didactica ED, usuario_experiencia UE, usuario U ".
                "WHERE ".
                    "ED.ed_id_diseno_didactico = ".$id_diseno." AND ".
                    "ED.ed_id_experiencia = UE.ue_id_experiencia AND ".
                    "ED.ed_publicado=1 AND ".
                    "UE.ue_id_usuario = U.u_id_usuario  AND ".
                    "ED.ed_id_profesor = U.u_id_usuario  ".
                    "AND ED.ed_fecha_termino is not null  ".
                    "GROUP BY U.u_id_usuario ".
                    "ORDER BY ED.ed_fecha_termino, U.u_nombre ";

   $resultado = dbEjecutarConsulta($consulta, $conexion);
//   echo $consulta;
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["fecha_termino"]     = $_fila["ed_fecha_termino"];
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
function dbMuralDisenoObtenerColaboradores($id_diseno, $conexion) {
    $_resp=array();

    //Obtiene información sobre los estudiantes de una expriencia
    $consulta = "SELECT distinct ".
                    "U.u_nombre, ".
                    "U.u_id_usuario, ".
                    "U.u_usuario, ".
                    "U.u_establecimiento, ".
                    "U.u_localidad, ".
                    "U.u_email, ".
                    "U.u_url_imagen ".
                "FROM ".
                    " experiencia_didactica ED, usuario U, usuario_experiencia UE ".
                "WHERE ".
                    "ED.ed_id_diseno_didactico = ".$id_diseno." AND ".
                    "ED.ed_id_experiencia = UE.ue_id_experiencia AND ".
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
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
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
function dbExpObtenerIdDiseno ($id_experiencia,$conexion){
    $id_diseno= null;
    $consulta = "SELECT ".
                "ED.ed_id_diseno_didactico ".
            "FROM ".
                " experiencia_didactica ED ".
            "WHERE ".
                "ED.ed_id_experiencia = ".$id_experiencia."  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
//    echo $consulta;
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $id_diseno = $_fila["ed_id_diseno_didactico"];
        mysql_free_result($resultado);
    }

    return $id_diseno;
}
function dbMuralUsuarioObtenerIdMensajeMd ($id_mensaje_mu,$conexion){
    $id_diseno= null;
    $consulta = "SELECT ".
                "mdmj_id_mensaje ".
            "FROM ".
                " md_mensajes ".
            "WHERE ".
                "mdmj_id_mensaje_mu = ".$id_mensaje_mu."  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
//   echo $consulta;
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $id_md = $_fila["mdmj_id_mensaje"];
        mysql_free_result($resultado);
    }

    return $id_md;
}
function dbMuralDisenoObtenerIdMensajeMu ($id_mensaje_md,$conexion){
    $id_diseno= null;
    $consulta = "SELECT ".
                "mdmj_id_mensaje_mu ".
            "FROM ".
                " md_mensajes ".
            "WHERE ".
                "mdmj_id_mensaje = ".$id_mensaje_md."  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
//    echo $consulta;
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $id_mu = $_fila["mdmj_id_mensaje_mu"];
        mysql_free_result($resultado);
    }

    return $id_mu;
}

function dbMuralDisenoObtenerIdMensaje ($id_mensaje_mu, $conexion){
    $id_diseno= null;
    $consulta = "SELECT ".
                "mdmj_id_mensaje ".
            "FROM ".
                " md_mensajes ".
            "WHERE ".
                "mdmj_id_mensaje_mu = ".$id_mensaje_mu."  ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
//    echo $consulta;
    if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $id_md = $_fila["mdmj_id_mensaje"];
        mysql_free_result($resultado);
    }

    return $id_md;
}
function dbMuralDisenoNuevosMensajes($id_diseno, $id_ultimo_mensaje, $tipo, $conexion){
    $resp=0;
    $consulta = "SELECT count(*) as total ".
                "FROM md_mensajes ".
                "WHERE ".
                "mdmj_id_diseno = '".$id_diseno."' AND ".
                "mdmj_id_mensaje > '".$id_ultimo_mensaje."' AND ".
                "mdmj_tipo_mensaje <= '".$tipo."'";

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
function dbMuralUsuarioNuevosMensajes($id_usuario, $id_ultimo_mensaje, $conexion){
    $resp=0;
    $consulta = "SELECT count(*) as total ".
                "FROM mu_mensajes ".
                "WHERE ".
                "mumj_id_usuario_muro = '".$id_usuario."' AND ".
                "mumj_id_mensaje > '".$id_ultimo_mensaje."'";

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
function dbBitacoraObtenerInfoExperiencias($et_clase_gemela, $conexion){
    $_resp=array();

    $consulta = "SELECT ".
                    "ED.ed_id_experiencia, ED.ed_localidad, ED.ed_colegio, ED.ed_curso, 
                     ED.ed_fecha_termino, U.u_nombre, U.u_usuario, U.u_url_imagen  ".
                 "FROM ".
                    "experiencia_didactica ED,usuario U, usuario_experiencia UE ".
                 "WHERE ".
                    "ED.ed_etiqueta_gemela = '".$et_clase_gemela."' AND ".
                    "ED.ed_publicado=1 AND ".
                    "ED.ed_id_experiencia = UE.ue_id_experiencia  AND ".
                    "UE.ue_rol_usuario =1 AND ".
                    "UE.ue_id_usuario = U.u_id_usuario ;";

//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_experiencia"]    = $_fila["ed_id_experiencia"];
                $_resp[$i]["localidad"]         = $_fila["ed_localidad"];
                $_resp[$i]["colegio"]           = $_fila["ed_colegio"];
                $_resp[$i]["curso"]             = $_fila["ed_curso"];
                $_resp[$i]["fecha_termino"]     = $_fila["ed_fecha_termino"];
                $_resp[$i]["nombre_profesor"]   = $_fila["u_nombre"];
                $_resp[$i]["usuario_profesor"]  = $_fila["u_usuario"];
                $_resp[$i]["imagen_profesor"]   = $_fila["u_url_imagen"];
                $i++;
            }
        }
        else {
            //No existe clase gemela
            $_resp =null;
        }
        mysql_free_result($resultado);
    }
    else {
        $_resp =null;
    }
    return $_resp;
}
function dbMuralDisenoUltimosMensajes ( $id_usuario,$conexion) { 
    $i = 0;
    //consulta por las experiencias en que participa el profesor o colaborador
    $consulta_dd = "SELECT ".
                    "E.ed_id_experiencia ".
                "FROM ".
                    "experiencia_didactica E, ".
                    "usuario_experiencia UE ".
                "WHERE ".
                    "UE.ue_id_usuario = '".$id_usuario."' AND ".
                    "UE.ue_id_experiencia = E.ed_id_experiencia AND ".
                    "E.ed_fecha_termino IS NULL ";
    
//    echo $consulta_dd;
    
    $resultado_dd = dbEjecutarConsulta($consulta_dd,$conexion);
    $consulta2 = "";
    if($resultado_dd) {
        if (mysql_num_rows($resultado_dd) > 0){
            while ($_fila = mysql_fetch_array($resultado_dd, MYSQL_BOTH)) {
                if($i>0){
                     $consulta2= $consulta2. " OR mdmj.mdmj_id_experiencia= '".$_fila["ed_id_experiencia"]."' ";
                }
                else{
                    $consulta2 = "mdmj.mdmj_id_experiencia = '".$_fila["ed_id_experiencia"]."'";
                }
                $i++;
            }
        }
    }
    // Incluir todos los ids resultantes en la busqueda de mensajes
    $_resp = null;
    $consulta= "SELECT ".
                    "mdmj.mdmj_id_mensaje, ".
                    "mdmj.mdmj_id_experiencia, ".
                    "mdmj.mdmj_id_usuario , ".
                    "mdmj.mdmj_fecha, ".
                    "mdmj.mdmj_mensaje, ".
                    "mdmj.mdmj_tipo_mensaje, ".
                    "mdmj.mdmj_id_actividad, ".
                    "u.u_nombre, ".
                    "u.u_email, ".
                    "u.u_localidad, ".
                    "u.u_establecimiento, ".
                    "u.u_usuario, ".
                    "u.u_url_imagen ".
                    "FROM md_mensajes mdmj, usuario u ".
                    "WHERE (".
    $consulta = $consulta. $consulta2.' )';
    $consulta3 =     " AND mdmj.mdmj_id_usuario = u.u_id_usuario  ".
                     " AND mdmj_tipo_mensaje = 0 ".
                     "ORDER BY mdmj.mdmj_fecha DESC ".
                     "LIMIT 0,20 ";

    $consulta= $consulta.$consulta3;
//   echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]        = $_fila["mdmj_id_mensaje"];
                $_resp[$i]["id_experiencia"]    = $_fila["mdmj_id_experiencia"];
                $_resp[$i]["id_usuario"]        = $_fila["mdmj_id_usuario"];
                $_resp[$i]["fecha"]             = $_fila["mdmj_fecha"];
                $_resp[$i]["mensaje"]           = $_fila["mdmj_mensaje"];
                $_resp[$i]["tipo"]              = $_fila["mdmj_tipo_mensaje"];
                $_resp[$i]["id_actividad"]      = $_fila["mdmj_id_actividad"];
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["email"]             = $_fila["u_email"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["url_imagen"]        = $_fila["u_url_imagen"];
                $i++;
            }
        }
    }

    return $_resp;
}
function dbActualizarUltimoAcceso($id_usuario,$fecha_acceso,$conexion) {
    $resp=false;

    $consulta = "UPDATE usuario ".
                "SET u_fecha_ultimo_acceso='".$fecha_acceso."' ".
                "WHERE u_id_usuario=".$id_usuario;
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if(mysql_affected_rows($conexion) > 0) {
            $resp=true;
        }
    }
    return $resp;
}
function dbActualizarUltimoAccesoExperiencia($id_experiencia,$fecha_acceso,$conexion) {
    $resp=false;

    $consulta = "UPDATE experiencia_didactica ".
                "SET ed_fecha_ultima_sesion='".$fecha_acceso."' ".
                "WHERE ed_id_experiencia=".$id_experiencia;
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if ($resultado) {
        if(mysql_affected_rows($conexion) > 0) {
            $resp=true;
        }
    }
    return $resp;
}
function dbBitacoraUltimosMensajes ( $id_usuario,$conexion) { 
    $i = 0;
    //Consulta por las experiencias en que participa el profesor o colaborador
    $consulta_dd = "SELECT ".
                    "ED.ed_id_experiencia ".
                "FROM ".
                    "usuario_experiencia UE, experiencia_didactica ED ".
                "WHERE ".
                    "UE.ue_id_usuario = '".$id_usuario."' AND ".
                    "UE.ue_id_experiencia = ED.ed_id_experiencia AND ".
                    "ED.ed_fecha_termino IS NULL ";
    $resultado_dd = dbEjecutarConsulta($consulta_dd,$conexion);
    $consulta2 = "";
    if($resultado_dd) {
        if (mysql_num_rows($resultado_dd) > 0){
            while ($_fila = mysql_fetch_array($resultado_dd, MYSQL_BOTH)) {
                if($i>0){
                     $consulta2= $consulta2. " OR bthm.bthm_id_experiencia = '".$_fila["ed_id_experiencia"]."' ";
                }
                else{
                    $consulta2 = "bthm.bthm_id_experiencia = '".$_fila["ed_id_experiencia"]."'";
                }
                $i++;
            }
        }
    }
    $consulta = "SELECT ".
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
                    "u.u_usuario ".
                    "FROM bt_historial_mensajes bthm , usuario u ".
                    "WHERE ";
      $consulta = $consulta.'('.$consulta2.')';
      $consulta3 =  " AND bthm.bthm_usuario = u.u_usuario  ".
                    "ORDER BY bthm.bthm_fecha DESC ".
                    "LIMIT 0,20";
      $consulta = $consulta.$consulta3;
      $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0){
            $i=0;
            while ($_fila = mysql_fetch_array($resultado, MYSQL_BOTH)) {
                $_resp[$i]["id_mensaje"]        = $_fila["bthm_id_mensaje"];
                $_resp[$i]["id_usuario"]        = $_fila["bthm_usuario"];
                $_resp[$i]["fecha"]             = $_fila["bthm_fecha"];
                $_resp[$i]["mensaje"]           = $_fila["bthm_mensaje"];
                $_resp[$i]["id_grupo"]           = $_fila["bthm_id_grupo"];
                $_resp[$i]["nombre_grupo"]      = $_fila["bthm_nombre_grupo"];
                $_resp[$i]["id_actividad"]      = $_fila["bthm_id_actividad"];
                $_resp[$i]["id_experiencia"]    = $_fila["bthm_id_experiencia"];
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["url_imagen"]        = $_fila["u_url_imagen"];
                $i++;
            }
        }
    }
    return $_resp;
}
function dbExpSemestresExperiencias($conexion){
    $consulta = "SELECT DISTINCT ".
                "ED.ed_semestre, ED.ed_anio ".
                "FROM experiencia_didactica ED ".
                "WHERE ED.ed_fecha_termino IS NOT NULL ".
                "AND (ED.ed_semestre IS NOT NULL AND ED.ed_semestre!=0) ".
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
function dbDisObtenerImagenHerramientaWebDD($id_dd,$conexion){
    $_resp = null;
    $consulta = "SELECT ".
            "HW.hw_nombre, HW.hw_enlace, HW.hw_imagen  ".
            "FROM herramientas_web HW, herramientas_diseno HD ".
            "WHERE  HD.hd_id_diseno_didactico = ".$id_dd." ".
            "AND HD.hd_id_herramienta = HW.hw_id_herramienta ";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
     if($resultado) {
        $_fila=mysql_fetch_array($resultado,MYSQL_BOTH);
        $_resp["nombre"]= $_fila["hw_nombre"];
        $_resp["enlace"]= $_fila["hw_enlace"];
        $_resp["imagen"]= $_fila["hw_imagen"];
        mysql_free_result($resultado);
    }
    else {
        $_resp =null;
    }
    
    return $_resp;
}

function dbDisInsertaImagenHerramientaWebDD($nombre,$enlace, $imagen, $conexion){
    $resp=false;
    $consulta = "INSERT INTO herramientas_web(".
                    "hw_nombre, ".
                    "hw_enlace, ".
                    "hw_imagen) ".
                 "VALUES (".
                     "'".$nombre."' ,".
                     "'".$enlace."' ,".
                     $imagen.");";
    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_affected_rows($conexion) >0) {
            $resp =true;
            }
        }
    else {
        //ERROR MYSQL
    }
    return $resp;
    
}
function dbDisVincularImagenHerramientaWebDD($id_herramienta, $id_dd, $conexion){
    $resp=false;
    $consulta = "INSERT INTO herramientas_diseno(".
                    "hd_id_herramienta, ".
                    "hd_id_diseno_didactico) ";
                 "VALUES (".
                     "'".$id_herramienta."' ,".
                     $id_dd.");";
    //echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_affected_rows($conexion) >0) {
            $resp =true;
            }
        }
    else {
        //ERROR MYSQL
    }
    return $resp;
}
function dbDisObtenerProfesoresEjecutaron($id_diseno, $conexion) {
    $_resp=array();
    $_id=null;
    $consulta2="SELECT dd_id_diseno_previo FROM diseno_didactico where dd_id_diseno_didactico=".$id_diseno."";

    $resultado2 = dbEjecutarConsulta($consulta2, $conexion);

    if($resultado2) {
        if(mysql_num_rows($resultado2)>0) {
            while ($_fila=mysql_fetch_array($resultado2,MYSQL_BOTH)) {
                $_id = $_fila["dd_id_diseno_previo"];
        }
     }
    }
    //Obtiene información sobre los estudiantes de una expriencia
    $consulta = "SELECT distinct ". 
                    "U.u_id_usuario, ".
                    "U.u_nombre, ".
                    "U.u_usuario, ".
                    "U.u_establecimiento, ".
                    "U.u_localidad, ".
                    "U.u_url_imagen ".
//                    "ED.ed_fecha_termino ".
                "FROM ".
                    " experiencia_didactica ED, usuario_experiencia UE, usuario U ".
                "WHERE ".
                    "ED.ed_id_diseno_didactico = ".$id_diseno." AND ".
                    "ED.ed_fecha_termino is not NULL AND ".
                    "ED.ed_id_experiencia = UE.ue_id_experiencia AND ".
                    "UE.ue_id_usuario = U.u_id_usuario  AND ".
                    "ED.ed_id_profesor = U.u_id_usuario  ";
                    
    if(!is_null($_id)){
        $consulta.= "UNION ".
                "SELECT distinct ".
                    "U.u_id_usuario, ".
                    "U.u_nombre, ".
                    "U.u_usuario, ".
                    "U.u_establecimiento, ".
                    "U.u_localidad, ".
                    "U.u_url_imagen ".
//                    "ED.ed_fecha_termino ".
                "FROM ".
                    " experiencia_didactica ED, usuario_experiencia UE, usuario U ".
                "WHERE ".
                    "ED.ed_id_diseno_didactico = ".$_id." AND ".
                    "ED.ed_fecha_termino is not NULL AND ".
                    "ED.ed_id_experiencia = UE.ue_id_experiencia AND ".
                    "UE.ue_id_usuario = U.u_id_usuario  AND ".
                    "ED.ed_id_profesor = U.u_id_usuario  ";
                   
    }
    $consulta.= "ORDER BY u_nombre ";

    $resultado = dbEjecutarConsulta($consulta, $conexion);
    
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            $i=0;
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["nombre"]            = $_fila["u_nombre"];
                $_resp[$i]["id_usuario"]        = $_fila["u_id_usuario"];
                $_resp[$i]["usuario"]           = $_fila["u_usuario"];
                $_resp[$i]["establecimiento"]   = $_fila["u_establecimiento"];
                $_resp[$i]["localidad"]         = $_fila["u_localidad"];
                $_resp[$i]["imagen"]            = $_fila["u_url_imagen"];
                $_resp[$i]["fecha_termino"]     = $_fila["ed_fecha_termino"];
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
function dbObtenerActividadFinalizadora($id_experiencia, $conexion){
    $_resp=array();
    $consulta = "SELECT ".
                    "ac.ac_id_actividad ".
                 "FROM ".
                    "experiencia_didactica ed, diseno_didactico dd, etapa e, actividad ac ".
                 "WHERE ".
                   "ed.ed_id_experiencia  = '".$id_experiencia."' AND ".
                   "ed.ed_id_diseno_didactico  = dd.dd_id_diseno_didactico AND ".
                   "e.e_id_diseno_didactico = dd.dd_id_diseno_didactico AND ".
                   "ac.ac_id_etapa = e.e_id_etapa AND  ".
                   "ac.ac_finalizadora = 1";
//    echo $consulta;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            while ($_fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp["id"]      = $_fila["ac_id_actividad"];
            }
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
function dbBitacoraEliminarMensaje($id_mensaje, $conexion){
    $consulta = "DELETE FROM ".
                 " bt_historial_mensajes ".
                 "WHERE ".
                    "bthm_id_mensaje = ".$id_mensaje."  ";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
function dbBitacoraEliminarRespuestaMensaje($id_mensaje, $conexion){
    $consulta = "DELETE FROM ".
                 " bt_respuesta_mensajes ".
                 "WHERE ".
                    "btrm_id_mensaje_respuesta = ".$id_mensaje."  ";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        return mysql_affected_rows();
    }
    else {
        return -1;
    }
}
function dbInsertarUsuarioGrupo($id_usuario, $id_grupo,$conexion){
    $resp=false;
    $consulta = "INSERT INTO usuario_grupo(".
                    "ug_id_usuario, ".
                    "ug_id_grupo ".
                    ") ".
                "VALUES (".
                    $id_usuario.", ".
                    $id_grupo.")";
    $resultado = dbEjecutarConsulta($consulta, $conexion);
     if($resultado) {
        if(mysql_affected_rows($conexion) >0) {
            $resp =true;
            }
        }
    else {
        //ERROR MYSQL
    }
    return $resp;
}

function dbEliminarUsuarioGrupo($id_usuario, $id_grupo,$conexion){
    $resp=false;
    $consulta = "DELETE FROM ".
                 " usuario_grupo ".
                 "WHERE ".
                    "ug_id_usuario = ".$id_usuario."  ".
                 "AND ".
                    "ug_id_grupo = ".$id_grupo;
    $resultado = dbEjecutarConsulta($consulta, $conexion);
    if($resultado) {
        if(mysql_affected_rows($conexion) >0) {
            $resp =true;
            }
    }
    else {
        //ERROR MYSQL
    }
    return $resp;
}
function dbObtenerEstudiantesSinAsignar($id_experiencia,$conexion){
    $_resp=null;

    $consulta = "SELECT ue_id_usuario,u_nombre, u_url_imagen ".
                "FROM usuario_experiencia, usuario ".
                "WHERE ue_id_usuario NOT ".
                "IN ( ".
                "SELECT UG.ug_id_usuario ".
                "FROM grupo G, usuario_grupo UG ".
                "WHERE G.g_id_experiencia =".$id_experiencia." ".
                "AND G.g_id_grupo = UG.ug_id_grupo) ".
                "AND ue_id_experiencia =".$id_experiencia." ".
                "AND ue_rol_usuario=2 ".
                "AND ue_id_usuario=u_id_usuario ";

    $resultado = dbEjecutarConsulta($consulta,$conexion);
    if($resultado) {
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $_resp[$i]["id_usuario"]      =  $_fila["ue_id_usuario"];
                $_resp[$i]["nombre_usuario"]  =  $_fila["u_nombre"];
                $_resp[$i]["url_imagen"]      =  $_fila["u_url_imagen"];
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
function dbObtenerNombreDiseno($conexion,$id_dd){


}
function dbExpObtenerFechaTermino($conexion,$id_exp){
       $resp="";

    $consulta = "SELECT ".
                    "ED.ed_fecha_termino ".
                "FROM ".
                    "experiencia_didactica ED ".
                "WHERE ".
                    "ED.ed_id_experiencia = ".$id_exp;
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        if(mysql_num_rows($resultado) >0) {
            if ($fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp =$fila["ed_fecha_termino"];
            }
            return $resp;
        }
    }
    
}

function dbObtenerComunidadProfesores($conexion){

    $resp=null;

    $consulta = "SELECT ".
                    "* ".
                "FROM ".
                    "usuario U ".
                "WHERE ".
                    "U.u_inscribe_diseno = 1";
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            $resp=array();
            $i=0;
            while($fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp[$i]["nombre"]= $fila["u_nombre"];
                $resp[$i]["nombre_usuario"] =$fila["u_usuario"];
                $resp[$i]["url_imagen"] =$fila["u_url_imagen"];
                $i++;
            }
        }
    }
    return $resp;

}

function dbObtenerComunidadProfesoresLimite($conexion,$lim_inf,$lim_sup){

    $resp=null;

    $consulta = "SELECT ".
                    "* ".
                "FROM ".
                    "usuario U ".
                "WHERE ".
                    "U.u_inscribe_diseno = 1 ".
                "ORDER BY U.u_id_usuario ASC ".
                "LIMIT ".$lim_inf.",".$lim_sup;
  
    $resultado = dbEjecutarConsulta($consulta, $conexion);

    if($resultado) {
        if(mysql_num_rows($resultado)>0) {
            $resp=array();
            $i=0;
            while($fila=mysql_fetch_array($resultado,MYSQL_BOTH)) {
                $resp[$i]["nombre"]= $fila["u_nombre"];
                $resp[$i]["nombre_usuario"] =$fila["u_usuario"];
                $resp[$i]["url_imagen"] =$fila["u_url_imagen"];
                $i++;
            }
        }
    }
    return $resp;

}

//Código agregado por Jordan Barría el 28-10-14
function dbLogCrearSesion($id_usuario,$navegador_usuario,$ip_usuario,$sistema_operativo_usuario,$conexion){
    $consulta_creacion_sesion="INSERT INTO log_sesion 
                               (s_id_usuario,
                                s_fecha_inicio_sesion,
                                s_navegador,
                                s_ip_sesion,
                                s_sistema_operativo)
                               VALUES (".$id_usuario.",
                                now(),'".
                                $navegador_usuario."','".
                                $ip_usuario."','".
                                $sistema_operativo_usuario."')";
    $resultado_creacion_sesion=dbEjecutarConsulta($consulta_creacion_sesion, $conexion);
    $id_sesion=mysql_insert_id();
    return $id_sesion;
}

function dbLogCerrarSesion($id_sesion,$tipo_cierre_sesion,$conexion){
    $consulta_cierre_sesion="UPDATE log_sesion
                            SET s_fecha_cierre_sesion=now(),
                                s_tipo_cierre_sesion=".$tipo_cierre_sesion."
                            WHERE s_id_sesion=".$id_sesion;
    $resultado_cierre_sesion=dbEjecutarConsulta($consulta_cierre_sesion,$conexion);
}

function dbLogRevertirCierreSesion($id_sesion,$conexion){
    $consulta_revertir_cierre_sesion="UPDATE log_sesion
                            SET s_fecha_cierre_sesion=NULL,
                                s_tipo_cierre_sesion=NULL
                            WHERE s_id_sesion=".$id_sesion;
    $resultado_revertir_cierre_sesion=dbEjecutarConsulta($consulta_revertir_cierre_sesion,$conexion);
}

function dbLogActualizarPerfil($id_sesion,$tipo_actualizacion,$conexion){
    $consulta_act_perfil="INSERT INTO log_act_perfil
                        (ap_id_sesion,
                        ap_fecha,
                        ap_tipo_actualizacion)
                        VALUES (".$id_sesion.",
                        now(),".
                        $tipo_actualizacion.")";
    $resultado_act_perfil=dbEjecutarConsulta($consulta_act_perfil,$conexion);
}

//Modificado el 31-10-14
function dbLogClickVisitaSeccion($id_sesion,$nombre_seccion,$conexion){
    $consulta_click_seccion="INSERT INTO log_click_seccion
                                (cs_id_sesion,
                                 cs_fecha,
                                 cs_seccion_pagina)
                                VALUES (".$id_sesion.",
                                 now(),'".
                                 $nombre_seccion."')";
    $resultado_click_seccion=dbEjecutarConsulta($consulta_click_seccion,$conexion);
}

//Agregado el 24-11-2014
function dbLogVisualizacionAccesoDetalleInfo($id_sesion,$id_experiencia,$id_elemento,$accion_detalle,$vista_activa,$perspectiva_activa,$tamano_elemento,$zoom_activo,$conexion){
    $consulta_vis_acceso_info="INSERT INTO vis_accion_detalleinfo
                                            (id_sesion,
                                            id_experiencia,
                                            accion,
                                            fecha,
                                            id_elemento,
                                            vista_activa,
                                            perspectiva_activa,
                                            tamano_elemento,
                                            zoom_activo)
                                            VALUES (".$id_sesion.",
                                            ".$id_experiencia.",
                                            ".$accion_detalle.",
                                            now(),
                                            '".$id_elemento."',
                                            ".$vista_activa.",
                                            ".$perspectiva_activa.",
                                            ".$tamano_elemento.",
                                            ".$zoom_activo.")";
    $resultado_vis_detalleinfo=dbEjecutarConsulta($consulta_vis_acceso_info,$conexion);
}

//Agregado el 24-11-2014
function dbLogVisualizacionCambioVista($id_sesion,$id_experiencia,$tipo_cambio_vista,$vista_transicion,$perspectiva_transicion,$conexion){
    $consulta_vis_cambio_vista="INSERT INTO vis_cambio_vista
                                            (id_sesion,
                                            id_experiencia,
                                            tipo_cambio_vista,
                                            fecha,
                                            vista_transicion,
                                            perspectiva_transicion)
                                            VALUES (".$id_sesion.",
                                            ".$id_experiencia.",
                                            ".$tipo_cambio_vista.",
                                            now(),
                                            ".$vista_transicion.",
                                            ".$perspectiva_transicion.")";
    $resultado_vis_cambio_vista=dbEjecutarConsulta($consulta_vis_cambio_vista,$conexion);
}

function dbLogActualizarDespliegueAyuda($id_usuario,$despliegue_ayuda_clase,$despliegue_ayuda_selfcentered,$conexion){
    $consulta_actualizacion=null;
    
    if ($despliegue_ayuda_clase==-1){
        $act_ayuda_clase=false;
    }else{
        $act_ayuda_clase=true;
    }

    if($despliegue_ayuda_selfcentered==-1){
        $act_ayuda_selfcentered=false;
    }else{
        $act_ayuda_selfcentered=true;

    }
    if ($act_ayuda_clase && $act_ayuda_selfcentered){
        $consulta_actualizacion="UPDATE vis_despliegue_ayuda
                                 SET desplegado_ayuda_vista=".$despliegue_ayuda_clase.",
                                    desplegado_ayuda_selfcentered=".$despliegue_ayuda_selfcentered."
                                 WHERE id_usuario=".$id_usuario;
    }elseif($act_ayuda_clase){
        $consulta_actualizacion="UPDATE vis_despliegue_ayuda
                                 SET desplegado_ayuda_vista=".$despliegue_ayuda_clase."
                                 WHERE id_usuario=".$id_usuario;
    }elseif($act_ayuda_selfcentered){
        $consulta_actualizacion="UPDATE vis_despliegue_ayuda
                                 SET desplegado_ayuda_selfcentered=".$despliegue_ayuda_selfcentered."
                                 WHERE id_usuario=".$id_usuario;
    }
    if($consulta_actualizacion){
        $resultado_actualizacion=dbEjecutarConsulta($consulta_actualizacion,$conexion);
    }
}

//Código agregado el 05-04-14
function dbObtenerArrayExperienciasGemelas($id_experiencia,$conexion){
    $consulta_datos_experiencia='SELECT ed_id_diseno_didactico,ed_semestre,ed_anio
                                 FROM experiencia_didactica
                                 WHERE ed_id_experiencia='.$id_experiencia;

    $res_consulta_experiencia = dbEjecutarConsulta($consulta_datos_experiencia,$conexion);
    if ($res_consulta_experiencia){
        if ($datos_experiencia = mysql_fetch_assoc($res_consulta_experiencia)){
            $id_diseno_didactico=$datos_experiencia['ed_id_diseno_didactico'];
            $semestre_experiencia=$datos_experiencia['ed_semestre'];
            $anio_experiencia=$datos_experiencia['ed_anio'];
        }
    }

    $consulta_experiencias='SELECT ed_id_experiencia , ed_curso , ed_colegio
                            FROM  experiencia_didactica
                            WHERE ed_id_diseno_didactico='.$id_diseno_didactico.
                                ' AND ed_semestre="'.$semestre_experiencia.'"
                                  AND ed_anio='.$anio_experiencia;

    $res_experiencias = dbEjecutarConsulta($consulta_experiencias,$conexion);
    $lista_experiencias = array();
    if($res_experiencias){
        while ($experiencia = mysql_fetch_assoc($res_experiencias)){
            array_push($lista_experiencias,$experiencia);
        }
    }
    return $lista_experiencias;
}

function dbObtenerMensajesBitacoraExperiencias($array_id_experiencias , $conexion){
    $string_lista_experiencias=implode(',',$array_id_experiencias);
    $string_lista_experiencias='('.$string_lista_experiencias.')';

    $consulta_mensajes_nuevos = 'SELECT bthm_mensaje
                                FROM bt_historial_mensajes
                                WHERE bthm_id_experiencia IN '.$string_lista_experiencias;

    $array_mensajes_experiencias = array();

    $res_consulta_mensajes_nuevos = dbEjecutarConsulta($consulta_mensajes_nuevos,$conexion);
    if ($res_consulta_mensajes_nuevos){
        while ($mensaje = mysql_fetch_assoc($res_consulta_mensajes_nuevos)){
            array_push($array_mensajes_experiencias,$mensaje['bthm_mensaje']);
        }
    }

    $consulta_mensajes_respuesta = 'SELECT btrm_mensaje
                                    FROM bt_respuesta_mensajes , bt_historial_mensajes
                                    WHERE btrm_id_mensaje_original = bthm_id_mensaje
                                    AND bthm_id_experiencia IN '.$string_lista_experiencias;

    $res_consulta_mensajes_rptas = dbEjecutarConsulta($consulta_mensajes_respuesta,$conexion);
    if ($res_consulta_mensajes_rptas){
        while ($mensaje = mysql_fetch_assoc($res_consulta_mensajes_rptas)){
            array_push($array_mensajes_experiencias,$mensaje['btrm_mensaje']);
        }
    }
    
    return $array_mensajes_experiencias;
}
//Fin codigo agregado por Jordan Barría

?>
