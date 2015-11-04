<?php

$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/db_functions.inc.php");
require_once($ruta_raiz . "portafolio/inc/por_funciones_db.inc.php");

if(existeSesion ()){
    $id_usuario = $_REQUEST['id_usuario'];
    $id_producto = $_REQUEST['id_producto'];
    $id_grupo = $_REQUEST['id_grupo'];

    //Obtencion de datos del formulario de producto
    $comentario_general = $_REQUEST['txt_nuevo_post'];

    //Ejecucion del ingreso del producto en la base de datos
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $id_nuevo_producto = dbPIngresaComentario($id_usuario,$id_producto,$comentario_general,$conexion);
    dbDesconectarMySQL($conexion);
    if ($id_nuevo_producto == '1'){
        echo "Comentario enviado con exito! Recarga la p&aacute;gina para verlo.";
    }
    else{
        echo '0';
    }
}
else{
    echo '0';
}

?>
