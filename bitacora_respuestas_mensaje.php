<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 //Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$clase_gemela = $_REQUEST["clase_gemela"];
$id_mensaje_original = $_REQUEST["id_mensaje"];
$id_profesor = $_REQUEST["id_profesor"];
$historial = $_REQUEST["historial"];
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_mensajes_en_respuesta = dbObtenerMensajesEnRespuesta($id_mensaje_original, $conexion);
$es_profesor_responsable =0;
if($id_profesor == $_SESSION["klwn_id_usuario"]){
    $es_profesor_responsable =1;
}
dbDesconectarMySQL($conexion);
if ($historial == 1) {
?>
    <ul class="historial_listado_mensajes_respuesta" >
    <?php
    $j = 0;
    while ($_mensajes_en_respuesta[$j]) {
        $_imagenes_usuario = darFormatoImagen($_mensajes_en_respuesta[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
        $fecha_mensaje = date("d-m-Y", strtotime($_mensajes_en_respuesta[$j]["fecha"]));
    ?>
        <li class="historial_listado_respuestas">
            <div  class="historial_respuesta_mensaje<?php echo " ".$clase_gemela; ?>" id="<?php echo $_mensajes_en_respuesta[$j]["id_mensaje_respuesta"]; ?>">
                <div class="respuesta_msg_avatar">
                    <img class="imagen_respuesta" src= "<?php echo $_imagenes_usuario["imagen_usuario"]; ?> "/>
                </div>
                <div  class="respuesta_msg_texto">
                    <p>
                        <a class = "ventana_perfil" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $_mensajes_en_respuesta[$j]["usuario"]; ?>"<?php } ?> class ="link_nombre" title ="<?php echo $_mensajes_en_respuesta[$j]["usuario"]; ?>" ><?php echo $_mensajes_en_respuesta[$j]["nombre"]; ?></a>
                    <?php echo $lang_bitacora_dice.": ".enlazarURLs($_mensajes_en_respuesta[$j]["mensaje"]); ?>
                    </p>
                    <div id= "time" class="respuesta_msg_datos_historial">
                        <?php echo $fecha_mensaje; ?>
                    </div>
                </div>
                <div class="eliminar_respuesta_br" id="eliminar_hbr<?php echo $_mensajes_en_respuesta[$j]["id_mensaje_respuesta"];?>">
                 <?php
                  if($es_profesor_responsable){
                  ?>
                    <button  class="boton_eliminar_msj" onclick="javascript:eliminarRespuestaHistorial(<?php echo $_mensajes_en_respuesta[$j]["id_mensaje_respuesta"];?>);">
                        <img src="<?php echo $config_ruta_img;?>eliminar.png" title ="<?php echo $lang_bitacora_eliminar_mensaje;?>" />
                    </button>
                  <?php  
                  }
                  ?>
              </div>
            </div>
        </li>
    <?php
                    $j++;
                }
    ?>
            </ul>
<?php
            } else {
?>
                <ul class="listado_mensajes_respuesta" >
<?php
                $j = 0;
                while ($_mensajes_en_respuesta[$j]) {
                    $_imagenes_usuario = darFormatoImagen($_mensajes_en_respuesta[$j]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
?>
                    <li class="listado_respuestas">
                        <div  class="respuesta_mensaje <?php echo $clase_gemela; ?> respuesta_msj" id="<?php echo $_mensajes_en_respuesta[$j]["id_mensaje_respuesta"];?>">
                            <div class="respuesta_msg_avatar">
                                <img class="imagen_respuesta" src= "<?php echo $_imagenes_usuario["imagen_usuario"]; ?> "/>
                            </div>
                            <div  class="respuesta_msg_texto">
                                <p>
                                    <a class = "ventana_perfil" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $_mensajes_en_respuesta[$j]["usuario"]; ?>"<?php }?> class ="link_nombre" title ="<?php echo $_mensajes_en_respuesta[$j]["usuario"]; ?>" ><?php echo $_mensajes_en_respuesta[$j]["nombre"]; ?></a>
                                    <?php echo $lang_bitacora_dice.": ".enlazarURLs($_mensajes_en_respuesta[$j]["mensaje"]); ?>
                                </p>
                                <div id= "time" class="respuesta_msg_datos" id="eliminar_respuesta<?php echo $_mensajes_en_respuesta[$j]["id_mensaje_respuesta"];?>">
                                <?php echo relativeTime($_mensajes_en_respuesta[$j]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
                            </div>
                            </div>
                            
                            <br/>
                            <div class="eliminar_respuesta_br" id="eliminar_br<?php echo $_mensajes_en_respuesta[$j]["id_mensaje_respuesta"];?>">
                             <?php
                              if($es_profesor_responsable){
                              ?>
                                <button  class="boton_eliminar_msj" onclick="javascript:eliminarRespuesta(<?php echo $_mensajes_en_respuesta[$j]["id_mensaje_respuesta"]; ?>);">
                                    <img src="<?php echo $config_ruta_img;?>eliminar.png" title ="<?php echo $lang_bitacora_eliminar_mensaje;?>" />
                                </button>
                              <?php  
                              }
                              ?>
                          </div>
                        </div>
                    </li>
<?php
                    $j++;
                }
?>
            </ul>
    <?php
            }
    ?>
<script type="text/javascript">

                $(document).ready(function(){
                    $('.eliminar_respuesta_br').hide();
                    $('.respuesta_msj').hover(function() {
                          var element2 = $(this);
                          var Id = element2.attr("id");
                        $("#eliminar_br"+Id).show();
                      }, function() {
                          var element = $(this);
                          var Id = element.attr("id");
                        $("#eliminar_br"+Id).hide();
                      });
                      $('.historial_respuesta_mensaje').hover(function() {
                          var element2 = $(this);
                          var Id2 = element2.attr("id");
                        $("#eliminar_hbr"+Id2).show();
                      }, function() {
                          var element = $(this);
                          var Id2 = element.attr("id");
                        $("#eliminar_hbr"+Id2).hide();
                      });
                    $('.ventana_perfil').each(function() {
                        var $linkc = $(this);
                        $linkc.click(function() {
                            var $dialog = $('<div></div>')
                            .load($linkc.attr('href'))
                            .dialog({
                                autoOpen: false,
                                title: '<?php echo $lang_perfil_usuario_titulo_ventana; ?>',
                    width: 800,
                    height: 600,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_bit_respuestas_msj_cerrar; ?>": function() {
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
    });

</script>

