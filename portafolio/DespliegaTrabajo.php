<?php

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "portafolio/inc/por_funciones_db.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");


//Parametros por URL
$id_grupo = $_REQUEST["id_grupo"];
$id_usuario = $_REQUEST['id_usuario'];
$id_actividad = $_REQUEST['id_actividad'];
$id_producto = $_REQUEST["id_producto"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd); //confirmar si es necesario
$producto = dbPObtenerDatosProducto($id_producto, $conexion);


//informacion de actividad y experiencia
$experiencia_producto = dbExpObtenerInfo($producto['id_experiencia'], $conexion);
$nombre_actividad_producto = dbPObtenerNombreActividad($producto['id_actividad'], $conexion);
$nombre_grupo_producto = dbPNombreGrupo($producto['id_grupo'], $conexion);

//obtenemos fecha de fin de experiencia para ver si mostramos atributos una vez finalizada la experiencia
//$fecha_fin_exp = $experiencia_producto['fecha_termino'];

$_imagenes_usuario = darFormatoImagen($_SESSION["klwn_foto"], $config_ruta_img_perfil, $config_ruta_img);
//$imagen_usuario = $_imagenes_usuario["imagen_usuario"];
$imagen_grande = $_imagenes_usuario["imagen_grande"];
//$instruccion = "";

//Agregamos visita al contador en la BD
dbPAgregaVisita($id_producto, $conexion);
?>


<?php 
    if($id_producto == null){?>
        <div id ='cuadro_sin_trabajo'><p style="font-weight: 600; padding: 25px">El grupo no ha subido su trabajo al Portafolio en la actividad de publicación correspondiente.</p></div>
    <?php }
    else{?>
    <div id= "cuadro_trabajo">    <!--
         Seccion izquierda del despliegue del producto (Detalles+Contenido)
        -->
        <div id="producto_izquierda">
            <!--
                Seccion izquierda superior del despliegue del producto (Detalles+Contenido)
            -->
        <div id="producto_izquierda_superior">
            <div class="rp_titulo_pagina"><?php echo $producto["nombre"];?><span style="float: right; font-size: smaller"><?php echo  $lang_por_visitas.': '.$producto["visitas"];?></span></div>
            <div class="rp_despliegue_trabajo">
                <div class="rp_tabla_producto">
                    <table class="rp_margen_tabla">
                        <tr>
                            <td class="rp_resaltado rp_agrandado"><?php echo $lang_por_publicado_por; ?></td>
                            <td class=""><?php echo "<span class=\"rp_resaltado\">".$lang_function_inc_grupo." ". obtenerNombreGrupo($nombre_grupo_producto["nombre"]) . "</span>, " . $experiencia_producto["curso"] . ", " . $experiencia_producto['colegio'] . ", " . $experiencia_producto['localidad']; ?></td>
                            <td class="rp_resaltado rp_agrandado "><?php echo $lang_por_trabajos_fecha; ?></td>
                            <td class=""><?php echo formatearFechaHora($producto["fecha"]) . ", " . $lang_por_durante_la_actividad . " <span class=\"rp_resaltado\">" . $nombre_actividad_producto . "</span>"; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="rp_tabla_producto rp_borde_cero">
                    <table class="rp_margen_tabla">
                        <tr>
                            <td class="rp_resaltado rp_agrandado"><?php echo $lang_por_trabajos_descripcion; ?></td>
                            <td class=""><?php echo $producto["descripcion"]; ?></td>
                        </tr>
                        <?php if (!is_null($producto['link']) && $producto['link'] != "") { ?>
                            <tr>
                                <td class="rp_resaltado"><center><img src= "<?php echo $config_ruta_img . 'rp_link_32.png'; ?>" alt="<?php echo $lang_por_link; ?>" title="<?php echo $lang_por_link; ?>" /></center></td>
                                <?php
                                $enlace = $producto['link'];
                                if (substr($enlace, 0, 4) != "http") {
                                    $enlace = "http://" . $enlace;
                                }
                                ?>
                                <td class="rp_centro_vertical "><a href="<?php echo $enlace; ?>" target="_blank" ><u><?php echo substr($producto['link'],0,750) ?></u></a></td>
                            </tr>
                        <?php
                        }
                        if ($producto['ruta']) {
                        ?>
                            <tr>
                                <td class="rp_resaltado "><center><img src= "<?php echo $config_ruta_img . 'rp_documento_32.png'; ?>" alt="<?php echo $lang_por_archivo; ?>" title="<?php echo $lang_por_archivo; ?>" /></center></td>
                                <td class="rp_centro_vertical" colspan="2"><a id="link_archivo" href="<?php echo $config_ruta_documentos_pares_http . 'exp_' . $producto["id_experiencia"] . '/act_' . $producto["id_actividad"] . '/' . $producto["id_grupo"] . '_' . $producto['ruta'] ?>" target="_blank"><u><?php echo substr($producto['ruta'],0,75); ?></u></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
                </div>
        </div>

         <!--Comentarios de la publicacion-->
        <div class="rp_cuadro_comentarios_producto">
            <div class="rp_titulo_pagina"><?php echo $lang_por_comentarios;?></div>
            <div id="bloque_posteo_usuario">
                <div id="imagen_usuario"><img alt="<?php echo $_SESSION["klwn_usuario"]; ?>" src="<?php echo $imagen_grande; ?>" /></div>
                <div id="nuevo_mensaje_rp">
                    <form id="rp_form_comentario" name="rp_form_comentario" action="">
                        <div id="caja_texto">
                            <textarea id="txt_nuevo_post_id" name="txt_nuevo_post" cols="30" rows="6"></textarea>
                        </div>
                        <div class="opciones_mensaje">
                            <div id="enviar_mensaje_rp"><button id="rp_boton_enviar_comentario"><?php echo $lang_por_boton_enviar_mensaje; ?></button></div>
                        </div>
                    </form>
                </div>
    <!--            <div class="clear"></div>
                <div id="resultado_envio_comentario">&nbsp;</div>-->
            </div>
            <!--Cuadro donde seran publicados los comentarios realizados-->
            <div id="comentarios">
            </div>
        </div>
        </div>
    </div>
    <?php }
?>

<script type="text/javascript">

    function desplegarComentarios(producto){
        url = "portafolio/DespliegaComentarios.php?id_producto="+producto;
        $.post(url, function(data){
            $('#comentarios').html(data);
        });
        return false;
    }

    $(document).ready(function(){

        
        //verificamos si la experiencia ha terminado para desactivar el boton comentar en ese caso
        var fecha_termino = '<?php echo $experiencia_producto['fecha_termino'];?>';
        //verificar si la experiencia ha terminado para desactivar link
        //console.log(fecha_termino);
        if(fecha_termino != ''){
           $("#rp_boton_enviar_comentario").attr("disabled", "disabled");
        }

        //carga lista de comentarios realizados sobre el trabajo
        desplegarComentarios(<?php echo $id_producto ?>);

        //$("#resultado_envio_comentario").hide();

        //Evento click de boton Modificar para ejecutar llamada a funcion de despliegue del formulario de modificaci�n
        $('#boton_modificar').click(function(){
            modificarTrabajo(<?php echo $id_producto ?>);
            return false;
        });

    //definición de modal
    var $dialog = $('<div></div>')
    .dialog({
        autoOpen: false,
        title: '<?php echo $lang_por_alerta_campos_evaluacion;?>',
        modal: true,
        close: function(ev, ui) {
           $(this).remove();
     }
    });

    $('#rp_boton_enviar_comentario').click(function(){
        var url = 'portafolio/FuncionIngresaComentario.php?id_usuario=<?php echo $_SESSION["klwn_id_usuario"] ?>&id_grupo=<?php echo $id_grupo ?>&id_producto=<?php echo $id_producto ?>';

        $.post(url, $("#rp_form_comentario").serialize(), function(data){
            if(parseInt(data) != '0'){
                desplegarComentarios(<?php echo $id_producto ?>);
                $("#txt_nuevo_post_id").html("");
                $("#txt_nuevo_post_id").val("ffggfgggggg");
            }
            else{
                $dialog.text ('<?php echo $lang_por_alerta_error_comentario;?>');
                $dialog.dialog('open');    
            }
        });
        return false;
    });
});
</script>

