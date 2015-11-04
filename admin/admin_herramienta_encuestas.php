<?php

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
//require_once($ruta_raiz.  "admin/inc/admin_db_functions.inc.php");
//require_once($ruta_raiz.  "admin/inc/admin_functions.inc.php");
require_once($ruta_raiz.  "encuestas/inc/en_db_functions.inc.php");
require_once($ruta_raiz.  "encuestas/inc/en_functions.inc.php");

$modo = $_REQUEST["modo"]; /*modo = 0 carga de los 10 primeros usuarios   modo = 1 carga según limite superior e inferior, para implementar ver más*/

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$a_anios = dbENAniosExperiencias($conexion);

?>
    <div class="admin_volver_administrador">
        <input class="admin_usuario_boton_volver_inicio" type="button" value="<?php echo $lang_he_volver_admin_general; ?>" onclick="javascript: volverAdminInicio();">
        </br>
    </div>

    <div class="admin_encuestas">
        <table class="admin_tabla_categoria">
            <tr>
                <td class ="admin_nueva_encuesta">
                    <p class="admin_subcategoria">
                        <?php echo $lang_he_nueva_encuesta; ?>
                    </p>
                    
                    <table class="admin_tabla_datos admin_margen_izq admin_margen_abajo_cero">
                        <tr>
                            <td>
                                <button id="admin_boton_crear" class="admin_boton_encuesta" onclick="javascript:adminAdministrarHerramientaEncuestas();">
                                    <?php echo $lang_he_nueva_encuesta?>
                                </button>
                            </td>
                            <td class="admin_nueva_encuesta">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="admin_texto_input">
                                                <?php echo $lang_he_id_encuesta; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="admin_margen_izq_10">
                                                <input class="admin_long_id_encuesta" type="text" id="id_encuesta" value="" onkeydown="javascript:ActivarBoton()" onkeyup="javascript:ActivarBoton()"/>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        <tr>
                            <td class="admin_texto_input admin_tabla_datos_cabecera">
                                <div class="admin_tabla_margenes_verticales">
                                    <?php echo $lang_he_grupo_encuestado; ?>
                                </div>
                            </td>
                            <td>
                                <div class="">
                                    <table>
                                        <tr ><input class="admin_margen_abajo" type="checkbox" value="2" id="alumnos" name="Alumnos" onChange="javascript:ActivarBoton()"/><?php echo $lang_he_alumnos; ?></tr>
                                        <tr><input type="checkbox" value="1" id="profesores" name="Profesores" onChange="javascript:ActivarBoton()"/><?php echo $lang_he_profesores; ?></tr>
                                        <tr ><div class="admin_margen_izq"><input type="checkbox" value="0" id="grado_avance" name="grado_avance"/><?php echo $lang_he_grado_avance; ?></div>
                                            <div class="admin_margen_izq admin_margen_abajo">
                                                <select value="0" id="porcentaje">
                                                    <option value ="0" >>= 0</option>
                                                    <option value ="25" >>= 25</option>
                                                    <option value ="50" >>= 50</option>
                                                    <option value ="75" >>= 75</option>
                                                    <option value ="100" >= 100</option>
                                                </select>
                                            </div>
                                        </tr>
                                        <tr><input type="checkbox" value="3" id="colaboradores" name="Colaboradores" onChange="javascript:ActivarBoton()"/><?php echo $lang_he_colaboradores; ?></tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="admin_texto_input admin_tabla_datos_cabecera">
                                <div class="admin_tabla_margenes_verticales">
                                    <?php echo $lang_he_periodo; ?>
                                </div>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td class="">
                                            <div class="">
                                                <select value="0" id="anio" onChange="javascript:ActivarBoton(); ActivarAnio()">
                                                    <option value ="0" ><?php echo $lang_he_desde_mayus; ?></option>
                                                    <?php
                                                    foreach($a_anios as $anio){
                                                        ?>
                                                            <option value ="<?php echo $anio?>" ><?php echo $anio?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="">
                                                <select value="0" id="anio1" onChange="javascript:ActivarBoton()">
                                                    <option value ="0" ><?php echo $lang_he_hasta_mayus; ?></option>
                                                    <?php
                                                    foreach($a_anios as $anio){
                                                        ?>
                                                            <option value ="<?php echo $anio?>" ><?php echo $anio?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan =" 2">
                                            <div class="admin_tabla_margenes_verticales">
                                                <select value="0" id="semestre" >
                                                    <option value ="0" name ="Ambos Semestres"><?php echo $lang_he_ambos_sem_defecto; ?></option>
                                                    <option value ="1" name="Primer Semestre"><?php echo $lang_he_primer_sem; ?></option>
                                                    <option value ="2" name="Segundo Semestre"><?php echo $lang_he_segundo_sem; ?></option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <button id="admin_boton_activar" class="admin_margen_izq admin_boton_encuesta admin_flotar_derecha admin_boton_activr_encuesta" onclick="javascript:adminAdministrarHerramientaEncuestas();">
                        <?php echo $lang_he_activar_encuesta?>
                    </button>
                    </td>
                <td>
                    <p class="admin_subcategoria">
                        <?php echo $lang_he_encuestas_activas; ?>
                    </p>
                    <div class="admin_div_tabla_encuestas_activas admin_div_tabla_encuestas">
                    </div>
                    <p class="admin_subcategoria">
                        <?php echo $lang_he_encuestas_cerradas; ?>
                    </p>
                    <div class="admin_div_tabla_encuestas_cerradas admin_div_tabla_encuestas">
                    </div>
                </td>
            </tr>
        </table>
    </div>

<script type="text/javascript"  >
    
    //link para volver al administrado general
    function volverAdminInicio(){
        $.get('admin/admin_inicio.php?', function(data) {
            $('.admin_contenido').html(data);
        });
    }

    //var id_encuesta;

    //funcion que habilita el boton de asignar una vez introducidos los datos
    function ActivarBoton(){
        if($('#id_encuesta').val().length  > 1){
            if($('#alumnos').attr('checked') || $('#profesores').attr('checked')  || $('#colaboradores').attr('checked')){
                if($('#anio').val() != '0' && $('#anio1').val() != '0' && //caso distinto del defecto
                   $('#anio').val() <= $('#anio1').val()){ // anio desde debe se rmenor o igual que hasta
                        $('#admin_boton_activar').css('background-color','#EDA541');
                        $('#admin_boton_activar').attr('disabled',false);
                        return true;
                }
            }
        }
        $('#admin_boton_activar').attr('disabled',true);
        $('#admin_boton_activar').css('background-color','#E5E5E5');
        return false;
    }

    //function para actibar combobox de anio 'hasta''
    function ActivarAnio(){
        if($('#id_anio').val() != '0'){
            $('#anio1').attr('disabled', false);
            return true;
        }
        return false;
    }

    //cargar tablas al costado derecho con info de las ecnuestas
    function CargarTablas(){
        url_tabla_activas = 'encuestas/enTablaEncuestas.php?bandera=1';
        $.post(url_tabla_activas,function(data){
            $('.admin_div_tabla_encuestas_activas').html(data);
        });
        url_tabla_cerradas = 'encuestas/enTablaEncuestas.php?bandera=0';
        $.post(url_tabla_cerradas,function(data){
            $('.admin_div_tabla_encuestas_cerradas').html(data);
        });
    }

    $(document).ready(function(){

        //seteamos los campos id encuesta como solamente numericos gracias al plugin
        $('#id_encuesta').numeric();

        //seteo de condiciones iniciales del formulario
       $('#admin_boton_activar').attr('disabled',true);
       $('#admin_boton_activar').css('background-color','#E5E5E5');
       $("#grado_avance").attr('disabled',true);
       $('#porcentaje').attr('disabled','disabled');
       $('#anio1').attr('disabled',true);

       //llamada a funcion de cargado de tablas
       CargarTablas();

       //definicion click de boton para ir a LimeSurvey
       $('#admin_boton_crear').click(function(){
           url = '../limesurvey/admin/admin.php?action=newsurvey';
           window.open(url);
           return false;
       });

       //definicion comportamiento parametros adicionales profesor (grado de avance)
       $("#profesores").click(function() {
            if($("#profesores").is(':checked')) {
                $("#grado_avance").attr('disabled',false);
            } else {
                $("#grado_avance").attr('disabled',true);
            }
        });

        //habilitacion campo porcentaje campo de avance
        $("#grado_avance").click(function(){
             if($("#grado_avance").is(':checked')) {
                $("#porcentaje").attr('disabled',false);
            } else {
                $("#porcentaje").attr('disabled',true);
            }
        });

       //click sobre el boton de activar encuesta
       $('#admin_boton_activar').click(function(){

           //asignamos id de la encuesta a variable
            var id_encuesta = $('#id_encuesta').val();

            //verificamos que grupos estan agregados para el mensaje modal
            var grupo = new Array();
            var grupo_valores = new Array
            if($('#alumnos').attr('checked') ==true)  {grupo.push($('#alumnos').attr('name')); grupo_valores.push($('#alumnos').attr('value'));}
            if($('#profesores').attr('checked')==true)  {grupo.push($('#profesores').attr('name'));grupo_valores.push($('#profesores').attr('value'));}
            if($('#colaboradores').attr('checked')==true) {grupo.push($('#colaboradores').attr('name'));grupo_valores.push($('#colaboradores').attr('value'));}

            //asignamos el semestre a una variable
            var semestre_valor = $('#semestre').val();

            //verificamos avance profesor activado o no
            //if($('#porcentaje').attr('disabled') == true){var avance = 0;}
            //else{
                var avance = $('#porcentaje').val();
            //}

            //nombre del semestre
            var semestre_nombre;
            switch(semestre_valor){
                case '0':
                    semestre_nombre = '<?php echo $lang_he_ambos_sem; ?>';
                break;
                case '1':
                    semestre_nombre = '<?php echo $lang_he_primer_sem; ?>';
                break;
                case '2':
                    semestre_nombre = '<?php echo $lang_he_segundo_sem; ?>';
                break
            }

            //asignamos el anio a una variable
            var anio = $('#anio').val();
            var anio1 = $('#anio1').val();

            //asignar estado de avance a una variable
            var avance = $('#porcentaje').val();
            
            //generar cadena para despliegue de grupos en ventana modal
            if(grupo.length == 3 ){
                var cadena  =  '<b>'+grupo.pop()+'</b>, <b>'+ grupo.pop()+'</b> y <b>'+ grupo.pop()+'</b>';
            }
            else{
                if(grupo.length == 2 ){
                    var cadena = '<b>'+grupo.pop()+'</b> y <b>'+grupo.pop()+'</b>';
                }
                else{
                    var cadena = '<b>'+grupo.pop()+'</b>';
                }
            }

            //definicion modal para confirmacion de asignacion
             var $dialog = $('<div></div>')
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_he_confirmar_encuesta;?>',
                modal: true,
                 buttons: {
                   "<?php echo $lang_he_si; ?>": function() {
                       grupo_valores_string = grupo_valores.join('-');
                       url_funcion = 'encuestas/enActivaEncuesta.php?id_encuesta='+id_encuesta+'&a_grupo='+grupo_valores_string+'&anio='+anio+'&anio1='+anio1+'&semestre='+semestre_valor+'&avance='+avance;
                       $.post(url_funcion,function(){
                        });
                        CargarTablas();
                       $(this).dialog("close");
                     },
                     "<?php echo $lang_he_no; ?>": function(){
                        $(this).dialog("close");
                     }
                 },
                close: function() {
                   $(this).remove();
             }
            });

            //definicion modal de atencion en caso que no se cumpla algun requisito
            var $dialog2 = $('<div></div>')
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_he_encuesta_no_existe;?>',
                modal: true,
                close: function() {
                   $(this).remove();
             }
            });

            //definicion de texto para modal ('de' o 'del' dependiendo si es semestre o no)
            if(semestre_valor == 0){
                var de = '<?php echo $lang_he_de; ?>';
            }
            else{
                var de = '<?php echo $lang_he_del; ?>';
            }

           //definicion de textos para ventana modal una vez presionado el boton asignar
           var contenidoHTML = '<p><?php echo $lang_he_confirmar;?><b> '+ id_encuesta +'</b> a '+cadena+' '+'<?php echo $lang_he_de; ?>'+' <b>'+semestre_nombre+'</b> <?php echo $lang_he_desde_minus; ?> <b>'+anio+'</b> <?php echo $lang_he_hasta_minus; ?> <b>'+anio1+'</b></p>';
           var contenidoHTML2 = '<p><?php echo $lang_he_no_existe_enunciado;?></p>';
           var contenidoHTML3 = '<p><?php echo $lang_he_ya_asignada;?></p>';
           var contenidoHTML4 = '<p><?php echo $lang_he_tabla_no_existe;?></p>';

            //verificamos la existencia de la encuesta
            url_verifica = 'encuestas/enVerificaEncuesta.php?id_encuesta='+id_encuesta;

            $.post(url_verifica, function(data){
                switch(data){
                    case '1'://acepta
                        $dialog.append(contenidoHTML);
                        $dialog.dialog('open');
                        break;
                    case '0'://tabla no existe
                        $dialog2.append(contenidoHTML2);
                        $dialog2.dialog('open');
                        break;
                    case '-1'://tabla asignada
                        $dialog2.append(contenidoHTML3);
                        $dialog2.dialog('open');
                    break;
                     case '-2'://tabla no ha sido creada
                        $dialog2.append(contenidoHTML4);
                        $dialog2.dialog('open');
                    break;
                }
            });
       });


    });
</script>