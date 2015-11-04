<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])

    )header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
$es_profesor_o_col = ($_SESSION["klwn_inscribe_diseno"] == 1);
?>
<div id="contenido_mis_experiencias">
    <?php

    $titulo= $lang_inscribir_dis_iniciar_exp;
    ?><div class="estado_experiencia"><?php echo $titulo ?></div><?php
    echo $lang_parrafo_inscribir_diseno;
   ?>
    <script type="text/javascript">
    $(document).ready(function(){
        var $tabes = $('#tabs2').tabs();
        $('a#link_tab_disenos_didacticos').click(function() { // bind click event to link
            $tabes.tabs('select', 2);
            return false;
        });

    });

</script>

