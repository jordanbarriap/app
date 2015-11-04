<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    //Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
    //if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");


    $enunciado      = $_POST['fcp_enunciado'];	
    $id_actividad   = $_POST['fcp_id_actividad'];
    $id_diseno      = $_POST['fcp_id_diseno'];
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_resultado = agregarPautaFuncion($enunciado, $id_actividad, $conexion);
    
    if($_resultado){
        
        $_diseno =obtenerDisenoActividadFuncion($id_actividad, $conexion);
        if(isset($_diseno[0]['dd_id_diseno_didactico'])){
            agregarRegistroCambio($_SESSION["klwn_id_usuario"], $_diseno[0]['dd_id_diseno_didactico'], $id_actividad, 1, 1, 'Se Agregó la pauta de evaluación "'.$enunciado
                                .'" a la actividad "'.$_diseno[0]['ac_nombre'].'" del diseño "'.$_diseno[0]['dd_nombre'].'"'
                                , $consulta, $conexion);
        }        
        echo "1";
    }else{
        echo "-1";
    }
    dbDesconectarMySQL($conexion);
    
?>
