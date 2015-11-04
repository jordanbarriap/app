<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    $id_actividad           = $_GET["id_actividad"];
    $tipo                = $_GET["tipo"];
    $end                 = $_GET['end'];

    $_comentarios = obtenerComentariosFuncion($id_actividad, $tipo, $end, $conexion);
    
    if($_comentarios !=null){
?>
        <div class="separador_comentario"></div>
<?php
        for($i=0; $i<count($_comentarios)-1 ; $i++){
            $_imagen_comentario = darFormatoImagen($_comentarios[$i]['u_url_imagen'], $config_ruta_img_perfil, $config_ruta_img); 
            ?>
          <div class="tdd_comentarios_act">
              <img src="<?php echo $_imagen_comentario["imagen_usuario"];?>" class="icono_invitacion"></img>
              <b class="tc_nombre_usuario"><?php echo $_comentarios[$i]['u_nombre']; ?></b> <?php echo $lang_crear_diseno_coment_dice; ?>: <?php echo $_comentarios[$i]['tc_texto_comentario']; ?> 
          </div>
<?php
        }
    }
    if($_comentarios[count($_comentarios)-1] > $end && count($_comentarios)-1 > 0){
?>
        <div class="tdd_verMasComentarios" onClick="verMasComentariosActividad(<?php echo $id_actividad ; ?>)"><?php echo $lang_crear_diseno_coment_vermas; ?></div>
<?php            
       }
       
dbDesconectarMySQL($conexion);       
?>