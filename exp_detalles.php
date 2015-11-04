<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$id_experiencia = $_REQUEST["codexp"];
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$datos_experiencia = dbExpObtenerInfo($id_experiencia, $conexion);
$herramienta_experiencia = dbDisObtenerImagenHerramientaWebDD($datos_experiencia["id_dd"], $conexion);
$i=0;
while($_lang_le_subsectores[$i]){
    if($datos_experiencia["subsector"]== $_lang_le_subsectores[$i]){
        break;
    }
    $i++;
}
$datos_experiencia["subsector"] = $_lang_le_subsectores_ext[$i];
dbDesconectarMySQL($conexion);

?>
    <div id="info_exp">
        <table class="tabla_tipo">
        <tr>
            <td class="celda_cabecera"><?php echo $lang_sector;?></td>
            <td class="celda_contenido"><?php echo $datos_experiencia["subsector"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_nivel;?></td>
            <td class="celda_contenido"><?php echo $datos_experiencia["nivel"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_descripcion;?></td>
            <td class="celda_contenido"><?php echo $datos_experiencia["descripcion"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_objetivos_c;?></td>
            <td class="celda_contenido"><?php echo $datos_experiencia["objetivos_c"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_objetivos_t;?></td>
            <td class="celda_contenido"><?php echo $datos_experiencia["objetivos_t"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera"><?php echo $lang_contenidos;?></td>
            <td class="celda_contenido"><?php echo $datos_experiencia["contenidos"];?></td>
        </tr>
        <tr>
            <td class="celda_cabecera ultima_fila"><?php echo $lang_herramienta;?></td>
            <td class="celda_contenido ultima_fila">
                <a href="<?php echo $herramienta_experiencia["enlace"];?>"  target="_blank">
                    <img src="<?php echo $config_ruta_img_herramientas.$herramienta_experiencia["imagen"];?>" alt="<?php echo $herramienta_experiencia["nombre"];?>" title="<?php echo $herramienta_experiencia["nombre"];?>"></img>
                </a>
            
        </tr>
        </table>
    </div>
<script type="text/javascript">

    $(document).ready(function(){
        detenerBitacoraNM();
        detenerMuralDisenoNM();
        

    });

</script>