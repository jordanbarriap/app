<?php
/**
 * Despliega el avance en la experiencia didáctica utilizando la funcion
 * dbExpObtenerAvance que entrega un arreglo con la última actividad en
 * ejecución o terminada, el número de actividades finalizadas, la suma de
 * tiempos de actividades finalizadas y de tiempo esperado por etapa del diseño.
 *
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Daniel Guerra - Kelluwen
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

$id_experiencia = $_REQUEST["codexp"];
$error = 0;
$error_msg = "";

if (is_null($id_experiencia) or strlen($id_experiencia) == 0) {
    $error = 1;
    $error_msg = $lang_error_sin_codigo_experiencia;
} else {
    $conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $id_diseno = dbExpObtenerIdDiseno($id_experiencia, $conexion);
    $avance_experiencia = dbExpObtenerAvance($id_experiencia, $conexion);
    dbDesconectarMySQL($conexion);

    $rol_esta_experiencia = validaExperiencia($id_experiencia);
    $es_estudiante = ($rol_esta_experiencia == 2);

    if (is_null($avance_experiencia) OR is_null($avance_experiencia["ultima_actividad"])) {
        $error = 2;
        $error_msg = $lang_error_consulta_avance;
    } else {
        $error = 0;
    }
}
if ($error == 0) {
    $pastilla_avance_txt = "<div class=\"titulo_pastilla_avance\">" . $lang_avance_exp . "</div>\n\r";
    $pastilla_finalizadas_txt = "<div class=\"titulo_pastilla_finalizadas\">" . $lang_actividades_finalizadas . "</div>\n\r";
    $pastilla_ultima_txt = "<div class=\"titulo_pastilla_noultima\">" . $lang_sin_actividades_comenzadas . "</div>\n\r";
    $terminada = $avance_experiencia["estado_ultima_actividad"] == '3';
    $n_actividades_finalizadas = $avance_experiencia["cant_actividades_finalizadas"];
    if (is_null($n_actividades_finalizadas) or strlen($n_actividades_finalizadas) == 0)
        $n_actividades_finalizadas = 0;
    $t_ejecutado = $avance_experiencia["suma_t_actividades_finalizadas"] OR 0;
    if (is_null($avance_experiencia["suma_sesiones_estimadas"]) OR $avance_experiencia["suma_sesiones_estimadas"] == "")
        $avance_experiencia["suma_sesiones_estimadas"] = 0;

    $t_estimado = $avance_experiencia["suma_sesiones_estimadas"] * $config_minutos_sesion;
    $nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);
    $clase_ultima_actividad = "act_finalizada";
    $ultima_titulo = $lang_ultima_actividad_finalizada;
    if (!$terminada) {
        $clase_ultima_actividad = "act_ejecucion";
        $ultima_titulo = $lang_actividad_actual;
    }
    if ($avance_experiencia["ultima_actividad"] == "") {
        
    } else {
    $icono_rp = "";
        if ($avance_experiencia["ultima_publica_producto"] == "1") $icono_rp = "<img class=\"rp_icono_actividad\" src=\"img/act_publicacion.png\" title=\"".$lang_icono_publicacion."\" alt=\"".$lang_icono_publicacion."\" />";
        if ($avance_experiencia["ultima_revisa_pares"] == "1") $icono_rp .= " <img class=\"rp_icono_actividad\" src=\"img/act_revision.gif\" title=\"".$lang_icono_revision."\" alt=\"".$lang_icono_revision."\" />";
        $ultima_actividad = "<a id=\"link_ultima_actividad\" class=\"link_ventana_actividad_avance\" href=\"exp_actividad.php?codact=" . $avance_experiencia["ultima_actividad_id"] . "&rol=" . $rol_esta_experiencia."&id_dd=".$id_diseno. "\" title=\"" . $avance_experiencia["ultima_actividad"] . "\">" . $avance_experiencia["ultima_actividad"] . "</a>".$icono_rp."\n\r";
        $pastilla_ultima_txt = "<div class=\"titulo_pastilla_ultima\">" . $ultima_titulo . "</div>\n\r";
        $pastilla_ultima_txt .= "<div class=\"" . $clase_ultima_actividad . "\"><div class=\"ultima_nombre\">" . $ultima_actividad . "</div></div>\n\r";
    }
    $pastilla_finalizadas_txt .= "<div class=\"n_actividades_finalizadas\">" . $n_actividades_finalizadas . "</div>\n\r";
    $pastilla_avance_txt .= "<div class=\"porcentaje_avance\">" . number_format($nivel_avance, 0) . "%</div>\n\r";
?>
    <div class="grid_3 alpha">
        <div class="pastilla_finalizadas">
        <?php echo $pastilla_finalizadas_txt; ?>
    </div>
</div>
<div class="grid_3">
    <div class="pastilla_avance">
        <?php echo $pastilla_avance_txt; ?>
    </div>
</div>
<div class="grid_10 omega">
    <div class="pastilla_ultima">
        <?php echo $pastilla_ultima_txt; ?>
    </div>
</div>

<?php
    } else {
?>
        <div class="grid_16 alpha omega">
    <?php echo mostrarError($error_msg . " [" . $error . "]"); ?>
    </div>
<?php
    }
?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.link_ventana_actividad_avance').click(function() {
                var $linkua = $(this);
                //var referencia = 'exp_actividad.php?codact=<?php echo $avance_experiencia["ultima_actividad_id"]; ?>&es_est=<?php echo $es_estudiante; ?>';
                var $dialogua = $('<div></div>')
                .load($linkua.attr('href') + ' #contenido_actividad')
                .dialog({
                    autoOpen: false,
                    title: $linkua.attr('title'),
                    width: 600,
                    height: 450,
                    modal: true

                });
                $dialogua.dialog('open');
                return false;
            });
        });

</script>
