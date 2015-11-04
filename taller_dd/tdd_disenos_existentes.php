<?php
/**
 *
 * @author  Elson Gueregat - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");


$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    

$_disenos_publicados2 = obtenerDisenosPublicadosFuncion($conexion);

$_temp_sectores = array();
for ($i = 0; $i < count($_sectores); $i++) {
    $_temp_sectores[$_sectores[$i]['valor']] = $_sectores[$i]['nombre'];
}

?>
<div class="contenido">
    <div class="titulo_dd">
        <?php echo $lang_titulo_disenos_clonar ?>
    </div>
    <?php
    if (count($_disenos_publicados2)>0 ){
        if (count($_disenos_publicados2)>0){?>

        <div id="lista_disenos" class="lista_disenos" style="margin-left:10px;">
            <?php
                for($j = 0; $j < count($_sectores); $j++) {
                    $_temp_sectores[$_sectores[$j]['valor']] = $_sectores[$j]['nombre'];
                    $_disenos_publicados = obtenerDisenosPublicadosPorSectorFuncion($_sectores[$j]['valor'], $conexion);
                    if (count($_disenos_publicados)>0){
                ?><p class="titulo_subsector"><?php echo $lang_mis_disenos_sector." ".$_sectores[$j]['nombre']; ?></p><ul>
                    <?php
                    }
                    for ($i = 0; $i < count($_disenos_publicados); $i++) {
                        $tt = $_disenos_publicados[$i]['dd_fecha_creacion'];
                        $_tt = explode(' ', $tt);
                        $_tt = explode("-",$_tt[0]);
                        $fecha_creacion = $_tt[2]."-".$_tt[1]."-".$_tt[0];
//                        echo '<li id="'.$_disenos_publicados[$i]['dd_id_diseno_didactico'].'" class="li_mis_disenos" >';
//                        echo '<div title="Ver diseño" onClick="cargarDiseno('.$_disenos_publicados[$i]['dd_id_diseno_didactico'].')"><span class="nombre_diseno_class">'.$_disenos_publicados[$i]['dd_nombre'].'</span><br> '.$_disenos_publicados[$i]['dd_nivel'].' - '.$_temp_sectores[$_disenos_publicados[$i]['dd_subsector']].', creado el '.$fecha_creacion.'</div>';
//                        echo '<a id="crear_nueva_version" class="link_mis_disenos" title="Usar este diseño para crear una nueva versión" name="crear_nueva_version" onClick="crearNuevaVersion('.$_disenos_publicados[$i]['dd_id_diseno_didactico'].')">Usar este diseño</a>';
//                        echo '</li>';
                        ?>
                        <li id="<?php echo $_disenos_publicados[$i]['dd_id_diseno_didactico']; ?>" class="li_mis_disenos" >
<!--                        <div title="<?php echo $lang_mis_disenos_ver; ?>" onClick="cargarDiseno(<?php echo $_disenos_publicados[$i]['dd_id_diseno_didactico']; ?>)"><span class="nombre_diseno_class"><?php echo $_disenos_publicados[$i]['dd_nombre']; ?></span> 
-->
                        <div><span class="nombre_diseno_class"><?php echo $_disenos_publicados[$i]['dd_nombre']; ?></span> 
                        <a href="#">
                        <?php
                            if(!is_null($_disenos_publicados[$i]["hw_nombre"])){
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas.$_disenos_publicados[$i]["hw_imagen"];?>" alt="<?php echo $_disenos_publicados[$i]["hw_nombre"];?>" title="<?php echo $_disenos_publicados[$i]["hw_nombre"];?>"></img>
                        <?php
                        }
                        else{
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas."no_herramienta.png";?>" alt="<?php echo $lang_plataforma_kelluwen;?>" title="<?php echo $lang_plataforma_kelluwen;?>"></img>
                        <?php 
                        }
                        ?>
                        </a>  
                        <br> <?php echo $_disenos_publicados[$i]['dd_nivel']; ?> - <?php echo $_temp_sectores[$_disenos_publicados[$i]['dd_subsector']]; ?>, <?php echo $lang_mis_disenos_creado; ?> <?php echo $fecha_creacion; ?></div>
                        <a id="crear_nueva_version" class="link_mis_disenos" title="<?php echo $lang_mis_disenos_usar2; ?>" name="crear_nueva_version" onClick="crearNuevaVersion(<?php echo $_disenos_publicados[$i]['dd_id_diseno_didactico']; ?>)"><?php echo $lang_mis_disenos_usar; ?></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul><?php
                }
                ?>
        </div>
            <?php
            }
    }
    else{
    ?>
        <p><?php echo $lang_mis_disenos_no_publi."."?></p>
   <?php 
    }   
    dbDesconectarMySQL($conexion);
    ?>
</div>


<script type="text/javascript">
    function cargarDiseno(idDiseno){
        $.get('./taller_dd/tdd_desbloquearTodo.php?', function(data) {

        });        
        $('#crear_disenod').show();
        modificarDiseno(idDiseno);
    }
    function crearNuevaVersion(idDiseno){
        var $dialog = $('<div><p><br></br><?php echo $lang_mis_disenos_preg; ?></p></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_mis_disenos_crear; ?>',
            dialogClass: 'uii-dialog',
            resizable: false,
            modal: true,
            width:'auto',
            height: 150,
            zIndex: 3999,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            },
             buttons: {
                "<?php echo $lang_mis_disenos_aceptar; ?>": function() { 
                    crearNuevaVersionOK(idDiseno);
                   $(this).dialog("close"); 
                },
                "<?php echo $lang_mis_disenos_cancelar; ?>": function() {
                    $(this).dialog("close");
                }                
             }            
        });
        $dialog.dialog('open');
        return false; 
    }    
    function crearNuevaVersionOK(idDiseno){
        $.get('./taller_dd/tdd_crearNuevaVersion.php?id_diseno='+idDiseno, function(data) {
            actualizarMisDisenos();
        });
    }
    
    $(document).ready(function(){


        
    });    

</script>