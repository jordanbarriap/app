<?php
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz.  "admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz.  "admin/inc/admin_functions.inc.php");
require_once($ruta_raiz.  "encuestas/inc/en_db_functions.inc.php");
require_once($ruta_raiz.  "encuestas/inc/en_functions.inc.php");

$bandera_tipo = $_REQUEST['bandera'];
//obtenemos encuestas de la plataforma
$conexion_ls = dbConectarMySQL($config_host_bd, $config_usuario_bd_ls, $config_password_bd_ls,$config_bd_ls);
$a_encuestas = dbENObtenerEncuestas($bandera_tipo, $conexion_ls);
?>

<table class =" admin_tabla_encuestas admin_alinear_centro">
    <tr class="admin_border_cabecera">
        <td class="admin_c_id admin_border_cabecera">
            ID LS
        </td>
        <td class="admin_c_nombre admin_border_cabecera ">
            <?php echo $lang_tabla_enc_nombre; ?>
        </td>
        <td class="admin_c_encuestados admin_border_cabecera">
            <?php echo $lang_tabla_enc_encuestados; ?>
        </td>
        <td class="admin_c_finicio admin_border_cabecera">
            <?php echo $lang_tabla_enc_semestre; ?>
        </td>
        <td class="admin_c_ffinal admin_border_cabecera">
            <?php echo $lang_tabla_enc_fechas; ?>
        </td>
    </tr>
    <?php
    if($a_encuestas){
        foreach($a_encuestas as $key => $encuesta){
            $info_encuesta = dbENEncuestaInfo($encuesta['id_encuesta'], $conexion_ls);
            if($a_encuesta[$key+1]) $clase_borde = 'admin_border_cabecera';
            
            //para el caso de la tabla de encuestas cerradas ponemos prefijo para avisar qu esigan activas
            if($bandera_tipo == 0){
                if($info_encuesta['activa'] == 'Y') $activa = '[A]';
                else $activa =  '';
            }
        ?>
        <tr>
            <td class="admin_c_id <?php echo $clase_borde?>">
                <?php echo $activa.$encuesta['id_encuesta']?>
            </td>
            <td class="admin_c_nombre <?php echo $clase_borde?>">
                <u><a id="link_encuesta_<?php echo $encuesta['id_encuesta']?>" href="#"><?php echo $encuesta['nombre_encuesta']?></a></u>
            </td>
            <td class="admin_c_encuestados <?php echo $clase_borde?>" align="center">
                <?php
                    $periodo_semestre = $lang_he_sin_asignar;
                    //damos formato a valores de encuestados, poniendo letra inicial de cada grupo encuestado
                    if($info_encuesta['encuestados']){
                        $encuestados = str_replace('1', 'P', $info_encuesta['encuestados']);
                        $encuestados = str_replace('2', 'A', $encuestados);
                        $encuestados = str_replace('3','C',$encuestados);

                        //bloque donde se da formato y junta informacion de los encuestados
                        //damos formato al semestre
                        $periodo_semestre =  enformatoSemestre($info_encuesta['encuestados_semestre']);
                        //anio
                        if($info_encuesta['encuestados_anio'] == $info_encuesta['encuestados_anio1']){
                            $periodo_anio = $info_encuesta['encuestados_anio'];
                        }
                        else{
                            $periodo_anio = $info_encuesta['encuestados_anio'].' hasta '.$info_encuesta['encuestados_anio1'];
                        }
                        //echo $encuestados.'</br>'.$periodo_anio.'</br>'.$periodo_semestre;
                        ?>
                        <table class="admin_tabla_encuestas">
                            <tr align="center"><?php echo $encuestados?></tr>
                            <tr align="center"><?php echo $periodo_anio?></tr>
                        </table>
                <?php
                    }
                    else
                    {
                        $encuestados = $lang_he_sin_asignar;
                        echo $encuestados;
                    }
                ?>
            </td>
            <td class="admin_c_finicio <?php echo $clase_borde?>">
                <?php echo $periodo_semestre?>
            </td>
            <td align="center" class="admin_c_ffinal <?php echo $clase_borde?>">
                <table class="admin_tabla_encuestas">
                    <tr><?php echo enformatoFechaDia($info_encuesta['fecha_comienzo'])?> a </tr>
                   <tr><?php  echo enformatoFechaDia($info_encuesta['fecha_expira'])?></tr>
                </table>
            </td>
        </tr>
        <?php
        }
        dbDesconectarMySQL($conexion_ls);
    }
    else{
        ?>
        <tr>
            <td colspan =" 4"><center><?php echo $lang_he_no_existen_encuestas_tabla; ?></center></td>
        </tr>
        <?php
    }
    ?>
</table>

<script type="text/javascript">

     //generar links para cada encuesta de la tabla
       <?php
       if($a_encuestas){
           foreach($a_encuestas as $encuesta){
           ?>
              $('#link_encuesta_<?php echo $encuesta['id_encuesta']?>').click(function(){
               url = '../limesurvey/admin/admin.php?sid=<?php echo $encuesta['id_encuesta']?>';
               window.open(url);
               return false;
           });
           <?php
           }
       }
       ?>
</script>