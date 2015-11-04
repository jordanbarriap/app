<?php
/**
 * Ejecuta la función dbTerminarActividad para marcar una actividad en ejecución
 * como terminada. El script recibe:
 *  codexpact: el id de la ejecución de la actividad (tabla exp_actividad)
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
require_once($ruta_raiz."revpares/inc/rp_db_functions.inc.php");
require_once($ruta_raiz."revpares/rpVinculacionPares.php");

//
// codexpact=453&
// codeexp=51&
// bandera_vincula=0&
// nombre_actividad=%C2%BFQu%C3%A9%20se%20dice,%20qui%C3%A9n%20lo%20dice,%20c%C3%B3mo%20lo%20dicen?

$id_exp_actividad = $_REQUEST["codexpact"];
$id_experiencia = $_REQUEST["codeexp"];
$id_actividad = $_REQUEST["id_actividad"];
$nombre_actividad = $_REQUEST["nombre_actividad"];
$id_usuario = $_SESSION["klwn_id_usuario"];
$exito = 1;

if (is_null($id_exp_actividad) or strlen($id_exp_actividad) == 0){
    $error = 1;
}
else{
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $exito = dbTerminarActividad($id_exp_actividad, $conexion);

    //inserción de vinculos a la tabla rp_vincula productos
   // $trabajos_mi_clase = dbRPTrabajosClasePorActividad($id_experiencia,$id_actividad, $conexion);
    //if($trabajos_mi_clase){
     //   foreach($trabajos_mi_clase as $trabajo){
     //       dbRPIngresaProductoEspera2($trabajo['id_producto'],$conexion);
     //   }
    //}

    $id_diseno = dbExpObtenerIdDiseno($id_experiencia, $conexion);
    $mensaje = $nombre_actividad;
    $tipo = 5;
    $n = dbMuralDisenoInsertarMensaje($id_diseno, $id_experiencia, $id_usuario, $mensaje, $tipo,$id_actividad, $conexion);
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
