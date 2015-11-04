<?php
header('Content-type: text/html; charset=UTF-8');

$ruta_raiz = "./../";
//require_once './lib/PHPWord.php';
require_once($ruta_raiz . "taller_dd/lib/PHPWord.php");
require_once($ruta_raiz . "taller_dd/lib/simplehtmldom/simple_html_dom.php");
require_once($ruta_raiz . "taller_dd/lib/htmlconverter/h2d_htmlconverter.php");
require_once($ruta_raiz . "taller_dd/lib/htmlconverter/styles.inc");
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz . "inc/db_functions.inc.php");
require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

function replaceHtml($insertInto, $text, $style = null){
    // HTML Dom object:
    $html_dom = new simple_html_dom();
    $html_dom->load('<html><body>' . $text . '</body></html>');
    // Note, we needed to nest the html in a couple of dummy elements

    // Create the dom array of elements which we are going to work on:
    $html_dom_array = $html_dom->find('html',0)->children();

    // Provide some initial settings:
    if($style == null){
        $style =  array('size' => '10');
    }
    $initial_state = array(
          'current_style' => $style,
          'style_sheet' => h2d_styles(), // This is an array (the "style sheet") - returned by h2d_styles_Example() here (in styles.inc) - see this function for an example of how to construct this array.
          'parents' => array(0 => 'body'), // Our parent is body
          'list_depth' => 0, // This is the current depth of any current list
          'context' => 'section', // Possible values - section, footer or header
          'base_root' => 'http://test.local', // Required for link elements - change it to your domain
          'base_path' => '/', // Path from base_root to whatever url your links are relative to
          'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present)
          'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font
          'pseudo_list_indicator_font_size' => '7', // Bullet indicator size
          'pseudo_list_indicator_character' => 'l ', // Gives a circle bullet point with wingdings
          );    

    // Convert the HTML and put it into the PHPWord object
    h2d_insert_html($insertInto, $html_dom_array[0]->nodes, $initial_state);    
    
}
 function cText($texto){
      $texto = html_entity_decode($texto, ENT_NOQUOTES, 'UTF-8'); // Convertir caracteres especiales a entidades
      return $texto;
  }
$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

$idDiseno=-1;
$fcd_nombre= '';
$fcd_sector= -1;
$fcd_nivel= -1;
$fcd_descripcion= '';
$fcd_objetivos_curriculares= '';
$fcd_objetivos_transversales= '';
$fcd_contenidos= '';
$fcd_descripcion_etapa1= '';
$fcd_descripcion_etapa2= '';
$fcd_descripcion_etapa3= '';
$fcd_web_20= -1;
$fcd_id_autor= $_SESSION["klwn_id_usuario"];

$_herramientas_web = obtenerHerramientasWebFuncion($conexion);

$h_web = array();
for($i=0; $i< count($_herramientas_web); $i++){
    $h_web[$_herramientas_web[$i]['hw_id_herramienta']] = $_herramientas_web[$i]['hw_enlace'];  
}

$_etapas = array();
$_actividades_etapa1 = array();
$_actividades_etapa2 = array();
$_actividades_etapa3 = array();

if(isset($_GET['idDiseno'])){
    $idDiseno                       = $_GET['idDiseno'];
    $_diseno= obtenerDisenoFuncion($idDiseno, $conexion);

    $fcd_id_autor                   = $_diseno[0]['dd_id_autor'];
    $fcd_nombre                     = $_diseno[0]['dd_nombre'];
    $fcd_sector                     = $_diseno[0]['dd_subsector'];
    $fcd_nivel                      = $_diseno[0]['dd_nivel'];
    $fcd_descripcion                = $_diseno[0]['dd_descripcion'];
    $fcd_objetivos_curriculares     = $_diseno[0]['dd_objetivos_curriculares'];
    $fcd_objetivos_transversales    = $_diseno[0]['dd_objetivos_transversales'];
    $fcd_contenidos                 = $_diseno[0]['dd_contenidos'];
    $fcd_descripcion_etapa1         = $_diseno[0]['dd_descripcion_e1'];
    $fcd_descripcion_etapa2         = $_diseno[0]['dd_descripcion_e2'];
    $fcd_descripcion_etapa3         = $_diseno[0]['dd_descripcion_e3'];
    $fcd_web_20                     = $_diseno[0]['hd_id_herramienta'];
    
    $_etapas = obtenerEtapasFuncion($idDiseno, $conexion);
    $_actividades_etapa1 = obtenerActividadesPorEtapaFuncion($_etapas[0]['e_id_etapa'], $conexion);
    $_actividades_etapa2 = obtenerActividadesPorEtapaFuncion($_etapas[1]['e_id_etapa'], $conexion);
    $_actividades_etapa3 = obtenerActividadesPorEtapaFuncion($_etapas[2]['e_id_etapa'], $conexion);
    
    $totalEtapa1 = count($_actividades_etapa1);
    $totalEtapa2 = count($_actividades_etapa2);
    $totalEtapa3 = count($_actividades_etapa3);
    $maxActividades = $totalEtapa1;
    if($totalEtapa2 > $maxActividades) $maxActividades = $totalEtapa2;
    if($totalEtapa3 > $maxActividades) $maxActividades = $totalEtapa3;
    

    // New Word Document
    $PHPWord = new PHPWord();

    $properties = $PHPWord->getProperties();
    $properties->setCreator('Kelluwen'); 
    $properties->setCompany('Kelluwen');


    $_estilo_titulo = array('name'=>'Verdana', 'color'=>'000000');
    $_estilo_titulo_form_diseno=  array('bold'=>true);
    $_estilo_titulo_actividad=  array('bold'=>true);
    // New portrait section
    $section = $PHPWord->createSection();
    $sectionStyle = $section->getSettings();

    // Add header
    $header = $section->createHeader();

    $styleTable = array('borderColor'=>'006699', 'borderSize'=>2);
    $styleFirstRow = array('bgColor'=>'66BBFF');
    $PHPWord->addTableStyle('myTable', $styleTable, $styleFirstRow);

    $table = $header->addTable();
    $table->addRow();
    //$table->addCell(1510)->addImage('./../img/logo.gif', array('width'=>151, 'height'=>43, 'align'=>'left'));
    replaceHtml($table->addCell(1510), '<img src="./../img/logo.jpg">');
//    $table->addCell(4500)->addText('Proyecto FONDEF D08i-1074', array('size'=>12), array('align'=>'right'));
//    $table->addCell(4500)->addText('www.kelluwen.cl', array('size'=>10), array('align'=>'right'));
    $table->addCell(2500)->addText('');
    $text = "<p><b><".$lang_crear_diseno_word_proyecto."</b></p><p>".$lang_crear_diseno_url_kelluwen."</p>";
    replaceHtml($table->addCell(3700), $text, array('align'=>'right', 'border'=>false,'spaceAfter'=>10));

    $PHPWord->addFontStyle('tamano_titulo', array('bold'=>true, 'italic'=>false, 'size'=>12));
    $PHPWord->addParagraphStyle('parrafo_titulo', array('align'=>'center'));
    $section->addTextBreak(1);

    $section->addText($lang_crear_diseno_word_estructura.": ".$fcd_nombre, 'tamano_titulo', 'pStyle');

//    $section->addTextBreak(1);

    /*TABLA CON EL RESUMEN DE LAS ACTIVIDADES*/

    /*Estilo tabla de resumen de actividades*/
    $styleTableDiseno = array('borderSize'=>6, 'borderColor'=>'000000', 'cellMargin'=>80);
    $styleFirstRowTableDiseno = array('bgColor'=>'D9D9D9');

    /*Estilo celda nombre actividad*/
    $styleCelda= array('bold'=>true);

    $PHPWord->addTableStyle('StyleTableDiseno', $styleTableDiseno, $styleFirstRowTableDiseno);

    $tableDiseno = $section->addTable('StyleTableDiseno');
    $tableDiseno->addRow(400);
    $tableDiseno->addCell(2890)->addText($lang_crear_diseno_word_etapa.' 1', array('bold'=>true, 'italic'=>false, 'size'=>11));
    $tableDiseno->addCell(2890)->addText($lang_crear_diseno_word_etapa.' 2', array('bold'=>true, 'italic'=>false, 'size'=>11));
    $tableDiseno->addCell(2890)->addText($lang_crear_diseno_word_etapa.' 3', array('bold'=>true, 'italic'=>false, 'size'=>11));
    for($i=0; $i<$maxActividades; $i++){
        $tableDiseno->addRow(400);
            $text='';
            if($i< $totalEtapa1){
                $text = $lang_crear_diseno_word_actividad.' '.($i+1);
                if($_actividades_etapa1[$i]['ac_tipo']==$actividad_laboratorio){
                    $text .= " (".$lang_crear_diseno_word_laboratorio.")";
                }else{
                    $text .= " (".$lang_crear_diseno_word_sala.")";
                }
            }            
            $tableDiseno->addCell(2890)->addText($text,$styleCelda);

            $text='';
            if($i< $totalEtapa2){        
                $text = $lang_crear_diseno_word_actividad.' '.($i+1);
                if($_actividades_etapa2[$i]['ac_tipo']==$actividad_laboratorio){
                    $text .= " (".$lang_crear_diseno_word_laboratorio.")";
                }else{
                    $text .= " (".$lang_crear_diseno_word_sala.")";
                }
            }
            $tableDiseno->addCell(2890)->addText($text,$styleCelda);

            $text='';
            if($i< $totalEtapa3){        
                $text = $lang_crear_diseno_word_actividad.' '.($i+1);
                if($_actividades_etapa3[$i]['ac_tipo']==$actividad_laboratorio){
                    $text .= " (".$lang_crear_diseno_word_laboratorio.")";
                }else{
                    $text .= " (".$lang_crear_diseno_word_sala.")";
                }
            }
            $tableDiseno->addCell(2890)->addText($text,$styleCelda);


        $tableDiseno->addRow(1000);
            $text = '';
            if($i< $totalEtapa1){$text = $_actividades_etapa1[$i]['ac_descripcion'];}
            replaceHtml($tableDiseno->addCell(2890), $text);

            $text = '';
            if($i< $totalEtapa2){$text = $_actividades_etapa2[$i]['ac_descripcion'];}
            replaceHtml($tableDiseno->addCell(2890), $text);

            $text = '';
            if($i< $totalEtapa3){$text = $_actividades_etapa3[$i]['ac_descripcion'];}
            replaceHtml($tableDiseno->addCell(2890), $text);
    }
    /*FIN TABLA CON EL RESUMEN DE LAS ACTIVIDADES*/

    $section->addTextBreak(2);

    /*TABLA FORMULARIO DE DISENO DIDACTICO*/
    $styleTableFormularioDiseno = array('borderSize'=>6, 'borderColor'=>'000000', 'cellMargin'=>80);
    $styleTableFormularioDisenoCelda= array('bgColor'=>'D9D9D9');

    $PHPWord->addTableStyle('StyleTableFormularioDiseno',$styleTableFormularioDiseno);

    $section->addText($lang_crear_diseno_word_form,array('bold'=>true, 'italic'=>false, 'size'=>12));

    $tableFormularioDiseno = $section->addTable('StyleTableFormularioDiseno');

    $anchoEtiqueta = 2500;
    $anchoContenido =6170;
    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_nombre),$styleCelda);
    $tableFormularioDiseno->addCell($anchoContenido)->addText($fcd_nombre);

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_sector),$styleCelda);
    for ($i = 0; $i < count($_sectores); $i++) { if (strcmp($_sectores[$i]['valor'], $fcd_sector) == 0) {
            $tableFormularioDiseno->addCell($anchoContenido)->addText($_sectores[$i]['nombre']);
        }
    }

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_nivel),$styleCelda);
    for ($i = 0; $i < count($_niveles); $i++) { if (strcmp($_niveles[$i], $fcd_nivel) == 0) {
            $tableFormularioDiseno->addCell($anchoContenido)->addText($_niveles[$i]);
        }
    }

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_descripcion),$styleCelda);
    replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $fcd_descripcion);

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_obj_curriculares),$styleCelda);
    replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $fcd_objetivos_curriculares);
    
    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_obj_transversales),$styleCelda);
    replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $fcd_objetivos_transversales);
    

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_contenidos),$styleCelda);
    replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $fcd_contenidos);

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_desc_etapa1),$styleCelda);
    replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $fcd_descripcion_etapa1);

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_desc_etapa2),$styleCelda);
    replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $fcd_descripcion_etapa2);

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_desc_etapa3),$styleCelda);
    replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $fcd_descripcion_etapa3);

    $tableFormularioDiseno->addRow(400);
    $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda)->addText(cText($lang_crear_nuevo_diseno_web_20),$styleCelda);
    for ($i = 0; $i < count($_herramientas_web); $i++) { if ($_herramientas_web[$i]['hw_id_herramienta'] == $fcd_web_20) {
            $tableFormularioDiseno->addCell($anchoContenido)->addText($_herramientas_web[$i]['hw_nombre']);
        }
    }
    /*FIN TABLA FORMULARIO DE DISENO DIDACTICO*/


    $section->addTextBreak(1);

    /*TABLAS FORMULARIO ACTIVIDADES*/
    for($j=0; $j<3; $j++){
        if($j==0){$_actividades = $_actividades_etapa1; $baseNumero=1;}
        if($j==1){$_actividades = $_actividades_etapa2; $baseNumero=1;}
        if($j==2){$_actividades = $_actividades_etapa3; $baseNumero=1;}

        for($i=0; $i<count($_actividades); $i++){
            $section->addTextBreak(2);

            $styleTableFormularioDiseno2 = array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80);
            $styleTableFormularioDisenoCelda2 = array('bgColor' => 'D9D9D9');

            $PHPWord->addTableStyle('StyleTableFormularioDiseno2', $styleTableFormularioDiseno);
            $tableFormularioDiseno = $section->addTable('StyleTableFormularioDiseno2');
            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell(3000)->addText(cText($lang_nueva_actividad_etapa." ".($j+1)." - ".$lang_crear_diseno_word_actividad." ".($baseNumero+$i) ), $_estilo_titulo_actividad);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_nombre),$styleCelda);
            $tableFormularioDiseno->addCell($anchoContenido)->addText($_actividades[$i]['ac_nombre']);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_aprendizaje_esperado),$styleCelda);
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $_actividades[$i]['ac_aprendizaje_esperado']);
            
            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_evidencia_aprendizaje),$styleCelda);
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $_actividades[$i]['ac_evidencia_aprendizaje']);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_descripcion_general),$styleCelda);
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $_actividades[$i]['ac_descripcion']);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_tipo_lugar),$styleCelda);
            for($z=0; $z<count($_act_tipo); $z++){ if($_act_tipo[$z]['valor'] == $_actividades[$i]['ac_tipo']){
                    $tableFormularioDiseno->addCell($anchoContenido)->addText($_act_tipo[$z]['nombre']);
                }
            }       

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_materiales),$styleCelda);
            $textMateriales = '';
            $_materiales = obtenerArchivosFuncion($_actividades[$i]['ac_id_actividad'], $conexion);
            if(count($_materiales) > 0 ){
                for($t=0; $t < count($_materiales); $t++){
                    $textMateriales .= $_materiales[$t]['a_nombre_archivo'].'<br>';
                }
                replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $textMateriales);
            }else{
                $tableFormularioDiseno->addCell($anchoContenido)->addText(cText($lang_nueva_actividad_no_materiales));
            }
            
            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_medios),$styleCelda);
            $texto = '';
            if($_actividades[$i]['ac_medios_bitacora']==1){$texto = $lang_nueva_actividad_bitacora.': '.$lang_crear_diseno_word_si;}
            else{$texto = $lang_nueva_actividad_bitacora.': '.$lang_crear_diseno_word_no;}

            for($z=0; $z<count($_act_medios_trabajos); $z++){ if($_act_medios_trabajos[$z]['valor'] == $_actividades[$i]['ac_medios_trabajos']){
                    $texto .= "<br> ".$lang_nueva_actividad_herramienta_trabajo.": ".$_act_medios_trabajos[$z]['nombre'];
                }
            }
            if($_actividades[$i]['ac_medios_web2']== 1){$texto .= "<br> ".$lang_crear_diseno_word_h_web20." : ".$lang_crear_diseno_word_si;}
            else{$texto .= "<br> ".$lang_crear_diseno_word_h_web20.": ".$lang_crear_diseno_word_no;}

            //Herramienta de trabajos: REVISIÖN
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $texto);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_inicio),$styleCelda);
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $_actividades[$i]['ac_instrucciones_inicio']);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_desarrollo),$styleCelda);
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $_actividades[$i]['ac_instrucciones_desarrollo']);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_cierre),$styleCelda);
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $_actividades[$i]['ac_instrucciones_cierre']);

            $tableFormularioDiseno->addRow(400);
            $tableFormularioDiseno->addCell($anchoEtiqueta,$styleTableFormularioDisenoCelda2)->addText(cText($lang_nueva_actividad_consejos_practicos),$styleCelda);
            replaceHtml($tableFormularioDiseno->addCell($anchoContenido), $_actividades[$i]['ac_consejos_practicos']);

        }
        $section->addTextBreak(1);

    }

    $footer = $section->createFooter();
    $footer->addPreserveText('{PAGE}');
    
    $nombre_temp = str_replace(" ", "_", $fcd_nombre);
    $nombre_temp = str_replace(array("á","é","í","ó","ú","Á","É","Í","Ó","Ú"), array("a","e","i","o","u","A","E","I","O","U"), $nombre_temp);
    $nombre_temp = preg_replace('/[^A-Za-z0-9_]/','',$nombre_temp);
    $nombreArchivo = $nombre_temp."_".date('dmY').".docx";    
   
    // Save File
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
    $objWriter->save('./data/'.$nombreArchivo);

    // Download the file:
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$nombreArchivo);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize('./data/'.$nombreArchivo));
    ob_clean();
    flush();
    $status = readfile('./data/'.$nombreArchivo);
    unlink('./data/'.$nombreArchivo);
    exit;    
    
    
    
}
?>