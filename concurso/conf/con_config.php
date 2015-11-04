<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

$carpeta_subida_archivos = 'dd/actividades'; //partiendo desde app_dev/
$max_tamano = 2*1024*1024; //tamaño maximo para archivo en mb

$_niveles = array(
    0 => $lang_concurso_config_nivel1,
    1 => $lang_concurso_config_nivel2,
    2 => $lang_concurso_config_nivel3,
    3 => $lang_concurso_config_nivel4
);

$_sectores = array(
    0 => array('valor'=> 'SLC','nombre'=>$lang_concurso_config_lenguaje_mayus),
    1 => array('valor'=> 'SHG','nombre'=>$lang_concurso_config_historia_mayus),
    2 => array('valor'=> 'SCS','nombre'=>$lang_concurso_config_ciencias_mayus),
    3 => array('valor'=> 'SG', 'nombre'=>$lang_concurso_config_general_mayus)
);

$_act_tipo = array(
    0 => array('valor'=> 1, 'nombre'=>$lang_concurso_config_sala),
    1 => array('valor'=> 2, 'nombre'=>$lang_concurso_config_lab),
    2 => array('valor'=> 3, 'nombre'=>$lang_concurso_config_terreno)
);
$actividad_laboratorio = 2;

$_act_medios_trabajos= array(
    0 => array('valor'=> 1, 'nombre'=>$lang_concurso_config_no),
    1 => array('valor'=> 2, 'nombre'=>$lang_concurso_config_publicacion),
    2 => array('valor'=> 3, 'nombre'=>$lang_concurso_config_revision)
);
$actividad_publicacion = 2;
$actividad_revision = 3;

$rutaObjCurriculares_SLC = 'http://localhost/app_dev/taller_dd/doc/sector_lenguaje_y_comunicacion_11012010.pdf';
$rutaObjCurriculares_SHG = 'http://localhost/app_dev/taller_dd/doc/sector_historia_geografia_y_ciencias_sociales_11012010.pdf';
$rutaObjCurriculares_SCS = 'http://localhost/app_dev/taller_dd/doc/sector_ciencias_naturales_11012010.pdf';
$rutaObjCurriculares_SG = '';


$ayudaConcurso = $lang_concurso_config_ayuda1."<br><br> -".$lang_concurso_config_ayuda2. 
                  "<br><br>".$lang_concurso_config_ayuda3.
                  "<br><br>".$lang_concurso_config_ayuda4;
?>
