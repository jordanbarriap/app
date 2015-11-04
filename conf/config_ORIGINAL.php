<?php
/**
 * Contiene variables de configuración de la plataforma
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Carolina Aros - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 **/

// Variables de conexión a MySQL
$config_host_bd="localhost";
$config_usuario_bd ="root";
$config_password_bd ="admin";
$config_bd="db_kelluwen_app3";

// Variables de ruta
$config_ruta_http = "http://localhost/kelluwen3/";
$config_ruta_img = $config_ruta_http."img/";
$config_ruta_img_herramientas = $config_ruta_http."img_herramientas/";
$config_ruta_dd = $config_ruta_http."dd/";
$config_ruta_actividades = $config_ruta_dd."actividades/";
$config_ruta_documentos = $config_ruta_dd."documentos/";

// Constantes
$config_charset="charset=UTF-8";
$config_archivo_idioma="spanish.php";
$config_minutos_sesion = 90;
$config_char_disponibles = 200;
$config_char_disponibles_md_mu = 2000;
$config_cant_subsectores =4;
$config_cant_subsectores_fin=4;

//niveles
/* 0 = no se notifica
 * 1 = solo cuando escriben en tu muro
 * 2 = En tu muro y en conversaciones en las que participas
 * 3 = En los diseños en que estas participando
 * 4 =
 */
$nivel_notificacion = 2;
/* 0 = muestra solo mensajes escritos en el muro del diseño
 * 1 = muestra los mensajes escritos por el profesor, en su muro, asociados al diseño didáctico
 * 2 = Muestra mensajes que indican cuando un profesor comenzo o termino de ejecutar el Diseño Didáctico
 * 3 = Muestra mensajes que indican cuando un profesor comenzo o termino de ejecutar una actividada del Diseño Didáctico
 * 4 =
 */
$nivel_intrusion_md =3;
/**
 *
 */
$nivel_intrusion_mu =1;


// Constantes imágenes de perfil, Cambiar los nombres
$upload_dir = "imagenes_perfil"; 				// The directory for the images to be saved in
$upload_path = $upload_dir."/";				// The path to where the image will be saved
$max_file = "1148576";                                  // Approx 1MB
$max_width = "500";					// Max width allowed for the large image
$thumb_width = "75";					// Width of thumbnail image
$thumb_height = "75";					// Height of thumbnail image
$normal_width = "50";					// Width of thumbnail image
$normal_height = "50";
$config_ruta_img_perfil = $config_ruta_http.$upload_path;
$config_tiempo_sesion = 1800 ;
?>