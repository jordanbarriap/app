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

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$codeexp = $_REQUEST["codeexp"];
$modo = $_REQUEST["modo"];
$nombre_busqueda = $_REQUEST["caja_busqueda"];
$publicando_grupo = "";
if($modo == 1){ /*Colaboradores de la experiencia*/
    $_colaboradores = dbAdminObtenerColaboradoresExperiencia($codeexp, $conexion);
                             
    if(!is_null($_colaboradores)){
        ?>
    <div class="admin_exp_estudiantes"><?php echo $lang_admin_colaboradores_exp; ?></div></br>
    <div class="admin_usuario_tabla">
        <table class="t_admin_colaboradores">
            <thead>
                <tr>
                    <td class="utabla_col_nombre"><?php echo $lang_admin_nombre; ?></td>
                </tr>
            </thead>
            <tbody>    
        <?php
        foreach($_colaboradores as $colaborador){
            $imagen_colaborador = darFormatoImagen($colaborador["imagen"], $config_ruta_img_perfil, $config_ruta_img)
        ?>
            <tr>
                <td>
                    <a  class="link_imagen_1_" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>"><img class="admin_avatar" src="<?php echo $imagen_colaborador["imagen_usuario"]; ?>"/></a>
                    <a  class="admin_nombre_profesor link_nombre_1_" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>" class ="link_perfil"><?php echo ucwords($colaborador["nombre"]); ?></a>
                    <button  class="admin_eliminar_colaborador" onclick="javascript:adminEliminarColaborador(<?php echo $colaborador["id_usuario"];?>,'<?php echo $colaborador["nombre"];?>');"></button>
                </td>
            </tr>
       <?php 
        }
        ?>
            </tbody>
        </table>
    </div>
        <?php
    }
    else{
        echo "<div class=\"admin_exp_estudiantes\">".$lang_admin_no_colaboradores."</br></div>";
    }
    ?>
    <button  class="admin_agregar_colaborador" id="agregar_colaborador" onclick="javascript:adminExpMostrarPosiblesColaboradores();"><?php echo $lang_admin_agregar_colaborador; ?></button>
    <div class="clear"></div>
<?php
}
else{
    if($modo == 2){
        if(!is_null($_REQUEST["grupos"])){
            $publicando_grupo = $_REQUEST["grupo"];
            $limite_inferior = (($publicando_grupo - 1)*10);     
            $_posibles_colaboradores = dbAdminObtenerPosiblesColaboradores($limite_inferior,$codeexp, $conexion);
            ?>
            <table class="t_admin_posibles_colaboradores">
                <tbody>
            <?php
        }
        else{
            $num_total_usuarios = dbAdminObtenerNumeroPosiblesColaboradoresExperiencia($codeexp, $conexion);
            $grupos = intval($num_total_usuarios/10);
            $resto = $num_total_usuarios%10;
            if($resto > 0){
                $grupos++;
            }
            $publicando_grupo = 1;
            $_posibles_colaboradores =dbAdminObtenerPosiblesColaboradores(0, $codeexp, $conexion);
            ?>
            <p> <?php echo $lang_admin_posibles_colaboradores; ?></p>
            </br>
            <div class="admin_exp_bloque_busqueda_colaborador">
                <input id="admin_exp_buscar_colaborador" class="admin_exp_busqueda_colaborador" type="button" value="<?php echo $lang_admin_buscar_colaborador; ?>" >
            </div>
            <div class="clear"></div>
            <form id="form_admin_busqueda_colaborador" method="post" action="">
                <div id="caja_form_busqueda_colaborador">
                    <label><?php echo $lang_registro_nombre." :";?></label>
                    <input tabindex="1" type="text" maxlenght="15" size="15" id="fr_admin_bus_ncolaborador" name="fr_admin_bus_ncolaborador"/>
                    </br>
                    <label  class="sugerencia" id="admin_suge_ncolaborador"><?php echo $lang_admin_solo_un_nombre; ?></label>
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_apellido." :";?></label>
                    <input tabindex ="2" type="text" maxlenght="15" size="15" id="fr_admin_bus_acolaborador" name="fr_admin_bus_acolaborador" />
                    </br>
                    <label class="sugerencia" id="admin_suge_acolaborador"><?php echo $lang_admin_solo_un_apellido; ?></label>
                    <div class="clear"></div>
                    <input class="submit" type="submit" value="<?php echo $lang_admin_buscar; ?>">
                </div>
            </form>
            <table class="t_admin_posibles_colaboradores">
                <thead>
                    <tr>
                        <td class="col1_posibles_colaboradores"><?php echo $lang_admin_nombre; ?></td>
                    </tr>
                </thead>
                <tbody>    
            <?php

        }
    }
    if($modo == 3){ 
        $nombre = $_REQUEST["nombre"];
        $apellido = $_REQUEST["apellido"];
        $_posibles_colaboradores = dbAdminBusquedaColaboradores($nombre, $apellido,$codeexp, $conexion);
        if(!is_null($_posibles_colaboradores)){
            ?><p> <?php echo $lang_admin_resultados_busqueda; ?></p><?php
        }
        else{
            ?><p> <?php echo $lang_admin_no_resultados; ?></p><?php
        }
        ?>
        <div class="admin_exp_bloque_busqueda_colaborador">
            <input id="admin_exp_volver_colaborador" class="admin_exp_busqueda_colaborador" type="button" value="<?php echo $lang_admin_volver_lista_completa; ?>">
        </div>
        </br>
        <?php
        if(!is_null($_posibles_colaboradores)){
        ?>
        <table class="t_admin_posibles_colaboradores">
            <thead>
                <tr>
                    <td class="col1_posibles_colaboradores"><?php echo $lang_admin_nombre; ?></td>
                </tr>
            </thead>
            <tbody>    
        <?php
        }
    }
    if(!is_null($_posibles_colaboradores)){
//        print_r($_posibles_colaboradores);
        foreach($_posibles_colaboradores as $colaborador ){
            $imagen_colaborador = darFormatoImagen($colaborador["imagen"], $config_ruta_img_perfil, $config_ruta_img)
            ?>
            <tr>
                <td class="col1_posibles_colaboradores">
                    <a class ="nombre_profesor_exp_todas link_imagen_<?php echo $modo."_".$publicando_grupo;?>" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>" ><img class="admin_avatar" src="<?php echo $imagen_colaborador["imagen_usuario"]; ?>" onclick="javascript:verPerfilModal(<?php echo $colaborador["usuario"]; ?>);"/></a>
                    <a  class="admin_nombre_profesor link_nombre_<?php echo $modo."_".$publicando_grupo;?>" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>" class ="link_perfil"><?php echo ucwords($colaborador["nombre"]); ?></a>
                    <button class="admin_agregar_co " id="admin_agregar_<?php echo $colaborador["id_usuario"];?>" onclick="javascript:adminAgregarColaborador(<?php echo $colaborador["id_usuario"];?>);"><?php echo $lang_admin_agregar_como_colaborador; ?></button>
                </td>
            </tr>
           <?php
        }
        ?>
        </tbody>
    </table>
        <?php
        if($publicando_grupo < $grupos && $modo!=3){
        ?>
                <div class ="admin_colaborador_ver_mas" id="colaboradores_vermas_<?php echo $publicando_grupo+1;?>">
                    <button class="admin_colaborador_ver_mas_boton" onclick="javascript: adminColaboradoresVerMas(<?php echo $grupos;?>,<?php echo $publicando_grupo+1;?>);"><?php echo $lang_admin_ver_mas; ?></button>
                </div>
        <?php
        }   
    }
}
dbDesconectarMySQL($conexion); 
?>
<script type="text/javascript">

    function adminColaboradoresVerMas(grupos, grupo){
        $.get('admin/admin_exp_listado_colaboradores.php?modo=2&codeexp=<?php echo $codeexp;?>&grupos='+grupos+'&grupo='+grupo, function(data) { 
            $('#colaboradores_vermas_'+grupo).html(data);
        });
    }
    
    function adminEliminarColaborador(id_usuario, nombre){
        var contenido = '<p><?php echo $lang_admin_seguro_eliminar; ?> '+nombre+' <?php echo $lang_admin_como_colaborador; ?> </p>';
        var $dialog = $('<div id=\"dialogo_eliminar_colaborador\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_eliminar_colaborador; ?>',
            width: 600,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesColaboradorEliminar',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_si; ?>": function() {
                    url = 'admin/admin_exp_colaboradores.php?id_usuario='+id_usuario+'&accion=1'+'&id_exp=<?php echo $codeexp;?>';
                    $.post(url,  function(data) {
                        if(data==1){
                            $('#dialogo_eliminar_colaborador').html('<p>'+nombre+' <?php echo $lang_admin_no_colaborador_exp; ?></p>');
                            $("div.dialogBotonesColaboradorEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesColaboradorEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesColaboradorEliminar div button:nth-child(3)").show();
                            cargarColaboradores();                    
                            
                        }else{
                            $('#dialogo_eliminar_colaborador').html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
                            $("div.dialogBotonesColaboradorEliminar div button:nth-child(1)").hide();
                            $("div.dialogBotonesColaboradorEliminar div button:nth-child(2)").hide();
                            $("div.dialogBotonesColaboradorEliminar button:nth-child(3)").show();
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
        $("div.dialogBotonesColaboradorEliminar div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }
    function adminExpMostrarPosiblesColaboradores(){
        var $dialog = $('<div id=\"admin_ventana_colaboradores\"></div>')
        .load('admin/admin_exp_listado_colaboradores.php?modo=2'+'&codeexp=<?php echo $codeexp;?>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_agregar_colaborador; ?>',
            width: 600,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesColaboradorEliminar',
            close: function() {
                $(this).remove();
            }
            });
        $dialog.dialog('open');
    }
    function volverPosiblesColaboradores(){
        url = 'admin/admin_exp_listado_colaboradores.php?modo=2'+'&codeexp=<?php echo $codeexp;?>';
        $.post(url,  function(data) {
            if(data){
                $('#admin_ventana_colaboradores').html(data);
            }
            else{
                
            }   
        });
    }
    function adminAgregarColaborador(id_usuario){
        var url = 'admin/admin_exp_colaboradores.php?accion=2&id_usuario='+id_usuario+'&id_exp=<?php echo $codeexp;?>';
        $.post(url,  function(data) {
            if(data==1){
                $('#admin_agregar_'+id_usuario).hide();
//                $('#admin_agregado_'+id_usuario).show();
                cargarColaboradores();
            }
            else{
                
            }   
        });
    }
   
    $(document).ready(function(){
        $('.admin_agregado').hide();
        $('.sugerencia').hide();
        $("#form_admin_busqueda_colaborador").hide();
        $("#admin_exp_buscar_colaborador").click(function() {
            $("#form_admin_busqueda_colaborador").slideDown(400);
        });
//        $(".admin_agregar_colaborador").click(function() {
//
//            var $dialog = $('<div id=\"admin_ventana_colaboradores\"></div>')
//            .load('admin/admin_exp_listado_colaboradores.php?modo=2'+'&codeexp=<?php echo $codeexp;?>')
//            .dialog({
//                autoOpen: false,
//                title: '<?php echo $lang_admin_agregar_colaborador; ?>',
//                width: 600,
//                modal: true,
//                resizable: true,
//                dialogClass:'dialogBotonesColaboradorEliminar',
//
//                close: function() {
//                    $(this).remove();
//                }
//            });
//            $dialog.dialog('open');
//            
//        });
        
        $("#form_admin_busqueda_colaborador").validate({
            rules:{
                fr_admin_bus_ncolaborador:{
                    required:"#fr_admin_bus_acolaborador:blank",
                    minlength:3
                },
                fr_admin_bus_acolaborador:{
                    required:"#fr_admin_bus_ncolaborador:blank",
                    minlength:3
                }
            },
            messages:{
                
                fr_admin_bus_ncolaborador: {
                    required:"<?php echo $lang_admin_minimo_uno; ?>",
                    minlength:"<?php echo $lang_admin_largo_minimo_nombre; ?>"
                },
                fr_admin_bus_acolaborador:{
                    required:"<?php echo $lang_admin_minimo_uno; ?>",
                    minlength:"<?php echo $lang_admin_largo_minimo_apellido; ?>"
                }
            },
            submitHandler: function() {
                var nombre = $('#fr_admin_bus_ncolaborador').val();
                var apellido = $('#fr_admin_bus_acolaborador').val();
                var url = 'admin/admin_exp_listado_colaboradores.php?modo=3&codeexp=<?php echo $codeexp;?>&nombre='+nombre;
                if(apellido != null){
                    url= url + '&apellido='+apellido;
                }
                $.post(url, function(data) {
                    $('#admin_ventana_colaboradores').html(data);

                });
            }
        });

        $("#fr_admin_bus_ncolaborador").focus(function() {
            $("#admin_suge_ncolaborador").show();
        });
        $("#fr_admin_bus_acolaborador").focus(function() {
            $("#admin_suge_acolaborador").show();
        });
        $("#fr_admin_bus_ncolaborador").blur(function() {
            $("#admin_suge_ncolaborador").hide();
        });
         $("#fr_admin_bus_acolaborador").blur(function() {
            $("#admin_suge_acolaborador").hide();
        });;
        $('.link_nombre_<?php echo $modo."_".$publicando_grupo;?>').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_admin_perfil_usuario; ?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_admin_cerrar; ?>": function() {
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
       $('.link_imagen_<?php echo $modo."_".$publicando_grupo;?>').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_admin_perfil_usuario; ?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_admin_cerrar; ?>": function() {
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
</script>


