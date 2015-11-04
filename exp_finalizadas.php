<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])
    )header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

$id_dd = $_REQUEST["id_dd"];
$lim_inf = $_REQUEST["lim_inf"];
$lim_sup = $_REQUEST["lim_sup"];
$cant_grupos = $_REQUEST["cant_grupos"];
$cont = $_REQUEST["cont"] + 1;
$semestre = $_REQUEST["semestre"];
$anio = $_REQUEST["anio"];
if ($semestre == 1) {
    $semestre = "1° Semestre";
} else {
    if ($semestre == 2) {
        $semestre = "2° Semestre";
    }
}
$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_experiencias = dbDisObtenerExpFinalizadasDisenoLimitePeriodo($conexion, $id_dd, $lim_inf + 2, $lim_sup, $semestre, $anio);

if (count($_experiencias) > 0) {
    foreach ($_experiencias as $_experiencia) {
        $_experiencia_info = dbExpObtenerInfo($_experiencia["id_experiencia"], $conexion);
        $_imagenes = darFormatoImagen($_experiencia_info["url_avatar_profesor"], $config_ruta_img_perfil, $config_ruta_img);

        $_avance_experiencia = dbExpObtenerAvance($_experiencia["id_experiencia"], $conexion);
        $t_estimado = $_avance_experiencia["suma_sesiones_estimadas"] * $config_minutos_sesion;
        $t_ejecutado = $_avance_experiencia["suma_t_actividades_finalizadas"] OR 0;
        $nivel_avance = obtieneNivelAvanceExp($t_ejecutado, $t_estimado);

        $actividad_terminada = $_avance_experiencia["estado_ultima_actividad"] == '3';
        $experiencia_finalizada = $_experiencia_info["fecha_termino"] != '';
        $fecha = formatearFecha($_experiencia_info["fecha_ultimo_acceso"]);
        $lang_fecha_titulo = $lang_exp_finalizadas_ultima_sesion;
        $ultima_titulo = $lang_ultima_actividad_finalizada;

        if (!$actividad_terminada) {
            $ultima_titulo = $lang_actividad_actual;
        }
        if ($experiencia_finalizada) {
            $fecha = formatearFecha($_experiencia_info["fecha_termino"]);
            $lang_fecha_titulo = $lang_exp_finalizadas_fecha_termino;
        }
?>
        <div class="cuadro_experiencia">
            <table class="t_experiencia_cabecera">
                <tr>
                    <td>
                        &raquo; <a class="titulo_exp"href="experiencia.php?codexp=<?php echo $_experiencia["id_experiencia"] ?>" alt="<?php echo $_experiencia["nombre_dd"] ?>" title="<?php echo $_experiencia["nombre_dd"] ?>" ><?php echo $_experiencia["nombre_dd"] ?></a>
                        <div class="info_exp">(<?php echo $_experiencia_info["curso"] . ", " . $_experiencia_info["colegio"] . ", " . $_experiencia_info["localidad"]; ?>)</div>
                        <ul class="imagen_profesor_exp">
                            <li>
                                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia_info["usuario_profesor"]; ?>" alt="<?php echo $_experiencia_info["nombre_profesor"]; ?>" title="<?php echo $_experiencia_info["nombre_profesor"]; ?>" class ="link_perfil"><img class="inicio_lu_img" src="<?php echo $_imagenes["imagen_usuario"]; ?>"/></a>
                            </li>
                            <li>
                                <a class="nombre_profesor_exp"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia_info["usuario_profesor"]; ?>" alt="<?php echo $_experiencia_info["nombre_profesor"]; ?>" title="<?php echo $_experiencia_info["nombre_profesor"]; ?>" class ="link_perfil"><?php echo ucwords($_experiencia_info["nombre_profesor"]); ?></a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <input class="ir" type="button" onclick="location.href='experiencia.php?codexp=<?php echo $_experiencia["id_experiencia"] ?>'" value="<?php echo $lang_le_ir; ?>"><br>
                    </td>
                </tr>
            </table>
            <table class="t_experiencia">
                <tr>
                    <td>
                <?php
                if ($_avance_experiencia["ultima_actividad"] != "") {
                    echo $ultima_titulo . ": " . $_avance_experiencia["ultima_actividad"];
                } else {
                    echo $lang_sin_actividades_comenzadas;
                }
                ?>      <br>
                <?php echo $lang_fecha_titulo . ": " . $fecha ?>
            </td>
            <td><div class="avance_exp"><?php echo number_format($nivel_avance, 0) ?>%</div></td>
        </tr>
    </table>
</div>
<?php
            }
            if ($cont < $cant_grupos) {
?>

                <div id="cont<?php echo $id_dd; ?>">
                    <button id="<?php echo $id_dd; ?>" class="vermas" onclick="javascript:verMas(this.id);"><?php echo $lang_exp_finalizadas_ver_mas; ?> »</button>
                    <input id="li<?php echo $id_dd; ?>" type="hidden" value="<?php echo $lim_inf + 2; ?>">
                    <input id="ls<?php echo $id_dd; ?>"  type="hidden" value="<?php echo $lim_sup; ?>">
                    <input id="contador<?php echo $id_dd; ?>" type="hidden" value="<?php echo $cont; ?>">
                      <input id="cant_grupos<?php echo $id_dd; ?>" type="hidden" value="<?php echo $cant_grupos; ?>">
                </div>
<?php
            }
        }
        dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">
        $(document).ready(function(){

        $('.nombre_profesor_exp').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_exp_finalizadas_perfil_usuario;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_exp_finalizadas_cerrar; ?>": function() {
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