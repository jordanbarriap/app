<?php
/**
 *
 * @author  Elson Gueregat - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1  
 *
 * */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
//if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");
?>
<html>
<?php
$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "concurso/con_obtenerMisDisenos.php");
require_once($ruta_raiz . "concurso/conf/con_config.php");


?>
    <head>
        <title><?php echo $titulo_pagina; ?></title>
        <meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset; ?>">
        <meta name="author" content="Kelluwen" />
        <meta name="description" lang="es" content="<?php echo $descripcion_pagina; ?>" />
        <link href="<?php echo $config_ruta_img; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/reset.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/text.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/960.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/lists.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>concurso/css/con_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>revpares/css/rp_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery-ui-1.7.2.custom.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/ui.spinner.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery.autocomplete.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>reco/css/rec_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>admin/css/admin_default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery.scrollbar.css" />        
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.validate.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.imgareaselect-0.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/info.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/ui.spinner.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.tinyscrollbar.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>revpares/inc/plugins/jquery.textbox-hinter.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.form.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/ui.core.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/ui.sortable.js"></script>
<!--        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>concurso/lib/uEditor/uEditor.js"></script>  -->    
<!--        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.form.js"></script>-->
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.tabSlideOut.v1.3.js"></script>
    </head>
    
<body>
<div class="contenido">
    <div class="titulo_dd">
        <?php echo $lang_con_titulo_nuevo_diseno;?>
    </div>    
    <form id="form_crear_diseno_nuevo" method="post" action="" accept-charset="UTF-8">
        <div id="caja_form_crear_diseno_nuevo"  style="margin-left:15px;">
            <label><?php echo $lang_crear_nuevo_diseno_nombre . " :"; ?></label>
            <input tabindex="1" type="text" maxlenght="20" size="20" id="fcdn_nombre"  name="fcdn_nombre" value=""/>
            <div class="clear"></div>
            <label><?php echo $lang_crear_nuevo_diseno_sector . " :"; ?></label>
            <select tabindex="2"  id="fcdn_sector" name="fcdn_sector" size="1">                    
            <?php
                for($i=0; $i<count($_sectores); $i++){
                    if($i == 0){
                        echo '<option value="'.$_sectores[$i]['valor'].'" selected>'.$_sectores[$i]['nombre'].'</option>';
                    }else{
                        echo '<option value="'.$_sectores[$i]['valor'].'">'.$_sectores[$i]['nombre'].'</option>';                            }
                }                    
            ?>
            </select>                       
            <div class="clear"></div>
            <label ><?php echo $lang_crear_nuevo_diseno_nivel . " :"; ?></label>
            <select tabindex="3"  id="fcdn_nivel" name="fcdn_nivel" size="1">   
            <?php
                for($i=0; $i<count($_niveles); $i++){
                    if($i == 0){
                        echo '<option value="'.$_niveles[$i].'" selected>'.$_niveles[$i].'</option>';
                    }else{
                        echo '<option value="'.$_niveles[$i].'">'.$_niveles[$i].'</option>';                            }
                }                    
            ?>                
            </select>                      
            <div class="clear"></div>            
            <input tabindex="4" class="fcdn_submit" type="submit" value="<?php echo $lang_concurso_crear_dd; ?>">
            <div class="clear"></div>            
            <div id="error_form_crear_diseno_nuevo"></div>
            <div class="clear"></div> 
        </div>
    </form>
    <div class="separador"></div> 
    <div class="separador"></div>    

  
</div>
 

<script type="text/javascript">
    function cargarDiseno(idDiseno){
        modificarDiseno(idDiseno);
    }
    
    $(document).ready(function(){

        $("#form_crear_diseno_nuevo").validate({
            rules:{
                fcdn_nombre:{
                    required: true,
                    minlength:6
                }
            },
            messages:{
                fcdn_nombre:{
                    required: '<?php echo $lang_concurso_campo_obligatorio; ?>',
                    minlength: '<?php echo $lang_concurso_largo_min; ?>'
                }                
            },
            submitHandler: function() {
                url = './con_crear_diseno.php?';

                $.post(url, $("#form_crear_diseno_nuevo").serialize(), function(id) {

                    if (parseInt(id)< 0){
                        $("#error_form_crear_diseno_nuevo").text('<?php echo $lang_concurso_no_creacion_dd; ?>');
                        $("#error_form_crear_diseno_nuevo").show();
                    }
                    else{
                        $("#error_form_crear_diseno_nuevo").text('<?php echo $lang_concurso_creacion_dd_exitosa; ?>');
                        $('#crear_disenod').show();
                        modificarDiseno(parseInt(id));
                    }
                });
            }
        });
        
    });    

</script>
</body>
</html>