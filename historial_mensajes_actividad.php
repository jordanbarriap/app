<?php
/**
 * Despliega la lista de mensajes publicados en la bitácora para una actividad que ya haya sido finalizada
 * Los mensajes son leidos desde la Base de Datos con la función dbTimeLine
 * Los parámetros solicitados son:
 * $_REQUEST["codexp"]: identificador de la experiencia en la Base Datos
 * $_REQUEST["codact"]: identificador de la actividad en la Base de Datos
 * $_REQUEST["codexpact"]: identificador de la experiencia actividad en la Base de Datos
 * $_REQUEST["et_Los parámetros solicitados son:exp"]: etiqueta que identifica en Twitter a la experiencia
 * $_REQUEST["et_clase_gemela"]: etiqueta que que comparten las clases gemelas para identificar
 * los mensajes en Twitter
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Daniel Guerra - Kelluwen
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

$id_experiencia         = $_REQUEST["codexp"];
$id_actividad           = $_REQUEST["codact"];
$id_exp_actividad       = $_REQUEST["codexpact"];
$etiqueta               = $_REQUEST["et_exp"];
$etiqueta_clase_gemela  = $_REQUEST["et_clase_gemela"];
$id_clase_gemela        = $_REQUEST["id_clase_gemela"];
$modo                   = $_REQUEST["modo"];
$usuario                = $_REQUEST["solo_usuario"];
$id_grupo               = $_REQUEST["id_grupo"];
$et_grupo_gemelo        = $_REQUEST["et_grupo_gemelo"];
$_mensajes  = null;
$class_gemela = "";
$es_profesor_responsable =0;

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$profesor = dbExpObtenerProfesor($id_experiencia, $conexion);

if($profesor["id"]== $_SESSION["klwn_id_usuario"]){
    $es_profesor_responsable =1;
}

if (!is_null($id_clase_gemela)){
    $id_experiencia = $id_clase_gemela;
}
if($modo == 1){
    $class_gemela ="_gemelo";
}


$_mensajes = dbTimeLine($conexion, $modo, $id_experiencia, $id_actividad, $id_exp_actividad, 
                        $etiqueta_clase_gemela,$id_grupo,$et_grupo_gemelo,null,$usuario);

if($_mensajes == null OR !is_array($_mensajes)){
    echo "<p class=\"no_hay_mensajes resaltado\">".$lang_no_hay_mensajes."</p>\n\r";
}
else{    
    $i = 0;
    $c = count($_mensajes);
    while($_mensajes[$i]) {
        $id_mensaje_actual = $_mensajes[$i]["id"];
        $_valoracion_mensaje = dbObtenerMeGustaMensaje($id_mensaje_actual, $conexion);
        $num_valoraciones = count($_valoracion_mensaje);
        $_mensajes_en_respuesta_resumen = dbObtenerMensajesEnRespuestaResumen($id_mensaje_actual, $conexion);
        $num_mensajes_en_respuesta = dbObtenerNumMensajesEnRespuesta($id_mensaje_actual, $conexion);
        $num_mensajes_en_respuesta_resumen = count($_mensajes_en_respuesta_resumen);
        $_historial_imagen =darFormatoImagen($_mensajes[$i]["url_imagen_perfil"], $config_ruta_img_perfil, $config_ruta_img);
        $imagen_n[$i] = $_historial_imagen["imagen_usuario"];
        $id_grupo = $_mensajes[$i]["id_grupo"];
        $es_gemela = false;
        $mensaje_gemelo = "historial";
        if ($_mensajes[$i]["id_experiencia"] != $id_experiencia){
            $mensaje_gemelo = "mensaje_gemelo";
            $es_gemela = true;
        }
        if (is_null($id_grupo)) $id_grupo = "-1";        
        $nombre_grupo = $_mensajes[$i]["nombre_grupo"];
        $es_producto = $_mensajes[$i]["producto"]=="1";
        $sin_borde = "";
        if ($i == $c-1) $sin_borde = "sin_borde";
        $fechastr = date("d-m-Y", strtotime($_mensajes[$i]["creado_el"]));
        ?>
        <div class="mensaje <?php echo $mensaje_gemelo." ".$sin_borde;?>" id="<?php echo $_mensajes[$i]["id"];?>">
          <div class="msg_avatar">
              <img src= "<?php echo $imagen_n[$i];?>" />
          </div>
          <div class="msg_texto">
            <p>
                <span class="resaltado"><?php echo $_mensajes[$i]["nombre_usuario"]." ";?></span><?php echo$lang_historial_dijo.": ";?>
                <?php echo " ".enlazarURLs($_mensajes[$i]["texto"]);?>
                
            </p>
              <div class="msg_datos_historial">
                 <p><?php echo $fechastr;?></p>
                 <?php
                if($num_valoraciones>0){
                ?>
                <div class ="historial_ver_megusta">
                    <a class ="historial_boton_ver_megusta" id ="historial_usuarios_gusta<?php echo $_mensajes[$i]["id"];?>" href= "usuarios_gusta_mensaje.php?">
                        <img src="<?php echo $config_ruta_img;?>me_gusta.jpg" title ="<?php echo $lang_num_megusta;?>" alt= <?php echo $lang_num_megusta;?>>
                        </img>
                        <p>
                        <?php
                            echo $num_valoraciones;
                        ?>
                        </p>
                    </a>
                </div>
                <br/>
                <?php
                }
                ?>
             </div>
         </div>
         <div class="eliminar" id="eliminar<?php echo $_mensajes[$i]["id"];?>">
          <?php
          if($es_profesor_responsable){
          ?>
            <button  class="boton_eliminar_msj"id="eliminar_mensaje<?php echo $_mensajes[$i]["id"];?>" onclick="javascript:eliminarMensajeHistorial(<?php echo $_mensajes[$i]["id"] ?>);">
                <img src="<?php echo $config_ruta_img;?>eliminar.png" title ="<?php echo $lang_hist_msj_act_profesor_responsable;?>" />
            </button>
          <?php  
          }
          ?>
        </div>
        <br/>
      </div>
         <div class="clear"></div>  
        <?php
        $j=$num_mensajes_en_respuesta_resumen-1;
            if($_mensajes_en_respuesta_resumen[$j]){
                if($num_mensajes_en_respuesta > 3){
                ?>
                <div class ="historial_ver_comentarios_mensaje<?php echo $class_gemela;?>" id="historial_ver_comentarios_mensaje<?php echo $_mensajes[$i]["id"];?>">
                        <button class ="boton_ver_comentarios<?php echo $class_gemela;?>" id ="historial_ver_comentarios<?php echo$_mensajes[$i]["id"];?>" href= "#"> <?php echo $lang_comentar_ver1." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?></button>
                </div>
                <div class ="historial_ocultar_comentarios_mensaje<?php echo $class_gemela;?>" id="historial_ocultar_comentarios_mensaje<?php echo $_mensajes[$i]["id"];?>">
                    <button class ="boton_ocultar_comentarios<?php echo $class_gemela;?>" id ="historial_ocultar_comentarios<?php echo$_mensajes[$i]["id"];?>" href= "#"> <?php echo $lang_comentar_ocultar." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?></button>
                </div>
                <?php
                }
                ?>
            <div class ="historial_resumen_respuestas_mensaje" id="historial_resumen_respuestas_mensaje<?php echo $_mensajes[$i]["id"];?>">
                <ul class="listado_mensajes_respuesta">
                    <?php
                    while($_mensajes_en_respuesta_resumen[$j]){
                        $_imagenes_usuario = darFormatoImagen($_mensajes_en_respuesta_resumen[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
                        $fecha_mensaje = date("d-m-Y", strtotime($_mensajes_en_respuesta_resumen[$j]["fecha"]));
                        ?>
                        <li class="historial_listado_respuestas">
                            <div  class="historial_respuesta_mensaje<?php echo $class_gemela;?>" id="<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"];?>">
                                <div class="respuesta_msg_avatar">
                                    <img class="imagen_respuesta" src= "<?php echo $_imagenes_usuario["imagen_usuario"] ;?> "/>
                                </div>
                                <div  class="respuesta_msg_texto">
                                    <p>
                                        <a <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>"<?php } ?> class ="link_nombre" title ="<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>" ><?php echo $_mensajes_en_respuesta_resumen[$j]["nombre"];?></a> <?php echo $lang_hist_msj_act_dice; ?>:
                                        <?php echo enlazarURLs($_mensajes_en_respuesta_resumen[$j]["mensaje"]);?>
                                    </p>
                                    <div id= "time" class="respuesta_msg_datos_historial">
                                        <?php echo $fecha_mensaje; ?>
                                    </div>
                                 </div>
                                <div class="eliminar_respuesta" id="eliminar_respuesta<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"];?>">
                                  <?php
                                  if($es_profesor_responsable){
                                  ?>
                                    <button  class="boton_eliminar_msj"  onclick="javascript:eliminarRespuestaHistorial(<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"] ?>);">
                                        <img src="<?php echo $config_ruta_img;?>eliminar.png" title ="<?php echo $lang_hist_msj_act_profesor_responsable;?>" />
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
                <br/>
            </div>
            <div class ="respuestas_mensaje" id="historial_respuestas_mensaje<?php echo $_mensajes[$i]["id"];?>">
            </div>
               <?php
            }
            ?>

        <?php
        $i++;
    }
}
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
    function recargarHistorial(){
        url= 'historial_mensajes_actividad.php?codexp=<?php echo $id_experiencia;?>'+'&codact=<?php echo $id_actividad;?>'+'&codexpact=<?php echo $id_exp_actividad;?>'+'&et_exp=<?php echo $etiqueta;?>'+
            '&et_clase_gemela=<?php echo $etiqueta_gemela?>';
        $.ajax({type:"get",url:url,success: function(data){
                $('.historial').parent().html(data);
        }});
    }
    function eliminarMensajeHistorial(id_mensaje){
        var contenido = '<p class=\"resaltado\"><?php echo $lang_historial_eliminar_mensaje1;?></p><p><?php echo $lang_historial_eliminar_mensaje2;?>.</p>'; 
        var $dialog = $('<div id=\"dialogo_deshaciendo_actividad\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_hist_msj_act_eliminar_comentario_bit;?>',
            width: 400,
            height: 250,
            modal: true,
            buttons: {
                '<?php echo $lang_hist_msj_act_eliminar;?>': function() {
                    url_eliminar = 'bitacora_eliminar_post.php?id_mensaje='+id_mensaje+'&tipo=1';//Eliminar un mensaje y sus respues asociadas
                    $.get(url_eliminar, function(data) {
                        if(data==1){
                                recargarHistorial();
                        }
                    });
                    $(this).dialog('close');
                },
                '<?php echo $lang_boton_cancelar;?>': function() {
                    $(this).dialog('close');
                }
            },
            close: function() {
            }
        });
        $dialog.dialog('open');
        return false;
        
    }
    function eliminarRespuestaHistorial(id_mensaje){
        var contenido = '<p class=\"resaltado\"><?php echo $lang_historial_eliminar_mensaje1;?></p><p><?php echo $lang_historial_eliminar_mensaje3;?>.</p>'; 
        var $dialog = $('<div id=\"dialogo_deshaciendo_actividad\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_hist_msj_act_eliminar_comentario_bit;?>',
            width: 400,
            height: 250,
            modal: true,
            buttons: {
                '<?php echo $lang_hist_msj_act_eliminar;?>': function() {
                    url_eliminar = 'bitacora_eliminar_post.php?id_mensaje='+id_mensaje+'&tipo=2';//Eliminar una respuesta
                    $.get(url_eliminar, function(data) {
                        if(data==1){
                            recargarHistorial();
                        }
                    });
                    $(this).dialog('close');
                },
                '<?php echo $lang_boton_cancelar;?>': function() {
                    $(this).dialog('close');
                }
            },
            close: function() {
            }
        });
        $dialog.dialog('open');
        return false;
    }
    $(document).ready(function(){
      $('.historial_ocultar_comentarios_mensaje<?php echo $class_gemela;?>').hide();
      $(".panel").hide();
      $('.eliminar').hide();
      $('.eliminar_respuesta').hide();
      
      $('.mensaje').hover(function() {
          var element = $(this);
          var I = element.attr("id");
          $("#eliminar"+I).show();
          }, function() {
              var element = $(this);
              var I = element.attr("id");
            $("#eliminar"+I).hide();
      });
      $('.historial_respuesta_mensaje').hover(function() {
          var element2 = $(this);
          var Id = element2.attr("id");
        $("#eliminar_respuesta"+Id).show();
      }, function() {
          var element = $(this);
          var Id = element.attr("id");
        $("#eliminar_respuesta"+Id).hide();
      });

      <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>
      $('.link_nombre').each(function() {
            var $linkc = $(this);
             $linkc.click(function() {
                var $dialog = $('<div></div>')
                .load($linkc.attr('href'))
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_perfil_usuario_titulo_ventana;?>',
                    width: 800,
                    height: 600,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_hist_msj_act_cerrar; ?>": function() {
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
       <?php } ?>
       
        <?php
        $num = 0;
        while($_mensajes[$num]["id"]){
        ?>
                $('#historial_ver_comentarios<?php echo $_mensajes[$num]["id"]; ?>').click(function(){
                    id_mensaje_original = <?php echo $_mensajes[$num]["id"]; ?>;
                    url_respuestas = 'bitacora_respuestas_mensaje.php?id_mensaje='+id_mensaje_original+'&historial=1&clase_gemela=<?php echo $class_gemela;?>'+'&id_profesor=<?php echo $profesor["id"];?>';
                    $.get(url_respuestas, function(data) {
                        $('#historial_resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').hide();
                        $("#historial_ver_comentarios_mensaje<?php echo $_mensajes[$num]["id"];?>").hide();
                        $('#historial_respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').html(data);
                        $('#historial_respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').show();
                        $('#historial_ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id"]; ?>').show();
                    });
                });
                $('#historial_ocultar_comentarios<?php echo $_mensajes[$num]["id"]; ?>').click(function(){
                    $('#historial_ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id"]; ?>').hide();
                    $('#historial_resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').show();
                    $('#historial_respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').hide();
                    $("#historial_ver_comentarios_mensaje<?php echo $_mensajes[$num]["id"];?>").show();
                });
                $('#historial_usuarios_gusta<?php echo $_mensajes[$num]["id"]; ?>').each(function() {
                    var $linkc = $(this);
                     $linkc.click(function() {
                        var $dialog = $('<div></div>')
                        .load($linkc.attr('href')+'id_mensaje=<?php echo $_mensajes[$num]["id"]; ?>')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_usuarios_gusta_msj_titulo_ventana;?>',
                            width: 600,
                            height: 400,
                            modal: true,
                            buttons: {
                                "<?php echo $lang_hist_msj_act_cerrar; ?>": function() {
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
