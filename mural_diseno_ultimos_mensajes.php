<?php
/**
 * Despliega los la lista de mensajes de la bitácora según los filtros pasados
 * como parámetros.
 * Los parametros recibidos son:
 * $_REQUEST["id_usuario"]: identificador de la experiencia

 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Katherine Inalef - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 **/
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");

$id_diseno          = $_REQUEST["id_diseno"];
$filtro             = $_REQUEST["filtro"];
$nivel_intrusion    = $nivel_intrusion_md;
$conexion           = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
if(!is_null($filtro)&& strlen($filtro)>0){
    $_mensajes  = dbMuralDisenoMensajesFiltro($conexion,$id_diseno,$filtro);
}
else{
    $_mensajes  = dbMuralDisenoMensajes($conexion,$id_diseno,$nivel_intrusion);
}
$num_mensajes       = count($_mensajes);
$grupos_msj_md      = $num_mensajes/10;


if ($_mensajes==null){
    echo "<br />";
    echo $lang_no_hay_mensajes;
    $id_ultimo_mensaje = -1;
}
else {
    $i=0;
    if($_mensajes == "" ){
        $md_id_ultimo_mensaje = 0;
    }
    else{
        $md_id_ultimo_mensaje = $_mensajes[$i]["id_mensaje"];
    }

        while($_mensajes[$i]) {
			$nombre_actividad = dbRECObtenerNombreAct($_mensajes[$i]["id_actividad"],$conexion);
            //niveles de intrusion
            $id_mensaje_actual      = $_mensajes[$i]["id_mensaje"];
            $_valoracion_mensaje    = dbMuralDisenoObtenerMeGustaMensaje($id_mensaje_actual, $conexion);
            $num_valoraciones       = count($_valoracion_mensaje);
            $usuario_gusta_mensaje  = dbMuralDisenoGustaMensaje($_SESSION["klwn_id_usuario"], $id_mensaje_actual, $conexion);
            $_mensajes_en_respuesta_resumen     = dbMuralDisenoObtenerMensajesEnRespuestaResumen($id_mensaje_actual, $conexion);
            $num_mensajes_en_respuesta          = dbMuralDisenoObtenerNumMensajesEnRespuesta($id_mensaje_actual, $conexion);
            $num_mensajes_en_respuesta_resumen  = count($_mensajes_en_respuesta_resumen);
            $_up_imagenes_usuario               = darFormatoImagen($_mensajes[$i]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
            $up_imagen  = $_up_imagenes_usuario["imagen_usuario"];
            $tipo       = $_mensajes[$i]["tipo"];
            $class      = intval($i/10);
            $resto      = $i%10;
            if($class<1){
                $class = "";
            }
            $clase_tipo = "";
            if($_mensajes[$i]["tipo"]==6){
                $clase_tipo = "tipo_6";
            }
            if($_mensajes[$i]["tipo"]==7){
                $clase_tipo = "tipo_7";
            }
            ?>
            <div  class = "msj<?php echo $class." ".$clase_tipo; ?>"  >
                <div class="md_mensaje ">
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario"];?>" class ="md_link_nombre" title ="<?php echo $_mensajes[$i]["usuario"];?>" >
                        <div class="md_msg_avatar">
                        <img src= "<?php echo $up_imagen;?> " >
                    </div>
                    </a>
                    
                    <div class="md_msg_texto">
                        <p>
                            <?php 	if($tipo == 6){ // Notificacion comentario al finalizar actividad ?>
                                                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario"];?>" class ="md_link_nombre" title ="<?php echo $_mensajes[$i]["usuario"];?>" ><?php echo $_mensajes[$i]["nombre"];?></a><?php echo " ".$_lang_mural_diseno_textos_mensajes[$tipo]." <b>".$nombre_actividad."</b>: ".enlazarURLs($_mensajes[$i]["mensaje"]);?>
                            <?php 	}
                                        else{
                                            if($tipo == 7){ // Notificacion comentario en la ventana Comentarios de Actividad   ?>
                                                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario"];?>" class ="md_link_nombre" title ="<?php echo $_mensajes[$i]["usuario"];?>" ><?php echo $_mensajes[$i]["nombre"];?></a><?php echo " ".$_lang_mural_diseno_textos_mensajes[$tipo]." <b>".$nombre_actividad."</b>: ".enlazarURLs($_mensajes[$i]["mensaje"]);?>
                    <?php                   }
                                            else{  // comentarios originales  ?>
                                                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario"];?>" class ="md_link_nombre" title ="<?php echo $_mensajes[$i]["usuario"];?>" ><?php echo $_mensajes[$i]["nombre"];?></a><?php echo " ".$_lang_mural_diseno_textos_mensajes[$tipo].": ".enlazarURLs($_mensajes[$i]["mensaje"]);?>
                    <?php                   }
                                        }
                            ?>
                        </p>
                        <div id= "time" class="md_msg_datos">
                            <?php echo relativeTime($_mensajes[$i]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales);?>

                        </div>
                        <div id="md_gusta_mensaje<?php echo $id_mensaje_actual?>">
                            <div class ="md_megusta_mensaje">
                                <?php
                                if($usuario_gusta_mensaje<1){
                                    echo "<button class = \"md_boton\" id = \"md_gusta".$id_mensaje_actual."\" >".$lang_mural_diseno_me_gusta." </button> ";
                                }
                                else{
                                    echo "<button class = \"md_boton\" id = \"md_nogusta".$id_mensaje_actual."\" >".$lang_mural_diseno_ya_no_me_gusta."</button> ";
                                }
                                ?>
                                
                            </div>
                            <?php
                                if($num_valoraciones>0){
                                ?>
                                <div class ="md_ver_megusta">
                                    <a class ="boton_ver_megusta" id ="md_usuarios_gusta<?php echo $_mensajes[$i]["id_mensaje"];?>" href= "usuarios_gusta_mensaje.php?">
                                      <img src="<?php echo $config_ruta_img;?>me_gusta_dm.png" title ="<?php echo $lang_num_megusta;?>" alt= <?php echo $lang_num_megusta;?>/>
                                    </a>
                                    <span><?php echo $num_valoraciones;?></span>
                                </div>
                                <?php
                                }
                                ?>
                        </div>
                        <?php
                        if($num_mensajes_en_respuesta < 1){
                        ?>
                        <div class ="md_comentar_mensaje">
                            <a class ="md_boton_comentar" id ="<?php echo$_mensajes[$i]["id_mensaje"];?>" href="#"><?php echo $lang_mural_diseno_responder;?></a>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                if($num_mensajes_en_respuesta<1){
                ?>
                <div id = "md_responder_msj<?php echo $_mensajes[$i]["id_mensaje"];?>">
                    <div class='md_msj' id="md_msj<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></div>
                    <div class='md_panel' id="md_panel<?php echo $_mensajes[$i]["id_mensaje"]; ?>">
                        <form action="" method="post" name="<?php echo $_mensajes[$i]["id"]; ?>">
                            <textarea class="md_textbox"  id="md_textbox<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></textarea> <br/>
                            <input type="submit" value=" <?php echo $lang_mural_dis_ult_msj_responder; ?> "  class="md_boton_enviar" id="md_boton<?php echo $_mensajes[$i]["id_mensaje"]; ?>" name="<?php echo $_mensajes[$i]["id_mensaje"]; ?>" />
                        </form>
                    </div>
                </div>
                <?php
                }
                ?>
                <?php
                $j=$num_mensajes_en_respuesta_resumen-1;
                if($_mensajes_en_respuesta_resumen[$j]){
                    if($num_mensajes_en_respuesta > 3){
                    ?>
                    <div class ="md_ver_comentarios_mensaje" id="md_ver_comentarios_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                        <button class ="md_boton_respuestas" id ="md_ver_comentarios<?php echo$_mensajes[$i]["id_mensaje"];?>"> <?php echo $lang_comentar_ver1." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?></button>
                    </div>
                    <div class ="md_ocultar_comentarios_mensaje" id="md_ocultar_comentarios_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                        <button class ="md_boton_respuestas" id ="md_ocultar_comentarios<?php echo$_mensajes[$i]["id_mensaje"];?>" > <?php echo $lang_comentar_ocultar." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?></button>
                    </div>
                    <?php
                    }
                    ?>
                    <div class ="md_resumen_respuestas_mensaje" id="md_resumen_respuestas_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                        <ul class="md_listado_mensajes_respuesta">
                            <?php
                            while($_mensajes_en_respuesta_resumen[$j]){
                                $_imagenes_usuario = darFormatoImagen($_mensajes_en_respuesta_resumen[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
                                ?>
                                <li class="md_listado_respuestas">
                                    <div  class="md_respuesta_mensaje">
                                        <div class="md_respuesta_msg_avatar">
                                            <img class="md_imagen_respuesta" src= "<?php echo $_imagenes_usuario["imagen_usuario"] ;?> "/>
                                        </div>
                                        <div  class="md_respuesta_msg_texto">
                                            <p>
                                                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>" class ="md_link_nombre" title ="<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>" ><?php echo $_mensajes_en_respuesta_resumen[$j]["nombre"];?></a> <?php echo $lang_mural_diseno_dice.': ';?>
                                                <?php echo enlazarURLs($_mensajes_en_respuesta_resumen[$j]["mensaje"]);?>
                                            </p>
                                            <div class="md_respuesta_msg_datos" id= "time">
                                                <?php echo relativeTime($_mensajes_en_respuesta_resumen[$j]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
                                            </div>
                                         </div>
                                    </div>
                                </li>
                                <?php
                                $j--;
                            }
                            ?>
                            </ul>
                        </div>
                    <div class ="md_respuestas_mensaje" id="md_respuestas_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                    </div>
                    <div id = "md_responder_msj_final<?php echo $_mensajes[$i]["id_mensaje"];?>">
                        <div class='md_msj_final' id="md_msj_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></div>
                        <div class='md_panel_final' id="md_panel_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>">
                            <form action="" method="post" name="<?php echo $_mensajes[$i]["id"]; ?>">
                                <textarea class="md_textbox_final"  id="md_textbox_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></textarea><br />
                                <input type="submit" value=" <?php echo $lang_mural_dis_ult_msj_responder; ?> "  class="md_boton_enviar_final" id="md_boton_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>" name="<?php echo $_mensajes[$i]["id_mensaje"]; ?>" />
                            </form>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
            </div>
                <?php
            $i++;
            if($resto == 0 && $class > 0){
            ?>
            <div class="md_ver_mas" >
                <button class="md_boton_ver_mas" id="md_ver_mas<?php echo $class;?>"><?php echo $lang_mural_diseno_ver_mas;?></button>
            </div>
            <?php
            }
        }
}
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    var id_ultimo_mensaje = "<?php echo $md_id_ultimo_mensaje;?>";

    function mdMeGustaMensaje(id_mensaje){
        url ="gusta_mensaje.php?origen=1&id_mensaje="+id_mensaje;
        $.get(url, function(data) {
          $('#md_gusta_mensaje'+id_mensaje).html(data);
        });
    }

    function mensajesNuevosMuralDiseno() {
        url = 'mural_nuevos_mensajes.php?id_diseno=<?php echo $id_diseno;?>&id_ultimo_mensaje='+id_ultimo_mensaje+'&origen=0';
        $.get(url, function(data) {
            if (data == "0"){
                $('#md_msj_nuevo_timeline').fadeOut();
            }else{
                $('#md_msj_nuevo_timeline').fadeIn(1500);
                $('#md_msj_nuevo_timeline').html(data);
            }
        });
    }
        $(document).ready(function(){
            $('.md_panel').hide();
            $('#md_msj_nuevo_timeline').hide();
            $('.msj').addClass('mu_mensaje_completo');
            $('.md_ocultar_comentarios_mensaje').hide();
            $(".md_boton_comentar").click(function(){
                var element = $(this);
                var I = element.attr("id");
                $("#md_panel"+I).slideToggle(300);
                $(this).toggleClass("active");
                return false;
            });

            $('.md_link_nombre').click(function() {
                    var $linkc = $(this);
                    var $dialog = $('<div></div>')
                    .load($linkc.attr('href'))
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_perfil_usuario_titulo_ventana;?>',
                        width: 800,
                        height: 600,
                        modal: true,
                        buttons: {
                            "<?php echo $lang_mural_dis_ult_msj_cerrar; ?>": function() {
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

            $(".md_boton_enviar").click(function(){
                var element = $(this);
                var Id = element.attr("name");
                var test = $("#md_textbox"+Id).val();
                var dataString = 'textcontent='+ test + '&en_respuesta_a=' + Id;
                if(test=='')
                {
                    alert("<?php echo $lang_mural_dis_ult_msj_ingresa_comentario; ?>");
                }
                else
                {
                    $.ajax({
                        type: "POST",
                        url: "mural_diseno_enviar_post.php?id_diseno=<?php echo $id_diseno;?>",
                        data: dataString,
                        cache: false,
                        success: function(html){
                            $("#md_respoder_msj"+Id).append(html);
                            leerUltimosMensajesMuralDiseno();
                            $.ajax({
                                type: "POST",
                                url: "notificaciones_correo.php?id_mensaje="+Id+"&id_diseno=<?php echo $id_diseno;?>",
                                async: true,
                                success: function(){

                                 }
                             });
                        }
                    });
                }
                return false;
            });

            $(".md_boton_enviar_final").click(function(){
                var element = $(this);
                var Id = element.attr("name");
                var test = $("#md_textbox_final"+Id).val();
                var dataString = 'textcontent='+ test + '&en_respuesta_a=' + Id;
                if(test=='')
                {
                    alert("<?php echo $lang_mural_dis_ult_msj_ingresa_comentario; ?>");
                }
                else
                {
                    $.ajax({
                        type: "POST",
                        url: "mural_diseno_enviar_post.php?id_diseno=<?php echo $id_diseno;?>",
                        data: dataString,
                        cache: false,
                        success: function(html){
                            $("#md_respoder_msj_final"+Id).append(html);
                            leerUltimosMensajesMuralDiseno();
                            $.ajax({
                                type: "POST",
                                url: "notificaciones_correo.php?id_mensaje="+Id+"&id_diseno=<?php echo $id_diseno;?>",
                                async: true,
                                success: function(){

                                 }
                             });
                        }
                    });

                }
                return false;
            });

            <?php
            $num_grupos=1;
            while($num_grupos < $grupos_msj_md){
                ?>
                    $("#md_ver_mas<?php echo $num_grupos+1; ?>").hide();
                    $('.msj<?php echo $num_grupos; ?>').hide();
                    $('#md_ver_mas<?php echo $num_grupos; ?>').click(function(){
                        $('.msj<?php echo $num_grupos; ?>').addClass('mu_mensaje_completo');
                        $('.msj<?php echo $num_grupos; ?>').show();
                        $('#md_ver_mas<?php echo $num_grupos; ?>').hide();
                        $('#md_ver_mas<?php echo $num_grupos+1; ?>').show();
                    });

                <?php
                $num_grupos++;
            }
            $num=0;
            while($_mensajes[$num]["id_mensaje"]){
            ?>

                $('#md_gusta<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                    id_mensaje_valoracion = <?php echo $_mensajes[$num]["id_mensaje"]; ?>;
                    url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=1&origen=2';
                    $.get(url_valoracion, function() {
                        mdMeGustaMensaje(<?php echo $_mensajes[$num]["id_mensaje"]?>);
                    });
                });

                $('#md_nogusta<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                    id_mensaje_valoracion = <?php echo $_mensajes[$num]["id_mensaje"]; ?>;
                    url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=0&origen=2';
                    $.get(url_valoracion, function() {
                        mdMeGustaMensaje(<?php echo $_mensajes[$num]["id_mensaje"]?>);
                    });
                });

                $('#md_ver_comentarios<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                    id_mensaje_original = <?php echo $_mensajes[$num]["id_mensaje"]; ?>;
                    url_respuestas = 'mural_diseno_respuestas_mensaje.php?id_mensaje='+id_mensaje_original;
                    $.get(url_respuestas, function(data) {
                        $('#md_resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').hide();
                        $("#md_ver_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"];?>").hide();
                        $('#md_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').html(data);
                        $('#md_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').show();
                        $('#md_ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').show();
                    });
                });

                $('#md_ocultar_comentarios<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                    $('#md_ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').hide();
                    $('#md_resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').show();
                    $('#md_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').hide();
                    $("#md_ver_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"];?>").show();

                });

                $('#md_usuarios_gusta<?php echo $_mensajes[$num]["id_mensaje"]; ?>').each(function() {
                        var $linkc = $(this);
                         $linkc.click(function() {
                            var $dialog = $('<div></div>')
                            .load($linkc.attr('href')+'id_mensaje=<?php echo $_mensajes[$num]["id_mensaje"]; ?>'+'&origen=2')
                            .dialog({
                                autoOpen: false,
                                title: '<?php echo $lang_usuarios_gusta_msj_titulo_ventana;?>',
                                width: 600,
                                height: 400,
                                modal: true,
                                buttons: {
                                    "<?php echo $lang_mural_dis_ult_msj_cerrar; ?>": function() {
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
               });

            <?php
            $num++;
            }
            ?>
        });
</script>
