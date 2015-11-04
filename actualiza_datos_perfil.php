<?php
/**
 * Actualiza en la base de datos los datos del perfil del usuario
 * Los datos son ingresados a través de la opción modificar perfil
 * Los datos a requeridos son:
 * $_REQUEST["fmp_campo_modificar_nombre"]: nuevo nombre del usuario
 * $_REQUEST["fmp_campo_modificar_apellido"]: nuevo apellido del usuario
 * $_REQUEST["fmp_campo_modificar_contrasena_nueva"]: nueva contraseña del usuario
 * $_REQUEST["fmp_campo_modificar_contrasena_antigua"]: contraseña antigua del usuario
 * $_REQUEST["fmp_campo_modificar_fecha_nacimiento"]: nueva fecha de nacimiento del usuario
 * $_REQUEST["fmp_campo_modificar_correo"]: nuevo correo electrónico del usuario
 * $_REQUEST["fmp_campo_modificar_comuna"]: nueva comuna del usuario
 * $_REQUEST["fmp_campo_modificar_establecimiento"]: nuevo establecimiento del usuario
 * $_REQUEST["imagen"]: nueva imagen del usuario
 *
 * @author  Katherine Inalef - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 **/
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_usuario         = $_SESSION["klwn_id_usuario"];
$nombre_usuario     = $_SESSION["klwn_usuario"];
$nombre             = $_REQUEST["fmp_campo_modificar_nombre"];
$apellido           = $_REQUEST["fmp_campo_modificar_apellido"];
$contrasena         = $_REQUEST["fmp_campo_modificar_contrasena_nueva"];
$contrasena_vieja   = $_REQUEST["fmp_campo_modificar_contrasena_antigua"];
$fecha_nacimiento   = $_REQUEST["fmp_campo_modificar_fecha_nacimiento"];
$correo             = $_REQUEST["fmp_campo_modificar_correo"];
$comuna             = $_REQUEST["fmp_campo_modificar_comuna"];
$establecimiento    = $_REQUEST["fmp_campo_modificar_establecimiento"];
$imagen             = $_REQUEST["imagen"];
$mostrar_correo     = $_REQUEST["fmp_campo_mostrar_correo"];
$mostrar_fecha      = $_REQUEST["fmp_campo_mostrar_fecha"];

$id_sesion          = $_SESSION["id_sesion"];//Código agregado por Jordan Barría el 28-10-14

list($dia, $mes, $anio) = split('-', $fecha_nacimiento);
$fecha_nacimiento = $anio."-".$mes."-".$dia;

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
/*
 * hacer las validaciones en el lado del servidor
 */
$validacion='true';
$ingreso = -1;
$usuario = dbObtenerInfoUsuario($nombre_usuario, $conexion);
if($usuario["email"]!= $correo){
    if (dbValidaCorreoUsuario($correo, $conexion)>=1){
        $validacion='false';
    }
}
if($contrasena!=null ){
    if (dbValidaContrasenaUsuario($nombre_usuario,$contrasena_vieja, $conexion)!= 1){
        $validacion='false';
    }
}
if($validacion == 'true'){
    $ingreso = dbActualizaDatosUsuario($id_usuario, $nombre, $apellido, $correo, $fecha_nacimiento, $comuna,$establecimiento, $contrasena, $imagen,$mostrar_correo,$mostrar_fecha,$conexion);
    dbLogActualizarPerfil($id_sesion,1,$conexion);//Código agregado por Jordan Barría el 31-10-14
}
dbDesconectarMySQL($conexion);
echo $ingreso;

?>
