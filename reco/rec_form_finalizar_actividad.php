<?php
/*
 * Despliega formulario al finalizar una actividad para recojer la evaluacion
 * de la actividad y de la recomendación ingresada por el usuario.
 * Parametros:
 * 	$id_actividad: Identificador de la actividad.
 *	$id_experiencia: Identificador de la experiencia.
 *      $id_exp_act: Identificador experiencia-actividad.
 *      $id_usuario: Identificador del usuario.
 *      $id_boton: Identificador de boton Finalizar.
 *      $nombre_actividad: Nomnbre de la actividad que se finaliza.
 *
 * Funciones
 *      dbRECBuscaRecomendacionAnterior(..): obtiene la evaluacion y
 *      recomendacion ingresados en la finalizacion anterior de la actividad.
 *
 *      recDespliegaFormularioRecEvActividad(..): funcion que despliega formulario.
 *
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Cristian Miranda - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.
 * 
 */
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."revpares/inc/rp_db_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");
require_once($ruta_raiz."reco/inc/rec_functions.inc.php");

$id_actividad = $_REQUEST["id_act"];
$id_experiencia = $_REQUEST["id_exp"];
$id_exp_act = $_REQUEST["id_exp_act"];
$id_usuario = $_REQUEST["id_usuario"];
$id_boton = $_REQUEST["id_boton"];
$nombre_act = $_REQUEST["nombre_act"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$_datos_anteriores = dbRECBuscaRecomendacionAnterior($id_actividad, $id_experiencia, $id_usuario, $conexion);
$profesor = dbExpObtenerProfesor($id_experiencia, $conexion);

if($profesor["id"]== $_SESSION["klwn_id_usuario"]){
    $es_profesor_responsable =1;
}
$avance_exp = dbExpObtenerAvance($id_experiencia, $conexion);
$t_ejecutado = $avance_exp["suma_t_actividades_finalizadas"] OR 0;
$t_estimado = $avance_exp["suma_sesiones_estimadas"] * $config_minutos_sesion;
$nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);

?>
<p class="resaltado"><?php echo $lang_seguro_de_terminar_actividad1;?></p>
<p class="nombre_actividad"><?php echo $nombre_act;?></p>
<p><?php echo $lang_seguro_de_terminar_actividad2;?></p>

<?php

//recDespliegaFormularioRecEvActividad($_datos_anteriores);
echo "<form id=\"rec_form_recomienda\" name=\"rec_form_recomienda\" action=\"\">";
echo    "<br />";
echo    "<span class=\"rec_resaltado\">".$lang_rec_function_resultado_act."</span>";
echo    "<div class=\"rec_chkbox_evaluacion\">";
    if($_datos_anteriores == null){   //si no hay recomendacion anterior
        echo    "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
        echo    "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
        echo    "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
        echo    "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
    }
    else{
        if($_datos_anteriores[0]["evaluacion"] == "Muy Bien"){
            echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_muy_bien." <br />";
            echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
            echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
            echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
        }
        else{
            if($_datos_anteriores[0]["evaluacion"] == "Bien"){
                echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
                echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_bien." <br />";
                echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
                echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
            }
            else{
                if($_datos_anteriores[0]["evaluacion"] == "Mal"){
                    echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
                    echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
                    echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_mal." <br />";
                    echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
                }
                else{
                    if($_datos_anteriores[0]["evaluacion"] == "Muy Mal"){
                        echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
                        echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
                        echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
                        echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_muy_mal;
                    }
                }
            }
        }
    }
    echo "</div>";
    echo "<div class=\"rec_chkbox_error\">";
        echo "<label for=\"rec_chkbox\" class=\"error\" style=\"display:none;\">".$lang_rec_function_selecciona_alternativa."</label>";
    echo "</div>";
    echo "<p></p>";
    echo "<span class=\"rec_resaltado\">".$lang_rec_function_deja_reco_pares."</span>";
    echo "<div class=\"rec_caja_nueva_reco\">";
    if($_datos_anteriores == null){
        echo "<textarea id=\"rec_txt_nueva_reco_id\" name=\"rec_txt_nueva_reco\"></textarea>";
    }
    else{
        echo "<textarea id=\"rec_txt_nueva_reco_id\" name=\"rec_txt_nueva_reco\">".$_datos_anteriores[0]["mensaje"]."</textarea>";            
    }
    echo "</div>";
    echo "<div class=\"rec_caracteres_disp\">";
            echo "<span id=\"n_cara_restantes\">1024</span> ".$lang_rec_function_caracteres_disponibles;
    echo "</div>";
echo "</form>";
?>


<script type="text/javascript">
function ventanaFinaliza(){
    var contenido = '<?php echo $lang_finalizar_experiencia; ?>';
    var $dialog = $('<div id=\"dialogo_deshaciendo_actividad\"><p class = \"resaltado\">'+contenido+'</p></div>')
    .dialog({
        autoOpen: false,
        title: '<?php echo $lang_rec_form_finalizar_act_fin_exp; ?>',
        width: 400,
        height: 250,
        modal: true,
        buttons: {
            "<?php echo $lang_rec_form_finalizar_act_cerrar; ?>": function() {
                $(this).dialog("close");
            }
        },
        close: function(ev, ui) {
            $(this).remove();
        }
    });
    $dialog.dialog('open');
    return false;
}
$("#rec_form_recomienda").validate({
        rules:{
            rec_txt_nueva_reco:{
                required:true
            },
            rec_chkbox:{
                required:true
            }
        },
        messages:{
            rec_txt_nueva_reco:{
                required:"<?php echo $lang_rec_form_finalizar_act_ingresa_rec; ?>"
            }
        },
        submitHandler: function() {
            despliegaCuadroRecomendaciones('<?php echo $id_actividad?>','3');
            url = 'reco/rec_enviar_mensaje_eval.php?codact=<?php echo $id_actividad?>&codexp=<?php echo $id_experiencia?>&id_exp_act=<?php echo $id_exp_act?>&id_usuario=<?php echo $id_usuario?>';
            $.post(url, $("#rec_form_recomienda").serialize(),function(){
                $("#dialogo_cambiando_estado").dialog('close');
                $("#rec_txt_nueva_reco").val(""); //area de texto
            });
            $.get(
                'exp_finalizar_actividad.php?codexpact=<?php echo $id_exp_act?>&codeexp=<?php echo $id_experiencia?>&nombre_actividad=<?php echo $nombre_act?>&id_actividad=<?php echo $id_actividad?>',
                function(data){
                    if (parseInt(data) == '1'){     
//                      <?php
                            if($nivel_avance == 100 && $es_profesor_responsable){
                                ?>
                                     ventanaFinaliza();
                                <?php
                            }
                            ?>                        
                            $('#<?php echo $id_boton?>').removeClass("boton_finalizar");
                            $('#<?php echo $id_boton?>').addClass("boton_finalizada");
                            $('#<?php echo $id_boton?>').html("");
                            $('#<?php echo $id_boton?>').unbind('click');
                            $('#id_desact_<?php echo $id_actividad?>').removeClass('bda_inactivo');
                            $('#id_desact_<?php echo $id_actividad?>').addClass('bda_activo');
                            $('#id_desact_<?php echo $id_actividad?>').show();
                            $('#<?php echo $id_boton?>').attr('onclick','');
                            visibilidadNoComenzadas(true);
                            $(".bda_activo").show();
                            cargarAvance();
                    }
                }
            );
            $("#dialogo_cambiando_estado").remove();
        }
    });
    $('#rec_txt_nueva_reco_id').one("click",function() {
        $(this).val('');
    });
    $('#rec_txt_nueva_reco_id').keyup(function(){
        var charlength = $(this).val().length;
        var car_disponibles = 1024;
        var car_restantes = car_disponibles - charlength;
        $('#n_cara_restantes').html(car_restantes);
    });
    
</script>







