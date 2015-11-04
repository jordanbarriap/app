<?php
$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."revpares/inc/rp_db_functions.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");
require_once($ruta_raiz."reco/inc/rec_functions.inc.php");


// ACTUALIZA LA COLUMNA REC_NPROMEDIO_ALUMNOS (numero promedio de alumnos en aula)
// Obtiene todos los id de la tabla rec_profesores
$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
$_profesores = dbRECObtenerProfesores($conexion);
$i=0;
while(!is_null($_profesores[$i])){
// Relleno de columna rec_npromedio_alumnos
$id_profesor = $_profesores[$i]["id_profesor"];
$num_experiencias = dbRECObtieneNumeroExperiencias($id_profesor, $conexion);
$exp_prof = dbRECObtieneExperienciasProfesor($id_profesor, $conexion);
$k=0;

$nexp_ejec = dbRECNumExpEjecutadas($id_profesor,$conexion);

$total_alumnos=0;
while(!is_null($exp_prof[$k])){
$cont = dbRECObtieneNumeroAlumnos($exp_prof[$k]["id_experiencia"], $conexion);
$total_alumnos = $total_alumnos + $cont;
$k++;
}
if($num_experiencias != 0){
$promedio_alumnos = $total_alumnos/$num_experiencias;
}
else{
$promedio_alumnos = 0;
}
echo "ID_profesor: ".$id_profesor." ";
echo "Numero_Experiencias(participa): ".$num_experiencias." ";
echo "Total_Alumnos: ".$total_alumnos." ";
echo "Promedio_Alumnos: ".$promedio_alumnos."<br>";
echo "Exp_eje(finalizadas): ".$nexp_ejec."<br>";
$x = dbRECActualizaNumeroPromedioAlumnos($id_profesor, $promedio_alumnos, $conexion);
$i++;
}
?>
