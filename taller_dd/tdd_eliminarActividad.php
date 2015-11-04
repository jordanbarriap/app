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
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");

    function eliminarDir($carpeta){ 
        foreach(glob($carpeta."/*") as $archivos_carpeta){ 
            if(is_dir($archivos_carpeta)) eliminarDir($archivos_carpeta); 
            else unlink($archivos_carpeta);             
        } 
        rmdir($carpeta);             
    }
   
    
    $id_diseno          = $_GET["id_diseno"];
    $nombre             = $_GET["nombre"];
    $id_actividad       = $_GET["id_actividad"];
    $actividad_orden    = $_GET["actividad_orden"];
    $id_etapa           = $_GET["id_etapa"];
    $id_usuario         = $_SESSION["klwn_id_usuario"];

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $resultado = eliminarActividadFuncion($id_diseno, $id_usuario, $id_actividad, $actividad_orden, $id_etapa, $conexion);  
    dbDesconectarMySQL($conexion);
    
    $destino = $ruta_raiz.$carpeta_subida_archivos;
    if(is_dir($destino.'/'.$id_actividad)){
        eliminarDir($destino.'/'.$id_actividad);
    }
    
    echo $resultado; 
?>  