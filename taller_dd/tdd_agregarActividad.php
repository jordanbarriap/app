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
    //require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");

    $id_etapa           = $_GET["id_etapa"];
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);        
    $_resultado = agregarActividadFuncion($id_etapa, $conexion);  
    if($_resultado){
        agregarRegistroCambio($_SESSION["klwn_id_usuario"], $_GET['id_diseno'], 0, 0, 0, "Se agregó una actividad", $conexion);
    }
    dbDesconectarMySQL($conexion);
    //print_r($_resultado); 
?>  