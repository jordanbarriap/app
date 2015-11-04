<?php
/**
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *  
 * @author  Elson Gueregat - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 *   
 **/

//if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./../";
require_once($ruta_raiz ."conf/config.php");
require_once($ruta_raiz ."inc/all.inc.php");
require_once($ruta_raiz ."inc/verificar_sesion.inc.php");
require_once($ruta_raiz ."taller_dd/inc/tdd_db_funciones.inc.php");
require_once($ruta_raiz ."taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

$fcp_id_actividad   = $_GET['idActividad'];
$error              = $_GET['error'];
$fcp_id_diseno      = $_GET['id_diseno'];

$ruta_raiz = "./../";

$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$enunciados = obtenerEnunciadosFuncion($conexion);

?>
<!doctype html> 
<html>
    <head>
            <meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset;?>" >
            <title><?php //echo $actividad;?></title> 
            <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>taller_dd/css/tdd_default.css" />
            <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery-ui-1.7.2.custom.css" />
            <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery.autocomplete.css" />
            <script src="<?php echo $ruta_raiz; ?>js/jquery-1.3.2.min.js" type="text/javascript" ></script>
            <script src="<?php echo $ruta_raiz; ?>js/jquery-ui-1.7.2.custom.min.js" type="text/javascript" ></script>
            <script src="<?php echo $ruta_raiz; ?>js/jquery.autocomplete.js" type="text/javascript" ></script>
            <style>
                body {
                    border: 0 none;
                    color: #666666;
                    font: 12px Helvetica,'Helvetica Neue',Arial,'Liberation Sans',FreeSans,sans-serif;
                    height: 100%;
                    padding: 0;
                    overflow: hidden;
                }            
            </style>
    </head> 
    <body>
        <div id="agregar_pauta">    
                <div class="separador"></div>
                <form id="form_agregar_pauta" action="tdd_agregarPauta.php" method="post"  accept-charset="UTF-8" enctype="multipart/form-data">
                    <div id="caja_agregar_pauta">
                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_actividad"  name="fcp_id_actividad" value="<?php echo $fcp_id_actividad; ?>"/>
                        <input tabindex="0" type="hidden" maxlenght="20" size="20" id="fcp_id_diseno"  name="fcp_id_diseno" value="<?php echo $fcp_id_diseno; ?>"/>
                        <label class="label_agregar_pauta"><?php echo $lang_nueva_actividad_pauta_enunciado . " :"; ?></label>
                        <!--<textarea tabindex="1" id="fcp_enunciado" name="fcp_enunciado" rows="2" cols="1"></textarea>-->
                        <input type="text" tabindex="1" id="fcp_enunciado" name="fcp_enunciado" />
                        <input tabindex="2" id="agregarPauta" type="submit" value="<?php echo $lang_nueva_actividad_pauta_agregar; ?>" class="fcp_submit" />
                    </div>         
                </form>
        </div>
    </body>

    <script type="text/javascript">
    enunciadosArray = new Array();
    enunArray = new Array();
<?php
    
    foreach ($enunciados as $key => $value){
        echo 'enunciadosArray.push({enu_id_enunciado:'.$value["enu_id_enunciado"].', enu_contenido:"'.$value["enu_contenido"].'"});';
        echo 'enunArray.push("'.$value["enu_contenido"].'");';
    }
  
?>
        var error = <?php echo $error; ?>;
        if(error == 0) noError();
        function noError(){
            window.parent.actualizarPautas();
        }
        
        $(document).ready(function(){
            $("#fcp_enunciado").val("");
            $("#fcp_enunciado").unbind("focus");
            $("#fcp_enunciado").autocomplete(enunArray, 
                {     
                    minChars: 0,  //make it appear as soon as we click in the field
                    max: 2000,
                    scrollHeight: 400,
                    matchContains: true,
                    selectFirst: false 
                }
                ).bind('focus', function() { if (!$(this).val().trim()) $(this).keydown(); $(this).autocomplete("search", ""); });
    
        });
        
    </script>
</html>

