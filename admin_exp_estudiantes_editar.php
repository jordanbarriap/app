<?php
/**
 * 
 **/ 
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$id_estudiante = $_REQUEST["id_usuario"];
$id_experiencia = $_REQUEST["id_exp"];
$accion = $_REQUEST["accion"];
$nombre = $_REQUEST["nombre"];
$usuario = $_REQUEST["usuario"];
$nombre = quitar_espacios_dobles(str_replace ( ".", " ",$nombre));
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$exito =0;
if($accion == 1){
    $exito =dbAdminEditarEstudiante($id_estudiante, $nombre, $usuario, $conexion);
    echo $exito;
}
if($accion == 2){
     $_respuesta = dbAdminEstudianteEstadoExperiencia($id_estudiante, $id_experiencia, $conexion);
     $resp_experiencia = dbAdminEliminarEstudianteExperiencia($id_estudiante, $id_experiencia, $conexion);
     if($resp_experiencia == 1){
         $data = "<p>- ".$lang_admin_eee_est_no_pertenece."</p> ";
     }
     else{
         $data = "<p>- ".$lang_admin_eee_no_elimina_est."</p></br>";
     }
     if(!is_null($_respuesta["grupo"])){
         $resp_grupo = dbAdminEliminarEstudianteGrupo($id_estudiante, $id_experiencia, $conexion);
         if($resp_grupo == 1){
             $data .= "<p>- ".$lang_admin_eee_no_pertenece_grupo."</p>";
         }
         else{
             $data .= "<p>- ".$lang_admin_eee_no_elimina_est_grupo."</p></br>";
         }
     }
     if($_respuesta["num_mensajes"]>0){
         $resp_mensajes = dbAdminEliminarEstudianteMensajesBitacora($id_estudiante, $id_experiencia, $conexion);
         if($resp_mensajes > 0){
             $data .= "<p>- ".$lang_admin_eee_msj_borrados."</p>";
         }
         else{
             $data .= "<p>- ".$lang_admin_eee_problema."</p>";
         }
     }
     if($_respuesta["num_megusta"]>0){
         $resp_megusta = dbAdminEliminarEstudianteMegusta($id_estudiante, $id_experiencia, $conexion);
         if($resp_megusta >0){
             $data .= "<p>- ".$lang_admin_eee_mg_borrados."</p>";
         }
         else{
             $data .= "<p>- ".$lang_admin_eee_no_elimina_mg."</p>";
         }
     }
     if($_respuesta["num_respuestas"]>0){
         $resp_respuestas = dbAdminEliminarEstudianteRespuestas($id_estudiante, $id_experiencia, $conexion);
         if($resp_respuestas > 0){
             $data .= "<p>- ".$lang_admin_eee_resp_eliminadas."</p>";
         }
         else{
             $data .= "<p>- ".$lang_admin_eee_no_elimina_resp."</p>";
         }
         
     }
     echo $data;
}
if($accion == 3){
    $exito = dbAdminResetearContrasena($id_estudiante, $conexion);
    echo $exito;
}

dbDesconectarMySQL($conexion);

?>

