<?php

$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

    $nombreArchivo = "documentos_de_ejemplo_kelluwen.zip";
    
    $sector             = $_POST["sector"];
    $nivel              = substr($_POST["nivel"],0,2);
    
    $zip = new ZipArchive();

    if ($zip->open("".$ruta_raiz."taller_dd/data/".$nombreArchivo."", ZIPARCHIVE::CREATE) !== TRUE) {
        exit($lang_tdd_archivo_no_abierto."\n");
    }
    $dir = $ruta_raiz . "taller_dd/doc_ejemplo/";
    $directorio = @opendir($dir);
    if($directorio){
        while (false !== ($archivo = @readdir($directorio))) {
            if (!is_dir("$dir/$archivo")) {
                $zip->addFile($dir . '/' . $archivo, "$archivo");
            }
        }
        @closedir($directorio);
    }
    if ($sector="SLC" && $nivel ="NB3"){
    $dir = $ruta_raiz . "taller_dd/slc_nb3/";
    $directorio = @opendir($dir);
    if($directorio){
        while (false !== ($archivo = @readdir($directorio))) {
            if (!is_dir("$dir/$archivo")) {
                $zip->addFile($dir . '/' . $archivo, "$archivo");
            }
        }
        @closedir($directorio);
    }
    }
    if ($sector="SLC" && $nivel ="NB4"){
    $dir = $ruta_raiz . "taller_dd/slc_nb4/";
    $directorio = @opendir($dir);
    if($directorio){
        while (false !== ($archivo = @readdir($directorio))) {
            if (!is_dir("$dir/$archivo")) {
                $zip->addFile($dir . '/' . $archivo, "$archivo");
            }
        }
        @closedir($directorio);
    }
    }
    if ($sector="SLC" && $nivel ="NB5"){
    $dir = $ruta_raiz . "taller_dd/slc_nb5/";
    $directorio = @opendir($dir);
    if($directorio){
        while (false !== ($archivo = @readdir($directorio))) {
            if (!is_dir("$dir/$archivo")) {
                $zip->addFile($dir . '/' . $archivo, "$archivo");
            }
        }
        @closedir($directorio);
    }
    }
    if ($sector="SLC" && $nivel ="NB6"){
    $dir = $ruta_raiz . "taller_dd/slc_nb6/";
    $directorio = @opendir($dir);
    if($directorio){
        while (false !== ($archivo = @readdir($directorio))) {
            if (!is_dir("$dir/$archivo")) {
                $zip->addFile($dir . '/' . $archivo, "$archivo");
            }
        }
        @closedir($directorio);
    }
    }

    $zip->addFromString('leer.txt', $lang_mensaje_contenido_carpeta_ejemplo);
    $zip->close();

    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=".basename($nombreArchivo)."");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    readfile("".$ruta_raiz."taller_dd/data/".$nombreArchivo."");
?>