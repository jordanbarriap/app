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

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    
    
        $id_usuario         = $_SESSION["klwn_id_usuario"];
//        $nombre_usuario     = $_SESSION["klwn_usuario"];

    $_mis_disenos = obtenerMisDisenosFuncion($id_usuario, $conexion);
    $_mis_participaciones = obtenerDisenosParticipoFuncion($id_usuario, $conexion);

    dbDesconectarMySQL($conexion);
?>