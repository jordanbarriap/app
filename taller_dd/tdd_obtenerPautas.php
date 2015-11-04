<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    $id_actividad           = $_GET["id_actividad"];

    $_pautas = obtenerPautasFuncion($id_actividad, $conexion);
    
    $maxLengh = 100;

    $totalPautas = count($_pautas);
?>
<ul>
<?php    
    for($i=0 ; $i< $totalPautas; $i++){
        $enunciado = $_pautas[$i]['rpe_enunciado'];
        if(strlen($enunciado) > $maxLengh) $enunciado= substr($enunciado,0,strrpos(substr($enunciado,0,$maxLengh-3)," "))."...";
?>
    <li class ="li_mis_pautas" id="<?php echo $_pautas[$i]['rpe_id']; ?>" >
        <div><?php echo $enunciado; ?></div>
        <a class="link_mis_pautas" name="eliminar_pauta" onClick="eliminarPauta(<?php echo $_pautas[$i]['rpe_id']; ?>,<?php echo $id_actividad; ?>,<?php echo $_pautas[$i]['rpe_orden']; ?>)"><?php echo $lang_mis_disenos_eliminar; ?></a>
<?php
        if($_pautas[$i]['rpe_orden'] != $totalPautas){
?>
        <input name="mover_pauta_abajo" class="boton_pauta_abajo" type="button" value="" onClick="bajarPauta(<?php echo $_pautas[$i]['rpe_id']; ?>,<?php echo $_pautas[$i]['rpe_orden'];?>,<?php echo $_pautas[$i]['rpe_id_actividad'];?>);"/>
<?php
        }
        if($_pautas[$i]['rpe_orden'] != 1){
?>
        <input name="mover_pauta_arriba" class="boton_pauta_arriba" type="button" value="" onClick="subirPauta(<?php echo $_pautas[$i]['rpe_id']; ?>,<?php echo $_pautas[$i]['rpe_orden']; ?>,<?php echo $_pautas[$i]['rpe_id_actividad']?>);"/>
<?php

        }
    }
?>
</ul>
<?php

dbDesconectarMySQL($conexion);

?>