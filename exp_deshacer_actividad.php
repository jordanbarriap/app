<?php
/**
 * Cambia el estado de una actividad finalizada identificada por el código 
 * de experiencia_actividad ($_REQUEST["codexpact"]) a no iniciada. Utiliza la
 * funcion dbDeshacerActividad($id_exp_actividad, $conexion)
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *  
 * @author  Daniel Guerra - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt  
 * @version 0.1
 *   
 **/
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "revpares/inc/rp_db_functions.inc.php");

$id_exp_actividad = $_REQUEST["codexpact"];
$exito = 1;

if (is_null($id_exp_actividad) or strlen($id_exp_actividad) == 0){
    $error = 1;
}
else{
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $exito = dbDeshacerActividad($id_exp_actividad, $conexion);//la misma funciçón revisa si la actividad es de revision
    $exito_1 = dbRPEliminaRevisores($id_exp_actividad, $conexion);
    dbDesconectarMySQL($conexion);
    
    if ($exito == -1){
        $error = 2;
        $error_msg = $lang_error_insertar_exp_act;
        
    }
    elseif($exito == 0){
        
    }else{
        
    }
}
echo $exito;
?>
