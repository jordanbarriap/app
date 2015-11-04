<?php
/**
 *
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
if (!existeSesion()) {
    header( 'Location: ingresar.php');
}
$perfil_usuario = $_REQUEST["nombre_usuario"];
$es_profesor = $_SESSION["klwn_inscribe_diseno"];
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$_datos_usuario = dbObtenerInfoUsuario($perfil_usuario, $conexion);
$_experiencias_usuario = dbObtenerExpUsuario($perfil_usuario, $conexion);
if($es_profesor == 1){
?>
    <div class="muro_perfil_titulo_seccion"><?php echo $lang_mural_usuario_dm;?> </div><br/>
    <div id="muro_usuario_bloque_posteo_modal">
        <div id="muro_usuario_nuevo_mensaje_modal">
        <form id="muro_usuario_form_posteo_modal" action="">
            <div id="caja_texto_modal">
                <textarea id="txt_nuevo_post_muro_id_modal" name="txt_nuevo_post_muro_modal" cols="30" rows="6"></textarea>
            </div>
            <div class="clear"></div>
            <div class="opciones_mensaje">
                <div id="caracteres_restantes_mu_modal"><span id="n_caracteres_restantes_mu_modal"><?php echo $config_char_disponibles_md_mu;?></span><?php echo " ".$lang_caracteres_restantes.".";?></div>
            <br/>
            <div class="clear"></div>
            <div id="muro_usuario_enviar_mensaje">
                <button id="muro_usuario_boton_enviar_post_modal"><?php echo $lang_boton_enviar_mensaje;?></button>
            </div>
            
            </div>
        </form>
        </div>
    </div>
    <br/>
    <div id="muro_usuario_modal_timeline">
    </div>
    <?php
    }
    ?>
<script type="text/javascript">
    var lista_experiencias = 0;
    var mensajes = 0;
    function leerUltimosPostsMuralModal(){
        url = 'mural_usuario_ultimos_mensajes_modal.php?id_usuario=<?php echo $_datos_usuario ["id"];?>';
        $.get(url, function(data) {
          $('#muro_usuario_modal_timeline').html(data);
        });
       return false;
    }
    $(document).ready(function(){
        leerUltimosPostsMuralModal();
        $('#muro_usuario_boton_enviar_post_modal').attr('disabled', true);
        $('#txt_nuevo_post_muro_id_modal').keyup(function(){
              var charlength = $(this).val().length;
              var car_disponibles = <?php echo $config_char_disponibles_md_mu;?>;
              var car_restantes = car_disponibles - charlength;
              $('#n_caracteres_restantes_mu_modal').html(car_restantes);
              if ((charlength > car_disponibles) || (charlength < 3)){
                  $('#muro_usuario_boton_enviar_post_modal').attr('disabled', true);

              }else{
                  $('#muro_usuario_boton_enviar_post_modal').attr('disabled', false);
              }
        });
        $('#muro_usuario_boton_enviar_post_modal').click(function(){
            url = 'mural_usuario_enviar_post.php?id_usuario_muro=<?php echo $_datos_usuario ["id"];?>'+'&usuario_muro=<?php echo $perfil_usuario;?>'+
                '&id_usuario_publica='+<?php echo $_SESSION["klwn_id_usuario"];?>;
            $.post(url, $("#muro_usuario_form_posteo_modal").serialize(), function(data) {
                $("#txt_nuevo_post_muro_id_modal").html("");
                $("#txt_nuevo_post_muro_id_modal").val("");
                $("#n_caracteres_restantes_mu_modal").html('<?php echo $config_char_disponibles_md_mu;?>');
                if (data == "0"){
                    window.location.replace("ingresar.php");
                }
                leerUltimosPostsMuralModal();
            });
            $('#muro_usuario_boton_enviar_post_modal').attr('disabled', true);
            return false;
        });
    });
</script>
<?php
dbDesconectarMySQL($conexion);
?>