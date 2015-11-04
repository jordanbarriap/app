<?php

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "portafolio/inc/por_funciones_db.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

//parametros parfa llamar exp_revisionpares
$etiqueta_exp         = $_REQUEST["et_exp"];
$es_estudiante        = $_REQUEST["es_estudiante"];
$etiqueta_gemela   = $_REQUEST["et_gemela"];

//parametros para almecanar publicacion
$id_experiencia = $_REQUEST["id_experiencia"];
$id_usuario = $_REQUEST["id_usuario"];
$id_grupo = $_REQUEST["id_grupo"];

$avance_experiencia  = dbExpObtenerAvance($id_experiencia, $conexion);
$id_actividad        = $avance_experiencia["ultima_actividad_id"];

$actividad = dbExpObtenerActividad($id_actividad, $conexion);

//eleccion de tipo de instruccion dependiendo de la actividad
if ($actividad['publica_producto'] == 1) {
    $instruccion = $actividad['instrucciones_producto'];
}
if ($actividad['revisa_pares'] == 1) {
    $instruccion = $actividad['instrucciones_revision'];
}
?>

<div id="cuadro_ingesar">
    <!--
        Seccion izquierda del despliegue del producto (Detalles+Contenido)
    -->
    <form enctype="multipart/form-data" id="rp_formulario_nuevo_trabajo" method="POST" action="portafolio/FuncionIngresaProducto.php?id_actividad=<?php echo $id_actividad ?>&id_experiencia=<?php echo $id_experiencia ?>&id_usuario=<?php echo $id_usuario ?>&id_grupo=<?php echo $id_grupo ?>">
        <!--
                Seccion izquierda superior del ingreso del producto (Detalles+Contenido)
        -->
        <div id="producto_izquierda_formulario">
            <table class="ingresa_trabajo">
                <tr>
                    <td class="rp_resaltado rp_celda_cabecera"><?php echo $lang_por_nombre_trabajo; ?></td>
                    <td class=""><input name="nombre" type="text" id="ingresar_nombre" class="ingresa_texto_titulo"/></td>
                </tr>
                <tr>
                    <td class="rp_resaltado rp_celda_cabecera"><?php echo $lang_por_trabajos_descripcion; ?></td>
                    <td class=""><textarea  name ="texto" id="ingresar_descripcion" class="ingresa_parrafo" rows="4" cols="40"></textarea></td>
                </tr>
            </table>
        </div>

        <div id="producto_derecha_formulario">
            <table class="ingresa_trabajo">
                <tr>
                    <td class="rp_resaltado rp_celda_cabecera"><?php echo $lang_por_link; ?></td>
                    <td class=""><input name="link" type="text" id="ingresar_link" class="ingresa_texto_titulo ingresa_texto_link"/></td>
                </tr>
                <tr>
                   <td class="rp_resaltado rp_celda_cabecera"><?php echo $lang_por_archivo; ?></td>
                    <td class="rp_contenido_formulario_examinar" ><input  id="boton_examinar"  type="file" name="archivo" size="27" /></td>
                </tr>
                <tr>
                   <td class="rp_resaltado rp_celda_cabecera"></td>
                    <td class="rp_contenido_formulario" colspan="2" >
                        <p  id ="rp_texto_tamano_maximo" >
                            <?php echo $lang_por_archivo_tamano_maximo; ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <input id="boton_publicar" type="submit" value="<?php echo $lang_por_publicar_trabajo; ?>"/>
    </form>
</div>

<div id="uploadOutput"></div>

<script  type="text/javascript">
    
    $(document).ready(function() {

        //Funciones que decoloran las instrucciones para poder ingresar el contenido del producto con la funcion del plugin
        $('#ingresar_nombre').tbHinter({
            text: '<?php echo $lang_por_completa_nombre ?>'
        });
        $('#ingresar_descripcion').tbHinter({
            text: '<?php echo $lang_por_completa_descripcion ?>'
        });
        $('#ingresar_link').tbHinter({
            text: '<?php echo $lang_por_completa_link ?>'
        });

        //definicion dle formulario ajax y sus opciones
        $('#rp_formulario_nuevo_trabajo').ajaxForm();

        //definición de modal para la subida de un archivo con un nombre ya existente.
        var $dialog_archivo = $('<div></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_por_modal_error_nombre; ?>',
            modal: true,
           buttons: {
               "<?php echo $lang_por_no; ?>": function() {
                    $(this).dialog("close");
                },
                "<?php echo $lang_por_si; ?>": function(){
                    if((document.getElementById('ingresar_descripcion').value == '<?php echo $lang_por_completa_descripcion ?>')){
                        document.getElementById('ingresar_descripcion').value = '';
                    }
//                     $('#rp_formulario_nuevo_trabajo').submit(function(){ //en el evento submit del fomulario
//                        event.preventDefault();  //detenemos el comportamiento por default
                        //console.log('2');
                        $('#rp_formulario_nuevo_trabajo').ajaxSubmit({
                                success: function() {
                                cargaPestanaTrabajos(<?php echo $id_experiencia; ?>,<?php echo $id_grupo;?>);
                                $('#divnuevotrabajo').dialog('close');
                                }
                            });
//                          var url = $('#rp_formulario_nuevo_trabajo').attr('action');  //la url del action del formulario
//                          var datos = $('#rp_formulario_nuevo_trabajo').serialize(); // los datos del formulario
//                          $.ajax({
//                              type: 'POST',
//                              url: url,
//                              data: datos,
//                              success: function(data){
//                                  cargaResumenAlumno(<?php// echo $id_experiencia; ?>,<?php// echo $id_grupo;?>);
//                                   $dialogo2.dialog('close');
//
//                              }  //funciones que definimos más abajo
//                          });
                      $(this).dialog('close');
                }
            }
        });

        //definición de modal
        var $dialog = $('<div></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_por_error;?>',
            modal: true,
             buttons: {
               "<?php echo $lang_por_cerrar; ?>": function() {
               $(this).dialog("close");
             }
             },
            close: function() {
              // $(this).remove();
         }
        });

       

        var $confirmar = $('<div></div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_por_confirmar;?>',
            modal: true,
             buttons: {
               "<?php echo $lang_por_no; ?>": function() {
                    $(this).dialog("close");
                },
                "<?php echo $lang_por_si; ?>": function(){
                    if((document.getElementById('ingresar_descripcion').value == '<?php echo $lang_por_completa_descripcion ?>')){
                        document.getElementById('ingresar_descripcion').value = '';
                    }
//                     $('#rp_formulario_nuevo_trabajo').submit(function(){ //en el evento submit del fomulario
//                        event.preventDefault();  //detenemos el comportamiento por default
                        //console.log('2');
                        $('#rp_formulario_nuevo_trabajo').ajaxSubmit({
                                success: function() {
                                cargaPestanaTrabajos(<?php echo $id_experiencia; ?>,<?php echo $id_grupo;?>);
                                $('#divnuevotrabajo').dialog('close');
                                }
                            });
//                          var url = $('#rp_formulario_nuevo_trabajo').attr('action');  //la url del action del formulario
//                          var datos = $('#rp_formulario_nuevo_trabajo').serialize(); // los datos del formulario
//                          $.ajax({
//                              type: 'POST',
//                              url: url,
//                              data: datos,
//                              success: function(data){
//                                  cargaResumenAlumno(<?php// echo $id_experiencia; ?>,<?php// echo $id_grupo;?>);
//                                   $dialogo2.dialog('close');
//
//                              }  //funciones que definimos más abajo
//                          });
                      $(this).dialog('close');
                }
            }
        });



   
        //Evento asociado al formulario para mostrar mensajes de confirmacion y alertas en caso de faltar campos
        $('#boton_publicar').click(function(){            
            if((document.getElementById('ingresar_nombre').value == '<?php echo $lang_por_completa_nombre ?>')){
                $dialog.text ('<?php echo $lang_por_nombre_vacio;?>');
                $dialog.dialog('open');              
            }
            else{
                if((document.getElementById('ingresar_link').value == 'Link a la página web del trabajo (http://)') && (document.getElementById('boton_examinar').value == '')  ){
                    alert('<?php echo $lang_por_ingresar_link_archivo; ?>');                  
                }
                else{
                    if((document.getElementById('boton_examinar').value == '')){
                        $confirmar.text('<?php echo $lang_por_confirmar_publicacion; ?>');
                        $confirmar.dialog('open');
                    }
                    else{                       
                        var direccion_archivo= (document.getElementById('boton_examinar').value);  //obtener nombre del archivo
                        var arreglo_dir = direccion_archivo.split("\\");
                        var nombre_archivo = arreglo_dir[arreglo_dir.length-1];
                        var existeArchivo = "";
                       
                        var url = "portafolio/funcionExisteArchivo.php?nombre_archivo="+nombre_archivo+"&id_experiencia="+<?php echo $id_experiencia; ?>+"&id_actividad="+<?php echo $id_actividad; ?>+"&id_grupo="+<?php echo $id_grupo; ?>;
                        $.post(url,function(data){
                            existeArchivo=data;
                            if(existeArchivo==1){
                                $dialog_archivo.text ('<?php echo $lang_por_existe_archivo_mismo_nombre; ?>'+" "+nombre_archivo+". \n"+'<?php echo $lang_por_desea_reemplazar_archivo; ?>');
                                $dialog_archivo.dialog('open');
                                
                            }
                            else{
                                $confirmar.text ('<?php echo $lang_por_confirmar_publicacion; ?>');
                                $confirmar.dialog('open');
                            }
                        });                                       
                                                                      
                    }

                }
            }
            return false;
        });
    });
</script>

