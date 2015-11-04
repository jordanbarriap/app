<?php

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$codeexp = $_REQUEST["codeexp"];
$grupos = $_REQUEST["grupos"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$ver_mas ="estudiantes";
$modo = $_REQUEST["modo"]; //2: mostrar resultados busqueda, otro: muestrar estudiantes inscritos en la experiencia
if(!is_null($modo) && $modo==2){
    $nombre = $_REQUEST["nombre"];
    $apellido = $_REQUEST["apellido"];
    $_estudiantes = dbAdminBusquedaEstudiante($nombre,$apellido, $codeexp, $conexion);
    
 
}
else{
    if(!is_null($_REQUEST["grupos"])){
        $publicando_grupo = $_REQUEST["grupo"];
        $limite_inferior = (($publicando_grupo - 1)*10);     
        $_estudiantes = dbAdminObtenerEstudiantesExperiencia($limite_inferior,$codeexp, $conexion);
        
    }
    else{
        $num_total_usuarios = dbAdminObtenerNumeroEstudiantesExperiencia($codeexp, $conexion);
        $grupos = intval($num_total_usuarios/10);
        $resto = $num_total_usuarios%10;
        if($resto > 0){
            $grupos++;
        }
        $publicando_grupo = 1;
        $_estudiantes = dbAdminObtenerEstudiantesExperiencia(0,$codeexp, $conexion);
        //formulario de busqueda
        
    }
}
if(!is_null($_estudiantes) && count($_estudiantes)>0){
    if(is_null($_REQUEST["grupos"])){
        if(!is_null($modo) && $modo==2){
        ?>
            <div class="admin_exp_estudiantes"><?php echo $lang_admin_resultados_busqueda; ?></div>
        <?php
        }else{
        ?>
            <div class="admin_exp_estudiantes"><?php echo $lang_admin_estudiantes_exp; ?></div>
        <?php
        }
    
        
if(!is_null($modo) && $modo==2){
?>
    <div class="admin_exp_bloque_busqueda">
        <input id="admin_exp_volver_busqueda" class="admin_exp_busqueda_estudiante" type="button" value="<?php echo $lang_admin_ver_todos_estudiantes; ?>" >
    </div>
    <?php
}
else{
    ?>
    <div class="admin_exp_bloque_busqueda">
        <input id="admin_exp_buscar_estudiante" class="admin_exp_busqueda_estudiante" type="button" value="<?php echo $lang_admin_buscar_estudiante; ?>" >
    </div>
    <div class="clear"></div>
    <form id="form_admin_exp_busqueda" method="post" action="">
        <div id="caja_form_busqueda_estudiante">
            <label><?php echo $lang_registro_nombre." :";?></label>
            <input tabindex="1" type="text" maxlenght="20" size="20" id="fr_admin_bus_nombre" name="fr_admin_bus_nombre"/>
            <label  class="sugerencia" id="admin_suge_nombre"><?php echo $lang_admin_solo_un_nombre; ?></label>
            <div class="clear"></div>
            <label><?php echo $lang_registro_apellido." :";?></label>
            <input tabindex ="2" type="text" maxlenght="20" size="20" id="fr_admin_bus_apellido" name="fr_admin_bus_apellido"/>
            <label class="sugerencia" id="admin_suge_apellido"><?php echo $lang_admin_solo_un_apellido; ?></label>
            <div class="clear"></div>
            <input class="submit" type="submit" value="<?php echo $lang_admin_buscar; ?>">
        </div>
    </form>
    <?php
}
?>
    
    <table class="admin_usuario_tabla">
        <thead>
            <tr>
                <td class="utabla_col_nombre"><?php echo $lang_admin_nombre; ?></td>
                <td class="utabla_col_usuario"><?php echo $lang_admin_usuario; ?></td>
                <td class="utabla_col_opciones"><?php echo $lang_admin_opciones; ?></td>
            </tr>
        </thead>
        <tbody>

    <?php
    }
    else{
    ?>
     <table class="admin_usuario_tabla">
        <tbody>
    <?php
        
    }
    foreach ($_estudiantes as $usuario){
        $imagen_usuario = darFormatoImagen($usuario["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
        ?>
        <tr id="admin_estudiante_<?php echo $usuario["id_usuario"];?>">
            <td>
                <img class="admin_avatar" src="<?php echo $imagen_usuario["imagen_usuario"]; ?>"/>
                <input tabindex="1" type="text" maxlenght="30" size="30" class="admin_usuario_nombre"  id="admin_nestudiante_<?php echo $usuario["id_usuario"];?>" name="admin_nestudiante" value="<?php echo $usuario["nombre"];?>" disabled="disabled"/>
            </td>
            <td>
                <input tabindex="2" type="text" maxlenght="30" size="30" class="admin_usuario_usuario" id="admin_uestudiante_<?php echo $usuario["id_usuario"];?>" name="admin_uestudiante" value="<?php echo $usuario["usuario"];?>" disabled="disabled"/>
            </td>
            <div class="clear"></div>
            <td>
                <button class="admin_usuario_editar" id="admin_estudiante_editar_<?php echo $usuario["id_usuario"];?>" onclick="javascript: adminEditarEstudiante(<?php echo $usuario["id_usuario"];?>);"></button>
                <button class="admin_estudiante_guardar" id="admin_estudiante_guardar_<?php echo $usuario["id_usuario"];?>" onclick="javascript: adminGuardarEstudiante(<?php echo $usuario["id_usuario"];?>,'<?php echo $usuario["nombre"];?>');" ></button>
                <button class="admin_usuario_eliminar" onclick="javascript:adminEliminarEstudiante(<?php echo $usuario["id_usuario"];?>,'<?php echo $usuario["nombre"];?>');"></button>
                <button class="admin_usuario_resetear" id="admin_estudiante_resetear_<?php echo $usuario["id_usuario"];?>" onclick="javascript: adminResetearContrasena(<?php echo $usuario["id_usuario"];?>,'<?php echo $usuario["nombre"];?>');"><?php echo $lang_admin_reset_password; ?></button>                                 
            </td>

        </tr>
        <?php
    }

    ?>
      </tbody>
    </table>  
    <?php
}
else{
    if(!is_null($modo) && $modo==2){
    ?>
        <div class="admin_exp_estudiantes"><?php echo $lang_admin_no_resultados; ?></div>
        <div class="admin_exp_bloque_busqueda">
            <input id="admin_exp_volver_busqueda" class="admin_exp_busqueda_estudiante" type="button" value="<?php echo $lang_admin_ver_todos_estudiantes; ?>" >
        </div>
    </br>
        <?php
    }
    else{
    ?>
        <div class="admin_exp_estudiantes"><?php echo $lang_admin_no_hay_estudiantes; ?></div>
    <?php
    }
}
if($publicando_grupo < $grupos){
?>
        <div class ="admin_usuario_ver_mas" id="<?php echo $ver_mas;?>_vermas_<?php echo $publicando_grupo+1;?>">
            <button class="admin_usuario_ver_mas_boton" onclick="javascript: adminEstudianteVerMas(<?php echo $grupos;?>,<?php echo $publicando_grupo+1;?>);"><?php echo $lang_admin_ver_mas; ?></button>
        </div>

<?php
}
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    

    function adminEstudianteVerMas(grupos, grupo){
        $.get('admin/admin_exp_listado_estudiantes.php?codeexp=<?php echo $codeexp;?>&grupos='+grupos+'&grupo='+grupo, function(data) { 
            $('#estudiantes_vermas_'+grupo).html(data);
        });
    }
      function adminEditarEstudiante(id_usuario){
        $('#admin_nestudiante_'+id_usuario).attr('disabled',false);
        $('#admin_uestudiante_'+id_usuario).attr('disabled',false);
        $('#admin_estudiante_editar_'+id_usuario).hide();
        $('#admin_estudiante_guardar_'+id_usuario).show();
        estudiante_nombre_editar = $('#admin_nestudiante_'+id_usuario).val();
        estudiante_usuario_editar = $('#admin_uestudiante_'+id_usuario).val();
    }
    function adminEliminarEstudiante(id_estudiante, nombre){
        var contenido = '<p><?php echo $lang_admin_seguro_eliminar_est; ?> '+nombre+'</p>';
        var $dialog = $('<div class=\"dialogo_opciones_estudiante\" id=\"dialogo_eliminar_estudiante\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_eliminar_estudiante; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesEstudianteEliminar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_si; ?>": function() {
                    url = 'admin/admin_exp_editar_estudiantes.php?id_usuario='+id_estudiante+'&accion=2'+'&id_exp=<?php echo $codeexp;?>';
                    $.post(url,  function(data) {
                        if(data){
                            $('#dialogo_eliminar_estudiante').html(data);
                            $("div.dialogBotonesEstudianteEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesEstudianteEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesEstudianteEliminar div button:nth-child(3)").show();
                            cargarEstudiantes();
                        }
                        return false;
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
        $("div.dialogBotonesEstudianteEliminar div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }
    
    function adminGuardarEstudiante(id_usuario, nombre){
        $('#admin_nestudiante_'+id_usuario).attr('disabled',true);
        $('#admin_uestudiante_'+id_usuario).attr('disabled',true);
        $('#admin_estudiante_editar_'+id_usuario).show();
        $('#admin_estudiante_guardar_'+id_usuario).hide();
        var nombre = $("#admin_nestudiante_"+id_usuario).val();
        var usuario = $("#admin_uestudiante_"+id_usuario).val();
        
        var nombre = nombre.replace(/ /g, '.');
//        var arreglo_nombre = nombre.split(" ");
        var contenido = '<p><?php echo $lang_admin_seguro_guardar_cambios; ?></p>';
        var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_estudiante\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_guardar_cambios; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesEstudianteGuardar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_si; ?>": function() {
//                    url = 'admin/admin_exp_editar_estudiantes.php?id_usuario='+id_usuario+'&accion=1'+'&nombre='+arreglo_nombre[0]+'&apellido='+arreglo_nombre[1]+'&usuario='+usuario;
                    url = 'admin/admin_exp_editar_estudiantes.php?id_usuario='+id_usuario+'&accion=1'+'&nombre='+nombre+'&usuario='+usuario;
                    if(nombre.length >0 && usuario.length >0){
                        $.post(url,function(data) {
                        if(data == 1){
                            $('#dialogo_guardar_estudiante').html('<p><?php echo $lang_admin_cambio_exitoso; ?></p>');
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                            
                        }
                        else{
                            if(data == 2){
                                $('#dialogo_guardar_estudiante').html("<p><?php echo $lang_admin_cambiar_dato; ?></p>");
                                $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                                $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                                $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                            }
                            else{
                                if(data == 3){
                                    $('#dialogo_guardar_estudiante').html("<p><?php echo $lang_admin_usuario_ocupado; ?></p>");
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                                     $("#admin_uestudiante_"+id_usuario).val(estudiante_usuario_editar);
                                }
                                else{
                                    $('#dialogo_guardar_estudiante').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show(); 
                                }
                                
                            }
                        }
                        return false;
                    });
                    }
                    else{
                            $('#dialogo_guardar_usuario').html('<p><?php echo $lang_admin_no_cambios_nulos; ?></p>');
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                            $("#admin_uestudiante_"+id_usuario).val(estudiante_usuario_editar);
                            $("#admin_nestudiante_"+id_usuario).val(estudiante_nombre_editar);
                    }
                    
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
        $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }
    function adminResetearContrasena(id_usuario, nombre){
        var contenido = ' <p><?php echo $lang_admin_seguro_cambio_password; ?> '+nombre+'</p>';
        var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_resetear_contrasena_admin_exp\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_reset_password; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesAdminEstudiantePass',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_reset_password; ?>": function() {
                    url = 'admin/admin_exp_editar_estudiantes.php?id_usuario='+id_usuario+'&accion=3';
                    $.post(url, function(data) {
                        if(data!= -1){
                            $('#dialogo_resetear_contrasena_admin_exp').html('<p><?php echo $lang_admin_nueva_password; ?></p><div class=\"admin_resp_cambio_pass\">'+data+'</div></br>\n\
                            <p><?php echo $lang_admin_password_temporal; ?></p>');
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(1)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(2)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(3)").show();
                            
                        }
                        else{
                            $('#dialogo_resetear_contrasena_admin_exp').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(1)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(2)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(3)").show();
                            
                            
                        }
                        return false;
                    });
                },
                //CANCELAR
                "<?php echo $lang_admin_cancelar; ?>": function() {
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
        $("div.dialogBotonesAdminEstudiantePass div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }
    $(document).ready(function(){
        var estudiante_usuario_editar;
        var estudiante_nombre_editar;
        
        $(".sugerencia").hide();
        $("#form_admin_exp_busqueda").hide();
        $('.admin_estudiante_guardar').hide();
        
        $("#admin_exp_buscar_estudiante").click(function() {
            $("#form_admin_exp_busqueda").show();
        });
        $("#admin_exp_volver_busqueda").click(function() {
            cargarEstudiantes();
        });
        $("#form_admin_exp_busqueda").validate({
            rules:{
                fr_admin_bus_nombre:{
                    required:true,
                    minlength:3
                },
                fr_admin_bus_apellido:{
                    required:false,
                    minlength:3
                }
            },
            messages:{
                
                fr_admin_bus_nombre: {
                    required:"<?php echo $lang_registro_nombre_required;?>",
                    minlength:"<?php echo $lang_registro_nombre_minlenght;?>"
                },
                fr_admin_bus_apellido:{
                    minlength:"<?php echo $lang_registro_apellido_minlength;?>"
                }
            },
            submitHandler: function() {
                var nombre = $('#fr_admin_bus_nombre').val();
                var apellido = $('#fr_admin_bus_apellido').val();
                url = 'admin/admin_exp_listado_estudiantes.php?codeexp=<?php echo $codeexp;?>'+'&modo=2&nombre='+nombre;
                if(apellido != null){
                    url= url + "&apellido="+apellido;
                }
//                $.post(url, $("#form_admin_busqueda").serialize(), function(data) {
                $.post(url, function(data) {
                    $('#admin_estudiantes_c').html(data);

                });
            }
        });

        $("#fr_admin_bus_nombre").focus(function() {
            $("#admin_suge_nombre").show();
        });
        $("#fr_admin_bus_apellido").focus(function() {
            $("#admin_suge_apellido").show();
        });
        $("#fr_admin_bus_nombre").blur(function() {
            $("#admin_suge_nombre").hide();
        });
         $("#fr_admin_bus_apellido").blur(function() {
            $("#admin_suge_apellido").hide();
        });
        
    });
</script>
