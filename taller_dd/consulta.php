<?php
    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
    
    function getArrayText($id_excluido, $etiqueta, $array){
        $temp = array();
        for($i=0; $i< count($array); $i++){
            if($array[$i][$etiqueta] != $id_excluido) $temp[] = $array[$i][$etiqueta];
        }
        return implode(",", $temp);
    }    
    function obtenerDisenos($conexion){
        $consulta = "SELECT `ed_id_experiencia` , `ed_id_diseno_didactico` ".
                    "FROM `experiencia_didactica` ".
                    "GROUP BY ed_id_diseno_didactico ";

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $_datos=array();
        if ($_resultado) {
            if (mysql_num_rows($_resultado) > 0) {
                while ($fila = mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $_datos[] = $fila;
                }
            }
        }
        return $_datos;    
    }    
    function obtenerExperienciasPorDiseno($id_diseno, $conexion){

        $consulta = "SELECT `ed_id_experiencia` ".
                    "FROM `experiencia_didactica` ".
                    "WHERE `ed_id_diseno_didactico` = ".$id_diseno." " ;
                    "ORDER BY ed_id_experiencia ASC " ;

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $_datos=array();
        if ($_resultado) {
            if (mysql_num_rows($_resultado) > 0) {
                while ($fila = mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $_datos[] = $fila;
                }
            }
        }
        return $_datos;    
    }
    function obtenerUsuariosExperiencia($id_experiencia, $conexion){

        $consulta = "SELECT `ue_id_usuario` ".
                    "FROM usuario_experiencia ".
                    "WHERE ue_rol_usuario = 2 ".  /*ROL 2 es estudiante*/
                    "AND ue_id_experiencia = ".$id_experiencia." " ;

        $_resultado = dbEjecutarConsulta($consulta, $conexion);

        $_datos=array();
        if ($_resultado) {
            if (mysql_num_rows($_resultado) > 0) {
                while ($fila = mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $_datos[] = $fila;
                }
            }
        }
        return $_datos;        
    }
    function obtenerComentarios($id_experiencia, $experiencias_distintas, $conexion){
       $consulta =  "SELECT count(*) as cant_coment ".
                    "FROM rp_producto, rp_comentario  ".
                    "WHERE rc_id_producto = rp_id_producto  ".
                        "AND rp_id_experiencia IN (".$experiencias_distintas.") ".
                        "AND rc_id_usuario IN (SELECT ue_id_usuario FROM usuario_experiencia WHERE ue_id_experiencia = ".$id_experiencia.") ";
               $_resultado = dbEjecutarConsulta($consulta, $conexion);
        $_datos=array();
        if ($_resultado) {
            if (mysql_num_rows($_resultado) > 0) {
                while ($fila = mysql_fetch_array($_resultado, MYSQL_ASSOC)) {
                    $_datos[] = $fila;
                }
            }
        }
        return $_datos; 
        
    }
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $_disenos = obtenerDisenos($conexion);
        
    $_experiencias = array();
    for($i=0; $i< count($_disenos); $i++){
        $_experiencias[] = obtenerExperienciasPorDiseno($_disenos[$i]['ed_id_diseno_didactico'], $conexion);
    }

    $salida = array();
    $z = 0;
    for($i=0; $i< count($_experiencias); $i++){
        for($j=0; $j<count($_experiencias[$i]); $j++){
            $id_experiencia = $_experiencias[$i][$j]['ed_id_experiencia'];
            $experiencias_distintas = getArrayText($id_experiencia, 'ed_id_experiencia', $_experiencias[$i]);
            $cantidad_comentarios = obtenerComentarios($id_experiencia, $experiencias_distintas, $conexion);
            $salida[$z]['id_exp'] = $id_experiencia;
            $salida[$z]['cant_comentarios'] = $cantidad_comentarios[0]['cant_coment'];
            $z++;
        }
    }    
    print_r($salida);
    
    dbDesconectarMySQL($conexion);  

?>
