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
    require_once($ruta_raiz . "concurso/conf/con_config.php");

    $id_actividad       = $_GET["id_actividad"];
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $resultado = existeActividadFuncion($id_actividad, $conexion);
    dbDesconectarMySQL($conexion);

    if($resultado != null && count($resultado) > 0)
        echo 'true';
    else
        echo 'false';
?>  