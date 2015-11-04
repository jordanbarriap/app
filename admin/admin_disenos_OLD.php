<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])
    )header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz . "admin/inc/admin_functions.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$agno_min = dbAdminObtenerDisenosAgnoMin($conexion);
if (count($agno_min) > 0) {
    $agno_min = $agno_min[0]['dd_fecha_creacion'];
    $agno_min = explode("-", $agno_min);
    $agno_min = $agno_min[0];
} else {
    $agno_min = 2010;
}
$agno = array();
for ($i = $agno_min; $i <= date("Y"); $i++) {
    $agno[] = $i;
}
$agno_max = $agno[count($agno) - 1];
$subsector = 0;
?>

<div class="container_16">
    <div id ="admin_contenido">
        <div class="grid_4">
            <div class="kellu_exp_menu" >
                <ul class="exp_menu">
                    <?php
                    for ($i = count($agno) - 1; $i >= 0; $i--) {
                    ?>
                        <li <?php if ($i == count($agno) - 1) {
                            echo "class='selected'";
                        } ?>>
                        <a class="enlace_menu" id="agno_<?php echo $agno[$i]; ?>">
<?php echo 'Año ' . $agno[$i]; ?>
                        </a>
                    </li>
                    <?php
                        }
                    ?>
                    </ul>
                </div>
            </div>
            <div class="grid_12">
                <div class="admin_volver_administrador">
                    <input class="admin_exp_boton_volver_inicio" type="button" value="<?php echo $lang_crear_diseno_admin_volver2; ?>" onclick="javascript: volverAdminInicio();">
                    <input class="admin_exp_boton_volver_inicio" type="button" value="Administrar archivos de ejemplo" onclick="javascript: mostrarDivArchEjemplo();">
                </div>

                <div class="admin_filtros_sector">
                    <ul class="admin_bloque_filtros_sector">
                        <li class="admin_li_filtro_sector"><a class="admin_filtro_sector" id = "admin_filtro_matematica"><?php echo $lang_crear_diseno_admin_matematica; ?></a> </li>
                        <li class="admin_li_filtro_sector admin_selected"><a class="admin_filtro_sector" id = "admin_filtro_lenguaje"><?php echo $lang_crear_diseno_admin_lenguaje; ?></a> </li>
                        <li class="admin_li_filtro_sector"><a class="admin_filtro_sector" id = "admin_filtro_historia"><?php echo $lang_crear_diseno_admin_historia; ?></a></li>
                        <li class="admin_li_filtro_sector"><a class="admin_filtro_sector" id = "admin_filtro_ciencias"><?php echo $lang_crear_diseno_admin_ciencias; ?></a> </li>
                        <li class="admin_li_filtro_sector"><a class="admin_filtro_sector" id = "admin_filtro_ingles"><?php echo $lang_crear_diseno_admin_ingles; ?></a> </li>
                        <li class="admin_li_filtro_sector"><a class="admin_filtro_sector" id = "admin_filtro_diplomado"><?php echo $lang_crear_diseno_admin_diplomado; ?></a> </li>
                        <li class="admin_li_filtro_sector"><a class="admin_filtro_sector" id = "admin_filtro_otros"><?php echo $lang_crear_diseno_admin_general; ?></a> </li>
                    </ul>
                </div>    
                <div class="clear"></div>
                </br>
                <div class="admin_exp_contenido">
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
        <div id="admin_arch_ejemplo">
            <div class="grid_4">&nbsp;</div>
            <div class="grid_12">
                <div class="admin_volver_administrador">
                    <input class="admin_exp_boton_volver_inicio" type="button" value="<?php echo $lang_crear_diseno_admin_volver2; ?>" onclick="javascript: volverAdminInicio();">
                    <input class="admin_exp_boton_volver_inicio" type="button" value="Administrar diseños didácticos" onclick="javascript: mostrarDivContenido();">
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div id="subir_archivo_admin" class="grid_16">
            </div>
            <div id="div_iframe_ap_ejm">
                <iframe  id="iframe-agregar-pauta_ejm" src="taller_dd/tdd_form_subir_archivo_ejemplo.php?error=-1" width="100%" height="220" align="center" frameborder=’0′ marginwidth=’0′ marginheight =’0′>
                </iframe>
            </div>
        </div>
    <?php
                        dbDesconectarMySQL($conexion);
    ?>
                        <script type="text/javascript">
                            agnoSeleccionado = <?php echo $agno_max; ?>;
                            sectorSeleccionado = 'SLC';

                            function adminCargarDiseno(agno, sector){
                                agnoSeleccionado = agno;
                                sectorSeleccionado = sector;
                                if(sector =='')sector = 'SLC';
                                $.get('admin/admin_disenos_obtener.php?agno='+agno+'&sector='+sector, function(data) {
                                    $('.admin_exp_contenido').html(data);
                                });
                            }
                            function eliminarArchivoEjemplo(idArchivo, nombreArchivo){

                                var $dialog = $('<div><p><br></br><?php echo $lang_nueva_actividad_elim_arch1; ?></p></div>')
                                .dialog({
                                    autoOpen: false,
                                    title: '<?php echo $lang_nueva_actividad_elim_arch2; ?>',
                                    dialogClass: 'uii-dialog',
                                    width: 500,
                                    height: 150,
                                    zIndex: 3999,
                                    modal: true,
                                    close: function(ev, ui) {
                                        $(this).remove();
                                    },
                                    buttons: {
                                        "<?php echo $lang_mis_disenos_cancelar; ?>": function() {
                                            $(this).dialog("close");
                                        },
                                        "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                                            eliminarArchivoEjemploOK(idArchivo, nombreArchivo);
                                            $(this).dialog("close");
                                        }
                                    }
                                });
                                $dialog.dialog('open');
                                return false;
                            }
                            function eliminarArchivoEjemploOK(idArchivo, nombreArchivo){
                                $.get('./taller_dd/tdd_eliminarArchivoEjemplo.php?id_archivo='+idArchivo+"&nombre_archivo="+nombreArchivo, function(data) {
                                    actualizarArchivosEjemplo();
                                });
                            }
                            function mostrarDivArchEjemplo(){
                                actualizarArchivosEjemplo();
                                $('#admin_arch_ejemplo').show();
                                $('#admin_contenido').hide();
                            }
                            function mostrarDivContenido(){
                                $('#admin_contenido').show();
                                $('#admin_arch_ejemplo').hide();
                            }
                            function actualizarArchivosEjemplo(){
                                $.get('./taller_dd/tdd_obtenerArchivosEjemplo.php', function(data) {
                                    $('#subir_archivo_admin').html(data);
                                });
                            }
                            function volverAdminInicio(){
                                $.get('admin/admin_inicio.php?', function(data) {
                                    $('.admin_contenido').html(data);
                                });
                            }
                            function volverAdminDiseno(){
                                $.get('admin/admin_disenos.php', function(data) {
                                    $('#admin_contenido').html(data);
                                });
                            }
                            $(document).ready(function(){
                                $('#admin_arch_ejemplo').hide();
                                adminCargarDiseno(<?php echo $agno_max; ?>, 'SLC');

                                $('#admin_filtro_matematica').click(function() {
                                    var sector ='SMT';
                                    adminCargarDiseno(agnoSeleccionado, sector)
                                });
                                $('#admin_filtro_lenguaje').click(function() {
                                    var sector ='SLC';
                                    adminCargarDiseno(agnoSeleccionado, sector)
                                });
                                $('#admin_filtro_historia').click(function() {
                                    var sector='SHG';
                                    adminCargarDiseno(agnoSeleccionado, sector)
                                });
                                $('#admin_filtro_ciencias').click(function() {
                                    var sector='SCS';
                                    adminCargarDiseno(agnoSeleccionado, sector)
                                });
                                $('#admin_filtro_ingles').click(function() {
                                    var sector="SIE";
                                    adminCargarDiseno(agnoSeleccionado, sector)
                                });
                                $('#admin_filtro_diplomado').click(function() {
                                    var sector="SD";
                                    adminCargarDiseno(agnoSeleccionado, sector)
                                });
                                $('#admin_filtro_otros').click(function() {
                                    var sector="SG";
                                    adminCargarDiseno(agnoSeleccionado, sector)
                                });

                                $('#admin_enlace_exp').click(function() {
                                    var element = $(this);
                                    var I = element.attr("name");
                                    irAdminExperiencias(I);

                                });
                                $(".admin_bloque_filtros_sector a").click(function(){
                                    $(this).parent().addClass('admin_selected').
                                        siblings().removeClass('admin_selected');
                                });
                                $(".exp_menu a").click(function(){
                                    $(this).parent().addClass('selected').
                                        siblings().removeClass('selected');
                                });

                                $('.admin_exp_guardar').hide();
                                $('.admin_estudiante_guardar').hide();
                                $('#admin_general_titulo_a').hide();
                                $('#admin_general_c').hide();
                                $('#admin_colaboradores_titulo_a').hide();
                                $('#admin_colaboradores_c').hide();
                                $('#admin_estudiantes_titulo_a').hide();
                                $('#admin_estudiantes_c').hide();
                                $('#admin_general_titulo_c').click(function(){
                                    $('#admin_general_c').slideDown();
                                    $('#admin_general_titulo_c').hide();
                                    $('#admin_general_titulo_a').show();
                                    $('#admin_colaboradores_c').slideUp();
                                    $('#admin_colaboradores_titulo_a').hide();
                                    $('#admin_colaboradores_titulo_c').show();
                                    $('#admin_estudiantes_c').slideUp();
                                    $('#admin_estudiantes_titulo_a').hide();
                                    $('#admin_estudiantes_titulo_c').show();
                                });
                                $('#admin_general_titulo_a').click(function(){
                                    $('#admin_general_c').slideUp();
                                    $('#admin_general_titulo_a').hide();
                                    $('#admin_general_titulo_c').show();
                                });
                                $('#admin_colaboradores_titulo_c').click(function(){
                                    $('#admin_colaboradores_c').slideDown();
                                    $('#admin_colaboradores_titulo_c').hide();
                                    $('#admin_colaboradores_titulo_a').show();
                                    $('#admin_estudiantes_c').slideUp();
                                    $('#admin_estudiantes_titulo_a').hide();
                                    $('#admin_estudiantes_titulo_c').show();
                                    $('#admin_general_c').slideUp();
                                    $('#admin_general_titulo_a').hide();
                                    $('#admin_general_titulo_c').show();
                                });
                                $('#admin_colaboradores_titulo_a').click(function(){
                                    $('#admin_colaboradores_c').slideUp();
                                    $('#admin_colaboradores_titulo_a').hide();
                                    $('#admin_colaboradores_titulo_c').show();
                                });
                                $('#admin_estudiantes_titulo_c').click(function(){
                                    $('#admin_estudiantes_c').slideDown();
                                    $('#admin_estudiantes_titulo_c').hide();
                                    $('#admin_estudiantes_titulo_a').show();
                                    $('#admin_general_c').slideUp();
                                    $('#admin_general_titulo_a').hide();
                                    $('#admin_general_titulo_c').show();
                                    $('#admin_colaboradores_c').slideUp();
                                    $('#admin_colaboradores_titulo_a').hide();
                                    $('#admin_colaboradores_titulo_c').show();
                                });
                                $('#admin_estudiantes_titulo_a').click(function(){
                                    $('#admin_estudiantes_c').slideUp();
                                    $('#admin_estudiantes_titulo_a').hide();
                                    $('#admin_estudiantes_titulo_c').show();

                                });
<?php
                        for ($i = count($agno) - 1; $i >= 0; $i--) {
?>
                                                    $('#<?php echo "agno_" . $agno[$i]; ?>').click(function(){
                                                        url = 'admin/admin_disenos_obtener.php?agno=<?php echo $agno[$i]; ?>&sector='+sectorSeleccionado;
                                                        $.get(url, function(data) {
                                                            agnoSeleccionado = <?php echo $agno[$i]; ?>;
                                                            $('.admin_exp_contenido').html(data);
                                                        });
                                                    });
<?php
                        }
?>
            
                });
    </script>


