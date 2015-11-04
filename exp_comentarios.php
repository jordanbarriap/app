<?php
/**
 * Despliega el contenido de la pestaña Testimonios.
 * El formulario para dejar un testimonio es visible sólo para profesores.
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Daniel Guerra - Kelluwen
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

$id_dd = $_REQUEST["coddd"];
$id_exp = $_REQUEST["codexp"];
$etiqueta = $_REQUEST["et_exp"];
$etiqueta_gemela = "";
$avance_experiencia = "";


//Modificar para que solo se pueda comentar cuando se jhaya terminado la ejecución del DD
//calcular el nivel de avance y si este es de un 100% mostrar el fourmulario para dejar coemntarios
if (!is_null($_REQUEST["et_gemela"]) AND strlen($_REQUEST["et_gemela"])>0){
    $etiqueta_gemela = $_REQUEST["et_gemela"];
}
if (is_null($id_exp) or strlen($id_exp) == 0){
    $id_exp = -1;}

$rol_esta_experiencia = validaExperiencia($id_exp);

if (is_null($id_dd) or strlen($id_dd) == 0){
    $error = 1;
    $error_msg = $lang_error_sin_codigo_dd;
}
else{

    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $avance_experiencia = dbExpObtenerAvance($id_exp, $conexion);
    $comentarios_experiencia = dbObtenerComentariosDD($id_dd, $id_exp, $conexion);
    dbDesconectarMySQL($conexion);
    //Calculo del avance, para saber si es que se terminó de ejecutar la experiencia
    $t_ejecutado = $avance_experiencia["suma_t_actividades_finalizadas"] OR 0;
    if (is_null($avance_experiencia["suma_sesiones_estimadas"]) OR $avance_experiencia["suma_sesiones_estimadas"] == "") $avance_experiencia["suma_sesiones_estimadas"] = 0;
    $t_estimado = $avance_experiencia["suma_sesiones_estimadas"] * $config_minutos_sesion;
    $nivel_avance = "-";
    if ($t_estimado > 0){
        $nivel_avance = $t_ejecutado / $t_estimado;
        if ($nivel_avance > 1) $nivel_avance = 1;
        $nivel_avance = 100*$nivel_avance;
    }

    if (is_null($comentarios_experiencia) OR !is_array($comentarios_experiencia)){
        $contenido = "<div class=\"no_hay_comentarios\">".$lang_no_hay_comentarios_dd."</div>";
    }
    else{
        $contenido = "";
        $impar = true;
        foreach($comentarios_experiencia as $comentario){
            $img_usuario = $comentario["url_imagen"];
            $imagenes_con_formato = darFormatoImagen($img_usuario, $config_ruta_img_perfil, $config_ruta_img);
            $fechastr = date("d-m-Y", strtotime($comentario["fecha"]));
            $clase_fila = "comfilapar";
            if ($impar) $clase_fila = "comfilaimpar";

            $contenido .= "<div class=\"comentario ".$clase_fila."\">\n\r";
            $contenido .= "<img class=\"avatar_comentario\" src=\"".$imagenes_con_formato["imagen_usuario"]."\" alt=\"".$comentario["usuario"]."\" />\n\r";
            $contenido .= "<div class=\"fecha_comentario\">".$fechastr."</div>";
            $contenido .= "<div class=\"texto_comentario_dd\">\n\r";
            $contenido .= "<p class=\"usuario_dijo\"><a href=\"perfil_usuario.php?nombre_usuario=".$comentario["usuario"]."\">".$comentario["nombre_usuario"]."</a> ";
            if(strlen($comentario["curso"])>0){
                $contenido .= "(".$comentario["curso"].", ".$comentario["colegio"].", ".$comentario["localidad"].")";
            }
            $contenido .= " ".$lang_dijo.":</p>\n\r";
            $contenido .= "<p class=\"comentario_txt\">".$comentario["comentario"]."</p>\n\r";
            $contenido .= "</div>\n\r";
            $contenido .= "</div>\n\r";
            $contenido .= "<div class=\"clear\"></div>\r\n";
            $impar = !$impar;
        }
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
    echo "<div id=\"com_exp\">\n\r";
    if($rol_esta_experiencia=="1"){
        if($nivel_avance == 100){ // Cambiarlo por la fecha de termino

 ?>
    <form id="form_comentar_dd" action="">
        <h2><?php echo $lang_cuentanos_tu_experiencia;?></h2>
        <div class="comentario_dd_exitoso"></div>
        <p class="texto_comentarios_dd"><?php echo $lang_texto_comentarios_dd;?></p>
        <!--p class="texto_comentarios_dd"><span class="resaltado"><?php echo $lang_deja_un_comentario;?></span></p-->
        <div class="caja_nuevo_comentario">
            <textarea id="pcomdd_texto" name="pcomdd_texto"></textarea>
            <input type="hidden" name="pcomdd_usuario" id="pcomdd_usuario" value="<?php echo $_SESSION["klwn_usuario"];?>" />
            <input type="hidden" name="pcomdd_nombre_usuario" id="pcomdd_nombre_usuario" value="<?php echo $_SESSION["klwn_nombre"];?>" />
            <input type="hidden" name="pcomdd_id_dd" id="pcomdd_id_dd" value="<?php echo $id_dd;?>" />
            <input type="hidden" name="pcomdd_id_exp" id="pcomdd_id_exp" value="<?php echo $id_exp;?>" />
        </div>
        <div class="enviar_comentario">
            <button id="boton_enviar_comentario_dd"><?php echo $lang_boton_enviar_comentario_dd;?></button>
        </div>
    </form>

<?php
        }
        else{
             ?>
        <h2><?php echo $lang_no_puede_dejar_testimonio;?></h2>
        <p class="texto_comentarios_dd"><?php echo $lang_testimonios_otros;?></p>
            <?php
        }
    }
    echo $contenido;
    echo "</div>\n\r";
}else{
    echo "<div id=\"contenido_actividad\">\n\r";
    echo mostrarError($error_msg);
    echo "</div>\n\r";
}
?>
<script type="text/javascript">
    function enviarComentarioDD(){
        url = 'exp_enviar_comentario_dd.php';

        $.post(url, $("#form_comentar_dd").serialize(), function() {
            $("#pcomdd_texto").val("");
            $(".comentario_dd_exitoso").html("<div><?php echo $lang_gracias_por_tu_comentario_dd?></div>");
            $(".comentario_dd_exitoso").show();
            return false;
        });

        return false;
    }

    $(document).ready(function(){
        detenerBitacoraNM();
     
        $(".comentario_dd_exitoso").hide();
        $("#boton_enviar_comentario_dd").click(function(){
            url = 'exp_enviar_comentario_dd.php';

            $.post(url, $("#form_comentar_dd").serialize(), function() {
                $("#pcomdd_texto").val("");
                $(".comentario_dd_exitoso").html("<div><?php echo $lang_gracias_por_tu_comentario_dd?></div>");
                $(".comentario_dd_exitoso").show();
            });
            return false;
        });

    });

</script>
</body>
</html>

