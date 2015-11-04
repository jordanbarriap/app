<?php
  

  $ruta_raiz = "../../";
  require_once($ruta_raiz."conf/config.php");
  require_once($ruta_raiz."inc/all.inc.php");
  require_once($ruta_raiz."inc/db_functions.inc.php");

  class FrecuenciaPalabras{
    public $text;
    public $size;
  }

  function raw_json_encode($input) {

    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function ($matches) {
            return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
        },
        json_encode($input)
    );

}

  $id_experiencia = $_REQUEST['codexp'];
 
  $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
  
  $array_experiencias_gemelas = dbObtenerArrayExperienciasGemelas($id_experiencia , $conexion);

  $nro_exp_gemelas = count($array_experiencias_gemelas);

  $array_id_experiencias_gemelas = array();
  for ($i=0 ; $i< $nro_exp_gemelas ; $i++){
    array_push($array_id_experiencias_gemelas , $array_experiencias_gemelas[$i]['ed_id_experiencia']);
  }

  $array_mensajes_experiencias_gemelas = dbObtenerMensajesBitacoraExperiencias($array_id_experiencias_gemelas , $conexion);

  $string_mensajes_experiencias_gemelas = implode(' ',$array_mensajes_experiencias_gemelas);

  $ruta_archivo="historial_mensajes_exp".$id_experiencia.".txt";
  $archivo_mensajes = fopen($ruta_archivo, "w+") or die($lang_obtiene_frec_palabras_error);

  fwrite($archivo_mensajes,$string_mensajes_experiencias_gemelas);
  fclose($archivo_mensajes);

 
  $resultado=exec("python ".$config_ruta_servidor."dataviz/wordcloud/obtiene_frecuencia_palabras.py ".$ruta_archivo,$output);

  $array_frecuencia_palabras=array();
  for ($i=0;$i<sizeof($output);$i++){
    $palabra_frecuencia=$output[$i];
    //echo $palabra_frecuencia.'</br>';
    $array_palabra_frecuencia=explode(",",$palabra_frecuencia);
    $array_palabra_frecuencia=array_map(utf8_encode,$array_palabra_frecuencia);
    $palabra=$array_palabra_frecuencia[0];
    $frecuencia=$array_palabra_frecuencia[1];
    $objeto_json=new FrecuenciaPalabras();
    $objeto_json->text=$palabra;
    $objeto_json->size=$frecuencia;
    array_push($array_frecuencia_palabras, $objeto_json);
  }

  $json_data=array("frecuencia_palabras"=>$array_frecuencia_palabras);
  echo raw_json_encode($json_data);

?>