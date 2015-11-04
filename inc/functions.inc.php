<?php
/**
 * Contiene funciones generales usadas por otros archivos PHP
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Daniel Guerra - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
define("SECOND", 1);
define("MINUTE", 60 * SECOND);
define("HOUR", 60 * MINUTE);
define("DAY", 24 * HOUR);
define("MONTH", 30 * DAY);

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
function mostrarError($error_msg) {
    $caja_error = "";
    $caja_error .= "<div class=\"caja_error\">\n\r";
    $caja_error .= "<p class=\"caja_error\">\n\r";
    $caja_error .= $error_msg;
    $caja_error .= "</p>\n\r";
    $caja_error .= "</div>\n\r";
    return $caja_error;
}

/**
 * Genera código secreto para experiencias didácticas
 *
 * @author      Carolina Aros - Kelluwen
 * @version     2010.08.17
 * @param       String $longitud Longitud de caracteres del codigo
 * @return      String $codigo   Código secreto para inscripción de experiencia
 */
function generarCodigo($longitud) {
    $codigo = '';
    $pattern = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
    $max = strlen($pattern) - 1;
    for ($i = 0; $i < $longitud; $i++)
        $codigo .= $pattern{mt_rand(0, $max)};

    return $codigo;
}

function buscarEstudianteGrupo($_estudiantes, $id_usuario, $id_grupo) {
    $cantidad = 0;
    foreach ($_estudiantes as $_usuario_grupo) {
        if ($_usuario_grupo["id_usuario"] == $id_usuario && $_usuario_grupo["id_grupo"] == $id_grupo)
            $cantidad++;
    }
    return $cantidad;
}

/**
 * Obtiene el Numero de Experiencias asociadas al rol
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.08.12
 * @param       Session   $_session session iniciada
 * @param       Integer   $rol tipo de rol
 * @return      Integer $cantidad Número de experiancias
 */
function obtenerNexperiencias($_session, $rol) {
    $cantidad = 0;
    if (count($_session) > 0) {
        foreach ($_session as $_experiencia) {
            if ($_experiencia["rol"] == $rol)
                $cantidad+=1;
        }
    }
    return $cantidad;
    require_once($ruta_raiz . "inc/lang/" . $config_archivo_idioma);
}

function cortarTexto($textos) {

    // Inicializamos las variables
    $tamano = 100; // tamaño máximo
    $contador = 0;


    // Cortamos la cadena por los espacios
    $arrayTexto = split(' ', $textos);
    $texto = '';

    // Reconstruimos la cadena
    while ($tamano >= strlen($texto) + strlen($arrayTexto[$contador])) {
        $texto .= ' ' . $arrayTexto[$contador];
        $contador++;
    }
    return $texto;
}

/**
 * Obtiene si es necesario clasificar
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.08.12
 * @param       Session   $_session session iniciada
 * @return      Boolean $retorno Si es necesario clasificar
 */
function obtenerClasificacion($_session) {
    $retorno = false;
    $rola = obtenerNexperiencias($_session, 1);
    $rolb = obtenerNexperiencias($_session, 2);
    $rolc = obtenerNexperiencias($_session, 3);
    $rold = obtenerNexperiencias($_session, 4);
    if ($rola > 0 && ($rolb > 0 OR $rolc > 0 OR $rold > 0))
        $retorno = true;
    if ($rolb > 0 && ($rola > 0 OR $rolc > 0 OR $rold > 0))
        $retorno = true;
    if ($rolc > 0 && ($rolb > 0 OR $rola > 0 OR $rold > 0))
        $retorno = true;
    if ($rold > 0 && ($rolb > 0 OR $rola > 0 OR $rolc > 0))
        $retorno = true;
    return $retorno;
}

/**
 * Obtiene si es necesario clasificar
 *
 * @author      José Carrasco - Kelluwen
 * @version     2010.08.13
 * @param       String   $cadena subsector a separar
 * @return      String $resp primera palabra del String separada por espacio
 */
function separaCadena($cadena) {
    $cadena = strtolower($cadena);
    $_token = explode(" ", $cadena);
    $resp = $_token[0];
    return $resp;
}

function contarExperienciasSubsector($_experiencias, $subsector) {
    $cantidad = 0;
    if (count($_experiencias) > 0) {
        foreach ($_experiencias as $_experiencia) {
            if ($_experiencia["subsector"] == $subsector)
                $cantidad+=1;
        }
    }
    return $cantidad;
}

function contarSubsectoresExperiencias($_experiencias, $_lang_subsectores) {
    $cantidad = 0;
    for ($i = 0; $i <= 2; $i++) {
        $temp = 0;
        foreach ($_experiencias as $_experiencia) {
            if ($_experiencia["subsector"] == $_lang_subsectores[$i])
                $temp+=1;
        }
        if ($temp > 0) {
            $cantidad+=1;
        }
    }
    return $cantidad;
}

function formatearFecha($fecha) {
    if (is_null($fecha)) {
        $fecha_formato = "-";
    } else {
        date_default_timezone_set('America/Santiago');
        $fecha_formato = date("d-m-Y", strtotime($fecha));
    }
    return $fecha_formato;
}

function formatearFechaHora($fecha) {
    if (is_null($fecha)) {
        $fecha_formato = "-";
    } else {
        date_default_timezone_set('America/Santiago');
        $fecha_formato = date("d-m-Y H:i:s", strtotime($fecha));
    }
    return $fecha_formato;
}

/* Devuelve el texto reemplazando todas las URLs por enlaces <a>. Las url deben comenzar con "http://" */

function enlazarURLs($str) {
    $str = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $str);
    return trim($str);
}

/**
 * Retorna la fecha relativa de una fecha en relación a la fecha actual
 * @author Katherine Inalef
 * @param <type> $date
 * @return <type>
 */
function relativeTime($date, $msj_error, $periodos, $tiempo, $plurales) {
    $caso_especial = false;
    if (empty($date)) {
        return $msj_error;
    }

    $periods = $periodos;
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    $now = time();
    date_default_timezone_set('America/Santiago');
    $unix_date = strtotime($date);

    // Ver que se la fecha sea valida
    if (empty($unix_date)) {
        return $msj_error;
    }

    // Ver si es una fecha futura o una fecha pasada
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = $tiempo[0];
    } else {
        $difference = $unix_date - $now;
        $tense = $tiempo[1];
        $caso_especial = true;
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        if ($periods[$j] == $periods[5]) {
            $periods[$j].= $plurales[1];
        } else {
            $periods[$j].= $plurales[0];
        }
    }
    if ($caso_especial) {
        return "{$tense}";
    } else {
        return "{$tense} $difference $periods[$j]   ";
    }
}

function filtrarString($string) {
    $special = array('/', '!', '&', '*', '\'', '"', '\\');
    $replacements = "";

    return str_replace($special, '', $string);
}

/*
 * Calcula el ultimo día segun el mes
 */

function ultimoDia($mes, $ano) {
    $ultimo_dia = 28;
    while (checkdate($mes, $ultimo_dia + 1, $ano)) {
        $ultimo_dia++;
    }
    return $ultimo_dia;
}

/*
 * Función de despliegue del calendio
 */

function calendar_html($meses, $dias) {
    $dias_calendario = $dias;
    $meses = array($meses[0], $meses[1], $meses[2], $meses[3], $meses[4], $meses[5], $meses[6], $meses[7], $meses[8], $meses[9], $meses[10], $meses[11]);
    date_default_timezone_set('America/Santiago');
    $mes = date('m', time());
    $anio = date('Y', time());
?>
    <table style="width:200px;text-align:center;border:1px solid #808080;border-bottom:0px;margin-bottom: 0;" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="4">
                <select id="calendar_mes" onchange="update_calendar()">
<?php
    $mes_numero = 1;
    while ($mes_numero <= 12) {
        if ($mes_numero == $mes) {
            echo "<option value=" . $mes_numero . " selected=\"selected\">" . $meses[$mes_numero - 1] . "</option> \n";
        } else {
            echo "<option value=" . $mes_numero . ">" . $meses[$mes_numero - 1] . "</option> \n";
        }
        $mes_numero++;
    }
?>
            </select>
        </td>
        <td colspan="3">
            <select style="width:70px;" id="calendar_anio" onchange="update_calendar()">
<?php
    // años a mostrar
    $anio_min = $anio - 60; //hace 60 años
    $anio_max = $anio; //año actual
    while ($anio_max >= $anio_min) {
        echo "<option value=" . $anio_max . ">" . $anio_max . "</option> \n";
        $anio_max--;
    }
?>
            </select>
        </td>
    </tr>
</table>
<div id="calendario_dias">
<?php calendar($mes, $anio, $dias_calendario) ?>
</div>
    <?php
}

function calendar($mes, $anio, $dias) {
    $dia = 1;
    if (strlen($mes) == 1)
        $mes = '0' . $mes;
    ?>
    <table style="width:200px;text-align:center;border:1px solid #808080;border-top:0px;" cellpadding="0" cellspacing="0">
        <tr style="background-color:#CCCCCC;">
            <td><?php echo $dias[0]; ?></td>
            <td><?php echo $dias[1]; ?></td>
            <td><?php echo $dias[2]; ?></td>
            <td><?php echo $dias[3]; ?></td>
            <td><?php echo $dias[4]; ?></td>
            <td><?php echo $dias[5]; ?></td>
            <td><?php echo $dias[6]; ?></td>
        </tr>
<?php
    date_default_timezone_set('America/Santiago');
    $numero_primer_dia = date('w', mktime(0, 0, 0, $mes, $dia, $anio));
    $ultimo_dia = ultimoDia($mes, $anio);
    $total_dias = $numero_primer_dia + $ultimo_dia;
    $diames = 1;
    //$j dias totales (dias que empieza a contarse el 1º + los dias del mes)
    $j = 1;
    while ($j < $total_dias) {
        echo "<tr> \n";
        //$i contador dias por semana
        $i = 0;
        while ($i < 7) {
            if ($j <= $numero_primer_dia) {
                echo " <td></td> \n";
            } elseif ($diames > $ultimo_dia) {
                echo " <td></td> \n";
            } else {
                if ($diames < 10)
                    $diames_con_cero = '0' . $diames;
                else
                    $diames_con_cero=$diames;
                echo " <td><a style=\"display:block;cursor:pointer;\" onclick=\"set_date('" . $diames_con_cero . "-" . $mes . "-" . $anio . "')\">" . $diames . "</a></td> \n";
                $diames++;
            }
            $i++;
            $j++;
        }
        echo "</tr> \n";
    }
?>
</table>
    <?php
}

/**
 * Redimensiona una imagen según los parametros ingresados
 *
 */
function resizeImage($image, $width, $height, $scale) {
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
    $source = imagecreatefromjpeg($image);
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
    imagejpeg($newImage, $image, 90);
    chmod($image, 0777);
    return $image;
}

/**
 * Redimensiona el tamaño de una imagen según los parametros ingresados
 * @param <type> $thumb_image_name
 * @param <type> $image
 * @param Integer $width
 * @param Integer $height
 * @param Integer $start_width
 * @param Integer $start_height
 * @param Integer $scale
 * @return <type>
 */
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
    $source = imagecreatefromjpeg($image);
    imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
    imagejpeg($newImage, $thumb_image_name, 90);
    chmod($thumb_image_name, 0777);
    return $thumb_image_name;
}

/**
 * Retorna el alto de una determinada imagen
 * @param <type> $image
 * @return integer
 */
function getHeight($image) {
    $sizes = getimagesize($image);
    $height = $sizes[1];
    return $height;
}

/**
 * Retorna el ancho de una determinada imagen
 * @param <type> $image
 * @return Integer
 */
function getWidth($image) {
    $sizes = getimagesize($image);
    $width = $sizes[0];
    return $width;
}

/**
 * @author Katherine Inalef - Kelluwen
 * @param <string> $imagen_formato
 * @param <string> $ruta_perfil
 * @param <string> $ruta_img
 * @return array $_imagenes_usuario
 */
function darFormatoImagen($imagen_formato, $ruta_perfil, $ruta_img) {
    if (!is_null($imagen_formato) AND strlen($imagen_formato) > 0) {
        //Para las imagenes con el formato nuevo
        if (strpos($imagen_formato, "http://") === false) {
            $_imagenes_usuario["imagen_usuario"] = $ruta_perfil . $imagen_formato;
            $_imagenes_usuario["imagen_grande"] = str_replace("normal", "grande", $_imagenes_usuario["imagen_usuario"]);
        } else { //Formato de Twitter
            $_imagenes_usuario["imagen_usuario"] = $imagen_formato;
            $_imagenes_usuario["imagen_grande"] = str_replace("normal", "bigger", $_imagenes_usuario["imagen_usuario"]);
        }
    } else {
        $_imagenes_usuario["imagen_usuario"] = $ruta_img . "no_avatar.jpg";
        $_imagenes_usuario["imagen_grande"] = $ruta_img . "no_avatar_bigger.jpg";
    }
    return $_imagenes_usuario;
}

function obtieneNivelAvanceExp($t_ejecutado, $t_estimado) {
    $nivel_avance = "-";
    if ($t_estimado > 0) {
        $nivel_avance = $t_ejecutado / $t_estimado;
        if ($nivel_avance > 1)
            $nivel_avance = 1;
        $nivel_avance = 100 * $nivel_avance;
    }
    return $nivel_avance;
}

function in_multiarray($elem, $array) {
    $top = sizeof($array) - 1;
    $bottom = 0;
    while ($bottom <= $top) {
        if ($array[$bottom] == $elem)
            return true;
        else
        if (is_array($array[$bottom]))
            if (in_multiarray($elem, ($array[$bottom])))
                return true;

        $bottom++;
    }
    return false;
}
function utf8_strtolower($string) {
    return utf8_encode(strtolower(utf8_decode($string)));
}
function obtenerSemestre(){
    $semestre="";
    $mes = date("n");
    if($mes <= 6){
        $semestre ="1° Semestre";
    }
    elseif ($mes > 6){
        $semestre ="2° Semestre";
    }
    return $semestre;
}
function obtenerAnio(){
    $anio=date("Y");
    return $anio;
}
function obtenerNombreGrupo($grupo){
    $numero = substr($grupo, 1);
    $nombre = $numero;
    return $nombre;
}


// Function to get the client ip address
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
?>