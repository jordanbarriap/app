<?php
session_start();
/**
 * Contiene variables de configuración de la plataforma
 * LICENSE: código fuente distribuido con licencia LGPL
 * @author  Carolina Aros - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1 
 *
 **/

// Variables de conexión a MySQL

$config_host_bd="localhost";
//$config_usuario_bd ="kelluwen_dev1";
$config_usuario_bd ="root";
//$config_password_bd ="&#]sXFqW94.Pb[}";
$config_password_bd ="root";
$config_bd="kelluwen_dev1";
//$config_usuario_bd_ls ="kelluwen_dev1";
$config_usuario_bd_ls ="root";
//$config_password_bd_ls ="&#]sXFqW94.Pb[}";
$config_password_bd_ls ="root";
$config_bd_ls="kelluwen_limes1";

// Variables de ruta
//$config_ruta_servidor = "/Library/Server/Web/Data/Sites/dev1.kelluwen.cl/";
//$config_ruta_http = "http://dev1.kelluwen.cl/";
//$config_ruta_servidor = "/home/kelluwen/public_html/dev1.kelluwen.cl/";
//$config_ruta_http = "http://www.kelluwen.cl/dev1.kelluwen.cl/";
$config_ruta_servidor = "http://localhost/app/";
$config_ruta_http = "http://localhost/app/";
$config_ruta_img = $config_ruta_http."img/";
$config_ruta_img_herramientas = $config_ruta_http."img_herramientas/";
$config_ruta_dd = $config_ruta_http."dd/";
$config_ruta_actividades = $config_ruta_dd."actividades/";
$config_ruta_documentos = $config_ruta_dd."documentos/";
$config_ruta_documentos_pares = $config_ruta_servidor.'portafolio/documentos/';
$config_ruta_documentos_pares_http = $config_ruta_http.'portafolio/documentos/';
$config_ruta_cache = $config_ruta_servidor.'cache/';


if (!isset($_SESSION["idioma"])){
    if(count($_COOKIE) > 0) {
        if (isset($_COOKIE["idioma_kelluwen"])){
            $_SESSION["idioma"] = $_COOKIE["idioma_kelluwen"];
        }else{
            $idioma_navegador = substr ($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
            switch ($idioma_navegador) {
                case "es":
                    $_SESSION["idioma"] = "spanish";
                    break;
                case "en":
                    $_SESSION["idioma"] = "english";
                    break;
                default:
                   $_SESSION["idioma"] = "english";//Idioma por defecto
            }
            //$_SESSION["idioma"] = "spanish";
        }
    }
    else{
        $idioma_navegador = substr ($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
        switch ($idioma_navegador) {
            case "es":
                $_SESSION["idioma"] = "spanish";
                break;
            case "en":
                $_SESSION["idioma"] = "english";
                break;
            default:
               $_SESSION["idioma"] = "english";//Idioma por defecto
        }
    }
}

// Constantes
$config_archivo_idioma = $_SESSION["idioma"].".php";
$config_charset="charset=UTF-8";
$config_minutos_sesion = 90;
$config_char_disponibles = 200;
$config_char_disponibles_md_mu = 2000;
$config_tiempo_sesion=1800;//segundos

// Constantes imágenes de perfil, Cambiar los nombres

$upload_dir = "imagenes_perfil"; 				// The directory for the images to be saved in
$upload_path = $upload_dir."/";				// The path to where the image will be saved
$max_file = "1148576";                                  // Approx 1MB
$max_width = "300";					// Max width allowed for the large image
$thumb_width = "75";					// Width of thumbnail image
$thumb_height = "75";					// Height of thumbnail image
$normal_width = "50";					// Width of thumbnail image
$normal_height = "50";
$config_ruta_img_perfil = $config_ruta_http.$upload_path;
$max_ancho_subir = 1024;
$max_alto_subir = 1024;

require_once($ruta_raiz."inc/all.inc.php");
//Niveles y Sectores
$_niveles = array(
    0 => 'NB1 ('.$lang_config_primer_segundo_ano_basico.')',
    1 => 'NB2 ('.$lang_config_tercer_cuarto_ano_basico.')',
    2 => 'NB3 ('.$lang_config_quinto_ano_basico.')',
    3 => 'NB4 ('.$lang_config_sexto_ano_basico.')',
    4 => 'NB5 ('.$lang_config_septimo_ano_basico.')',
    5 => 'NB6 ('.$lang_config_octavo_ano_basico.')',
    6 => 'NM1 ('.$lang_config_primer_ano_medio.')',
    7 => 'NM2 ('.$lang_config_segundo_ano_medio.')',
    8 => 'NM3 ('.$lang_config_tercer_ano_medio.')',
    9 => 'NM4 ('.$lang_config_cuarto_ano_medio.')',
    10 => $lang_config_pregado,
    11 => $lang_config_postgrado
);

$_sectores = array(
    0 => array('valor'=> 'SMT','nombre'=>$lang_config_matematica),
    1 => array('valor'=> 'SLC','nombre'=>$lang_config_lenguaje_comunicacion),
    2 => array('valor'=> 'SHG','nombre'=>$lang_config_historia_cs_sociales),
    3 => array('valor'=> 'SCS','nombre'=>$lang_config_cs_naturales),
    4 => array('valor'=> 'SIE','nombre'=>$lang_config_ingles),
    5 => array('valor'=> 'SD', 'nombre'=>$lang_config_diplomado),
    6 => array('valor'=> 'ST', 'nombre'=>$lang_config_tecnologia),
    7 => array('valor'=> 'SG', 'nombre'=>$lang_config_otro)
);

//NIVELES
/* 0 = no se notifica
 * 1 = solo cuando escriben en tu muro
 * 2 = En tu muro y en conversaciones en las que participas
 * 3 = En los diseños en que estas participando
 * 4 =
 */
$nivel_notificacion = 3;
/* 0 = muestra solo mensajes escritos en el muro del diseño
 * 1 = muestra los mensajes escritos por el profesor, en su muro, asociados al diseño didáctico
 * 2 = Muestra mensajes que indican cuando un profesor comenzo o termino de ejecutar el Diseño Didáctico
 * 3 = Muestra mensajes que indican cuando un profesor comenzo o termino de ejecutar una actividada del Diseño Didáctico
 * 4 =
 */
$nivel_intrusion_md =1;
/**
 *
 */
$nivel_intrusion_mu =1;
?>