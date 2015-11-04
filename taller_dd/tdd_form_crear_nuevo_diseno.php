<?php
/**
 *
 * @author  Elson Gueregat - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "taller_dd/tdd_obtenerMisDisenos.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");

$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$_tipo_escala = obtenerTiposEscala($conexion);

?>
<div class="contenido">
    <div class="titulo_dd">
        <?php echo $lang_tdd_titulo_nuevo_diseno;?>
    </div>    
    <form id="form_crear_diseno_nuevo" method="post" action="" accept-charset="UTF-8">
        <div id="caja_form_crear_diseno_nuevo"  style="margin-left:15px;">
            <label><?php echo $lang_crear_nuevo_diseno_nombre . " :"; ?></label>
            <input tabindex="1" type="text" maxlenght="20" size="20" id="fcdn_nombre"  name="fcdn_nombre" value="" onkeypress="hideErrorDiseno();"/>
            <div class="clear"></div>
            <label><?php echo $lang_crear_nuevo_diseno_sector . " :"; ?></label>
            <select tabindex="2"  id="fcdn_sector" name="fcdn_sector" size="1">                    
            <?php
                for($i=0; $i<count($_sectores); $i++){
                    if($i == 0){
                        echo '<option value="'.$_sectores[$i]['valor'].'" selected>'.$_sectores[$i]['nombre'].'</option>';
                    }else{
                        echo '<option value="'.$_sectores[$i]['valor'].'">'.$_sectores[$i]['nombre'].'</option>';                            }
                }                    
            ?>
            </select>                       
            <div class="clear"></div>
            <label ><?php echo $lang_crear_nuevo_diseno_nivel . " :"; ?></label>
            <select tabindex="3"  id="fcdn_nivel" name="fcdn_nivel" size="1">   
            <?php
                for($i=0; $i<count($_niveles); $i++){
                    if($i == 0){
                        echo '<option value="'.$_niveles[$i].'" selected>'.$_niveles[$i].'</option>';
                    }else{
                        echo '<option value="'.$_niveles[$i].'">'.$_niveles[$i].'</option>';                            }
                }                    
            ?>                
            </select>
            <div class="clear"></div>
            <label><?php echo $lang_crear_nuevo_tipo_escala . " :"; ?></label>
            <select tabindex="12"  id="fcdn_escala" name="fcdn_escala" size="1">
                <?php
                for ($i = 0; $i < count($_tipo_escala); $i++) {
                    if ($_tipo_escala[$i]['tesc_id_tipo_escala'] == $fcd_diseno_escala) {
                        echo '<option value="' . $_tipo_escala[$i]['tesc_id_tipo_escala'] . '" selected>' . $_tipo_escala[$i]['tesc_nombre_tipo'] . '</option>';
                    } else {
                        echo '<option value="' . $_tipo_escala[$i]['tesc_id_tipo_escala'] . '">' . $_tipo_escala[$i]['tesc_nombre_tipo'] . '</option>';
                    }
                }
                ?>
            </select>
            <div class="clear"></div>            
            <input tabindex="4" class="fcdn_submit" type="submit" value="<?php echo $lang_crear_diseno_crear; ?>">
            <div class="clear"></div>            
            <div id="error_form_crear_diseno_nuevo"></div>
            <div class="clear"></div> 
        </div>
    </form>
    <div class="separador"></div> 
    <div class="separador"></div>    

  
</div>
 

<script type="text/javascript">
    function cargarDiseno(idDiseno){
        modificarDiseno(idDiseno);
    }
    function hideErrorDiseno(){
        $("#error_form_crear_diseno_nuevo").hide();
    }
    
    $(document).ready(function(){

        $("#form_crear_diseno_nuevo").validate({
            rules:{
                fcdn_nombre:{
                    required: true,
                    minlength:6
                }
            },
            messages:{
                fcdn_nombre:{
                    required: '<?php echo $lang_crear_diseno_requerido1; ?>',
                    minlength: '<?php echo $lang_crear_diseno_requerido2; ?>'
                }                
            },
            submitHandler: function() {
                url = './taller_dd/tdd_crear_diseno.php?';

                $.post(url, $("#form_crear_diseno_nuevo").serialize(), function(id) {

                    if (parseInt(id)< 0){
                        $("#error_form_crear_diseno_nuevo").text('<?php echo $lang_crear_diseno_crear_err; ?>');
                        $("#error_form_crear_diseno_nuevo").show();
                    }
                    else{
                        $("#error_form_crear_diseno_nuevo").text('<?php echo $lang_crear_diseno_crear_ok; ?>');
                        $('#crear_disenod').show();
                        modificarDiseno(parseInt(id));
                    }
                });
            }
        });
        
    });    

</script>