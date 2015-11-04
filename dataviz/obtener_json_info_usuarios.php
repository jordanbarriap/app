<?php

$ruta_raiz = "../";

require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."dataviz/inc/db_functions_dataviz.inc.php");
require_once($ruta_raiz."inc/db_functions.inc.php");


$id_experiencia     = $_REQUEST["codexp"]; 
$id_usuario         = $_REQUEST["id_usuario"];
$ruta_carpeta_imagenes=$config_ruta_img;

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);

echo obtenerParticipacionUsuarios($id_experiencia,$id_usuario,$conexion,$config_ruta_img_perfil);

dbDesconectarMySQL($conexion);

?>
