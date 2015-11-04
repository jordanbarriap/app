<?php
/*
 * Ejecuta parte de la funcionalidad de valoracion de recomendaciones.
 * Se ejecuta el script para realizar los cambios en el despliegue de la
 * valoracion de las recomendaciones, mostrar/ocultar Me gusta/Ya no me gusta y
 * mostrar/ocultar imagen de dedo pulgar junto a ventana de usuarios.
 *
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Cristian Miranda - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 */

$ruta_raiz = "../";
$ruta_raiz_dos = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");

$id_mensaje = $_REQUEST["id_mensaje"];
$id_usuario = $_SESSION["klwn_id_usuario"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
// Se obtiene si el usuario de sesion valora el mensaje actual
$usuario_gusta_mensaje =  dbRECObtenerMeGustaMensaje($id_usuario, $id_mensaje, $conexion);

// Se obtiene a todos los usuarios que valoran el mensaje actual (arreglo de usuarios con sus datos)
$_usuarios_valoran_mensaje = dbRECObtenerUsuariosGustaMensaje($id_mensaje, $conexion);
// Numero de registros de usuarios que valoran el mensaje actual
$num_usuarios_valoran_mensaje = count($_usuarios_valoran_mensaje);
dbDesconectarMySQL($conexion);
?>
<div class="rec_megusta">
<?php
    // Si el usuario de sesion no valora el mensaje actual se despliega 'Me gusta'
    if($usuario_gusta_mensaje<1){ ?>
        <button class="rec_boton_megusta_mensaje" id="rec_megusta<?php echo $id_mensaje;?>"> <?php echo $lang_rec_gusta_msj_mg; ?> </button>
    <?php
    }
    else{ // Si el usuario de sesion no valora el mensaje actual se despliega 'Ya no me gusta' ?> 
        <button class="rec_boton_megusta_mensaje" id="rec_nomegusta<?php echo $id_mensaje;?>"> <?php echo $lang_rec_gusta_msj_ya_no_mg; ?> </button>
    <?php
    }
    // Si existen usuarios que valoran el mensaje actual, se despliega icono de pulgar
    if($num_usuarios_valoran_mensaje>0){
    ?>
        <div class ="rec_ver_megusta">
            <a class ="rec_boton_ver_megusta" id ="rec_usuarios_gusta<?php echo $id_mensaje;?>" href= "<?php echo $ruta_raiz_dos;?>reco/rec_lista_usuarios_valoran_mensajes.php?">
              <img src="<?php echo $config_ruta_img;?>me_gusta_dm.png" title ="<?php echo $lang_num_megusta;?>" alt= <?php echo $lang_num_megusta;?>/>
            </a>
            <span><?php echo $num_usuarios_valoran_mensaje;?></span>
        </div>
    <?php
    }
    ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#rec_megusta<?php echo $id_mensaje;?>').click(function(){
            id_mensaje_valorado = <?php echo $id_mensaje; ?>;
            url_mensaje_valorado = '<?php echo $ruta_raiz_dos;?>reco/rec_insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valorado+'&megusta=1';
            $.get(url_mensaje_valorado, function() {
                recMeGustaMensaje(<?php echo $id_mensaje?>);
            });
        });
        $('#rec_nomegusta<?php echo $id_mensaje;?>').click(function(){
            id_mensaje_valorado = <?php echo $id_mensaje; ?>;
            url_mensaje_valorado = '<?php echo $ruta_raiz_dos;?>reco/rec_insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valorado+'&megusta=0';
            $.get(url_mensaje_valorado, function() {
                recMeGustaMensaje(<?php echo $id_mensaje?>);
            });
        });
    });

    $('#rec_usuarios_gusta<?php echo $id_mensaje; ?>').each(function() {
            var $linkc = $(this);
             $linkc.click(function() {
                var $dialog = $('<div></div>')
                .load($linkc.attr('href')+'id_mensaje=<?php echo $id_mensaje; ?>')
                .dialog({
                    autoOpen: false,
                    title: '<?php echo $lang_usuarios_gusta_rec_titulo_ventana;?>',
                    width: 600,
                    height: 400,
                    modal: true,
                    buttons: {
                        "<?php echo $lang_rec_gusta_msj_cerrar; ?>": function() {
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
</script>

