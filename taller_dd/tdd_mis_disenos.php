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
require_once($ruta_raiz . "taller_dd/tdd_obtenerMisDisenos.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");


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
    if (count($_mis_disenos)>0 || count($_mis_participaciones)>0 || count($_mis_disenos_publicados)>0){
        if (count($_mis_disenos)>0){ ?>
        <div class="titulo_subsector"><?php echo $lang_tdd_disenos_mi;?></div>
        <div id="lista_disenos" class="lista_disenos" style="margin-left:10px;">
            <ul>
                <?php
                for ($i = 0; $i < count($_mis_disenos); $i++) {
                    $tt = $_mis_disenos[$i]['dd_fecha_creacion'];
                    $_tt = explode(' ', $tt);
                    $_tt = explode("-",$_tt[0]);
                    $fecha_creacion = $_tt[2]."-".$_tt[1]."-".$_tt[0];
                ?>
                    <li id="<?php echo $_mis_disenos[$i]['dd_id_diseno_didactico']; ?>" class="li_mis_disenos" >
                    <div onClick="cargarDiseno(<?php echo $_mis_disenos[$i]['dd_id_diseno_didactico']; ?>)"><span class="nombre_diseno_class"><?php echo $_mis_disenos[$i]['dd_nombre']; ?></span>
                        <a href="#">
                        <?php
                            if(!is_null($_mis_disenos[$i]["hw_nombre"])){
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas.$_mis_disenos[$i]["hw_imagen"];?>" alt="<?php echo $_mis_disenos[$i]["hw_nombre"];?>" title="<?php echo $_mis_disenos[$i]["hw_nombre"];?>"></img>
                        <?php
                        }
                        else{
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas."no_herramienta.png";?>" alt="<?php echo $lang_plataforma_kelluwen;?>" title="<?php echo $lang_plataforma_kelluwen;?>"></img>
                        <?php 
                        }
                        ?>
                        </a>                     
                    <br><?php echo $_mis_disenos[$i]['dd_nivel']; ?> - <?php echo $_temp_sectores[$_mis_disenos[$i]['dd_subsector']]; ?>, <?php echo $lang_mis_disenos_creado; ?> <?php echo $fecha_creacion; ?></div>
                    <a id="editar_diseno" class="link_mis_disenos" name="editar_diseno" onClick="cargarDiseno(<?php echo $_mis_disenos[$i]['dd_id_diseno_didactico']; ?>)"><?php echo $lang_mis_disenos_editar; ?></a>
                    <a id="eliminar_diseno" class="link_mis_disenos" name="eliminar_diseno" onClick="eliminarDiseno('<?php echo $_mis_disenos[$i]['dd_nombre'];?>',<?php echo $_mis_disenos[$i]['dd_id_diseno_didactico'];?> )"><?php echo $lang_mis_disenos_eliminar; ?></a>
                    </li>               
                <?php
                }
                ?>
                    </ul>
                </div>
                <?php
                }
                if (count($_mis_participaciones) > 0) {
                ?>
                    <div class="titulo_subsector"><?php echo $lang_tdd_disenos_colaboro; ?></div>
                    <div id="lista_participaciones" class="lista_disenos" style="margin-left:10px;">
                        <ul>
                <?php
                    for ($i = 0; $i < count($_mis_participaciones); $i++) {
                        $tt = $_mis_participaciones[$i]['dd_fecha_creacion'];
                        $_tt = explode(' ', $tt);
                        $_tt = explode("-",$_tt[0]);
                        $fecha_creacion = $_tt[2]."-".$_tt[1]."-".$_tt[0];                
                ?>
                        <li id="<?php echo $_mis_participaciones[$i]['dd_id_diseno_didactico']; ?>" class="li_mis_disenos" >
                        <div onClick="cargarDiseno(<?php echo $_mis_participaciones[$i]['dd_id_diseno_didactico']; ?>)"><span class="nombre_diseno_class"><?php echo $_mis_participaciones[$i]['dd_nombre']; ?></span>
                        <a href="#">
                        <?php
                            if(!is_null($_mis_participaciones[$i]["hw_nombre"])){
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas.$_mis_participaciones[$i]["hw_imagen"];?>" alt="<?php echo $_mis_participaciones[$i]["hw_nombre"];?>" title="<?php echo $_mis_participaciones[$i]["hw_nombre"];?>"></img>
                        <?php
                        }
                        else{
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas."no_herramienta.png";?>" alt="<?php echo $lang_plataforma_kelluwen;?>" title="<?php echo $lang_plataforma_kelluwen;?>"></img>
                        <?php 
                        }
                        ?>
                        </a>
                        <br><?php echo $_mis_participaciones[$i]['dd_nivel']; ?> - <?php echo $_temp_sectores[$_mis_participaciones[$i]['dd_subsector']]; ?>, <?php echo $lang_mis_disenos_creado; ?> <?php echo $fecha_creacion; ?></div>
                        <a id="editar_diseno_colaborador" class="link_mis_disenos" name="editar" onClick="cargarDiseno(<?php echo $_mis_participaciones[$i]['dd_id_diseno_didactico']; ?>)"><?php echo $lang_mis_disenos_editar; ?></a>
                        </li>                            
                    <?php
                    }                    
                ?>
                    </ul>
                </div>
                <?php
                }
                if (count($_mis_disenos_publicados) > 0) {
                ?>
                    <div class="titulo_subsector"><?php echo $lang_tdd_disenos_publicados; ?></div>
                    <div id="lista_participaciones" class="lista_disenos" style="margin-left:10px;">
                        <ul>
                <?php
                    for ($i = 0; $i < count($_mis_disenos_publicados); $i++) {
                        $tt = $_mis_disenos_publicados[$i]['dd_fecha_creacion'];
                        $_tt = explode(' ', $tt);
                        $_tt = explode("-",$_tt[0]);
                        $fecha_creacion = $_tt[2]."-".$_tt[1]."-".$_tt[0];                
                ?>
                        <li id="<?php echo $_mis_disenos_publicados[$i]['dd_id_diseno_didactico']; ?>" class="li_mis_disenos" >
                        <div><span class="nombre_diseno_class"><?php echo $_mis_disenos_publicados[$i]['dd_nombre']; ?></span>
                        <a href="#">
                        <?php
                            if(!is_null($_mis_disenos_publicados[$i]["hw_nombre"])){
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas.$_mis_disenos_publicados[$i]["hw_imagen"];?>" alt="<?php echo $_mis_disenos_publicados[$i]["hw_nombre"];?>" title="<?php echo $_mis_disenos_publicados[$i]["hw_nombre"];?>"></img>
                        <?php
                        }
                        else{
                        ?>
                            <img src="<?php echo $config_ruta_img_herramientas."no_herramienta.png";?>" alt="<?php echo $lang_plataforma_kelluwen;?>" title="<?php echo $lang_plataforma_kelluwen;?>"></img>
                        <?php 
                        }
                        ?>
                        </a>
                        <br><?php echo $_mis_disenos_publicados[$i]['dd_nivel']; ?> - <?php echo $_temp_sectores[$_mis_disenos_publicados[$i]['dd_subsector']]; ?>, <?php echo $lang_mis_disenos_creado; ?> <?php echo $fecha_creacion; ?></div>
                        <a id="editar_diseno_colaborador" class="link_mis_disenos" name="editar" onClick="enviarMsgAdminPre(<?php echo $_mis_disenos_publicados[$i]['dd_id_diseno_didactico']; ?>)"><?php echo $lang_mis_disenos_msg_admin; ?></a>
                        </li>                            
                    <?php
                    }
                ?>                        
                    </ul>
                </div>
                    <?php

                }
    
    }
    else{
    ?>
        <p><?php echo $lang_mis_disenos_vacio; ?></p>
   <?php 
    }   ?>
</div>


<script type="text/javascript">
    function enviarMsgAdminPre(idDiseno){
            text_ = '<div>';
            text_ += '<table><tr><td><label>Mensaje:</label></td>';
            text_ += '<td><textarea id="textareaMsgAdmin"></textarea></td></tr></table>';
            text_ += '</div>';
            var $dialog = $(text_)
            .dialog({
                autoOpen: false,
                title: 'Enviar mensaje a administrador',
                dialogClass: 'uii-dialog',
                width: 500,
                height: 170,
                zIndex: 3999,
                modal: true,
                close: function(ev, ui) {
                    $(this).remove();
                },
                buttons: {
                    "<?php echo $lang_mis_disenos_cancelar; ?>": function() {
                        $(this).dialog("close");
                    },
                    "<?php echo $lang_mis_disenos_enviar; ?>": function() {
                        texto = document.getElementById("textareaMsgAdmin").value;
                        if(texto.length > 0){
                            enviarMsgAdmin(idDiseno, texto);
                            $(this).dialog("close");
                        }
                    }
                }            
            });
            $dialog.dialog('open');
            return false;    
    }    
    function cargarDiseno(idDiseno){
        $.get('./taller_dd/tdd_desbloquearTodo.php?', function(data) {

        });        
        $('#crear_disenod').show();
        modificarDiseno(idDiseno);
    }
    function eliminarDiseno(nombre, idDiseno){
        var $dialog = $('<div><p><br></br><?php echo $lang_mis_disenos_elim_preg; ?></p></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_mis_disenos_elim_titulo; ?>',
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
                "<?php echo $lang_mis_disenos_cancelar; ?>": function() { 
                   $(this).dialog("close"); 
                },
                "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                    eliminarDisenoOK(nombre, idDiseno);
                    $(this).dialog("close");
                }                
             }            
        });
        $dialog.dialog('open');
        return false; 
    }    
    function eliminarDisenoOK(nombre, idDiseno){
        $.get('./taller_dd/tdd_eliminarDiseno.php?id_diseno='+idDiseno+'&nombre='+nombre, function(data) {
            actualizarMisDisenos();
        });
    }
    
    $(document).ready(function(){
        actualizarInvitacionesRecib();        
    });    

</script>