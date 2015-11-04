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
$isIniciador = false;

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
    
    if($_diseno[0]['dd_id_autor'] == $_SESSION["klwn_id_usuario"]) $isIniciador = true;
    
//    $_etapas = obtenerEtapasFuncion($idDiseno, $conexion);
//    $_actividades_etapa1 = obtenerActividadesPorEtapaFuncion($_etapas[0]['e_id_etapa'], $conexion);
//    $_actividades_etapa2 = obtenerActividadesPorEtapaFuncion($_etapas[1]['e_id_etapa'], $conexion);
//    $_actividades_etapa3 = obtenerActividadesPorEtapaFuncion($_etapas[2]['e_id_etapa'], $conexion);
//    
//    $maxActividades = count($_actividades_etapa1);
//    if(count($_actividades_etapa2) > $maxActividades) $maxActividades = count($_actividades_etapa2);
//    if(count($_actividades_etapa3) > $maxActividades) $maxActividades = count($_actividades_etapa3);
    
}
?>
<div id="menu_editar_dd">
    <ul id="ul_menu_editar_dd">
        <li class="uno sel" ><a id="editar_dd" ><?php echo $lang_crear_nuevo_diseno_titulo_2; ?></a> </li>
        <li class="dos"><a id="actividades_editar_dd"><?php echo $lang_crear_nuevo_diseno_etapas_titulo; ?> </a> </li>
    </ul>
</div>
<div id="descargas">
    <?php if($isIniciador){ ?><a id="disenoListo" title="<?php echo $lang_crear_diseno_listo_tooltip; ?>" href="#" onclick="disenoListo();"><?php echo $lang_crear_diseno_listo; ?></a><?php } ?>
     <a id="descargaZip" href="./taller_dd/tdd_crearZip.php?idDiseno=<?php echo $idDiseno; ?>" target="_black"><?php echo $lang_crear_diseno_descarga_zip; ?></a> 
    <a id="descargaWord" href="./taller_dd/tdd_crearWord.php?idDiseno=<?php echo $idDiseno; ?>" target="_black"><?php echo $lang_crear_diseno_descarga_word; ?></a>
   
</div>
<div id="crear_diseno">
    <div id="datos_diseno">
        <div id="formulario_crear_diseno">
            <div id="ayuda_di"><div id="ayuda_content_di"></div></div>
            <div id="ayuda_d" onClick="mostrarDivAyuda();"></div>
            <form id="form_crear_diseno" method="post" action="" accept-charset="UTF-8">
                <div id="caja_form_crear_diseno">                    

                    <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcd_id_autor"  name="fcd_id_autor" value="<?php echo $fcd_id_autor; ?>"/>
                    <input tabindex="1" type="hidden" maxlenght="20" size="20" id="fcd_id_diseno"  name="fcd_id_diseno" value="<?php echo $idDiseno; ?>"/>
                    <table>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_nombre . " :"; ?></label></td>
                            <td><input tabindex="2" type="text" maxlenght="40" size="20" id="nicEdit-fcd_nombre"  name="fcd_nombre" value="<?php echo $fcd_nombre; ?>"/></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_sector . " :"; ?></label></td>
                            <td><select tabindex="3"  id="fcd_sector" name="fcd_sector" size="1" onChange="actualizarInvitaciones(<?php echo $idDiseno; ?>);">
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
                            <td><select tabindex="4"  id="fcd_nivel" name="fcd_nivel" size="1">
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
                            <td><textarea tabindex="5" id="fcd_descripcion" name="fcd_descripcion"><?php echo $fcd_descripcion; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_obj_curriculares . " :"; ?></label></td>
                            <td><textarea tabindex="6" id="fcd_objetivos_curriculares" name="fcd_objetivos_curriculares" rows="3" cols="1"><?php echo $fcd_objetivos_curriculares; ?></textarea>
                                <a class="abrirObjetivos" title="Ver Objetivos Curriculares" onClick="abrirObjetivosCurriculares();"><img src="./taller_dd/img/pdf.png" class="icono_pdfObjCurr"></img></a></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_obj_transversales . " :"; ?></label></td>
                            <td><textarea tabindex="7" id="fcd_objetivos_transversales" name="fcd_objetivos_transversales" rows="3" cols="1"><?php echo $fcd_objetivos_transversales; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_contenidos . " :"; ?></label></td>
                            <td><textarea tabindex="8" id="fcd_contenidos" name="fcd_contenidos" rows="2" cols="1"><?php echo $fcd_contenidos; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa1 . " :"; ?></label></td>
                            <td><textarea tabindex="9" id="fcd_descripcion_etapa1" name="fcd_descripcion_etapa1" rows="3" cols="1"><?php echo $fcd_descripcion_etapa1; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa2 . " :"; ?></label></td>
                            <td><textarea tabindex="10" id="fcd_descripcion_etapa2" name="fcd_descripcion_etapa2" rows="3" cols="1"><?php echo $fcd_descripcion_etapa2; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_desc_etapa3 . " :"; ?></label></td>
                            <td><textarea tabindex="11" id="fcd_descripcion_etapa3" name="fcd_descripcion_etapa3" rows="3" cols="1"><?php echo $fcd_descripcion_etapa3; ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_crear_nuevo_diseno_web_20 . " :"; ?></label></td>
                            <td><select tabindex="12"  id="fcd_web_20" name="fcd_web_20" size="1">
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
                    <input id="editar_diseno" class="editar_diseno" type="button" onclick="editarDiseno();" value="<?php echo $lang_crear_diseno_editar; ?>" name="editar">
                    <input tabindex="13" id="fcd_submit" class="fcd_submit" type="submit" value="<?php echo $lang_crear_diseno_guardar; ?>">
                    <div class="clear"></div>
                    <div id="error_form_crear_diseno"></div>
                    <div class="clear"></div>     
                </div>
            </form>
            <div id="mascara_"></div>
        </div>
    </div>
</div>
<input type="hidden" id="sector_oculto" name="sector_oculto"></input>
<div class="separador_vertical"></div>
    <div id="tdd_comentarios" >
        <div class="titulo_tdd_comentarios"><?php echo $lang_crear_diseno_coment; ?></div>
        <div class="caja_form_comentarios">
            <form id="form_tdd_comentarios" method="post" action="" accept-charset="UTF-8">
                <input tabindex="0" type="hidden" maxlenght="20" size="20" id="dc_tipo_comentario"  name="dc_tipo_comentario" value="0"/>
                <input tabindex="0" type="hidden" maxlenght="20" size="20" id="dc_id_comentario"  name="dc_id_comentario" value="<?php echo $idDiseno; ?>"/>
                <textarea tabindex="1" id="dc_texto_comentario" name="dc_texto_comentario" rows="2" cols="1"></textarea>
                <div class="clear"></div>
                <input tabindex="2" id="agregarComentario" type="submit" value="<?php echo $lang_crear_diseno_enviar; ?>" class="dc_submit" />
            </form>
        </div>
        <div class="separador"></div>
        <div id="tdd_lista_comentarios">
        </div>
    </div>
<!--<script type="text/javascript" src="./taller_dd/nicEdit.js"></script> -->
<script type="text/javascript">

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
    
    objCurriculares['SMT'] = '<?php echo $rutaObjCurriculares_SMT; ?>';
    objCurriculares['SLC'] = '<?php echo $rutaObjCurriculares_SLC; ?>';
    objCurriculares['SHG'] = '<?php echo $rutaObjCurriculares_SHG; ?>';
    objCurriculares['SCS'] = '<?php echo $rutaObjCurriculares_SCS; ?>';
    objCurriculares['SIE'] = '<?php echo $rutaObjCurriculares_SIE; ?>';
    objCurriculares['SG'] = '<?php echo $rutaObjCurriculares_SG; ?>'
   
    function descargaWord(){
        $.get('./taller_dd/tdd_crearWord.php?idDiseno='+id_diseno_actual, function(data) {
             if(data != '' && data != 'false'){
                 window.open('./'+data,'_newtab');
             }
        });
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
//        document.getElementById('ayuda_d').style.left = (posiciones[0]+400-anc)+"px";
        document.getElementById('ayuda_d').style.left = "870px";
        document.getElementById('ayuda_d').style.top = (posiciones[1]-95)+"px";
        document.getElementById('ayuda_d').style.visibility = 'visible';
        if(key != input_ayuda_actual)
            document.getElementById('ayuda_di').style.visibility = 'hidden';
        input_ayuda_actual= key;
        document.getElementById('ayuda_di').style.left = "440px";
        document.getElementById('ayuda_di').style.top = (posiciones[1]-206)+"px";
        document.getElementById('ayuda_content_di').innerHTML = texto;
    }
    function mostrarDivAyuda(){
        if(document.getElementById('ayuda_di').style.visibility == 'hidden')
            $('#ayuda_di').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0});
        else
            document.getElementById('ayuda_di').style.visibility = 'hidden';
    }

    function actualizarActividad(idActividad, orden_etapa, abajo, arriba, tipo){
        $.get('./taller_dd/tdd_obtenerActividad.php?id_actividad='+idActividad+'&orden_etapa='+orden_etapa+'&abajo='+abajo+'&arriba='+arriba, function(data) {
            $('#actividad_'+idActividad).html(data);
            if(tipo == actividad_laboratorio) document.getElementById('actividad_'+idActividad).className = 'actividad_lab';
            else document.getElementById('actividad_'+idActividad).className = 'actividad_sala';
        });
    }
    function actualizarEtapa(id_etapa, orden_etapa){
        $.get('./taller_dd/tdd_obtenerEtapa.php?id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
            $('#etapa'+orden_etapa).html(data);
        });
    }
    function cargarEtapas(id_diseno_actual){
        $.get('./taller_dd/tdd_obtenerEtapas.php?id_diseno='+id_diseno_actual, function(data) {
            campos = data.split("$@%%@$");
            $('#crear_diseno').html(campos[1]);
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
                cerrarAyuda();
                $(this).remove();                
            }
        });
        $dialog.dialog('open');
        return false;
    }

    function desbloquearActividad(tipo, idActividad){
        $.get('./taller_dd/tdd_desbloquearActividad.php?tipo='+tipo+'&id_bloqueo='+idActividad, function(data) {

        });
        actualizarCambios(id_diseno_actual);
    }

    function abrirObjetivosCurriculares(){
        sector = document.getElementById('fcd_sector').value;
        urlObjCurriculares = objCurriculares[sector];
        if(urlObjCurriculares != ''){
            window.open(urlObjCurriculares,"<?php echo $lang_crear_nuevo_diseno_obj_curriculares; ?>");
        }
    }

    function agregarActividad(id_etapa, orden_etapa, cantidad_actividades){
        $.get('./taller_dd/tdd_agregarActividad.php?id_etapa='+id_etapa+'&id_diseno='+id_diseno_actual, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
        });
        
        actualizarCambios(id_diseno_actual);
    }

    function eliminarActividad( idActividad, id_etapa, orden_etapa, actividad_orden){

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
                            eliminarActividadOK( idActividad, id_etapa, orden_etapa, actividad_orden);
                            $(this).dialog("close");
                        }
                    }
                });
                $dialog.dialog('open');
                return false;
            }
        });

    }
    function eliminarActividadOK( idActividad, id_etapa, orden_etapa, actividad_orden){
        $.get('./taller_dd/tdd_eliminarActividad.php?id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa+'&id_diseno='+id_diseno_actual, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
            actualizarCambios(id_diseno_actual);
        });
    }

    function bajarActividad(idActividad, id_etapa, orden_etapa, actividad_orden){
        $.get('./taller_dd/tdd_moverActividad.php?mover=abajo&id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
            actualizarCambios(id_diseno_actual);
        });
    }

    function subirActividad(idActividad, id_etapa, orden_etapa, actividad_orden){
        $.get('./taller_dd/tdd_moverActividad.php?mover=arriba&id_actividad='+idActividad+'&actividad_orden='+actividad_orden+'&id_etapa='+id_etapa+'&orden_etapa='+orden_etapa, function(data) {
            actualizarEtapa(id_etapa, orden_etapa);
            actualizarCambios(id_diseno_actual);
        });
    }
    var editarDisenoBool=true;
    function editarDiseno(){
        if(editarDisenoBool){
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
    function disenoListo(){
        idDiseno = id_diseno_actual;
        $.get('./taller_dd/tdd_diseno_listo.php?id_diseno='+idDiseno, function(data) {
            if(data == 'true'){
                texto = 'El correo se envió con exito';
            }else{
                texto = 'No fue posible enviar el correo, intenta mas tarde';
            }
                 var $dialog33 = $('<div><p><br></br>'+texto+'</p></div>')
                .dialog({
                    autoOpen: false,
                    title: 'Aviso diseño listo',
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
                $dialog33.dialog('open');
                return false;             
        });        
    }
    function deshabilitarEdicionDiseno(){
        editarDisenoBool=true;
        
        document.getElementById("mascara_").style.display = "block";
        $('#editar_diseno').attr('value','Editar');
        $('#nicEdit-fcd_nombre').attr('disabled','disabled');
        $('#fcd_sector').attr('disabled','disabled');
        $('#fcd_nivel').attr('disabled','disabled');
        $('#fcd_web_20').attr('disabled','disabled');
        $('#fcd_submit').attr('disabled','disabled');

        //se deshabilitan los textarea
       <?php foreach ($_ta_diseno as $key => $value) {
        echo "nicEditors.findEditor('".$value."').disable();";
        echo "$('#nicEdit-".$value."').css('color','#969696');";
       }?>
               
        $('#nicEdit-fcd_nombre').attr('style','color: rgb(150, 150, 150)');
        $('#fcd_sector').attr('style','color: rgb(150, 150, 150)');
        $('#fcd_nivel').attr('style','color: rgb(150, 150, 150)');
        $('.nicEdit-contenedor').css("background-color", "#F0F0F0");
        $('.nicEdit-contenedor').css("color","#969696");
        $('#fcd_web_20').attr('style','color: rgb(150, 150, 150)');
    }

    function habilitarEdicionDiseno(){
        editarDisenoBool=false;
        
        document.getElementById("mascara_").style.display = "none";
        $('#editar_diseno').attr('value','Cancelar');
        
        $('#nicEdit-fcd_nombre').attr('disabled','');
        $('#fcd_sector').attr('disabled','');
        $('#fcd_nivel').attr('disabled','');
        $('#fcd_web_20').attr('disabled','');
        $('#fcd_submit').attr('disabled','');

        //se habilitan los textarea
        <?php foreach ($_ta_diseno as $key => $value) {
        echo "nicEditors.findEditor('".$value."').init();";
        }?>
        
        $('#nicEdit-fcd_nombre').attr('style','color: rgb(0, 0, 0)');
        $('#fcd_sector').attr('style','color: rgb(0, 0, 0)');
        $('#fcd_nivel').attr('style','color: rgb(0, 0, 0)');
        $('.nicEdit-contenedor').css("background-color","#FFFFFF");
        $('.nicEdit-contenedor').css("color","#333333");
//        $('#fcd_web_20').attr('style','color: rgb(0, 0, 0)');

    }

    function actualizarCamposDiseno(){
        idDiseno = id_diseno_actual;
        $.get('./taller_dd/tdd_obtenerDiseno.php?id_diseno='+idDiseno, function(data) {
            campos = data.split("$@%%@$");
            $('#nicEdit-fcd_nombre').val(campos[0]);
            $('#fcd_nivel').val(campos[1]);
            $('#fcd_sector').val(campos[2]);
            $('#fcd_descripcion').val(campos[3]);
            $('#fcd_objetivos_curriculares').val(campos[4]);
            $('#fcd_objetivos_transversales').val(campos[5]);
            $('#fcd_contenidos').val(campos[6]);
            $('#fcd_descripcion_etapa1').val(campos[7]);
            $('#fcd_descripcion_etapa2').val(campos[8]);
            $('#fcd_descripcion_etapa3').val(campos[9]);
            $('#fcd_web_20').val(campos[10]);
            document.getElementById("nicEdit-fcd_descripcion").innerHTML = campos[3];
            document.getElementById("nicEdit-fcd_objetivos_curriculares").innerHTML = campos[4];
            document.getElementById("nicEdit-fcd_objetivos_transversales").innerHTML = campos[5];
            document.getElementById("nicEdit-fcd_contenidos").innerHTML = campos[6];
            document.getElementById("nicEdit-fcd_descripcion_etapa1").innerHTML = campos[7];
            document.getElementById("nicEdit-fcd_descripcion_etapa2").innerHTML = campos[8];
            document.getElementById("nicEdit-fcd_descripcion_etapa3").innerHTML = campos[9];            
            
            <?php
                foreach ($_ta_diseno as $key => $value) {
                    echo "if (nicEditors.findEditor('".$value."').getContent() == ''|| nicEditors.findEditor('".$value."').getContent()=='<br>'){".
                         "      nicEditors.findEditor('".$value."').setContent('".$ayuda['diseno'][$value]."');".
                         "      $('#nicEdit-".$value."').css('color','#969696');".
                         "}else if(!editarDisenoBool){ $('#nicEdit-".$value."').css('color','#333333'); }";    
                }
            ?>            
        });
    }


    $(document).ready(function(){
    
     $('#editar_dd').click(function(){
            $.get('./taller_dd/tdd_form_crear_diseno.php?idDiseno='+id_diseno_actual, function(data) {

                $('.inicio_bloque_central_crear_diseno').html(data);
            });
        });

        $('#actividades_editar_dd').click(function(){
            if(estado == 0){
                if(primerClickEtapa){
                    sector = document.getElementById('fcd_sector').value;
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


        nicEditors.elemById({buttonList : ['bold','italic','underline','ol','ul','indent','outdent','link','unlink','removeformat'], maxHeight : 80},['fcd_descripcion','fcd_objetivos_curriculares','fcd_objetivos_transversales','fcd_contenidos','fcd_descripcion_etapa1','fcd_descripcion_etapa2','fcd_descripcion_etapa3']);

       <?php
       foreach ($_ta_diseno as $key => $value) {

       //se pone el texto de ayuda en el textarea
        echo "if(nicEditors.findEditor('".$value."').getContent()==''|| nicEditors.findEditor('".$value."').getContent()=='<br>'){".
             " nicEditors.findEditor('".$value."').setContent('".$ayuda['diseno'][$value]."');".
             " $('#nicEdit-".$value."').css('color','#969696')};";

        //se define la funcion focus para los textarea
        echo "$('#nicEdit-".$value."').focus(function() {".
             "if (nicEditors.findEditor('".$value."').getContent() == '".$ayuda['diseno'][$value]."')".
             "     nicEditors.findEditor('".$value."').setContent('');".
             "  $('#nicEdit-".$value."').css('color','#333333') });";
             "  });";

        //se define la funcion blur para los textarea
        echo "$('#nicEdit-".$value."').blur(function() {".
             "if (nicEditors.findEditor('".$value."').getContent() == ''|| nicEditors.findEditor('".$value."').getContent()=='<br>'){".
             "    nicEditors.findEditor('".$value."').setContent('".$ayuda['diseno'][$value]."');".
             "$('#nicEdit-".$value."').css('color','#969696');}".
             "  });";

        //se deshabilita la edicion para todos los textarea
        echo "nicEditors.findEditor('".$value."').disable();";
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
            echo "document.getElementById('nicEdit-" . $key . "').onclick = function(){mostrarAyuda('" . $value . "','nicEdit-" . $key . "');};";
        }
        ?>

        scroll(0,0);
        deshabilitarEdicionDiseno();

        $("#error_form_crear_diseno").hide();
        $('.titulo_editar_c').hide();
        $('.modificar_imagen_a').hide();


        $("#form_crear_diseno").validate({
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
                    echo "nicEditors.findEditor('" . $value . "').saveContent();";
                }
                ?>
                url = './taller_dd/tdd_guardar_diseno.php?';

                $.post(url, $("#form_crear_diseno").serialize(), function(id) {

                    if (parseInt(id)< 0){
                        $("#error_form_crear_diseno").text('<?php echo $lang_crear_diseno_guardar_err; ?>');
                        mensajeEvento("<?php echo $lang_crear_diseno_guardar; ?>", "<?php echo $lang_crear_diseno_guardar_err; ?>");
                        $("#error_form_crear_diseno").show();
                    }
                    else{
                        $(".intro_crear_diseno").html("<div><?php echo $lang_crear_diseno_guardar_ok; ?> </div>");
                        mensajeEvento("<?php echo $lang_crear_diseno_guardar; ?>", "<?php echo $lang_crear_diseno_guardar_ok; ?>");
                        $("#error_form_crear_diseno").show();
                        id_diseno_actual = parseInt(id);
                        desbloquearDiseno();
                        actualizarCambios(id_diseno_actual);
                    }
                });
            }
        });

        $("#form_tdd_comentarios").validate({
            rules:{
                dc_texto_comentario:{
                    required: true,
                    minlength:6
                }
            },
            messages:{
                dc_texto_comentario:{
                    required: '',
                    minlength: '<?php echo $lang_crear_diseno_requerido2; ?>'
                }
            },
            submitHandler: function() {
                url = './taller_dd/tdd_enviarComentario.php?';

                $.post(url, $("#form_tdd_comentarios").serialize(), function(data) {

                    document.getElementById('dc_texto_comentario').value = '';
                    actualizarComentariosDiseno(id_diseno_actual);
                });
            }
        });


        $('.modificar_diseno').click(function(){
            if(estado == 1){
                estado=0
                scroll(0,0);
                $('#datos_diseno').slideDown();               
                $('.titulo_editar_a').show();
                $('.titulo_editar_c').hide();
                $('.modificar_imagen_c').show();
                $('.modificar_imagen_a').hide();
            }else if(estado == 0){
                if(primerClickEtapa){
                    primerClickEtapa = false;
                    cargarEtapas(id_diseno_actual);
                }                
                estado=1
                scroll(0,0);
                
                $('#datos_diseno').slideUp();
                $('.titulo_editar_c').show();
                $('.titulo_editar_a').hide();
                $('.modificar_imagen_a').show();
                $('.modificar_imagen_c').hide();                
            }

        });
        $('.modificar_actividades').click(function(){
            if(estado == 0){
                if(primerClickEtapa){
                    primerClickEtapa = false;
                    cargarEtapas(id_diseno_actual);
                }
                estado=1
                scroll(0,0);
                
                $('#datos_diseno').slideUp();
                $('.titulo_editar_c').show();
                $('.titulo_editar_a').hide();
                $('.modificar_imagen_a').show();
                $('.modificar_imagen_c').hide();
            }else if(estado == 1){
                estado=0
                scroll(0,0);
                $('#datos_diseno').slideDown();
                
                $('.titulo_editar_a').show();
                $('.titulo_editar_c').hide();
                $('.modificar_imagen_c').show();
                $('.modificar_imagen_a').hide();                
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
