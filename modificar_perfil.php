<?php
/**
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Katherine Inalef - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

// Obtener datos actuales del usuario
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$datos_actuales = dbObtenerInfoUsuario($_SESSION["klwn_usuario"], $conexion);
$nombre_completo = $datos_actuales["nombre"];
list($nombre, $apellido) = split(' ', $nombre_completo);
list($anio, $mes, $dia) = split('-', $datos_actuales["fecha_nacimiento"]);
$datos_actuales["fecha_nacimiento"] = $dia . "-" . $mes . "-" . $anio;
if ($datos_actuales["fecha_nacimiento"] == "00-00-0000") {
    $datos_actuales["fecha_nacimiento"] = null;
}
dbDesconectarMySQL($conexion);
?>
<div id="contenido_modificar_perfil">
    <div id="modificar_datos_personales" class="estado_experiencia  modificar_datos click titulo_editar_a" >
        <img id="img_a" src="img/flecha_a.png"></img>
        <?php echo $lang_modificar_perfil_datos_personales; ?> 
    </div>
    <div id="modificar_datos_personales" class="estado_experiencia modificar_datos click titulo_editar_c" >
        <img id="img_a" src="img/flecha_c.png"></img>
        <?php echo $lang_modificar_perfil_datos_personales; ?> 
    </div>
    <div id="modificar_perfil">
        <div class="intro_modificar_perfil"><?php echo $lang_modificar_perfil_intro; ?></div>
        <div id="formulario_modificar_perfil">
            <form id="form_modificar_perfil" method="post" action="">
                <div id="caja_form_modificar_perfil">
                    <label><?php echo $lang_registro_nombre . " :"; ?></label>
                    <input tabindex="1" type="text" maxlenght="20" size="20" id="fmp_campo_modificar_nombre"  name="fmp_campo_modificar_nombre" value="<?php echo $nombre; ?>"/>
                    <div class="clear"></div>
                    <label><?php echo $lang_registro_apellido . " :"; ?></label>
                    <input tabindex ="2" type="text" maxlenght="20" size="20" id="fmp_campo_modificar_apellido" name="fmp_campo_modificar_apellido" value="<?php echo $apellido; ?>"/>
                    <div class="clear"></div>
                    <label ><?php echo $lang_modificar_perfil_correo . " :"; ?></label>
                    <input tabindex="3" type="text" maxlenght="20" size="20" id="fmp_campo_modificar_correo" name="fmp_campo_modificar_correo" value="<?php echo $datos_actuales["email"]; ?>" />
                    <div class="clear"></div>
                    <input class="check" type="checkbox" name="fmp_campo_mostrar_correo" value="1" <?php if($datos_actuales["mostrar_correo"]==1){echo "checked";} ?>><label class="lbl_check"><?php echo $lang_modif_perfil_correo_electronico; ?></label>
                    <div class="clear"></div>
                    <label for="fmp_campo_modificar_contrasena_antigua"><?php echo $lang_modificar_perfil_contrasena_actual . " :"; ?></label>
                    <input tabindex="4" type="password" maxlenght="20" size="20" id="fmp_campo_modificar_contrasena_antigua" name="fmp_campo_modificar_contrasena_antigua" />
                    <div class="clear"></div>
                    <label for="fmp_campo_modificar_contrasena_nueva"><?php echo $lang_modificar_perfil_contrasena_nueva . " :"; ?></label>
                    <input tabindex="5" type="password" maxlenght="20" size="20" id="fmp_campo_modificar_contrasena_nueva" name="fmp_campo_modificar_contrasena_nueva" />
                    <div class="clear"></div>
                    <label for="fmp_campo_modificar_contrasena_conf"><?php echo $lang_modificar_perfil_contrasena_conf . " :"; ?></label>
                    <input tabindex="6" type="password" maxlenght="20" size="20" id="fmp_campo_modificar_contrasena_conf" name="fmp_campo_modificar_contrasena_conf" />
                    <div class="clear"></div>
                    <label><?php echo $lang_modificar_perfil_comuna . " :"; ?></label>
                    <input tabindex="7" type="text" maxlenght="20" size="20" id="fmp_campo_modificar_comuna" name="fmp_campo_modificar_comuna" value="<?php echo $datos_actuales["localidad"]; ?>"/>
                    <div class="clear"></div>
                    <label><?php echo $lang_modificar_perfil_establecimiento . " :"; ?></label>
                    <input tabindex="8" type="text" maxlenght="20" size="20" id="fmp_campo_modificar_establecimiento" name="fmp_campo_modificar_establecimiento" value="<?php echo $datos_actuales["establecimiento"]; ?>"/>
                    <div class="clear"></div>
                    <label><?php echo $lang_modificar_perfil_fecha_nacimiento . " :"; ?> <a onclick="show_calendar()" style="cursor: pointer;">
                            <small>(<?php echo $lang_registro_calendario; ?>)</small>
                        </a>
                    </label>
                    <input  tabindex="9" type="text"  id="fmp_campo_modificar_fecha_nacimiento" name="fmp_campo_modificar_fecha_nacimiento" value="<?php echo $datos_actuales["fecha_nacimiento"]; ?>"/>
                    <div class="clear"></div>
                    <input class="check" type="checkbox" name="fmp_campo_mostrar_fecha" value="1" <?php if($datos_actuales["mostrar_fecha"]==1){echo "checked";} ?>><label class="lbl_check"><?php echo $lang_modif_perfil_mostrar_cumpleanos; ?></label>
                    <div class="clear"></div>
                    <div id="calendario" style="display:none;"><?php calendar_html($calendario_meses, $calendario_dias) ?></div>
                    <div class="clear"></div>
                    <input tabindex="10" class="submit" type="submit" value="<?php echo $lang_modificar_perfil_actualizar; ?>">
                    <div class="clear"></div>
                    <div id="error_modificar_perfil"></div>
                    <div class="clear"></div>     
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modificar_img_perfil">
    <div id="modificar_img" class="estado_experiencia modificar_imagen click modificar_imagen_c" >
        <img id="img_a" src="img/flecha_c.png"></img>
        <?php echo $lang_modificar_imagen_perfil_titulo; ?> 
    </div>
    <div id="modificar_img" class="estado_experiencia modificar_imagen click modificar_imagen_a" >
        <img id="img_a" src="img/flecha_a.png"></img>
        <?php echo $lang_modificar_imagen_perfil_titulo; ?> 
    </div>
   <iframe id="iframe_imagen" src="modificar_imagen_perfil.php" frameborder="0" scrolling="no"></iframe> 
</div>

<?php
require_once($ruta_raiz . "inc/footer.inc.php");
?>
<script type="text/javascript">
    //Funciones para el calendario
    var estado = 0;
    function show_calendar(){
        $('#calendario').toggle();
    }
    function set_date(date){
        $('#fmp_campo_modificar_fecha_nacimiento').attr('value',date);
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

    $(document).ready(function(){
        $('#icono_datos').hide();
        $('#iframe_imagen').hide();
        $("#error_modificar_perfil").hide();
        $('.titulo_editar_c').hide();
        $('.modificar_imagen_a').hide();
        $("#fmp_campo_modificar_comuna").autocomplete(comunas, {
            width: 258,
            max: 4,
            highlight: false,
            multiple: true,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $("#fmp_campo_modificar_establecimiento").autocomplete(establecimientos, {
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
        }, "<?php echo $lang_modificar_perfil_mensaje_caracteres_especiales; ?>");

        $.validator.addMethod("noCaracteresEspecialesContrasena", function(value, element) {
            return this.optional(element) || /^[a-zA-Z|0-9]+$/i.test(value);
        }, "<?php echo $lang_modificar_perfil_mensaje_caracteres_especiales_contrasena; ?>");

        $("#form_modificar_perfil").validate({
            rules:{
                fmp_campo_modificar_nombre:{
                    required: true,
                    minlength:3
                },
                fmp_campo_modificar_apellido:{
                    required: true,
                    minlength:3
                },
                fmp_campo_modificar_correo:{
                    required:true,
                    email: true,
                    remote:"verifica_campo.php"
                },
                fmp_campo_modificar_contrasena_antigua: {
                    required:function() {
                        if ( $('#fmp_campo_modificar_contrasena_nueva').val().length >0 ) {
                            return true;
                        }
                        else{
                            return false;
                        }
                    },
                    remote:"verifica_campo.php"
                },
                fmp_campo_modificar_contrasena_nueva: {
                    required: function() {
                        if ( $('#fmp_campo_modificar_contrasena_antigua').val().length > 0 ) {
                            return true;
                        }
                        else{
                            return false;
                        }
                    },
                    minlength: 5
                },
                fmp_campo_modificar_contrasena_conf: {
                    required: function() {
                        if ( $('#fmp_campo_modificar_contrasena_nueva').val().length > 0 ) {
                            return true;
                        }
                        else{
                            return false;
                        }
                    },
                    minlength: 5,
                    equalTo:"#fmp_campo_modificar_contrasena_nueva"
                },
                fmp_campo_modificar_comuna: {
                    required: true,
                    minlength: 5
                },
                fmp_campo_modificar_establecimiento: {
                    required: false,
                    minlength: 5
                }
            },

            messages:{

                fmp_campo_modificar_nombre: {
                    required:"<?php echo $lang_modificar_perfil_nombre_required; ?>",
                    minlength:"<?php echo $lang_modificar_perfil_nombre_minlenght; ?>"
                },
                fmp_campo_modificar_apellido:{
                    required:"<?php echo $lang_modificar_perfil_apellido_required; ?>",
                    minlength:"<?php echo $lang_modificar_perfil_apellido_minlength ?>"
                }
                ,
                fmp_campo_modificar_correo:{
                    required:"<?php echo $lang_modificar_perfil_correo_required ?>",
                    email:"<?php echo $lang_modificar_perfil_correo_email ?>" ,
                    remote:"<?php echo $lang_modificar_perfil_correo_remote ?>"
                },
                fmp_campo_modificar_nombre_usuario:{
                    required:"<?php echo $lang_modificar_perfil_nombre_usuario_required ?>",
                    remote:"<?php echo $lang_modificar_perfil_nombre_usuario_remote ?>"
                },
                fmp_campo_modificar_contrasena_antigua:{
                    required:"<?php echo $lang_modificar_perfil_contrasena_antigua_required ?>",
                    remote:"<?php echo $lang_modificar_perfil_contrasena_antigua_minlength ?>"
                },
                fmp_campo_modificar_contrasena_nueva: {
                    required: "<?php echo $lang_modificar_perfil_contrasena_required ?>",
                    minlength: "<?php echo $lang_modificar_perfil_contrasena_minlength ?>"
                },
                fmp_campo_modificar_contrasena_conf: {
                    required: "<?php echo $lang_modificar_perfil_contrasena_conf_required ?>",
                    minlength: "<?php echo $lang_modificar_perfil_contrasena_conf_minlength ?>",
                    equalTo: "<?php echo $lang_modificar_perfil_contrasena_conf_equalto ?>"
                },
                fmp_campo_modificar_comuna: {
                    required: "<?php echo $lang_modificar_perfil_localidad_required ?>",
                    minlength: "<?php echo $lang_modificar_perfil_localidad_minlength ?>"
                },
                fmp_campo_modificar_establecimiento: {                   
                    minlength: "<?php echo $lang_modificar_perfil_establecimiento_minlength ?>"
                }
            },
            submitHandler: function() {
                url = 'actualiza_datos_perfil.php?';

                $.post(url, $("#form_modificar_perfil").serialize(), function(data) {

                    if (data != "1"){
                        $("#error_modificar_perfil").text('<?php echo $lang_fallo_modificar_perfil; ?>');
                        $("#error_modificar_perfil").show();
                    }
                    else{
                        $(".intro_modificar_perfil").html("<div><?php echo $lang_modificar_perfil_exitoso ?> </div>");
                        $("#form_modificar_perfil").html("<div></div>");
                        $.post('inicio_datos_usuario.php', function(data) {
                          $('#inicio_info_usuario').html(data);
                        });
                        
                    }
                });
            }
        });
        $('.modificar_datos').click(function(){
            if(estado == 1){
                estado=0
                $('#modificar_perfil').slideDown();
                $('#iframe_imagen').slideUp();
                $('.titulo_editar_a').show();
                $('.titulo_editar_c').hide();
                $('.modificar_imagen_c').show();
                $('.modificar_imagen_a').hide();
            }

        });
        $('.modificar_imagen').click(function(){
            if(estado == 0){
                estado=1
                $('#iframe_imagen').slideDown();
                $('#modificar_perfil').slideUp();
                $('.titulo_editar_c').show();
                $('.titulo_editar_a').hide();
                $('.modificar_imagen_a').show();
                $('.modificar_imagen_c').hide();
            }
        });
    });
</script>

