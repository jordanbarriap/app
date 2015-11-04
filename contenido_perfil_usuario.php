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
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$perfil_usuario = $_REQUEST["nombre_usuario"];
$es_profesor = $_SESSION["klwn_inscribe_diseno"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$_datos_usuario = dbObtenerInfoUsuario($perfil_usuario, $conexion);
$tiene_muro = dbUsuarioOpcionMuro($_datos_usuario ["id"], $conexion);
$_experiencias_usuario = dbObtenerExpUsuario($perfil_usuario, $conexion);
$comentarios_usuario = dbObtenerComentariosUsuario($perfil_usuario, $conexion);
list ($anio,$mes,$dia) = split("-", $_datos_usuario["fecha_nacimiento"]);
if(!is_null($_datos_usuario["fecha_nacimiento"])){
    $_datos_usuario["fecha_nacimiento"]= $dia."-".$mes."-".$anio;
    if($_datos_usuario["fecha_nacimiento"]== "00-00-0000"){
        $_datos_usuario["fecha_nacimiento"]= '';
    }
}
$_imagen = darFormatoImagen($_datos_usuario["imagen"], $config_ruta_img_perfil, $config_ruta_img);
$imagen_normal = $_imagen["imagen_usuario"];
$imagen_grande = $_imagen["imagen_grande"];
?>
<?php
if($tiene_muro){
?>
<div id="perfil_con_muro">
    <div class="grid_4">
        <div class="muro_perfil_titulo_seccion"><?php echo $lang_perfil_datos_personales;?> </div><br/>
        <div id="perfil_usuario_info_personal">
            <table>
                <tr>
                    <td>
                        <img alt="<?php echo $perfil_usuario;?>" src="<?php echo $imagen_grande;?>" height="62"/>
                        <div id="perfil_datos">
                            <p class="datos_personales"><?php echo "<b>".$lang_perfil_nombre."</b>: ";
                                if(strlen($_datos_usuario["nombre"])!='') echo $_datos_usuario["nombre"]; else echo $lang_perfil_sin_informacion;?></p>
                            <?php if($_datos_usuario["mostrar_correo"]==1) {?>
                            <p class="datos_personales"><?php echo "<b>".$lang_perfil_correo."</b>: ";
                                if(strlen($_datos_usuario["email"])!='') echo $_datos_usuario["email"]; else echo $lang_perfil_sin_informacion;?></p>
                           <?php }?>
                            <p class="datos_personales"><?php echo "<b>".$lang_perfil_localidad."</b>: ";
                                if($_datos_usuario["localidad"]!='') echo $_datos_usuario["localidad"]; else echo $lang_perfil_sin_informacion;?></p>
                            <p class="datos_personales"><?php echo "<b>".$lang_perfil_establecimiento."</b>: ";
                                if($_datos_usuario["establecimiento"]!='') echo $_datos_usuario["establecimiento"]; else echo $lang_perfil_sin_informacion;?></p>
                            <?php if($_datos_usuario["mostrar_fecha"]==1){?>
                            <p class="datos_personales"><?php echo "<b>".$lang_perfil_fecha_nacimiento."</b>: ";
                                if($_datos_usuario["fecha_nacimiento"]!='') echo $_datos_usuario["fecha_nacimiento"];else echo $lang_perfil_sin_informacion; ?></p>
                             <?php } ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="perfil_experiencias" class="muro_perfil_titulo_seccion"><a href="#" alt="<?php echo $lang_perfil_experiencias_inscritas;?>" title="<?php echo $lang_perfil_experiencias_inscritas;?>"><?php echo $lang_perfil_experiencias_inscritas;?></a>  </div><br/>
        <div id="muro_perfil_lista_experiencias">
            <?php
            if ($_experiencias_usuario== NULL) {
                echo "<p>".$lang_perfil_no_experiencias."</p>";
            }
            else {
                echo "<ul>";
                $i=0;
                while ($_experiencias_usuario[$i]) {
                ?>
                    <li><?php echo $_experiencias_usuario[$i][nombre_dd]." - ".$_experiencias_usuario[$i]["curso"].", ".$_experiencias_usuario[$i]["colegio"]; ?></li>
                <?php
                    $i++;
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>
    <?php
    if($es_profesor == 1){
    ?>
    <div class="grid_5">
        <div class="bloque_muro" id="bloque_muro">
        </div>
    </div>
    <?php
    }
    ?>
</div>
<?php
}
else{
?>
<div id="perfil_sin_muro">
    <table>
        <tr>
            <td>
                <img alt="<?php echo $perfil_usuario;?>" src="<?php echo $imagen_grande;?>" height="62"/>
            </td>
            <td class="nombre_usuario">
                <?php echo $_datos_usuario["nombre"];?>
            </td>
        </tr>
    </table>
    <div class="perfil_titulo_seccion"><?php echo $lang_perfil_datos_personales;?> </div>
    <div id="perfil_datos_personales">
        <div id="perfil_datos">
            <?php if($_datos_usuario["mostrar_correo"]==1){?>
            <p><?php echo "<b>".$lang_perfil_correo."</b>: ";
                if(strlen($_datos_usuario["email"])!='') echo $_datos_usuario["email"]; else echo $lang_perfil_sin_informacion;?></p>
            <?php }?>
            <p><?php echo "<b>".$lang_perfil_localidad."</b>: ";
                if($_datos_usuario["localidad"]!='') echo $_datos_usuario["localidad"]; else echo $lang_perfil_sin_informacion;?></p>
            <p><?php echo "<b>".$lang_perfil_establecimiento."</b>: ";
                if($_datos_usuario["establecimiento"]!='') echo $_datos_usuario["establecimiento"]; else echo $lang_perfil_sin_informacion;?></p>
            <?php if($_datos_usuario["mostrar_fecha"]==1){?>
            <p><?php echo "<b>".$lang_perfil_fecha_nacimiento."</b>: ";
                if($_datos_usuario["fecha_nacimiento"]!='') echo $_datos_usuario["fecha_nacimiento"];else echo $lang_perfil_sin_informacion; ?></p>
            <?php } ?>
        </div>
    </div>
    <div id="perfil_experiencias" class="perfil_titulo_seccion"><a href="#" alt="<?php echo $lang_perfil_experiencias_inscritas;?>" title="<?php echo $lang_perfil_experiencias_inscritas;?>"><?php echo $lang_perfil_experiencias_inscritas;?></a>  </div>
    <div id="perfil_lista_experiencias">
        <?php
        if ($_experiencias_usuario== NULL) {
            echo "<p>".$lang_perfil_no_experiencias."</p>";
        }
        else {
            echo "<ul>";
            $i=0;
            while ($_experiencias_usuario[$i]) {
                ?>
        <li><?php echo $_experiencias_usuario[$i][nombre_dd]." - ".$_experiencias_usuario[$i]["curso"].", ".$_experiencias_usuario[$i]["colegio"]; ?></li>
                <?php
                $i++;
            }
            echo "</ul>";
        }
        ?>
    </div>
    <div id="perfil_mensajes" class="perfil_titulo_seccion"><a href="#" alt="<?php echo $lang_perfil_mensajes_bitacora;?>" title="<?php echo $lang_perfil_mensajes_bitacora;?>"><?php echo $lang_perfil_mensajes_bitacora;?></a></div>
    <div id="perfil_mensajes_bitacora">
        <?php
        if ($comentarios_usuario== NULL) {
            echo "<p>".$lang_perfil_no_comentarios."</p>";
        }
        else {
            $i=0;
            while($comentarios_usuario[$i]) {
                $es_producto = $comentarios_usuario[$i]["producto"]=="1";
                echo "<div class=\"mensaje\">";
                echo "  <div class=\"msg_avatar_perfil\">";
                echo "      <img src= ".$imagen_normal." />";
                echo "  </div>";
                echo "  <div class=\"msg_texto\">";
                echo "      <p>";
                echo "          <a href=\"#\" class =\"link_nombre\" title =\"".$perfil_usuario."\" >".$_datos_usuario["nombre"]."</a>".$lang_dice.": ";
                echo "          ".enlazarURLs($comentarios_usuario[$i]["mensaje"]);
                echo "      </p>";
                echo "      <br />";
                echo "  </div>";
                echo "  <div id= \"time\" class=\"msg_datos\">";
                echo       relativeTime($comentarios_usuario[$i]["fecha"], $rt_fecha_sin_formato, $rt_periodos, $rt_tiempo, $rt_plurales);
                if         (!is_null($comentarios_usuario[$i]["id_grupo"])) echo "<br> <b>".$comentarios_usuario[$i]["nombre_grupo"]."</b>";
                if ($es_producto) echo "      <p><img src=\"".$config_ruta_img."producto.png\" title =\"".$lang_contiene_producto."\" alt=\"".$lang_producto."\"/></p>";
                echo "  </div>";
                echo "</div>";
                $i++;
            }
        }
        ?>
    </div>
</div>
<?php
}
?>
<script type="text/javascript">
    var lista_experiencias = 0;
    var mensajes = 0;
    function cargarMuralUsuario(){
    url = 'mural_usuario.php?nombre_usuario=<?php echo $perfil_usuario;?>';
    $.get(url, function(data) {
      $('#bloque_muro').html(data);
    });
   return false;
    }
    $(document).ready(function(){
        cargarMuralUsuario();
        $('#perfil_experiencias').click(function(){
            if(lista_experiencias == 0){
                $('#perfil_lista_experiencias').hide();
                lista_experiencias=1;
            }
            else{
                $('#perfil_lista_experiencias').show();
                lista_experiencias= 0;
            }
            return false;
        });
        $('#perfil_mensajes').click(function(){
            if(mensajes == 0){
                $('#perfil_mensajes_bitacora').hide();
                mensajes = 1;
            }
            else{
                $('#perfil_mensajes_bitacora').show();
                mensajes = 0;
            }
            return false;
        });
    });
</script>
<?php
dbDesconectarMySQL($conexion);
?>