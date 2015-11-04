<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */ 
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$codeexp = $_REQUEST["codeexp"];
$modo = $_REQUEST["modo"];
$nombre_busqueda = $_REQUEST["caja_busqueda"];
$_solicitudes = dbAdminConsultarSolicitudesColaboradoresExperiencia($codeexp, $conexion);
$datos_experiencia = dbExpObtenerInfo($codeexp, $conexion);
$esta_finalizada    = ($datos_experiencia["fecha_termino"] != '')?"1":"0";

if($modo == 1){ /*Colaboradores de la experiencia*/
    $_colaboradores = dbAdminObtenerColaboradoresExperiencia($codeexp, $conexion);
                             
    if(!is_null($_colaboradores)){
        ?>
    <div id="administrador_colaboradores_listado">
    <p class="intro_etapas"><?php echo $lang_admin_ec_colab_exp; ?></p></br>
    <div class="admin_usuario_tabla">
        <table class="t_admin_colaboradores">
            <thead>
                <tr>
                    <td class="utabla_col_nombre"><?php echo $lang_admin_ec_nombre; ?></td>
                </tr>
            </thead>
            <tbody>    
        <?php
        foreach($_colaboradores as $colaborador){
            $imagen_colaborador = darFormatoImagen($colaborador["imagen"], $config_ruta_img_perfil, $config_ruta_img)
        ?>
            <tr>
                <td>
                    <a class="link_img_col" href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>"><img class="admin_avatar" src="<?php echo $imagen_colaborador["imagen_usuario"]; ?>"/></a>
                    <a class="admin_col_nombre_profesor link_perfil"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>" ><?php echo ucwords($colaborador["nombre"]); ?></a>
                    <?php
                    $cont_solicitud = 0;
                    if(!is_null($_solicitudes)){
                        foreach ($_solicitudes as $solicitudes){
                            if($solicitudes["id_colaborador"]== $colaborador["id_usuario"]  ){
                                if($solicitudes["estado"]==0){
                                    $cont_solicitud = 1;
                                }
                                
                            }
                        }
                    }
                    if($cont_solicitud == 1){
                        ?><button   class="admin_exp_eliminar_colaborador_pendiente" disabled="disabled"><?php echo $lang_admin_ec_solicitud_pendiente; ?></button><?php
                    }
                    else{
                        ?><button id="admin_enviar_sol_<?php echo $colaborador["id_usuario"];?>" class="admin_exp_eliminar_colaborador" onclick="javascript:adminSolicitarEliminarColaborador(<?php echo $colaborador["id_usuario"];?>,'<?php echo $colaborador["nombre"];?>');"><?php echo $lang_admin_ec_solicitud_eliminar; ?></button><?php
                    }
                    
                    ?>
                    
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
        echo "<p class=\"intro_etapas\">".$lang_admin_ec_exp_sin_colab."</p></br>";
    }
    ?>
    <button class="admin_exp_agregar_colaborador" id="admin_agregar_colaborador" onclick="javascript:adminMostrarPosiblesColaboradores();"><?php echo $lang_admin_ec_sol_agregar_colab; ?></button>
    <div class="clear"></div>
    </div>
    <div id="admin_solicitudes_exp_ver">
    <?php
    if(!is_null($_solicitudes) && count($_solicitudes)>0){
        ?>
        <p class="intro_etapas"><?php echo $lang_admin_ec_ultimas_solicitudes; ?>: </p>
        <?php
        foreach ($_solicitudes as $solicitud){
            if($solicitud["accion"]==0){
                $accion = $lang_admin_ec_borrar;
            }
            else{
                $accion = $lang_admin_ec_agregar;
            }
            switch ($solicitud["estado"]){
                case 0:
                    $estado = $lang_admin_ec_pendiente;
                    $clase_estado ="pendiente";
                    break;
                case 1:
                    $estado = $lang_admin_ec_aceptada;
                    $clase_estado ="aceptada";
                    break;
                case 2:
                    $estado = $lang_admin_ec_rechazada;
                    $clase_estado ="rechazada";
                    break;
            }
        ?>
            <div id="admin_bloque_solicitud_exp" class="admin_bloque_solicitud">
                <p>
                    <span class="admin_accion"><?php echo "- ".$accion;?></span> <?php echo " ".$lang_admin_ec_a." ";?> <span class="admin_colaborador"><?php echo$solicitud["nombre_colaborador"];?><span class="admin_estado_<?php echo $clase_estado;?>"><?php echo " -- ".$estado." --";?></span>
                </p>
            </div>
        <?php
        }
    }
    ?>
    </div>

<?php
}
else{
    if($modo == 2){
        if(!is_null($_REQUEST["grupos"])){
            $publicando_grupo = $_REQUEST["grupo"];
            $limite_inferior = (($publicando_grupo - 1)*10);     
            $_posibles_colaboradores = dbAdminObtenerPosiblesColaboradoresSolicitud($limite_inferior,$codeexp, $conexion);
            ?>
            <table class="t_admin_posibles_colaboradores">
                <tbody>
            <?php
        }
        else{
            $num_total_usuarios = dbAdminObtenerNumeroPosiblesColaboradoresExperienciaSolicitud($codeexp, $conexion);
            $grupos = intval($num_total_usuarios/10);
            $resto = $num_total_usuarios%10;
            if($resto > 0){
                $grupos++;
            }
            $publicando_grupo = 1;
            $_posibles_colaboradores =dbAdminObtenerPosiblesColaboradoresSolicitud(0, $codeexp, $conexion);
            ?>
            <p><?php echo $lang_admin_ec_lista_colab; ?>: </p>
            </br>
            <div class="admin_exp_bloque_busqueda_colaborador">
                <input id="admin_exp_buscar_colaborador" class="admin_exp_busqueda_colaborador" type="button" value="<?php echo $lang_admin_ec_buscar_colab; ?>" >
            </div>
            <div class="clear"></div>
            <form id="form_admin_exp_busqueda_colaborador" method="post" action="">
                <div id="caja_form_busqueda_colaborador">
                    <label><?php echo $lang_registro_nombre." :";?></label>
                    <input tabindex="1" type="text" maxlenght="15" size="15" id="fr_admin_exp_bus_ncolaborador" name="fr_admin_exp_bus_ncolaborador"/>
                    </br>
                    <label  class="sugerencia" id="admin_suge_ncolaborador"><?php echo $lang_admin_ec_solo_nombre;?></label>
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_apellido." :";?></label>
                    <input tabindex ="2" type="text" maxlenght="15" size="15" id="fr_admin_exp_bus_acolaborador" name="fr_admin_exp_bus_acolaborador"/>
                    </br>
                    <label class="sugerencia" id="admin_suge_acolaborador"><?php echo $lang_admin_ec_solo_apellido;?></label>
                    <div class="clear"></div>
                    <input class="submit" type="submit" value="<?php echo $lang_admin_ec_buscar;?>">
                </div>
            </form>
            <table class="t_admin_posibles_colaboradores">
                <thead>
                    <tr>
                        <td class="col1_posibles_colaboradores"><?php echo $lang_admin_ec_nombre; ?></td>
                    </tr>
                </thead>
                <tbody>    
            <?php

        }
    }
    if($modo == 3){ 
        $nombre = $_REQUEST["nombre"];
        $apellido = $_REQUEST["apellido"];
        $busqueda = "busqueda";
        $_posibles_colaboradores = dbAdminBusquedaColaboradores($nombre, $apellido,$codeexp, $conexion);
        if(!is_null($_posibles_colaboradores)){
            ?><p> <?php echo $lang_admin_ec_resultado_busqueda; ?>: </p><?php
        }
        else{
            ?><p> <?php echo $lang_admin_ec_sin_resultados; ?></p><?php
        }
        ?>
        <div class="admin_exp_bloque_busqueda_colaborador">
            <input id="admin_exp_volver_colaborador" class="admin_exp_busqueda_colaborador" type="button" value="<?php echo $lang_admin_ec_volver_lista_completa; ?>">
        </div>
        </br>
        <?php
        if(!is_null($_posibles_colaboradores)){
        ?>
        <table class="t_admin_posibles_colaboradores">
            <thead>
                <tr>
                    <td class="col1_posibles_colaboradores"><?php echo $lang_admin_ec_nombre; ?></td>
                </tr>
            </thead>
            <tbody>    
        <?php
        }
    }
    if(!is_null($_posibles_colaboradores)){
        foreach($_posibles_colaboradores as $colaborador ){
            $imagen_colaborador = darFormatoImagen($colaborador["imagen"], $config_ruta_img_perfil, $config_ruta_img)
            ?>
            <tr>
                <td class="col1_posibles_colaboradores">
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>" class ="nombre_profesor_exp_todas admin_col_nombre_profesor_<?php echo $publicando_grupo;?>"><img class="admin_avatar_posibles" src="<?php echo $imagen_colaborador["imagen_usuario"]; ?>"/></a>
                    <a class="admin_col_sugerencia admin_col_nombre_profesor_<?php echo $publicando_grupo;?>"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $colaborador["usuario"]; ?>" alt="<?php echo $colaborador["nombre"]; ?>" title="<?php echo $colaborador["nombre"]; ?>" class ="link_perfil"><?php echo ucwords($colaborador["nombre"]); ?></a>
                    <button class="admin_agregar_co " id="admin_agregar_<?php echo $colaborador["id_usuario"];?>" onclick="javascript:adminSolicitarAgregarColaborador(<?php echo $colaborador["id_usuario"];?>);"><?php echo $lang_admin_ec_solicitar_agregar; ?></button>
                    <p class="admin_agregar_resp" id="admin_agregar_resp_<?php echo $colaborador["id_usuario"];?>"><?php echo $lang_admin_ec_sol_evianda_exitosa; ?></p>
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
                    <button class="admin_colaborador_ver_mas_boton" onclick="javascript: adminColaboradoresVerMas(<?php echo $grupos;?>,<?php echo $publicando_grupo+1;?>);"><?php echo $lang_admin_ec_ver_mas; ?></button>
                </div>
        <?php
        }   
    }
}
dbDesconectarMySQL($conexion); 
?>


<script type="text/javascript">

    function adminColaboradoresVerMas(grupos, grupo){
        $.get('admin_exp_colaboradores.php?modo=2&codeexp=<?php echo $codeexp;?>&grupos='+grupos+'&grupo='+grupo, function(data) { 
            $('#colaboradores_vermas_'+grupo).html(data);
        });
    }
    function cargarColaboradores(){
        $.get('admin_exp_colaboradores.php?modo=1&codeexp=<?php echo $codeexp;?>', function(data) {                  
          $('#administrador_colaboradores').html(data);
        });
    }
    function adminSolicitarEliminarColaborador(id_usuario, nombre){
        var contenido = '<p><?php echo $lang_admin_ec_seguro_enviar_solicitud; ?> '+nombre+' <?php echo $lang_admin_ec_como_colab_exp; ?> </p>';
        var $dialog = $('<div id=\"dialogo_eliminar_colaborador\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_ec_solicitud_eliminar_colab; ?>',
            width: 600,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesSolicitarEliminarColaborador',
            buttons: {
                //ACEPTAR
                "<?php echo $lang_admin_ec_si; ?>": function() {
                    url = 'admin_exp_colaboradores_editar.php?id_usuario='+id_usuario+'&accion=1'+'&id_exp=<?php echo $codeexp;?>';
                    $.post(url,  function(data) {
                        if(data==1){
                            $('#dialogo_eliminar_colaborador').html('<p><?php echo $lang_admin_ec_solicitud_enviada; ?></p>');
                            $("div.dialogBotonesSolicitarEliminarColaborador div button:nth-child(1)").hide();
                            $("div.dialogBotonesSolicitarEliminarColaborador div button:nth-child(2)").hide();
                            $("div.dialogBotonesSolicitarEliminarColaborador div button:nth-child(3)").show();
                            cargarColaboradores();                    
                            
                        }else{
                            $('#dialogo_eliminar_colaborador').html("<p><?php echo $lang_admin_ec_problema; ?></p>");
                            $("div.dialogBotonesSolicitarEliminarColaborador div button:nth-child(1)").hide();
                            $("div.dialogBotonesSolicitarEliminarColaborador div button:nth-child(2)").hide();
                            $("div.dialogBotonesSolicitarEliminarColaborador button:nth-child(3)").show();
                        }
                        return false;
                    });
                },
                //CANCELAR
                "<?php echo $lang_admin_ec_nombre; ?>": function() {
                    $(this).dialog('destroy').remove();
                },
                //CERRAR
                "<?php echo $lang_admin_ec_cerrar; ?>": function() {
                    $(this).dialog('destroy').remove();
                }
            },
            close: function() {
            }
            });
        $("div.dialogBotonesSolicitarEliminarColaborador div button:nth-child(3)").hide();
        $dialog.dialog('open');
    }
    function adminMostrarPosiblesColaboradores(){
        var $dialog = $('<div id=\"admin_exp_ventana_colaboradores\"></div>')
        .load('admin_exp_colaboradores.php?modo=2'+'&codeexp=<?php echo $codeexp;?>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_ec_agregar_colab; ?>',
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
        url = 'admin_exp_colaboradores.php?modo=2'+'&codeexp=<?php echo $codeexp;?>';
        $.post(url,  function(data) {
            if(data){
                $('#admin_exp_ventana_colaboradores').html(data);
            }
            else{
                
            }   
        });
    }
    function adminSolicitarAgregarColaborador(id_usuario){
        var url = 'admin_exp_colaboradores_editar.php?accion=2&id_usuario='+id_usuario+'&id_exp=<?php echo $codeexp;?>';
        $.post(url,  function(data) {
            if(data==1){
                var $dialog = $('<div class=\"admin_exp_enviar_sol\"><p><?php echo $lang_admin_ec_sol_evianda_exitosa; ?><p></div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_admin_ec_sol_agregar_colab;?>',
                    width: 600,
                    height: 150,
                    modal: true,
                    buttons: {
                        '<?php echo $lang_admin_ec_cerrar; ?>': function() {
                        $(this).dialog("close");
                        }
                    },
                    close: function(ev, ui) {
                        $(this).remove();
                    }
                });
                $dialog.dialog('open');
                $('#admin_agregar_'+id_usuario).hide();
                cargarColaboradores();
            }
            else{
                alert("<?php echo $lang_admin_ec_problema; ?>");
            }   
        });
    }
    $(document).ready(function(){
        $('.admin_agregado').hide();
        $('.sugerencia').hide();
        $('.admin_agregar_resp').hide();
        $("#form_admin_exp_busqueda_colaborador").hide();
        <?php
        if($esta_finalizada){?>
            $(".admin_exp_eliminar_colaborador").attr('disabled', true);
            $(".admin_exp_agregar_colaborador").attr('disabled', true);
            $(".admin_exp_eliminar_colaborador").addClass("admin_desactivado");
            $(".admin_exp_agregar_colaborador").addClass("admin_desactivado");
            $(".admin_exp_eliminar_colaborador").attr('title', '<?php echo $lang_admin_ec_btn_desactivado; ?>');
            $(".admin_exp_agregar_colaborador").attr('title', '<?php echo $lang_admin_ec_btn_desactivado; ?>');
            
        <?php
        }
        ?>
        $("#admin_exp_buscar_colaborador").click(function() {
            $("#form_admin_exp_busqueda_colaborador").show();
        });
        $("#admin_exp_volver_colaborador").click(function() {
            $('#admin_exp_ventana_colaboradores').load('admin_exp_colaboradores.php?modo=2'+'&codeexp=<?php echo $codeexp;?>');
        });
        
        $("#form_admin_exp_busqueda_colaborador").validate({
            rules:{
                fr_admin_exp_bus_ncolaborador:{
                    required:"#fr_admin_exp_bus_acolaborador:blank",
                    minlength:3
                },
                fr_admin_exp_bus_acolaborador:{
                    required:"#fr_admin_exp_bus_ncolaborador:blank",
                    minlength:3
                }
            },
            messages:{
                
                fr_admin_exp_bus_ncolaborador: {
                    required:"<?php echo $lang_admin_ec_dos_campos;?>",
                    minlength:"<?php echo $lang_admin_ec_nombre_superior;?>"
                },
                fr_admin_exp_bus_acolaborador:{
                    required:"<?php echo $lang_admin_ec_dos_campos;?>",
                    minlength:"<?php echo $lang_admin_ec_apellido_superior;?>"
                }
            },
            submitHandler: function() {
                var nombre = $('#fr_admin_exp_bus_ncolaborador').val();
                var apellido = $('#fr_admin_exp_bus_acolaborador').val();
                url = 'admin_exp_colaboradores.php?modo=3&codeexp=<?php echo $codeexp;?>&nombre='+nombre;
                if(apellido != null){
                    url= url + "&apellido="+apellido;
                }
                $.post(url, function(data) {
                    $('#admin_exp_ventana_colaboradores').html(data);

                });
            }
        });

        $("#fr_admin_exp_bus_ncolaborador").focus(function() {
            $("#admin_suge_ncolaborador").show();
        });
        $("#fr_admin_exp_bus_acolaborador").focus(function() {
            $("#admin_suge_acolaborador").show();
        });
        $("#fr_admin_exp_bus_ncolaborador").blur(function() {
            $("#admin_suge_ncolaborador").hide();
        });
         $("#fr_admin_exp_bus_acolaborador").blur(function() {
            $("#admin_suge_acolaborador").hide();
        });;
        $('.admin_col_nombre_profesor_<?php echo $publicando_grupo;?>').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_admin_ec_perfil_usuario;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    '<?php echo $lang_admin_ec_cerrar; ?>': function() {
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
       $('.admin_col_nombre_profesor').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_admin_ec_perfil_usuario;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    '<?php echo $lang_admin_ec_cerrar; ?>': function() {
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
       $('.link_img_col').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_admin_ec_perfil_usuario;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    '<?php echo $lang_admin_ec_cerrar; ?>': function() {
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