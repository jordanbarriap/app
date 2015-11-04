<?php
/**
 * Inicia la ejecución de una actividad utilizando la función 
 * dbIniciarActividad($id_actividad, $id_exp_etapa, $etiqueta_actividad, $conexion)
 * Para ello requiere el código de la actividad $echo "EQUEST["codact"], ";
 * el código de la etapa asociada a la experiencia didáctica $_REQUEST["codexpetapa"]
 * y la etiqueta asociada a la actividad $_REQUEST["etiquetaact"]
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
require_once($ruta_raiz."revpares/inc/rp_db_functions.inc.php");
require_once($ruta_raiz."revpares/rpVinculacionPares2.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
// Require Portafolio
require_once($ruta_raiz."portafolio/inc/por_funciones_db.inc.php");
require_once($ruta_raiz."portafolio/vinculacion.php");

$id_actividad = $_REQUEST["codact"];
$id_exp_act = $_REQUEST["codexpact"];
$id_exp_etapa = $_REQUEST["codexpetapa"];
$etiqueta_actividad = $_REQUEST["etiquetaact"];
$id_experiencia = $_REQUEST["codeexp"];
$nombre_actividad = $_REQUEST["nombre_actividad"];
$id_usuario = $_SESSION["klwn_id_usuario"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$a_datos_actividad = dbExpObtenerActividad($id_actividad,$conexion);

//vinculación
if($a_datos_actividad['revisa_pares'] == 1){
    //echo "Es actividad de revision";
    //motorVinculacion2($id_experiencia,$id_actividad,$a_datos_actividad,$conexion); 
    crearVinculos($id_experiencia, $id_actividad, $conexion);  
}

if (is_null($id_actividad) or strlen($id_actividad) == 0){
    $error = 1;
    $error_msg = $lang_error_sin_codigo_actividad;
}
else{
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    $exito = dbIniciarActividad($id_actividad,$id_exp_act,$id_exp_etapa, $etiqueta_actividad, $conexion);
    $id_diseno = dbExpObtenerIdDiseno($id_experiencia, $conexion);    
    $iniciadora = dbVerificarActividadIniciadora($id_exp_act, $conexion);
    $mensaje = $nombre_actividad;
    $tipo = 4;
    $n = dbMuralDisenoInsertarMensaje($id_diseno, $id_experiencia, $id_usuario, $mensaje, $tipo, $id_actividad,$conexion);
    if($iniciadora["iniciadora"] == 1){
        $detalle_dd =dbObtenerDetalleDDidacticos($conexion, $id_diseno);
        $nombre_dd = $detalle_dd["nombre_dd"];
        $mensaje = $nombre_dd;
        $inicio_experiencia = dbIniciarEjecucionDD($id_experiencia, $conexion);
        $tipo = 2;       
        $n = dbMuralDisenoInsertarMensaje($id_diseno, $id_experiencia, $id_usuario, $mensaje, $tipo,-1, $conexion);
    }
    dbDesconectarMySQL($conexion);
    
    if ($exito == -1){
        $error = 2;
        $error_msg = $lang_error_insertar_exp_act;
    }
    elseif($exito == 0){
        
        
    }else{
        
    }
}
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
//Verificar si existe algún tipo de evaluación a realizar en esta actividad e inicializar las evaluaciones.
//con la coevaluación individual grupo a grupo la inicializacipon se hace en otra actividad. Es la excepción.
$existe_autoev = dbPExisteTipoEvaluacion($id_actividad, 1, $conexion); //Saber si la actividad tiene autoevaluacion. Lo mismo con las otras actividades.
$existe_coevgrupal = dbPExisteTipoEvaluacion($id_actividad, 2, $conexion);
$existe_heteroev = dbPExisteTipoEvaluacion($id_actividad, 4, $conexion);
$existe_ecoev = dbPExisteTipoEvaluacion($id_actividad, 5, $conexion);

if($existe_autoev){
    $id_evaluacion = dbPObtenerIdEvaluacion($id_actividad,1,$conexion);
    $idestudiantes_exp = dbPObtenerIdEstudiantesExp($id_experiencia,$conexion); 
    dbPInicializarAutoev($idestudiantes_exp, $id_actividad, $id_experiencia, $id_evaluacion, $conexion);
}

if($existe_coevgrupal){
    $id_evaluacion = dbPObtenerIdEvaluacion($id_actividad,2,$conexion);
    $idestudiantes_exp = dbPObtenerIdEstudiantesExp($id_experiencia,$conexion);
    for($i=0;$i<count($idestudiantes_exp);$i++){
        $id_grupo = null;
        $id_grupo = dbPObtenerIdGrupo($idestudiantes_exp[$i], $id_experiencia, $conexion);
        dbPInicializarCoevGrupal($idestudiantes_exp[$i], $id_grupo, $id_actividad,$id_experiencia, $id_evaluacion, $conexion);
    }
}

if($existe_heteroev){
    $id_grupos = dbPObtenerIdGruposMiExp($id_experiencia, $conexion);
    $id_evaluacion = dbPObtenerIdEvaluacion($id_actividad,4,$conexion);
    dbPInicializarHeteroev($id_usuario, $id_grupos, $id_experiencia, $id_actividad, $id_evaluacion, $conexion);
}

if($existe_ecoev){
    $id_estudiantes = dbPObtenerIdEstudiantesExp($id_experiencia,$conexion);
    $id_evaluacion = dbPObtenerIdEvaluacion($id_actividad,5,$conexion);
    dbPInicializarEcoev($id_estudiantes, $id_experiencia, $id_actividad, $id_evaluacion, $conexion );

} 


echo $exito;
?>
