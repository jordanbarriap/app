<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$agno = $_GET["agno"];
$sector = $_GET["sector"];
$disenos = dbAdminObtenerDisenos($agno, $sector, $conexion);

$num_disenos = 1;
$lim_inf = 0;
$lim_sup = 2;
?>
<div class="admin_contenido">
    <div id="admin_listado_experiencias">
        <?php
            if(count($disenos)>0){
                for($i=0; $i<count($disenos); $i++){
        ?>
                            <div class="admin_cuadro_experiencia">
                                <table class="t_experiencia_cabecera <?php if($disenos[$i]['dd_publicado']==1){echo "en_curso";} if($disenos[$i]['dd_revision']==1){echo "en_revision";}?>"  >
                                    <tr>
                                        <td>
                                            <p onclick="javascript: irAdminDiseno(<?php echo $disenos[$i]['dd_id_diseno_didactico'];?>);" class="admin_cuadro_exp_nombre_dd">
                                                <?php echo $disenos[$i]['dd_nombre']; ?>
                                            </p>
                                            <div class="info_exp">(<?php echo $disenos[$i]['dd_subsector'].",".$disenos[$i]['dd_nivel']; ?>)</div>
                                            <ul class="imagen_profesor_exp">
                                                <li><?php echo $lang_crear_diseno_admin_creado; ?> <?php echo $disenos[$i]['dd_fecha_creacion']; ?>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <div class="avance_exp"></div>
                                        </td>
                                    <?php
                                    if($disenos[$i]['dd_publicado']==1){
                                    ?>
                                        <td>
                                            <input class="admin_ir" type="button" onclick="javascript: adminPublicarDiseno(<?php echo $disenos[$i]['dd_id_diseno_didactico']; ?>,0);" value="<?php echo $lang_crear_diseno_admin_no_publicar; ?>"><br>
                                        </td>
                                    <?php
                                    }else{
                                    ?>
                                        <td>
                                            <input class="admin_ir" type="button" onclick="javascript: adminPublicarDiseno(<?php echo $disenos[$i]['dd_id_diseno_didactico']; ?>,1);" value="<?php echo $lang_crear_diseno_admin_publicar; ?>"><br>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                        <td>
                                            <input class="admin_ir" type="button" onclick="javascript: adminEliminarDiseno(<?php echo $disenos[$i]['dd_id_diseno_didactico']; ?>);" value="<?php echo $lang_crear_diseno_admin_eliminar; ?>"><br>
                                        </td> 
                                        <td>
                                            <input class="admin_ir" type="button" onclick="javascript: irAdminDiseno(<?php echo $disenos[$i]['dd_id_diseno_didactico']; ?>);" value="<?php echo $lang_crear_diseno_admin_ir; ?>"><br>
                                        </td>
                                        
                                    </tr>
                                </table>
                            </div>
        <?php
                }
            }else{
                echo $lang_admin_no_hay_disenos;
;
            }

        dbDesconectarMySQL($conexion);
        ?>
    </div>
</div>    
    <script type="text/javascript">
        
        function adminPublicarDiseno(id_diseno, accion){
            $.get('admin/admin_diseno_publicar.php?id_diseno='+id_diseno+'&accion='+accion, function(data) {
                if(data == 99){
                    var $dialog99 = $('<div><p><br><?php echo $lang_crear_diseno_admin_despub_err; ?></p></div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_crear_diseno_admin_despub_tit; ?>',
                        dialogClass: 'uii-dialog',
                        width: 450,
                        height: 150,
                        zIndex: 3999,
                        modal: true,
                        close: function(ev, ui) {
                            $(this).remove();
                        },
                        buttons: {
                            "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                    $dialog99.dialog('open');
                    return false;                    
                }
                adminCargarDiseno(agnoSeleccionado, sectorSeleccionado);
            });

        }
        function irAdminDiseno(id_diseno){
            $.get('taller_dd/tdd_form_crear_diseno_admin.php?idDiseno='+id_diseno, function(data) {
                $('#admin_contenido').html(data);
            });

        }
        $(document).ready(function(){
            $('.nombre_profesor_exp_todas').click(function() {
                var $linkc = $(this);
                var $dialog = $('<div></div>')
                .load($linkc.attr('href'))
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_crear_diseno_admin_perfil; ?>',
                    width: 800,
                    height: 600,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_crear_diseno_admin_cerrar; ?>": function() {
                        $(this).dialog("close");
                        }
                    },
                    close: function(ev, ui) {
                        $(this).remove();
                    }
                    });
                $dialog.dialog('open');
                return false;
           });
        });
        
    function adminEliminarDiseno(id_diseno){
        var contenido = '<p><?php echo $lang_crear_diseno_admin_elim_preg; ?></p>';
        var $dialog = $('<div class=\"dialogo_eliminar_diseno\" id=\"dialogo_eliminar_diseno\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_crear_diseno_admin_elim; ?>',
            width: 500,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesDisenoEliminar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_crear_diseno_admin_si; ?>": function() {
                    url = './taller_dd/tdd_eliminarDisenoAdmin.php?id_diseno='+id_diseno;
                    $.post(url,  function(data) {
                        if(data == 1){
                            $('#dialogo_eliminar_diseno').html('<p><?php echo $lang_crear_diseno_admin_elim_ok; ?></p>');
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(3)").show();
                            adminAdministrarDisenos();
                        }
                        else if(data == 99){
                            $('#dialogo_eliminar_diseno').html('<p><?php echo $lang_crear_diseno_admin_elim_err1; ?></p>');
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(3)").show();                            
                        }
                        else{
                            $('#dialogo_eliminar_diseno').html('<p><?php echo $lang_crear_diseno_admin_elim_err2; ?></p>');
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesDisenoEliminar div button:nth-child(3)").show();
                        }
                    });
                },
                //CANCELAR
                "<?php echo $lang_crear_diseno_admin_no; ?>": function() {
                    $(this).dialog('destroy').remove();
                },
                //CERRAR
                "<?php echo $lang_crear_diseno_admin_cerrar; ?>": function() {
                    $(this).dialog('destroy').remove();
                }
            },
            close: function() {
            }
            });
        $("div.dialogBotonesDisenoEliminar div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }


</script>