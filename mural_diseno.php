<?php
/**
 * Carga la bitácora o el formulario de acceso (cuenta twitter).
 * Si no está establecida la variable de sesión $_SESSION["klwn_twitter_pass"]
 * muestra el formulario, que hace submit hacia el script
 * valida_cuenta_twitter.php que carga la información faltante en el arreglo
 * sesión (foto, contraseña twitter).
 * La bitácora es mostrada en dos bloques, izquierda con el formulario de posteo
 * y la lista de mensajes, y derecha, con los filtros y la lista de usuarios.
 * El div timeline se completa vía llamadas ajax usando jquery a
 * bitacora_ultimos_posts.php (ver función javascript leerUltimosPosts()). Los
 * parámetros pasados a este script se establecen dependiendo de los filtros
 * activos cuyo estado es manejado por variables javascript que son cambiadas al
 * presionar los enlaces de filtros (f_modo_timeline_exp, f_etiqueta_grupo, etc).
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

$id_experiencia = $_REQUEST["codexp"];
$id_diseno      = $_REQUEST["coddd"];
$id_actividad = $_REQUEST["id_actividad"];
$conexion       = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$datos_experiencia = dbExpObtenerInfo($id_experiencia, $conexion);
$filtro = "";
?>
<div class="container_16">
    <div class="grid_10">
        <div class="md_contenido_derecha">
            <div class="md_titulo_seccion"><?php echo $lang_mural_diseno_titulo;?> </div><br/>
            <div class="md_nombre_diseno">
                <?php echo $datos_experiencia["nombre_dd"]; ?>
            </div>
            <div id="md_bloque_posteo">
                <div id="md_nuevo_mensaje">
                <form id="md_form_posteo" action="">
                    <div id="md_caja_texto">
                        <textarea id="txt_nuevo_post_md_id" name="txt_nuevo_post_md" cols="30" rows="6"></textarea>
                    </div>
                    <div id="caracteres_restantes_md"><span id="n_caracteres_restantes_md"><?php echo $config_char_disponibles_md_mu;?></span><?php echo " ".$lang_caracteres_restantes.".";?></div>
                    <div class="clear"></div>
                    <div id="md_enviar_mensaje">
                        <button id="md_boton_enviar_post"><?php echo $lang_boton_enviar_mensaje;?></button>
                    </div>
                    <div class="clear"></div>
                </form>
                <div id="md_div_recargar"><?php echo "<button id=\"md_link_recarga_timeline\" title=\"".$lang_recargar_timeline."\" ><img src=\"".$config_ruta_img."recargar.png\" alt=\"".$lang_recargar_timeline."\" /></button>";?></div>
                <div class="clear"></div>
                <div>
                    <button class ="md_filtro" id="mensajes"><?php echo $lang_mural_dis_todos; ?></button>
                    <button class ="md_filtro" id="recomendaciones"><?php echo $lang_mural_dis_recomendaciones; ?></button>
                </div>
                <div id="md_msj_nuevo_timeline"></div>
                <div id="md_mensajes">
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid_6">
        <div class="md_contenido_izquierda">
            <div class="md_titulo_seccion"><?php echo $lang_mural_diseno_profesores_ejecutando;?> </div><br/>
            
        <?php
        $_profesores_participan = dbMuralDisenoObtenerProfesoresEjecutando($id_diseno, $conexion);
        $_profesores_participaron = dbMuralDisenoObtenerProfesoresEjecutaron($id_diseno, $conexion);
        $i = 0;
        if(is_null($_profesores_participan)){
            echo "<div class= \"md_no_profesores\">".$lang_mural_diseno_no_hay_profesores." </div> <br>";
        }
        else{
            ?>
            <table class="md_profesores_ejecutando">
                <tbody>
                    <?php
            while(is_null($_profesores_participan[$i]["fecha_termino"]) && !is_null($_profesores_participan[$i])){
                $imagen_usuario = darFormatoImagen($_profesores_participan[$i]["imagen"], $config_ruta_img_perfil, $config_ruta_img);
                ?>
                    <tr>
                        <td>
                            <div class="md_fondo_prof_ejecutando">
                                <img class="md_imagen_profesor_p" src="<?php echo $imagen_usuario["imagen_usuario"];?>" />
                                <div class="md_datos_profesores">
                                    <p>
                                        <b><?php echo $lang_mural_diseno_nombre.': ';?> </b> <?php echo ucwords(utf8_strtolower($_profesores_participan[$i]["nombre"]));?>
                                    </p>
                                    <p>
                                        <b><?php echo $lang_mural_diseno_comuna.': ';?> </b> <?php if($_profesores_participan[$i]["localidad"]==""){echo $lang_mural_diseno_sin_informacion;}else {echo ucwords(utf8_strtolower($_profesores_participan[$i]["localidad"]));}?>
                                    </p>
                                    <p>
                                        <b><?php echo $lang_mural_diseno_establecimiento.': ';?></b><?php if ($_profesores_participan[$i]["establecimiento"]==""){echo $lang_mural_diseno_sin_informacion;}else{echo ucwords(utf8_strtolower($_profesores_participan[$i]["establecimiento"]));}?>
                                    </p>
                                    <p>
                                        <a class="md_ver_muro_profesor" id="md_ver_muro_profesor_participa<?php echo $_profesores_participan[$i]["id_usuario"];?>" href="mural_usuario_modal.php?nombre_usuario=<?php echo $_profesores_participan[$i]["usuario"]; ?>"><?php echo $lang_mural_diseno_ver_mural;?></a>
                                    </p>
                                    <br>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php

            $i++;
        }
        ?>
                </tbody>
            </table>
        <?php
    }
        
        
        
        $i=0;
        if(!is_null($_profesores_participaron[$i])){
        ?>
        <div class="md_titulo_seccion_colaboradores"><?php echo $lang_mural_diseno_profesores_ejecutaron;?> </div><br/>
        <div class="md_ver_profesores_que_han_ejecutado">
            <button class="md_boton_ver_profesores_p"><?php echo $lang_mural_diseno_ver_profesores;?></button>
            <button class="md_boton_ocultar_profesores_p"><?php echo $lang_mural_diseno_ocultar_profesores;?> </button>
        </div>
        <table class="md_profesores_que_han_ejecutado">
            <tbody>

        <?php
        while ($_profesores_participaron[$i]){
            $imagen_usuario = darFormatoImagen($_profesores_participaron[$i]["imagen"], $config_ruta_img_perfil, $config_ruta_img);
                    ?>
                    <tr>
                        <td>
                            <div class="md_fondo_prof_ejecutando">
                                <img class="md_imagen_profesor_p" src="<?php echo $imagen_usuario["imagen_usuario"];?>" />
                                <div class="md_datos_profesores">
                                    <p>
                                        <b><?php echo $lang_mural_diseno_nombre.': ';?> </b> <?php echo  ucwords(utf8_strtolower($_profesores_participaron[$i]["nombre"]));?>
                                    </p>
                                    <p>
                                        <b><?php echo $lang_mural_diseno_comuna.': ';?></b> <?php if($_profesores_participaron[$i]["localidad"]==""){echo $lang_mural_diseno_sin_informacion;}else {echo ucwords(utf8_strtolower($_profesores_participaron[$i]["localidad"]));}?>
                                    </p>
                                    <p>
                                        <b><?php echo $lang_mural_diseno_establecimiento.': ';?></b><?php if ($_profesores_participaron[$i]["establecimiento"]==""){echo $lang_mural_diseno_sin_informacion;}else{echo ucwords(utf8_strtolower($_profesores_participaron[$i]["establecimiento"]));}?>
                                    </p>
                                    <p>
                                        <a class="md_ver_muro_profesor" id="md_ver_muro_profesor_participo<?php echo $_profesores_participaron[$i]["id_usuario"];?>" href="mural_usuario_modal.php?nombre_usuario=<?php echo $_profesores_participaron[$i]["usuario"]; ?>"><?php echo $lang_mural_diseno_ver_mural;?></a>
                                    </p>
                                    <br>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
            $i++;
            }
            ?>
            </tbody>
        </table>
        <?php
        }
        $i=0;
        $_colaboradores_participan = dbMuralDisenoObtenerColaboradores($id_diseno, $conexion);
        dbDesconectarMySQL($conexion);
        
        if(!is_null($_colaboradores_participan[$i])){
        ?>
        
            <div class="md_titulo_seccion_colaboradores"><?php echo $lang_mural_diseno_colaboradores;?> </div><br/>
            <div class="md_ver_colaboradores">
                <button class="md_boton_ver_colaboradores"><?php echo $lang_mural_diseno_ver_colaboradores;?></button>
                <button class="md_boton_ocultar_colaboradores"><?php echo $lang_mural_diseno_ocultar_colaboradores;?></button>
            </div>
            <table class="md_colaboradores_ejecutando">
                <tbody>
            <?php
            while ($_colaboradores_participan[$i]){
                $imagen_usuario = darFormatoImagen($_colaboradores_participan[$i]["imagen"], $config_ruta_img_perfil, $config_ruta_img);
            ?>
                    <tr>
                        <td>
                            <div class="md_fondo_prof_ejecutando">
                                <img class="md_imagen_profesor_p" src="<?php echo $imagen_usuario["imagen_usuario"];?>" />
                                <div class="md_datos_profesores">
                                    <p>
                                        <b><?php echo $lang_mural_diseno_nombre.': ';?></b> <?php echo  ucwords(utf8_strtolower($_colaboradores_participan[$i]["nombre"]));?>
                                    </p>
                                    <p>
                                        <b><?php echo $lang_mural_diseno_comuna.': ';?> </b> <?php if($_colaboradores_participan[$i]["localidad"]==""){echo $lang_mural_diseno_sin_informacion;}else {echo ucwords(utf8_strtolower($_colaboradores_participan[$i]["localidad"]));}?>
                                    </p>
                                    <p>
                                        <b><?php echo $lang_mural_diseno_establecimiento.': ';?></b><?php if ($_colaboradores_participan[$i]["establecimiento"]==""){echo $lang_mural_diseno_sin_informacion;}else{echo ucwords(utf8_strtolower($_colaboradores_participan[$i]["establecimiento"]));}?>
                                    </p>
                                    <br>
                                    <p>
                                        <a class="md_ver_muro_profesor" id="md_ver_muro_profesor_colaborador<?php echo $_colaboradores_participan[$i]["id_usuario"];?>" href="mural_usuario_modal.php?nombre_usuario=<?php echo $_colaboradores_participan[$i]["usuario"];?>"><?php echo $lang_mural_diseno_ver_mural;?></a>
                                    </p>
                                    <br>
                                </div>
                            </div>
                        </td>
                    </tr>
            <?php
            $i++;
            }
        }
        ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<script type="text/javascript">
    var filtro = '';
    function leerUltimosMensajesMuralDiseno(){
        
        url = 'mural_diseno_ultimos_mensajes.php?id_diseno=<?php echo $id_diseno;?>&filtro='+filtro;
        $.get(url, function(data) {
          $('#md_mensajes').html(data);
        });
       return false;
    }
    $(document).ready(function(){
        repeticionMD = window.setInterval("mensajesNuevosMuralDiseno()",120000);
        detenerBitacoraNM();
        detenerBitacoraCompartidaNM();
        leerUltimosMensajesMuralDiseno();
        $('.md_colaboradores_ejecutando').hide();
        $('.md_profesores_que_han_ejecutado').hide();
        $('.md_boton_ocultar_colaboradores').hide();
        $('.md_boton_ocultar_profesores_p').hide();
        $('#md_link_recarga_timeline').click(function(){
            leerUltimosMensajesMuralDiseno();
        });
        $('#recomendaciones').click(function(){
            filtro = 3;
            leerUltimosMensajesMuralDiseno();
        });
        $('#mensajes').click(function(){
            filtro = '';
            leerUltimosMensajesMuralDiseno();
        });
        $('#md_boton_enviar_post').click(function(){
            url = 'mural_diseno_enviar_post.php?id_diseno=<?php echo $id_diseno;?>'+
                '&id_experiencia=<?php echo $id_experiencia;?>'+'&id_actividad=<?php echo $id_actividad;?>';
            $.post(url, $("#md_form_posteo").serialize(), function(data) {
                $("#txt_nuevo_post_md_id").html("");
                $("#txt_nuevo_post_md_id").val("");
                $("#n_caracteres_restantes_md").html('<?php echo $config_char_disponibles_md_mu;?>');
                if (data == "0"){
                    window.location.replace("ingresar.php");
                }
                else{
                    $.ajax({
                        type: "POST",
                        url: "notificaciones_correo.php?id_diseno=<?php echo $id_diseno;?>",
                        async: true,
                        success: function(){

                        }
                    });
                    leerUltimosMensajesMuralDiseno();
                }
            });
            return false;
        });
        $('#txt_nuevo_post_md_id').keyup(function(){
              var charlength = $(this).val().length;
              var car_disponibles = <?php echo $config_char_disponibles_md_mu;?>;
              var car_restantes = car_disponibles - charlength;
              $('#n_caracteres_restantes_md').html(car_restantes);
              if ((charlength > car_disponibles) || (charlength < 3)){
                  $('#md_boton_enviar_post').attr('disabled', true);

              }else{
                  $('#md_boton_enviar_post').attr('disabled', false);
              }
        });
        $('.md_boton_ver_colaboradores').click(function(){
           $('.md_colaboradores_ejecutando').show();
           $('.md_boton_ocultar_colaboradores').show();
           $('.md_boton_ver_colaboradores').hide();
        });
        $('.md_boton_ocultar_colaboradores').click(function(){
            $('.md_colaboradores_ejecutando').hide();
            $('.md_boton_ocultar_colaboradores').hide();
            $('.md_boton_ver_colaboradores').show();
        });
         $('.md_boton_ver_profesores_p').click(function(){
           $('.md_profesores_que_han_ejecutado').show();
           $('.md_boton_ocultar_profesores_p').show();
           $('.md_boton_ver_profesores_p').hide();
        });
        $('.md_boton_ocultar_profesores_p').click(function(){
            $('.md_profesores_que_han_ejecutado').hide();
            $('.md_boton_ocultar_profesores_p').hide();
            $('.md_boton_ver_profesores_p').show();
        });
        <?php
        $j = 0;
        $i = 0;
        $k = 0;
        while($_profesores_participan[$j]){
                ?>
                    $('#md_ver_muro_profesor_participa<?php echo $_profesores_participan[$j]["id_usuario"]; ?>').click(function() {
                        var $linkc = $(this);
                        var $dialog = $('<div></div>')
                        .load($linkc.attr('href')+'&id_mensaje=<?php echo $_profesores_participan[$j]["id_usuario"]; ?>'+'&origen=2')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_mural_diseno_ventana_perfil;?>',
                            width: 400,
                            height: 600,
                            modal: true,
                            buttons: {
                                "<?php echo $lang_mural_dis_cerrar; ?>": function() {
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
            $j++;
        }
        while($_profesores_participaron[$i]){
                ?>
                    $('#md_ver_muro_profesor_participo<?php echo $_profesores_participaron[$i]["id_usuario"]; ?>').click(function() {
                        var $linkc = $(this);
                        var $dialog = $('<div></div>')
                        .load($linkc.attr('href')+'&id_mensaje=<?php echo $_profesores_participaron[$i]["id_usuario"]; ?>'+'&origen=2')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_mural_diseno_ventana_perfil;?>',
                            width: 400,
                            height: 600,
                            modal: true,
                            buttons: {
                                "<?php echo $lang_mural_dis_cerrar; ?>": function() {
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

            $i++;
        }
        while($_colaboradores_participan[$k]){
                ?>
                    $('#md_ver_muro_profesor_colaborador<?php echo $_colaboradores_participan[$k]["id_usuario"]; ?>').click(function() {
                        var $linkc = $(this);
                        var $dialog = $('<div></div>')
                        .load($linkc.attr('href')+'&id_mensaje=<?php echo $_colaboradores_participan[$k]["id_usuario"]; ?>'+'&origen=2')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_mural_diseno_ventana_perfil;?>',
                            width: 400,
                            height: 600,
                            modal: true,
                            buttons: {
                                "<?php echo $lang_mural_dis_cerrar; ?>": function() {
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
            $k++;
        }
        ?>
        
  });
</script>