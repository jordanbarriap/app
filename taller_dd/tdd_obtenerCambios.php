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
$id_diseno      = $_GET["id_diseno"];
$todos          = $_GET["todos"];
$_cambios = obtenerCambiosFuncion($id_diseno, $todos, $conexion);

    if($todos == 0){
?>
<div class="titulo_usuarios"><?php echo $lang_crear_diseno_cambios_titulo; ?></div>
<?php }if(count($_cambios)>0){?>
<div class="subtitulo_usuarios"><?php echo $lang_crear_diseno_cambios_subt1; ?></div>
<?php }
else{
?>
<div class="subtitulo_usuarios"><?php echo $lang_crear_diseno_cambios_subt2; ?></div>
<?php
}
?>
<div style="margin-bottom: 55px;">
    <ul  style="margin-bottom: 10px !important;">
    <?php
    for ($i = 0; $i < count($_cambios); $i++) {
        $fec = explode(" ",$_cambios[$i]['trc_fecha']);
        $fecha = explode("-", $fec[0]);
        $fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0]." ".$fec[1];
               
    ?>
        <li class="li_participantes2">
            <div>
            <span style="text-transform: capitalize;"><b><?php echo $fecha;?></b>: <?php echo $_cambios[$i]['trc_texto'];?> <?php echo $lang_crear_diseno_cambios_usuario; ?> <?php echo $_cambios[$i]['u_nombre'];?></span>
            </div>
        </li>
    <?php
    }
    ?>        
    </ul>
    <?php 
    if($todos == 0 && count($_cambios)>0){
    ?>    
    <div id="vermas_cambios"class="vermas" onClick="verMasCambios(<?php echo $id_diseno; ?>)"><?php echo $lang_crear_diseno_cambios_todo; ?> »</div>
    <?php 
    }
    ?>  
</div>
<?php

dbDesconectarMySQL($conexion);

?>