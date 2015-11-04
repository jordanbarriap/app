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
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))
    header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

$id_usuario = $_SESSION["klwn_id_usuario"];
?>

<div class="container_16">
    <div class="grid_16">
        <div class="intro">
            <?php echo $lang_tdd_intro ?>
        </div>
        <div class=" inicio_izquierda inicio_izquierda_tdd">
            <ul class="inicio_menu">
                <li class="selected"><a id="mis_disenos"><?php echo $lang_mis_Disenos; ?></a></li>
                <li><a id="crear_diseno_nuevo"><?php echo $lang_crear_nuevo_diseno; ?></a></li>
                <li><a id="crear_diseno_nueva_version"><?php echo $lang_crear_nuevo_version_diseno; ?></a></li>
                <li><a id="crear_disenod"><?php echo $lang_modificar_diseno; ?></a></li>
            </ul>
            <br></br>
            <div id="invitado_a">
                <div id="invitac">
                </div>
            </div>
            <div id="colaboracion">
                <div id="ultimos_cambios">
                </div>                
                <div id="participantes">
                </div>
                <div id="invitaciones">
                </div>                   
            </div>
        </div>
        <div id="inicio_bloque_central_tdd">
        </div> 
        <div class="inicio_bloque_central_crear_diseno">
        </div>        
    </div>
</div>
<script type="text/javascript">
    
    var end = 20;
    var end2 = 10;

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
        $('#colaboracion').hide();
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
    function actualizarParticipantes(idDiseno){
   
        $.get('./taller_dd/tdd_obtenerParticipantes.php?id_diseno='+idDiseno, function(data) {
            $('#participantes').html(data);
        });
    }
    function verMasInvitaciones(idDiseno){
        end = end+end2;
        actualizarInvitaciones(idDiseno);       
    }
    function verMasCambios(idDiseno){
        scroll(0,0);
        $dialog_cambios = $('<div scrolling="auto"></div>')
        .load('./taller_dd/tdd_obtenerCambios?id_diseno='+idDiseno+'&todos=1')
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
            sector = document.getElementById('fcd_sector').value;
        }
        $.get('./taller_dd/tdd_obtenerInvitaciones.php?id_diseno='+idDiseno+"&sector="+sector+"&end="+end, function(data) {
            $('#invitaciones').html(data);
            $('#invitado_a').hide();
            $('#colaboracion').show();
        });
    }
    function actualizarInvitacionesRecib(){
        $.get('./taller_dd/tdd_obtenerInvitacionesRecib.php', function(data) {
            $('#invitac').html(data);
            $('#colaboracion').hide();
            $('#invitado_a').show();
        });
    }
    function actualizarCambios(idDiseno){
        $.get('./taller_dd/tdd_obtenerCambios.php?id_diseno='+idDiseno+'&todos=0', function(data) {
            $('#ultimos_cambios').html(data);
        });
    }     
    function enviarInvitacion(idUsuario, idDiseno, nombre, texto_diseno){
        mensaje = 'Te invito a participar de la creación del Diseño Didáctico: '+texto_diseno+', para aceptar has click ';
        mensaje += '<a onClick="aceptaInvitacion('+idUsuario+','+idDiseno+');" >aquí</a>';
        $.get('./taller_dd/tdd_agregarInvitacion.php?id_diseno='+idDiseno+"&id_usuario="+idUsuario+"&mensaje="+mensaje+"&texto_diseno="+texto_diseno, function(data) {
            actualizarInvitaciones(idDiseno);
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
    $(document).ready(function(){
        cargarCentro();
        
        $('#mis_disenos').click(function(){
            if(tabTallerActivo){ clearTimeout(timeoutComentario); }
            $('#crear_disenod').hide();
            $('#colaboracion').hide();
            $.get('./taller_dd/tdd_mis_disenos.php', function(data) {
                $('.inicio_bloque_central_crear_diseno').html(data);
            });
        }); 
        
        $('#crear_diseno_nuevo').click(function(){
            if(tabTallerActivo){ clearTimeout(timeoutComentario); }
            //$('#crear_diseno').show();
            $('#colaboracion').hide();
            $('#invitado_a').hide();
            $('#crear_disenod').hide();
            $.get('./taller_dd/tdd_form_crear_nuevo_diseno', function(data) {
                $('.inicio_bloque_central_crear_diseno').html(data);
            });
        });          

        $('#crear_diseno_nueva_version').click(function(){
            if(tabTallerActivo){ clearTimeout(timeoutComentario); }
            //$('#crear_diseno').show();
            $('#colaboracion').hide();
            $('#invitado_a').hide();
            $('#crear_disenod').hide();
            $.get('./taller_dd/tdd_disenos_existentes.php', function(data) {
                $('.inicio_bloque_central_crear_diseno').html(data);
            });
        });          
        
        $(".inicio_menu a").click(function(){
            if($(this).attr("id")== 'mis_disenos'){
                $(this).parent().addClass('selected').
                    siblings().removeClass('selected');
            }
            if($(this).attr("id")== 'crear_diseno_nuevo'){
                $(this).parent().addClass('selected').
                    siblings().removeClass('selected');
            }
            if($(this).attr("id")== 'crear_diseno_nueva_version'){
                $(this).parent().addClass('selected').
                    siblings().removeClass('selected');
            }               
        });            
    });
</script>
