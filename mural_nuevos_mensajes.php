<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");

$id_ultimo_mensaje = $_REQUEST["id_ultimo_mensaje"];
$id_diseno = $_REQUEST["id_diseno"];
$id_usuario = $_REQUEST["id_usuario"];
$origen = $_REQUEST["origen"]; // 0: muro diseño, 1: muro usuario

if($nivel_intrusion_md == 0){
    $tipo = 0;
}
if($nivel_intrusion_md == 1){
    $tipo = 1;
}
if($nivel_intrusion_md == 2){
    $tipo = 2;
}
if($nivel_intrusion_md == 3){
    $tipo = 4;
}
$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
if($origen == 0 && !is_null($id_diseno)&& !is_null($id_ultimo_mensaje)){
    $mensajes_nuevos    = dbMuralDisenoNuevosMensajes($id_diseno, $id_ultimo_mensaje,$tipo,$conexion);
    $funcion_a_llamar   = "<a href=\"#\" onclick=\"javascript: leerUltimosMensajesMuralDiseno(); return false;\"> ";
}
if($origen == 1 && !is_null($id_usuario)&& !is_null($id_ultimo_mensaje)){
    $mensajes_nuevos    = dbMuralUsuarioNuevosMensajes($id_usuario, $id_ultimo_mensaje,$tipo, $conexion);
    $funcion_a_llamar   = "<a href=\"#\" onclick=\"javascript: leerUltimosPostsMural(); return false;\"> ";
}
dbDesconectarMySQL($conexion);
if($mensajes_nuevos > 0){
    if($mensajes_nuevos == 1){//singular
        echo $funcion_a_llamar.$lang_hay_mensajes_hay.$mensajes_nuevos.$lang_hay_mensajes_s." </a>";
    }
    else {//plural
        echo $funcion_a_llamar.$lang_hay_mensajes_hay.$mensajes_nuevos.$lang_hay_mensajes_p."</a>";
    }
}else{
    echo "0";
}
?>
