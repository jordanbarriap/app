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

$id_usuario = $_REQUEST["id_usuario"];
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);

$_datos_usuario = dbAdminObtenerInfoUsuario($id_usuario, $conexion);

/*Experiencias en curso*/
$_experiencias = dbAdminObtenerExperienciasEnCurso($conexion);
$_imagen = darFormatoImagen($_datos_usuario["imagen"], $config_ruta_img_perfil, $config_ruta_img);
$imagen_normal = $_imagen["imagen_usuario"];
$imagen_grande = $_imagen["imagen_grande"];
?>
<div class="admin_usuario_creado_intro"><?php echo $lang_admin_datos_nuevo_usuario; ?></div>
<div class="admin_usuario_creado">
    <table>
        <tr>
            <td>
                <div class="admin_img_usuario_creado">
                    <img alt="<?php echo $perfil_usuario;?>" src="<?php echo $imagen_grande;?>" height="62"/>
                </div>
                <div class="admin_usuario_creado_datos">
                    <p class="datos_personales"><?php echo "<b>".$lang_perfil_nombre."</b>: ";
                        if(strlen($_datos_usuario["nombre"])!='') echo $_datos_usuario["nombre"]; else echo $lang_perfil_sin_informacion;?></p>
                    <p class="datos_personales"><?php echo "<b>".$lang_perfil_correo."</b>: ";
                        if(strlen($_datos_usuario["email"])!='') echo $_datos_usuario["email"]; else echo $lang_perfil_sin_informacion;?></p>
                    <p class="datos_personales"><?php echo "<b>".$lang_perfil_localidad."</b>: ";
                        if($_datos_usuario["localidad"]!='') echo $_datos_usuario["localidad"]; else echo $lang_perfil_sin_informacion;?></p>
                    <p class="datos_personales"><?php echo "<b>".$lang_perfil_establecimiento."</b>: ";
                        if($_datos_usuario["establecimiento"]!='') echo $_datos_usuario["establecimiento"]; else echo $lang_perfil_sin_informacion;?></p>
                         
                </div>
            </td>
        </tr>
    </table>
</div>
</br>
<?php
    if(!is_null($_experiencias)){
        ?>
        <div class ="admin_usuario_creado_intro_exp">
            <p><?php echo $lang_admin_asociar_usuario_exp; ?><p>
        </div>
        <?php
        $i++;
        foreach ($_experiencias as $_experiencia) {
            $_imagenes = darFormatoImagen($_experiencia["url_avatar_profesor"], $config_ruta_img_perfil, $config_ruta_img);
                ?>
                <div class="admin_cuadro_experiencia_asignar_usuario">
                    <table class="t_experiencia_cabecera">
                        <tr>
                            <td>
                                <p class="admin_cuadro_exp_nombre_dd">
                                    <?php echo $_experiencia["nombre_dd"]; ?>
                                </p>
                                <div class="info_exp">(<?php echo $_experiencia["curso"] . ", " . $_experiencia["colegio"] . ", " . $_experiencia["localidad"]; ?>)</div>
                                <ul class="imagen_profesor_exp">
                                    <li>
                                        <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia["usuario_profesor"]; ?>" alt="<?php echo $_experiencia["nombre_profesor"]; ?>" title="<?php echo $_experiencia["nombre_profesor"]; ?>" class ="nombre_profesor_exp_todas">
                                            <img class="admin_avatar" src="<?php echo $_imagenes[imagen_usuario]; ?>"/></a>
                                    </li>
                                    <li>
                                        <a class="nombre_profesor_exp_todas"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia["usuario_profesor"]; ?>" alt="<?php echo $_experiencia["nombre_profesor"]; ?>" title="<?php echo $_experiencia["nombre_profesor"]; ?>" class ="link_perfil"><?php echo ucwords($_experiencia["nombre_profesor"]); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <input id ="admin_usuario_boton_agregar_<?php echo $_experiencia["id_experiencia"];?>" class="admin_usuario_boton_vincular" type="button" onclick="javascript: adminAsignarUsuarioExperiencia(<?php echo $_experiencia["id_experiencia"];?>);" value="<?php echo $lang_admin_asignar_a_exp; ?>">
                                <input id ="admin_usuario_boton_agregado_<?php echo $_experiencia["id_experiencia"];?>" class="admin_usuario_boton_vinculado" type="button" value="<?php echo $lang_admin_asignado; ?>"><br>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php

            }           
    }
    if($i==0){
        echo $lang_admin_no_exp_disponibles;
    }
    dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    
    function adminAsignarUsuarioExperiencia(id_experiencia){
        
        var contenido = '<p><?php echo $lang_admin_seleccione_rol_usuario_exp; ?></p>'+
                        '   <form>'+
                        '       <select  class="admin_usuario_select_rol" id= "admin_rol_vincula">'+
                        '           <option value="2" SELECTED><?php echo $lang_admin_estudiante; ?> '+
                        '           <option value="3" ><?php echo $lang_admin_colaborador; ?>'+
                        '       </select>'+
                        '   </form>';
        var $dialog = $('<div id=\"dialogo_vincular_usuario_exp\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_vincular_usuario_exp; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesUsuarioVincular',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_vincular; ?>": function() {
                   var rol = $("#admin_rol_vincula").val();
                   url = 'admin/admin_usuario_guarda_usuario_exp.php?id_usuario=<?php echo $id_usuario?>&id_exp='+id_experiencia+'&rol='+rol;
                    $.post(url,  function(data) {
                    if(data == 1){
                        $('#dialogo_vincular_usuario_exp').html('<p><?php echo $lang_admin_asign_exitosa; ?></p>');
                        $('#admin_usuario_boton_agregar_'+id_experiencia).hide();
                        $('#admin_usuario_boton_agregado_'+id_experiencia).show();
                        $("div.dialogBotonesUsuarioVincular div button:nth-child(1)").hide();
                        $("div.dialogBotonesUsuarioVincular div button:nth-child(2)").hide();
                        $("div.dialogBotonesUsuarioVincular div button:nth-child(3)").show();

                    }
                    else{
//                        $('#dialogo_vincular_usuario_exp').html(data);
                        $('#dialogo_vincular_usuario_exp').html("<p><?php echo $lang_admin_hubo_problema; ?></p>");
                        $("div.dialogBotonesUsuarioVincular div button:nth-child(1)").hide();
                        $("div.dialogBotonesUsuarioVincular div button:nth-child(2)").hide();
                        $("div.dialogBotonesUsuarioVincular div button:nth-child(3)").show();                        
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
        $("div.dialogBotonesUsuarioVincular div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }
    
    $(document).ready(function(){

        $('.admin_usuario_boton_vinculado').hide();
        
    });
</script>