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

$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$id_diseno = $_GET["id_diseno"];

$_iniciador = obtenerAutorFuncion($id_diseno, $conexion);
$_participantes = obtenerParticipantesFuncion($id_diseno, $conexion);

$_imagen_iniciador= darFormatoImagen($_iniciador[0]["u_url_imagen"], $config_ruta_img_perfil, $config_ruta_img);

function parseCamelCase($string)
{
    $string = str_replace("\xc3\x81", "á", $string);
    $string = str_replace("\xc3\x89", "é", $string);
    $string = str_replace("\xc3\x8d", "í", $string);
    $string = str_replace("\xc3\x93", "ó", $string);
    $string = str_replace("\xc3\x9a", "ú", $string);
    $lowercaseTitle = strtolower($string);
    return ucwords($lowercaseTitle);
}


?>

<div class="titulo_usuarios"><?php echo $lang_crear_diseno_partic; ?></div>
<div class="subtitulo_usuarios"><?php echo $lang_crear_diseno_partic_inic; ?></div>

<?php
if (strlen($_iniciador[0]['u_nombre']) > 25)
    $_iniciador[0]['u_nombre'] = substr($_iniciador[0]['u_nombre'], 0, strrpos(substr($_iniciador[0]['u_nombre'], 0, 25), " "));?>
<div id="iniciador"><a onclick="cargarPerfilTaller('<?php echo $_iniciador[0]['u_usuario']; ?>');"href="#" alt="" title="" class="link_inv_perfil">
    <img src="<?php echo $_imagen_iniciador["imagen_usuario"];?>" class="icono_invitacion"></img></a><?php echo $_iniciador[0]['u_nombre']?></div>
<br>
<?php if(count($_participantes)>0){?>
<div class="subtitulo_usuarios" ><?php echo $lang_crear_diseno_partic_colab; ?></div>
<?php }?>
<ul>
    <?php
for ($i = 0; $i < count($_participantes); $i++) {
    if (strlen($_participantes[$i]['u_nombre']) > 25)
        $_participantes[$i]['u_nombre'] = substr($_participantes[$i]['u_nombre'], 0, strrpos(substr($_participantes[$i]['u_nombre'], 0, 25), " "));
        $_imagen_participantes= darFormatoImagen($_participantes[$i]['u_url_imagen'], $config_ruta_img_perfil, $config_ruta_img); ?>
    <li class="li_participantes">
    <?php if ($_participantes[$i]['u_id_usuario'] == $_SESSION["klwn_id_usuario"]) {?>
        <div><a onclick="cargarPerfilTaller('<?php echo $_participantes[$i]['u_usuario']; ?>');"href="#" alt="" title="" class="link_inv_perfil">
            <img style="border-left: 5px solid <?php echo $_participantes[$i]['ta_color']; ?>;" src="<?php echo $_imagen_participantes["imagen_usuario"]?>" class="icono_invitacion"></img></a><?php echo parseCamelCase($_participantes[$i]['u_nombre']); ?><img onClick="dejarColaboracion(<?php echo $_participantes[$i]['u_id_usuario'] . ',' . $id_diseno?>)" src="./taller_dd/img/eliminar.png" class="icono_invitacion" title="<?php echo $lang_crear_diseno_partic_no_colab; ?>"></img></div>
   <?php } else {?>
        <div><a onclick="cargarPerfilTaller('<?php echo $_participantes[$i]['u_usuario']; ?>');"href="#" alt="" title="" class="link_inv_perfil">
            <img style="border-left: 5px solid <?php echo $_participantes[$i]['ta_color']; ?>;" src="<?php echo $_imagen_participantes["imagen_usuario"]?>" class="icono_invitacion"></img></a><?php echo parseCamelCase($_participantes[$i]['u_nombre']); ?></div>
   <?php }?>
    </li>
<?php }
?>
</ul>
</div>
<?php

dbDesconectarMySQL($conexion);

?>