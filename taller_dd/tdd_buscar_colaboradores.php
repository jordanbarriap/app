<?php
/**
 * @author  Elson Gueregat - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 * */
$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");

$nombre_usuario = $_REQUEST["nombre"];
$id_diseno = $_REQUEST["id_diseno"];
$texto_diseno = $_REQUEST["texto"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_resultado = tddBuscarColaboradores($nombre_usuario, $conexion);

function parseCamelCase($string) {
    $string = str_replace("\xc3\x81", "á", $string);
    $string = str_replace("\xc3\x89", "é", $string);
    $string = str_replace("\xc3\x8d", "í", $string);
    $string = str_replace("\xc3\x93", "ó", $string);
    $string = str_replace("\xc3\x9a", "ú", $string);
    $lowercaseTitle = strtolower($string);
    return ucwords($lowercaseTitle);
}

if($_resultado!=null){
foreach ($_resultado as $resultado) {
    $_imagen_colaborador = darFormatoImagen($resultado['url_imagen'], $config_ruta_img_perfil, $config_ruta_img);
    ?>
    <li id="<?php echo $resultado['id_usuario'] ?>" class="li_participantes">
    <?php
    $u_nombre = $resultado['nombre'];
    if (strlen($u_nombre) > 25)
        $u_nombre = substr($u_nombre, 0, strrpos(substr($u_nombre, 0, 25), " "));
    ?>
        <img src="<?php echo $_imagen_colaborador["imagen_usuario"]; ?>" class="icono_invitacion"></img><?php echo parseCamelCase($u_nombre); ?><img onClick="enviarInvitacion(<?php echo $resultado['id_usuario'] . ',' . $id_diseno ?>,'<?php echo $u_nombre; ?>','<?php echo $texto_diseno ?>')" src="./taller_dd/img/invitacion_20x20.png" class="icono_invitacion" title="<?php echo $lang_crear_diseno_invit_enviar; ?>"></img>
    </li>
    <?php
}
}
else{
    echo $lang_tdd_registros_no_encontrados;
}
dbDesconectarMySQL($conexion);
?>




