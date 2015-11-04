<?php
/**
 * Despliega los comentarios asociados a una actividad identificada por 
 * $_REQUEST["codact"] y dependiendo del rol de usuario y si puede comentar la 
 * actividad (un profesor que ya haya comenzado la ejecución de la actividad), 
 * muestra el formulario para agregar un comentario.
 * La funcion javastript  enviarComentarioActividad() está definida en 
 * exp_lista_etapas.php 
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *  
 * @author  Daniel Guerra - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt  
 * @version 0.1
 *   
 **/
 
$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");

$id_actividad = $_REQUEST["codact"];
$rol_usuario = $_REQUEST["rol"];
$id_exp_actividad = $_REQUEST["codexpact"];
$puede_comentar = ($_REQUEST["comentar"]=="1");
$id_diseno = $_REQUEST["coddiseno"];
$id_exp = $_REQUEST["codexp"];

$error = 0;

if (is_null($id_actividad) or strlen($id_actividad) == 0){
    $error = 1;
    $error_msg = $lang_error_sin_codigo_actividad;
}
else{
    
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $_comentarios_actividad = dbObtenerComentariosActividad($id_actividad, $conexion);
    print_r($_comentarios_actividad);
    if (is_null($_comentarios_actividad) OR !is_array($_comentarios_actividad)){
        $contenido = "<div class=\"no_hay_comentarios\">".$lang_no_hay_comentarios_act."</div>";
    }
    else{
        $contenido = "";
        $impar = true;
        foreach($_comentarios_actividad as $comentario){
            $fechastr = date("d-m-Y h:i:s A", strtotime($comentario["fecha"]));
            $nombre_real = $comentario["nombre"];
            $img_usuario = $comentario["imagen"];
            $usuario     = $comentario["usuario"];
            $img_formateada = darFormatoImagen($img_usuario, $config_ruta_img_perfil, $config_ruta_img);
            
            $clase_fila = "comfilapar";
            if ($impar) $clase_fila = "comfilaimpar";

            $contenido .= "<div class=\"comentario ".$clase_fila."\">\n\r";
            $contenido .= " <img class=\"avatar_comentario\" src=\"".$img_formateada["imagen_usuario"]."\" alt=\"".$usuario."\" />\n\r";
            $contenido .= " <div class=\"texto_comentario\">\n\r";
            $contenido .= "     <p class=\"usuario_dijo\"><span class=\"resaltado\">".$nombre_real."</span> <span class=\"rec_fecha_comentario\">(".$fechastr.")</span> ".$lang_dijo.": </p>";
            $contenido .=       $comentario["mensaje"]."\n\r";
//            $contenido .= "     <p class=\"comentario_txt\">".$comentario["mensaje"]."</p>\n\r";
            $contenido .= " </div>\n\r";
            $contenido .= "</div>\n\r";
            $contenido .= "<div class=\"clear\"></div>\r\n";
            $impar = !$impar;
        }
        dbDesconectarMySQL($conexion);
        $horas = number_format($datos_actividad["horas_estimadas"]/60,1);
    }
}

?>
<!doctype html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset;?>">
	<title><?php echo $lang_comentarios." - ".$datos_actividad["nombre"];?></title>
</head> 
<body> 
<?php
if ($error == 0){
    echo "<div id=\"contenido_actividad\">\n\r";
    if($rol_usuario!="0"){
        if($rol_usuario!="2" AND $puede_comentar){
     ?>
        <div class="comentario_actividad_exitoso"></div>
        <form id="form_comentar">
            <p><span class="resaltado"><?php echo $lang_deja_un_comentario;?></span></p>
            <div class="caja_nuevo_comentario">
                <textarea id="pcomact_texto" name="pcomact_texto"></textarea>
                <input type="hidden" name="pcomact_usuario" id="pcomact_usuario" value="<?php echo $_SESSION["klwn_usuario"];?>" />
                <input type="hidden" name="pcomact_nombre_usuario" id="pcomact_nombre_usuario" value="<?php echo $_SESSION["klwn_nombre"];?>" />
                <input type="hidden" name="pcomact_id_exp_act" id="pcomact_id_exp_act" value="<?php echo $id_exp_actividad;?>" />
                <input type="hidden" name="pcomact_id_act" id="pcomact_id_act" value="<?php echo $id_actividad;?>" />
            </div>
            <div class="enviar_comentario">
                <button class="boton_enviar_comentario" onclick="javascript: enviarComentarioActividad(); return false;"><?php echo $lang_boton_enviar_comentario;?></button>
            </div>
        </form>

    <?php
        }
    }
    else{
        echo "<div class=\"no_hay_comentarios\">".$lang_comentarios_observador."</div>\n\r";
    }
    echo $contenido;
    echo "</div>\n\r";
}else{
    echo "<div id=\"contenido_actividad\">\n\r";    
    echo mostrarError($error_msg);
    echo "</div>\n\r";
}


?>
</body>
</html>

