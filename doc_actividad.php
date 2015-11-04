<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_actividad = $_REQUEST["codact"];
$es_estudiante = ($_REQUEST["rol"] == "2");
$es_observador = ($_REQUEST["rol"] == "0");
$archivos_estudiante = "";
$archivos_profesor = "";
if (is_null($id_actividad) or strlen($id_actividad) == 0){
    $error = 1;
    $error_msg = $lang_error_sin_codigo_actividad;
}
else{
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $datos_actividad = dbDisObtieneArchivosActividad($id_actividad, $conexion);
    dbDesconectarMySQL($conexion);

    if (is_null($datos_actividad)){
        $error = 2;
        $error_msg = $lang_error_sin_documento_actividad;// NO EXISTE DOCUMENTOS ASOCIADOS FALTA PONER
    }
    else{
        foreach($datos_actividad as $archivo){
            if (strcmp($archivo["solo_profesor"],"1") == 0){
                $archivos_profesor .= "<a href=\"".$config_ruta_actividades.$id_actividad."/".$archivo["nombre"]."\" title=\"".$archivo["nombre"]."\">".$archivo["nombre"]."</a><br />".$archivo["descripcion"]."<br /><div style=\"height:1px;background-color:#EDEDE9;margin-top:5px;margin-bottom:5px;\"></div>";
            }else{
                $archivos_estudiante .= "<a href=\"".$config_ruta_actividades.$id_actividad."/".$archivo["nombre"]."\" title=\"".$archivo["nombre"]."\">".$archivo["nombre"]."</a><br />".$archivo["descripcion"]."<br /><div style=\"height:1px;background-color:#EDEDE9;margin-top:5px;margin-bottom:5px;\"></div>";
            }
        }

    }
}

?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset;?>" >
	<title><?php echo $datos_actividad["nombre"];?></title>
</head>
<body>
<?php

if ($error == 0){
?>
<!--h1><?php echo $datos_actividad["nombre"];?></h1-->
<div id="contenido_actividad">
    <!--div id="titulo_actividad"><?php echo $datos_actividad["nombre"];?></div-->
    <div id="info_actividad">
    <table class="tabla_tipo">

<?php
    if(!$es_observador){
        if (!$es_estudiante){
        ?>
            <tr>
                <td class="celda_cabecera"><?php echo $lang_archivos_profesor;?></td>
                <td class="celda_contenido"><?php echo $archivos_profesor;?></td>
            </tr>
        <?php
        }
        ?>
            <tr>
                <td class="celda_cabecera"><?php echo $lang_archivos_estudiante;?></td>
                <td class="celda_contenido"><?php echo $archivos_estudiante;?></td>
            </tr>

            </table>
            </div>
        </div>
        <?php
    }
    else{
        echo "<div id=\"contenido_actividad\">\n\r";
         echo "<div class=\"no_hay_documentos\">".$lang_archivos_observador."</div>";
        echo "</div>\n\r";
    }
}
else{
    echo "<div id=\"contenido_actividad\">\n\r";
    echo "<div class=\"no_hay_documentos\">".$error_msg."</div>";
    echo "</div>\n\r";
}
?>
</body>
</html>