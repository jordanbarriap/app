<?php
/**
 * Sección que permite Configurar las Experiencias Didácticas, siguiendo los siguientes pasos:
 * Paso 1: Muestra información referente a los grupos de la experiencia didáctica y
 * permite modificar atributos relevantes a los grupos.
 *
 *
 * Utiliza las funciones:
 *      dbObtenerEtiqGemExpDidac
 *      dbObtenerEtiqExpDidac
 *      dbExpObtenerEstudiantesSinAsignar
 *      dbExpGruposExperiencia
 *      dbExpObtenerEstudiantesAsignado
 *      dbExpObtenerInfo
 *      dbObtenerClasesGem
 *      dbExpObtenerEstudiantesPorGrupo
 *
 * Los parámetros necesarios pasados son:
 * $_REQUEST["codexpi"] : identificador único de la experiencia didáctica
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  José Carrasco - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");

$titulo_pagina = $lang_sufijo_titulo_paginas . $lang_config_dd;
$id_experiencia_didac = $_REQUEST["codexpi"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

//Obtiene etiqueta de la experiencia
$etiqueta_experiencia = dbObtenerEtiqExpDidac($id_experiencia_didac, $conexion);

//Obtener los estudiantes registrados en la experiencia didáctica
$_estudiantes_registrados = dbExpObtenerEstudiantesRegistrados($id_experiencia_didac, $conexion);
$n_estudiantes_registrados = count($_estudiantes_registrados);

//Obtener los grupos registrados en la experiencia didáctica
$_grupos = dbExpGruposExperiencia($id_experiencia_didac, $conexion);
$n_grupos = count($_grupos);

//Mismo boton con dos funciones diferentes: CREAR y MODIFICAR N° DE GRUPOS
$texto_boton = $lang_conf_diseno_crear;
$texto_grupos = $lang_conf_diseno_num_grupos_crear.": ";
$clase_activo = "bt_asignar_inactivo";

if ($n_grupos > 0) {
    //Si los grupos ya existen, cambia boton CREAR por MODIFICAR N° DE GRUPOS
    $texto_boton = $lang_conf_diseno_modificar;
    $texto_grupos = $lang_conf_diseno_modificar_grupos.": ";
    $clase_activo = "bt_asignar_activo";
    $grupos_creados = 1;
}
//Obtener datos de Experiencia Didáctica
$datos_exp = dbExpObtenerInfo($id_experiencia_didac, $conexion);

$esta_finalizada = ($datos_exp["fecha_termino"] != '')?"1":"0";

dbDesconectarMySQL($conexion);
?>

<!-- PASO 2 - CREACIÓN DE GRUPOS -->
<?php
if ($esta_finalizada) {
    $visible = "ocultar";
} else {
    $visible = "";
} ?>
    <div id="botones_grupos" class="<?php echo $visible; ?>">
        <span id="txt_grupos"><?php echo $texto_grupos; ?></span>
<?php
    if ($n_grupos > 0) {
?>
        <input type="text" id="num_grupos" value="<?php echo $n_grupos ?>" />
        <?php } else { ?>
        <input type="text" id="num_grupos"/>
        <? } ?>
        <button  id="boton_crear" title="<?php echo $texto_boton; ?>"><?php echo $texto_boton; ?></button>
    </div>

    <div id="contenedor_resumen">
        <p class="intro_etapas"><?php echo $lang_conf_diseno_asignar_est; ?><br>
        <?php echo $lang_conf_diseno_arrastra_mouse; ?>
        </p>
        <div id="resumen_estudiantes" >
            <table>
                <tr>
                    <td><label class="titulo_resumen"><?php echo $lang_conf_diseno_est_registrados; ?></label><br>
                        <span id="n_estudiantes_registrados" ></span>
                    </td>
                    <td><label class="titulo_resumen"><?php echo $lang_conf_diseno_est_asignados; ?></label><br>
                        <span id="n_estudiantes_asignados"></span>
                    </td>
                    <td class="sin_borde"><label class="titulo_resumen"><?php echo $lang_conf_diseno_est_sin_asignar; ?></label><br>
                        <span id="n_estudiantes_sin_asignar"></span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="clear"></div>
    <div id="grupos_creados">
        <div id="asignacion_grupos"></div>
    </div>

<!--TODOS LOS DIVS DE MENSAJES: ERROR - EXITO - ADVERTENCIAS -->
<div id="modal_msges_conf">
    <p></p>
</div>
<div id="modal_adv_conf">
    <p></p>
</div>

<script type="text/javascript">
    var grupos_creados = 0;
    
    $(document).ready(function(){
        
        cargarAsignacionEstudiantes();
        detenerBitacoraNM();
        detenerBitacoraCompartidaNM();
        $("#num_grupos").spinner({max: 35, min: 1});

        $("#modal_msges_conf").dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            buttons: {
                '<?php echo $lang_conf_diseno_aceptar; ?>': function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        $( "#modal_adv_conf" ).dialog({
            modal: true,
            autoOpen: false,
            height: 180,
            width:300,
            resizable: false,
            buttons: {
                '<?php echo $lang_conf_diseno_aceptar; ?>': function() {
                    $( this ).dialog( "close" );
                    crearGrupos();
                },
                '<?php echo $lang_conf_diseno_cancelar; ?>': function(){
                    $(this).dialog("close");

                }
            }
        });

    });
    function cargarAsignacionEstudiantes(){
        $.get(
        'conf_grupos.php?id_exp=<?php echo $id_experiencia_didac; ?>',
        function(data){
            $('#asignacion_grupos').html(data);
            return false;
        }
    );
        return false;
    }
    function crearGrupos(){
        var id_exp =  <?php echo $id_experiencia_didac; ?>;
        var ngrupos = $('#num_grupos').val();
        //Crea los grupos
        url = 'conf_crear_grupos.php?codexpi='+id_exp+"&ngru="+ngrupos;
        $.post(url,function(data){
            data = parseInt(data);
            if (data == "1"){
                cargarAsignacionEstudiantes();
                $("#modal_msges_conf").dialog("option","title", "<?php echo $lang_exito; ?>");
                $("#modal_msges_conf p").attr("class","msg_exito");
                $("#modal_msges_conf p").html("<?php echo $lang_conf_diseno_grupos_correcto; ?>");
                $("#modal_msges_conf").dialog("open");
                $("#txt_grupos").html(" <?php echo $lang_conf_diseno_modificar_grupos; ?>: ");
                $("#boton_asignar").attr("class","bt_asignar_activo");
                $("#boton_crear").attr("title","<?php echo $lang_conf_diseno_modificar; ?>");
                $("#boton_crear").html("<?php echo $lang_conf_diseno_modificar; ?>");
                $("#asignacion_grupos").val($("#n_grupos").html());
                grupos_creados = 1;
            }else{
                cargarAsignacionEstudiantes();
                $("#modal_msges_conf").dialog("option", "title", "<?php echo $lang_error; ?>");
                $("#modal_msges_conf p").attr("class","msg_error");
                $("#modal_msges_conf p").html("<?php echo $lang_conf_diseno_problema_grupo; ?>");
                $("#modal_msges_conf").dialog("open");
            }
        });
    }
    /*Se verifica si está modificando el número de grupos, se le entrega una advertencia*/
    $('#boton_crear').click(function() {
        var grupos="<?php echo $grupos_creados ?>";
        if(grupos_creados>0 ||grupos > 0 ){
            $("#modal_adv_conf").dialog("option", "title", "<?php echo $lang_advertencia; ?>");
            $("#modal_adv_conf p").attr("class","msg_alerta");
            $("#modal_adv_conf p").html("<?php echo $lang_conf_diseno_modifica_cantidad_grupos; ?>");
            $("#modal_adv_conf").dialog('open');
        }else{
            crearGrupos();
        }
    });
</script>

<?php
        require_once($ruta_raiz . "inc/footer.inc.php");
?>