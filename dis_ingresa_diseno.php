<?php

/**
 * Inscribe un diseño didáctico como experiencia.
 * Utiliza las funciones dbExpInscribirExperiencia y
 * dbDisObtenerUltimaEtiqueta
 *
 * Los parámetros necesarios pasados son:
 * $_REQUEST["fr_campo_curso"] : curso para la experiencia didáctica
 * $_REQUEST["fr_campo_localidad"]: localidad para la experiencia didáctica
 * $_REQUEST["fr_campo_colegio"]: colegio para la experiencia didáctica
 * $_REQUEST["user"]: usuario que inscribe el diseño didáctico
 * $_REQUEST["dd"] : identificador diseño didáctico

 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  José Carrasco - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])

    )header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

require_once($ruta_raiz . "inc/class.phpmailer.php");
date_default_timezone_set('America/Toronto');

$inscribir_experiencia = $_REQUEST["iexp"];
if ($inscribir_experiencia == 1) {
    $ed_curso = $_REQUEST["fr_campo_curso"];
    $ed_localidad = $_REQUEST["fr_campo_localidad"];
    $ed_colegio = $_REQUEST["fr_campo_colegio"];
    $id_profesor = $_REQUEST["user"];
    $id_diseno_didactico = $_REQUEST["dd"];
    $semestre = obtenerSemestre();
    $anio = obtenerAnio();

    $conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

    $ingreso = dbExpInscribirExperiencia($id_profesor, $id_diseno_didactico, $ed_localidad, $ed_curso, $ed_colegio, $semestre, $anio, $conexion);
    if ($ingreso == 1) {
        $ultima_etiqueta = dbDisObtenerUltimaEtiqueta($conexion, $id_profesor, $id_diseno_didactico);
        foreach ($ultima_etiqueta as $ultima)
            echo $ultima["etiqueta"];
    } else {
        echo "0";
    }
    dbDesconectarMySQL($conexion);
} else if ($inscribir_experiencia == 0) {
    $si_ed_nombre = $_REQUEST["fsi_campo_nombre"];
    $si_ed_email = $_REQUEST["fsi_campo_email"];
    $si_ed_telefono = $_REQUEST["fsi_campo_telefono"];
    $si_ed_establecimiento = $_REQUEST["fsi_campo_establecimiento"];
    $si_ed_comuna = $_REQUEST["fsi_campo_comuna"];


    $mail = new PHPMailer();
    $body = $lang_dis_ingresa_dis_equipo_kelluwen.": <br>";
    $body .= $lang_dis_ingresa_dis_ejecucion_dd.": <br>";
    $body .= $lang_dis_ingresa_dis_nombre.": " . $si_ed_nombre . "<br>";
    $body .= $lang_dis_ingresa_dis_email.": " . $si_ed_email . "<br>";
    $body .= $lang_dis_ingresa_dis_telefono.": " . $si_ed_telefono . "<br>";
    $body .= $lang_dis_ingresa_dis_establecimiento.": " . $si_ed_establecimiento . "<br>";
    $body .= $lang_dis_ingresa_dis_comuna.": " . $si_ed_comuna . "<br><br>";
    $body .= $lang_dis_ingresa_dis_no_olvide.".<br><br>".$lang_dis_ingresa_dis_gracias;

    $mail->From = "no-contestar@kelluwen.cl";
    $mail->FromName = "Kelluwen";
    $mail->Subject = utf8_decode($lang_dis_ingresa_dis_sol_ejecucion_dd);

    $mail->AddReplyTo("no-contestar@kelluwen.cl", $lang_recupera_registro_body_estimado_despedida);

    $mail->AltBody = utf8_decode($body);
    $mail->MsgHTML(utf8_decode($body));
    $mail->AddAddress("carolinaaros@gmail.com");
    $mail->IsHTML(true);
    if (!$mail->Send()) {
        echo $lang_dis_ingresa_dis_problema;
    } else {
        echo $lang_dis_ingresa_dis_datos_enviados.".<br>".$lang_dis_ingresa_dis_comunicaremos;
    }
}
?>
