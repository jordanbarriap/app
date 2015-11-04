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
$_imagenes_usuario  = darFormatoImagen($_SESSION["klwn_foto"], $config_ruta_img_perfil, $config_ruta_img);
$imagen_usuario     = $_imagenes_usuario["imagen_usuario"];
$imagen_grande      = $_imagenes_usuario["imagen_grande"];

//datos del usuario
$usuario        = $_SESSION["klwn_usuario"];
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

if($es_estudiante){ //Obtener el grupo al que pertenece el usuario y sus grupo gemelo, si es que existe
    $grupo_usuario = dbObtenerGrupoUsuario($usuario, $id_experiencia, $conexion);
    $integrantes_grupo_usuario = dbExpObtenerEstudiantesPorGrupo($grupo_usuario["id_grupo"], $conexion);
    $etiqueta_grupo = $grupo_usuario["et_grupo"];
    if(!is_null($grupo_usuario["et_gemela"])){ // Obtener el grupo gemelo del grupo, si es que existe
        $grupo_gemelo_usuario = dbObtenerGrupoGemeloUsuario($grupo_usuario["id_grupo"],$grupo_usuario["et_gemela"] , $conexion);
        $i=0;
        $integrantes_grupo_gemelo = array();
        while ($grupo_gemelo_usuario[$i]){
            $_integrantes_grupo_gemelo[$i] = dbExpObtenerEstudiantesPorGrupo($grupo_gemelo_usuario[$i]["id_grupo"], $conexion);
            $i++;
        }
        if(!is_null($grupo_gemelo_usuario[0]["id_grupo"])){
            $etiqueta_grupo_gemelo = $grupo_usuario["et_gemela"];
        }
    }
}

$_grupos = dbExpObtenerGrupos($id_experiencia, $conexion); // Grupos de la experiencia
if(!is_null($_info_experiencias)){ //datos experiencias gemelas (ID, LOCALIDAD, COLEGIO, PROFESOR)
    $i = 0;
    foreach ($_info_experiencias as $clase_gemela){
        if($clase_gemela["id_experiencia"]!= $id_experiencia){
            $_info_experiencias_gemelas[$i]= $clase_gemela;
            $i++;
        }  
//        $i++;
    }
    // Obtener los grupos de cada clase gemela
    $i=0;
    while($_info_experiencias_gemelas [$i]){
        $_grupo_clase_gemela[$i]= dbExpObtenerGrupos($_info_experiencias_gemelas[$i]["id_experiencia"], $conexion);
        $i++;
        
    }
}
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

</script>
        
        <div id="bitacora_derecha">
        <?php
        //Bloque de información sobre el grupo al que pertenece el usuario y su grupo gemelo
            if ($es_estudiante && !is_null($grupo_usuario["nombre"])){
            ?>            
            <div>
                <div class="titulo_modulo_derecha"><?php echo $lang_bitacora_info_grupos?></div>                    
                <div class="cabecera_info_grupos">
                    <div class="info_grupos_ver" id="info_grupos_ver">
                        <img src="<?php echo $config_ruta_img.'flecha_c.png'?>"></img>
                        <?php echo $lang_bitacora_mi_grupo.": ".obtenerNombreGrupo($grupo_usuario["nombre"]);?>
                    </div>
                    <div class="info_grupos_ver" id="info_grupos_ocultar">
                        <img src="<?php echo $config_ruta_img.'flecha_a.png'?>">
                        <?php echo $lang_bitacora_mi_grupo.": ".obtenerNombreGrupo($grupo_usuario["nombre"]);?>
                    </div>
                </div>
                <div class="bloque_integrantes_mi_grupo" id="info_integrantes_mi_grupo">
                    <div><?php echo $lang_bitacora_integrantes_mi_grupo.': ';?></div>
                <?php
                if(!is_null($integrantes_grupo_usuario)){
                    foreach($integrantes_grupo_usuario as $datos_usuario){
                        $imagen_usuario_grupo = darFormatoImagen($datos_usuario["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
                        ?>      
                        <div class="datos_integrante">
                            <div class="integrante_img">
                                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $datos_usuario["usuario"];?>" class="img_integrantes_info_grupo" title="<?php echo $datos_usuario["usuario"];?>" >
                                    <img class="imagen_bloque_infg" src="<?php echo $imagen_usuario_grupo["imagen_usuario"];?>"/> 
                                </a>
                                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $datos_usuario["usuario"];?>" class="img_integrantes_info_grupo" title="<?php echo $datos_usuario["usuario"];?>" >
                                    <div class="nombre_integrantes_bloque_infg"> <?php echo $datos_usuario["nombre_usuario"];?></div>
                                </a>
                            </div>   
                        </div>
                <?php
                    }
                }
               ?>
                </div>                
            </div>                          
            <?php
            }
            ?>
            <div id="bloque_filtros">
                <div id="bloque_filtros_titulo" class="titulo_modulo_derecha">
                    <?php echo $lang_filtros_timeline;?>
                </div>
                <div id="lista_filtros">
                    <ul id="menu_bitacora">
                        <li id="menu_pestana_mi_clase" ><a  class="cabecera_menu" id="link_solo_post_mi_clase_cabecera" href="#"><?php echo $lang_link_filtro_mi_clase;?></a>
                            <ul class="bloque_filtro ">
                                <li class="filtro_cabecera">
                                    <?php
                                    if(!is_null($_info_experiencias[$id_clase]["nombre_profesor"])){
                                        $imagen_profesor= darFormatoImagen($_info_experiencias[$id_clase]["imagen_profesor"], $config_ruta_img_perfil, $config_ruta_img);
                                        $_info_experiencias[$id_clase]["imagen_profesor"] = $imagen_profesor["imagen_usuario"];
                                        echo "<div class=\"foto_profesor_clase\">\n\r";
                                        echo "  <a href=\"contenido_perfil_usuario_modal.php?nombre_usuario=".$_info_experiencias[$id_clase]["usuario_profesor"]."\" class=\"img_menu\" title=\"".$_info_experiencias[$id_clase]["nombre_profesor"]."\"><img src=\"".$_info_experiencias[$id_clase]["imagen_profesor"]."\" /></a>\n\r";
                                        echo "</div>\n\r";
                                        echo "<div class=\"datos_profesor_establecimiento\">\n\r";
                                        echo "<p><b>".$lang_profesor."</b>: ".$_info_experiencias[$id_clase]["nombre_profesor"]."</p>\n\r";
                                        echo "</div>\n\r";
                                        echo "<div class=\"clear\"></div>\n\r";
                                        }

                                    ?>
                                </li>
                                <li><a id="link_solo_post_mi_clase" href="#"><?php echo $lang_link_filtro_mi_clase_todos;?></a> </li>
                                <?php
                                if(!$es_observador){
                                ?>
                                    <li><a id="link_solo_mis_post" href="#" ><?php echo $lang_link_filtro_mis_posts;?></a> </li>
                                <?php
                                }
                                ?>
                                <li><a id="link_solo_post_profesor" href="#" ><?php echo $lang_link_filtro_profesor;?></a> </li>
                                <?php
                                if(!is_null($_grupos)){
                                    foreach($_grupos as $grupo) {
                                        if(!is_null($grupo_usuario) && $grupo["id"]== $grupo_usuario["id_grupo"]){
                                            //Para marcar el grupo al que pertenece el usuario
                                            echo "<li>";
                                            echo  "<a id=\"link_solo_grupo_".$grupo["id"]."\"href=\"#\"> ".$lang_link_filtro_por_grupo.$lang_function_inc_grupo." ".obtenerNombreGrupo($grupo["nombre"])."</a>";
                                            echo "</li>";
                                        }
                                        else{
                                            echo "<li>";
                                            echo  "<a id=\"link_solo_grupo_".$grupo["id"]."\"href=\"#\">".$lang_link_filtro_por_grupo.$lang_function_inc_grupo." ".obtenerNombreGrupo($grupo["nombre"])."</a>";
                                            echo "</li>";
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                        //Ver si es que existe una clase gemela asociada
                        $i=0;
                        if (!is_null($_info_experiencias_gemelas[$i]["id_experiencia"]) AND strlen($_info_experiencias_gemelas[$i]["id_experiencia"])>0) {
                            foreach ($_info_experiencias_gemelas as $clase_gemela){
//                                $n_clase = $clase_gemela["indice"];
                                echo  "<li class= \"menu_pestana_clase_gemela\"><a  class=\"cabecera_menu\" id=\"link_solo_post_clase_gemela_cabecera".$i."\" href=\"#\">".$lang_link_filtro_clase_gemela."</a>";
                                echo "  <ul class=\"bloque_filtro \">";
                                $imagen_profesor_gemelo= darFormatoImagen($clase_gemela["imagen_profesor"], $config_ruta_img_perfil, $config_ruta_img);
                                $profesor_gemelo_imagen= $imagen_profesor_gemelo["imagen_usuario"];
                                echo "<li class=\"filtro_cabecera\" >\n\r";
                                echo "<div class=\"foto_profesor_clase\">\n\r";
                                echo "  <a href=\"contenido_perfil_usuario_modal.php?nombre_usuario=".$clase_gemela["usuario_profesor"]."\" class=\"img_menu\" title=\"".$clase_gemela["nombre_profesor"]."\"><img src=\"".$profesor_gemelo_imagen."\" /></a>\n\r";
                                echo "</div>\n\r";
                                echo "<div class=\"datos_profesor_establecimiento\">\n\r";
                                echo "<p><b>".$lang_profesor."</b>: ".$clase_gemela["nombre_profesor"]."</p>\n\r";
                                echo "<p><b>".$lang_colegio."</b>: ".$clase_gemela["colegio"]. "</p>\n\r";
                                echo "<p><b>".$lang_localidad."</b>: ".$clase_gemela["localidad"]."</p>\n\r";
                                echo "</div>\n\r";
                                echo "<div class=\"clear\"></div>\n\r";

                                echo "</li> ";
                                echo "      <li><a id=\"link_solo_post_clase_gemela".$i."\" href=\"#\">".$lang_link_filtro_clase_gemela_mensajes."</a> </li>";
                                echo "      <li> <a id=\"link_solo_post_profesor_gemelo".$i."\" href=\"#\" >".$lang_link_filtro_profesor."</a> </li>";
                                if(!is_null($_grupo_clase_gemela[$i])){
                                    foreach($_grupo_clase_gemela[$i] as $grupo) {
                                        echo "<li>";
                                        echo  " <a id=\"link_solo_grupo_".$grupo["id"]."\"href=\"#\">".$lang_link_filtro_por_grupo.$lang_function_inc_grupo." ".obtenerNombreGrupo($grupo["nombre"])."</a>";
                                        echo "</li>";
                                    }
                                }
                                echo "  </ul>";
                                echo "</li>";
                                $i++;
                            }
                            
                        }
                        ?>
                    </ul>
                </div>
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
    function mostrarPostsUsuario(el_usuario){
        f_solo_usuario = el_usuario;
        f_modo_timeline_exp = -1;
        leerUltimosPosts();
    }
    function limpiarFiltro(){
        f_modo_timeline_exp = 0;
        f_etiqueta_grupo = '';
        f_etiqueta_grupo_gemelo = '';
        f_solo_usuario = '' ;
        f_id_grupo = '';
        f_id_experiencia_gemela = '';
        f_nombre_grupo = '';
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
    $(document).ready(function(){
        iniciarMenu();
        $('#info_grupos_ocultar').hide();
        $('#info_integrantes_mi_grupo').hide();      
        $('#info_grupos_ver').click(function(){
            $('#info_grupos_ver').hide(); 
            $('#info_integrantes_mi_grupo').slideDown('normal');
            $('#info_grupos_ocultar').show();        
            return false;
        });
        $('#info_grupos_ocultar').click(function(){
            $('#info_grupos_ocultar').hide(); 
            $('#info_integrantes_mi_grupo').slideUp('normal');
            $('#info_grupos_ver').show();        
            return false;
        });        
        $('#link_solo_mis_post').click(function(){
            limpiarFiltro();
            f_modo_timeline_exp = -1;
            f_solo_usuario = '<?php echo $_SESSION["klwn_usuario"];?>';
            leerUltimosPosts();
            definirURLHistorialMensajes();
            return false;
        });
        $('#link_solo_post_profesor').click(function(){
            limpiarFiltro();
            f_modo_timeline_exp = -1;
            f_solo_usuario = '<?php echo $_info_experiencias[$id_clase]["usuario_profesor"];?>';
            leerUltimosPosts();
            definirURLHistorialMensajes();
            return false;
        });
        $('#link_solo_post_mi_clase').click(function(){
            limpiarFiltro();
            f_modo_timeline_exp = 0;
            leerUltimosPosts();
            definirURLHistorialMensajes();
            return false;
        });
        $('#link_solo_post_mi_clase_cabecera').click(function(){
            limpiarFiltro();
            f_modo_timeline_exp = 0;
            leerUltimosPosts();
            definirURLHistorialMensajes();
            $('#mensajes_actividad_actual').removeClass('fondo_clase_gemela');
            $('#mensajes_actividad_actual').addClass('fondo_mi_clase');
            $('.mensajes_historial').removeClass('fondo_clase_gemela');
            return false;
        });
 
        $('.img_integrantes_info_grupo').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_perfil_usuario_titulo_ventana;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_bit_mi_clase_cd_cerrar; ?>": function() {
                    $(this).dialog("close");
                    }
                },
                close: function(ev, ui) {
                    $(this).remove();
                }
            });
            $dialog.dialog('open');
            return false;

       });
       $('.img_menu').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_perfil_usuario_titulo_ventana;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_bit_mi_clase_cd_cerrar; ?>": function() {
                    $(this).dialog("close");
                    }
                },
                close: function(ev, ui) {
                    $(this).remove();
                }
            });
            $dialog.dialog('open');
            return false;

       });
       
        <?php
        if(!is_null($_grupos)){
            foreach($_grupos as $grupo){
            ?>
            $('#link_solo_grupo_<?php echo $grupo["id"];?>').click(function(){
                limpiarFiltro();
                f_etiqueta_grupo = '<?php echo $grupo["etiqueta"];?>';
                f_etiqueta_grupo_gemelo = '<?php echo $grupo["etiqueta_gemela"];?>';
                f_id_grupo = '<?php echo $grupo["id"];?>' ;
                f_nombre_grupo = '<?php echo $lang_function_inc_grupo." ".obtenerNombreGrupo($grupo["nombre"]);?>' ;
                f_modo_timeline_exp = 3;
                leerUltimosPosts();
                definirURLHistorialMensajes();
                return false;
            });
            <?php
            }
        }
        $i=0;
        //Para las clases gemelas
        while($_info_experiencias_gemelas[$i]){
          ?>
                $('#link_solo_post_clase_gemela<?php echo $i;?>').click(function(){
                    limpiarFiltro();
                    f_modo_timeline_exp = 1;
                    f_id_experiencia_gemela = '<?php echo $_info_experiencias_gemelas[$i]["id_experiencia"];?>';
                    leerUltimosPosts();
                    definirURLHistorialMensajes();
                    return false;
                });
                 $('#link_solo_post_clase_gemela_cabecera<?php echo $i;?>').click(function(){
                    limpiarFiltro();
                    f_modo_timeline_exp = 1;
                    f_id_experiencia_gemela = '<?php echo $_info_experiencias_gemelas[$i]["id_experiencia"];?>';
                    leerUltimosPosts();
                    definirURLHistorialMensajes();
                    $('#mensajes_actividad_actual').removeClass('fondo_mi_clase');
                    $('#mensajes_actividad_actual').addClass('fondo_clase_gemela');
                    $('.mensajes_historial').addClass('fondo_clase_gemela');
                    return false;
                });
                $('#link_solo_post_profesor_gemelo<?php echo $i;?>').click(function(){
                    limpiarFiltro();
                    f_modo_timeline_exp = -1;
                    f_solo_usuario = '<?php echo $_info_experiencias_gemelas[$i]["usuario_profesor"];?>';
                    leerUltimosPosts();
                    definirURLHistorialMensajes();
                    return false;
                });
             <?php
             if(!is_null($_grupo_clase_gemela[$i])){
                foreach($_grupo_clase_gemela[$i] as $grupo) {
             ?>
                $('#link_solo_grupo_<?php echo $grupo["id"];?>').click(function(){
                    limpiarFiltro();
                    f_etiqueta_grupo = '<?php echo $grupo["etiqueta"];?>';
                    f_etiqueta_grupo_gemelo = '<?php echo $grupo["etiqueta_gemela"];?>';
                    f_id_grupo = '<?php echo $grupo["id"];?>' ;
                    f_nombre_grupo = '<?php echo $lang_function_inc_grupo." ".obtenerNombreGrupo($grupo["nombre"]);?>' ;
                    f_modo_timeline_exp = 3;
                    f_id_experiencia_gemela = '<?php echo $_info_experiencias_gemelas[$i]["id_experiencia"];?>';
                    leerUltimosPosts();
                    definirURLHistorialMensajes();
                    return false;
                });
         <?php
                }
             }
            $i++;
        }
        ?>
  });
</script>
