<?php
/**
*Carga de menu experiencia para herrmaienta de trabajos del alumno.
*
* LICENSE: código fuente distribuido con licencia LGPL
*
* @author  Sergio Bustamante M. - Kelluwen
* @copyleft Kelluwen, Universidad Austral de Chile
* @license www.kelluwen.cl/app/licencia_kelluwen.txt
* @version 0.1
*
**/
$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz.  "encuestas/inc/en_functions.inc.php");
require_once($ruta_raiz.  "encuestas/inc/en_db_functions.inc.php");

$conexion_ls = dbConectarMySQL($config_host_bd, $config_usuario_bd_ls, $config_password_bd_ls,$config_bd_ls);

$id_usuario = $_REQUEST['id_usuario'];

//obtenemos las encuestas no repsondidas por el usuario del usuario
$a_encuestas = dbENEncuestasPorUsuario($id_usuario,1,$conexion_ls);
dbDesconectarMySQL($conexion_ls);

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);

//consultamos por el usuario para obtener su contrasenia
$datos_usuario = dbENInfoUsuario($id_usuario, $conexion);


$usuario = str_replace('.','_',$datos_usuario['usuario']);
dbDesconectarMySQL($conexion);
?>

<ul>
    <?php
    if($a_encuestas){
        foreach($a_encuestas as $encuesta){
            ?>
            <li>
                 
                 <a href="../limesurvey/index.php?token=<?php echo $id_usuario.$usuario;?>&sid=<?php echo $encuesta['id']?>&lang=es" target="_blank"><?php echo $encuesta['nombre']?></a>
            </li>
            <?php
        }
    }
    else{
    ?>
        <li>
            <?php echo $lang_he_no_hay_encuestas_por_responder;?>
        </li>
    <?php
    }
    ?>
</ul>

