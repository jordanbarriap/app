<?php
/**
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *  
 * @author  Elson Gueregat - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 *   
 **/

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./../";
require_once($ruta_raiz ."conf/config.php");
require_once($ruta_raiz ."inc/all.inc.php");
require_once($ruta_raiz ."inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz ."concurso/inc/con_db_funciones.inc.php");
require_once($ruta_raiz ."concurso/conf/con_config.php");
require_once($ruta_raiz . "concurso/conf/con_mensajes_ayuda.php");

$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$fca_id_actividad           =-1;
$fca_nombre                 = "";	
$fca_aprendizaje_esperado   = "";	
$fca_evidencia_aprendizaje  = "";
$fca_descripcion_general    = "";
$fca_horas                  = 0;
$fca_tipo_lugar             = 1;
$fca_materiales             = "";
$fca_medios                 = "";
$fca_inicio                 = "";
$fca_desarrollo             = "";
$fca_cierre                 = "";
$fca_medios_otros           = 1;
$fca_medios_trabajos        = 1;
$fca_id_complementaria      = -1;
$fca_medios_bitacora        = 0;
$fca_medios_web20           = 0;

if(isset($_GET['idActividad'])){
    $fca_id_actividad           = $_GET['idActividad'];
    $_actividad = obtenerActividadFuncion($fca_id_actividad, $conexion);

    $fca_nombre                 = $_actividad[0]['ac_nombre'];	
    $fca_aprendizaje_esperado   = $_actividad[0]['ac_aprendizaje_esperado'];	
    $fca_descripcion_general    = $_actividad[0]['ac_descripcion'];
    $fca_tipo_lugar             = $_actividad[0]['ac_tipo'];
    
    $orden_etapa                = $_GET['orden_etapa'];
    $abajo                      = $_GET['abajo'];
    $arriba                     = $_GET['arriba'];
   
}

$class_laboratorio = 'oculto';
$class_sala        = 'oculto';

//$_archivos = obtenerArchivosFuncion($fca_id_actividad, $conexion);
//$_pautas = obtenerPautasFuncion($fca_id_actividad, $conexion);
//$_complementarias = buscarActividadComplemFuncion($_GET['id_diseno'], $orden_etapa, $_actividad[0]['ac_orden'], $conexion);

dbDesconectarMySQL($conexion);        
?>
<!doctype html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset;?>" >
	<title><?php //echo $actividad;?></title> 
</head> 
<body>
<div id="contenido_actividad"> 
<!--    
    <div id="prueba_a_borrar">xx</div>
-->
    <div id="info_actividad">
        <div id="ayuda_ac"><div id="ayuda_content_ac"></div></div>
            <form id="form_crear_actividad" method="post" action="" accept-charset="UTF-8">
                <div id="caja_form_crear_actividad">
                    <br></br>
                    <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fca_id_actividad"  name="fca_id_actividad" value="<?php echo $fca_id_actividad; ?>"/>
                    <label><?php echo $lang_nueva_actividad_nombre . " :"; ?></label>
                    <input tabindex="1" type="text" maxlenght="20" size="20" id="fca_nombre"  name="fca_nombre" value="<?php echo $fca_nombre; ?>"/>
                    <img class="imagen_ayuda" id="fca_nombre_ayuda" src="./img/help.png"></img>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_aprendizaje_esperado . " :"; ?></label>
                    <textarea tabindex="2" id="fca_aprendizaje_esperado" name="fca_aprendizaje_esperado" rows="3" cols="1"><?php echo $fca_aprendizaje_esperado; ?></textarea>
                    <img class="imagen_ayuda" id="fca_aprendizaje_esperado_ayuda" src=".//img/help.png"></img>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_descripcion_general . " :"; ?></label>
                    <textarea tabindex="4" id="fca_descripcion_general" name="fca_descripcion_general" rows="3" cols="1"><?php echo $fca_descripcion_general; ?></textarea>
                    <img class="imagen_ayuda" id="fca_descripcion_general_ayuda" src=".//img/help.png"></img>
                    <div class="clear"></div>
                    <label><?php echo $lang_nueva_actividad_tipo_lugar . " :"; ?></label>
                    <select tabindex="6"  id="fca_tipo_lugar" name="fca_tipo_lugar" size="1"> 
                    <?php
                        for($i=0; $i<count($_act_tipo); $i++){
                            if($_act_tipo[$i]['valor'] == $fca_tipo_lugar){
                                echo '<option value="'.$_act_tipo[$i]['valor'].'" selected>'.$_act_tipo[$i]['nombre'].'</option>';
                            }else{
                                echo '<option value="'.$_act_tipo[$i]['valor'].'">'.$_act_tipo[$i]['nombre'].'</option>';                            }
                        }                    
                    ?>
                    </select>
                    <div class="clear"></div> 
                    <input id="cerrar" class="cerrar_actividad" type="button" onclick="cerrarActividad();" value="<?php echo $lang_concurso_cerrar; ?>" name="cerrar">
                    <input tabindex="11" class="fca_submit" type="submit" value="<?php echo $lang_crear_actividad_guardar; ?>">
                    <div class="clear"></div>
                    <div id="error_form_crear_actividad"></div>
                    <div class="clear"></div>     
                </div>
            </form>        
    </div>
</div>
 
<script type="text/javascript">
 
<?php

foreach ($ayuda['actividad'] as $key => $value){
    echo "document.getElementById('".$key."').onclick = function(){mostrarAyudaActividad('".$value."','".$key."');};";
}
foreach ($ayuda['actividad'] as $key => $value){
    echo "document.getElementById('".$key."_ayuda').onclick = function(){mostrarDivAyudaActividad();};";
}

?>
 
    var actividad_laboratorio = <?php echo $actividad_laboratorio; ?>;
    var actividad_revision = <?php echo $actividad_revision; ?>;
    
    var input_ayuda_actual_acti = "fca_nombre";
    var end_comentario_act= 5;
    var ver_mas_comentario_act= 5;
    
    function mostrarAyudaActividad(texto, key){

        left= document.getElementById(key).offsetLeft;
        top_= document.getElementById(key).offsetTop;

        document.getElementById(key+'_ayuda').style.visibility = 'visible';
        if(key != input_ayuda_actual_acti){
            document.getElementById(input_ayuda_actual_acti+'_ayuda').style.visibility = 'hidden';
            document.getElementById('ayuda_ac').style.visibility = 'hidden';
        }
        input_ayuda_actual_acti= key;
        if(key=="fca_medios_otros"){
            document.getElementById('ayuda_ac').style.width = 390+"px";
        }
        document.getElementById('ayuda_ac').style.left = (left)+"px";
        document.getElementById('ayuda_ac').style.top = (top_ -55)+"px";
        document.getElementById('ayuda_content_ac').innerHTML = texto;
    }
    function mostrarDivAyudaActividad(){
        if(document.getElementById('ayuda_ac').style.visibility == 'hidden')
            $('#ayuda_ac').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0});
        else
            document.getElementById('ayuda_ac').style.visibility = 'hidden';
    }     
    
    function cerrarActividad(){
        scroll(0,0);
        id_etapa= <?php echo $_actividad[0]['ac_id_etapa_con']; ?>;
        orden_etapa= <?php echo $orden_etapa; ?>;        
        actualizarEtapa(id_etapa, orden_etapa);
        $dialog.dialog('close');
    }
   
    $(document).ready(function(){
//        document.getElementById('ayuda-diseno').style.zIndex = 9999;
        //$("#prueba_a_borrar").html(diffString("hola como estas tu","hola como tu"));
        nicEditors.elemById({buttonList : ['bold','italic','underline','ol','ul','indent','outdent','link','unlink'], maxHeight : 80},['fca_aprendizaje_esperado','fca_descripcion_general']);
        
        
        $('#fca_nombre').tbHinter({
            text: '<?php echo $ayuda['actividad']['fca_nombre']?>'
        });
        $('#fca_aprendizaje_esperado').tbHinter({
            text: '<?php echo $ayuda['actividad']['fca_aprendizaje_esperado']?>'
        });
        $('#fca_descripcion_general').tbHinter({
            text: '<?php echo $ayuda['actividad']['fca_descripcion_general']?>'
        });
        
        $("#form_crear_actividad").validate({
            rules:{
                fca_nombre:{
                    required: true,
                    minlength:6
                },
                fca_horas:{
                    required: true,
                    min: 0,
                    max: 6
                }
            },
            messages:{
                fca_nombre:{
                    required: '<?php echo $lang_concurso_campo_obligatorio; ?>',
                    minlength: '<?php echo $lang_concurso_caract_min; ?>'
                }                
            },                
            submitHandler: function() {
                url = './concurso/con_guardar_actividad.php?';

                $.post(url, $("#form_crear_actividad").serialize(), function(id) {

                    if (parseInt(id)< 0){
                        $(".error_form_crear_actividad").html("<div><?php echo $lang_concurso_act_no_guardada; ?> </div>");
                        $("#error_form_crear_diseno").show();
                    }
                    else{
                        $(".error_form_crear_actividad").html("<div><?php echo $lang_concurso_act_guardada; ?> </div>");
                        $("#error_form_crear_diseno").show();
                        id_etapa= <?php echo $_actividad[0]['ac_id_etapa_con']; ?>;
                        orden_etapa= <?php echo $orden_etapa; ?>;
                        //abajo= <?php echo $abajo;  ?>;
                        //arriba= <?php echo $arriba;  ?>;   
                        actualizarEtapa(id_etapa, orden_etapa);
                        //actualizarActividad(document.getElementById('fca_id_actividad').value, orden_etapa, abajo, arriba, document.getElementById('fca_tipo_lugar').value);
                    }
                });
            }
        });
        
        
       
    }); 
        
</script>    
</body>
</html>

