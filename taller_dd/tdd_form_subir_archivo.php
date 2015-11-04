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

$fca_id_actividad       = $_GET['idActividad'];
$error                  = $_GET['error'];

$ruta_raiz = "./../";

?>
<!doctype html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset;?>" >
	<title></title> 
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>taller_dd/css/tdd_default.css" />
        <style>
            body {
                border: none;
                color: #666666;
                font: 12px Helvetica,'Helvetica Neue',Arial,'Liberation Sans',FreeSans,sans-serif;
                height: 100%;
                padding: 0;
                overflow: hidden;
            }            
        </style>
</head> 
<body>
    <div id="agregar_archivo">    
            <div class="separador"></div>
            <div id="file_error" class="clear" style="color:red; height: 25px;"></div>
            <form id="form_subir_archivo" action="tdd_subirArchivo.php" method="post"  accept-charset="UTF-8" enctype="multipart/form-data">
                <div id="caja_subir_archivo">
                    <input tabindex="0" type="hidden" maxlenght="20" size="20" id="faa_id_actividad"  name="faa_id_actividad" value="<?php echo $fca_id_actividad; ?>"/>
                    <label class="label_subir_archivo"><?php echo $lang_nueva_actividad_archivos_agregar. " :"; ?></label>
                    <input tabindex="1" type="file" name="file" id="faa_file"/>
                    <div class="clear"></div>
                    <label class="label_subir_archivo"><?php echo $lang_nueva_actividad_archivos_desc . " :"; ?></label>
                    <textarea tabindex="2" id="faa_descripcion" name="faa_descripcion" rows="2" cols="1"></textarea>
                    <div class="clear"></div>
                    <label class="label_subir_archivo"><?php echo $lang_nueva_actividad_archivos_solo_prof . " :"; ?></label>
                    <input tabindex="3" type="checkbox" id="faa_solo_profesor" class ="checkbox" name="faa_solo_profesor"/>
                    <div class="clear"></div>               
                    <input tabindex="4" id="subirArchivo" type="submit" value="<?php echo $lang_nueva_actividad_material_agregar; ?>" class="faa_submit" />
                </div>         
            </form>
    </div>
</body>

<script type="text/javascript">
 
    var error = <?php echo $error; ?>;
    if(error == 0) noError();
    else withErrors(error);
    function noError(){
        window.parent.actualizarArchivos();
    }
    function withErrors(error){
        if(error==1){
            document.getElementById("file_error").innerHTML= '<?php echo $lang_nueva_actividad_arch_err1; ?>';
        }
        if(error==2){
            document.getElementById("file_error").innerHTML= '<?php echo $lang_nueva_actividad_arch_err2; ?>';            
        }
        if(error==3){
            document.getElementById("file_error").innerHTML= '<?php echo $lang_nueva_actividad_arch_err3; ?>';            
        }
        setTimeout("document.getElementById('file_error').innerHTML= '';", 7000);
    }   
</script>
</html>

