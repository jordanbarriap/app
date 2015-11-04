<?php
/**
 * Inserta una recomendacion y evaluación ingresada al finalizar una actividad,
 * o actualiza una recomendación y evaluación dejada anteriormente.
 *
 *  El script recibe:
 *  codact:             codigo (id) de la actividad.
 *  codexp:             codigo (id) de la experiencia.
 *  klwn_id_usuario:    codigo (id) del usuario que dejó la recomendación y/o evaluación.
 *  id_exp_act:         codigo (id) de experiencia-actividad.
 *  rec_chkbox:         valor de checkbox de evaluación (Muy Bien, Bien, Mal, Muy Mal)
 *  rec_txt_nueva_reco: mensaje (recomendación) ingresada.
 
 *
 * Se utilizan las siguientes funciones:
 * dbExpObtenerIdDiseno: Obtiene el id del diseño para almacenar la recomendacion en la BD.
 * dbRECBuscaRecomendacionAnterior: Obtiene la recomendacion almacenada anteriormente y el valor
 *                                  de la evaluacion ingresada.
 * dbMuralDisenoInsertarMensaje: Almacena la recomendacion (mensaje) en tabla md_mensajes.
 * dbRECInsertarEvaluacionActividad: Almacena la evaluación en la tabla rec_evaluacion_actividad.
 * dbRECActualizaRecomendacionFinal: Actualiza los registros de las tablas md_mensajes y 
 *                                   rec_evaluacion_actividad que contienen la recomendacion y evaluacion
 *                                   ingresada anteriormente.
 *
 * Se realiza busqueda de comentario anterior:
 *      - Si no existe, se almacena en md_mensajes y rec_evaluacion_actividad.
 *      - Si existe, se actualizan ambas tablas.
 *
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Cristian Miranda - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 */
$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");

if(existeSesion()){
    /*
     * Se capturan los valores desde exp_lista_etapas.php al presionar el
     * boton Finalizar en ventana pop-up.
     */
    $id_actividad   = $_REQUEST["codact"];
    $id_experiencia = $_REQUEST["codexp"];
    $id_usuario     = $_REQUEST["id_usuario"];
    $id_expact      = $_REQUEST["id_exp_act"];
    $evaluacion     = $_REQUEST["rec_chkbox"]; //es la evaluacion de la actividad
    $mensaje        = $_REQUEST["rec_txt_nueva_reco"]; //es la recomendacion ingresada en textarea
    $tipo           = '6';
    
    // Conexion a BD
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
    // Obtengo el id_diseno para almacenar la recomendacion y evaluacion en la BD
    $id_diseno  = dbExpObtenerIdDiseno($id_experiencia, $conexion);
    /* Se realiza busqueda de comentario tipo 6 y valor de evaluacion en tabla rec_evaluacion_actividad
     * Si existe comentario anterior, retorna el comentario y el valor de la evaluacion,
     * Si no existe, retorna -1 */
    $comentario_anterior = dbRECBuscaRecomendacionAnterior($id_actividad, $id_experiencia, $id_usuario, $conexion);
    
    if($comentario_anterior == null){ // No existe comentario anterior
        // Inserta comentario en BD en tabla md_mensaje y funcion retorna el id del mensaje comentario insertado.
        $id_mensaje = dbMuralDisenoInsertarMensaje($id_diseno, $id_experiencia, $id_usuario, $mensaje, $tipo, $id_actividad, $conexion);
        // Inserta la evaluacion en tabla rec_evaluacion_activdad
        $x = dbRECInsertarEvaluacionActividad($id_actividad, $id_experiencia, $id_usuario, $id_mensaje, $id_diseno, $id_expact, $evaluacion, $mensaje, $conexion);
    }
    else{ // Existe comentario anterior
        // Actualiza registro en tablas md_mensajes y rec_evaluacion_actividad
        $y = dbRECActualizaRecomendacionFinal($id_actividad, $id_experiencia, $id_usuario, $evaluacion, $mensaje, $conexion);
    }
    dbDesconectarMySQL($conexion);
}
else{
    echo "-1";
}



echo "exp: ".$id_experiencia." ";
echo "user: ".$id_usuario." ";
echo "mens: ".$mensaje." ";
echo "act: ".$id_actividad." ";
echo "diseno: ".$id_diseno." ";
echo "evaluacion: ".$evaluacion." ";
echo "id_mensaje: ".$id_mensaje." ";
echo "largo: ".strlen($mensaje)." ";
echo "id_exp_act: ".$id_expact." ";
echo "comentario anterior: ".$comentario_anterior;
?>
