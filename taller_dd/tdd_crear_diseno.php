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
    $nombre             = $_POST['fcdn_nombre'];
    $sector             = $_POST["fcdn_sector"];
    $nivel              = $_POST["fcdn_nivel"];
    $escala              = $_POST["fcdn_escala"];
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    
    $idDiseno = crearDisenoFuncion($id_autor, $nombre, $sector, $nivel, '', '', '', '', '', '', '', 2, $escala, $conexion);
    dbDesconectarMySQL($conexion);
    
    echo $idDiseno;
    
?>
