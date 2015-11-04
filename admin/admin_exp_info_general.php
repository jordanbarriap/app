<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$codeexp = $_REQUEST["codeexp"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$datos_experiencia = dbAdminExpObtenerInfo($codeexp, $conexion);
$_imagenes = darFormatoImagen($datos_experiencia["url_avatar_profesor"], $config_ruta_img_perfil, $config_ruta_img);
$datos_select_estado= array($lang_admin_no_comenzada,$lang_admin_en_curso,$lang_admin_finalizada);
if(is_null($datos_experiencia["fecha_inicio"])){
    $selected = 1;
}
else{
    if(is_null($datos_experiencia["fecha_termino"])){
        $selected = 2;
    }
    else{
        $selected = 3;
    }
}
$datos_select_publicado = array($lang_admin_si, $lang_admin_no);
?>
    <form id ="form_admin_exp_localidad" class="form_admin_experiencia" method="post" action="">
        <label><?php echo $lang_admin_localidad;?></label>
        <input disabled="disabled" tabindex="1" type="text" maxlenght="120" size="120" class ="admin_exp_localidad" id="admin_campo_localidad"  name="admin_campo_localidad" value="<?php echo $datos_experiencia["localidad"];?>" />
        <button type="button" class ="admin_exp_editar admin_experiencia" id="admin_exp_boton_localidad" name="admin_campo_localidad" ></button>
        <button type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_localidad_enviar" name="admin_exp_boton_localidad" value=""></button>
    </form>
    </br>
    <form id ="form_admin_exp_curso" class="form_admin_experiencia" method="post" action="">
        <label><?php echo $lang_admin_curso;?></label>
        <input disabled="disabled" tabindex="2" type="text" maxlenght="120" size="120" class ="admin_exp_curso" id="admin_campo_curso"  name="admin_campo_curso" value="<?php echo $datos_experiencia["curso"];?>" />
        <input type="button" class ="admin_exp_editar admin_experiencia" id ="admin_exp_boton_curso" name="admin_campo_curso"></input>
        <input type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_curso_enviar" name="admin_exp_boton_curso" value="">
    </form>
    </br>
    <form id ="form_admin_exp_establecimiento" class="form_admin_experiencia"method="post" action="">
        <label><?php echo $lang_admin_establecimiento_educacional;?></label>
        <input disabled="disabled" tabindex="3" type="text" maxlenght="120" size="120" class="admin_exp_establecimiento" id="admin_campo_colegio"  name="admin_campo_colegio" value="<?php echo $datos_experiencia["colegio"];?>" />
        <input type="button" class ="admin_exp_editar admin_experiencia" id ="admin_exp_boton_establecimiento" name="admin_campo_colegio"></input>
        <input type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_establecimiento_enviar" name="admin_exp_boton_establecimiento" value="">

    </form>
    </br>
    <form id ="form_admin_exp_publicado" class="form_admin_experiencia" method="post" action="">
       <label><?php echo $lang_admin_publicado; ?></label>
       <select disabled="disabled" name=admin_campo_publicado id="admin_campo_publicado" size=1 class="admin_exp_publicado" onChange="">
           <?php
           if($datos_experiencia["publicado"]==1){
           ?>
           <option value="1" SELECTED><?php echo $datos_select_publicado[0];?>
           <option value="0"><?php echo $datos_select_publicado[1];?>
           <?php
            }
            else{
            ?>
                <option value="1" ><?php echo $datos_select_publicado[0];?>
                <option value="0" SELECTED ><?php echo $datos_select_publicado[1];?>
            <?php
            }
           ?>
       </select>
        <input type="button" class ="admin_exp_editar admin_experiencia" id ="admin_exp_boton_publicado" name="admin_campo_publicado"></input>
        <input type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_publicado_enviar" name="admin_exp_boton_publicado" value="">
    </form>
        </br>
    <form id ="form_admin_exp_estado"  class="form_admin_experiencia" method="post" action="">
        <label><?php echo $lang_admin_estado; ?></label>
        <select  disabled="disabled" name=admin_campo_estado id="admin_campo_estado" size=1 class="admin_exp_estado" onChange="">
            <?php 
            if($selected==1){
            ?>
                <option value="1" SELECTED><?php echo $datos_select_estado[0];?> 
            <?php 
            }
            else{
            ?>
                <option value="1" ><?php echo $datos_select_estado[0];?> 
            <?php 
            }
            if($selected == 2){
            ?>
                <option value="2" SELECTED><?php echo $datos_select_estado[1];?> 
            <?php 
            } 
            else{
            ?>
                <option value="2"><?php echo $datos_select_estado[1];?>
            <?php 
            }
            if($selected == 3){
            ?>
                <option value="3" SELECTED><?php echo $datos_select_estado[2];?> 
            <?php 
            } 
            else{
            ?>
                <option value="3"><?php echo $datos_select_estado[2];?>
            <?php 
            }
            ?>
        </select>
        <input type="button" class ="admin_exp_editar admin_experiencia" id ="admin_exp_boton_estado" name="admin_campo_estado"></input>
        <input type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_estado_enviar" name="admin_exp_boton_estado" value="">
    </form>
    </br>

    <button class="admin_boton_eliminar_experiencia" onclick="javascript:adminEliminarExperiencia(<?php echo $codeexp;?>);"><?php echo $lang_admin_eliminar_exp; ?></button>
    <div class="clear"></div>


<?php
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    
    function adminEliminarExperiencia(id_experiencia){
        var contenido = '<p><?php echo $lang_admin_seguro_eliminar_exp; ?></p>';
        var $dialog = $('<div class=\"dialogo_eliminar_experiencia\" id=\"dialogo_eliminar_experiencia\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_eliminar_exp; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesExperienciaEliminar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_si; ?>": function() {
                    url = 'admin/admin_exp_editar_info_general.php?&codeexp='+id_experiencia+'&eliminar=1';
                    $.post(url,  function(data) {
                        if(data == 1){
                            $('#dialogo_eliminar_experiencia').html('<p><?php echo $lang_admin_exp_did_eliminada; ?></p>');
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(3)").show();
                            adminAdministrarExperiencias();
                        }
                        else{
                            $('#dialogo_eliminar_experiencia').html('<p><?php echo $lang_admin_problema_cambio; ?></p>');
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesExperienciaEliminar div button:nth-child(3)").show();
                        }
                    });
                },
                //CANCELAR
                "<?php echo $lang_admin_no; ?>": function() {
                    $(this).dialog('destroy').remove();
                },
                //CERRAR
                "<?php echo $lang_admin_cerrar; ?>": function() {
                    $(this).dialog('destroy').remove();
                }
            },
            close: function() {
            }
            });
        $("div.dialogBotonesExperienciaEliminar div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }

    $(document).ready(function(){
        var campo_modificar;
        $('.admin_exp_guardar').hide();
        $('#admin_general_titulo_a').hide();
        $('#admin_general_c').hide();
        $('.admin_exp_guardar').hide();
        
        $(".admin_exp_editar").click(function() {
            var element = $(this);
            var I = element.attr("id");
            var nombre = element.attr("name");
            $("#"+I).hide();
            $("#"+I+"_enviar").show();
            $("#"+nombre).attr("disabled",false);
            campo_modificar = $("#"+nombre).val();
        });
        $(".admin_exp_guardar").click(function() {
            var element = $(this);
            var I = element.attr("id");
            var nombre = element.attr("name");
            var input_form = $('#'+nombre).attr("name");
            $('#'+nombre).show();
            $('#'+I).hide();          
        });
        
        $("#form_admin_exp_localidad").validate({
            rules:{
                admin_campo_localidad:{
                    required:true,
                    minlength:3
                }
            },
            messages:{
                admin_campo_localidad: {
                    required:"<?php echo $lang_registro_nombre_required;?>",
                    minlength:"<?php echo $lang_registro_nombre_minlenght;?>"
                }
            },
            submitHandler: function() {
                var datos_a_enviar = $("#admin_campo_localidad").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_admin_ingresar_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_localidad\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioLocalidad',
                        buttons: {
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_campo_localidad').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_admin_seguro_guardar_cambios; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_localidad\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioLocalidad',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_si; ?>": function() {
                                url = 'admin/admin_exp_editar_info_general.php?codeexp=<?php echo $codeexp;?>';
                                    $.post(url, $("#form_admin_exp_localidad").serialize(),function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_localidad').html('<p><?php echo $lang_admin_cambio_exitoso; ?></p>');
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_localidad').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_campo_localidad').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_admin_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        },
                        close: function() {}
                    });
                    $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    }
                }
            });
            
            $("#form_admin_exp_curso").validate({
            rules:{
                admin_campo_curso:{
                    required:true,
                    minlength:3
                }
            },
            messages:{
                admin_campo_curso: {
                    required:"<?php echo $lang_registro_nombre_required;?>",
                    minlength:"<?php echo $lang_registro_nombre_minlenght;?>"
                }
            },
            submitHandler: function() {
                var datos_a_enviar = $("#admin_campo_curso").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_admin_ingresar_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_curso\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioCurso',
                        buttons: {
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioCurso div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_campo_curso').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_admin_seguro_guardar_cambios; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_curso\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioCurso',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_si; ?>": function() {
                                url = 'admin/admin_exp_editar_info_general.php?codeexp=<?php echo $codeexp;?>';
                                    $.post(url, $("#form_admin_exp_curso").serialize(),function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_curso').html('<p><?php echo $lang_admin_cambio_exitoso; ?></p>');
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_curso').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_campo_curso').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_admin_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        },
                        close: function() {}
                    });
                    $("div.dialogBotonesGuardarCambioCurso div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    }
                }
            });
            
            $("#form_admin_exp_establecimiento").validate({
            rules:{
                admin_campo_colegio:{
                    required:true,
                    minlength:3
                }
            },
            messages:{
                admin_campo_colegio: {
                    required:"<?php echo $lang_registro_nombre_required;?>",
                    minlength:"<?php echo $lang_registro_nombre_minlenght;?>"
                }
            },
            submitHandler: function() {
                var datos_a_enviar = $("#admin_campo_colegio").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_admin_ingresar_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_establecimiento\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioEstablecimiento',
                        buttons: {
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_campo_colegio').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_admin_seguro_guardar_cambios; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_establecimiento\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioEstablecimiento',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_si; ?>": function() {
                                url = 'admin/admin_exp_editar_info_general.php?codeexp=<?php echo $codeexp;?>';
                                    $.post(url, $("#form_admin_exp_establecimiento").serialize(),function(data) {

                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_establecimiento').html('<p><?php echo $lang_admin_cambio_exitoso; ?></p>');
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_establecimiento').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_campo_colegio').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_admin_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        },
                        close: function() {}
                    });
                    $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    }
                }
            });
            $("#form_admin_exp_publicado").validate({
            rules:{
                admin_campo_publicado:{
                    required:true
                }
            },
            messages:{
                admin_campo_publicado: {
                    required:"<?php echo $lang_registro_nombre_required;?>"
                }
            },
            submitHandler: function() {
                var datos_a_enviar = $("#admin_campo_publicado").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_admin_ingresar_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_publicado\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioPublicado',
                        buttons: {
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioPublicado div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_campo_publicado').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_admin_seguro_guardar_cambios; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_publicado\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioPublicado',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_si; ?>": function() {
                                url = 'admin/admin_exp_editar_info_general.php?codeexp=<?php echo $codeexp;?>';
                                    $.post(url, $("#form_admin_exp_publicado").serialize(),function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_publicado').html('<p><?php echo $lang_admin_cambio_exitoso; ?></p>');
                                            $("div.dialogBotonesGuardarCambioPublicado div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioPublicado div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioPublicado div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_publicado').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                                            $("div.dialogBotonesGuardarCambioPublicado div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioPublicado div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioPubblicado div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_campo_publicado').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_admin_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        },
                        close: function() {}
                    });
                    $("div.dialogBotonesGuardarCambioPublicado div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    }
                }
            });
            $("#form_admin_exp_estado").validate({
            rules:{
                admin_campo_publicado:{
                    required:true
                }
            },
            messages:{
                admin_campo_publicado: {
                    required:"<?php echo $lang_registro_nombre_required;?>"
                }
            },
            submitHandler: function() {

                var datos_a_enviar = $("#admin_campo_estado").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_admin_ingresar_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_estado\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioEstado',
                        buttons: {
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioEstado div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_campo_estado').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_admin_seguro_guardar_cambios; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_estado\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioEstado',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_si; ?>": function() {
                                url = 'admin/admin_exp_editar_info_general.php?codeexp=<?php echo $codeexp;?>&estado_actual='+campo_modificar;
                                    $.post(url, $("#form_admin_exp_estado").serialize(),function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_estado').html('<p><?php echo $lang_admin_cambio_exitoso; ?></p>');
                                            $("div.dialogBotonesGuardarCambioEstado div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioEstado div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioEstado div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_estado').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                                            $("div.dialogBotonesGuardarCambioEstado div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioEstado div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioEstado div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_campo_estado').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_admin_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_admin_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        },
                        close: function() {}
                    });
                    $("div.dialogBotonesGuardarCambioEstado div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    }
                }
            });

    });
</script>


