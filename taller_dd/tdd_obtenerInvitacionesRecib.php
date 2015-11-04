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

$id_usuario     = $_SESSION["klwn_id_usuario"];
$_invitaciones = obtenerInvitacionesRecibFuncion($id_usuario, $conexion);

$sector = '';

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
<div class="titulo_usuarios"><?php echo $lang_crear_diseno_invit_rec_titulo; ?></div>
<?php if(count($_invitaciones)>0){?>
<div class="subtitulo_usuarios"><?php echo $lang_crear_diseno_invit_rec_subt1; ?></div>
<?php }
else{
?>
<div class="subtitulo_usuarios"><?php echo $lang_crear_diseno_invit_rec_subt2; ?></div>
<?php
}
?>
<div>
    <ul>
    <?php
    for ($i = 0; $i < count($_invitaciones); $i++) {
        $sector = '';
        for ($j = 0; $j < count($_sectores); $j++) {
            if (strcmp($_sectores[$j]['valor'], $_invitaciones[$i]['dd_subsector']) == 0) {
                $sector = $_sectores[$j]['nombre'];
            } 
        }
        
        $_imagen_usuario = darFormatoImagen($_invitaciones[$i]['u_url_imagen'], $config_ruta_img_perfil, $config_ruta_img);
    ?>
        <li class="li_participantes2">
            <?php if (strlen($_invitaciones[$i]['u_nombre']) > 25)
            $_invitaciones[$i]['u_nombre'] = substr($_invitaciones[$i]['u_nombre'], 0, strrpos(substr($_invitaciones[$i]['u_nombre'], 0, 25), " ")); ?>
            <div><img src="<?php echo $_imagen_usuario["imagen_usuario"]; ?> " class="icono_invitacion"></img><?php echo parseCamelCase($_invitaciones[$i]['u_nombre']); ?> <?php echo $lang_crear_diseno_invit_te_invita; ?> 
            <span style="text-transform: capitalize;"><?php echo $_invitaciones[$i]['dd_nombre'];?></span>
            , <?php echo $lang_crear_diseno_invit_sector; ?> <?php echo $sector ?>
            , <?php echo $lang_crear_diseno_invit_nivel; ?> <?php echo $_invitaciones[$i]['dd_nivel'] ?>
            . <?php echo $lang_crear_diseno_invit_aceptar; ?> <a class="enlace" onclick="aceptaInvitacion2(<?php echo $_invitaciones[$i]['ta_id_autor']; ?>,<?php echo $_invitaciones[$i]['ta_id_diseno_didactico']; ?>);"><?php echo $lang_crear_diseno_invit_aqui; ?></a>
            </div>
        </li>
    <?php
    }
    ?>
    </ul>
</div>
<?php

dbDesconectarMySQL($conexion);

?>