<?php

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
$nombre = $_REQUEST["admin_fr_campo_nombre"];
if(!is_null($nombre)&& strlen($nombre)>0){
    
    $apellido = $_REQUEST["admin_fr_campo_apellido"];
    $nombre_usuario = $_REQUEST["admin_fr_campo_nombre_usuario"];
    $contrasena = $_REQUEST["admin_fr_campo_contrasena"];
    $fecha_nacimiento = $_REQUEST["admin_fr_campo_fecha_nacimiento"];
    $correo = $_REQUEST["admin_fr_campo_correo"];
    $inscribe_diseno = '0';
    $localidad = $_REQUEST["admin_fr_campo_localidad"];
    $establecimiento = $_REQUEST["admin_fr_campo_establecimiento"];
    list($dia, $mes, $anio) = split('-', $fecha_nacimiento);
    $fecha_nacimiento = $anio."-".$mes."-".$dia;

    //Validaciones
    $validacion = 'true';
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);

    if(isset($nombre)&& isset($apellido)&& isset($nombre_usuario)&& isset($contrasena)&& isset($correo)&& $validacion== 'true'){
        $ingreso = dbAdminInsertarNuevoUsuario($nombre, $apellido, $nombre_usuario, $contrasena, $fecha_nacimiento, $correo, $inscribe_diseno, $localidad,$establecimiento, $conexion);
        echo $ingreso;
        dbDesconectarMySQL($conexion);
    }
    else{
        echo "-1";
    }
}
else{    
?>
<div class="container_16">
    <div class="grid_12">
        <div>
            </br>
            <form id="admin_form_crear_usuario" method="post" action="">
                <div id="admin_caja_form_crear_usuario">
                    <label><?php echo $lang_registro_nombre." :";?></label>
                    <input tabindex="1" type="text" maxlenght="20" size="20" id="admin_fr_campo_nombre"  name="admin_fr_campo_nombre" />
                    <label  class="sugerencia" id="admin_sugerencia_nombre"><?php echo $lang_registro_sugerencia_nombre;?></label>
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_apellido." :";?></label>
                    <input tabindex ="2" type="text" maxlenght="20" size="20" id="admin_fr_campo_apellido" name="admin_fr_campo_apellido" />
                    <label class="sugerencia" id="admin_sugerencia_apellido"><?php echo $lang_registro_sugerencia_apellido;?></label>
                    <div class="clear"></div>
                    <label for="admin_fr_campo_correo"><?php echo $lang_registro_correo." :";?></label>
                    <input tabindex="3" type="text" maxlenght="20" size="20" id="admin_fr_campo_correo" name="admin_fr_campo_correo" />
                    <div class="clear"></div>
                    <label for="admin_fr_campo_nombre_usuario"><?php echo $lang_registro_nombre_usuario." :";?></label>
                    <input tabindex="4" type="text" maxlenght="20" size="20" id="admin_fr_campo_nombre_usuario"  name="admin_fr_campo_nombre_usuario" class="noCaracteresEspeciales"/>
                    <div class="clear"></div>
                    <label for="admin_fr_campo_contrasena"><?php echo $lang_registro_contrasena." :";?></label>
                    <input tabindex="5" type="password" maxlenght="20" size="20" id="admin_fr_campo_contrasena" name="admin_fr_campo_contrasena" class="noCaracteresEspecialesContrasena"/>
                    <div class="clear"></div>
                    <label for="admin_fr_campo_contrasena_conf"><?php echo $lang_registro_contrasena_confirma." :";?></label>
                    <input tabindex="6" type="password" maxlenght="20" size="20" id="admin_fr_campo_contrasena_conf" name="admin_fr_campo_contrasena_conf" class="noCaracteresEspecialesContrasena"/>
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_localidad." :";?></label>
                    <input tabindex="7" type="text" maxlenght="20" size="20" id="admin_fr_campo_localidad" name="admin_fr_campo_localidad" />
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_establecimiento." :";?></label>
                    <input tabindex="8" type="text" maxlenght="20" size="20" id="admin_fr_campo_establecimiento" name="admin_fr_campo_establecimiento" />
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_fecha_nacimiento." :";?> <a onclick="show_calendar()" style="cursor: pointer;">
                            <small>(<?php echo $lang_registro_calendario;?>)</small>
                        </a>
                    </label>
                    <input  tabindex="9" type="text"  id="admin_fr_campo_fecha_nacimiento" name="admin_fr_campo_fecha_nacimiento" value=""/>
                    <div class="clear"></div>
                    <div id="calendario" style="display:none;"><?php calendar_html($calendario_meses, $calendario_dias) ?></div>
                    <div class="clear"></div>
                    <input id=" admin_boton_enviar_crear" class="submit" type="submit" value="<?php echo $lang_admin_crear_usuario; ?>">
                </div>
            </form>
        </div>
    </div>
    <div class="grid_2">&nbsp;</div>
</div>
<?php

?>
<script type="text/javascript">
    //Funciones para el calendario
    function show_calendar(){
        $('#calendario').toggle();
    }
    function set_date(date){
        $('#admin_fr_campo_fecha_nacimiento').attr('value',date);
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
        $("#admin_fr_campo_localidad").autocomplete(comunas, {
            width: 258,
            max: 4,
            highlight: false,
            multiple: true,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $("#admin_fr_campo_establecimiento").autocomplete(establecimientos, {
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

        $("#admin_form_crear_usuario").validate({
            onkeyup: false,
            rules:{
                admin_fr_campo_nombre:{
                    required:true,
                    minlength:3
                },
                admin_fr_campo_apellido:{
                    required:true,
                    minlength:3
                },
                admin_fr_campo_correo:{
                    required:true,
                    email: true,
                    remote:"verifica_campo.php"
                },
                admin_fr_campo_nombre_usuario:{
                    required:true,
                    remote:"verifica_campo.php"
                },
                admin_fr_campo_contrasena: {
                    required: true,
                    minlength: 5
                },
                admin_fr_campo_contrasena_conf: {
                    required: true,
                    minlength: 5,
                    equalTo:"#admin_fr_campo_contrasena"
                },
                admin_fr_campo_localidad: {
                    required: true,
                    minlength: 5
                },
                admin_fr_campo_establecimiento: {
                    required: false,
                    minlength: 5
                }
            },
            messages:{
                
                admin_fr_campo_nombre: {required:"<?php echo $lang_registro_nombre_required;?>",
                    minlength:"<?php echo $lang_registro_nombre_minlenght;?>"
                },
                admin_fr_campo_apellido:{required:"<?php echo $lang_registro_apellido_required;?>",
                    minlength:"<?php echo $lang_registro_apellido_minlength?>"
                }
                ,
                admin_fr_campo_correo:{required:"<?php echo $lang_registro_correo_required?>",
                    email:"<?php echo $lang_registro_correo_email?>" ,
                    remote:"<?php echo $lang_registro_correo_remote?>"
                },
                admin_fr_campo_nombre_usuario:{
                    required:"<?php echo $lang_registro_nombre_usuario_required?>",
                    remote:"<?php echo $lang_registro_nombre_usuario_remote?>"
                },
                admin_fr_campo_contrasena: {
                    required: "<?php echo $lang_registro_contrasena_required?>",
                    minlength: "<?php echo $lang_registro_contrasena_minlength?>"
                },
                admin_fr_campo_contrasena_conf: {
                    required: "<?php echo $lang_registro_contrasena_conf_required?>",
                    minlength: "<?php echo $lang_registro_contrasena_conf_minlength?>",
                    equalTo: "<?php echo $lang_registro_contrasena_conf_equalto?>"
                },
                admin_fr_campo_localidad: {
                    required: "<?php echo $lang_registro_localidad_required?>",
                    minlength: "<?php echo $lang_registro_localidad_minlength?>"
                },
                admin_fr_campo_establecimiento: {
                    required: "<?php echo $lang_registro_establecimiento_required?>",
                    minlength: "<?php echo $lang_registro_establecimiento_minlength?>"
                }
            },
            submitHandler: function() {
                $("#admin_boton_enviar_crear").attr('disabled', true),
                url = 'admin/admin_usuario_crear.php?';
                $.post(url, $("#admin_form_crear_usuario").serialize(), function(data) {
                    $("#admin_fr_campo_nombre").html("");
                    $("#admin_fr_campo_nombre").val("");
                    $("#admin_fr_campo_apellido").html("");
                    $("#admin_fr_campo_apellido").val("");
                    $("#admin_fr_campo_fecha_nacimiento").html("");
                    $("#admin_fr_campo_fecha_nacimiento").val("");
                    $("#admin_fr_campo_correo").html("");
                    $("#admin_fr_campo_correo").val("");
                    $("#admin_fr_campo_nombre_usuario").html("");
                    $("#admin_fr_campo_nombre_usuario").val("");
                    $("#admin_fr_campo_contrasena").html("");
                    $("#admin_fr_campo_contrasena").val("");
                    $("#admin_fr_campo_contrasena_conf").html("");
                    $("#admin_fr_campo_contrasena_conf").val("");
                    $("#admin_fr_campo_localidad").html("");
                    $("#admin_fr_campo_localidad").val("");
                    $("#admin_fr_campo_establecimiento").html("");
                    $("#admin_fr_campo_establecimiento").val("");
                    if (data != "-1"){
                     $.get('admin/admin_usuario_vincular_exp.php?id_usuario='+data, function(data2) { 
                       $('.admin_listado_usuarios').html(data2);
                     });
                        
                    }
                    
                    else{
                        $("#error_registro").text('<?php echo $lang_fallo_registro;?>');
                    }
                });
            }
        });

        $("#admin_fr_campo_nombre_usuario").focus(function() {
            var nombre = $("#admin_fr_campo_nombre").val().toLowerCase();
            var apellido = $("#admin_fr_campo_apellido").val().toLowerCase();
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
        $("#admin_fr_campo_nombre").focus(function() {
            $("#admin_sugerencia_nombre").show();
        });
        $("#admin_fr_campo_apellido").focus(function() {
            $("#admin_sugerencia_apellido").show();

        });
        $("#admin_fr_campo_nombre").blur(function() {
            $("#admin_sugerencia_nombre").hide();
        });
        $("#admin_fr_campo_apellido").blur(function() {
            $("#admin_sugerencia_apellido").hide();
        });
       
    });
</script>
<?php }?>
