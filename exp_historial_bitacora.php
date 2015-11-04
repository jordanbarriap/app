<?php
/**
 * Muestra el contenido de la pestaña Historial Bitácora. Despliega la lista de 
 * actividades finalizadas del la experiencia didáctica como acordiones JQueryUI. 
 * Al desplegar un acordión, se invoca vía ajax el script 
 * historial_mensajes_actividad.php que a su vez, consulta y despliega los mensajes 
 * archivados de la bitácora para una actividad ejecutada específica.
 * Recibe:
 *  codexp: el id de la experiencia didáctica (tabla expereincia_didactica)
 *  et_exp: etiqueta de la experiencia o hashtag
 *  et_gemela: etiqueta compartida por clases gemelas
 *      
 * @author  Daniel Guerra - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt  
 * @version 0.1
 **/
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_experiencia = $_REQUEST["codexp"];
$etiqueta = $_REQUEST["et_exp"];
$etiqueta_gemela = "";
if (!is_null($_REQUEST["et_gemela"]) AND strlen($_REQUEST["et_gemela"])>0){
    $etiqueta_gemela = $_REQUEST["et_gemela"];
}
if (is_null($id_experiencia) or strlen($id_experiencia) == 0){
    $error = 1;
    $error_msg = "";
}
else{
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $_act_fin = dbExpObtenerActividadesRealizadas($id_experiencia, $conexion);
    dbDesconectarMySQL($conexion);
}
?>
<div class="contenido">
<div class="contenido_izquierda">
    <div id="lista_act_fin">
<?php
if ($error == 0){ 
    echo "        <div id=\"accordion_historial\">\n\r";
    if (is_array($_act_fin)){        
        foreach($_act_fin as $actividad){
            echo "            <h3><a href=\"historial_mensajes_actividad.php?codexp=".$id_experiencia."&codact=".$actividad["id_actividad"]."&codexpact=".$actividad["id_exp_act"]."&et_exp=".$etiqueta."&et_clase_gemela=".$etiqueta_gemela."\">".$lang_historial_de_actividad.": ".$actividad["nombre"]."</a></h3>\n\r";
            echo "            <div class=\"mensajes_historial\">\n\r";
            echo "            </div>\n\r";
        }
        echo "            <h3><a href=\"historial_mensajes_actividad.php?codexp=".$id_experiencia."&codact=-1&codexpact=-1&et_exp=".$etiqueta."&et_clase_gemela=".$etiqueta_gemela."\">".$lang_historial_antes_actividad."</a></h3>\n\r";
        echo "            <div class=\"mensajes_historial\">\n\r";
        echo "            </div>\n\r";
    }else{
        echo "            <h3><a href=\"historial_mensajes_actividad.php?codexp=".$id_experiencia."&codact=-1&codexpact=-1&et_exp=".$etiqueta."&et_clase_gemela=".$etiqueta_gemela."\">".$lang_historial_antes_actividad."</a></h3>\n\r";
        echo "            <div class=\"mensajes_historial\">\n\r";
        echo "            </div>\n\r";
    }
    echo "            </div>\n\r";
}
else{  
    mostrarError($error_msg);
}
?>       
    </div>
</div>
<div class="contenido_derecha">
   &nbsp;
</div>
</div>
<script type="text/javascript">

    $(document).ready(function(){
        /* Construye los acrodiones JQueryUI para el div con id #accordion_historial */
        $("#accordion_historial").accordion({
            header: "h3",
            collapsible: true,
            active: -1,
            autoHeight: false,
            navigation: true
        });
        /* Redefine el click de cada acordión invocando vía ajax a la URL asociada */
        $("h3","#accordion_historial").click(function(e){
            var div_contenido = $(this).next("div");
            la_url = $(this).find("a").attr("href");
            $.ajax({type:"get",url:la_url,success: function(data){
                div_contenido.html(data);
                $("#accordion_historial").accordion();
            }});
        });
   });
</script>

