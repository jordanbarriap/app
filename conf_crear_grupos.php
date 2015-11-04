<?php

/**
 * Crea los grupos solicitados en la Base de Datos.
 * Si ya existen grupos asociados a la experiencia, los elimina y crea los nuevos grupos.
 * Utiliza las funciones dbExpGruposExperiencia,dbExpEliminarGrupos y dbExpGenerarGrupos
 *
 * Los parámetros necesarios pasados son:
 * $_REQUEST["codexpi"] : identificador de la experiencia didáctica
 * $_REQUEST["ngru"]: número de grupos
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
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."revpares/inc/rp_db_functions.inc.php");

$id_experiencia = $_REQUEST["codexpi"];
$grupos = $_REQUEST["ngru"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_grupos_experiencia = dbExpGruposExperiencia($id_experiencia, $conexion);
$cantidad_grupos = count($_grupos_experiencia);

// Si hay grupos se eliminan los existentes y se generan los nuevos
if ($cantidad_grupos > 0) {
    if (dbExpEliminarGrupos($id_experiencia, $conexion)) {
        //eliminar los datos de los grupos que existian
       $eliminar_datos_grupo =  dbRPEliminaDatosGruposExp($id_experiencia,$_grupos_experiencia, $conexion);
       //echo $eliminar_datos_grupo;
        if (dbExpGenerarGrupos($id_experiencia, $grupos, $conexion)) {
            echo "1";
        } else {
            echo "0";
        }
    }
} else {
    if (dbExpGenerarGrupos($id_experiencia, $grupos, $conexion)) {
        echo "1";
    } else {
        echo "0";
    }
}
dbDesconectarMySQL($conexion);
?>



