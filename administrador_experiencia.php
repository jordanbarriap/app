<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor. 
 */
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_experiencia = $_REQUEST["codeexp"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$admin_exp_datos_experiencia = dbAdminExpObtenerInfo($id_experiencia, $conexion);
$_imagenes = darFormatoImagen($datos_experiencia["url_avatar_profesor"], $config_ruta_img_perfil, $config_ruta_img);
//Obtiene etiqueta de la experiencia
$etiqueta_experiencia = dbObtenerEtiqExpDidac($id_experiencia, $conexion);
$datos_experiencia = dbExpObtenerInfo($id_experiencia, $conexion);
$esta_finalizada    = ($datos_experiencia["fecha_termino"] != '')?"1":"0";
dbDesconectarMySQL($conexion);
?>
<div class="contenido_config">
    
    <div class="admin_exp_titulo_pasos admin_exp_cerrado" id="admin_general">
        <img id="img_a" src="img/flecha_c.png"></img>
        <?php echo $lang_adm_exp_admin_general; ?>
    </div>
    <div class="admin_exp_titulo_pasos admin_exp_abierto" id="admin_general_a" name="admin_general">
        <img id="img_a" src="img/flecha_a.png"></img>
        <?php echo $lang_adm_exp_admin_general; ?>
    </div>
    <div id="admin_general_contenido" class="admin_exp_contenido">
        <p class="intro_etapas"><?php echo $lang_adm_exp_modificar_info; ?></p>
        </br>
        <form id ="fr_admin_exp_localidad" class="form_admin_exp_general" method="post" action="">
            <label><?php echo $lang_adm_exp_localidad." :";?></label>
            <input disabled="disabled" tabindex="1" type="text" maxlenght="120" size="120" class ="admin_exp_localidad" id="admin_exp_campo_localidad"  name="admin_exp_campo_localidad" value="<?php echo $admin_exp_datos_experiencia["localidad"];?>" />
            <button type="button" class ="admin_exp_editar admin_experiencia" id="admin_exp_boton_localidad" name="admin_exp_campo_localidad" ></button>
            <button type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_localidad_enviar" name="admin_exp_boton_localidad" value=""></button>
        </form>
        </br>
        <form id ="fr_admin_exp_curso" class="form_admin_exp_general" method="post" action="">
            <label><?php echo $lang_adm_exp_curso." :";?></label>
            <input disabled="disabled" tabindex="2" type="text" maxlenght="120" size="120" class ="admin_exp_curso" id="admin_exp_campo_curso"  name="admin_exp_campo_curso" value="<?php echo $admin_exp_datos_experiencia["curso"];?>" />
            <input type="button" class ="admin_exp_editar admin_experiencia" id ="admin_exp_boton_curso" name="admin_exp_campo_curso"></input>
            <input type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_curso_enviar" name="admin_exp_boton_curso" value="">
        </form>
        </br>
        <form id ="fr_admin_exp_establecimiento" class="form_admin_exp_general"method="post" action="">
            <label><?php echo $lang_adm_exp_establecimiento_educacional." :";?></label>
            <input disabled="disabled" tabindex="3" type="text" maxlenght="120" size="120" class="admin_exp_establecimiento" id="admin_exp_campo_colegio"  name="admin_exp_campo_colegio" value="<?php echo $admin_exp_datos_experiencia["colegio"];?>" />
            <input type="button" class ="admin_exp_editar admin_experiencia" id ="admin_exp_boton_establecimiento" name="admin_exp_campo_colegio"></input>
            <input type="submit" class="admin_experiencia admin_exp_guardar" id="admin_exp_boton_establecimiento_enviar" name="admin_exp_boton_establecimiento" value="">

        </form>
    </div>
</div>
<div class="contenido_config">
    <div class="admin_exp_titulo_pasos admin_exp_cerrado" id="admin_estudiantes">
        <img id="img_a" src="img/flecha_c.png"></img>
        <?php echo $lang_adm_exp_admin_estudiantes; ?>
    </div>
    <div class="admin_exp_titulo_pasos admin_exp_abierto" id="admin_estudiantes_a" name="admin_estudiantes">
        <img id="img_a" src="img/flecha_a.png"></img>
        <?php echo $lang_adm_exp_admin_estudiantes; ?>
    </div>
    <div id="admin_estudiantes_contenido" class="admin_exp_contenido">
        <p class="intro_etapas"><?php echo $lang_adm_exp_invitar_estudiantes; ?>
            <span id="etiqueta_experiencia"><?php echo $etiqueta_experiencia; ?></span>
            <br>(<?php echo $lang_adm_exp_ejemplo_codigo; ?>)
        </p>
        <div id="administrador_estudiantes">
        </div>
    </div>
    
</div>

<div class="contenido_config">
    <div class="admin_exp_titulo_pasos admin_exp_cerrado" id="admin_grupos">
        <img id="img_a" src="img/flecha_c.png"></img>
        <?php echo $lang_adm_exp_admin_grupos; ?>
    </div>
    <div class="admin_exp_titulo_pasos admin_exp_abierto" id="admin_grupos_a" name="admin_grupos">
        <img id="img_a" src="img/flecha_a.png"></img>
        <?php echo $lang_adm_exp_admin_grupos; ?>
    </div>
    <div id="admin_grupos_contenido" class="admin_exp_contenido">
        <p class="intro_etapas">
            <?php echo $lang_adm_exp_definir_cantidad_grupos; ?>
            <?php echo $lang_adm_exp_modificar_cantidad_grupos; ?>
        </p>
        <div id="administrador_grupos">
            
        </div>
    </div>
</div>
    

<div class="contenido_config">
    <div class="admin_exp_titulo_pasos admin_exp_cerrado" id="admin_colaboradores">
        <img id="img_a" src="img/flecha_c.png"></img>
        <?php echo $lang_adm_exp_admin_colab; ?>
    </div>
    <div class="admin_exp_titulo_pasos admin_exp_abierto" id="admin_colaboradores_a" name="admin_colaboradores">
        <img id="img_a" src="img/flecha_a.png"></img>
        <?php echo $lang_adm_exp_admin_colab; ?>
    </div>
    <div id="admin_colaboradores_contenido" class="admin_exp_contenido">
        <div id="admin_colaboradores_solicitudes">
            <div id="administrador_colaboradores"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function adminProfCargarAdminEstudiantes(){
        $.get('admin_exp_estudiantes.php?codeexp=<?php echo $id_experiencia; ?>', function(data) {                  
          $('#administrador_estudiantes').html(data);
        });
    }
    function adminProfCargarAdminColaboradores(){
        $.get('admin_exp_colaboradores.php?codeexp=<?php echo $id_experiencia; ?>&modo=1', function(data) { 
          $('#administrador_colaboradores').html(data);
        });
    }
    function adminProfCargarAdminGrupos(){
        $.get('configurar_diseno.php?codexpi=<?php echo $id_experiencia; ?>', function(data) { 
          $('#administrador_grupos').html(data);
        });
    }

    String.prototype.removeAccents = function ()
    {
        var __r = {
                        'À':'A','Á':'A','Â':'A','Ã':'A','Ä':'A','Å':'A','Æ':'E',
                        'È':'E','É':'E','Ê':'E','Ë':'E',
                        'Ì':'I','Í':'I','Î':'I',
                        'Ò':'O','Ó':'O','Ô':'O','Ö':'O',
                        'Ù':'U','Ú':'U','Û':'U','Ü':'U',
                        'Ñ':'N'};

        return this.replace(/[ÀÁÂÃÄÅÆÈÉÊËÌÍÎÒÓÔÖÙÚÛÜÑ]/gi, function(m){
                var ret = __r[m.toUpperCase()];
                if (m === m.toLowerCase())
                        ret = ret.toLowerCase();
                return ret;
        });
    };
    $(document).ready(function(){
        var campo_modificar;
        adminProfCargarAdminEstudiantes();
        adminProfCargarAdminColaboradores();
        adminProfCargarAdminGrupos();
        detenerBitacoraNM();
        $(".admin_exp_abierto").hide();
        $('.admin_exp_guardar').hide();
        $(".admin_exp_contenido").hide();
        <?php
        if($esta_finalizada){?>
            $(".admin_exp_editar").attr('disabled', true);
            $(".admin_exp_editar").addClass("admin_desactivado");
            $(".admin_exp_editar").attr('title', '<?php echo $lang_adm_exp_btn_desactivado; ?>');
        <?php
        }
        ?>
        
        $(".admin_exp_cerrado").click(function() {
            var element = $(this);
            var I = element.attr("id");
            $(".admin_exp_abierto").hide();
            $(".admin_exp_cerrado").show();
            $(".admin_exp_contenido").hide();
            $("#"+I+"_contenido").slideDown(400);
            $("#"+I).hide();
            $("#"+I+"_a").show();
            
        });
        $(".admin_exp_abierto").click(function() {
            var element = $(this);
            var I = element.attr("name");
            $("#"+I+"_contenido").hide();
            $("#"+I+"_a").hide();
            $("#"+I).show();
        });

        
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
        $("#num_grupos").spinner({max: 25, min: 1});

        $("#modal_msges_conf").dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            buttons: {
                '<?php echo $lang_adm_exp_aceptar; ?>': function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        $( "#modal_adv_conf" ).dialog({
            modal: true,
            autoOpen: false,
            height: 180,
            width:300,
            resizable: false,
            buttons: {
                '<?php echo $lang_adm_exp_aceptar; ?>': function() {
                    $( this ).dialog( "close" );
                    crearGrupos();
                },
                '<?php echo $lang_adm_exp_cancelar; ?>': function(){
                    $(this).dialog("close");

                }
            }
        });
        $("#admin_exp_campo_localidad").autocomplete(comunas, {
            width: 258,
            max: 4,
            highlight: false,
            multiple: true,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $("#admin_exp_campo_curso").autocomplete(establecimientos, {
            width: 258,
            max: 4,
            highlight: false,
            multiple: true,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        
        $("#fr_admin_exp_localidad").validate({
            rules:{
                admin_exp_campo_localidad:{
                    required:true,
                    minlength:3
                }
            },
            messages:{
                admin_exp_campo_localidad: {
                    required:"<?php echo $lang_registro_localidad_required;?>",
                    minlength:"<?php echo $lang_registro_localidad_minlenght;?>"
                }
            },
            submitHandler: function() {
                var datos_a_enviar = $("#admin_exp_campo_localidad").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_adm_exp_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_localidad\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_adm_exp_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioLocalidad',
                        buttons: {
                            "<?php echo $lang_adm_exp_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_exp_campo_localidad').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_adm_exp_seguro_guardar; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_localidad\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_adm_exp_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioLocalidad',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_adm_exp_si; ?>": function() {
                                url = 'admin_exp_editar_info_general.php?codeexp=<?php echo $id_experiencia;?>';
                                    $.post(url, $("#fr_admin_exp_localidad").serialize(),function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_localidad').html('<p><?php echo $lang_adm_exp_cambio_exito; ?></p>');
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_localidad').html("<p><?php echo $lang_adm_exp_problema; ?></p>");
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioLocalidad div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_exp_campo_localidad').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_adm_exp_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_adm_exp_cerrar; ?>": function() {
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
            
            $("#fr_admin_exp_curso").validate({
            rules:{
                admin_exp_campo_curso:{
                    required:true,
                    minlength:3
                }
            },
            messages:{
                admin_exp_campo_curso: {
                    required:"<?php echo $lang_adm_exp_ingresar_algun_valor;?>",
                    minlength:"<?php echo $lang_adm_exp_largo_superior;?>"
                }
            },
            submitHandler: function() {
                var datos_a_enviar = $("#admin_exp_campo_curso").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_adm_exp_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_curso\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_adm_exp_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioCurso',
                        buttons: {
                            "<?php echo $lang_adm_exp_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioCurso div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_exp_campo_curso').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_adm_exp_seguro_guardar; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_curso\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_adm_exp_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioCurso',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_adm_exp_si; ?>": function() {
                                url = 'admin_exp_editar_info_general.php?codeexp=<?php echo $id_experiencia;?>';
                                    $.post(url, $("#fr_admin_exp_curso").serialize(),function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_curso').html('<p><?php echo $lang_adm_exp_cambio_exito; ?></p>');
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_curso').html("<p><?php echo $lang_adm_exp_problema; ?></p>");
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioCurso div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_exp_campo_curso').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_adm_exp_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_adm_exp_cerrar; ?>": function() {
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
            
            $("#fr_admin_exp_establecimiento").validate({
            rules:{
                admin_exp_campo_colegio:{
                    required:true,
                    minlength:3
                }
            },
            messages:{
                admin_exp_campo_colegio: {
                    required:"<?php echo $lang_registro_establecimiento_required;?>",
                    minlength:"<?php echo $lang_registro_establecimiento_minlenght;?>"
                }
            },
            submitHandler: function() {
                var datos_a_enviar = $("#admin_exp_campo_colegio").val();
                if(campo_modificar == datos_a_enviar){
                    var contenido = '<p><?php echo $lang_adm_exp_valor_distinto; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_establecimiento\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_adm_exp_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioEstablecimiento',
                        buttons: {
                            "<?php echo $lang_adm_exp_cerrar; ?>": function() {
                                $(this).dialog('destroy').remove();
                            }
                        }
                        ,
                        close: function() {
                        }
                    });
                    $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(3)").hide();
                    $dialog.dialog('open');  
                    $('#admin_exp_campo_colegio').attr("disabled",true);
                    
                }
                else{
                    var contenido = '<p><?php echo $lang_adm_exp_seguro_guardar; ?></p>';
                    var $dialog = $('<div class=\"dialogo_opciones_estudiante\"  id=\"dialogo_guardar_cambio_establecimiento\">'+contenido+'</div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_adm_exp_guardar_cambios; ?>',
                        width: 500,
                        modal: true,
                        resizable: true,
                        dialogClass:'dialogBotonesGuardarCambioEstablecimiento',
                        buttons: {
                            //ACEPTAR
                            "<?php echo $lang_adm_exp_si; ?>": function() {
                                url = 'admin_exp_editar_info_general.php?codeexp=<?php echo $id_experiencia;?>';
                                    $.post(url, $("#fr_admin_exp_establecimiento").serialize(),function(data) {
                                        if(data == 1){
                                            $('#dialogo_guardar_cambio_establecimiento').html('<p><?php echo $lang_adm_exp_cambio_exito; ?></p>');
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(3)").show();
                                        }
                                        else{
                                            $('#dialogo_guardar_cambio_establecimiento').html("<p><?php echo $lang_adm_exp_problema; ?></p>");
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(1)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(2)").hide();
                                            $("div.dialogBotonesGuardarCambioEstablecimiento div button:nth-child(3)").show(); 
                                        }
                                        $('#admin_exp_campo_colegio').attr("disabled",true);
                                    });
                            },
                            //CANCELAR
                            "<?php echo $lang_adm_exp_no; ?>": function() {
                                $(this).dialog('destroy').remove();
                            },
                            //CERRAR
                            "<?php echo $lang_adm_exp_cerrar; ?>": function() {
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
     
    });
    
</script>