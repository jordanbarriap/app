<?php
$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title><?php echo $titulo_pagina; ?></title>
        <meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset; ?>">
        <meta name="author" content="Kelluwen" />
        <meta name="description" lang="es" content="<?php echo $descripcion_pagina; ?>" />
        <link href="<?php echo $config_ruta_img; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/reset.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/text.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/960.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/lists.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>taller_dd/css/tdd_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>revpares/css/rp_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery-ui-1.7.2.custom.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/ui.spinner.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery.autocomplete.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>reco/css/rec_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>admin/css/admin_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery.scrollbar.css" />        
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.validate.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.imgareaselect-0.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/info.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/ui.spinner.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.tinyscrollbar.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>revpares/inc/plugins/jquery.textbox-hinter.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>encuestas/inc/plugins/jquery.numeric.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.form.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/ui.core.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/ui.sortable.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.tabSlideOut.v1.3.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>taller_dd/nicEdit.js"></script>

		<!-- Gr�ficos HighchartsPortafolio-->
        <!-- Para no depender de jquery -->
		<script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/highcharts-standalone-framework.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/highcharts.js"></script>
		<!-- Para el gr�fico polar -->
		<script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/highcharts-more.js"></script>
        <!-- No s� para que es esto -->
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/highcharts-exporting.js"></script>

        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>portafolio/css/portafolio.css" />
<!--    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>-->

        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/d3.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/d3.layout.cloud.js"></script>
        <!--<script src="http://d3js.org/d3.v3.min.js"></script>-->
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript">
          var $jQ11 = jQuery.noConflict();
        </script>-->

<!--    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>-->

        <script type="text/javascript">
            $(function(){
                var idioma_sel = '<?php echo $_SESSION['idioma']?>';
                if(idioma_sel == 'spanish'){
                    var imagen = 'img/contact_tab.gif';    
                }
                else{
                    if(idioma_sel == 'english'){
                        var imagen = 'img/contact_us_tab.gif'; 
                    }
                }
                $('.slide-out-div').tabSlideOut({
                    tabHandle: '.handle',                     //class of the element that will become your tab
                    pathToTabImage: imagen,                   //path to the image for the tab //Optionally can be set using css
                    imageHeight: '122px',                     //height of tab image           //Optionally can be set using css
                    imageWidth: '40px',                       //width of tab image            //Optionally can be set using css
                    tabLocation: 'left',                      //side of screen where tab lives, top, right, bottom, or left
                    speed: 300,                               //speed of animation
                    action: 'click',                          //options: 'click' or 'hover', action to trigger animation
                    topPos: '200px',                          //position from the top/ use if tabLocation is left or right
                    leftPos: '20px',                          //position from left/ use if tabLocation is bottom or top
                    fixedPosition: false                      //options: true makes it stick(fixed position) on scroll
                });

            });

        </script>
        <script type="text/javascript">
            function cambiar_idioma(idioma_seleccionado){
                $.ajax({ 
                    type: "POST", 
                    url: "./inc/lang/cambiar_idioma.php", 
                    data:"idioma="+idioma_seleccionado,
                    async: false,
                    success: function(data){
                        if(data != ''){
                            cargarPopUpCookies(data);//muestra mensaje de cookies deshabilitadas   
                        }
                        else{ //si cookies habilitadas->recarga pagina con idioma seleccionado
                            location.reload(); 
                        }                        
                    }
                });   
            }
            
            function cargarPopUpCookies(idioma){
                var mensaje_cookie, titulo;
                switch (idioma) {
                    case "spanish":
                        titulo = "<?php echo $lang_header_inc_atencion; ?>"
                        mensaje_cookie = "<?php echo $lang_header_inc_sin_cookies; ?>";
                        break;
                    case "english":
                        titulo = "Attention!"
                        mensaje_cookie = "It seems that your browser has the cookies disabled. Make sure that cookies are enabled.";
                        break;
                    default:
                        titulo = "Attention!"
                        mensaje_cookie = "It seems that your browser has the cookies disabled. Make sure that cookies are enabled."; //mensaje por defecto
                }
                var $dialog = $('<div class=\"mensaje_cookie\"> <p>'+mensaje_cookie+'</p></div>')
                
                .dialog({
                    autoOpen: false,
                    title: titulo,
                    width: 500,
                    height: 100,
                    modal: true,
                    buttons: {                                              
                        "Ok": function() {
                            $(this).dialog("close");
                        }
                    },
                    close: function(ev, ui) {
                        $(this).remove();
                    }
                    });
                $dialog.dialog('open');
                return false;
            }
        </script>
        <script type="text/javascript">
            var repeticion;
            var repeticion2;
            var repeticion_md;
            var tabTallerActivo = false;

            function detenerBitacoraNM (){
                if (window.mensajesNuevosTimeLine){
                    window.clearInterval(repeticion);
                }
                return false;
            }
            function detenerBitacoraCompartidaNM (){

                if (window.mensajesNuevosTimeLineCompartida){
                    window.clearInterval(repeticion2);
                }
                return false;
            }
            
            function detenerMuralDisenoNM (){
                if (window.mensajesNuevosMuralDiseno){
                    window.clearInterval(repeticion_md);
                }
                return false;
            }
            
            function cargarAvance(){
                $.get(
                'exp_avance.php?codexp=<?php echo $id_experiencia; ?>',
                function(data){
                    $('#caja_avance_exp').html(data);
                    return false;
                }
            );
                
            }
            function cargarGestionAvance(){
                $.get(
                'exp_lista_etapas.php?codexp=<?php echo $id_experiencia; ?>',
                function(data){
                    $('#ui-tabs-4').html(data);
                    return false;
                }
            );

                return false;
            }

            function recargaAutomaticaBitacora(id_experiencia,tipo_bitacora){
                var exp_actual="<?php echo $_SESSION['id_exp_seleccionada'];?>";
                /*var array_textarea_rptas=array();
                array_textarea_rptas=document.getElementsByClassName("txt_mensaje");
                console.log("Largo: "+array_textarea_rptas.length());
                for (var i=0;i<array_textarea_rptas.length();i++){
                    console.log("Texto rpta: "+array_textarea_rptas[i].innerHTML);
                }*/
                if (tipo_bitacora=="Clase" && exp_actual==id_experiencia && $('#link_recarga_timeline').is(":visible")){
                    $('#link_recarga_timeline').click();
                }else{
                    if(tipo_bitacora=="Compartida" && $('#link_recarga_timeline_compartida').is(":visible")){
                        $('#link_recarga_timeline_compartida').click();
                    }
                }
            }

            //Variables y funciones necesarias para la recarga en tiempo real de la Bitácora
            //intervalo_recarga_bitacora=5000;//tiempo en milisegundos minimo entre recargas de la bitácora
            intervalo_recarga_bitacora=0;
            recarga_pendiente_bitacora=false;
            timer1_recarga_bitacora=new Date();
            /*setTimeout(function(){
                if ($("#bloque_bitacora").is(":visible") && recarga_pendiente_bitacora){
                    console.log("Había una o más recargas pendientes de bitácora");
                    recargaAutomaticaBitacora(id_experiencia,tipo_bitacora);
                    timer1_recarga_bitacora=new Date();
                }
                recarga_pendiente_bitacora=false;
            },intervalo_recarga_bitacora);*/

            //Variables necesarias para la recarga en tiempo real de la Visualización
            //intervalo_recarga_vis=60000;
            intervalo_recarga_vis=0;
            recarga_pendiente_vis=false;
            timer1_recarga_vis=new Date();
            /*setTimeout(function(){
                if ($("#bloque_bitacora").is(":visible") && recarga_pendiente_vis){
                    console.log("Había una o más recargas pendientes de visualización");
                    recargarVisualizacion();
                    timer1_recarga_vis=new Date();
                }
                recarga_pendiente_bitacora=false;
            },intervalo_recarga_vis);*/

            var socket;
            function conectarWebsocket(){
              var host = "ws://localhost:9000/websocket/servidor.php";
              //var host = "ws://146.83.222.170:9000/servidor.php";
              //var host = "ws://app.kelluwen.cl:9000/servidor.php";
              try{
                socket = new WebSocket(host);
                //socket = window['MozWebSocket'] ? new MozWebSocket(host) : new WebSocket(host);
                console.log('WebSocket - status '+socket.readyState);
                socket.onopen    = function(mensaje){ 
                    console.log("Bienvenido - status "+this.readyState);
                    if (this.readyState==1){
                        if ($("#bloque_bitacora").is(":visible")){
                            enviarWebsocket("Bitacora <?php echo $_SESSION['id_exp_seleccionada'];?>");
                        }
                    }
                };
                socket.onmessage = function(mensaje){
                    var accion=mensaje.data; 
                    console.log(mensaje.data);
                    if (accion!=""){
                        var instrucciones=accion.split(" ");
                        var update=instrucciones[0];
                        //var id_usuario_update=instrucciones[1];
                        var id_sesion_update=instrucciones[1];//Código agregado por Jordan Barría el 13-12-14
                        var tipo_bitacora=instrucciones[2];
                        var id_experiencia=instrucciones[3];
                        if (update=="Actividad"){// && id_usuario_update!="<?php echo $_SESSION['klwn_id_usuario'];?>"){
                            var bitacora_activa=$("#bloque_bitacora").is(":visible");
                            if (bitacora_activa){
                                timer2_recarga_bitacora=new Date();
                                timer2_recarga_vis=new Date();
                                console.log("Recibido: "+mensaje.data);
                                var diftiempo_recarga_bitacora=timer2_recarga_bitacora.getTime()-timer1_recarga_bitacora.getTime();//Diferencia tiempo desde última recarga de los mensajes de la bitácora
                                console.log("Dif tiempo ultima recarga bitacora: "+diftiempo_recarga_bitacora);
                                var diftiempo_recarga_vis=timer2_recarga_vis.getTime()-timer1_recarga_vis.getTime();//Diferencia tiempo desde última recarga de los mensajes de la visualización
                                console.log("Dif tiempo ultima recarga visualizacion: "+diftiempo_recarga_vis);
                                if (id_sesion_update!="<?php echo $_SESSION['id_sesion'];?>"){
                                    if (diftiempo_recarga_bitacora>=intervalo_recarga_bitacora){
                                        recargaAutomaticaBitacora(id_experiencia,tipo_bitacora);
                                        intervalo_recarga_bitacora=10000;
                                        recarga_pendiente_bitacora=false;
                                        timer1_recarga_bitacora=new Date();
                                        setTimeout(function(){
                                            if ($("#bloque_bitacora").is(":visible") && recarga_pendiente_bitacora){
                                                console.log("Había una o más recargas pendientes de bitácora");
                                                recargaAutomaticaBitacora(id_experiencia,tipo_bitacora);
                                                timer1_recarga_bitacora=new Date();
                                            }
                                        },intervalo_recarga_bitacora);
                                    }else{
                                        recarga_pendiente_bitacora=true;
                                    }
                                    
                                }
                                if (diftiempo_recarga_vis>=intervalo_recarga_vis){
                                    recargarVisualizacion();
                                    intervalo_recarga_vis=45000;
                                    recarga_pendiente_bitacora=false;
                                    timer1_recarga_vis=new Date();
                                    setTimeout(function(){
                                        if ($("#visualizacion_actividad").is(":visible") && recarga_pendiente_vis){
                                            console.log("Había una o más recargas pendientes de visualización");
                                            recargarVisualizacion();
                                            timer1_recarga_vis=new Date();
                                        }
                                    },intervalo_recarga_vis);
                                }else{
                                    recarga_pendiente_vis=true;
                                }



                            }
                        }
                    }
                };
                socket.onclose   = function(mensaje){ 
                    console.log("Desconectado - status "+this.readyState);
                    //Sección de código para establecer reconexión del websocket en caso de que haya sido desconectado de manera fortuita
                    setTimeout(function(){
                        if (!socket){// || (socket && socket.readyState==3)){
                            console.log("Reconectando websocket...");
                            conectarWebsocket();
                        }else{
                            if (socket.readyState==3){
                                console.log("Reconectando websocket...");
                                desconectarWebsocket();
                                conectarWebsocket();
                            }
                        }
                    }, 5000);
                };
                socket.onerror   = function(mensaje){ console.log("Error: "+mensaje.data); };
              }
              catch(ex){ console.log(ex); }

            }

            function enviarWebsocket(mensaje){
              if(!mensaje){ console.log("Mensaje no puede ser null"); return; }
              try{ socket.send(mensaje); console.log('Enviado: '+mensaje); } catch(ex){ console.log(ex); }
            }

            function desconectarWebsocket(){
              socket.close();
              socket=null;
            }

            function registrarClickSeccion(id_sesion,nombre_seccion){
                $.ajax({ 
                    type: "POST", 
                    url: "log_accion_sesion.php", 
                    data:{accion:"click_seccion" , 
                        id_sesion:id_sesion ,
                        nombre_seccion: nombre_seccion},
                    async: false,
                    success: function(data){
                    console.log("Clickee seccion: "+data);
                    }
                });
            }
            
            //Variable necesaria para evitar que ciertos eventos no sean considerados como cierres de pestaña o de navegador
            validar_navegacion=false;//Codigo agregado por Jordan el 30-10-14

            $(document).ready(function(){
                console.log("Inicie header con validar_navegacion= "+validar_navegacion);//Codigo agregado por Jordan
                $("#fb_form").validate({
                    rules:{
                        fb_nombre:{
                            required:true,
                            minlength:3
                        },
                        fb_correo:{
                            required:true,
                            email: true
                        },
                        fb_mensaje: {
                            required: true,
                            minlength: 5
                        }
                    },
                    messages:{

                        fb_nombre: {required:"<?php echo $lang_contactanos_nombre_required; ?>",
                            minlength:"<?php echo $lang_contactanos_nombre_minlenght; ?>"
                        },
                        fb_correo:{required:"<?php echo $lang_contactanos_correo_required; ?>",
                            email:"<?php echo $lang_contactanos_correo_email; ?>"
                        },
                        fb_mensaje: {
                            required: "<?php echo $lang_contactanos_mensaje_required; ?>",
                            minlength: "<?php echo $lang_contactanos_mensaje_minlenght; ?>"
                        }
                    },
                    submitHandler: function() {
                        url = 'feedback.php?';
                        $.post(url, $("#fb_form").serialize(), function(data) {
                            $("#fb_nombre").html("");
                            $("#fb_nombre").val("");
                            $("#fb_correo").html("");
                            $("#fb_correo").val("");
                            $("#fb_mensaje").html("");
                            $("#fb_mensaje").val("");
                            if (data != "1"){
                                $("#fb_respuesta").html("<?php echo $lang_header_inc_problema; ?>");
                                $("#fb_respuesta").show();
                            }
                            else{
                                $("#fb_respuesta").html("<?php echo $lang_header_inc_msj_enviado; ?>");
                                $("#fb_respuesta").show();
                            }
                        });
                    }
                });


                var $tabs2 = $('#tabs2').tabs();

                $('#tab_admin').hide();
                $('#tab_concurso').hide();

                $('#admin').click(function() {
                    if(tabTallerActivo){clearTimeout(timeoutComentario); tabTallerActivo=false;}
                    $tabs2.tabs('select', '#'+'<?php echo $lang_ingresar_admin;?>');
                    $('#tab_admin').show();
                    $('#tab_concurso').hide();
                    $('#tab_inicio').hide();
                    $('#tab_experiencias').hide();
                    $('#tab_disenos').hide();
                    $('#tab_inscribir').hide();
                    $('#tab_taller').hide();
                    return false;
                });

                $('#tabs').tabs();
                
                $('#tabs2').bind('tabsselect', function(event, ui) {
                     if(tabTallerActivo){clearTimeout(timeoutComentario); tabTallerActivo=false;}
                    // Objects available in the function context:
                    //alert(ui.tab);     // anchor element of the selected (clicked) tab
                    //alert(ui.panel);   // element, that contains the selected/clicked tab contents
                    //alert(ui.index);   // zero-based index of the selected (clicked) tab

                });
                
                // $('#tabs2 ul li a').click(function () {location.hash = $(this).attr('href');});

                $('#tabs').tabs();
                $('#tabs').bind('tabsselect', function(event, ui) {
                    // Objects available in the function context:
                    //alert(ui.tab);     // anchor element of the selected (clicked) tab
                    //alert(ui.panel);   // element, that contains the selected/clicked tab contents
                    //alert(ui.index);   // zero-based index of the selected (clicked) tab
                });
                $('#tabs_perfil').tabs();
                $('#tabs_perfil').bind('tabsselect', function(event, ui) {
                    // Objects available in the function context:
                    //alert(ui.tab);     // anchor element of the selected (clicked) tab
                    //alert(ui.panel);   // element, that contains the selected/clicked tab contents
                    //alert(ui.index);   // zero-based index of the selected (clicked) tab

                });

                //$('#caja_avance_exp').load('exp_avance.php?codexp=<?php echo $id_experiencia; ?>');

                cargarAvance();

                $('#boton_recargar_avance').click(function(){
                    cargarAvance();
                    cargarGestionAvance();
                    return false;
                });

                // Dialogs
                $('#dialog_detalles').dialog({
                    autoOpen: false,
                    width: 500,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_cerrar; ?>": function() {
                            $(this).dialog("close");
                        }
                    }
                });
                $('#dialog_documentos').dialog({
                    autoOpen: false,
                    width: 500,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_cerrar; ?>": function() {
                            $(this).dialog("close");
                        }
                    }
                });
                $('#dialog_comentarios').dialog({
                    autoOpen: false,
                    width: 500,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_cerrar; ?>": function() {
                            $(this).dialog("close");
                        }
                    }
                });
                // Dialog Links
                $('#dialog_detalles_link').click(function(){
                    $('#dialog_detalles').dialog('open');
                    return false;
                });
                $('#dialog_documentos_link').click(function(){
                    $('#dialog_documentos').dialog('open');
                    return false;
                });
                $('#dialog_comentarios_link').click(function(){
                    $('#dialog_comentarios').dialog('open');
                    return false;
                });

                $(function () {
                    $('body').css('height','100%');
                });

                $('#dialogo_cargando').dialog({
                    autoOpen: false,
                    //modal: true,
                    minWidth: 175,
                    minHeight: 40,
                    width: 175,
                    height: 40,
                    stack: true,
                    resizable: false
                });

                $("#dialogo_cargando").ajaxStart(function(){
                    //$(this).show();
                    $(".ui-dialog-titlebar").hide();
                    $(this).dialog('open');

                });
                $("#dialogo_cargando").ajaxStop(function(){
                    //$(this).hide();
                    $(this).dialog('close');
                    $(".ui-dialog-titlebar").show();
                });
                $('.perfil_home').click(function() {
                    if(tabTallerActivo){clearTimeout(timeoutComentario); tabTallerActivo=false;}
                    var $linkc = $(this);
                    var $dialog = $('<div></div>')
                    .load($linkc.attr('href'))
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_perfil_usuario_titulo_ventana; ?>',
                        width: 800,
                        height: 600,
                        modal: true,
                        buttons: {
                            "<?php echo $lang_header_inc_cerrar; ?>": function() {
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

                //definicion para modal de encuestas
                $('#encuesta').click(function() {
                    var $linkc = $(this);
                    var $dialog = $('<div></div>')
                    .load($linkc.attr('href'))
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_he_titulo_modal_encuestas; ?>',
                        width: 350,
                        height: 100,
                        modal: true
                    });
                    $dialog.dialog('open');
                    return false;
                });

                //Codigo agregado por Jordan Barría el 28-10-14

                window.onbeforeunload = function(e) {
                    //console.log('location href: '+ location.href +' onbeforeunload validar_navegacion: '+validar_navegacion);
                    /*existe_sesion="<?php echo existeSesion();?>";
                    if (existe_sesion){
                        console.log("Existe sesion para cerrar por onbeforeunload");*/
                        var id_sesion="<?php echo $_SESSION['id_sesion']; ?>";
                        //if(!id_sesion) id_sesion="";
                        var existe_sesion=id_sesion.localeCompare("");
                        if (socket) desconectarWebsocket();
                        console.log("Existe sesion: "+existe_sesion+" id sesion: "+id_sesion);
                        if(existe_sesion!=0 && !validar_navegacion){//(!validar_navegacion || !validar_navegacion_por_hipervinculo)){
                            $.ajax({ 
                              type: "POST", 
                              url: "log_accion_sesion.php", 
                              data:{accion:"cerrar_navegador" , id_sesion: id_sesion},
                              async: false,
                              success: function(data){
                                console.log("Cerrado navegador: "+data+" sesion <?php echo $_SESSION['id_sesion']; ?>");
                              }
                            });
                        } 
                    //}
                    
                };

                window.onunload=function(){
                    console.log("id sesion cerrada ? <?php echo $_SESSION['id_sesion']; ?>");
                }

                window.onload = function(){
                    verifica_salio_navegador="<?php echo verificaSalirNavegador();?>";
                    var id_sesion="<?php echo $_SESSION['id_sesion']; ?>";
                    if (verifica_salio_navegador!=""){
                        if(id_sesion!=""){
                            $.ajax({ 
                              type: "POST", 
                              url: "log_accion_sesion.php", 
                              data:{accion:"revertir_cierre" , id_sesion: id_sesion},
                              success: function(data){
                                console.log("Reverti cierre navegador: "+data);
                              }
                            });
                        }           
                    }
                    if (id_sesion && !socket) conectarWebsocket();//Código agregado por Jordan Barría el 09-11-14
                }

                $(document).bind("keydown",function(e) {
                    var tecla=e.keyCode;
                    if (tecla == 116){
                     validar_navegacion=true;
                     console.log('click f5');
                    }
                });

                $("*", document.body ).click(function( event ) {
                //$jQ11(document).on('click', '*', function() {
                    //var elemento_dom = $( this ).get( 0 );
                    var elemento_dom= $(event.target);
                    if (elemento_dom.is("a")){
                        event.stopPropagation();
                        href_a=elemento_dom.attr("href");
                        if (href_a===undefined || href_a.substring(0,1)=="#" || href_a=="ingresar.php"){
                            validar_navegacion=false;
                        }else{
                            validar_navegacion=true;
                        }
                        var id_sesion="<?php echo $_SESSION['id_sesion']; ?>";
                        var nombre_seccion=elemento_dom.attr("name");
                        if(!nombre_seccion) nombre_seccion=elemento_dom.text().trim();
                        if(!nombre_seccion) nombre_seccion="";
                        if(id_sesion!="" && nombre_seccion!=""){
                            registrarClickSeccion(id_sesion,nombre_seccion);
                        }

                        //console.log( "Id sesion: "+id_sesion+" click en a clase: " + elemento_dom.attr("class") + " id: " + elemento_dom.attr("id"));
                    }else{
                        if (elemento_dom.is("input")){
                            event.stopPropagation();
                            var id_sesion="<?php echo $_SESSION['id_sesion']; ?>";
                            var tipo_input = elemento_dom.attr("type");
                            var nombre_input = elemento_dom.attr("name");
                            if (!nombre_input) nombre_input="";
                            if (tipo_input=="button" && id_sesion!="" && nombre_input!=""){
                                registrarClickSeccion(id_sesion,nombre_input);
                            }
                        }
                    }
                    //event.stopPropagation();
                });

                // Agrega el evento submit para todos los formularios en la página
                $("form").bind("submit", function() {
                    validar_navegacion = true;
                    console.log("submit form");
                });
                 
                // Agrega el evento click para todos los inputs en la página
                $("input[type=submit]").bind("click", function() {
                    validar_navegacion = true;
                    console.log("submit checkbox");
                });
                //fin codigo agregado por Jordan.
                

<?php
//echo "//".$pagina_cargada;


if ($pagina_cargada == "ingresar") {
    echo "                $('#fl_campo_usuario').focus();";
}
?>

    });

        </script>       
    </head>
    <body>
<!--        <div style="display:block;text-align:center;padding:15px;background-color:#DA4A38;
    color:#FFF;text-decoration: blink;font-size:1.1em;font-weight:bold">PLATAFORMA DE PRUEBAS --- PLATAFORMA DE PRUEBAS --- PLATAFORMA DE PRUEBAS </div>-->
        <div id="bloque_encabezado">
            <div class="container_12">
                <div class="grid_3">
                    <div id="encabezado_logo">
                        <img src="<?php echo $config_ruta_img . "logo.gif"; ?>" title="<?php echo $lang_logo_kelluwen; ?>" alt="<?php echo $lang_logo_kelluwen; ?>"/>
                    </div>
                </div>
                <div class="grid_9">
                    <div id="encabezado_menu">
                        <?php
                        if (isset($_SESSION["klwn_usuario"])) {
                            $nombre_usuario = $_SESSION["klwn_usuario"];
                            if (isset($_SESSION["klwn_nombre"]) AND strlen($_SESSION["klwn_nombre"]) > 0){
                                $nombre = $_SESSION["klwn_nombre"];
                                $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
                                $datos = dbObtenerInfoUsuario($_SESSION["klwn_usuario"], $conexion);
                                $_imagen = darFormatoImagen($datos["imagen"], $config_ruta_img_perfil, $config_ruta_img);
                                $imagen_normal = $_imagen["imagen_usuario"];}
//                            if ($_SESSION["klwn_inscribe_diseno"] == 1) {
//
//                                echo "<a class=\"perfil_home\" href=\"contenido_perfil_usuario_modal.php?nombre_usuario=".$nombre_usuario."\" style=\"margin-right:20px;text-decoration:none;\"><img style=\"vertical-align:middle;margin-right:3px\" src=\"".$imagen_normal."\" height=\"20\"><label style=\"margin-bottom:10px;font-weight:bold;cursor:pointer;\">".$nombre."</label></a>"."<a href=\"ingresar.php\" class=\"home\">" . $lang_inicio . "</a> | <a target=\"_blank\" href=\"".$config_ruta_http."wiki/index.php?title=Especial:Entrar&returnto=P%C3%A1gina_Principal&klwnsid=".session_id()."\">".'Wiki'."</a> | <a href=\"ingresar.php?salir=1\">" . $lang_salir . "</a>";
//                          }
                                //consultamos por encuestas que el usuario no ha respondido
                                $conexion_ls = dbConectarMySQL($config_host_bd, $config_usuario_bd_ls, $config_password_bd_ls,$config_bd_ls);
                                $a_encuestas = dbENEncuestasPorUsuario($datos['id'],1,$conexion_ls);
                                //var_dump($a_encuestas);
                                if(count($a_encuestas) > 0){
                                    $boton = "<a id=\"encuesta\" class=\"boton_notifica_encuesta\" href=\"encuestas/enInformacionModal.php?id_usuario=".$datos['id']."\">".$lang_he_notifica_encuesta."</a> | ";
                                }
                                else{
                                    $boton = '';
                                }  
                                 if($_SESSION["klwn_administrador"]==1){
                                    echo "<a href=\"javascript:cambiar_idioma('spanish');\">Español</a><label> | </label><a href=\"javascript:cambiar_idioma('english');\" style=\"margin-right:80px;\">English</a>"."<a class=\"perfil_home\" href=\"contenido_perfil_usuario_modal.php?nombre_usuario=".$nombre_usuario."\" style=\"margin-right:20px;text-decoration:none;\"><img style=\"vertical-align:middle;margin-right:3px\" src=\"".$imagen_normal."\" height=\"20\"><label style=\"margin-bottom:10px;font-weight:bold;cursor:pointer;\">".$nombre."</label></a>".$boton."<a href=\"ingresar.php\" class=\"home\" name='Inicio Header'>" . $lang_inicio . "</a> | <a id=\"admin\" href=\"ingresar.php\">".$lang_ingresar_admin."</a> | <a href=\"ingresar.php?salir=1\">" . $lang_salir . "</a>";
                                }
                                else{
                                    echo "<a href=\"javascript:cambiar_idioma('spanish');\">Español</a><label> | </label><a href=\"javascript:cambiar_idioma('english');\" style=\"margin-right:80px;\">English</a>"."<a class=\"perfil_home\" href=\"contenido_perfil_usuario_modal.php?nombre_usuario=".$nombre_usuario."\" style=\"margin-right:20px;text-decoration:none;\"><img style=\"vertical-align:middle;margin-right:3px\" src=\"".$imagen_normal."\" height=\"20\"><label style=\"margin-bottom:10px;font-weight:bold;cursor:pointer;\">".$nombre."</label></a>".$boton."<a href=\"ingresar.php\" class=\"home\" name='Inicio Header'>" . $lang_inicio . "</a> | <a id=\"admin\" href=\"ingresar.php\">".$lang_ingresar_admin."</a> | <a href=\"ingresar.php?salir=1\">" . $lang_salir . "</a>";
                                }
                        }
                        else {
                            echo "<a href=\"javascript:cambiar_idioma('spanish');\">Español</a><label> | </label><a href=\"javascript:cambiar_idioma('english');\" style=\"margin-right:80px;\">English</a>"."<a href=\"registro.php\">" . $lang_registrarse . "</a> | <a href=\"ingresar.php\">" . $lang_ingresar . "</a>";
                            
                        }
                        ?>

                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
<?php if($_SESSION["klwn_inscribe_diseno"]==1){?>        
<div class="slide-out-div" id="feedback">
    <a class="handle" ></a>
    <h3 class="fb_titulo"><?php echo $lang_header_inc_comunicate;?></h3>
    <p class ="fb_intro"><?php echo $lang_header_inc_escribenos; ?></p>
    <div id="fb_respuesta" style="display: none"></div>
    <p class="fb_respuesta" style="display: none"></p>
    <form  id="fb_form" method="post" action="">
        <div class="fb_formulario">
            <label class="fb_label" ><?php echo $lang_header_inc_nombre." :";?></label> </br>
            <input class="fb_input" id="fb_nombre" name="fb_nombre" tabindex="1" type="text" maxlenght="50" value="<?php echo $_SESSION["klwn_nombre"];?>" />
            <div class="clear"></div>
            </br>
            <label class="fb_label"><?php echo $lang_header_inc_correo." :";?></label></br>
            <input class="fb_input" id="fb_correo" name="fb_correo" tabindex="1" type="text" maxlenght="50" value="<?php echo $_SESSION["klwn_email"];?>"/>
            <div class="clear"></div>
             </br>
            <label><?php echo $lang_header_inc_mensaje." :";?></label></br>
            <textarea class="fb_mensaje" id="fb_mensaje" name="fb_mensaje" tabindex ="1" type="text" maxlenght="1000" size="20" ></textarea></br>
            <input class="submit" type="submit" value="<?php echo $lang_header_inc_enviar;?>">
        </div>
    </form>
</div>

<?php
}
?>