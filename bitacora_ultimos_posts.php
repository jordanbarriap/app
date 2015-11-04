<?php
/**
 * Despliega los la lista de mensajes de la bitácora según los filtros pasados
 * como parámetros.
 * Los parametros recibidos son:
 * $_REQUEST["codeexp"]: identificador de la experiencia
 * $_REQUEST["id_clase_gemela"]: identificador de la clase gemela
 * $_REQUEST["et_exp"]: etiqueta de la experiencia
 * $_REQUEST["et_clase_gemela"]: etiqueta de la clase gemela
 * $_REQUEST["modo"]: identificar del filtro aplicado
 * $_REQUEST["et_grupo"]: etiqueta del grupo
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

$id_experiencia         = $_REQUEST["codeexp"];
$id_clase_gemela        = $_REQUEST["id_clase_gemela"];
$etiqueta               = $_REQUEST["et_exp"];
$etiqueta_clase_gemela  = $_REQUEST["et_clase_gemela"];
$modo                   = $_REQUEST["modo"];
$etiqueta_grupo         = $_REQUEST["et_grupo"];
$id_grupo               = $_REQUEST["id_grupo"];
$solo_usuario           = $_REQUEST["solo_usuario"];
$producto               = $_REQUEST["producto"];
$esta_finalizada        = $_REQUEST["finalizada"];
$etiqueta_grupo_gemelo  = null;
$conexion               = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$avance_experiencia     = dbExpObtenerAvance($id_experiencia, $conexion);
$id_exp_actividad       = $avance_experiencia["ultima_actividad_id_expact"];
$id_actividad           = $avance_experiencia["ultima_actividad_id"];
$es_profesor_responsable =0;
$profesor = dbExpObtenerProfesor($id_experiencia, $conexion);
$bitacora_publicando_grupo = "";

if($profesor["id"]== $_SESSION["klwn_id_usuario"]){
    $es_profesor_responsable =1;
}
if(!is_null($id_grupo)){
    if(!is_null($id_clase_gemela)){
        $usuarios_grupo         = dbExpObtenerEstudiantesGrupo($id_clase_gemela,$id_grupo, $conexion);
    }
    else{
        $usuarios_grupo         = dbExpObtenerEstudiantesGrupo($id_experiencia,$id_grupo, $conexion);
    }
}
if(!is_null($_REQUEST["grupos"])){
    $bitacora_publicando_grupo = $_REQUEST["grupo"];
    $limite_inferior = (($bitacora_publicando_grupo - 1)*20);     
    //solicitar los mensajes
    if ($modo == 0){
        $_mensajes = dbTimeLineBloque($conexion,$limite_inferior, $modo, $id_experiencia, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela,
                                    $id_grupo = null, $etiqueta_grupo_gemelo,$producto = null, $usuario = null);
    }
    //Mensajes de la clase gemela
    if ($modo == 1){
        $_mensajes = dbTimeLineBloque($conexion,$limite_inferior, $modo, $id_clase_gemela, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela,
                                    $id_grupo = null, $etiqueta_grupo_gemelo, $producto = null, $usuario = null);
    }
    // Mensajes de un grupo
    if ($modo == 3){
        $etiqueta_grupo_gemelo = $_REQUEST["et_grupo_gemelo"];
        $_mensajes = dbTimeLineBloque($conexion,$limite_inferior, $modo, $id_experiencia, $id_actividad, $id_exp_actividad,$etiqueta_clase_gemela,
                                $id_grupo, $etiqueta_grupo_gemelo,$producto = null, $usuario = null);
        // despliegue de las imagenes de los integrantes del grupo
        if(!is_null($usuarios_grupo)or strlen($usuarios_grupo)>1){
            ?>
            <div class= "integrantes">
                <p class= "integrantes_txt"><?php echo $lang_integrantes_grupo.": ";?></p>
                <div class="bloque_integrantes">
                <?php
                foreach($usuarios_grupo as $datos_usuario){
                    $_imagen_usuario = darFormatoImagen($datos_usuario["url_avatar"],$config_ruta_img_perfil, $config_ruta_img);
                    $imagen = $_imagen_usuario["imagen_usuario"];
                ?>
                    <div class= "datos_integrate">
                        <a class="bloque_img_integrantes" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $datos_usuario["usuario"];?>"<?php } ?> title= "<?php echo $datos_usuario["usuario"];?>">
                            <img class= "img_integrantes" src="<?php echo $imagen;?>" />
                            <div class="nombre_integrantes"><?php echo $datos_usuario["nombre"];?>
                            </div>
                        </a>
                    </div>
                <?php
                }
                ?>
                    <div class="clear"></div>
                </div>
            </div>
    <?php
        }
        else{
            echo $lang_bitacora_grupos;
        }
    }
    //Mensajes de un usuario
    if (!is_null($_REQUEST["solo_usuario"]) AND strlen($_REQUEST["solo_usuario"]) > 0){
        $solo_usuario = $_REQUEST["solo_usuario"];
        $_mensajes = dbTimeLineBloque($conexion,$limite_inferior, $modo = -1, $id_experiencia, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela, null, $etiqueta_grupo_gemelo, null,  $solo_usuario );
    }

}
else{
    //depende del filtro
    if ($modo == 0){
        $num_total_mensajes = dbNumMensajesTimeLine($conexion,$modo, $id_experiencia, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela,
                                    $id_grupo = null, $etiqueta_grupo_gemelo,$producto = null, $usuario = null);
        $grupos = intval($num_total_mensajes/20);
        $resto = $num_total_mensajes%20;
        if($resto > 0){
            $grupos++;
        }
        $bitacora_publicando_grupo = 1;
        $_mensajes = dbTimeLineBloque($conexion,0, $modo, $id_experiencia, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela,
                                    $id_grupo = null, $etiqueta_grupo_gemelo,$producto = null, $usuario = null);
    }
    //Mensajes de la clase gemela
    if ($modo == 1){
        $num_total_mensajes = dbNumMensajesTimeLine($conexion,$modo, $id_clase_gemela, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela,
                                    $id_grupo = null, $etiqueta_grupo_gemelo, $producto = null, $usuario = null);
        $grupos = intval($num_total_mensajes/20);
        $resto = $num_total_mensajes%20;
        if($resto > 0){
            $grupos++;
        }
        $bitacora_publicando_grupo = 1;
        $_mensajes = dbTimeLineBloque($conexion,0, $modo, $id_clase_gemela, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela,
                                    $id_grupo = null, $etiqueta_grupo_gemelo, $producto = null, $usuario = null);
    }
    // Mensajes de un grupo
    if ($modo == 3){
        $etiqueta_grupo_gemelo = $_REQUEST["et_grupo_gemelo"];
        $num_total_mensajes = dbNumMensajesTimeLine($conexion,$modo, $id_experiencia, $id_actividad, $id_exp_actividad,$etiqueta_clase_gemela,
                                $id_grupo, $etiqueta_grupo_gemelo,$producto = null, $usuario = null);
        $grupos = intval($num_total_mensajes/20);
        $resto = $num_total_mensajes%20;
        if($resto > 0){
            $grupos++;
        }
        $bitacora_publicando_grupo = 1;
        $_mensajes = dbTimeLineBloque($conexion,0, $modo, $id_experiencia, $id_actividad, $id_exp_actividad,$etiqueta_clase_gemela,
                                $id_grupo, $etiqueta_grupo_gemelo,$producto = null, $usuario = null);
        // despliegue de las imagenes de los integrantes del grupo
        if(!is_null($usuarios_grupo)or strlen($usuarios_grupo)>1){
            ?>
            <div class= "integrantes">
                <p class= "integrantes_txt"><?php echo $lang_integrantes_grupo.": ";?></p>
                <div class="bloque_integrantes">
                <?php
                foreach($usuarios_grupo as $datos_usuario){
                    $_imagen_usuario = darFormatoImagen($datos_usuario["url_avatar"],$config_ruta_img_perfil, $config_ruta_img);
                    $imagen = $_imagen_usuario["imagen_usuario"];
                ?>
                    <div class= "datos_integrate">
                        <a class="bloque_img_integrantes" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $datos_usuario["usuario"];?>"<?php } ?> title= "<?php echo $datos_usuario["usuario"];?>">
                            <img class= "img_integrantes" src="<?php echo $imagen;?>" />
                            <div class="nombre_integrantes"><?php echo $datos_usuario["nombre"];?>
                            </div>
                        </a>
                    </div>
                <?php
                }
                ?>
                    <div class="clear"></div>
                </div>
            </div>
    <?php
        }
        else{
            echo $lang_bitacora_grupos;
        }
    }
    //Mensajes de un usuario
    if (!is_null($_REQUEST["solo_usuario"]) AND strlen($_REQUEST["solo_usuario"]) > 0){
        $solo_usuario = $_REQUEST["solo_usuario"];
        $num_total_mensajes = dbNumMensajesTimeLine($conexion,$modo = -1, $id_experiencia, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela, null, $etiqueta_grupo_gemelo, null,  $solo_usuario );
        $grupos = intval($num_total_mensajes/20);
        $resto = $num_total_mensajes%20;
        if($resto > 0){
            $grupos++;
        }
        $bitacora_publicando_grupo = 1;
        $_mensajes = dbTimeLineBloque($conexion,0, $modo = -1, $id_experiencia, $id_actividad, $id_exp_actividad, $etiqueta_clase_gemela, null, $etiqueta_grupo_gemelo, null,  $solo_usuario );
    }
    
}
//Mensajes de mi clase

if (is_null($id_experiencia) or strlen($id_experiencia) == 0){
    $error = 1;
    $error_msg = "";
}
$i=0;
if ($_mensajes==null){
    echo "<br />";
    echo "<div>";
    echo $lang_no_hay_mensajes;
    echo "</div>";
    $id_ultimo_mensaje = -1;
}
else {
    ?>
<?php
        $id_ultimo_mensaje = $_mensajes[0]["id"];
        while($_mensajes[$i]) {
            $id_mensaje_actual                  = $_mensajes[$i]["id"];
            $_valoracion_mensaje                = dbObtenerMeGustaMensaje($id_mensaje_actual, $conexion);
            $_mensajes_en_respuesta_resumen     = dbObtenerMensajesEnRespuestaResumen($id_mensaje_actual, $conexion);
            $num_mensajes_en_respuesta          = dbObtenerNumMensajesEnRespuesta($id_mensaje_actual, $conexion);
            $_up_imagenes_usuario               = darFormatoImagen($_mensajes[$i]["url_imagen_perfil"], $config_ruta_img_perfil, $config_ruta_img);
            $num_valoraciones                   = count($_valoracion_mensaje);
            $num_mensajes_en_respuesta_resumen  = count($_mensajes_en_respuesta_resumen);
            $es_producto = $_mensajes[$i]["producto"]=="1";
            $up_imagen = $_up_imagenes_usuario["imagen_usuario"];
            $class_gemela = "";
            $class_gemela_respuesta = "";
            if ( $_mensajes[$i]["id_experiencia"] != $id_experiencia){
                $class_gemela = "clase_gemela";
                $class_gemela_respuesta = "_gemelo";
            }
            ?>
            <?php
           if($_mensajes[$i]["compartido"]==1){
           ?>
            <div class = "mensaje_completo">
              <div class="mensaje <?php echo $class_gemela;?> mensaje_completo_compartido" id="<?php echo $_mensajes[$i]["id"];?>">
                  <div class="msg_avatar">
                      <a  id="ventana_perfil<?php echo $_mensajes[$i]["id"];?>" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario"];?>"<?php }?> class ="link_nombre_bitacora" title ="<?php echo $_mensajes[$i]["usuario"];?>" >
                         <img src= "<?php echo $up_imagen;?> " />
                      </a>
                      
                  </div>
                  <div class="msg_texto">
                      <p>
                          <b> <?php echo $_mensajes[$i]["nombre_usuario"];?></b><?php echo " ".$lang_bit_ultimos_post_escribio." "; ?><a class="ver_mensaje_compartido_modal"  onclick="javascript: cargarMensajeCompartido('<?php echo filtrarString($_mensajes[$i]["texto"]);?>');"><?php echo $lang_bit_ultimos_post_msj; ?></a><?php echo " ".$lang_bit_ultimos_post_bit_comp; ?>
                      </p>
                      <div id= "time" class="msg_datos">
                          <div class="fecha">
                              <?php echo relativeTime($_mensajes[$i]["creado_el"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales);
                              ?>
                          </div>
                     </div>
                  </div>
              </div>
            </div>
          <?php
           }
           else{
           ?>  
            <div class = "mensaje_completo">
              <div class="mensaje <?php echo $class_gemela;?>" id="<?php echo $_mensajes[$i]["id"];?>">
                                 
                  <div class="msg_avatar">
                      <a  id="ventana_perfil<?php echo $_mensajes[$i]["id"];?>" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario"];?>"<?php }?> class ="link_nombre_bitacora" title ="<?php echo $_mensajes[$i]["usuario"];?>" >
                         <img src= "<?php echo $up_imagen;?> " />
                      </a>
                      
                  </div>
                  <div class="msg_texto">
                      <p>
                          <a  id="ventana_perfil<?php echo $_mensajes[$i]["id"];?>" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_mensajes[$i]["usuario"];?>"<?php }?> class ="link_nombre_bitacora" title ="<?php echo $_mensajes[$i]["usuario"];?>" >
                          <?php echo $_mensajes[$i]["nombre_usuario"];?></a> <?php echo $lang_bitacora_dice. ": ";?>
                          <?php echo enlazarURLs($_mensajes[$i]["texto"]);?>
                      </p>
                      <div id= "time" class="msg_datos">
                          <div class="fecha">
                              <?php echo relativeTime($_mensajes[$i]["creado_el"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales);
                              ?>
                          </div>
                          <?php
                        if (!is_null($_mensajes[$i]["id_grupo"])) echo " <b>".$_mensajes[$i]["nombre_grupo"]."</b>";
                        ?>
                        <div>
                             <?php
                            if($num_mensajes_en_respuesta==0){
                            ?>
                              <div class ="comentar_mensaje">
                                  <button class ="boton_comentar" id ="<?php echo $_mensajes[$i]["id"];?>" ><?php echo $lang_bitacora_responder;?></button>
                              </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div id="bt_gusta_mensaje<?php echo $_mensajes[$i]["id"]?>">
                           <div class ="megusta_mensaje">
                                <?php $usuario_gusta_mensaje = dbUsuarioGustaMensaje($_SESSION["klwn_id_usuario"], $id_mensaje_actual, $conexion);
                                if($usuario_gusta_mensaje > 0){
                                    echo "      <button class =\"boton_comentar\" id =\"no_gusta".$_mensajes[$i]["id"]."\">".$lang_usuarios_gusta_msj_no."</button>";
                                }
                                else{
                                    echo "      <button class =\"boton_comentar\" id =\"me_gusta".$_mensajes[$i]["id"]."\">".$lang_usuarios_gusta_msj."</button>";
                                }
                                ?>
                            </div>
                            <?php
                            if($num_valoraciones>0){
                            ?>
                            <div class ="ver_megusta">
                                <a class ="boton_ver_megusta" id ="usuarios_gusta<?php echo $_mensajes[$i]["id"];?>" href= "usuarios_gusta_mensaje.php?">
                                    <img src="<?php echo $config_ruta_img;?>me_gusta.jpg" title ="<?php echo $lang_num_megusta;?>" alt= "<?php echo $lang_num_megusta;?>"/>
                                </a>
                                <span><?php echo $num_valoraciones;?></span>
                            </div>
                            <?php
                            }
                            ?>
                       </div>

                     </div>
                  </div>
                  <div class="eliminar" id="eliminar<?php echo $_mensajes[$i]["id"];?>">
                      <?php
                      if($es_profesor_responsable){
                      ?>
                        <button  class="boton_eliminar_msj"id="eliminar_mensaje<?php echo $_mensajes[$i]["id"];?>" onclick="javascript:eliminarMensaje(<?php echo $_mensajes[$i]["id"] ?>);">
                            <img src="<?php echo $config_ruta_img;?>eliminar.png" title ="<?php echo $lang_bitacora_eliminar_mensaje;?>" />
                        </button>
                      <?php  
                      }
                      ?>
                  </div>
                  
              </div>
            
               
                <div id = "loadplace<?php echo $_mensajes[$i]["id"];?>" class="bt_cuadro_respuesta">
                    <div id="flash<?php echo $_mensajes[$i]["id"]; ?>" class='flash_load'></div>
                    <div class='panel' id="slidepanel<?php echo $_mensajes[$i]["id"]; ?>">
                        <form action="" method="post" name="<?php echo $_mensajes[$i]["id"]; ?>">
                            <textarea class ="textbox_responder" id="textboxcontent<?php echo $_mensajes[$i]["id"]; ?>" ></textarea><br />
                            <input type="submit" value=" <?php echo $lang_bitacora_enviar_respuesta;?> "  class="comment_submit" id="boton<?php echo $_mensajes[$i]["id"]; ?>" name="<?php echo $_mensajes[$i]["id"]; ?>" />
                            <div class="caract_restantes_comentario_inicial" id="caracteres_restantes_comentario"><span  id="n_caracteres_restantes<?php echo $_mensajes[$i]["id"]; ?>"><?php echo $config_char_disponibles;?></span><?php echo " ".$lang_caracteres_restantes.".";?></div>
                        </form>
                    </div>
                </div>
                <div id="bt_respuestas<?php echo $_mensajes[$i]["id"]?>">
                    <?php
                    $j=$num_mensajes_en_respuesta_resumen-1;
                    if($_mensajes_en_respuesta_resumen[$j]){
                        if($num_mensajes_en_respuesta > 3){
                        ?>
                        <div class ="ver_comentarios_mensaje<?php echo $class_gemela_respuesta;?>" id="ver_comentarios_mensaje<?php echo $_mensajes[$i]["id"];?>">
                                <button class ="boton_ver_comentarios<?php echo $class_gemela_respuesta;?>" id ="ver_comentarios<?php echo$_mensajes[$i]["id"];?>" > <?php echo $lang_comentar_ver1." ".$num_mensajes_en_respuesta." ".$lang_comentar_respuestas ;?> </button>
                        </div>
                        <div class ="ocultar_comentarios_mensaje<?php echo $class_gemela_respuesta;?>" id="ocultar_comentarios_mensaje<?php echo $_mensajes[$i]["id"];?>">
                            <button class ="boton_ocultar_comentarios<?php echo $class_gemela_respuesta;?>" id ="ocultar_comentarios<?php echo$_mensajes[$i]["id"];?>"> <?php echo $lang_comentar_ocultar." ".$lang_comentar_respuestas ;?></button>
                        </div>
                        <?php
                        }
                        ?>
                        <div class ="resumen_respuestas_mensaje" id="resumen_respuestas_mensaje<?php echo $_mensajes[$i]["id"];?>">
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
                                                    <a id="ventana_perfil<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"];?>" <?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>"<?php }?> class ="link_nombre_bitacora" title ="<?php echo $_mensajes_en_respuesta_resumen[$j]["usuario"];?>" ><?php echo $_mensajes_en_respuesta_resumen[$j]["nombre"];?></a><?php echo " ".$lang_bitacora_dice.": ";?>
                                                    <?php echo enlazarURLs($_mensajes_en_respuesta_resumen[$j]["mensaje"]);?>
                                                    <div id= "time" class="respuesta_msg_datos">
                                                        <?php echo relativeTime($_mensajes_en_respuesta_resumen[$j]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales); ?>
                                                    </div>
                                                </p>
                                             </div>

                                            <div class="eliminar_respuesta" id="eliminar_respuesta<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"];?>">
                                              <?php
                                              if($es_profesor_responsable){
                                              ?>
                                                <button  class="boton_eliminar_msj"  onclick="javascript:eliminarRespuesta(<?php echo $_mensajes_en_respuesta_resumen[$j]["id_mensaje_respuesta"] ?>);">
                                                    <img src="<?php echo $config_ruta_img;?>eliminar.png" title ="<?php echo $lang_bitacora_eliminar_mensaje;?>" />
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
                        <div class ="respuestas_mensaje<?php echo $class_gemela_respuesta;?>" id="respuestas_mensaje<?php echo $_mensajes[$i]["id"];?>">
                        </div>
                        <div class='responder_mensaje<?php echo $class_gemela_respuesta;?>' id="responder<?php echo $_mensajes[$i]["id"]; ?>">
                            <form action="" method="post" name="<?php echo $_mensajes[$i]["id"]; ?>">
                                <textarea name="<?php echo $_mensajes[$i]["id"]; ?>" class="txt_mensaje" id="contenido_textbox<?php echo $_mensajes[$i]["id"]; ?>"></textarea>
                                <input type="submit"  value="<?php echo $lang_bit_ultimos_post_responder; ?>"  class="boton_enviar_comentario" id="sub_comentario<?php echo $_mensajes[$i]["id"]; ?>" name="<?php echo $_mensajes[$i]["id"]; ?>" />
                                <div class="caracteres_restantes_comentario" id="caracteres_restantes_comentario<?php echo $_mensajes[$i]["id"];?>">
                                    <span id="n_caracteres_restantes_comentario<?php echo $_mensajes[$i]["id"];?>">
                                        <?php echo $config_char_disponibles;?>
                                    </span>
                                    <?php echo " ".$lang_caracteres_restantes.".";?>
                                </div>
                            </form>
                        </div>
                           <?php
                        }
                        ?>
                </div>
            </div>
            <?php
            }
            $i++;
        }
        //bloque ver más
        if($bitacora_publicando_grupo < $grupos ){
        ?>
                <div class ="admin_colaborador_ver_mas" id="bitacora_vermas_<?php echo $bitacora_publicando_grupo+1;?>">
                    <button class="admin_colaborador_ver_mas_boton" onclick="javascript: bitacoraVerMas(<?php echo $grupos;?>,<?php echo $bitacora_publicando_grupo+1;?>);"><?php echo $lang_bit_ultimos_post_msj_ant; ?></button>
                </div>
        <?php
        }   
}
dbDesconectarMySQL($conexion);

?>
<script type="text/javascript">
    var codexp = '<?php echo $id_experiencia;?>';
    var et_exp = '<?php echo $etiqueta; ?>';
    var et_clase_gemela = '<?php echo $etiqueta_clase_gemela; ?>';
    var id_mensaje = '<?php echo $id_ultimo_mensaje; ?>';
    var modo = '<?php echo $modo; ?>';
    var id_clase_gemela = '<?php echo $id_clase_gemela; ?>';
    var id_mensaje_valoracion = '';
    var id_mensaje_megusta = '';
    var f_solo_usuario = '<?php echo $solo_usuario; ?>';
    var f_id_grupo = '<?php echo $id_grupo; ?>';
    var f_etiqueta_grupo = '<?php echo $etiqueta_grupo; ?>';
    var f_etiqueta_grupo_gemelo  = '<?php echo $etiqueta_grupo_gemelo; ?>';
    
    
    function bitacoraVerMas(grupos, grupo){
        url_vermas = 'bitacora_ultimos_posts.php?codeexp=<?php echo $id_experiencia;?>&et_exp=<?php echo $etiqueta;?>'+
            '&et_clase_gemela=<?php echo $etiqueta_clase_gemela;?>&modo=<?php echo $modo;?>&finalizada=<?php echo $esta_finalizada;?>'+
            '&grupos='+grupos+'&grupo='+grupo;
        if (f_solo_usuario.length > 0){
            url_vermas += '&solo_usuario='+f_solo_usuario;
        }
        if (id_clase_gemela.length >0){
            url_vermas+= '&id_clase_gemela='+f_id_experiencia_gemela;
        }
        if (f_id_grupo.length >0){
            url_vermas+= '&id_grupo='+f_id_grupo;
        }
        else {
            if ((f_etiqueta_grupo != null) && (f_etiqueta_grupo != '')) url_vermas += '&et_grupo='+f_etiqueta_grupo;
            if ((f_etiqueta_grupo_gemelo != null) && (f_etiqueta_grupo_gemelo != '')) url_vermas += '&et_grupo_gemelo='+f_etiqueta_grupo_gemelo;
        }
        $.get(url_vermas, function(data) { 
            $('#bitacora_vermas_'+grupo).html(data);
        });
    }
    function btMeGustaMensaje(id_mensaje){
        url ="gusta_mensaje.php?origen=0&id_mensaje="+id_mensaje;
        $.get(url, function(data) {
          $('#bt_gusta_mensaje'+id_mensaje).html(data);
        });
    }
    function btRespuestasMensaje(id_mensaje){
        url ="resumen_respuesta.php?origen=0&id_mensaje="+id_mensaje+'&id_profesor=<?php echo $profesor["id"];?>';
        $.get(url, function(data) {
          $('#bt_respuestas'+id_mensaje).html(data);

        });
    }
    function cargarMensajeCompartido(mensaje){
            var $dialog = $('<div class=\"mensaje_compartido\"> <p>'+mensaje+'</p></div>')

            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_bit_ultimos_post_msj_may; ?>',
                width: 500,
                height: 200,
                modal: true,
                buttons: {
                    "<?php echo $lang_bit_ultimos_post_cerrar; ?>": function() {
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
    function mostrarTextoFiltro(){
        if (f_modo_timeline_exp == 0){
            texto = "<?php echo $lang_filtrando_miclase;?>";
        }
        if (f_modo_timeline_exp == 1){
            texto = "<?php echo $lang_filtrando_gemela;?>";
        }
        if (f_modo_timeline_exp == 3){
            texto = "<?php echo $lang_filtrando_grupo;?>"+ f_nombre_grupo;
        }
        if ((f_solo_usuario!=null) && (f_solo_usuario != '') ){
            texto = "<?php echo $lang_filtrando_usuario.": " ;?>"+ f_solo_usuario;
        }
        $('#filtrando').html("("+texto+")");
    }
    function mensajesNuevosTimeLine() {
        url = 'bitacora_nuevos_mensajes.php?codeexp='+codexp+
                '&et_exp='+et_exp+
                '&et_clase_gemela='+et_clase_gemela+
                '&id_mensaje='+id_mensaje+
                '&modo='+modo;
       <?php
            if (!is_null($solo_usuario)){
       ?>
                url+= '&solo_usuario='+ '<?php echo $solo_usuario; ?>' ;
      <?php
            }
            if (!is_null($etiqueta_grupo)){
       ?>
                url+= '&et_grupo='+ '<?php echo $etiqueta_grupo; ?>';
      <?php
            }
            if (!is_null($id_grupo)){
       ?>
                url+= '&id_grupo='+ '<?php echo $id_grupo; ?>';
      <?php
            }
            if (!is_null($etiqueta_grupo_gemelo)){
       ?>
                url+= '&et_grupo_gemelo='+ '<?php echo $etiqueta_grupo_gemelo; ?>';
      <?php
            }
            if (!is_null($id_clase_gemela)){
       ?>
                url+= '&id_clase_gemela='+ '<?php echo $id_clase_gemela; ?>';
      <?php
            }
       ?>
        $.get(url, function(data) {
            if (data == "0"){
                $('#msj_nuevo_timeline').fadeOut();
            }else{
                $('#msj_nuevo_timeline').fadeIn(1500);
                $('#msj_nuevo_timeline').html(data);
            }
        });
    }
    function eliminarMensaje(id_mensaje){
        var contenido = '<p class=\"resaltado\"><?php echo $lang_bitacora_eliminar_mensaje1;?></p><p><?php echo $lang_bitacora_eliminar_mensaje2;?>.</p>'; 
        var $dialog = $('<div id=\"dialogo_deshaciendo_actividad\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_bit_ultimos_post_elimina_comentario;?>',
            width: 400,
            height: 250,
            modal: true,
            buttons: {
                '<?php echo $lang_bit_ultimos_post_eliminar;?>': function() {
                    console.log("eliminar msje bit clase");
                    url_eliminar = 'bitacora_eliminar_post.php?id_mensaje='+id_mensaje+'&tipo=1';//Eliminar un mensaje y sus respues asociadas
                    $.get(url_eliminar, function(data) {
                        if(data==1){
                            leerUltimosPosts();
                            //Código agregado por Jordan Barría el 12-11-14
                            //Recarga automática bitácora al borrar un mensaje
                            var id_exp="<?php echo $id_experiencia;?>";
                            var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";
                            var id_sesion="<?php echo $_SESSION['id_sesion'];?>";
                            var tipo_bitacora="Clase";
                            //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);
                            enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);
                            //Fin código agregado por Jordan Barría el 12-11-14

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
    function eliminarRespuesta(id_mensaje){
        var contenido = '<p class=\"resaltado\"><?php echo $lang_bitacora_eliminar_mensaje1;?></p><p><?php echo $lang_bitacora_eliminar_mensaje3;?>.</p>';  
        var $dialog = $('<div id=\"dialogo_deshaciendo_actividad\">'+contenido+'</div>')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_bitacora_eliminar_titulo_ventana;?>',
            width: 400,
            height: 250,
            modal: true,
            buttons: {
                '<?php echo $lang_bit_ultimos_post_eliminar;?>': function() {
                    url_eliminar = 'bitacora_eliminar_post.php?id_mensaje='+id_mensaje+'&tipo=2';//Eliminar una respuesta
                    console.log("Elimine mensaje respuesta bit clase");
                    $.get(url_eliminar, function(data) {
                        if(data==1){
                            leerUltimosPosts();
                            //Código agregado por Jordan Barría el 12-11-14
                            //Recarga automática bitácora al borrar un mensaje
                            var id_exp="<?php echo $id_experiencia;?>";
                            var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";
                            var id_sesion="<?php echo $_SESSION['id_sesion'];?>";
                            var tipo_bitacora="Clase";
                            //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);
                            enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);
                            //Fin código agregado por Jordan Barría el 12-11-14
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
      $('.comment_submit').attr('disabled', true);
      $('.caracteres_restantes_comentario').hide();
      $('.boton_enviar_comentario').attr('disabled', true);
       $('.boton_enviar_comentario').hide();
      $('.ocultar_comentarios_mensaje<?php echo $class_gemela_respuesta;?>').hide();
      $('#msj_nuevo_timeline').hide();
      $('#cargando_enviar_respuesta').hide();
      $('.eliminar').hide();
      $('.eliminar_respuesta').hide();
      <?php
      if($esta_finalizada){
      ?>
            $('.txt_mensaje').attr('disabled', true);  
            $('.boton_enviar_comentario').attr('disabled', true);  
            $(".boton_comentar").attr('disabled', true);   
            $('.textbox_responder').attr('disabled', true);  
            
      <?php
      }
      ?>
              
      $('.mensaje').hover(function() {
          var element = $(this);
          var I = element.attr("id");
        $(this).addClass('mensaje_seleccionado');
        $("#eliminar"+I).show();
      }, function() {
          var element = $(this);
          var I = element.attr("id");
        $(this).removeClass('mensaje_seleccionado');$('#respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').hide();
        $("#eliminar"+I).hide();
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
      $(".panel").hide();
      $(".boton_comentar").click(function(){
            var element = $(this);
            var I = element.attr("id");
            $("#slidepanel"+I).slideToggle(300);
            $(this).toggleClass("active");
            return false;
        });
        $(".comment_submit").click(function(){
            var element = $(this);
            var Id = element.attr("name");
            var test = $("#textboxcontent"+Id).val();
            //$("#textboxcontent"+Id).val('');
            var dataString = 'textcontent='+ test + '&en_respuesta_a=' + Id;
            if(test=='')
            {
                alert("<?php echo $lang_bitacora_respuesta_sin_msj;?>");
            }
            else
            {
                var id_exp="<?php echo $id_experiencia;?>";//Código agregado por Jordan Barría el 12-11-14
                var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";//Código agregado por Jordan Barría el 12-11-14
                var id_sesion="<?php echo $_SESSION['id_sesion'];?>";//Código agregado por Jordan Barría el 13-12-14
                var tipo_bitacora="Clase";//Código agregado por Jordan Barría el 12-11-14
                $.ajax({
                    type: "POST",
                    url: "bitacora_enviar_post.php",
                    data: dataString,
                    cache: false,
                    //async: false,
                    success: function(){

                    $("#slidepanel"+Id).hide();
                    leerUltimosPosts();
                    //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);//Código agregado por Jordan Barría el 12-11-14
                    enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);//Código agregado por Jordan Barría el 13-12-14
                    }
                });

            }
            return false;
        });
        $(".boton_enviar_comentario").click(function(){
            var element = $(this);
            var Id = element.attr("name");
            var test = $("#contenido_textbox"+Id).val();
            $("#contenido_textbox"+Id).val('');
            var dataString = 'textcontent='+ test + '&en_respuesta_a=' + Id;
            if(test=='')
            {
                alert("<?php echo $lang_bitacora_respuesta_sin_msj;?>");
            }
            else
            {
                var id_exp="<?php echo $id_experiencia;?>";//Código agregado por Jordan Barría el 12-11-14
                var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";//Código agregado por Jordan Barría el 12-11-14
                var id_sesion="<?php echo $_SESSION['id_sesion'];?>";//Código agregado por Jordan Barría el 13-12-14
                var tipo_bitacora="Clase";//Código agregado por Jordan Barría el 12-11-14
                $.ajax({
                    type: "POST",
                    url: "bitacora_enviar_post.php",
                    data: dataString,
                    cache: false,
                    //async: false,
                    success: function(){
                    btRespuestasMensaje(Id);
                    //Avisar al resto de sesiones que se escribió un nuevo comentario en bitácora
                    //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);//Código agregado por Jordan Barría el 12-11-14
                    enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);//Código agregado por Jordan Barría el 13-12-14
                    }
                });

            }
            return false;
        });
<?php if (isset($_SESSION["klwn_experiencias_inscritas"])){?>
       $('.bloque_img_integrantes').click(function() {
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
                    "<?php echo $lang_bit_ultimos_post_cerrar; ?>": function() {
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

       $('.link_nombre_bitacora').click(function() {
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
                        "<?php echo $lang_bit_ultimos_post_cerrar; ?>": function() {
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
<?php }?>
        <?php
        $num = 0;
        while($_mensajes[$num]["id"]){
        ?>
                $('#me_gusta<?php echo $_mensajes[$num]["id"]; ?>').click(function(){
                    id_mensaje_valoracion = <?php echo $_mensajes[$num]["id"]; ?>;
                    url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=1&origen=0';
                    $.get(url_valoracion, function(data) {
                        btMeGustaMensaje(<?php echo $_mensajes[$num]["id"];?>);
                        //Código agregado por Jordan Barría el 12-11-14
                        var id_exp="<?php echo $id_experiencia;?>";
                        var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";
                        var id_sesion="<?php echo $_SESSION['id_sesion'];?>";//Código agregado por Jordan Barría el 13-12-2014
                        var tipo_bitacora="Clase";
                        //Avisar a otras sesiones que se dió un me gusta
                        //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);
                        enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);
                        //Fin código agregado por Jordan Barría el 12-11-14
                    });

                });
                $('#no_gusta<?php echo $_mensajes[$num]["id"]; ?>').click(function(){
                    id_mensaje_valoracion = <?php echo $_mensajes[$num]["id"]; ?>;
                    url_valoracion = 'insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valoracion+'&megusta=0&origen=0';
                    $.get(url_valoracion, function() {
                        btMeGustaMensaje(<?php echo $_mensajes[$num]["id"];?>);
                        //Código agregado por Jordan Barría el 12-11-14
                        var id_exp="<?php echo $id_experiencia;?>";
                        var id_usuario="<?php echo $_SESSION['klwn_id_usuario'];?>";
                        var id_sesion="<?php echo $_SESSION['id_sesion'];?>";
                        var tipo_bitacora="Clase";
                        //Avisar a otras sesiones que se quitó un me gusta
                        //enviarWebsocket("Actividad "+id_exp+" "+id_usuario+" "+tipo_bitacora);
                        enviarWebsocket("Actividad "+id_exp+" "+id_sesion+" "+tipo_bitacora);//Código agregado por Jordan Barría el 13-12-2014
                        //Fin código agregado por Jordan Barría el 12-11-14
                    });

                });
                
                $('#ver_comentarios<?php echo $_mensajes[$num]["id"]; ?>').click(function(){
                    id_mensaje_original = <?php echo $_mensajes[$num]["id"]; ?>;
                    url_respuestas = 'bitacora_respuestas_mensaje.php?id_mensaje='+id_mensaje_original+'&clase_gemela=<?php echo $class_gemela_respuesta;?>'+'&id_profesor=<?php echo $profesor["id"];?>';
                    $.get(url_respuestas, function(data) {
                        $('#resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').hide();
                        $("#ver_comentarios_mensaje<?php echo $_mensajes[$num]["id"];?>").hide();
                        $('#respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').html(data);
                        $('#respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').show();
                        $('#ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id"]; ?>').show();
                    });
                });
                $('#ocultar_comentarios<?php echo $_mensajes[$num]["id"]; ?>').click(function(){
                    $('#ocultar_comentarios_mensaje<?php echo $_mensajes[$num]["id"]; ?>').hide();
                    $('#resumen_respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').show();
                    $('#respuestas_mensaje<?php echo $_mensajes[$num]["id"]; ?>').hide();
                    $("#ver_comentarios_mensaje<?php echo $_mensajes[$num]["id"];?>").show();

                });
                $('#contenido_textbox<?php echo $_mensajes[$num]["id"]; ?>').keyup(function(){
                      var charlength = $(this).val().length;
                      var car_disponibles = <?php echo $config_char_disponibles;?>;
                      var car_restantes = car_disponibles - charlength;
                      $('#n_caracteres_restantes_comentario<?php echo $_mensajes[$num]["id"]; ?>').html(car_restantes);
                      if ((charlength > car_disponibles) || (charlength < 3)){
                          $('#sub_comentario<?php echo $_mensajes[$num]["id"]; ?>').attr('disabled', true);

                      }else{
                          $('#sub_comentario<?php echo $_mensajes[$num]["id"]; ?>').attr('disabled', false);
                      }
                });
                $('#textboxcontent<?php echo $_mensajes[$num]["id"]; ?>').keyup(function(){
                      var charlength = $(this).val().length;
                      var car_disponibles = <?php echo $config_char_disponibles;?>;
                      var car_restantes = car_disponibles - charlength;
                      $('#n_caracteres_restantes<?php echo $_mensajes[$num]["id"]; ?>').html(car_restantes);
                      if ((charlength > car_disponibles) || (charlength < 3)){
                          $('#boton<?php echo $_mensajes[$num]["id"]; ?>').attr('disabled', true);

                      }else{
                          $('#boton<?php echo $_mensajes[$num]["id"]; ?>').attr('disabled', false);
                      }
                });
                $('#contenido_textbox<?php echo $_mensajes[$num]["id"]; ?>').click(function(){
                    $('#sub_comentario<?php echo $_mensajes[$num]["id"]; ?>').show();
                    $('#caracteres_restantes_comentario<?php echo $_mensajes[$num]["id"]; ?>').show();
                    return false;

                });

                $('#usuarios_gusta<?php echo $_mensajes[$num]["id"]; ?>').click(function() {
                    var $linkc = $(this);
                    var $dialog = $('<div></div>')
                    .load($linkc.attr('href')+'id_mensaje=<?php echo $_mensajes[$num]["id"]; ?>'+'&origen=0')
                    .dialog({
                        autoOpen: false,
                        title: '<?php echo $lang_usuarios_gusta_msj_titulo_ventana;?>',
                        width: 600,
                        height: 400,
                        modal: true,
                        buttons: {
                            "<?php echo $lang_bit_ultimos_post_cerrar; ?>": function() {
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

        <?php
            $num++;
        }
        ?>
        
    });
</script>