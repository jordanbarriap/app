<?php
/**
 * Despliega los datos de una actividad identificada por el parámetro 
 * $_REQUEST["codact"]. El parámetro $_REQUEST["rol"] es usado para mostrar u 
 * ocultar los archivos para profesor.
 * El contenido del div "contenido_actividad" es cargado dentro de un diálogo 
 * jqueryui, desde el script ext_lista_etapas.php.  
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

$id_actividad = $_REQUEST["codact"];
$es_estudiante = ($_REQUEST["rol"] == "2");
$id_dd = $_REQUEST["id_dd"];

$archivos_estudiante = "";
$archivos_profesor = "";
if (is_null($id_actividad) or strlen($id_actividad) == 0){
    $error = 1;
    $error_msg = $lang_error_sin_codigo_actividad;
}
else{    
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $datos_actividad = dbExpObtenerActividad($id_actividad, $conexion);

    if($datos_actividad["tipo"]==1){$lugar = $lang_detact_lugar_sala;}
    if($datos_actividad["tipo"]==2){$lugar = $lang_detact_lugar_lab;}
    if($datos_actividad["tipo"]==3){$lugar = $lang_detact_lugar_terreno;}
    if (is_null($datos_actividad)){
        $error = 2;
        $error_msg = $lang_error_no_existe_actividad;
    }
    else{
        if (!is_null($datos_actividad["archivos_actividad"]) && is_array($datos_actividad["archivos_actividad"]))
        foreach($datos_actividad["archivos_actividad"] as $archivo){
            if (strcmp($archivo["solo_profesor"],"1") == 0){
                $archivos_profesor .= "<a href=\"".$config_ruta_actividades.$id_actividad."/".$archivo["nombre"]."\" title=\"".$archivo["nombre"]."\">".$archivo["nombre"]."</a><br />".$archivo["descripcion"]."<br />";
            }else{
                $archivos_estudiante .= "<a href=\"".$config_ruta_actividades.$id_actividad."/".$archivo["nombre"]."\" title=\"".$archivo["nombre"]."\">".$archivo["nombre"]."</a><br />".$archivo["descripcion"]."<br />";;
            }
        
        }
        $horas = number_format($datos_actividad["horas_estimadas"]/45,1);  
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
        <tr>
            <td class="celda_cabecera"><?php echo $lang_aprendizaje_esperado;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["aprendizaje_esperado"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_evidencia_aprendizaje;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["evidencia_aprendizaje"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_detact_descripcion;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["descripcion"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_detact_lugar;?></td>
            <td class="celda_contenido"><?php echo $lugar ;?></td>
        </tr>
        <?php
        if($datos_actividad["tipo"]== 2){
            $cont =0;
        ?>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_detact_medios;?></td>
            <td class="celda_sub_tabla">
                <table class="sub_tabla">
                <?php
                if($datos_actividad["medios_bitacora"]==1){
                    $cont++;
                ?>
                    <tr>
                        <td class="celda_sub_tabla <?php echo 'c'.$cont;?>"><img  src="img/bitacora.png" title="<?php echo $lang_exp_act_bitacora;?>" ><?php echo $lang_exp_act_bitacora;?></img></td>
                    </tr>
                <?php
                }
                if($datos_actividad["medios_trabajos"]==2){
                    $cont++;
                ?>
                    <tr>
                        <td class="celda_sub_tabla <?php echo 'c'.$cont;?>"><img  src="img/act_publicacion.png" title="<?php echo $lang_exp_act_ht_publicar; ?>" ><?php echo $lang_exp_act_ht_publicar; ?></img></td>
                    </tr>
                <?php
                }
                else{
                    if($datos_actividad["medios_trabajos"]==3){
                        $cont++;
                    ?>
                        <tr>
                            <td class="celda_sub_tabla <?php echo 'c'.$cont;?>"><img  src="img/act_revision.gif" title="<?php echo $lang_exp_act_ht_revisar; ?>" ><?php echo $lang_exp_act_ht_revisar; ?></img></td>
                        </tr>
                    <?php  
                    }
                }
                if($datos_actividad["medios_web2"]==1){
                    $cont++;
                    $herramienta_w2 = dbDisObtenerImagenHerramientaWebDD($id_dd, $conexion);
                ?>
                   <tr>
                       <td class="celda_sub_tabla <?php echo 'c'.$cont;?>"><img  src="img_herramientas/<?php echo $herramienta_w2["imagen"]?>" title="<?php echo $herramienta_w2["nombre"];?>" ><?php echo " ".$herramienta_w2["nombre"];?></img></td>
                   </tr>     
                <?php 
                }
                if(!is_null($datos_actividad["medios_otros"])&&$datos_actividad["medios_otros"]!=''){
                    $cont++;
                ?>
                   <tr><td class="celda_sub_tabla <?php echo 'c'.$cont;?>"><?php echo " ".$datos_actividad["medios_otros"];?> </td></tr>
               <?php   
                }
                ?>
                   </table>
            </td>
        </tr>
        <?php              
        }
        else{
        
        if(!is_null($datos_actividad["medios"]) && $datos_actividad["medios"]!=''){
            ?>

        <tr>
            <td class="celda_cabecera"><?php echo $lang_detact_medios;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["medios"];?></td>
        </tr>
        <?php
        }
        }
        ?>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_momento_inicio;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["instrucciones_inicio"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_momento_desarrollo;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["instrucciones_desarrollo"];?></td>
        </tr>

        <tr>
            <td class="celda_cabecera"><?php echo $lang_momento_cierre;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["instrucciones_cierre"];?></td>
        </tr>
 <?php

    if(!is_null($datos_actividad["consejos_practicos"]) && $datos_actividad["consejos_practicos"]!=''){
    ?>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_consejos;?></td>
            <td class="celda_contenido"><?php echo $datos_actividad["consejos_practicos"];?></td>
        </tr>
    <?php
        
    }
 ?>
        
    </table>
    </div>
</div>
<?php
}else{
    echo "<div id=\"contenido_actividad\">\n\r";    
    echo mostrarError($error_msg);
    echo "</div>\n\r";    
}
dbDesconectarMySQL($conexion);
?>
</body>
</html>
<script type="text/javascript">

    $(document).ready(function(){
        $('.c<?php echo $cont;?>').addClass("ultima_fila");
    });

</script>
