<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    //Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "concurso/inc/con_db_funciones.inc.php");
    require_once($ruta_raiz . "concurso/conf/con_config.php");


    $id_actividad           = $_POST['fca_id_actividad'];	
    $aprendizaje_esperado   = $_POST['fca_aprendizaje_esperado'];	
    $descripcion_general    = $_POST['fca_descripcion_general'];
    $nombre                 = $_POST['fca_nombre'];
    $tipo                   = $_POST['fca_tipo_lugar'];


    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    guardarActividadFuncion( $id_actividad, $nombre, $descripcion_general, $aprendizaje_esperado, $tipo, $conexion);	
	
    dbDesconectarMySQL($conexion);

    
?>