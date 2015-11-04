<?php
/* 
 * 
 */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$rr_id_mensaje = $_REQUEST["id_mensaje"];
$rr_origen = $_REQUEST["origen"];
$id_profesor = $_REQUEST["id_profesor"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$es_profesor_responsable =0;
if($id_profesor == $_SESSION["klwn_id_usuario"]){
    $es_profesor_responsable =1;
}
/*Resumen Bitácora*/
if($rr_origen == 0){
    $_mensajes_en_respuesta_resumen = dbObtenerMensajesEnRespuestaResumen($rr_id_mensaje, $conexion);
    $num_mensajes_en_respuesta = dbObtenerNumMensajesEnRespuesta($rr_id_mensaje, $conexion);
    $num_mensajes_en_respuesta_resumen = count($_mensajes_en_respuesta_resumen);
    $j=$num_mensajes_en_respuesta_resumen-1;
    if($_mensajes_en_respuesta_resumen[$j]){
        if($num_mensajes_en_respuesta > 3){
        ?>
            <div class ="ver_comentarios_mensaje<?php echo $class_gemela_respuesta;?>" id="ver_comentarios_mensaje<?php echo $rr_id_mensaje;?>">
                <button class ="boton_ver_comentarios<?php echo $class_gemela_respuesta;?>" id ="ver_comentarios<?php echo $rr_id_mensaje;?>" > <?php echo $lang_comentar_ver1." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?> </button>
            </div>
            <div class ="ocultar_comentarios_mensaje<?php echo $class_gemela_respuesta;?>" id="ocultar_comentarios_mensaje<?php echo $rr_id_mensaje;?>">
                <button class ="boton_ocultar_comentarios<?php echo $class_gemela_respuesta;?>" id ="ocultar_comentarios<?php echo $rr_id_mensaje;?>"> <?php echo $lang_comentar_ocultar." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?></button>
            </div>
        <?php
        }
        ?>
        <div class ="resumen_respuestas_mensaje" id="resumen_respuestas_mensaje<?php echo $rr_id_mensaje;?>">
            <ul class="listado_mensajes_respuesta">
                <?php
                while($_mensajes_en_respuesta_resumen[$j]){
                    $_imagenes_usuario = darFormatoImagen($_mensajes_en_respuesta_resumen[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
                    ?>
                    <li class="listado_respuestas">
                        <div  class="respuesta_mensaje<?php echo $class_gemela_respuesta;?> respuesta_msj" id="<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"];?>">
                            <div class="respuesta_msg_avatar">
                                <img class="imagen_respuesta" src= "<?php echo $_imagenes_usuario["imagen_usuario"] ;?> "/>
                            </div>
                            <div  class="respuesta_msg_texto">
                                <p>
                                    <a id="ventana_perfil<?php echo $_mensajes_en_respuesta_resumen[$j]["id"];?>" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>"<?php } ?> class ="link_nombre_bitacora" title ="<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>" ><?php echo $_mensajes_en_respuesta_resumen[$j]["nombre"];?></a><?php echo " ".$lang_bitacora_dice.': ';?>
                                    <?php echo enlazarURLs($_mensajes_en_respuesta_resumen[$j]["mensaje"]);?>
                                </p>
                                <div id= "time" class="respuesta_msg_datos">
                                    <?php echo relativeTime($_mensajes_en_respuesta_resumen[$j]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
                                </div>
                             </div>
                            <div class="eliminar_respuesta" id="eliminar_respuesta<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"];?>">
                              <?php
                              if($es_profesor_responsable){
                              ?>
                                <button  class="boton_eliminar_msj" onclick="javascript:eliminarRespuesta(<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"] ?>);">
                                    <img src="<?php echo $config_ruta_img;?>eliminar.png" title ="<?php echo $lang_resumen_resp_prof_responsable;?>" />
                                </button>
                              <?php  
                              }
                              ?>
                          </div>
                        </div>
                    </li>
                    <?php
                    $j--;
                }
                ?>
            </ul>
        </div>
        <div class ="respuestas_mensaje<?php echo $class_gemela_respuesta;?>" id="respuestas_mensaje<?php echo $rr_id_mensaje;?>">
        </div>
        <div class='responder_mensaje<?php echo $class_gemela_respuesta;?>' id="responder<?php echo $rr_id_mensaje; ?>">
            <form action="" method="post" name="<?php echo $rr_id_mensaje; ?>">
                <textarea name="<?php echo $rr_id_mensaje; ?>" class="txt_mensaje" id="contenido_textbox<?php echo $rr_id_mensaje; ?>"></textarea>
                <input type="submit"  value=" Responder"  class="boton_enviar_comentario" id="sub_comentario<?php echo $rr_id_mensaje; ?>" name="<?php echo $rr_id_mensaje; ?>" />
                <div class="caracteres_restantes_comentario" id="caracteres_restantes_comentario<?php echo $rr_id_mensaje;?>">
                    <span id="n_caracteres_restantes_comentario<?php echo $rr_id_mensaje;?>">
                        <?php echo $config_char_disponibles;?>
                    </span>
                    <?php echo " ".$lang_caracteres_restantes.".";?>
                </div>
            </form>
        </div>
   <?php
    }
}
/*Resumen Kellu - Muro*/

/*Resumen Mural usuario*/
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.eliminar_respuesta').hide();
        
        $('.boton_enviar_comentario').attr('disabled', true);
        $('.boton_enviar_comentario').hide();
        $('.caracteres_restantes_comentario').hide();
        $('.ocultar_comentarios_mensaje<?php echo $class_gemela_respuesta;?>').hide();
        $('#ver_comentarios<?php echo $rr_id_mensaje; ?>').click(function(){
            id_mensaje_original = <?php echo $rr_id_mensaje; ?>;
            url_respuestas = 'bitacora_respuestas_mensaje.php?id_mensaje='+id_mensaje_original+'&clase_gemela=<?php echo $class_gemela_respuesta;?>';
            $.get(url_respuestas, function(data) {
                $('#resumen_respuestas_mensaje<?php echo $rr_id_mensaje; ?>').hide();
                $("#ver_comentarios_mensaje<?php echo $rr_id_mensaje;?>").hide();
                $('#respuestas_mensaje<?php echo $rr_id_mensaje; ?>').html(data);
                $('#respuestas_mensaje<?php echo $rr_id_mensaje; ?>').show();
                $('#ocultar_comentarios_mensaje<?php echo $rr_id_mensaje; ?>').show();
            });
        });
        $('.respuesta_msj').hover(function() {
              var element2 = $(this);
              var Id = element2.attr("id");
            $("#eliminar_respuesta"+Id).show();
          }, function() {
              var element = $(this);
              var Id = element.attr("id");
            $("#eliminar_respuesta"+Id).hide();
          });
        $('#ocultar_comentarios<?php echo $rr_id_mensaje; ?>').click(function(){
            $('#ocultar_comentarios_mensaje<?php echo $rr_id_mensaje; ?>').hide();
            $('#resumen_respuestas_mensaje<?php echo $rr_id_mensaje; ?>').show();
            $('#respuestas_mensaje<?php echo $rr_id_mensaje; ?>').hide();
            $("#ver_comentarios_mensaje<?php echo $rr_id_mensaje;?>").show();

        });
        $('#contenido_textbox<?php echo $rr_id_mensaje; ?>').click(function(){
                    $('#sub_comentario<?php echo $rr_id_mensaje; ?>').show();
                    $('#caracteres_restantes_comentario<?php echo $rr_id_mensaje; ?>').show();
                    return false;
        });
        $('#contenido_textbox<?php echo $rr_id_mensaje; ?>').keyup(function(){
                      var charlength = $(this).val().length;
                      var car_disponibles = <?php echo $config_char_disponibles;?>;
                      var car_restantes = car_disponibles - charlength;
                      $('#n_caracteres_restantes_comentario<?php echo $rr_id_mensaje; ?>').html(car_restantes);
                      if ((charlength > car_disponibles) || (charlength < 3)){
                          $('#sub_comentario<?php echo $rr_id_mensaje; ?>').attr('disabled', true);

                      }else{
                          $('#sub_comentario<?php echo $rr_id_mensaje; ?>').attr('disabled', false);
                      }
                });
        $(".boton_enviar_comentario").click(function(){
            var element = $(this);
            var Id = element.attr("name");
            var test = $("#contenido_textbox"+Id).val();
            var dataString = 'textcontent='+ test + '&en_respuesta_a=' + Id;
            if(test=='')
            {
                alert("<?php echo $lang_resumen_resp_ingresa_comentario; ?>");
            }
            else
            {
//                $("#flash"+Id).show();
//                $("#flash"+Id).fadeIn(400).html('<img src="<?php echo $config_ruta_img;?>cargando_respuesta_mensaje.gif" align="absmiddle" > cargando.....');
                $.ajax({
                    type: "POST",
                    url: "bitacora_enviar_post.php",
                    data: dataString,
                    cache: false,
                    success: function(){
                    btRespuestasMensaje(Id);
                    }
                });

            }
            return false;
        });
    });
</script>