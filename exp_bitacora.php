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

$id_sesion              = $_SESSION['id_sesion'];
$id_experiencia         = $_REQUEST["codexp"];
$etiqueta               = $_REQUEST["et_exp"];
$etiqueta_gemela        = "";
if (!is_null($_REQUEST["et_gemela"]) AND strlen($_REQUEST["et_gemela"])>0){
    $etiqueta_gemela = $_REQUEST["et_gemela"];
}
$_imagenes_usuario  = darFormatoImagen($_SESSION["klwn_foto"], $config_ruta_img_perfil, $config_ruta_img);
$imagen_usuario     = $_imagenes_usuario["imagen_usuario"];
$imagen_grande      = $_imagenes_usuario["imagen_grande"];

//datos del usuario
$usuario        = $_SESSION["klwn_usuario"];
$id_usuario     = $_SESSION["klwn_id_usuario"];
$rol            = validaExperiencia($id_experiencia);
$es_estudiante  = $rol == '2';
$es_observador  = $rol == '-1';

$conexion           = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$_info_experiencias = dbBitacoraObtenerInfoExperiencias($etiqueta_gemela, $conexion);
$i= 0;
while(!is_null($_info_experiencias[$i])){
    if($_info_experiencias[$i]["id_experiencia"]== $id_experiencia){
        $id_clase = $i;
        break;
    }
    $i++;
}
$esta_finalizada    = ($_info_experiencias[$id_clase]["fecha_termino"] != '')?"1":"0";

$_act_fin               = dbExpObtenerActividades($id_experiencia, $conexion);
$id_actividad           = $_act_fin[0]["actividad_id"];
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

//Variable que indica si el usuario activó o no la visualización
var visualizacion_activa = false;
</script>
<?php
    //Agregar aqui los id de los usuarios que tendran acceso a la bitacora
    //$usuario_seleccionado_vis= 5664;
    //$usuarios_seleccionados_vis=array(121,2087,5664,6714,6593,6599,6596,6605,6607,6600,6604,6606,6602,6594,6601,6609,6603,6640 ,6626,6649,6635,6616,6615,6623,6619,6641,6627,6622,6637,6630,6633,6629,6625,6614,6613,6650,6648,6643,6642,6628,6676,6671,6668,6658,6685,6663,6654,6655,6669,6662,6657);
    //Agregar aqui los id de las experiencias que tendran acceso a la bitacora
    //$experiencias_seleccionadas_vis=array(347,348,350);
    //if (in_array($id_experiencia,$experiencias_seleccionadas_vis)) {// && in_array($id_usuario, $usuarios_seleccionados_vis)){
?>
    <div id="div-visualizacion">
        <!--<div id="visualizacion_actividad">
        </div>
        <div id="nube_palabras">
        </div>-->
    </div>
<?php
    //}
?>    
<div class="contenido">
    <div class="contenido_izquierda">
        <div id="bloque_posteo_usuario">
            <div id="imagen_usuario"><img alt="<?php echo $_SESSION["klwn_usuario"];?>" src="<?php echo $imagen_grande;?>" /></div>
            <div id="nuevo_mensaje">
            <form id="form_posteo_usuario" action="">
                <div id="caja_texto">
                    <textarea id="txt_nuevo_post_id" name="txt_nuevo_post" cols="30" rows="6"></textarea>
                </div>
                <div class="clear"></div>
                <div class="opciones_mensaje">
                <div id="caracteres_restantes"><span id="n_caracteres_restantes"><?php echo $config_char_disponibles;?></span><?php echo " ".$lang_caracteres_restantes.".";?></div>
                <br/>
                <br/>
                <label class="bitacora_intro_compartir"><?php echo $lang_exp_bit_publicar; ?> </label>
                <input type="radio"  id ="msj_opcion_defecto" name="compartido" value="0" checked> <span title="<?php echo $lang_exp_bit_bit_actividad; ?>"><?php echo $lang_exp_bit_mi_clase; ?></span>
                <input type="radio"  name="compartido" value="1" > <span title="<?php echo $lang_exp_bit_aulas_gemelas; ?>"><?php echo $lang_exp_bit_compartida; ?></span>
                </br>
                <div id="enviar_mensaje" class="enviar_mensaje"><button id="boton_enviar_post" class="boton_enviar_post"><?php echo $lang_exp_bit_publicar;?></button></div>
                <div class="clear"></div>
                </div>
            </form>
            </div>
        </div>
        <div class="opciones_bitacora">
            <ul class="opciones_bitacora">
                <li title="<?php echo $lang_exp_bit_bit_actividad; ?>" class="li_opcion_bitacora bitacora_selected"><a class="admin_filtro_usuario " id = "bitacora_mi_clase"><?php echo $lang_exp_bit_mi_clase; ?></a> </li>
                <li title="<?php echo $lang_exp_bit_aulas_gemelas; ?>" class="li_opcion_bitacora"><a class="opcion_bitacora" id = "bitacora_compartida"><?php echo $lang_exp_bit_compartida; ?></a> </li>
            </ul>
        </div>
        <div class="clear"></div>
        <div id="bloque_bitacora">
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="contenido_derecha" id="contenido_derecha">
    </div>
</div>

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

    //Código agregado por Jordan Barría el 31-10-2014
    function cargarVisualizacion(){
        var id_experiencia = "<?php echo $id_experiencia;?>";
        var experiencias_vis_desactivada = ["358","360"]; 
        if (experiencias_vis_desactivada.indexOf(id_experiencia) > -1 && !visualizacion_activa){
            $('#div-visualizacion').empty();
            //$('#div-visualizacion').html('<img src="/dataviz/img/herramienta_visualizacion_blurred.png"><button class="boton_activar_vis" type="button" onclick="activarVisualizacion()"></button>');
            $('#div-visualizacion').html('<img src="/dataviz/img/herramienta_visualizacion_blurred.png"><div class="boton_activar_vis" onclick="activarVisualizacion();"></div>');
            
        }else{
            url='dataviz/herramienta_visualizacion_actividad.php?codexp=<?php echo $id_experiencia;?>&id_usuario=<?php echo $id_usuario?>&id_sesion=<?php echo $id_sesion?>';
            $.get(url, function(data){
                $('#div-visualizacion').empty();
                $('#div-visualizacion').html('<div id="visualizacion_actividad"></div><div id="nube_palabras"></div>');
                $('#visualizacion_actividad').html(data);
            });
        }  
    }

    function activarVisualizacion(){
        visualizacion_activa = true;
        cargarVisualizacion();
    }
    //Fin código agregado por Jordan Barría
    function cargarBitacoraMiClaseContenidoDerecha(){
        url = 'bitacora_mi_clase_cont_derecha.php?codexp=<?php echo $id_experiencia;?>&et_exp=<?php echo $etiqueta;?>&et_gemela=<?php echo $etiqueta_gemela;?>'
        $.get(url, function(data) {
          $('#contenido_derecha').html(data);
        });
    }
    function cargarBitacoraCompartidaContenidoDerecha(){
        url = 'bitacora_compartida_cont_derecha.php?codexp=<?php echo $id_experiencia;?>&et_exp=<?php echo $etiqueta;?>&et_gemela=<?php echo $etiqueta_gemela;?>'
        $.get(url, function(data) {
          $('#contenido_derecha').html(data);
        });
    }
    function cargarBitacoraMiClase(){
        url = 'bitacora_mi_clase.php?codexp=<?php echo $id_experiencia;?>&et_exp=<?php echo $etiqueta;?>&et_gemela=<?php echo $etiqueta_gemela;?>'
        $.get(url, function(data) {
          $('#bloque_bitacora').html(data);
        });
        cargarBitacoraMiClaseContenidoDerecha();
    }
    function cargarBitacoraCompartida(){
        url = 'bitacora_compartida.php?codexp=<?php echo $id_experiencia;?>&et_exp=<?php echo $etiqueta;?>&et_gemela=<?php echo $etiqueta_gemela;?>'
        $.get(url, function(data) {
          $('#bloque_bitacora').html(data);
        });
        cargarBitacoraCompartidaContenidoDerecha();
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
    function leerUltimosPostsCompartida(){
        url = 'bitacora_compartida_ultimos_posts.php?codeexp=<?php echo $id_experiencia;?>'+
                '&et_clase_gemela='+v_etiqueta_clase_gemela+
                '&modo='+f_modo_timeline_exp;

        if (f_solo_usuario.length > 0){
            url += '&solo_usuario='+f_solo_usuario;
        }
        $.get(url, function(data) {
          $('#mensajes_compartida').html(data);
        });
        mostrarTextoFiltro();

       return false;
    }
    function limpiarAccordion(){
        $( "div","#accordion_mensajes" ).empty();
        $("h3","#accordion_mensajes").removeClass("abierto");
        $("#accordion_mensajes").accordion('activate',-1);
    }
    $(document).ready(function(){
        enviarWebsocket("Bitacora <?php echo $id_experiencia;?>")
        iniciarMenu();
        definirURLHistorialMensajes();
        cargarBitacoraMiClase();

        cargarVisualizacion();//Código agregado por Jordan Barría el 31-10-2014

        $('#boton_enviar_post').attr('disabled', true);
//        $('#boton_enviar_post_todos').attr('disabled', true);
//        repeticion = window.setInterval("mensajesNuevosTimeLine()",300000);
//        repeticion2 = window.setInterval("mensajesNuevosTimeLineCompartida()",300000);
        $('#mensajes_actividad_actual').addClass('fondo_mi_clase');
        detenerMuralDisenoNM();
        
        $('#bitacora_mi_clase').click(function() {
            cargarBitacoraMiClase();
        });
        $('#bitacora_compartida').click(function() {
            cargarBitacoraCompartida();
        });
        
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
//                  $('#boton_enviar_post_todos').attr('disabled', true);

              }else{
                  $('#boton_enviar_post').attr('disabled', false);
//                  $('#boton_enviar_post_todos').attr('disabled', false);
              }
        });

        

        //ENVÍO DE LAS ETIQUETAS PARA PONERLAS EN EL MENSAJE
        $('#boton_enviar_post').click(function(){
            var id_exp="<?php echo $id_experiencia;?>";
            var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";
            var id_sesion="<?php echo $_SESSION['id_sesion'];?>";//Código agregado por Jordan Barría el 13-12-14
            var tipo_bitacora=$("#form_posteo_usuario input[type='radio']:checked").val();
            url = 'bitacora_enviar_post.php?codeexp='+id_exp+
                '&et_exp='+v_etiqueta_exp+
                '&et_exp_gemela='+v_etiqueta_clase_gemela+
                '&et_grupo='+v_etiqueta_grupo+
                '&id_grupo='+v_id_grupo+
                '&et_grupo_gemelo='+v_etiqueta_grupo_gemelo;
            if (v_producto) url = url + '&et_producto=<?php echo $config_etiqueta_producto;?>';
            var mensaje=$("#txt_nuevo_post_id").val();//Código agregado por Jordan Barría el 08-11-14
            $.post(url, $("#form_posteo_usuario").serialize(), function(data) {
                $("#txt_nuevo_post_id").html("");
                $("#txt_nuevo_post_id").val("");
                $("#n_caracteres_restantes").html('<?php echo $config_char_disponibles;?>');
                //falta identificar que bitácora está visualizando
                leerUltimosPosts();
                leerUltimosPostsCompartida();
                if (data == "0"){
                    window.location.replace("ingresar.php");
                }else{
                    var nombre_tipo_bitacora;
                    if (tipo_bitacora==0){
                        nombre_tipo_bitacora="Clase";
                    }else{
                        nombre_tipo_bitacora="Compartida";
                    }
                    //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+nombre_tipo_bitacora);//Linea agregada por Jordan Barría el 06-11-14
                    enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+nombre_tipo_bitacora);//Linea agregada por Jordan Barría el 06-11-14
                }
            });
            $('#boton_enviar_post').attr('disabled', true);
            $('#boton_enviar_post_todos').attr('disabled', true);
            $('#msj_opcion_defecto').attr('checked', true);
            return false;
        });


        $('#link_recarga_timeline').click(function(){
            leerUltimosPosts();
            definirURLHistorialMensajes();
            return false;
        });
        $(".opciones_bitacora a").click(function(){
            $(this).parent().addClass('bitacora_selected'). 
            siblings().removeClass('bitacora_selected');
        });
        
  });
</script>
