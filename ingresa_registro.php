<?php
/**
 * Recibe los campos del formulario registro.php e ingresa el usuario a la base de datos
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Katherine Inalef - Kelluwen
 *
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");

$nombre = $_REQUEST["fr_campo_nombre"];
$apellido = $_REQUEST["fr_campo_apellido"];
$nombre_usuario = $_REQUEST["fr_campo_nombre_usuario"];
$contrasena = $_REQUEST["fr_campo_contrasena"];
$fecha_nacimiento = $_REQUEST["fr_campo_fecha_nacimiento"];
$correo = $_REQUEST["fr_campo_correo"];
$inscribe_diseno = '0';
$localidad = $_REQUEST["fr_campo_localidad"];
$establecimiento = $_REQUEST["fr_campo_establecimiento"];
list($dia, $mes, $anio) = split('-', $fecha_nacimiento);
$fecha_nacimiento = $anio."-".$mes."-".$dia;

//Validaciones
$validacion = 'true';
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);

if (dbValidaNombreUsuario($nombre_usuario, $conexion)>=1){
    $validacion='false';
    //Nombre usuario ya existe en la base de datos
}
if (dbValidaCorreoUsuario($correo, $conexion)>=1){
    $validacion='false';
    //Correo electrónico ya existe en la base de datos
}

if(isset($nombre)&& isset($apellido)&& isset($nombre_usuario)&& isset($contrasena)&& isset($correo)&& $validacion== 'true'){
    $ingreso = dbInsertarNuevoUsuario($nombre, $apellido, $nombre_usuario, $contrasena, $fecha_nacimiento, $correo, $inscribe_diseno, $localidad,$establecimiento, $conexion);
    echo $ingreso;
    dbDesconectarMySQL($conexion);
}
else{
    echo "-1";
}



?>
