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
    require_once($ruta_raiz . "concurso/inc/con_db_funciones.inc.php");

    $id_actividad       = $_GET["id_actividad"];
    $actividad_orden    = $_GET["actividad_orden"];
    $id_etapa           = $_GET["id_etapa"];
    $orden_etapa        = $_GET["orden_etapa"];
    $mover              = $_GET["mover"];
    
$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    
    switch($mover){
        case "abajo":
            bajarActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion);
            $resultado= 'true';
            break;
        case "arriba":
            subirActividadFuncion($id_actividad, $actividad_orden, $id_etapa, $orden_etapa, $conexion);
            $resultado= 'true';
            break;
    }       
dbDesconectarMySQL($conexion);
    echo $resultado; 
?>  