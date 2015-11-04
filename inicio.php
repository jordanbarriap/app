<?php
/**
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Katherine Inalef- Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))
    header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

$id_usuario = $_SESSION["klwn_id_usuario"];

/* Distinguir profesor, estudiante y nuevo usuario */
$es_profesor_o_col = ($_SESSION["klwn_inscribe_diseno"] == 1);
?>
<div class="container_16">
    <div class="grid_12">
        <div class=" inicio_izquierda">
            <div id="inicio_info_usuario"> 
            </div>
            <br/>
            <div class="clear"></div> 
            <ul class="inicio_menu">
                <li class="selected"><a id="inicio_mis_exp" name="Mis Experiencias En Curso"><?php echo $lang_menu_mis_exp_en_curso; ?></a></li>
                <li><a id="inicio_mis_exp_fin" name="Mis Experiencias Finalizadas"><?php echo $lang_menu_mis_exp_en_finalizadas; ?></a></li>

                <?php
                if ($es_profesor_o_col) {
                    ?>
                    <li><a id="inicio_ini_exp" name="Iniciar Nueva Experiencia"><?php echo $lang_menu_iniciar_nueva_exp; ?></a></li>
                    <li><a id="inicio_mi_muro" name="Mi Muro"><?php echo $lang_menu_muro; ?></a></li>
<?php } ?>
                <li><a id="inicio_edit_perfil" name="Editar Mi Perfil"><?php echo $lang_menu_editar_perfil; ?></a></li>
            </ul>
            <?php if ($_SESSION["klwn_administrador"] == 3) { ?>
                <a id="concurso" href="ingresar.php"></a>
<?php } ?>
        </div>
        <div class="inicio_bloque_central">
        </div>        
    </div>
    <div class="grid_4">
        <div class="inicio_derecha">
            <?php
            if ($es_profesor_o_col) {
                ?>
                <div class="bloque_lo_ultimo_muro">
                    <div class="inicio_titulo_derecha">
    <?php echo $lang_lo_ultimo_kellu_muro; ?>
                    </div>
                    <div id="inicio_bloque_lo_ultimo_muro"></div>

                </div>
            </div>
            <?php
        }
        ?>
        <div class="bloque_lo_ultimo_bitacora">
            <div class="inicio_titulo_derecha">
<?php echo $lang_lo_ultimo_bitacora; ?>
            </div>
            <div id="inicio_bloque_lo_ultimo_bitacora"></div> 
        </div>          
        <div id="inicio_bloque_noticias">
            <div class="inicio_titulo_derecha">
<?php echo $lang_noticias_kelluwen; ?>
            </div>
            <div id="inicio_noticias">    
            </div>
        </div>
        <?php if ($es_profesor_o_col) {
                ?>
        <div class="inicio_titulo_derecha">
<?php echo $lang_inicio_comunidad_kellu ?>
        </div>
        <div id="inicio_bloque_comunidad">
        </div>
<?php }?>

    </div>
    <div class="grid_16">

    </div>
    <script type="text/javascript">

        function cargarDatosUsuario(){
            $.get('inicio_datos_usuario.php', function(data) {
                $('#inicio_info_usuario').html(data);
            });
        }
        function cargarLoUltimoMuro(){
            $.get("inicio_lo_ultimo.php?tipo=1", function(data) {
                $('#inicio_bloque_lo_ultimo_muro').html(data);
            });
        }
        function cargarLoUltimoBitacora(){
            $.get("inicio_lo_ultimo.php?tipo=2", function(data) {
                $('#inicio_bloque_lo_ultimo_bitacora').html(data);
            });
        }
        function cargarNoticias(){
            $.get("inicio_noticias.php", function(data) {
                $('#inicio_noticias').html(data);
            });
        }
        function cargarCentro(){
            $.get('exp_lista_experiencias.php?estado=1', function(data) {
                $('.inicio_bloque_central').html(data);
            });
        }
        function cargarComunidad(){
            $.get('inicio_comunidad.php', function(data) {
                $('#inicio_bloque_comunidad').html(data);
            })
        }
        

        $(document).ready(function(){

            var $inicio_tabes = $('#tabs2').tabs();

            $('.bloque_lo_ultimo_muro').hide();
            $('.bloque_lo_ultimo_bitacora').hide();
            
            cargarDatosUsuario();
            cargarCentro();
            cargarLoUltimoMuro();
            cargarLoUltimoBitacora();
            cargarNoticias();
            cargarComunidad();
        
            $('#inicio_editar_perfil').click(function(){
                $.get('modificar_perfil.php', function(data) {
                    $('.inicio_bloque_central').html(data);
                });
            });

            $('#inicio_mis_exp').click(function(){
                $.get('exp_lista_experiencias.php?estado=1', function(data) {
                    $('.inicio_bloque_central').html(data);
                });
            });
            $('#inicio_mis_exp_fin').click(function(){
                $.get('exp_lista_experiencias.php?estado=2', function(data) {
                    $('.inicio_bloque_central').html(data);
                });
            });
            $('#inicio_mi_muro').click(function(){
                $.get('mural_usuario.php?nombre_usuario=<?php echo $_SESSION["klwn_usuario"]; ?>', function(data) {
                    $('.inicio_bloque_central').html(data);
                });
            });
            $('#inicio_ini_exp').click(function(){
                $.get('inscribir_diseno.php', function(data) {
                    $('.inicio_bloque_central').html(data);
                });
            });
            $('#inicio_edit_perfil').click(function(){
                $.get('modificar_perfil.php', function(data) {
                    $('.inicio_bloque_central').html(data);
                });
            });
            $(".inicio_menu a").click(function(){
                $(this).parent().addClass('selected').
                    siblings().removeClass('selected');
            });

            $('#concurso').click(function() {
                if(tabTallerActivo){ clearTimeout(timeoutComentario); }
                $inicio_tabes.tabs('select', '#Concurso');
                $('#tab_admin').hide();
                $('#tab_concurso').show();
                $('#tab_inicio').hide();
                $('#tab_experiencias').hide();
                $('#tab_disenos').hide();
                $('#tab_inscribir').hide();
                $('#tab_taller').hide();
                return false;
            });
            
        });

    </script>
