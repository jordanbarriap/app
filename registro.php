<?php
/**
 * Formulario de registro de usuario 
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Katherine Inalef - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 **/
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/header.inc.php");
?>
<div class="container_16">
    <div class="grid_2">&nbsp;</div>
    <div class="grid_12">
        <div id="contenido_registro">
            <div id="intro_registro"><?php echo $lang_mensaje_registro;?></div>
            <?php echo $msg_fallo_login;?>
            <form id="form_registro" method="post" action="">
                <div id="caja_form_registro">
                    <label><?php echo $lang_registro_nombre." :";?></label>
                    <input tabindex="1" type="text" maxlenght="20" size="20" id="fr_campo_nombre"  name="fr_campo_nombre" />
                    <label  class="sugerencia" id="sugerencia_nombre"><?php echo $lang_registro_sugerencia_nombre;?></label>
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_apellido." :";?></label>
                    <input tabindex ="2" type="text" maxlenght="20" size="20" id="fr_campo_apellido" name="fr_campo_apellido" />
                    <label class="sugerencia" id="sugerencia_apellido"><?php echo $lang_registro_sugerencia_apellido;?></label>
                    <div class="clear"></div>
                    <label for="fr_campo_correo"><?php echo $lang_registro_correo." :";?></label>
                    <input tabindex="3" type="text" maxlenght="20" size="20" id="fr_campo_correo" name="fr_campo_correo" />
                    <div class="clear"></div>
                    <label for="fr_campo_nombre_usuario"><?php echo $lang_registro_nombre_usuario." :";?></label>
                    <input tabindex="4" type="text" maxlenght="20" size="20" id="fr_campo_nombre_usuario"  name="fr_campo_nombre_usuario" class="noCaracteresEspeciales"/>
                    <div class="clear"></div>
                    <label for="fr_campo_contrasena"><?php echo $lang_registro_contrasena." :";?></label>
                    <input tabindex="5" type="password" maxlenght="20" size="20" id="fr_campo_contrasena" name="fr_campo_contrasena" class="noCaracteresEspecialesContrasena"/>
                    <div class="clear"></div>
                    <label for="fr_campo_contrasena_conf"><?php echo $lang_registro_contrasena_confirma." :";?></label>
                    <input tabindex="6" type="password" maxlenght="20" size="20" id="fr_campo_contrasena_conf" name="fr_campo_contrasena_conf" class="noCaracteresEspecialesContrasena"/>
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_localidad." :";?></label>
                    <input tabindex="7" type="text" maxlenght="20" size="20" id="fr_campo_localidad" name="fr_campo_localidad" />
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_establecimiento." :";?></label>
                    <input tabindex="7" type="text" maxlenght="20" size="20" id="fr_campo_establecimiento" name="fr_campo_establecimiento" />
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_fecha_nacimiento." :";?> <a onclick="show_calendar()" style="cursor: pointer;">
                            <small>(<?php echo $lang_registro_calendario;?>)</small>
                        </a>
                    </label>
                    <input  tabindex="8" type="text"  id="fr_campo_fecha_nacimiento" name="fr_campo_fecha_nacimiento" value=""/>
                    <div class="clear"></div>
                    <div id="calendario" style="display:none;"><?php calendar_html($calendario_meses, $calendario_dias) ?></div>
                    <div class="clear"></div>
                    <input class="submit" type="submit" value="<?php echo $lang_pagina_registro;?>">
                </div>
            </form>
        </div>
    </div>
    <div class="grid_2">&nbsp;</div>
</div>
<?php
require_once($ruta_raiz."inc/footer.inc.php");
?>
<script type="text/javascript">
    //Funciones para el calendario
    function show_calendar(){
        $('#calendario').toggle();
    }
    function set_date(date){
        $('#fr_campo_fecha_nacimiento').attr('value',date);
        show_calendar();
    }
    function update_calendar(){
        var month = $('#calendar_mes').attr('value');
        var year = $('#calendar_anio').attr('value');

        var valores='month='+month+'&year='+year;

        $.ajax({
            url: 'calendario.php',
            type: "GET",
            data: valores,
            success: function(datos){
                $("#calendario_dias").html(datos);
            }
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
        $(".sugerencia").hide();
        $("#fr_campo_localidad").autocomplete(comunas, {
            width: 258,
            max: 4,
            highlight: false,
            multiple: true,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $("#fr_campo_establecimiento").autocomplete(establecimientos, {
            width: 258,
            max: 4,
            highlight: false,
            multiple: true,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $.validator.addMethod("noCaracteresEspeciales", function(value, element) {
            return this.optional(element) || /^[.|_a-zA-Z|0-9]+$/i.test(value);
        }, "<?php echo $lang_registro_mensaje_caracteres_especiales;?>");

        $.validator.addMethod("noCaracteresEspecialesContrasena", function(value, element) {
            return this.optional(element) || /^[a-zA-Z|0-9]+$/i.test(value);
        }, "<?php echo $lang_registro_mensaje_caracteres_especiales_contrasena;?>");

        $("#form_registro").validate({
            rules:{
                fr_campo_nombre:{
                    required:true,
                    minlength:3
                },
                fr_campo_apellido:{
                    required:true,
                    minlength:3
                },
                fr_campo_correo:{
                    required:true,
                    email: true,
                    remote:"verifica_campo.php"
                },
                fr_campo_nombre_usuario:{
                    required:true,
                    remote:"verifica_campo.php"
                },
                fr_campo_contrasena: {
                    required: true,
                    minlength: 5
                },
                fr_campo_contrasena_conf: {
                    required: true,
                    minlength: 5,
                    equalTo:"#fr_campo_contrasena"
                },
                fr_campo_localidad: {
                    required: true,
                    minlength: 5
                },
                fr_campo_establecimiento: {
                    required: false,
                    minlength: 5
                }
            },
            messages:{
                
                fr_campo_nombre: {required:"<?php echo $lang_registro_nombre_required;?>",
                    minlength:"<?php echo $lang_registro_nombre_minlenght;?>"
                },
                fr_campo_apellido:{required:"<?php echo $lang_registro_apellido_required;?>",
                    minlength:"<?php echo $lang_registro_apellido_minlength?>"
                }
                ,
                fr_campo_correo:{required:"<?php echo $lang_registro_correo_required?>",
                    email:"<?php echo $lang_registro_correo_email?>" ,
                    remote:"<?php echo $lang_registro_correo_remote?>"
                },
                fr_campo_nombre_usuario:{
                    required:"<?php echo $lang_registro_nombre_usuario_required?>",
                    remote:"<?php echo $lang_registro_nombre_usuario_remote?>"
                },
                fr_campo_contrasena: {
                    required: "<?php echo $lang_registro_contrasena_required?>",
                    minlength: "<?php echo $lang_registro_contrasena_minlength?>"
                },
                fr_campo_contrasena_conf: {
                    required: "<?php echo $lang_registro_contrasena_conf_required?>",
                    minlength: "<?php echo $lang_registro_contrasena_conf_minlength?>",
                    equalTo: "<?php echo $lang_registro_contrasena_conf_equalto?>"
                },
                fr_campo_localidad: {
                    required: "<?php echo $lang_registro_localidad_required?>",
                    minlength: "<?php echo $lang_registro_localidad_minlength?>"
                },
                fr_campo_establecimiento: {
                    required: "<?php echo $lang_registro_establecimiento_required?>",
                    minlength: "<?php echo $lang_registro_establecimiento_minlength?>"
                }
            },
            submitHandler: function() {
                url = 'ingresa_registro.php?';
                $.post(url, $("#form_registro").serialize(), function(data) {
                    $("#fr_campo_nombre").html("");
                    $("#fr_campo_nombre").val("");
                    $("#fr_campo_apellido").html("");
                    $("#fr_campo_apellido").val("");
                    $("#fr_campo_fecha_nacimiento").html("");
                    $("#fr_campo_fecha_nacimiento").val("");
                    $("#fr_campo_correo").html("");
                    $("#fr_campo_correo").val("");
                    $("#fr_campo_nombre_usuario").html("");
                    $("#fr_campo_nombre_usuario").val("");
                    $("#fr_campo_contrasena").html("");
                    $("#fr_campo_contrasena").val("");
                    $("#fr_campo_localidad").html("");
                    $("#fr_campo_localidad").val("");
                    $("#fr_campo_establecimiento").html("");
                    $("#fr_campo_establecimiento").val("");
                   if (data != "1"){
                        $("#error_registro").text('<?php echo $lang_fallo_registro;?>');
                    }
                    else{
                        $("#intro_registro").html("<div><?php echo $lang_registro_exitoso?> <a href=\"ingresar.php\"><?php echo $lang_ingresar;?></a></div>");
                        $("#form_registro").html("<div></div>");
                    }
                });
            }
        });

        $("#fr_campo_nombre_usuario").focus(function() {
            var nombre = $("#fr_campo_nombre").val().toLowerCase();
            var apellido = $("#fr_campo_apellido").val().toLowerCase();
            var nombres = nombre.split(' ');
            var nombre1 = nombres[0];
            var apellidos= apellido.split(' ');
            var apellido1 = apellidos[0];
            if(nombre && apellido && !this.value) {
                var nombre_usuario = nombre1 + "." + apellido1;
                nombre_usuario = nombre_usuario.removeAccents();
                this.value = nombre_usuario;
            }
        });
        $("#fr_campo_nombre").focus(function() {
            $("#sugerencia_nombre").show();
        });
        $("#fr_campo_apellido").focus(function() {
            $("#sugerencia_apellido").show();

        });
        $("#fr_campo_nombre").blur(function() {
            $("#sugerencia_nombre").hide();
        });
        $("#fr_campo_apellido").blur(function() {
            $("#sugerencia_apellido").hide();
        });
       
    });
</script>

