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

require_once($ruta_raiz."inc/header.inc.php");
$perfil_usuario = $_REQUEST["nombre_usuario"];
?>
<div class="container_12">
    <div class="grid_1">&nbsp;</div>
    <div id="tabs_perfil" class="grid_10">
        <ul><li><a href="contenido_perfil_usuario.php?nombre_usuario=<?php echo $perfil_usuario;?>"><?php echo $lang_perfil_titulo;?></a></li>
            <li><a href="modificar_perfil.php"><?php echo $lang_modificar_perfil_titulo; ?></a></li>
            <li><a href="#modificar_imagen_perfil"><?php echo $lang_modificar_imagen_perfil_titulo; ?></a>  </li>
        </ul>
        <div id="modificar_imagen_perfil">
            <div class="container_12">
                <div id="tabs" class="grid_10">
                    <div id="contenido_modificar_imagen_perfil">
                        <div id="modificar_foto_perfil" class="perfil_titulo_seccion"><a id="modificar_foto_perfil" href="#" alt="<?php echo $lang_modificar_perfil_foto;?>" title="<?php echo $lang_modificar_perfil_foto;?>"><?php echo $lang_modificar_perfil_foto;?></a>  </div>
                        <iframe id="iframe_imagen" src="modificar_imagen_perfil.php" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>             
    </div>
    <div class="grid_1">&nbsp;</div>
</div>
<?php
require_once($ruta_raiz."inc/footer.inc.php");
?>
