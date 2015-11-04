<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

$carpeta_subida_archivos = 'dd/actividades'; //partiendo desde app_dev/
$config_ruta_archivo_ejemplo = 'taller_dd/doc_ejemplo'; //partiendo desde app_dev/
$max_tamano = 5*1024*1024; //tamaño maximo para archivo en mb

$_niveles = array(
    0 => 'NB1 ('.$tdd_config_pri_seg_ano.')',
    1 => 'NB2 ('.$tdd_config_ter_cua_ano.')',
    2 => 'NB3 ('.$tdd_config_qui_ano.')',
    3 => 'NB4 ('.$tdd_config_sex_ano.')',
    4 => 'NB5 ('.$tdd_config_sep_ano.')',
    5 => 'NB6 ('.$tdd_config_oct_ano.')',
    6 => 'NM1 ('.$tdd_config_pri_ano_medio.')',
    7 => 'NM2 ('.$tdd_config_seg_ano_medio.')',
    8 => 'NM3 ('.$tdd_config_ter_ano_medio.')',
    9 => 'NM4 ('.$tdd_config_cua_ano_medio.')',
    10 => $tdd_config_pregado,
    11 => $tdd_config_postgrado
);

$_niveles_universitario = array(10,11);

$_sectores = array(
    0 => array('valor'=> 'SMT','nombre'=>$tdd_config_matematica),
    1 => array('valor'=> 'SLC','nombre'=>$tdd_config_leng_comunicacion),
    2 => array('valor'=> 'SHG','nombre'=>$tdd_config_hist_cs_sociales),
    3 => array('valor'=> 'SCS','nombre'=>$tdd_config_cs_naturales),
    4 => array('valor'=> 'SIE','nombre'=>$tdd_config_idioma_ingles),
    5 => array('valor'=> 'SD', 'nombre'=>$tdd_config_diplomado),
    6 => array('valor'=> 'ST', 'nombre'=>$tdd_config_tecnologia),
    7 => array('valor'=> 'SG', 'nombre'=>$tdd_config_otro)
);

$_act_tipo = array(
    0 => array('valor'=> 1, 'nombre'=>$lang_tdd_conf_sala),
    1 => array('valor'=> 2, 'nombre'=>$lang_tdd_conf_lab),
    2 => array('valor'=> 3, 'nombre'=>$lang_tdd_conf_terreno),
    3 => array('valor'=> 4, 'nombre'=>$tdd_config_trabajo_casa)
);
$actividad_laboratorio = 2;
$actividad_casa = 4;

$_act_medios_trabajos= array(
    0 => array('valor'=> 1, 'nombre'=>$lang_tdd_conf_no),
    1 => array('valor'=> 2, 'nombre'=>$lang_tdd_conf_publicacion),
    2 => array('valor'=> 3, 'nombre'=>$lang_tdd_conf_revision),
    3 => array('valor'=> 4, 'nombre'=>$tdd_config_otro)
);
$actividad_publicacion = 2;
$actividad_revision = 3;
$actividad_otros = 4;

$rutaObjCurriculares_SMT = 'http://localhost/app_aliwen/taller_dd/doc/sector_matematica_11012010.pdf';
$rutaObjCurriculares_SLC = 'http://localhost/app_aliwen/taller_dd/doc/sector_lenguaje_y_comunicacion_11012010.pdf';
$rutaObjCurriculares_SHG = 'http://localhost/app_aliwen/taller_dd/doc/sector_historia_geografia_y_ciencias_sociales_11012010.pdf';
$rutaObjCurriculares_SCS = 'http://localhost/app_aliwen/taller_dd/doc/sector_ciencias_naturales_11012010.pdf';
$rutaObjCurriculares_SIE=  'http://localhost/app_aliwen/taller_dd/doc/sector_ingles_11012010.pdf';
$rutaObjCurriculares_SG = '';

$_ta_diseno = array(
    0 => 'fcd_descripcion',
    1 => 'fcd_objetivos_curriculares',
    2 => 'fcd_objetivos_transversales',
    3 => 'fcd_contenidos',
    4 => 'fcd_descripcion_etapa1',
    5 => 'fcd_descripcion_etapa2',
    6 => 'fcd_descripcion_etapa3'
);
$_ta_actividad = array(
    0 => 'fca_aprendizaje_esperado',
    1 => 'fca_evidencia_aprendizaje',
    2 => 'fca_descripcion_general',
    3 => 'fca_medios_otros',
    4 => 'fca_inicio',
    5 => 'fca_desarrollo',
    6 => 'fca_cierre',
    7 => 'fca_consejos',
    8 => 'fca_medios'
);

$_ta_colaborador_color = array(
    0 => '#ff0000',
    1 => '#00ff00',
    2 => '#0000ff',
    3 => '#ffff00',
    4 => '#ff00ff',
    5 => '#00ffff',
    6 => '#990000',
    7 => '#009900',
    8 => '#000099',
    9 => '#999900',
    10 => '#990099',
    11 => '#009999',
    12 => '#ff3333',
    13 => '#33ff33',
    14 => '#3333ff',
    15 => '#ffff33',
    16 => '#ff33ff',
    17 => '#33ffff'
);

?>
