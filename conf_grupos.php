<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

function subval_sort($a, $subkey) {
    foreach ($a as $k => $v) {
        $b[$k] = strtolower($v[$subkey]);
    }
    asort($b);
    foreach ($b as $k => $v) {
        $c[] = $a[$k];
    }
    return $c;
}

$id_experiencia_didac = $_GET["id_exp"];
$id_grupo = substr($_GET["id_grupo"], 1);
$id_usuario = $_GET["id_usuario"];
$inscribe_grupo = $_GET["inscribe_grupo"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

if (isset($id_grupo) && isset($id_usuario) && isset($inscribe_grupo)) {
    if ($inscribe_grupo == 1) {
        dbInsertarUsuarioGrupo($id_usuario, $id_grupo, $conexion);
    } else {
        dbEliminarUsuarioGrupo($id_usuario, $id_grupo, $conexion);
    }
}
//Obtener los estudiantes registrados en la experiencia didáctica
$_estudiantes_registrados = dbExpObtenerEstudiantesRegistrados($id_experiencia_didac, $conexion);
$n_estudiantes_registrados = count($_estudiantes_registrados);

//Obtener estudiantes sin asignar en la experiencia didáctica
$_estudiantes_sin_asignar = dbObtenerEstudiantesSinAsignar($id_experiencia_didac, $conexion);
$n_sin_asignar = count($_estudiantes_sin_asignar);

//Obtener los grupos registrados en la experiencia didáctica
$_grupos = dbExpGruposExperiencia($id_experiencia_didac, $conexion);
$n_grupos = count($_grupos);

$n_estudiantes_asignados = 0;

$experiencia_finalizada = dbExpObtenerFechaTermino($conexion, $id_experiencia_didac);


if ($n_grupos > 0) {
//Obtener cantidad estudiantes asignados
    $n_estudiantes_asignados = dbExpObtenerTotalEstudiantesAsignados($_grupos, $conexion);
?>
    <div style="float:left">
        <ul id="general" class="sortable connectedSortable">
            <li class="t_grupo ui-state-disabled"><?php echo $lang_conf_grupos_est_sin_asignar; ?></li>
        <?php
        if ($n_sin_asignar > 0) {
            $arr = subval_sort($_estudiantes_sin_asignar, "nombre_usuario");
            foreach ($arr as $_estudiante) {
                $_imagen_usuario = darFormatoImagen($_estudiante["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
        ?>
                <li class="lista <?php if($experiencia_finalizada!=""){ echo "ui-state-disabled";}?>" id="<?php echo $_estudiante["id_usuario"]; ?>">
                    <img src="<?php echo $_imagen_usuario["imagen_usuario"]; ?>" width="30" height="30" style="float:left;"><label style="line-height:2.8em"><?php echo $_estudiante["nombre_usuario"]; ?>
                    </label>
                    <div class="clear"></div>
                </li>
        <?php
            }
        } else {
            if ($n_estudiantes_registrados > 0) {
        ?>
                <label class="msg"><?php echo $lang_conf_grupos_est_asignados; ?></label>
        <?php } else {
 ?>
                <label class="msg"><?php echo $lang_conf_grupos_sin_est_asignados; ?></label>
        <?php }
        } ?>
    </ul>
</div>
<div style="float:right;width:75%">
    <?php
        $i = 1;
        foreach ($_grupos as $grupo) {
            if ($i % 3 == 0) {
    ?>
                <div>
        <?php
            }

            $_estporgru = dbExpObtenerEstudiantesPorGrupo($grupo["id_grupo"], $conexion);
        ?>
            <ul id="g<?php echo $grupo["id_grupo"] ?>" class="sortable connectedSortable">
                <li class="t_grupo ui-state-disabled <?php if($experiencia_finalizada!=""){ echo "ui-state-disabled";}?>"><?php echo $lang_conf_grupos_grupo; ?> <?php echo $i ?></li>
            <?php
            if (count($_estporgru) > 0) {
                foreach ($_estporgru as $est_g) {
                    $_imagen_usuario = darFormatoImagen($est_g["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
            ?>
                    <li class="lista <?php if($experiencia_finalizada!=""){ echo "ui-state-disabled";}?>" id="<?php echo $est_g["id_usuario"]; ?>">
                        <img src="<?php echo $_imagen_usuario["imagen_usuario"]; ?>" width="30" height="30" style="float:left;"><label style="line-height:2.8em"><?php echo $est_g["nombre_usuario"]; ?>
                        </label>
                        <div class="clear"></div>
                    </li>
            <?php
                }
            } else {
            ?>
                <label class="msg"><?php echo $lang_conf_grupos_grupo_sin_integrantes; ?></label>
            <?php } ?>
        </ul>
        <?php if ($i % 3 == 0) {
 ?>
            </div>
            <div class="clear"></div>
    <?php } ?>

    <?php
            $i++;
        }
    ?>
    </div>
<?php
    }
    dbDesconectarMySQL($conexion);
?>
    <div id="n_grupos" style="display:none"><?php echo $n_grupos; ?></div>
    <div id="n_registrados"><?php echo $n_estudiantes_registrados; ?></div>
    <div id="n_asignados"><?php echo $n_estudiantes_asignados; ?></div>
    <div id="n_sin_asignar"><?php echo $n_sin_asignar; ?></div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#n_estudiantes_asignados').html($("#n_asignados").html());
            $('#n_estudiantes_registrados').html($("#n_registrados").html());
            $('#n_estudiantes_sin_asignar').html($("#n_sin_asignar").html());

            $(function() {
                $( "#general").sortable({
                    connectWith: ".connectedSortable",
                    appendTo: '#contenido_config',
                    items: "li:not(.ui-state-disabled)",
                    receive:function(){
                        $('#dialogo_cargando').dialog('close');
                    },
                    remove:function(){
                        $('#dialogo_cargando').dialog('close');
                    }
                }).disableSelection();
            });
            <?php if ($n_grupos > 0) {
foreach ($_grupos as $grupo) { ?>
            $(function() {
                $( "#g<?php echo $grupo["id_grupo"]; ?>").sortable({
                    connectWith: ".connectedSortable",
                    appendTo: '#contenido_config',
                    items: "li:not(.ui-state-disabled)",
                    receive: function(event, ui) {
                        $('#dialogo_cargando').dialog('close');
                        $.get('conf_grupos.php?id_exp=<?php echo $id_experiencia_didac; ?>'+'&id_grupo='+this.id+'&id_usuario='+$(ui.item).attr("id")+'&inscribe_grupo=1',
                        function(data){
                            $('#asignacion_grupos').html(data);
                            return false;
                        }
                    );
                    },
                    remove: function(event,ui) {
                        $('#dialogo_cargando').dialog('close');
                        $.get('conf_grupos.php?id_exp=<?php echo $id_experiencia_didac; ?>'+'&id_grupo='+this.id+'&id_usuario='+$(ui.item).attr("id")+'&inscribe_grupo=0',
                        function(data){
                            $('#asignacion_grupos').html(data);
                            return false;
                        }
                    );
                    }
                }).disableSelection();
            });
<?php } 
}?>
});
</script>