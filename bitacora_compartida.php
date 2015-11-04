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
 **/
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_experiencia         = $_REQUEST["codexp"];
$etiqueta               = $_REQUEST["et_exp"];
$etiqueta_gemela        = "";
if (!is_null($_REQUEST["et_gemela"]) AND strlen($_REQUEST["et_gemela"])>0){
    $etiqueta_gemela = $_REQUEST["et_gemela"];
}
?>
<script type="text/javascript">
//ETIQUETAS QUE SE PASAN POR URL
var v_etiqueta_exp = '<?php echo $etiqueta;?>';
var v_etiqueta_clase_gemela = '<?php echo $etiqueta_gemela;?>';
// FILTROS. Su valor es cambiado al presionar sobre los enlaces filtros
var f_modo_timeline_exp = '0';
var f_solo_usuario = '' ;
var f_id_experiencia_gemela = '';

</script>
<div id="titulo_timeline">
    <div id="titulo_prefijo"></div>
    <div id="filtrando_compartida"></div>
    <div id="div_recargar"><?php echo "<a id=\"link_recarga_timeline_compartida\" title=\"".$lang_recargar_timeline."\" href=\"#\"><img src=\"".$config_ruta_img."recargar.png\" alt=\"".$lang_recargar_timeline."\" /></a>";?></div>
    <div class="clear"></div>
</div>
<div id="msj_nuevo_timeline_compartida"></div>
<div id="timeline_compartida">
    <div id="mensajes_compartida">
        &nbsp;
    </div>
</div>
<div class="clear"></div>
<script type="text/javascript">
     function mostrarTextoFiltro(){
        if (f_modo_timeline_exp == 2){
            texto = "<?php echo $lang_bit_compartida_mis_msj;?>";
        }
        if (f_modo_timeline_exp == 1){
            texto = "<?php echo $lang_bit_compartida_msj_mi_clase;?>";
        }
        if (f_modo_timeline_exp == 0){
            texto = "<?php echo $lang_bit_compartida_todos_msj;?>";
        }
        $('#filtrando_compartida').html(texto);
    }
    

    $(document).ready(function(){
        leerUltimosPostsCompartida();
        $('#mensajes_compartida').addClass('fondo_mi_clase');
        repeticion2 = window.setInterval("mensajesNuevosTimeLineCompartida()",180000);
        detenerMuralDisenoNM();
        detenerBitacoraNM();
        <?php
        if($es_observador || $esta_finalizada){
        ?>
            $('#txt_nuevo_post_id').attr('disabled', true);
            
        <?php
        }
        ?>        
        $('#txt_nuevo_post_id').keyup(function(){
              var charlength = $(this).val().length;
              var car_disponibles = <?php echo $config_char_disponibles;?>;
              var car_restantes = car_disponibles - charlength;
              $('#n_caracteres_restantes').html(car_restantes);
              if ((charlength > car_disponibles) || (charlength < 3)){
                  $('#boton_enviar_post').attr('disabled', true);

              }else{
                  $('#boton_enviar_post').attr('disabled', false);
              }
        });


        $('#link_recarga_timeline_compartida').click(function(){
            leerUltimosPostsCompartida();
            return false;
        });
 
  });
</script>
