<?php
/**
 *Muestra el menú de la derecha de la Bitácora, con las experiencias participantes y las opciones de filtrado
 * Los parametros de entrada son:
 * $_REQUEST["codeexp]: identificador de la experiencia desde la cual se está visualizando la Bitácora compartida
 * $_REQUEST["et_gemela"];
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
$etiqueta_gemela        = "";
if (!is_null($_REQUEST["et_gemela"]) AND strlen($_REQUEST["et_gemela"])>0){
    $etiqueta_gemela = $_REQUEST["et_gemela"];
}
$conexion           = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$_info_experiencias = dbBitacoraObtenerInfoExperiencias($etiqueta_gemela, $conexion);

dbDesconectarMySQL($conexion);
?>
        <div id="bitacora_derecha">
            <div>
                <div class="titulo_modulo_derecha"><?php echo $lang_bit_compartida_cd_quienes_participan?></div>                    
                <div class="participantes_bitacora">
                    <?php
                    foreach ($_info_experiencias as $info_experiencia){
                    ?>
                    <p> <?php echo $info_experiencia["curso"].", ".$info_experiencia["colegio"];?></p>
                    <?php
                    }
                    ?>
                </div>
            </div>   
<!--            <div>
                <div class="titulo_modulo_derecha"><?php echo $lang_bit_compartida_cd_ordenar?></div>                    
                    <div class="orden_msj_compartida" id="orden_msj_reciente"><a><?php echo $lang_bit_compartida_cd_mas_recientes; ?></a></div>
                    <div class="orden_msj_compartida" id="orden_msj_respondidos"><a><?php echo $lang_bit_compartida_cd_mas_respondidos; ?></a></div>
                    <div class="orden_msj_compartida" id="orden_msj_megusta"><a><?php echo $lang_bit_compartida_cd_mas_mg; ?></a></div>              
            </div>   -->
            <div id="bloque_filtros">
                <div id="bloque_filtros_titulo" class="titulo_modulo_derecha">
                    <?php echo $lang_filtros_timeline;?>
                </div>
                <div class="filtros_compartida"><a id="enlace_todos" href="#" ><?php echo $lang_bit_compartida_cd_todos;?></a>  </div>
                <div class="filtros_compartida"><a id="enlace_mi_clase" href="#" ><?php echo $lang_bit_compartida_cd_msj_mi_clase;?></a> </div>
                <div class="filtros_compartida"><a id="enlace_mios" href="#"><?php echo $lang_bit_compartida_cd_mis_msj?></a></div>
                 
            </div>
        </div>


<script type="text/javascript">
    function limpiarFiltro(){
        f_modo_timeline_exp = 0;
        f_solo_usuario = '' ;
    }
    $(document).ready(function(){ 
        $('#enlace_mios').click(function(){
            limpiarFiltro();
            f_modo_timeline_exp = 2;
            f_solo_usuario = '<?php echo $_SESSION["klwn_usuario"];?>';
            leerUltimosPostsCompartida();
            return false;
        });
        $('#enlace_mi_clase').click(function(){
            limpiarFiltro();
            f_modo_timeline_exp = 1;
            leerUltimosPostsCompartida();
            return false;
        });
        $('#enlace_todos').click(function(){
            limpiarFiltro();
            f_modo_timeline_exp = 0;
            leerUltimosPostsCompartida();
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
                    "<?php echo $lang_bit_compartida_cd_cerrar; ?>": function() {
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
  });
</script>
