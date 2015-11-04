<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$semestre = $_REQUEST["semestre"];
$anio = $_REQUEST["anio"];
if($semestre == 1){
    $semestre = "1° Semestre";
}
else{
    if($semestre == 2){
        $semestre = "2° Semestre";
    }
}
$num_disenos = 1;
$lim_inf = 0;
$lim_sup = 2;

?>
<div class="contenido">
    <div id="accordion_finalizadas">
        <?php
        foreach ($_sectores as $sec) {
            $subsector = $sec["valor"];
            $titulo_subsector = $lang_le_subsector . " " . $sec["nombre"];
            $i=0;
            ?>
            
            <?php
            $_disenosdidacticos = dbDisObtenerDisenosGeneral($conexion, $subsector);
            foreach ($_disenosdidacticos as $diseno) {
                $_comentarios = dbDisObtenerComentariosAleatorios($conexion, $diseno["id_dd"]);
                $_experiencias = dbDisObtenerExpFinalizadasDisenoPeriodo($conexion, $diseno["id_dd"], $semestre, $anio);
                
                $cantidad_exp_subsector = contarExperienciasSubsector($_experiencias, $subsector);

                $cont = 1;
                $cant_grupos = intval($cantidad_exp_subsector / $lim_sup );
                $resto = $cantidad_exp_subsector % $lim_sup ;
                if ($resto > 0) {
                    $cant_grupos = $cant_grupos + 1;
                }
                if ($cantidad_exp_subsector > 0) {
                    $i++;
                    if ($i==1){?>
                       <div class="titulo_subsector"><?php echo $titulo_subsector ?></div>
                     <?php } ?>
                    <h3>
                        <a href="#">
                            <?php echo $lang_diseno . " " . $diseno["nivel"] . ": " . $diseno["nombre_dd"]; ?>
                            <?php
                            if(!is_null($diseno["herramienta_nombre"])){
                            ?>
                                <img src="<?php echo $config_ruta_img_herramientas.$diseno["herramienta_imagen"];?>" alt="<?php echo $diseno["herramienta_nombre"];?>" title="<?php echo $diseno["herramienta_nombre"];?>"></img>
                            <?php
                            }
                            else{
                            ?>
                                <img src="<?php echo $config_ruta_img_herramientas."no_herramienta.png";?>" alt="<?php echo $lang_exp_todas_exp_plataforma_kellu;?>" title="<?php echo $lang_exp_todas_exp_plataforma_kellu;?>"></img>
                            <?php
                            }
                            ?>
                        </a>
                        
                    </h3>
                    <div id="c<?php echo $diseno["id_dd"] ?>">

            <?
                    if ($cantidad_exp_subsector > $lim_sup ) {
                        $_experiencias = dbDisObtenerExpFinalizadasDisenoLimitePeriodo($conexion, $diseno["id_dd"], $lim_inf, $lim_sup, $semestre, $anio);
                    }
                    
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
                            $lang_fecha_titulo = $lang_exp_todas_exp_ult_sesion;
                            $ultima_titulo = $lang_ultima_actividad_finalizada;

                            if (!$actividad_terminada) {
                                $ultima_titulo = $lang_actividad_actual;
                            }
                            if ($experiencia_finalizada) {
                                $fecha = formatearFecha($_experiencia_info["fecha_termino"]);
                                $lang_fecha_titulo = $lang_exp_todas_exp_fecha_termino;
                            }
                        if ($subsector == $_experiencia_info["subsector"]) {
            ?>
                             <div class="cuadro_experiencia">
                                    <table class="t_experiencia_cabecera">
                                        <tr>
                                            <td>
                                                &raquo; <a class="titulo_exp"href="experiencia.php?codexp=<?php echo $_experiencia["id_experiencia"] ?>" alt="<?php echo $_experiencia["nombre_dd"] ?>" title="<?php echo $_experiencia["nombre_dd"] ?>" name="Experiencia Pública <?php echo $_experiencia["id_experiencia"] ?>" ><?php echo $_experiencia["nombre_dd"] ?></a><!--atributo name agregado por Jordan Barría el 12-04-15 para registrar su click-->
                                                <div class="info_exp">(<?php echo $_experiencia_info["curso"] . ", " . $_experiencia_info["colegio"] . ", " . $_experiencia_info["localidad"]; ?>)</div>
                                                <ul class="imagen_profesor_exp">
                                                    <li>
                                                        <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia_info["usuario_profesor"]; ?>" alt="<?php echo $_experiencia_info["nombre_profesor"]; ?>" title="<?php echo $_experiencia_info["nombre_profesor"]; ?>" class ="nombre_profesor_exp_todas"><img class="inicio_lu_img" src="<?php echo $_imagenes[imagen_usuario]; ?>"/></a>
                                                    </li>
                                                    <li>
                                                        <a class="nombre_profesor_exp_todas"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $_experiencia_info["usuario_profesor"]; ?>" alt="<?php echo $_experiencia_info["nombre_profesor"]; ?>" title="<?php echo $_experiencia_info["nombre_profesor"]; ?>" class ="link_perfil"><?php echo ucwords($_experiencia_info["nombre_profesor"]); ?></a>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <input class="ir" type="button" onclick="location.href='experiencia.php?codexp=<?php echo $_experiencia["id_experiencia"] ?>'" value="<?php echo $lang_le_ir; ?>" name="Experiencia Pública <?php echo $_experiencia["id_experiencia"] ?>"><br><!--atributo name agregado por Jordan Barría el 12-04-15 para registrar su click-->
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
                        }
                        if ($cantidad_exp_subsector > 2) {
            ?>
                            <div id="cont<?php echo $diseno["id_dd"]; ?>">
                                <button id="<?php echo $diseno["id_dd"]; ?>" class="vermas" onclick="javascript:verMas(this.id);"><?php echo $lang_exp_todas_exp_ver_mas; ?> »</button>
                                <input id="li<?php echo $diseno["id_dd"]; ?>" type="hidden" value="<?php echo $lim_inf; ?>">
                                <input id="ls<?php echo $diseno["id_dd"]; ?>"  type="hidden" value="<?php echo $lim_sup; ?>">
                                <input id="contador<?php echo $diseno["id_dd"]; ?>" type="hidden" value="<?php echo $cont; ?>">
                                <input id="cant_grupos<?php echo $diseno["id_dd"]; ?>" type="hidden" value="<?php echo $cant_grupos; ?>">
                            </div>
            <?php } ?>

                    </div>
        <?php
                    }
                }
            }
            dbDesconectarMySQL($conexion);
        ?>
        </div>
    </div>    
    <script type="text/javascript">
        $(document).ready(function(){
            $("#accordion_finalizadas").accordion({
                header: "h3",
                collapsible: true,
                active: -1,
                autoHeight: false,
                navigation: true
            });

        $('.nombre_profesor_exp_todas').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_exp_todas_exp_perfil_usuario;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_exp_todas_exp_cerrar; ?>": function() {
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
    function verMas(id){
        cont=$("#contador"+id).val();
        url = 'exp_finalizadas.php?id_dd='+id+'&lim_inf='+$("#li"+id).val()+'&lim_sup='+$("#ls"+id).val()+'&cont='+cont+'&cant_grupos='+$("#cant_grupos"+id).val()+'&semestre=<?php echo substr($semestre, 0, 1);?>'+'&anio=<?php echo $anio;?>';
        $.get(url,function(data){
            $("#cont"+id).remove();
            $("#c"+id).append(data).show('slow');

            return false;
        });
        
        return false;
    }
</script>
