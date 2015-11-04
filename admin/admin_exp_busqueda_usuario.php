<?php

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
?>
</br>
    <form id="form_admin_busqueda" method="post" action="">
        <div id="caja_form_admin_busqueda">
            <label><?php echo $lang_registro_nombre." :";?></label>
            <input tabindex="1" type="text" maxlenght="20" size="20" id="fr_admin_busqueda_nombre"  name="fr_admin_busqueda_nombre" />
            <label  class="sugerencia" id="admin_sugerencia_nombre"><?php echo $lang_admin_solo_un_nombre; ?></label>
            <div class="clear"></div>
            <label><?php echo $lang_registro_apellido." :";?></label>
            <input tabindex ="2" type="text" maxlenght="20" size="20" id="fr_admin_busqueda_apellido" name="fr_admin_busqueda_apellido" />
            <label class="sugerencia" id="admin_sugerencia_apellido"><?php echo $lang_admin_solo_un_apellido; ?></label>
            <div class="clear"></div>
            <label><?php echo $lang_registro_localidad." :";?></label>
            <input tabindex="7" type="text" maxlenght="20" size="20" id="fr_admin_busqueda_localidad" name="fr_admin_busqueda_localidad" />
            <div class="clear"></div>
            <label><?php echo $lang_registro_establecimiento." :";?></label>
            <input tabindex="7" type="text" maxlenght="20" size="20" id="fr_admin_busqueda_establecimiento" name="fr_admin_busqueda_establecimiento" />
            <div class="clear"></div>
            <input class="submit" type="submit" value="<?php echo $lang_admin_buscar_minus;?>">
        </div>
    </form>
    <div id="admin_bloque_busqueda">
    </div>
<script type="text/javascript">

    $(document).ready(function(){
        
        $(".sugerencia").hide();
        $(".admin_bloque_filtros_usuarios a").click(function(){
            $(this).parent().addClass('admin_selected'). 
            siblings().removeClass('admin_selected');
        });
        
        $("#fr_admin_busqueda_localidad").autocomplete(comunas, {
            width: 258,
            max: 4,
            highlight: false,
            multiple: true,
            multipleSeparator:"",
            scroll: true,
            scrollHeight: 300
        });
        $("#fr_admin_busqueda_establecimiento").autocomplete(establecimientos, {
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
        
        
        $("#form_admin_busqueda").validate({
            rules:{
                fr_admin_busqueda_nombre:{
                    required:true,
                    minlength:3
                },
                fr_admin_busqueda_apellido:{
                    required:false,
                    minlength:3
                },
                fr_admin_busqueda_localidad: {
                    required: false,
                    minlength: 5
                },
                fr_admin_busqueda_establecimiento: {
                    required: false,
                    minlength: 5
                }
            },
            messages:{
                
                fr_admin_busqueda_nombre: {
                    required:"<?php echo $lang_registro_nombre_required;?>",
                    minlength:"<?php echo $lang_registro_nombre_minlenght;?>"
                },
                fr_admin_busqueda_apellido:{
                    minlength:"<?php echo $lang_registro_apellido_minlength?>"
                }
                ,
                fr_admin_busqueda_localidad: {
                    minlength: "<?php echo $lang_registro_localidad_minlength?>"
                },
                fr_admin_busqueda_establecimiento: {
                    minlength: "<?php echo $lang_registro_establecimiento_minlength?>"
                }
            },
            submitHandler: function() {
                url = 'admin/admin_exp_listado_estudiantes.php?modo=2';
                $.post(url, $("#form_admin_busqueda").serialize(), function(data) {
                    $("#fr_admin_busqueda_nombre").html("");
                    $("#fr_admin_busqueda_nombre").val("");
                    $("#fr_admin_busqueda_apellido").html("");
                    $("#fr_admin_busqueda_apellido").val("");
                    $("#fr_admin_busqueda_localidad").html("");
                    $("#fr_admin_busqueda_localidad").val("");
                    $("#fr_admin_busqueda_establecimiento").html("");
                    $("#fr_admin_busqueda_establecimiento").val("");
                    $("#admin_bloque_busqueda").html(data);
                    $("#form_admin_busqueda").html("<div></div>");

                });
            }
        });
        $("#fr_admin_busqueda_nombre").focus(function() {
            $("#admin_sugerencia_nombre").show();
        });
        $("#fr_admin_busqueda_apellido").focus(function() {
            $("#admin_sugerencia_apellido").show();
        });
        $("#fr_admin_busqueda_nombre").blur(function() {
            $("#admin_sugerencia_nombre").hide();
        });
        $("#fr_admin_busqueda_apellido").blur(function() {
            $("#admin_sugerencia_apellido").hide();
        });
    });
</script>