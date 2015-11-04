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
    $id_rubrica         = $_GET["id_rubrica"];
    $id_pauta           = $_GET["id_pauta"];//rbenu_id_enunciado
    $orden              = $_GET["orden"];
    $mover              = $_GET["mover"];
    
    
$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    
    switch($mover){
        case "abajo":
            bajarPautaFuncion($id_pauta, $orden, $id_actividad, $id_rubrica, $conexion);
            $resultado= 'true';
            break;
        case "arriba":
            subirPautaFuncion($id_pauta, $orden, $id_actividad, $id_rubrica, $conexion);
            $resultado= 'true';
            break;
    }       
dbDesconectarMySQL($conexion);
    
    echo $resultado; 
?>