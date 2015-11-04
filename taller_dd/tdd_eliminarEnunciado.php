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
    

    $id_pauta       = $_GET["id_pauta"];
    $id_actividad   = $_GET["id_actividad"];
    $id_rubrica     = $_GET["id_rubrica"];
    $orden          = $_GET["orden"];

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_pauta = obtenerPautaFuncion($id_pauta, $conexion);
    $_diseno =obtenerDisenoActividadFuncion($id_actividad, $conexion);
    //$resultado = eliminarPautaFuncion($id_pauta, $id_actividad, $orden, $conexion);
    $resultado = eliminarEnunciadoFuncion($id_pauta, $id_actividad, $id_rubrica, $orden, $conexion);
    if($resultado == 1){
        if(isset($_pauta[0]['rpe_id']) && isset($_diseno[0]['dd_id_diseno_didactico'])){
            agregarRegistroCambio($_SESSION["klwn_id_usuario"], $_diseno[0]['dd_id_diseno_didactico'], $id_actividad, 0, 2, 
                                'Se Eliminó el enunciado "'.$_pauta[0]['rpe_enunciado']
                                .'" de la actividad "'.$_diseno[0]['ac_nombre'].'" del diseño "'.$_diseno[0]['dd_nombre'].'"'
                                , $consulta, $conexion);
        }        
    }
    dbDesconectarMySQL($conexion);
    
    echo $resultado;

?>