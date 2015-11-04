<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");

    $id_actividad       = $_GET["id_actividad"];
    $id_archivo         = $_GET["id_archivo"];
    $orden              = $_GET["orden"];
    $mover              = $_GET["mover"];
    
    
$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    
    switch($mover){
        case "abajo":
            bajarMArchivoFuncion($id_archivo, $orden, $id_actividad, $conexion);
            $resultado= 'true';
            break;
        case "arriba":
            subirMArchivoFuncion($id_archivo, $orden, $id_actividad, $conexion);
            $resultado= 'true';
            break;
    }       
dbDesconectarMySQL($conexion);
    
    echo $resultado; 
?>