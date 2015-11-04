<?php
$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");

$cont=$_REQUEST["cont"]+1;
$lim_inf=$_REQUEST["lim_inf"];
$lim_sup=$_REQUEST["lim_sup"];
$cant_grupos = $_REQUEST["cant_grupos"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$datos = dbObtenerComunidadProfesoresLimite($conexion,$lim_inf,$lim_sup);

        foreach ($datos as $dato) {
                        $_imagenes = darFormatoImagen($dato["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
            ?>
            <li>
                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $dato["nombre_usuario"]; ?>" alt="<?php echo $dato["nombre"]; ?>" title="<?php echo $dato["nombre"]; ?>" class ="nombre_profesor_exp_todas md_link_nombre">
                    <img class="admin_avatar" src="<?php echo $_imagenes[imagen_usuario]; ?>"/></a>
            </li>
            <li>
                <a class="nombre_profesor_exp_todas"href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $dato["nombre_usuario"]; ?>" alt="<?php echo $dato["nombre"]; ?>" title="<?php echo $dato["nombre"]; ?>" class ="link_perfil md_link_nombre"></a>
            </li>
        <?php
        }
        if ($cont < $cant_grupos) {
?>

                <div id="cont">
                    <button id="ver" class="vermascomunidad" onclick="javascript:verMas();"><?php echo $lang_inicio_comun_mas_ver_mas; ?> »</button>
                    <input id="li" type="hidden" value="<?php echo $lim_inf + $lim_sup + 1; ?>">
                    <input id="ls"  type="hidden" value="<?php echo $lim_sup?>">
                    <input id="contador" type="hidden" value="<?php echo $cont; ?>">
                    <input id="cant_grupos" type="hidden" value="<?php echo $cant_grupos; ?>">
                </div>
<?php
            }
            ?>