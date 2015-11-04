<?php
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER'])
)
    header("Location:../ingresar.php");

$ruta_raiz = "./../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");

$sel=$_GET['sel'];

if (isset($_GET['idDiseno'])) {
    $idDiseno = $_GET['idDiseno'];
}
    ?>

<div id="menu_editar_dd">
    <ul id="ul_menu_editar_dd">
        <li class="uno <?php if($sel==1) echo 'sel';?>" ><a id="editar_dd" ><?php echo $lang_crear_nuevo_diseno_titulo_2; ?></a> </li>
        <li class="dos <?php if($sel==2) echo 'sel';?>"><a id="actividades_editar_dd"><?php echo $lang_crear_nuevo_diseno_etapas_titulo; ?> </a> </li>
    </ul>
</div>
<div id="descargas">
    <a id="descargaZip" href="./taller_dd/tdd_crearZip.php?idDiseno=<?php echo $idDiseno; ?>" target="_black"><?php echo $lang_crear_diseno_descarga_zip; ?></a>
    <a id="descargaWord" href="./taller_dd/tdd_crearWord.php?idDiseno=<?php echo $idDiseno; ?>" target="_black"><?php echo $lang_crear_diseno_descarga_word; ?></a>
</div>
