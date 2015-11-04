<?php
/**
 * Muestra los documentos relacionados a la actividad.
 * Utiliza la función dbDisObtieneArchivosActividad
 *
 * Los parámetros necesarios pasados son:
 * $_REQUEST["codact"] : código de la actividad
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  José Carrasco - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_actividad = $_REQUEST["codact"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$datos_actividad = dbDisObtieneArchivosActividad($id_actividad, $conexion);
dbDesconectarMySQL($conexion);

if (!is_null($datos_actividad)) {
    foreach ($datos_actividad as $archivo) {
        $archivos_actividad .= "<a href=\"" . $config_ruta_actividades . $id_actividad . "/" . $archivo["nombre"] . "\" title=\"" . $archivo["nombre"] . "\">" . $archivo["nombre"] . "</a><br />" . $archivo["descripcion"] . "<br />";
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset; ?>" >
        <title><?php echo $datos_actividad["nombre"]; ?></title>
    </head>
    <body>
        <div id="contenido_actividad">
            <?php if ($_SESSION["klwn_inscribe_diseno"] != 1) {
            ?>
                <div class="no_hay_documentos"><?php echo $lang_dis_doc_act_prof_visualizar; ?></div>
            <?php
            } else
            if (!is_null($datos_actividad)) {
            ?>
                <div id="info_actividad">
                    <table class="tabla_tipo">
                        <tr>
                            <td class="celda_cabecera"><?php echo $lang_documentos_actividad; ?></td>
                            <td class="celda_contenido"><?php echo $archivos_actividad; ?></td>
                        </tr>

                    </table>
                </div>
<?php } else { ?>
                <div class="no_hay_documentos"><?php echo $lang_dis_doc_act_sin_documentos; ?></div>
            <?php } ?>
        </div>
    </body>
</html>