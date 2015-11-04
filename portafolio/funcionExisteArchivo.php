<?php

$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/db_functions.inc.php");
require_once($ruta_raiz."portafolio/inc/por_funciones_db.inc.php");


$id_experiencia = $_REQUEST['id_experiencia'];
$id_actividad = $_REQUEST['id_actividad'];
$id_grupo = $_REQUEST['id_grupo'];
$nombre_original = $_REQUEST['nombre_archivo'];

$nombre_archivo = str_replace(' ', '_', $nombre_original);
$ruta_archivo = $config_ruta_documentos_pares."exp_".$id_experiencia."/act_".$id_actividad."/".$id_grupo."_".$nombre_archivo;
//echo "ruta archivo: ".$ruta_archivo.". <br>";
if(file_exists($ruta_archivo)){
	//echo ("existe archivo");
    $respuesta = 1;
}
else{
	//echo ("no existe archivo");
    $respuesta = 0;
}

echo $respuesta;
?> 
