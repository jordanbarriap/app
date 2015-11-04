<?php
/*
 * Script asociado a ventana pop-up que muestra el listado de usuarios que
 * valoran una recomendacion.
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
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");

$id_usuario = $_SESSION["klwn_id_usuario"];
$id_mensaje = $_REQUEST["id_mensaje"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
// Se obtiene a todos los usuarios que valoran el mensaje actual (arreglo de usuarios con sus datos)
$_usuarios_valoran_mensaje = dbRECObtenerUsuariosGustaMensaje($id_mensaje, $conexion);
// Numero de registros de usuarios que valoran el mensaje actual
$num_usuarios_valoran_mensaje = count($_usuarios_valoran_mensaje);
?>

<table class="rec_t_usuarios_gustamsj" id="rec_tabla_resumen">
    <thead>
        <tr>
            <td class="col1_gustamsj"><?php echo $lang_usuarios_gusta_msj_nombre;?> </td>
            <td class="col2_gustamsj"><?php echo $lang_usuarios_gusta_msj_establecimiento;?></td>
            <td class="col3_gustamsj"><?php echo $lang_usuarios_gusta_msj_comuna;?> </td>
        </tr>
    </thead>
       <tbody>
<?php
$i=0;
while ($_usuarios_valoran_mensaje[$i]){
    $imagen_usuario = darFormatoImagen($_usuarios_valoran_mensaje[$i]["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
    if($i <3){
    ?>
        <tr>
            <td class="col1_gustamsj">
                <div>
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>" class="rec_img_integrantes" title="<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>">
                        <img src="<?php echo $imagen_usuario["imagen_usuario"];?>" />
                    </a>
                </div>
                <div class="rec_nombre_usuario_gustamsj">
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>" class=rec_img_integrantes" title="<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>">
                        <?php echo ucwords(strtolower($_usuarios_valoran_mensaje[$i]["nombre"]));?>
                    </a>
                </div>
            </td>
            <td class="col2_gustamsj">
                <?php echo ucwords(strtolower($_usuarios_valoran_mensaje[$i]["establecimiento"]));?>
            </td>
            <td class="col3_gustamsj">
                <?php echo ucwords(strtolower($_usuarios_valoran_mensaje[$i]["localidad"]));?>
            </td>
        </tr>
    <?php

    }
    else{
        ?>
        <tr class="rec_tabla_completa">
            <td class="col1_gustamsj">
                <div>
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>" class="rec_img_integrantes" title="<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>">
                        <img src="<?php echo $imagen_usuario["imagen_usuario"];?>" />
                    </a>
                </div>
                <div class="rec_nombre_usuario_gustamsj">
                    <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>" class="rec_img_integrantes" title="<?php echo $_usuarios_valoran_mensaje[$i]["usuario"];?>">
                        <?php echo ucwords(strtolower($_usuarios_valoran_mensaje[$i]["nombre"]));?>
                    </a>
                </div>
            </td>
            <td class="col2_gustamsj">
                <?php echo ucwords(strtolower($_usuarios_valoran_mensaje[$i]["establecimiento"]));?>
            </td>
            <td class="col3_gustamsj">
                <?php echo ucwords(strtolower($_usuarios_valoran_mensaje[$i]["localidad"]));?>
            </td>
        </tr>
      <?php
    }
    $i++;
}
?>
    </tbody>
</table>
<?php
if($num_usuarios_valoran_mensaje > 3){
?>
<div class="rec_ver_mas_gusta_mensaje">
    <a id="rec_ver_mas_usuarios_gusta_mensaje"><?php echo $lang_usuarios_gusta_msj_ver_todos; ?></a>
</div>

<?php
}
?>
<script type="text/javascript">

    $(document).ready(function(){
        $(".rec_tabla_completa").fadeOut(1);
        $("#rec_ver_mas_usuarios_gusta_mensaje").click(function (){
            $(".rec_tabla_completa").fadeIn(500);
            $("#rec_ver_mas_usuarios_gusta_mensaje").hide();
        });
        $('.rec_img_integrantes').click(function() {
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
                        "<?php echo $lang_rec_lista_usuarios_mg_cerrar; ?>": function() {
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