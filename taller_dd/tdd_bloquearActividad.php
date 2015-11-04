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

    $tipo           = $_GET["tipo"];
    $id_bloqueo     = $_GET["id_bloqueo"];
    $id_usuario     = $_SESSION["klwn_id_usuario"];
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    limpiarBloqueosAntiguosFuncion($conexion);   
    $_resultado = bloquearActividadFuncion($tipo, $id_bloqueo, $id_usuario, $conexion);  
     
    if($_resultado != null && count($_resultado)>0){
        $_dato_usuario = obtenerNombreUsuarioFuncion($_resultado[0]['tb_id_usuario'], $conexion);
        if($_dato_usuario != null && count($_dato_usuario)>0){
            echo "true$$==$$".$_dato_usuario[0]['u_nombre'];
        }
        else echo "false";
    }
    else echo "false";
    dbDesconectarMySQL($conexion); 
?>