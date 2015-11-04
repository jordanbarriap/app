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
require_once($ruta_raiz . "concurso/con_obtenerMisDisenos.php");
require_once($ruta_raiz . "concurso/conf/con_config.php");


$_temp_sectores = array();
for ($i = 0; $i < count($_sectores); $i++) {
    $_temp_sectores[$_sectores[$i]['valor']] = $_sectores[$i]['nombre'];
}

?>
<div class="contenido">
    <div class="titulo_dd">
        <?php echo $lang_titulo_disenos_participo ?>
    </div>
    <?php
    if (count($_mis_disenos)>0 || count($_mis_participaciones)>0){
    if (count($_mis_disenos)>0){?>
    <div class="titulo_subsector"><?php echo $lang_con_disenos_mi;?></div>
    <div id="lista_disenos" class="lista_disenos" style="margin-left:10px;">
        <ul>
            <?php

            for ($i = 0; $i < count($_mis_disenos); $i++) {
            $tt = $_mis_disenos[$i]['dd_fecha_creacion'];
            $_tt = explode(' ', $tt);
            $_tt = explode("-",$_tt[0]);
            $fecha_creacion = $_tt[2]."-".$_tt[1]."-".$_tt[0];
            echo '<li id="'.$_mis_disenos[$i]['dd_id_diseno_didactico'].'" class="li_mis_disenos" >';
            echo '<div onClick="cargarDiseno('.$_mis_disenos[$i]['dd_id_diseno_didactico'].')">'.$_mis_disenos[$i]['dd_nombre'].' - '.$_mis_disenos[$i]['dd_nivel'].' - '.$_temp_sectores[$_mis_disenos[$i]['dd_subsector']].', '.$lang_concurso_creado_el.' '.$fecha_creacion.'</div>';
            echo '<a id="editar_diseno" class="link_mis_disenos" name="editar_diseno" onClick="cargarDiseno('.$_mis_disenos[$i]['dd_id_diseno_didactico'].')">'.$lang_concurso_editar.'</a>';
            echo '<a id="eliminar_diseno" class="link_mis_disenos" name="eliminar_diseno" onClick="eliminarDiseno('.$_mis_disenos[$i]['dd_id_diseno_didactico'].')">'.$lang_concurso_eliminar.'</a>';
            echo '</li>';
            }
            ?>
                    </ul>
                </div>
                        <?php
                        }
                        if (count($_mis_participaciones) > 0) {
                        ?>
                    <div class="titulo_subsector"><?php echo $lang_con_disenos_colaboro; ?></div>
                    <div id="lista_participaciones" class="lista_disenos" style="margin-left:10px;">
                        <ul>
                <?php
                            for ($i = 0; $i < count($_mis_participaciones); $i++) {
                $tt = $_mis_participaciones[$i]['dd_fecha_creacion'];
                $_tt = explode(' ', $tt);
                $_tt = explode("-",$_tt[0]);
                $fecha_creacion = $_tt[2]."-".$_tt[1]."-".$_tt[0];                
                echo '<li id="'.$_mis_participaciones[$i]['dd_id_diseno_didactico'].'" class="li_mis_disenos" >';
                echo '<div onClick="cargarDiseno('.$_mis_participaciones[$i]['dd_id_diseno_didactico'].')">'.$_mis_participaciones[$i]['dd_nombre'].' - '.$_mis_participaciones[$i]['dd_nivel'].' - '.$_temp_sectores[$_mis_participaciones[$i]['dd_subsector']].', '.$lang_concurso_creado_el.' '.$fecha_creacion.'</div>';
                echo '<a id="editar_diseno_colaborador" class="link_mis_disenos" name="editar" onClick="cargarDiseno('.$_mis_participaciones[$i]['dd_id_diseno_didactico'].')"><'.$lang_concurso_editar.'></a>';
                echo '</li>';
                            }
                ?>
            </ul>
        </div>
            <?php
            
    }}
    else{
    ?>
                    <p><?php echo $lang_concurso_usuario_sin_dd; ?></p>
   <?php 
    }   ?>
</div>


<script type="text/javascript">
    function cargarDiseno(idDiseno){
        $.get('./concurso/con_desbloquearTodo.php?', function(data) {

        });        
        $('#crear_disenod').show();
        modificarDiseno(idDiseno);
    }
    function eliminarDiseno(idDiseno){
        var $dialog = $('<div><p><br></br><?php echo $lang_concurso_seguro_elim_dd; ?></p></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_concurso_eliminar_dd; ?>',
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
                "<?php echo $lang_concurso_aceptar; ?>": function() { 
                   $(this).dialog("close"); 
                },
                "<?php echo $lang_concurso_cancelar; ?>": function() {
                    eliminarDisenoOK(idDiseno);
                    $(this).dialog("close");
                }                
             }            
        });
        $dialog.dialog('open');
        return false; 
    }    
    function eliminarDisenoOK(idDiseno){
        $.get('./concurso/con_eliminarDiseno.php?id_diseno='+idDiseno, function(data) {
            actualizarMisDisenos();
        });
    }
    
    $(document).ready(function(){


        
    });    

</script>