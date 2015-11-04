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

$id_diseno  = $_GET["id_diseno"];
$sector     = $_GET["sector"];
$end        = $_GET["end"];

$_invitaciones = obtenerInvitacionesFuncion($id_diseno, $conexion);
$datos_diseno = obtenerDisenoFuncion($id_diseno, $conexion);
$_invitar = buscarColaboradoresFuncion($id_diseno, $sector, $end, $conexion);
$texto_diseno = $datos_diseno[0]['dd_nombre']." ".$lang_tdd_obtinv_nivel." ".$datos_diseno[0]['dd_nivel']." ".$lang_tdd_obtinv_sector." ".obtenerSector($datos_diseno[0]['dd_subsector']);

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
function obtenerSector($fcd_sector){
    global $_sectores;
    for($i=0; $i<count($_sectores); $i++){
        if(strcmp ($_sectores[$i]['valor'] , $fcd_sector) == 0){
            return $_sectores[$i]['nombre'];
        }                          
    }
    return '';
}

?>
<div class="titulo_usuarios"><?php echo $lang_crear_diseno_invit_titulo; ?></div>
<?php if(count($_invitaciones)>0){?>
<div class="subtitulo_usuarios"><?php echo $lang_crear_diseno_invit_subtit1; ?></div>
<?php } ?>
<div>
    <ul>
    <?php
    for ($i = 0; $i < count($_invitaciones); $i++) {
        $_imagen_usuario = darFormatoImagen($_invitaciones[$i]['u_url_imagen'], $config_ruta_img_perfil, $config_ruta_img);
    ?>
        <li class="li_participantes">
            <?php if (strlen($_invitaciones[$i]['u_nombre']) > 25)
            $_invitaciones[$i]['u_nombre'] = substr($_invitaciones[$i]['u_nombre'], 0, strrpos(substr($_invitaciones[$i]['u_nombre'], 0, 25), " ")); ?>
            <div><img src="<?php echo $_imagen_usuario["imagen_usuario"]; ?> " class="icono_invitacion"></img><?php echo parseCamelCase($_invitaciones[$i]['u_nombre']); ?></div>
        </li>
    <?php
    }
    ?>
    </ul>
</div>
<div class="subtitulo_usuarios"><?php echo $lang_crear_diseno_invit_subtit2; ?></div>
<div>
    <form id="form_buscar_colaboradores">
        <input id="fbc_nombre" name="fbc_nombre" type="text" value=""></input>
        <input type="button" value="<?php echo $lang_tdd_obtinv_buscar;?>" onclick="javascript:buscarColaboradores(<?php echo $id_diseno; ?>,'<?php echo $texto_diseno; ?>');"></input>
    </form>
    <ul id="tdd_colaboradores">
        <?php
        for ($i = 0; $i < count($_invitar)-1; $i++) {
            $_imagen_colaborador = darFormatoImagen($_invitar[$i]['u_url_imagen'], $config_ruta_img_perfil, $config_ruta_img); ?>
            <li id="<?php echo $_invitar[$i]['u_id_usuario'] ?>" class="li_participantes">
                <?php
                $u_nombre=$_invitar[$i]['u_nombre'];
                if (strlen($_invitar[$i]['u_nombre']) > 25)
                    $_invitar[$i]['u_nombre'] = substr($_invitar[$i]['u_nombre'], 0, strrpos(substr($_invitar[$i]['u_nombre'], 0, 25), " "));?>
                <img src="<?php echo $_imagen_colaborador["imagen_usuario"];?>" class="icono_invitacion"></img><?php echo parseCamelCase($_invitar[$i]['u_nombre']); ?><img onClick="enviarInvitacion(<?php echo $_invitar[$i]['u_id_usuario'] . ',' . $id_diseno?>,'<?php echo $u_nombre; ?>','<?php echo $texto_diseno ?>')" src="./taller_dd/img/invitacion_20x20.png" class="icono_invitacion" title="<?php echo $lang_crear_diseno_invit_enviar; ?>"></img>
            </li>
        <?php
        }
        ?>
    </ul>
<?php    
    if($_invitar[count($_invitar)-1] > $end){
?>
        <div id="vermas_invitar"class="vermas" onClick="verMasInvitaciones(<?php echo $id_diseno; ?>)"><?php echo $lang_crear_diseno_coment_vermas; ?> »</div>
<?php
    }
?>
</div>
<?php

dbDesconectarMySQL($conexion);

?>