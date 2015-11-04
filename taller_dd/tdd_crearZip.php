<?php

$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

if (isset($_GET['idDiseno'])) {
    $idDiseno = $_GET['idDiseno'];
    $conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_diseno = obtenerDisenoFuncion($idDiseno, $conexion);
    $_etapas = obtenerEtapasFuncion($idDiseno, $conexion);
    $_actividades_etapa[0] = obtenerActividadesPorEtapaFuncion($_etapas[0]['e_id_etapa'], $conexion);
    $_actividades_etapa[1] = obtenerActividadesPorEtapaFuncion($_etapas[1]['e_id_etapa'], $conexion);
    $_actividades_etapa[2] = obtenerActividadesPorEtapaFuncion($_etapas[2]['e_id_etapa'], $conexion);

    $nombreArchivo = str_replace(" ", "_", $_diseno[0]['dd_nombre']);
    $nombreArchivo = str_replace(array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"), array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U"), $nombreArchivo);
    $nombreArchivo = preg_replace('/[^A-Za-z0-9_]/', '', $nombreArchivo);
    $nombreArchivo = $nombreArchivo . "_" . date('dmY') . ".zip";
    
    $zip = new ZipArchive();

    if ($zip->open("".$ruta_raiz."taller_dd/data/".$nombreArchivo."", ZIPARCHIVE::CREATE) !== TRUE) {
        exit($lang_tdd_archivo_no_abierto."\n");
    }

    for ($i = 0; $i < count($_actividades_etapa); $i++) {
        for ($j = 0; $j < count($_actividades_etapa[$i]); $j++) {
            $dir = $ruta_raiz . "dd/actividades/" . $_actividades_etapa[$i][$j]['ac_id_actividad'] . "/";
            $directorio = @opendir($dir);
            while (false !== ($archivo = @readdir($directorio))) {
                //error_log($archivo."  ".is_dir("$dir/$archivo"));
                if (!is_dir("$dir/$archivo")) {
                    $zip->addFile($dir . '/' . $archivo, "$archivo");
                }
            }
            @closedir($directorio);
        }
    }
    $zip->addFromString('leer.txt', $lang_mensaje_contenido_carpeta);
    $zip->close();

    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=".basename($nombreArchivo)."");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    readfile("".$ruta_raiz."taller_dd/data/".$nombreArchivo."");
}
?>
