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
 * */


if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:../ingresar.php");

$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$idDiseno=-1;
$fcd_nombre= '';
$fcd_sector= -1;
$fcd_nivel= -1;
$fcd_descripcion= '';
$fcd_objetivos_curriculares= '';
$fcd_objetivos_transversales= '';
$fcd_contenidos= '';
$fcd_descripcion_etapa1= '';
$fcd_descripcion_etapa2= '';
$fcd_descripcion_etapa3= '';
$fcd_web_20= -1;
$fcd_id_autor= $_SESSION["klwn_id_usuario"];

desbloquearTodoFuncion($fcd_id_autor, $conexion);

$_herramientas_web = obtenerHerramientasWebFuncion($conexion);

$h_web = array();
for($i=0; $i< count($_herramientas_web); $i++){
    $h_web[$_herramientas_web[$i]['hw_id_herramienta']] = $_herramientas_web[$i]['hw_enlace'];  
}

$_etapas = array();
$_actividades_etapa1 = array();
$_actividades_etapa2 = array();
$_actividades_etapa3 = array();

if(isset($_GET['idDiseno'])){
    $idDiseno                       = $_GET['idDiseno'];
    $_diseno= obtenerDisenoFuncion($idDiseno, $conexion);

    $fcd_id_autor                   = $_diseno[0]['dd_id_autor'];
    $fcd_nombre                     = $_diseno[0]['dd_nombre'];
    $fcd_sector                     = $_diseno[0]['dd_subsector'];
    $fcd_nivel                      = $_diseno[0]['dd_nivel'];
    $fcd_descripcion                = $_diseno[0]['dd_descripcion'];
    $fcd_objetivos_curriculares     = $_diseno[0]['dd_objetivos_curriculares'];
    $fcd_objetivos_transversales    = $_diseno[0]['dd_objetivos_transversales'];
    $fcd_contenidos                 = $_diseno[0]['dd_contenidos'];
    $fcd_descripcion_etapa1         = $_diseno[0]['dd_descripcion_e1'];
    $fcd_descripcion_etapa2         = $_diseno[0]['dd_descripcion_e2'];
    $fcd_descripcion_etapa3         = $_diseno[0]['dd_descripcion_e3'];
    $fcd_web_20                     = $_diseno[0]['hd_id_herramienta'];
    
    $_etapas = obtenerEtapasFuncion($idDiseno, $conexion);
    $_actividades_etapa1 = obtenerActividadesPorEtapaFuncion($_etapas[0]['e_id_etapa'], $conexion);
    $_actividades_etapa2 = obtenerActividadesPorEtapaFuncion($_etapas[1]['e_id_etapa'], $conexion);
    $_actividades_etapa3 = obtenerActividadesPorEtapaFuncion($_etapas[2]['e_id_etapa'], $conexion);
    
    $maxActividades = count($_actividades_etapa1);
    if(count($_actividades_etapa2) > $maxActividades) $maxActividades = count($_actividades_etapa2);
    if(count($_actividades_etapa3) > $maxActividades) $maxActividades = count($_actividades_etapa3);
    
}
?>
<div class="admin_volver_experiencias">
    <input  class="admin_boton_volver" type="button" onclick="javascript: volverAdminDiseno();" value="<?php echo $lang_crear_diseno_admin_volver; ?>"><br>
</div>
<div class="container_16">
    <div class="grid_16">
        <div class=" inicio_izquierda inicio_izquierda_tdd">
            <div id="colaboracion_admin">
                <div id="ultimos_cambios">
                </div>
                <div id="participantes_admin">
                </div>
                <div id="invitaciones_admin">
                </div>
            </div>
        </div>
        <div id="inicio_bloque_central_tdd">
        <div id="menu_editar_dd">
    <ul id="ul_menu_editar_dd">
        <li class="uno sel" ><a id="editar_dd_admin" ><?php echo $lang_crear_nuevo_diseno_titulo_2; ?></a> </li>
        <li class="dos"><a id="actividades_editar_dd_admin"><?php echo $lang_crear_nuevo_diseno_etapas_titulo; ?> </a> </li>
    </ul>
</div>
<div id="descargas" style="padding-right: 50px;">
    <a id="descargaZip" href="./taller_dd/tdd_crearZip.php?idDiseno=<?php echo $idDiseno; ?>" target="_black"><?php echo $lang_crear_diseno_descarga_zip; ?></a>
    <a id="descargaWord" href="./taller_dd/tdd_crearWord.php?idDiseno=<?php echo $idDiseno; ?>" target="_black"><?php echo $lang_crear_diseno_descarga_word; ?></a>
</div>
<div id="crear_diseno_admin">
    <div id="datos_diseno_admin">
        <div id="formulario_crear_diseno">
            <div id="ayuda_di_admin"><div id="ayuda_content_di_admin"></div></div>
            <div id="ayuda_d_admin" onClick="mostrarDivAyuda();"></div>
            <form id="form_crear_diseno_admin" method="post" action="" accept-charset="UTF-8">
                <div id="caja_form_crear_diseno">

                    <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcd_id_autor_admin"  name="fcd_id_autor" value="<?php echo $fcd_id_autor; ?>"/>
                    <input tabindex="1" type="hidden" maxlenght="20" size="20" id="fcd_id_diseno_admin"  name="fcd_id_diseno" value="<?php echo $idDiseno; ?>"/>
                    <table>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_nombre . " :"; ?></label></td>
                            <td><input tabindex="2" type="text" maxlenght="40" size="20" id="nicEdit-fcd_nombre_admin"  name="fcd_nombre" value="<?php echo $fcd_nombre; ?>"/></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_sector . " :"; ?></label></td>
                            <td><select tabindex="3"  id="fcd_sector_admin" name="fcd_sector" size="1" onChange="actualizarInvitaciones(<?php echo $idDiseno; ?>);">
                                    <?php
                                    for ($i = 0; $i < count($_sectores); $i++) {
                                        if (strcmp($_sectores[$i]['valor'], $fcd_sector) == 0) {
                                            echo '<option value="' . $_sectores[$i]['valor'] . '" selected>' . $_sectores[$i]['nombre'] . '</option>';
                                        } else {
                                            echo '<option value="' . $_sectores[$i]['valor'] . '">' . $_sectores[$i]['nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><label ><?php echo $lang_crear_nuevo_diseno_nivel . " :"; ?></label></td>
                            <td><select tabindex="4"  id="fcd_nivel_admin" name="fcd_nivel" size="1">
                                    <?php
                                    for ($i = 0; $i < count($_niveles); $i++) {
                                        if (strcmp($_niveles[$i], $fcd_nivel) == 0) {
                                            echo '<option value="' . $_niveles[$i] . '" selected>' . $_niveles[$i] . '</option>';
                                        } else {
                                            echo '<option value="' . $_niveles[$i] . '">' . $_niveles[$i] . '</option>';
                                        }
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_descripcion . " :"; ?></label></td>
                            <td><textarea tabindex="5" id="fcd_descripcion_admin" name="fcd_descripcion"><?php echo $fcd_descripcion; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_obj_curriculares . " :"; ?></label></td>
                            <td><textarea tabindex="6" id="fcd_objetivos_curriculares_admin" name="fcd_objetivos_curriculares" rows="3" cols="1"><?php echo $fcd_objetivos_curriculares; ?></textarea>
                                <a class="abrirObjetivos" title="Ver Objetivos Curriculares" onClick="abrirObjetivosCurriculares();"><img src="./taller_dd/img/pdf.png" class="icono_pdfObjCurr"></img></a></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_obj_transversales . " :"; ?></label></td>
                            <td><textarea tabindex="7" id="fcd_objetivos_transversales_admin" name="fcd_objetivos_transversales" rows="3" cols="1"><?php echo $fcd_objetivos_transversales; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_contenidos . " :"; ?></label></td>
                            <td><textarea tabindex="8" id="fcd_contenidos_admin" name="fcd_contenidos" rows="2" cols="1"><?php echo $fcd_contenidos; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa1 . " :"; ?></label></td>
                            <td><textarea tabindex="9" id="fcd_descripcion_etapa1_admin" name="fcd_descripcion_etapa1" rows="3" cols="1"><?php echo $fcd_descripcion_etapa1; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa2 . " :"; ?></label></td>
                            <td><textarea tabindex="10" id="fcd_descripcion_etapa2_admin" name="fcd_descripcion_etapa2" rows="3" cols="1"><?php echo $fcd_descripcion_etapa2; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa3 . " :"; ?></label></td>
                            <td><textarea tabindex="11" id="fcd_descripcion_etapa3_admin" name="fcd_descripcion_etapa3" rows="3" cols="1"><?php echo $fcd_descripcion_etapa3; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_web_20 . " :"; ?></label></td>
                            <td><select tabindex="12"  id="fcd_web_20_admin" name="fcd_web_20" size="1">
                                    <?php
                                    for ($i = 0; $i < count($_herramientas_web); $i++) {
                                        if ($_herramientas_web[$i]['hw_id_herramienta'] == $fcd_web_20) {
                                            echo '<option value="' . $_herramientas_web[$i]['hw_id_herramienta'] . '" selected>' . $_herramientas_web[$i]['hw_nombre'] . '</option>';
                                        } else {
                                            echo '<option value="' . $_herramientas_web[$i]['hw_id_herramienta'] . '">' . $_herramientas_web[$i]['hw_nombre'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select></td>
                        </tr>
                    </table>
                    <input id="editar_diseno_admin" class="editar_diseno" type="button" onclick="editarDiseno();" value="<?php echo $lang_crear_diseno_editar; ?>" name="editar">
                    <input tabindex="13" id="fcd_submit_admin" class="fcd_submit" type="submit" value="<?php echo $lang_crear_diseno_guardar; ?>">
                    <div class="clear"></div>
                    <div id="error_form_crear_diseno_admin"></div>
                    <div class="clear"></div>
                </div>
            </form>
            <div id="mascara_admin" style="left: 425px;"></div>
        </div>
    </div>
</div>
<input type="hidden" id="sector_oculto" name="sector_oculto"></input>
<div class="separador_vertical"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var end = 20;
    var end2 = 10;
    var estado = 0;
    var primerClickEtapa = true;
    var id_diseno_actual= <?php echo $idDiseno; ?>;
    var actividad_laboratorio = <?php echo $actividad_laboratorio; ?>;
    var $dialog;
    var input_ayuda_actual= '';
    var end_comentario=5;
    var ver_mas_comentario = 5;
    var timeoutComentario = 0;
    var objCurriculares = new Array();
    var urlObjCurriculares = '';
    var maxActividades = 2;
    
    objCurriculares['SLC'] = '<?php echo $rutaObjCurriculares_SLC; ?>';
    objCurriculares['SHG'] = '<?php echo $rutaObjCurriculares_SHG; ?>';
    objCurriculares['SCS'] = '<?php echo $rutaObjCurriculares_SCS; ?>';
    objCurriculares['SIE'] = '<?php echo $rutaObjCurriculares_SIE; ?>';
    objCurriculares['SG'] = '<?php echo $rutaObjCurriculares_SG; ?>';
   
    function descargaWord(){
        $.get('./taller_dd/tdd_crearWord.php?idDiseno='+id_diseno_actual, function(data) {
             if(data != '' && data != 'false'){
                 window.open('./'+data,'_newtab');
             }
        });
    }
   function cerrarAyuda(){
        //document.getElementById('ayuda').style.visibility = 'hidden';
    }
    function buscarColaboradores(idDiseno,texto){
        valor=$('#fbc_nombre').val();
        if(valor !=""){
        $.get('./taller_dd/tdd_buscar_colaboradores.php?nombre='+valor+'&id_diseno='+idDiseno+'&texto='+texto,function(data) {
           $('#vermas_invitar').hide();
           $('#tdd_colaboradores').html(data);

        });
        }
    }
    function cargarCentro(){

        $.get('./taller_dd/tdd_mis_disenos.php', function(data) {
            $('.inicio_bloque_central_crear_diseno').html(data);
        });
        $('#crear_disenod').hide();
        $('#colaboracion_admin').hide();
    }
    function modificarDiseno(idDiseno){
        $('#crear_disenod').parent().addClass('selected').
            siblings().removeClass('selected');

        $.get('./taller_dd/tdd_form_crear_diseno.php?idDiseno='+idDiseno, function(data) {
            $('.inicio_bloque_central_crear_diseno').html(data);
        });


    }
    function actualizarMisDisenos(){
        $('#mis_disenos').parent().addClass('selected').
            siblings().removeClass('selected');

        $.get('./taller_dd/tdd_mis_disenos.php', function(data) {
            $('.inicio_bloque_central_crear_diseno').html(data);
        });
    }
    function cargarPerfilTaller(nombre){
    /*
        scroll(0,0);
        var $dialog = $('<div></div>')
            .load("contenido_perfil_usuario_modal.php?nombre_usuario="+nombre)
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_perfil_usuario_titulo_ventana; ?>',
                width: 800,
                height: 600,
                position: [parseInt((ancho(window.document.body.clientWidth)-990)/2),70],
                modal: true,
                buttons: {
                    "Cerrar": function() {
                        $(this).dialog("close");
                    }
                },
                close: function(ev, ui) {
                    $(this).remove();
                }
            });
        $dialog.dialog('open');
        */
        return false;
    }
    function actualizarParticipantes(idDiseno){

        $.get('./taller_dd/tdd_obtenerParticipantes.php?id_diseno='+idDiseno, function(data) {
            $('#participantes_admin').html(data);
        });
    }
    function verMasInvitaciones(idDiseno){
        end = end+end2;
        actualizarInvitaciones(idDiseno);
    }
    function verMasCambios(idDiseno){
        scroll(0,0);
        $dialog_cambios = $('<div scrolling="auto"></div>')
        .load('./taller_dd/tdd_obtenerCambios.php?id_diseno='+idDiseno+'&todos=1')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_tdd_registro_cambios; ?>',
            dialogClass: 'uii-dialog',
            width: 500,
            minHeight: 500,
            position: [parseInt((ancho(window.document.body.clientWidth)-990)/2),70],
            zIndex: 3999,
            draggable: false,
            modal: true,
            open: function (event, ui) { window.setTimeout(function () {
                    jQuery(document).unbind('mousedown.dialog-overlay').unbind('mouseup.dialog-overlay'); }, 100);
            },
            close: function(ev, ui) {
                $(this).remove();
            }
        });
        $dialog_cambios.dialog('open');
        return false;
    }
    function actualizarInvitaciones(idDiseno){
        sector = $('#sector_oculto').val();
        if(sector == ""){
            sector = document.getElementById('fcd_sector_admin').value;
        }
        $.get('./taller_dd/tdd_obtenerInvitaciones.php?id_diseno='+idDiseno+"&sector="+sector+"&end="+end, function(data) {
            $('#invitaciones_admin').html(data);
            $('#invitado_a').hide();
            $('#colaboracion_admin').show();
        });
    }
    function actualizarInvitacionesRecib(){
        $.get('./taller_dd/tdd_obtenerInvitacionesRecib.php', function(data) {
            $('#invitac').html(data);
            $('#colaboracion_admin').hide();
            $('#invitado_a').show();
        });
    }
    function actualizarCambios(idDiseno){
        $.get('./taller_dd/tdd_obtenerCambios.php?id_diseno='+idDiseno+'&todos=0', function(data) {
            $('#ultimos_cambios').html(data);
        });
    }
    function enviarInvitacion(idUsuario, idDiseno, nombre, texto_diseno){
        $.get('./taller_dd/tdd_agregarInvitacion.php?id_diseno='+idDiseno+"&id_usuario="+idUsuario+"&texto_diseno="+texto_diseno, function(data) {
            actualizarInvitaciones(idDiseno);
        });

    }
    function enviarMsgAdmin(idDiseno, texto_diseno){
        var msg = "";
        $.get('./taller_dd/tdd_agregarMsgAdmin.php?id_diseno='+idDiseno+"&texto_diseno="+texto_diseno, function(data) {
            if(data == 'true'){
                msg = "<?php echo $lang_tdd_cda_msg_enviado;?>";
            }else{
                msg = "<?php echo $lang_tdd_cda_msg_error;?>";
            }
            var $dialog = $('<div><p><br></br>'+msg+'</p></div>')
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_tdd_cda_msg_admin;?>',
                dialogClass: 'uii-dialog',
                width: 500,
                height: 150,
                zIndex: 3999,
                modal: true,
                close: function(ev, ui) {
                    $(this).remove();
                },
                buttons: {
                    "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                        $(this).dialog("close");
                    }
                }
            });
            $dialog.dialog('open');
            return false;
        });
    }
    function dejarColaboracion(idUsuario, idDiseno){
        var $dialog = $('<div><p><br></br><?php echo $lang_crear_diseno_partic_no_colab_preg; ?></p></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_crear_diseno_partic_no_colab; ?>',
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
                    dejarColaboracionOK(idUsuario, idDiseno);
                    $(this).dialog("close");
                }
            }
        });
        $dialog.dialog('open');
        return false;
    }
    function aceptaInvitacion2(idUsuario, idDiseno){
        $.get('./taller_dd/tdd_aceptarInvitacion.php?id_diseno='+idDiseno+"&id_usuario="+idUsuario, function(data) {
            $('#mis_disenos').click();
        });
    }
    function dejarColaboracionOK(idUsuario, idDiseno){
        $('#mis_disenos').parent().addClass('selected').siblings().removeClass('selected');
        $.get('./taller_dd/tdd_dejarColaboracion.php?id_diseno='+idDiseno+"&id_usuario="+idUsuario, function(data) {
            cargarCentro();
        });
    }
    function mensajeEvento(titulo, mensaje){
        var $dialog9999 = $('<div><p><br>'+mensaje+'</div>')
        .dialog({
            autoOpen: false,
            title: titulo,
            dialogClass: 'uii-dialog',
            width: 450,
            height: 150,
            zIndex: 3999,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            },
            buttons: {
                "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                    $(this).dialog("close");
                }
            }
        });
        $dialog9999.dialog('open');
        return false;
    }
    function findPos(obj) {
        var off=document.getElementById(obj);
//        var off=document.getElementById("nicEdit-fcd_objetivos_transversales");
	var curleft = curtop = 0;
	if (off.offsetParent) {
            curleft = off.offsetLeft;
            curtop = off.offsetTop;
            while (off = off.offsetParent) {
                curleft += off.offsetLeft;
                curtop += off.offsetTop;
            }
	}
	return [curleft,curtop];
    }

    function mostrarAyuda(texto, key){
        var posiciones= findPos(key);
//        anc = parseInt((window.document.body.clientWidth-960)/2);
//        if(anc<0){ anc = 0;}        
//        document.getElementById('ayuda_d_admin').style.left = (posiciones[0]+400-anc)+"px";
        document.getElementById('ayuda_d_admin').style.left = "870px";
        document.getElementById('ayuda_d_admin').style.top = (posiciones[1]-155)+"px";
        document.getElementById('ayuda_d_admin').style.visibility = 'visible';
        if(key != input_ayuda_actual)
            document.getElementById('ayuda_di_admin').style.visibility = 'hidden';
        input_ayuda_actual= key;
        document.getElementById('ayuda_di_admin').style.left = "440px";
        document.getElementById('ayuda_di_admin').style.top = (posiciones[1]-270)+"px";
        document.getElementById('ayuda_content_di_admin').innerHTML = texto;
    }
    function mensajeEvento(titulo, mensaje){
        var $dialog9999 = $('<div><p><br>'+mensaje+'</div>')
        .dialog({
            autoOpen: false,
            title: titulo,
            dialogClass: 'uii-dialog',
            width: 450,
            height: 150,
            zIndex: 7999,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            },
            buttons: {
                "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                    $(this).dialog("close");
                }
            }
        });
        $dialog9999.dialog('open');
        return false;
    }    
    function mostrarDivAyuda(){
        if(document.getElementById('ayuda_di_admin').style.visibility == 'hidden')
            $('#ayuda_di_admin').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0});
        else
            document.getElementById('ayuda_di_admin').style.visibility = 'hidden';
    }

    function actualizarActividad(idActividad, orden_etapa, abajo, arriba, tipo){
        $.get('./taller_dd/tdd_obtenerActividad.php?id_actividad='+idActividad+'&orden_etapa='+orden_etapa+'&abajo='+abajo+'&arriba='+arriba, function(data) {
            $('#actividad_'+idActividad).html(data);
            if(tipo == actividad_laboratorio) document.getElementById('actividad_'+idActividad).className = 'actividad_lab';
            else document.getElementById('actividad_'+idActividad).className = 'actividad_sala';
        });
    }
    function actualizarEtapa(id_etapa, orden_etapa){
        $.get('./taller_dd/tdd_obtenerEtapa.php?id_etapa='+id_etapa+'&orden_etapa='+orden_etapa+'&admin', function(data) {
            $('#etapa'+orden_etapa).html(data);
        });
    }
    function cargarEtapas(id_diseno_actual){
        $.get('./taller_dd/tdd_obtenerEtapas.php?id_diseno='+id_diseno_actual, function(data) {
            campos = data.split("$@%%@$");
            $('#crear_diseno_admin').html(campos[1]);
            maxActividades = parseInt(campos[0]);
        });        
    }    
    function actualizarComentariosDiseno(id_diseno){

        $.get('./taller_dd/tdd_obtenerComentarios.php?id_diseno='+id_diseno+'&tipo=0&end='+end_comentario, function(data) {
            $('#tdd_lista_comentarios').html(data);
        });
    }
    function verMasComentarios(){
        end_comentario= end_comentario + ver_mas_comentario;
        actualizarComentariosDiseno(id_diseno_actual);
    }
    function actualizarComentariosTimeout(){
        clearTimeout(timeoutComentario);
        actualizarComentariosDiseno(id_diseno_actual);
        timeoutComentario = setTimeout("actualizarComentariosTimeout();",1000*3*60);
    }
    function ancho(a){
        if(a>=990)return a+60;
        else return 990+35;
    }
    function abrirActividad(idActividad, orden_etapa, abajo, arriba, id_etapa) {

        tipo= 1;
        $.get('./taller_dd/tdd_bloquearActividad.php?tipo='+tipo+'&id_bloqueo='+idActividad, function(data) {
            texto="";
            if(data.indexOf("true$$==$$") != -1){
                dato_usuario = data.split("$$==$$");
                if(dato_usuario.length >1){
                    texto = "<?php echo $lang_crear_diseno_act_bloq1; ?> "+dato_usuario[1];
                }else{
                    texto = "<?php echo $lang_crear_diseno_act_bloq2; ?>";
                }

                var $dialog2 = $('<div><p><br></br>'+texto+'</p></div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_crear_diseno_editar_act; ?>',
                    dialogClass: 'uii-dialog',
                    width: 450,
                    height: 150,
                    zIndex: 3999,
                    modal: true,
                    close: function(ev, ui) {
                        $(this).remove();
                    },
                    buttons: {
                        "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                            actualizarEtapa(id_etapa, orden_etapa);
                            $(this).dialog("close");
                        }
                    }
                });
                $dialog2.dialog('open');
                return false;

            }else{
                $.get('./taller_dd/tdd_existeActividad.php?id_actividad='+idActividad, function(data) {
                    if(data.indexOf("true") != -1){
                        editarActividad(idActividad, orden_etapa, abajo, arriba, id_etapa);
                    }else{
                        desbloquearActividad(1, idActividad);
                        var $dialog2 = $('<div><p><br></br><?php echo $lang_crear_diseno_act_elim; ?></p></div>')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_crear_diseno_editar_act; ?>',
                            dialogClass: 'uii-dialog',
                            width: 450,
                            height: 150,
                            zIndex: 3999,
                            modal: true,
                            close: function(ev, ui) {
                                $(this).remove();
                            },
                            buttons: {
                                "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                                    actualizarEtapa(id_etapa, orden_etapa);
                                    $(this).dialog("close");
                                }
                            }
                        });
                        $dialog2.dialog('open');
                        return false;
                    }
                });

            }
        });

    }
    function editarActividad(idActividad, orden_etapa, abajo, arriba, id_etapa) {
        scroll(0,0);
        $dialog = $('<div scrolling="auto"></div>')
        .load('./taller_dd/tdd_form_crear_actividad.php?idActividad='+idActividad+'&orden_etapa='+orden_etapa+'&abajo='+abajo+'&arriba='+arriba+'&id_diseno='+id_diseno_actual)
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_crear_diseno_editar_act_title; ?>',
            dialogClass: 'uii-dialog',
            width: 700,
            minHeight: 1000,
            position: [parseInt((ancho(window.document.body.clientWidth)-990)/2),70],
            zIndex: 3999,
            draggable: false,
            modal: true,
            open: function (event, ui) { window.setTimeout(function () {
                    jQuery(document).unbind('mousedown.dialog-overlay').unbind('mouseup.dialog-overlay'); }, 100);
            },
            close: function(ev, ui) {
                desbloquearActividad(1,idActividad);
                actualizarEtapa(id_etapa, orden_etapa);
                $(this).remove();                
            }
        });
        $dialog.dialog('open');
        return false;
    }

    function desbloquearActividad(tipo, idActividad){
        $.get('./taller_dd/tdd_desbloquearActividad.php?tipo='+tipo+'&id_bloqueo='+idActividad, function(data) {

        });
    }

    function abrirObjetivosCurriculares(){
        sector = document.getElementById('fcd_sector_admin').value;
        urlObjCurriculares = objCurriculares[sector];
        if(urlObjCurriculares != ''){
            window.open (urlObjCurriculares,"<?php echo $lang_crear_nuevo_diseno_obj_curriculares; ?>");
        }
    }

    function alturaInicial(){
        cantidad_actividades = <?php echo $maxActividades; ?>;
        altura = document.getElementById('etapas_admin').style.height;
        altura = altura.split('px');
        height = parseInt(altura);
        if(cantidad_actividades*160+150 >= height){
            document.getElementById('etapas_admin').style.height = (cantidad_actividades+1)*160+150+"px";
        }
    }

    function agregarActividad(id_etapa, orden_etapa, cantidad_actividades){
        $.get('./taller_dd/tdd_agregarActividad.php?id_etapa='+id_etapa, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
        });
        altura = document.getElementById('etapas_admin').style.height;
        altura = altura.split('px');
        height = parseInt(altura);
        if(cantidad_actividades*160+150 >= height){
            document.getElementById('etapas_admin').style.height = (cantidad_actividades+1)*160+150+"px";
        }
    }

    function eliminarActividad(idActividad, id_etapa, orden_etapa, actividad_orden){

        tipo= 1;
        $.get('./taller_dd/tdd_bloquearActividad.php?tipo='+tipo+'&id_bloqueo='+idActividad, function(data) {
            texto="";
            if(data.indexOf("true$$==$$") != -1){
                dato_usuario = data.split("$$==$$");
                if(dato_usuario.length >1){
                    texto = "<?php echo $lang_crear_diseno_act_bloq1; ?> "+dato_usuario[1];
                }else{
                    texto = "<?php echo $lang_crear_diseno_act_bloq2; ?>";
                }


                var $dialog2 = $('<div><p><br></br>'+texto+'</p></div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_crear_diseno_editar_act; ?>',
                    dialogClass: 'uii-dialog',
                    width: 500,
                    height: 150,
                    zIndex: 3999,
                    modal: true,
                    close: function(ev, ui) {
                        $(this).remove();
                    },
                    buttons: {
                        "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                            actualizarEtapa(id_etapa, orden_etapa);
                            $(this).dialog("close");
                        }
                    }
                });
                $dialog2.dialog('open');
                return false;

            }else{

                var $dialog = $('<div><p><br></br><?php echo $lang_crear_diseno_act_elim_preg; ?></p></div>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_crear_diseno_act_elim2; ?>',
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
                            desbloquearActividad(1, idActividad);
                            actualizarEtapa(id_etapa, orden_etapa);
                            $(this).dialog("close");
                        },
                        "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                            desbloquearActividad(1, idActividad);
                            eliminarActividadOK(idActividad, id_etapa, orden_etapa, actividad_orden);
                            $(this).dialog("close");
                        }
                    }
                });
                $dialog.dialog('open');
                return false;
            }
        });

    }
    function eliminarActividadOK(idActividad, id_etapa, orden_etapa, actividad_orden){
        $.get('./taller_dd/tdd_eliminarActividad.php?id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
        });
    }

    function bajarActividad(idActividad, id_etapa, orden_etapa, actividad_orden){
        $.get('./taller_dd/tdd_moverActividad.php?mover=abajo&id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
        });
    }

    function subirActividad(idActividad, id_etapa, orden_etapa, actividad_orden){
        $.get('./taller_dd/tdd_moverActividad.php?mover=arriba&id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
        });
    }
    var editarDisenoBool_admin=true;
    function editarDiseno(){
        if(editarDisenoBool_admin){
            tipo= 0;
            $.get('./taller_dd/tdd_bloquearDiseno.php?tipo='+tipo+'&id_bloqueo='+id_diseno_actual, function(data) {
                texto="";
                if(data.indexOf("true$$==$$") != -1){
                    dato_usuario = data.split("$$==$$");
                    if(dato_usuario.length >1){
                        texto = "<?php echo $lang_crear_diseno_bloq1; ?> "+dato_usuario[1];
                    }else{
                        texto = "<?php echo $lang_crear_diseno_bloq2; ?>";
                    }

                    var $dialog2 = $('<div><p><br></br>'+texto+'</p></div>')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_crear_diseno_editar_dis; ?>',
                        dialogClass: 'uii-dialog',
                        width: 450,
                        height: 150,
                        zIndex: 3999,
                        modal: true,
                        close: function(ev, ui) {
                            $(this).remove();
                        },
                        buttons: {
                            "<?php echo $lang_mis_disenos_aceptar; ?>": function() {
                                //actualizarEtapa(id_etapa, orden_etapa);
                                $(this).dialog("close");
                            }
                        }
                    });
                    $dialog2.dialog('open');
                    return false;

                }else{
                    habilitarEdicionDiseno();
                }
            });
        }else{
            //document.getElementById("nicEdit-fcd_nombre").focus();
            desbloquearDiseno();
        }
        actualizarCamposDiseno();

    }
    function desbloquearDiseno(){
        tipo = 0;
        idDiseno = id_diseno_actual;
        deshabilitarEdicionDiseno();
        $.get('./taller_dd/tdd_desbloquearDiseno.php?tipo='+tipo+'&id_bloqueo='+idDiseno, function(data) {

        });

    }
    function deshabilitarEdicionDiseno(){
        editarDisenoBool_admin=true;
        
        document.getElementById("mascara_admin").style.display = "block";
        $('#editar_diseno_admin').attr('value','<?php echo $lang_tdd_cda_editar;?>');
        $('#nicEdit-fcd_nombre_admin').attr('disabled','disabled');
        $('#fcd_sector_admin').attr('disabled','disabled');
        $('#fcd_nivel_admin').attr('disabled','disabled');
        $('#fcd_web_20_admin').attr('disabled','disabled');
        $('#fcd_submit_admin').attr('disabled','disabled');

        //se deshabilitan los textarea
       <?php foreach ($_ta_diseno as $key => $value) {
            echo "nicEditors.findEditor('".$value."_admin').disable();";
            echo "$('#nicEdit-".$value."_admin').css('color','#969696');"; 
       }?>
               
        $('#nicEdit-fcd_nombre_admin').attr('style','color: rgb(150, 150, 150)');
        $('#fcd_sector_admin').attr('style','color: rgb(150, 150, 150)');
        $('#fcd_nivel_admin').attr('style','color: rgb(150, 150, 150)');
        $('.nicEdit-contenedor').css("background-color", "#F0F0F0");
        $('.nicEdit-contenedor').css("color","#969696");
        $('#fcd_web_20_admin').attr('style','color: rgb(150, 150, 150)');
    }

    function habilitarEdicionDiseno(){
        editarDisenoBool_admin=false;


        document.getElementById("mascara_admin").style.display = "none";
        $('#editar_diseno_admin').attr('value','Cancelar');

        $('#nicEdit-fcd_nombre_admin').attr('disabled','');
//        $('#fcd_sector_admin').attr('disabled','');
//        $('#fcd_nivel_admin').attr('disabled','');
//        $('#fcd_web_20_admin').attr('disabled','');
        $('#fcd_submit_admin').attr('disabled','');

        //se habilitan los textarea
        <?php foreach ($_ta_diseno as $key => $value) {
            echo "nicEditors.findEditor('".$value."_admin').init();";            
        }?>
        
        $('#nicEdit-fcd_nombre_admin').attr('style','color: rgb(0, 0, 0)');
        $('#fcd_sector_admin').attr('style','color: rgb(0, 0, 0)');
        $('#fcd_nivel_admin').attr('style','color: rgb(0, 0, 0)');
        $('.nicEdit-contenedor').css("background-color","#FFFFFF");
        $('.nicEdit-contenedor').css("color","#333333");
//        $('#fcd_web_20_admin').attr('style','color: rgb(0, 0, 0)');

    }

    function actualizarCamposDiseno(){
        idDiseno = id_diseno_actual;
        $.get('./taller_dd/tdd_obtenerDiseno.php?id_diseno='+idDiseno, function(data) {
            campos = data.split("$@%%@$");
            $('#nicEdit-fcd_nombre_admin').val(campos[0]);
            $('#fcd_nivel_admin').val(campos[1]);
            $('#fcd_sector_admin').val(campos[2]);
            $('#fcd_descripcion_admin').val(campos[3]);
            $('#fcd_objetivos_curriculares_admin').val(campos[4]);
            $('#fcd_objetivos_transversales_admin').val(campos[5]);
            $('#fcd_contenidos_admin').val(campos[6]);
            $('#fcd_descripcion_etapa1_admin').val(campos[7]);
            $('#fcd_descripcion_etapa2_admin').val(campos[8]);
            $('#fcd_descripcion_etapa3_admin').val(campos[9]);
            $('#fcd_web_20_admin').val(campos[10]);
            document.getElementById("nicEdit-fcd_descripcion_admin").innerHTML = campos[3];
            document.getElementById("nicEdit-fcd_objetivos_curriculares_admin").innerHTML = campos[4];
            document.getElementById("nicEdit-fcd_objetivos_transversales_admin").innerHTML = campos[5];
            document.getElementById("nicEdit-fcd_contenidos_admin").innerHTML = campos[6];
            document.getElementById("nicEdit-fcd_descripcion_etapa1_admin").innerHTML = campos[7];
            document.getElementById("nicEdit-fcd_descripcion_etapa2_admin").innerHTML = campos[8];
            document.getElementById("nicEdit-fcd_descripcion_etapa3_admin").innerHTML = campos[9];
            
            <?php
                foreach ($_ta_diseno as $key => $value) {
                    echo "if (nicEditors.findEditor('".$value."_admin').getContent() == ''|| nicEditors.findEditor('".$value."_admin').getContent()=='<br>'){".
                         "      nicEditors.findEditor('".$value."_admin').setContent('".$ayuda['diseno'][$value]."');".
                         "      $('#nicEdit-".$value."_admin').css('color','#969696');".
                         "}else if(!editarDisenoBool_admin){ $('#nicEdit-".$value."_admin').css('color','#333333'); }";    
                }
            ?>
        });
    }


    $(document).ready(function(){
    
     $('#editar_dd_admin').click(function(){
            $.get('./taller_dd/tdd_form_crear_diseno_admin.php?idDiseno='+id_diseno_actual, function(data) {

                $('#admin_contenido').html(data);
            });
        });

        $('#actividades_editar_dd_admin').click(function(){
            if(estado == 0){
                if(primerClickEtapa){
                    sector = document.getElementById('fcd_sector_admin').value;
                    $('#sector_oculto').val(sector);
                    primerClickEtapa = false;
                    cargarEtapas(id_diseno_actual);
                }
                estado=1
            }else if(estado == 1){
                estado=0
            }
            $('li.uno').removeClass('sel');
            $('li.dos').addClass('sel');
        });

        nicEditors.elemById({buttonList : ['bold','italic','underline','ol','ul','indent','outdent','link','unlink','removeformat'], maxHeight : 80},['fcd_descripcion_admin','fcd_objetivos_curriculares_admin','fcd_objetivos_transversales_admin','fcd_contenidos_admin','fcd_descripcion_etapa1_admin','fcd_descripcion_etapa2_admin','fcd_descripcion_etapa3_admin']);

       <?php
       foreach ($_ta_diseno as $key => $value) {

       //se pone el texto de ayuda en el textarea
        echo "if(nicEditors.findEditor('".$value."_admin').getContent()==''|| nicEditors.findEditor('".$value."_admin').getContent()=='<br>'){".
             " nicEditors.findEditor('".$value."_admin').setContent('".$ayuda['diseno'][$value]."');".
             " $('#nicEdit-".$value."_admin').css('color','#969696')};";

        //se define la funcion focus para los textarea
        echo "$('#nicEdit-".$value."_admin').focus(function() {".
             "if (nicEditors.findEditor('".$value."_admin').getContent() == '".$ayuda['diseno'][$value]."')".
             "     nicEditors.findEditor('".$value."_admin').setContent('');".
             "  $('#nicEdit-".$value."_admin').css('color','#333333') });";
             "  });";

        //se define la funcion blur para los textarea
        echo "$('#nicEdit-".$value."_admin').blur(function() {".
             "if (nicEditors.findEditor('".$value."_admin').getContent() == ''|| nicEditors.findEditor('".$value."_admin').getContent()=='<br>'){".
             "    nicEditors.findEditor('".$value."_admin').setContent('".$ayuda['diseno'][$value]."');".
             "$('#nicEdit-".$value."_admin').css('color','#969696');}".
             "  });";

        //se deshabilita la edicion para todos los textarea
        echo "nicEditors.findEditor('".$value."_admin').disable();";
       }

       ?>
         //cambia el color y el fondo de todos los textarea
         $('.nicEdit-contenedor').css("background-color", "#F0F0F0");
         $('.nicEdit-contenedor').css("color","#969696");
         
        // se quita el editor para el textarea de los comentarios
//        nicEditors.findEditor('dc_texto_comentario').remove();
//        document.getElementById('dc_texto_comentario').value = ''
        
        <?php
        //se crean las funciones para mostrar los textos de ayuda
        foreach ($ayuda['diseno'] as $key => $value) {
            echo "document.getElementById('nicEdit-" . $key . "_admin').onclick = function(){mostrarAyuda('" . $value . "','nicEdit-" . $key . "_admin');};";
        }
        ?>

        scroll(0,0);
        //alturaInicial();
        deshabilitarEdicionDiseno();

        $('#etapas_admin').hide();
        $("#error_form_crear_diseno_admin").hide();
        $('.titulo_editar_c').hide();
        $('.modificar_imagen_a').hide();


        $("#form_crear_diseno_admin").validate({
            rules:{
                fcd_nombre:{
                    required: true,
                    minlength:6
                }
            },
            messages:{
                fcd_nombre:{
                    required: '<?php echo $lang_crear_diseno_requerido1; ?>',
                    minlength: '<?php echo $lang_crear_diseno_requerido2; ?>'
                }
            },
            submitHandler: function() {
                <?php
                foreach ($_ta_diseno as $key => $value) {
                    echo "nicEditors.findEditor('" . $value . "_admin').saveContent();";
                }
                ?>
                url = './taller_dd/tdd_guardar_diseno_admin.php?';

                $.post(url, $("#form_crear_diseno_admin").serialize(), function(id) {

                    if (parseInt(id)< 0){
                        $("#error_form_crear_diseno_admin").text('<?php echo $lang_crear_diseno_guardar_err; ?>');
                        mensajeEvento("<?php echo $lang_crear_diseno_guardar; ?>", "<?php echo $lang_crear_diseno_guardar_err; ?>");
                        $("#error_form_crear_diseno_admin").show();
                    }
                    else{
                        $(".intro_crear_diseno").html("<div><?php echo $lang_crear_diseno_guardar_ok; ?> </div>");
                        mensajeEvento("<?php echo $lang_crear_diseno_guardar; ?>", "<?php echo $lang_crear_diseno_guardar_ok; ?>");
                        $("#error_form_crear_diseno_admin").show();
                        id_diseno_actual = parseInt(id);
                        desbloquearDiseno();
                    }
                });
            }
        });

        actualizarParticipantes(id_diseno_actual);
        end = 20;
        actualizarInvitaciones(id_diseno_actual);
        actualizarCambios(id_diseno_actual);
        tabTallerActivo = true;
        actualizarComentariosTimeout();
    });

</script>
