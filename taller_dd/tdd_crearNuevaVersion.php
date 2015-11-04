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
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");

   

    $id_autor           = $_SESSION["klwn_id_usuario"];
    
    $id_diseno_original = $_GET['id_diseno'];


    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    
    
        $idNuevaVersion = crearDisenoNuevaVersionFuncion($id_autor, $id_diseno_original, $conexion);
    
    dbDesconectarMySQL($conexion);
    
    echo $idNuevaVersion;
    
?>