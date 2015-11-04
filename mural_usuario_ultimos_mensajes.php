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
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_propietario_muro    = $_REQUEST["id_usuario"];
$id_usuario_sesion = $_SESSION["klwn_id_usuario"];
$usuario_sesion = $_SESSION["klwn_usuario"];
$conexion               = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_mensajes = dbMuralUsuarioMensajes($conexion, $id_propietario_muro);
$k=0;
while ($_mensajes[$k]){
    $k++;
}
$num_mensajes_mural= $k;
$grupos_msj = $num_mensajes_mural/10;
$i=0;
if ($_mensajes==null){
    echo "<br />";
    echo $lang_no_hay_mensajes;
    $id_ultimo_mensaje = -1;
}
else {
        $mu_id_ultimo_mensaje = $_mensajes[0]["id_mensaje"];
        while($_mensajes[$i]) {
            $id_mensaje_actual = $_mensajes[$i]["id_mensaje"];
            $_valoracion_mensaje = dbMuralUsuarioObtenerMeGustaMensaje($id_mensaje_actual, $conexion);
            $num_valoraciones = count($_valoracion_mensaje);
            $_mensajes_en_respuesta_resumen = dbMuralUsuarioObtenerMensajesEnRespuestaResumen($id_mensaje_actual, $conexion);
            $num_mensajes_en_respuesta = dbMuralUsuarioObtenerNumMensajesEnRespuesta($id_mensaje_actual, $conexion);
            $usuario_gusta_mensaje = dbMuralUsuarioGustaMensaje($id_usuario_sesion, $id_mensaje_actual, $conexion);
            $num_mensajes_en_respuesta_resumen = count($_mensajes_en_respuesta_resumen);
            $class = intval($i/10);
            $resto = $i%10;
            if($class<1){
                $class = "";
            }
            if($_mensajes[$i]["id_usuario_muro"]== $id_propietario_muro){
                // El propietario del muro publica en su propio muro u otro usuario publicó en su muro
                $_up_imagenes_usuario = darFormatoImagen($_mensajes[$i]["url_imagen_usuario_publica"], $config_ruta_img_perfil, $config_ruta_img);
                $up_imagen = $_up_imagenes_usuario["imagen_usuario"];                
                ?>
                <div  class = "msj<?php echo $class; ?>"  >
                    <div class="mu_mensaje ">
                        <div class="mu_msg_avatar">
                            <img src= "<?php echo $up_imagen;?> " >
                        </div>
                        <div class="mu_msg_texto">
                            <p>
                                <?php
                                if($id_usuario_sesion == $_mensajes[$i]["id_usuario_publica"]){
                                ?>
                               <b><?php echo $_mensajes[$i]["nombre_usuario_publica"];?></b> <?php echo " ".$lang_mural_usuario_ult_msj_dice; ?>: <?php echo enlazarURLs($_mensajes[$i]["mensaje"]);?>
                               <?php
                                }
                                else{
                                ?>
                                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario_publica"];?>" class ="link_nombre_perfil" title ="<?php echo $_mensajes[$i]["usuario_publica"];?>" ><?php echo $_mensajes[$i]["nombre_usuario_publica"];?></a> <?php echo " ".$lang_mural_usuario_ult_msj_dice; ?>: <?php echo enlazarURLs($_mensajes[$i]["mensaje"]);?>
                               <?php
                                }
                                ?>
                            </p>
                            <div id= "time" class="mu_msg_datos">
                                <?php echo relativeTime($_mensajes[$i]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales);?>
                                
                            </div>
                            <div id="mu_gusta_mensaje<?php echo $id_mensaje_actual; ?>">
                                <div class ="mu_megusta_mensaje">
                                <?php
                                if($usuario_gusta_mensaje<1){
                                    echo "<button  class = \"mu_megusta_msj_boton\" id = \"mu_gusta".$id_mensaje_actual."\" href= \"#\"> ".$lang_mural_usuario_ult_msj_mg." </button> ";
                                }
                                else{
                                    echo "<button class = \"mu_megusta_msj_boton\" id = \"mu_nogusta".$id_mensaje_actual."\" href= \"#\"> ".$lang_mural_usuario_ult_msj_ya_no_mg." </button> ";
                                }
                                ?>
                                </div>
                                <?php
                                if($num_valoraciones>0){
                                ?>
                                <div class ="mu_ver_megusta">
                                    <a class ="boton_ver_megusta" id ="mu_usuarios_gusta<?php echo $_mensajes[$i]["id_mensaje"];?>" href= "usuarios_gusta_mensaje.php?">
                                      <img src="<?php echo $config_ruta_img;?>me_gusta_dm.png" title ="<?php echo $lang_num_megusta;?>" alt= "<?php echo $lang_num_megusta;?>"/>
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
                            <div class ="mu_comentar_mensaje">
                                <a class ="mu_boton_comentar" id ="<?php echo$_mensajes[$i]["id_mensaje"];?>" href="#"><?php echo $lang_mural_usuario_ult_msj_responder; ?></a>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    if($num_mensajes_en_respuesta<1){
                    ?>
                    <div id = "mu_responder_msj<?php echo $_mensajes[$i]["id_mensaje"];?>">
                        <div class='mu_msj' id="mu_msj<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></div>
                        <div class='mu_panel' id="mu_panel<?php echo $_mensajes[$i]["id_mensaje"]; ?>">
                            <form action="" method="post" name="<?php echo $_mensajes[$i]["id"]; ?>">
                                <textarea class="mu_textbox"  id="mu_textbox<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></textarea><br />
                                <input type="submit" value=" <?php echo $lang_mural_usuario_ult_msj_responder; ?> "  class="mu_boton_enviar" id="mu_boton<?php echo $_mensajes[$i]["id_mensaje"]; ?>" name="<?php echo $_mensajes[$i]["id_mensaje"]; ?>" />
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
                        <div class ="mu_ver_comentarios_mensaje" id="mu_ver_comentarios_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                                <img src="<?php echo $config_ruta_img; ?>comentar.gif" title ="<?php echo $lang_comentar_ver_todos;?>" alt="<?php echo $lang_comentar_ver_todos; ?>"/>
                                <button class ="mu_boton_ver_comentarios" id ="mu_ver_comentarios<?php echo$_mensajes[$i]["id_mensaje"];?>" href= "#"> <?php echo $lang_comentar_ver1." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?></button>
                        </div>
                        <div class ="mu_ocultar_comentarios_mensaje" id="mu_ocultar_comentarios_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                            <img src="<?php echo $config_ruta_img; ?>comentar.gif" title ="<?php echo $lang_comentar_ver_todos;?>" alt="<?php echo $lang_comentar_ver_todos; ?>"/>
                            <button class ="mu_boton_ocultar_comentarios" id ="mu_ocultar_comentarios<?php echo$_mensajes[$i]["id_mensaje"];?>" href= "#"> <?php echo $lang_comentar_ocultar." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?></button>
                        </div>
                        <?php
                        }
                        ?>
                        <div class ="mu_resumen_respuestas_mensaje" id="mu_resumen_respuestas_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                            <ul class="mu_listado_mensajes_respuesta">
                                <?php
                                while($_mensajes_en_respuesta_resumen[$j]){
                                    $_imagenes_usuario = darFormatoImagen($_mensajes_en_respuesta_resumen[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
                                    ?>
                                    <li class="mu_listado_respuestas">
                                        <div  class="mu_respuesta_mensaje">
                                            <div class="mu_respuesta_msg_avatar">
                                                <img class="mu_imagen_respuesta" src= "<?php echo $_imagenes_usuario["imagen_usuario"] ;?> "/>
                                            </div>
                                            <div  class="mu_respuesta_msg_texto">
                                                <p>
                                                    <?php
                                                    if($usuario_sesion == $_mensajes_en_respuesta_resumen[$j]["usuario"]){
                                                    ?>
                                                        <b><?php echo $_mensajes_en_respuesta_resumen[$j]["nombre"];?></b> <?php echo " ".$lang_mural_usuario_ult_msj_dice; ?>:
                                                    <?php
                                                    }
                                                    else{
                                                    ?>
                                                        <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>" class ="link_nombre_perfil" title ="<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>" ><?php echo $_mensajes_en_respuesta_resumen[$j]["nombre"];?></a> <?php echo $lang_mural_usuario_ult_msj_dice; ?>:
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php echo enlazarURLs($_mensajes_en_respuesta_resumen[$j]["mensaje"]);?>
                                                </p>
                                                <div class="mu_respuesta_msg_datos" id= "time">
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
                    <div class ="mu_respuestas_mensaje" id="mu_respuestas_mensaje<?php echo $_mensajes[$i]["id_mensaje"];?>">
                    </div>
                    <div id = "mu_responder_msj_final<?php echo $_mensajes[$i]["id_mensaje"];?>">
                        <div class='mu_msj_final' id="mu_msj_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></div>
                        <div class='mu_panel_final' id="mu_panel_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>">
                            <form action="" method="post" name="<?php echo $_mensajes[$i]["id"]; ?>">
                                <textarea class="mu_textbox_final"  id="mu_textbox_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>" ></textarea><br />
                                <input type="submit" value=" <?php echo $lang_mural_usuario_ult_msj_responder; ?> "  class="mu_boton_enviar_final" id="mu_boton_final<?php echo $_mensajes[$i]["id_mensaje"]; ?>" name="<?php echo $_mensajes[$i]["id_mensaje"]; ?>" />
                            </form>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>                
                    <?php
            }
            else
            {
                if ($_mensajes[$i]["id_usuario_publica"]== $id_propietario_muro) {
                //El propietario del muro publicó en el muro de otro usuario
                    $_up_imagenes_usuario = darFormatoImagen($_mensajes[$i]["url_imagen_usuario_publica"], $config_ruta_img_perfil, $config_ruta_img);
                    $up_imagen_propietario = $_up_imagenes_usuario["imagen_usuario"];
                    $_up_imagenes_usuario_publica = darFormatoImagen($_mensajes[$i]["url_imagen_usuario_dueno"], $config_ruta_img_perfil, $config_ruta_img);
                    $up_imagen_publica = $_up_imagenes_usuario_publica["imagen_usuario"];
                    ?>
                    <div class ="msj<?php echo $class; ?>" class = "mu_mensaje_completo">
                        <div class="mu_mensaje ">
                            <div class="mu_msg_avatar">
                                <img src= "<?php echo $config_ruta_img;?>comentario_entre_muro.png" />
                            </div>
                            <div class="mu_msg_texto">
                                <p>
                                    <b> <?php echo $_mensajes[$i]["nombre_usuario_publica"];?></b> <?php echo $lang_mural_usuario_ult_msj_escribio; ?> <a class="mu_ver_mensaje_modal" id="mu_ver_msj<?php echo $_mensajes[$i]["id_mensaje"];?>" href="mural_usuario_ver_msj_entre_muros.php?id_mensaje=<?php echo $_mensajes[$i]["id_mensaje"];?>"><?php echo $lang_mural_dis_ult_msj_mensaje; ?></a> <?php echo $lang_mural_usuario_ult_msj_diario_mural; ?>
                                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario_dueno"];?>" class ="link_nombre_perfil" title ="<?php echo $_mensajes[$i]["usuario_dueno"];?>" ><?php echo $_mensajes[$i]["nombre_usuario_dueno"];?></a>
                                </p>
                            </div>
                            <div id= "time" class="mu_msg_datos">
                                <?php echo relativeTime($_mensajes[$i]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales);?>
                            </div>
                        </div>
                    </div>                    
                    <?php
                }
            }
           $i++;
            if($resto == 0 && $class > 0){
            ?>
            <div class="mu_ver_mas" >
                <button class="mu_boton_ver_mas" id="ver_mas<?php echo $class;?>" href="#"><?php echo $lang_mural_usuario_ver_mas ;?></button>
            </div>
            <?php
            }
    }    
}
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    var id_ultimo_mensaje = "<?php echo $mu_id_ultimo_mensaje;?>";
    function muMeGustaMensaje(id_mensaje){
        url ="gusta_mensaje.php?origen=2&id_mensaje="+id_mensaje;
        $.get(url, function(data) {
          $('#mu_gusta_mensaje'+id_mensaje).html(data);
        });
    }
    function mensajesNuevosMuralUsuario() {
        url = 'mural_nuevos_mensajes.php?id_usuario=<?php echo $id_propietario_muro;?>&id_ultimo_mensaje='+id_ultimo_mensaje+'&origen=1';
        $.get(url, function(data) {
            if (data == "0"){
                $('#mu_msj_nuevo_timeline').fadeOut();
            }else{
                $('#mu_msj_nuevo_timeline').fadeIn(1500);
                $('#mu_msj_nuevo_timeline').html(data);
            }
        });
    }
    $(document).ready(function(){  
        $('.mu_panel').hide();
        $('.msj').addClass('mu_mensaje_completo');
        $('.mu_ocultar_comentarios_mensaje').hide();
        $('#mu_msj_nuevo_timeline').hide();
        $(".mu_boton_comentar").click(function(){
            var element = $(this);
            var I = element.attr("id");
            $("#mu_panel"+I).slideToggle(300);
            $(this).toggleClass("active");
            return false;
        });
        $(".mu_boton_enviar").click(function(){
            var element = $(this);
            var Id = element.attr("name");
            var test = $("#mu_textbox"+Id).val();
            var dataString = 'textcontent='+ test + '&en_respuesta_a=' + Id;
            if(test=='')
            {
                alert("<?php echo $lang_mural_usuario_ult_msj_ingresa_comentario; ?>");
            }
            else
            {
                $("#mu_msj"+Id).show();
                $("#mu_msj"+Id).fadeIn(400).html('<img src="<?php echo $config_ruta_img;?>cargando_respuesta_mensaje.gif" align="absmiddle" > <?php echo $lang_mural_usuario_ult_msj_cargando; ?>.....');
                $.ajax({
                    type: "POST",
                    url: "mural_usuario_enviar_post.php",
                    data: dataString,
                    cache: false,
                    success: function(html){
                        $("#mu_respoder_msj"+Id).append(html);
                        leerUltimosPostsMural();
                    }
                });
                
            }
            return false;
        });
        $(".mu_boton_enviar_final").click(function(){
            var element = $(this);
            var Id = element.attr("name");
            var test = $("#mu_textbox_final"+Id).val();
            var dataString = 'textcontent='+ test + '&en_respuesta_a=' + Id;
            if(test=='')
            {
                alert("<?php echo $lang_mural_usuario_ult_msj_ingresa_comentario; ?>");
            }
            else
            {
                $("#mu_msj_final"+Id).show();
                $("#mu_msj_final"+Id).fadeIn(400).html('<img src="<?php echo $config_ruta_img;?>cargando_respuesta_mensaje.gif" align="absmiddle" > <?php echo $lang_mural_usuario_ult_msj_cargando; ?>.....');
                $.ajax({
                    type: "POST",
                    url: "mural_usuario_enviar_post.php",
                    data: dataString,
                    cache: false,
                    success: function(html){
                        $("#mu_respoder_msj_final"+Id).append(html);
                        leerUltimosPostsMural();
                    }
                });
                
            }
            return false;
        });
      $('.link_nombre_perfil').click(function() {
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
                    "<?php echo $lang_mural_usuario_ult_msj_cerrar; ?>": function() {
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
       $('.mu_ver_mensaje_modal').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_mural_diseno_titulo_venta_mensaje;?>',
                width: 400,
                height: 200,
                modal: true,
                buttons: {
                    "<?php echo $lang_mural_usuario_ult_msj_cerrar; ?>": function() {
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
        <?
        $num_grupos=1;
        while($num_grupos < $grupos_msj){
            ?>
                    $("#ver_mas<?php echo $num_grupos+1; ?>").hide();
                    $('.msj<?php echo $num_grupos; ?>').hide();
                    $('#ver_mas<?php echo $num_grupos; ?>').click(function(){
                    $('.msj<?php echo $num_grupos; ?>').addClass('mu_mensaje_completo');
                    $('.msj<?php echo $num_grupos; ?>').show();
                    $('#ver_mas<?php echo $num_grupos; ?>').hide();
                    $('#ver_mas<?php echo $num_grupos+1; ?>').show();
                    });

            <?php            
            $num_grupos++;
        }
        $num=0;
        while($_mensajes[$num]["id_mensaje"]){         
        ?>
//            muMeGustaMensaje(<?php echo $_mensajes[$num]["id_mensaje"];?>);

            $('#mu_gusta<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                id_mensaje_valoracion = <?php echo $_mensajes[$num]["id_mensaje"]; ?>;
                url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=1&origen=1';
                $.get(url_valoracion, function() {
                     muMeGustaMensaje(<?php echo $_mensajes[$num]["id_mensaje"];?>);
                });

            });
            $('#mu_nogusta<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                id_mensaje_valoracion = <?php echo $_mensajes[$num]["id_mensaje"]; ?>;
                url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=0&origen=1';
                $.get(url_valoracion, function() {
                     muMeGustaMensaje(<?php echo $_mensajes[$num]["id_mensaje"];?>);
                });

            });
            $('#mu_ver_comentarios<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                id_mensaje_original = <?php echo $_mensajes[$num]["id_mensaje"]; ?>;
                url_respuestas = 'mural_usuario_respuestas_mensaje.php?id_mensaje='+id_mensaje_original;
                $.get(url_respuestas, function(data) {
                    $('#mu_resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').hide();
                    $("#mu_ver_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"];?>").hide();
                    $('#mu_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').html(data);
                    $('#mu_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').show();
                    $('#mu_ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').show();
                });
            });
            $('#mu_ocultar_comentarios<?php echo $_mensajes[$num]["id_mensaje"]; ?>').click(function(){
                $('#mu_ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').hide();
                $('#mu_resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').show();
                $('#mu_respuestas_mensaje<?php echo $_mensajes[$num]["id_mensaje"]; ?>').hide();
                $("#mu_ver_comentarios_mensaje<?php echo $_mensajes[$num]["id_mensaje"];?>").show();

            });
             $('#mu_usuarios_gusta<?php echo $_mensajes[$num]["id_mensaje"]; ?>').each(function() {
                    var $linkc = $(this);
                     $linkc.click(function() {
                        var $dialog = $('<div></div>')
                        .load($linkc.attr('href')+'id_mensaje=<?php echo $_mensajes[$num]["id_mensaje"]; ?>'+'&origen=1')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_usuarios_gusta_msj_titulo_ventana;?>',
                            width: 600,
                            height: 400,
                            modal: true,
                            buttons: {
                                "<?php echo $lang_mural_usuario_ult_msj_cerrar; ?>": function() {
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