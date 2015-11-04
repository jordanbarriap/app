<?php
/**
 * Carga la bitácora o el formulario de acceso (cuenta twitter).
 * Si no está establecida la variable de sesión $_SESSION["klwn_twitter_pass"]
 * muestra el formulario, que hace submit hacia el script
 * valida_cuenta_twitter.php que carga la información faltante en el arreglo
 * sesión (foto, contraseña twitter).
 * La bitácora es mostrada en dos bloques, izquierda con el formulario de posteo
 * y la lista de mensajes, y derecha, con los filtros y la lista de usuarios.
 * El div timeline se completa vía llamadas ajax usando jquery a
 * bitacora_ultimos_posts.php (ver función javascript leerUltimosPosts()). Los
 * parámetros pasados a este script se establecen dependiendo de los filtros
 * activos cuyo estado es manejado por variables javascript que son cambiadas al
 * presionar los enlaces de filtros (f_modo_timeline_exp, f_etiqueta_grupo, etc).
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
$conexion           = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$_act_fin               = dbExpObtenerActividades($id_experiencia, $conexion);
$id_actividad           = $_act_fin[0]["actividad_id"];

$datos_experiencia = dbExpObtenerInfo($id_experiencia, $conexion);
$esta_finalizada    = ($datos_experiencia["fecha_termino"] != '')?"1":"0";

dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
//ETIQUETAS QUE SE PASAN POR URL
var v_etiqueta_exp = '<?php echo $etiqueta;?>';
var v_etiqueta_clase_gemela = '<?php echo $etiqueta_gemela;?>';
var v_etiqueta_grupo = '<?php echo $etiqueta_grupo;?>';
var v_etiqueta_grupo_gemelo = '<?php echo $etiqueta_grupo_gemelo;?>';
var v_producto = false;
var v_id_grupo = '<?php echo $grupo_usuario["id_grupo"];?>';
// FILTROS. Su valor es cambiado al presionar sobre los enlaces filtros
var f_modo_timeline_exp = '0';
var f_etiqueta_grupo = '';
var f_etiqueta_grupo_gemelo = '';
var f_id_grupo = '';
var f_solo_usuario = '' ;
var f_id_experiencia_gemela = '';

</script>

<div id="titulo_timeline">
    <div id="titulo_prefijo"><?php echo $lang_que_pasa;?></div>
    <div id="filtrando"></div>
    <div id="div_recargar"><?php echo "<a id=\"link_recarga_timeline\" title=\"".$lang_recargar_timeline."\" href=\"#\"><img src=\"".$config_ruta_img."recargar.png\" alt=\"".$lang_recargar_timeline."\" /></a>";?></div>
    <div class="clear"></div>
</div>
<div id="msj_nuevo_timeline"></div>
<div id="timeline">
    <div id="mensajes_actividad_actual">
        &nbsp;
    </div>
</div>
<div id="titulo_timeline">
    <div id="titulo_prefijo"><?php echo $lang_historial_por_actividad;?></div>
    <div class="clear"></div>
</div>
<div id="timeline_finalizadas">
    <?php
    //Acordeon con los mensajes del historial
    echo "        <div id=\"accordion_mensajes\">\n\r";
    if (!is_null($_act_fin)){
        foreach($_act_fin as $actividad){ //En el caso que existan actividades finalizadas
            if($actividad["actividad_id"]!= $id_actividad){
                echo "            <h3 id = \"".$actividad["actividad_id"]."\"><a  href=\"historial_mensajes_actividad.php?codexp=".$id_experiencia."&codact=".$actividad["actividad_id"]."&codexpact=".$actividad["id_expact"]."&et_exp=".$etiqueta."&et_clase_gemela=".$etiqueta_gemela."\">".$lang_mensajes_de_actividad.": ".$actividad["nombre_actividad"]."</a></h3>\n\r";
                echo "            <div id = \"div_".$actividad["actividad_id"]."\" class=\"mensajes_historial\">\n\r";
                echo "            </div>\n\r";
            }
        }
        //Mensajes previos a iniciar la primera actividad
        echo "            <h3><a href=\"historial_mensajes_actividad.php?codexp=".$id_experiencia."&codact=-1&codexpact=-1&et_exp=".$etiqueta."&et_clase_gemela=".$etiqueta_gemela."\">".$lang_historial_antes_actividad."</a></h3>\n\r";
        echo "            <div id= \"acor\" class=\"mensajes_historial\">\n\r";
        echo "            </div>\n\r";
    }//Mensajes previos a iniciar la primera actividad, sin ninguna actividad finalizada
    else{
        echo "            <h3><a href=\"historial_mensajes_actividad.php?codexp=".$id_experiencia."&codact=-1&codexpact=-1&et_exp=".$etiqueta."&et_clase_gemela=".$etiqueta_gemela."\">".$lang_historial_antes_actividad."</a></h3>\n\r";
        echo "            <div id= \"acor\" class=\"mensajes_historial\">\n\r";
        echo "            </div>\n\r";
    }
    echo "        </div>\n\r";
?>
</div>
<div class="clear"></div>
<script type="text/javascript">
    function iniciarMenu() {
        $('#menu_bitacora ul').hide();
        $('#menu_bitacora ul:first').show();
        $('#menu_bitacora li a').click(function() {
            var checkElement = $(this).next();
            if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                return false;
            }
            if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                $('#menu_bitacora ul:visible').slideUp('normal');
                checkElement.slideDown('normal');
                return false;
            }
        });
    }
    function mostrarTextoFiltro(){
        if (f_modo_timeline_exp == 0){
            texto = "<?php echo $lang_filtrando_miclase;?>";
        }
        if (f_modo_timeline_exp == 1){
            texto = "<?php echo $lang_filtrando_gemela;?>";
        }
        if (f_modo_timeline_exp == 3){
            texto = "<?php echo $lang_filtrando_grupo;?>"+ f_nombre_grupo;
        }
        if ((f_solo_usuario!=null) && (f_solo_usuario != '') ){
            texto = "<?php echo $lang_filtrando_usuario.": " ;?>"+ f_solo_usuario;
        }
        $('#filtrando').html("("+texto+")");
    }
    function leerUltimosPosts(){
        url = 'bitacora_ultimos_posts.php?codeexp=<?php echo $id_experiencia;?>'+
                '&et_exp='+v_etiqueta_exp+
                '&et_clase_gemela='+v_etiqueta_clase_gemela+
                '&modo='+f_modo_timeline_exp+
                '&finalizada=<?php echo $esta_finalizada;?>';
        if (f_solo_usuario.length > 0){
            url += '&solo_usuario='+f_solo_usuario;
        }
        if (f_id_experiencia_gemela.length >0){
            url+= '&id_clase_gemela='+f_id_experiencia_gemela;
        }
        if (f_id_grupo.length >0){
            url+= '&id_grupo='+f_id_grupo;
        }
        else {
            if ((f_etiqueta_grupo != null) && (f_etiqueta_grupo != '')) url += '&et_grupo='+f_etiqueta_grupo;
            if ((f_etiqueta_grupo_gemelo != null) && (f_etiqueta_grupo_gemelo != '')) url += '&et_grupo_gemelo='+f_etiqueta_grupo_gemelo;
        }
        $.get(url, function(data) {
          $('#mensajes_actividad_actual').html(data);
        });
        mostrarTextoFiltro();
       return false;
    }
    function definirURLHistorialMensajes(){
        url2 = '&modo='+f_modo_timeline_exp;
        if (f_solo_usuario.length > 0){
            url2 += '&solo_usuario='+f_solo_usuario;
        }
        if (f_id_experiencia_gemela.length >0){
            url2+= '&id_clase_gemela='+f_id_experiencia_gemela;
        }
        if (f_id_grupo.length >0){
            url2+= '&id_grupo='+f_id_grupo;
        }
        else {
            if ((f_etiqueta_grupo != null) && (f_etiqueta_grupo != '')) url2 += '&et_grupo='+f_etiqueta_grupo;
            if ((f_etiqueta_grupo_gemelo != null) && (f_etiqueta_grupo_gemelo != '')) url2 += '&et_grupo_gemelo='+f_etiqueta_grupo_gemelo;
        }
        limpiarAccordion();
        mostrarTextoFiltro();
       return false;
    }
    function limpiarAccordion(){
        $( "div","#accordion_mensajes" ).empty();
        $("h3","#accordion_mensajes").removeClass("abierto");
        $("#accordion_mensajes").accordion('activate',-1);
    }
    $(document).ready(function(){
        iniciarMenu();
        leerUltimosPosts();
        definirURLHistorialMensajes();
        $('#boton_enviar_post').attr('disabled', true);
        repeticion = window.setInterval("mensajesNuevosTimeLine()",180000);
        $('#mensajes_actividad_actual').addClass('fondo_mi_clase');
        detenerMuralDisenoNM();
        detenerBitacoraCompartidaNM();
        <?php
        if($es_observador || $esta_finalizada){
        ?>
            $('#txt_nuevo_post_id').attr('disabled', true);
            
        <?php
        }
        ?>        
        $("#accordion_mensajes").accordion({
            header: "h3",
            collapsible: true,
            active: -1,
            autoHeight: false,
            navigation: true,
            animated: false
        });
        /* Redefine el click de cada acordión invocando vía ajax a la URL asociada */
        $("h3","#accordion_mensajes").click(function(e){
            if($(this).hasClass('abierto')) {

            } else {
                $(this).addClass('abierto');
                var div_contenido = $(this).next("div");
                la_url = $(this).find("a").attr("href")+url2;
                $.ajax({type:"get",url:la_url,success: function(data){
                        div_contenido.html(data);
                }}); 
            }              
        });
    
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


        $('#link_recarga_timeline').click(function(){
            leerUltimosPosts();
            definirURLHistorialMensajes();
            return false;
        });
 
  });
</script>