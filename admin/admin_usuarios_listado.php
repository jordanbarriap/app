<?php
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))
    header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz . "admin/inc/admin_functions.inc.php");

$modo = $_REQUEST["modo"]; /* modo = 0 carga de los 10 primeros usuarios   modo = 1 carga según limite superior e inferior, para implementar ver más */
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
if ($modo == 0) { /* todos los usuarios */
    $ver_mas = "usuario";
    if (!is_null($_REQUEST["grupos"])) {
        $grupos = $_REQUEST["grupos"];
        $publicando_grupo = $_REQUEST["grupo"];
        $limite_inferior = (($publicando_grupo - 1) * 10);
        $_usuarios = dbAdminObtenerUsuariosPlataforma($limite_inferior, $conexion);
    } else {
        $num_total_usuarios = dbAdminObtenerNumeroUsuariosPlataforma($conexion);
        $grupos = intval($num_total_usuarios / 10);
        $resto = $num_total_usuarios % 10;
        if ($resto > 0) {
            $grupos++;
        }
        $publicando_grupo = 1;
        $_usuarios = dbAdminObtenerUsuariosPlataforma(0, $conexion);
    }
}
if ($modo == 1) { /* Solo profesores */
    $ver_mas = "profesor";
    if (!is_null($_REQUEST["grupos"])) {
        $grupos = $_REQUEST["grupos"];
        $publicando_grupo = $_REQUEST["grupo"];
        $limite_inferior = (($publicando_grupo - 1) * 10);
        $_usuarios = dbAdminObtenerProfesores($limite_inferior, $conexion);
    } else {
        $num_total_usuarios = dbAdminObtenerNumeroProfesores($conexion);
        $grupos = intval($num_total_usuarios / 10);
        $resto = $num_total_usuarios % 10;
        if ($resto > 0) {
            $grupos++;
        }
        $publicando_grupo = 1;
        $_usuarios = dbAdminObtenerProfesores(0, $conexion);
    }
}
if ($modo == 2) { /* Solo Colaboradores */
    $ver_mas = "colaborador";
    if (!is_null($_REQUEST["grupos"])) {
        $grupos = $_REQUEST["grupos"];
        $publicando_grupo = $_REQUEST["grupo"];
        $limite_inferior = (($publicando_grupo - 1) * 10);
        $_usuarios = dbAdminObtenerColaboradores($limite_inferior, $conexion);
    } else {
        $num_total_usuarios = dbAdminObtenerNumeroColaboradores($conexion);
        $grupos = intval($num_total_usuarios / 10);
        $resto = $num_total_usuarios % 10;
        if ($resto > 0) {
            $grupos++;
        }
        $publicando_grupo = 1;
        $_usuarios = dbAdminObtenerColaboradores(0, $conexion);
    }
}
if ($modo == 3) { /* Resultados de la búsqueda */
    $ver_mas = "busqueda";
    if (!is_null($_REQUEST["grupos"])) {
        $nombre = $_REQUEST["nombre"];
        /*$apellido = $_REQUEST["apellido"];*/
        $localidad = $_REQUEST["localidad"];
        $establecimiento = $_REQUEST["establecimiento"];
        $grupos = $_REQUEST["grupos"];
        $publicando_grupo = $_REQUEST["grupo"];
        $limite_inferior = (($publicando_grupo - 1) * 10);
        $_usuarios = dbAdminFiltroBusquedaUsuario($limite_inferior, $nombre, $localidad, $establecimiento, $conexion);
    } else {
        $nombre = $_REQUEST["fr_admin_busqueda_nombre"];
       /* $apellido = $_REQUEST["fr_admin_busqueda_apellido"];*/
        $localidad = $_REQUEST["fr_admin_busqueda_localidad"];
        $establecimiento = $_REQUEST["fr_admin_busqueda_establecimiento"];
        $num_total_usuarios = dbAdminObtenerNumeroResultadosBusqueda($nombre,$localidad, $establecimiento, $conexion);
        $grupos = intval($num_total_usuarios / 10);
        $resto = $num_total_usuarios % 10;
        if ($resto > 0) {
            $grupos++;
        }
        $publicando_grupo = 1;
        $_usuarios = dbAdminFiltroBusquedaUsuario(0, $nombre, $localidad, $establecimiento, $conexion);
    }
}

if (!is_null($_usuarios)) {
    if (is_null($_REQUEST["grupos"])) {
        ?>
        <table class="admin_usuario_tabla">
            <thead>
                <tr>
                    <td class="utabla_col_nombre"><?php echo $lang_admin_nombre; ?></td>
                    <td class="utabla_col_usuario"><?php echo $lang_admin_usuario; ?></td>
                    <td class="utabla_col_rol"><?php echo $lang_admin_rol; ?></td>
                    <td class="utabla_col_opciones"><?php echo $lang_admin_opciones; ?></td>
                </tr>
            </thead>
            <br></br>
            <tbody>

        <?php
    } else {
        ?>
            <table class="admin_usuario_tabla">
                <tbody>
                <?php
            }
//    print_r($_usuarios);
            foreach ($_usuarios as $usuario) {
                $imagen_usuario = darFormatoImagen($usuario["imagen"], $config_ruta_img_perfil, $config_ruta_img);
                if ($usuario["administrador"] == 1) {
                    $rol = "administrador";
                } else {
                    if ($usuario["inscribe_diseno"] == 1) {
                        $rol = "profesor";
                    } else {
                        $rol = "estudiante";
                    }
                }
                ?>
                    <tr id="admin_usuario_<?php echo $usuario["id_usuario"]; ?>">
                        <td>
                            <img class="admin_avatar" src="<?php echo $imagen_usuario["imagen_usuario"]; ?>"/>
                            <input tabindex="1" type="text" maxlenght="30" size="30" class="admin_usuario_nombre"  id="admin_nusuario_<?php echo $usuario["id_usuario"]; ?>" name="admin_nestudiante" value="<?php echo $usuario["nombre"]; ?>" disabled="disabled"/>
                        </td>
                        <td>
                            <input tabindex="2" type="text" maxlenght="30" size="30" class="admin_usuario_usuario" id="admin_uusuario_<?php echo $usuario["id_usuario"]; ?>" name="admin_uestudiante" value="<?php echo $usuario["usuario"]; ?>" disabled="disabled"/>
                        </td>
                        <td>
                            <select  id="admin_rol_usuario_<?php echo $usuario["id_usuario"]; ?>" disabled="disabled" name=admin_campo_estado size=1 class="admin_usuario_rol" onChange="">
        <?php
        if ($rol == "estudiante") {
            ?>
                                    <option value="1" SELECTED><?php echo $lang_admin_estudiante_minus; ?> 
            <?php
        } else {
            ?>
                                    <option value="1" ><?php echo $lang_admin_estudiante_minus; ?>
                                    <?php
                                }
                                if ($rol == "profesor") {
                                    ?>
                                    <option value="2" SELECTED><?php echo $lang_admin_profesor_minus; ?>
                                        <?php
                                    } else {
                                        ?>
                                    <option value="2" ><?php echo $lang_admin_profesor_minus; ?>
                                        <?php
                                    }
                                    if ($rol == "administrador") {
                                        ?>
                                    <option value="3" SELECTED><?php echo $lang_admin_administrador; ?> 
                                        <?php
                                    } else {
                                        ?>
                                    <option value="3" ><?php echo $lang_admin_administrador; ?> 
                                        <?php
                                    }
                                    ?>
                            </select>
                        </td>
                <div class="clear"></div>
                <td>
                    <button class="admin_usuario_editar" title="<?php echo $lang_admin_editar_usuario_minus; ?>"id="admin_usuario_editar_<?php echo $usuario["id_usuario"]; ?>" onclick="javascript: adminEditarUsuario(<?php echo $usuario["id_usuario"]; ?>);"></button>
                    <button class="admin_usuario_guardar" title="<?php echo $lang_admin_guardar_cambios_minus; ?>" id="admin_usuario_guardar_<?php echo $usuario["id_usuario"]; ?>" onclick="javascript: adminGuardarUsuario(<?php echo $usuario["id_usuario"]; ?>,'<?php echo $usuario["nombre"]; ?>');" ></button>
                    <?php 
                    if($usuario["activo"]==0){
                       ?> <button class="admin_usuario_inactivo" title="<?php echo $lang_admin_usuario_inactivo; ?>"></button><?php 
                    }
                    else{
                        ?><button class="admin_usuario_eliminar" onclick="javascript:adminEliminarUsuario(<?php echo $usuario["id_usuario"]; ?>,'<?php echo $usuario["nombre"]; ?>');"></button><?php
                    }
                    ?>
                    
                    <button class="admin_usuario_resetear" title="<?php echo $lang_admin_resetear_password; ?>" id="admin_resetear_<?php echo $usuario["id_usuario"]; ?>" onclick="javascript: adminResetearContrasena(<?php echo $usuario["id_usuario"]; ?>,'<?php echo $usuario["nombre"]; ?>');"><?php echo $lang_admin_reset_password_minus; ?></button>                                 
                </td>

                </tr>
                                    <?php
                                }
                                ?>
            </tbody>
        </table>  
    <?php
} else {
    echo $lang_admin_no_resultados;
}
if ($publicando_grupo < $grupos) {
    ?>
        <div class ="admin_usuario_ver_mas" id="<?php echo $ver_mas; ?>_vermas_<?php echo $publicando_grupo + 1; ?>">
            <button class="admin_usuario_ver_mas_boton" onclick="javascript: adminUsuariosVerMas(<?php echo $grupos; ?>,<?php echo $publicando_grupo + 1; ?>);"><?php echo $lang_admin_ver_mas; ?></button>
        </div>

    <?php
}
dbDesconectarMySQL($conexion);
?>
    <script type="text/javascript">

        function adminUsuariosVerMas(grupos, grupo){
    <?php
    if ($ver_mas == "usuario") {
        ?>
                        $.get('admin/admin_usuarios_listado.php?modo=0&grupos='+grupos+'&grupo='+grupo, function(data) { 
                            $('#usuario_vermas_'+grupo).html(data);
                        });
        <?php
    }
    if ($ver_mas == "profesor") {
        ?>
                        $.get('admin/admin_usuarios_listado.php?modo=1&grupos='+grupos+'&grupo='+grupo, function(data) { 
                            $('#profesor_vermas_'+grupo).html(data);
                        });
    <?php
}
if ($ver_mas == "colaborador") {
    ?>
                        $.get('admin/admin_usuarios_listado.php?modo=2&grupos='+grupos+'&grupo='+grupo, function(data) { 
                            $('#colaborador_vermas_'+grupo).html(data);
                        });
    <?php
}
if ($ver_mas == "busqueda") {
    ?>
                        var url = 'admin/admin_usuarios_listado.php?modo=3&grupos='+grupos+'&grupo='+grupo+'&nombre=<?php echo $nombre; ?>';
    <?
    if (!is_null($apellido) && strlen($apellido) > 3) {
        ?>
                                    url+='&apellido=<?php echo $apellido; ?>';
        <?php
    }
    if (!is_null($localidad) && strlen($localidad) > 3) {
        ?>
                                    url+='&localidad=<?php echo $localidad; ?>';
        <?php
    }
    if (!is_null($establecimiento) && strlen($establecimiento) > 3) {
        ?>
                                    url+='&establecimiento=<?php echo $establecimiento; ?>';
        <?php
    }
    ?>
                                $.get(url, function(data) { 
                                    $('#busqueda_vermas_'+grupo).html(data);
                                });
    <?php
}
?>

        
                }
                function adminEditarUsuario(id_usuario){
                    $('#admin_nusuario_'+id_usuario).attr('disabled',false);
                    $('#admin_uusuario_'+id_usuario).attr('disabled',false);
                    $('#admin_rol_usuario_'+id_usuario).attr('disabled',false);
                    $('#admin_usuario_editar_'+id_usuario).hide();
                    $('#admin_usuario_guardar_'+id_usuario).show();
                    nombre_editar = $('#admin_nusuario_'+id_usuario).val();
                    usuario_editar = $('#admin_uusuario_'+id_usuario).val();
                }
                function adminEliminarUsuario(id_usuario, nombre){
                    var contenido = '<p><?php echo $lang_admin_seguro_elim_usuario; ?> '+nombre+'</p>';
                    var $dialog = $('<div id=\"dialogo_eliminar_usuario\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_eliminar_usuario; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesUsuarioEliminar',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_si; ?>": function() {
                                url = 'admin/admin_usuario_editar.php?id_usuario='+id_usuario+'&accion=2';
                                $.post(url,  function(data) {
                                    if(data == 1){//Todos los datos fueron eliminados
                                        $('#dialogo_eliminar_usuario').html("<p><?php echo $lang_admin_usuario_eliminado; ?></p>");
                                        $("div.dialogBotonesUsuarioEliminar div button:nth-child(1)").hide();
                                        $("div.dialogBotonesUsuarioEliminar div button:nth-child(2)").hide();
                                        $("div.dialogBotonesUsuarioEliminar div button:nth-child(3)").show();
                                        $("#admin_usuario_"+id_usuario).hide();
                                    }
                                    else{
                                        if(data==2){//Parte de la consulta no se ejecutó bien
                                            $('#dialogo_eliminar_usuario').html("<p><?php echo $lang_admin_usuario_cuasi_eliminado; ?></p>");
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(1)").hide();
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(2)").hide();
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(3)").show();
                                            $("#admin_usuario"+id_usuario).hide();
                                
                                        }
                                        else if(data ==3){
                                            $('#dialogo_eliminar_usuario').html("<p><?php echo $lang_admin_usuario_inactivo; ?></p>");
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(1)").hide();
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(2)").hide();
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(3)").show();
                                            $("#admin_usuario"+id_usuario).hide();
                                        }
                                        else{ // No se pudo eliminar al usuario
                                            $('#dialogo_eliminar_usuario').html("<p><?php echo $lang_admin_usuario_no_eliminado; ?></p>");
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(1)").hide();
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(2)").hide();
                                            $("div.dialogBotonesUsuarioEliminar div button:nth-child(3)").show();
                                
                                        }
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
                    $("div.dialogBotonesUsuarioEliminar div button:nth-child(3)").hide();
                    $dialog.dialog('open');
                }
    
                function adminGuardarUsuario(id_usuario, nombre){
                    $('#admin_nusuario_'+id_usuario).attr('disabled',true);
                    $('#admin_uusuario_'+id_usuario).attr('disabled',true);
                    $("#admin_rol_usuario_"+id_usuario).attr('disabled',true);
                    $('#admin_usuario_editar_'+id_usuario).show();
                    $('#admin_usuario_guardar_'+id_usuario).hide();
                    var nombre = $("#admin_nusuario_"+id_usuario).val();
                    var usuario = $("#admin_uusuario_"+id_usuario).val();          
                    var rol = $("#admin_rol_usuario_"+id_usuario).val();
                    var nombre = nombre.replace(/ /g, '.');
                    var contenido = '<p><?php echo $lang_admin_seguro_guardar_cambios; ?></p>';
                    var $dialog = $('<div id=\"dialogo_guardar_usuario\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesUsuarioGuardar',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_si; ?>": function() {
                                url = 'admin/admin_usuario_editar.php?id_usuario='+id_usuario+'&accion=1'+'&nombre='+nombre+'&usuario='+usuario+'&rol='+rol;
                                //                    var valida_nombre = adminValidarCadena(nombre);
                                //                    var valida_usuario = adminValidarCadena(usuario);
                                if(nombre.length >0 && usuario.length >0){
                        
                                    $.post(url,  function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_usuario').html('<p><?php echo $lang_admin_cambio_exitoso; ?></p>');
                                            $("div.dialogBotonesUsuarioGuardar div button:nth-child(1)").hide();
                                            $("div.dialogBotonesUsuarioGuardar div button:nth-child(2)").hide();
                                            $("div.dialogBotonesUsuarioGuardar div button:nth-child(3)").show();
                            
                                        }
                                        else{
                                            if(data == 2){
                                                $('#dialogo_guardar_usuario').html("<p><?php echo $lang_admin_cambiar_dato; ?></p>");
                                                $("div.dialogBotonesUsuarioGuardar div button:nth-child(1)").hide();
                                                $("div.dialogBotonesUsuarioGuardar div button:nth-child(2)").hide();
                                                $("div.dialogBotonesUsuarioGuardar div button:nth-child(3)").show();
                                            }
                                            else{
                                                if(data == 3){
                                                    $('#dialogo_guardar_usuario').html("<p><?php echo $lang_admin_usuario_ocupado; ?></p>");
                                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(1)").hide();
                                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(2)").hide();
                                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(3)").show();
                                                    $("#admin_uusuario_"+id_usuario).val(usuario_editar);
                                                }
                                                else{
                                                    $('#dialogo_guardar_usuario').html("<p><?php echo $lang_admin_problema; ?></p>");
                                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(1)").hide();
                                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(2)").hide();
                                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(3)").show(); 
                                                }
                                
                                            }
                                        }
                                        return false;
                                    });
                                }
                                else{
                                    $('#dialogo_guardar_usuario').html('<p><?php echo $lang_admin_no_campos_nulos; ?></p>');
                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(1)").hide();
                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(2)").hide();
                                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(3)").show();
                                    $("#admin_uusuario_"+id_usuario).val(usuario_editar);
                                    $("#admin_nusuario_"+id_usuario).val(nombre_editar);
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
                    $("div.dialogBotonesUsuarioGuardar div button:nth-child(3)").hide();
                    $dialog.dialog('open');
                }
                function adminResetearContrasena(id_usuario, nombre){
                    var contenido = ' <p><?php echo $lang_admin_seguro_cambio_password; ?> '+nombre+'</p>';
                    var $dialog = $('<div id=\"dialogo_resetear_contrasena_admin_usuario\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_admin_reset_password_mayus; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesAdminUsuarioPass',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_admin_reset_password_minus; ?>": function() {
                                url = 'admin/admin_usuario_editar.php?id_usuario='+id_usuario+'&accion=3';
                                $.post(url, function(data) {
                                    if(data!= -1 && data.length == 6){
                                        $('#dialogo_resetear_contrasena_admin_usuario').html('<p><?php echo $lang_admin_nueva_password; ?></p><div class=\"admin_resp_cambio_pass\">'+data+'</div></br>\n\
                                <p><?php echo $lang_admin_password_temporal; ?></p>');
                                                            $("div.dialogBotonesAdminUsuarioPass div button:nth-child(1)").hide();
                                                            $("div.dialogBotonesAdminUsuarioPass div button:nth-child(2)").hide();
                                                            $("div.dialogBotonesAdminUsuarioPass div button:nth-child(3)").show();
                            
                                                        }
                                                        else{
                                                            $('#dialogo_resetear_contrasena_admin_usuario').html("<p><?php echo $lang_admin_hubo_problema; ?></p>");
                                                            $("div.dialogBotonesAdminUsuarioPass div button:nth-child(1)").hide();
                                                            $("div.dialogBotonesAdminUsuarioPass div button:nth-child(2)").hide();
                                                            $("div.dialogBotonesAdminUsuarioPass div button:nth-child(3)").show();
                            
                            
                                                        }
                                                        return false;
                                                    });
                                                },
                                                //CANCELAR
                                                "<?php echo $lang_cancelar; ?>": function() {
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
                                        $("div.dialogBotonesAdminUsuarioPass div button:nth-child(3)").hide();
                                        $dialog.dialog('open');
                                    }
                                    $(document).ready(function(){
                                        var usuario_editar;
                                        var nombre_editar;
                                        $('.admin_usuario_guardar').hide();
        
                                    });
    </script>
