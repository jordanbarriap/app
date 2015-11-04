<?php
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
 

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$codeexp = $_REQUEST["codeexp"];
$grupos = $_REQUEST["grupos"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$ver_mas ="estudiantes";
$modo = $_REQUEST["modo"]; //2: mostrar resultados busqueda, otro: muestrar estudiantes inscritos en la experiencia

$datos_experiencia = dbExpObtenerInfo($codeexp, $conexion);
$esta_finalizada    = ($datos_experiencia["fecha_termino"] != '')?"1":"0";

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
        ?>
    <p class="intro_etapas"><?php echo $lang_admin_ee_est_inscritos; ?>: </p></br>  
    <table class="admin_exp_usuario_tabla">
        <thead>
            <tr>
                <td class="utabla_col_nombre"><?php echo $lang_admin_ee_nombre; ?></td>
                <td class="utabla_col_usuario"><?php echo $lang_admin_ee_usuario; ?></td>
                <td class="utabla_col_opciones"><?php echo $lang_admin_ee_opciones; ?></td>
            </tr>
        </thead>
        <tbody>

    <?php
    }
    else{
    ?>
     <table class="admin_exp_usuario_tabla">
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
                <button class="admin_usuario_resetear" id="admin_estudiante_resetear_<?php echo $usuario["id_usuario"];?>" onclick="javascript: adminResetearContrasena(<?php echo $usuario["id_usuario"];?>,'<?php echo $usuario["nombre"];?>');"><?php echo $lang_admin_ee_resetear_contrasena; ?></button>                                 
            </td>

        </tr>
        <?php
    }

    ?>
      </tbody>
    </table>  
    <?php
}
if($publicando_grupo < $grupos){
?>
        <div class ="admin_usuario_ver_mas" id="<?php echo $ver_mas;?>_vermas_<?php echo $publicando_grupo+1;?>">
            <button class="admin_exp_estudiantes_ver_mas_boton" onclick="javascript: adminEstudianteVerMas(<?php echo $grupos;?>,<?php echo $publicando_grupo+1;?>);"><?php echo $lang_admin_ee_ver_mas; ?></button>
        </div>

<?php
}
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    

    function adminEstudianteVerMas(grupos, grupo){
        $.get('admin_exp_estudiantes.php?codeexp=<?php echo $codeexp;?>&grupos='+grupos+'&grupo='+grupo, function(data) { 
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
    function cargarEstudiantes(){
        $.get('admin_exp_estudiantes.php?codeexp=<?php echo $codeexp;?>', function(data) {                  
          $('#administrador_estudiantes').html(data);
        });
    }
    function adminEliminarEstudiante(id_estudiante, nombre){
        var contenido = '<p><?php echo $lang_admin_ee_seguro_eliminar; ?> '+nombre+'. </p><p class ="\advertencia_estudiante"\><?php echo $lang_admin_ee_elimina_est_exp; ?></p>';
        var $dialog = $('<div class=\"dialogo_opciones_estudiante\" id=\"dialogo_eliminar_estudiante\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_ee_eliminar_estudiante; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesEstudianteEliminar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_ee_si; ?>": function() {
                    url = 'admin_exp_estudiantes_editar.php?id_usuario='+id_estudiante+'&accion=2'+'&id_exp=<?php echo $codeexp;?>';
                    $.post(url,  function(data) {
                        if(data){
                            $('#dialogo_eliminar_estudiante').html(data);
                            $("div.dialogBotonesEstudianteEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesEstudianteEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesEstudianteEliminar div button:nth-child(3)").show();
                            cargarEstudiantes();
                            adminProfCargarAdminGrupos();
                        }
                        return false;
                    });
                },
                //CANCELAR
                "<?php echo $lang_admin_ee_no; ?>": function() {
                    $(this).dialog('destroy').remove();
                },
                //CERRAR
                "<?php echo $lang_admin_ee_cerrar; ?>": function() {
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
        var contenido = '<p><?php echo $lang_admin_ee_seguro_guardar; ?></p>';
        var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_estudiante\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_ee_guardar_cambios; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesEstudianteGuardar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_ee_si; ?>": function() {
//                    url = 'admin/admin_exp_editar_estudiantes.php?id_usuario='+id_usuario+'&accion=1'+'&nombre='+arreglo_nombre[0]+'&apellido='+arreglo_nombre[1]+'&usuario='+usuario;
                    url = 'admin_exp_estudiantes_editar.php?id_usuario='+id_usuario+'&accion=1'+'&nombre='+nombre+'&usuario='+usuario;
                    if(nombre.length >0 && usuario.length >0){
                        $.post(url,function(data) {
                        if(data == 1){
                            $('#dialogo_guardar_estudiante').html('<p><?php echo $lang_admin_ee_cambio_exito; ?></p>');
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                            adminProfCargarAdminGrupos();
                            
                        }
                        else{
                            if(data == 2){
                                $('#dialogo_guardar_estudiante').html("<p><?php echo $lang_admin_ee_cambiar_datos; ?></p>");
                                $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                                $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                                $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                            }
                            else{
                                if(data == 3){
                                    $('#dialogo_guardar_estudiante').html("<p><?php echo $lang_admin_ee_nombre_ocupado; ?></p>");
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                                    $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                                     $("#admin_uestudiante_"+id_usuario).val(estudiante_usuario_editar);
                                }
                                else{
                                    $('#dialogo_guardar_estudiante').html("<p><?php echo $lang_admin_ee_problema; ?></p>");
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
                            $('#dialogo_guardar_usuario').html('<p><?php echo $lang_admin_ee_campos_nulos; ?></p>');
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(1)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(2)").hide();
                            $("div.dialogBotonesEstudianteGuardar div button:nth-child(3)").show();
                            $("#admin_uestudiante_"+id_usuario).val(estudiante_usuario_editar);
                            $("#admin_nestudiante_"+id_usuario).val(estudiante_nombre_editar);
                    }
                    
                },
                //CANCELAR
                "<?php echo $lang_admin_ee_no; ?>": function() {
                    $(this).dialog('destroy').remove();
                },
                //CERRAR
                "<?php echo $lang_admin_ee_cerrar; ?>": function() {
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
        var contenido = ' <p><?php echo $lang_admin_ee_seguro_cambiar_contrasena; ?> '+nombre+'</p>';
        var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_resetear_contrasena_admin_exp\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_ee_resetear_contrasena; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesAdminEstudiantePass',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_ee_resetear_contrasena; ?>": function() {
                    url = 'admin_exp_estudiantes_editar.php?id_usuario='+id_usuario+'&accion=3';
                    $.post(url, function(data) {
                        if(data!= -1){
                            $('#dialogo_resetear_contrasena_admin_exp').html('<p><?php echo $lang_admin_ee_nueva_contrasena; ?>:</p><div class=\"admin_resp_cambio_pass\">'+data+'</div></br>\n\
                            <p><?php echo $lang_admin_ee_contrasena_temporal; ?></p>');
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(1)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(2)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(3)").show();
                            
                        }
                        else{
                            $('#dialogo_resetear_contrasena_admin_exp').html("<p><?php echo $lang_admin_ee_problema; ?></p>");
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(1)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(2)").hide();
                            $("div.dialogBotonesAdminEstudiantePass div button:nth-child(3)").show();
                            
                            
                        }
                        return false;
                    });
                },
                //CANCELAR
                "<?php echo $lang_admin_ee_cancelar; ?>": function() {
                    $(this).dialog('destroy').remove();
                },
                //CERRAR
                "<?php echo $lang_admin_ee_cerrar; ?>": function() {
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
        
         <?php
        if($esta_finalizada){?>
            $(".admin_usuario_editar").attr('disabled', true);
            $(".admin_estudiante_guardar").attr('disabled', true);
            $(".admin_usuario_eliminar").attr('disabled', true);
            $(".admin_usuario_resetear").attr('disabled', true);
            $(".admin_usuario_editar").addClass("admin_desactivado");
            $(".admin_estudiante_guardar").addClass("admin_desactivado");
            $(".admin_usuario_eliminar").addClass("admin_desactivado");
            $(".admin_usuario_resetear").addClass("admin_desactivado");
            $(".admin_usuario_editar").attr('title', '<?php echo $lang_admin_ee_btn_desactivado; ?>');
            $(".admin_estudiante_guardar").attr('title', '<?php echo $lang_admin_ee_btn_desactivado; ?>');
            $(".admin_usuario_eliminar").attr('title', '<?php echo $lang_admin_ee_btn_desactivado; ?>');
            $(".admin_usuario_resetear").attr('title', '<?php echo $lang_admin_ee_btn_desactivado; ?>');
        <?php
        }
        ?>
        
        $(".sugerencia").hide();
        $("#form_admin_exp_busqueda").hide();
        $('.admin_estudiante_guardar').hide();
        
        $("#admin_exp_buscar_estudiante").click(function() {
            $("#form_admin_exp_busqueda").show();
        });
        $("#admin_exp_volver_busqueda").click(function() {
            cargarEstudiantes();
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