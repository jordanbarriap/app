<?php
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
$config_usuario_bd ="kelluwen_userapp";
$config_password_bd ="userapp";
$config_bd="kelluwen_app5";
$config_usuario_bd_ls ="kelluwen_limes";
$config_password_bd_ls ="limesu";
$config_bd_ls="kelluwen_limesurvey";

// Variables de ruta

$config_ruta_http = "http://www.kelluwen.cl/app/";
$config_ruta_img = $config_ruta_http."img/";
$config_ruta_img_herramientas = $config_ruta_http."img_herramientas/";
$config_ruta_dd = $config_ruta_http."dd/";
$config_ruta_actividades = $config_ruta_dd."actividades/";
$config_ruta_documentos = $config_ruta_dd."documentos/";
$config_ruta_documentos_pares = "/var/www/html/app/revpares/documentos/";
$config_ruta_documentos_pares_http = $config_ruta_http.'revpares/documentos/';
$config_ruta_cache = "/var/www/html/app/cache/";

// Constantes

$config_charset="charset=UTF-8";
$config_archivo_idioma="spanish.php";
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


//Niveles y Sectores
$_niveles = array(
    0 => 'NB1 (Primer y Segundo Año Básico)',
    1 => 'NB2 (Tercer y Cuarto Año Básico)',
    2 => 'NB3 (Quinto Año Básico)',
    3 => 'NB4 (Sexto Año Básico)',
    4 => 'NB5 (Séptimo Año Básico)',
    5 => 'NB6 (Octavo Año Básico)',
    6 => 'NM1 (Primer Año Medio)',
    7 => 'NM2 (Segundo Año Medio)',
    8 => 'NM3 (Tercer Año Medio)',
    9 => 'NM4 (Cuarto Año Medio)',
    10 => 'Pregrado',
    11 => ¡'stgrado'
);

$_sectores = array(
    0 => array('valor'=> 'SMT','nombre'=>'Matemática'),
    1 => array('valor'=> 'SLC','nombre'=>'Lenguaje y Comunicación'),
    2 => array('valor'=> 'SHG','nombre'=>'Historia, Geografía y Ciencias Sociales'),
    3 => array('valor'=> 'SCS','nombre'=>'Ciencias Naturales'),
    4 => array('valor'=> 'SIE','nombre'=>'Idioma Extranjero Inglés'),
    5 => array('valor'=> 'SD', 'nombre'=>'Diplomado'),
    6 => array('valor'=> 'ST', 'nombre'=>'Tecnología'),
    7 => array('valor'=> 'SG', 'nombre'=>'Otro')
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
