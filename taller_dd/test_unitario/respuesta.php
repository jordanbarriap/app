<?php

    $ruta_raiz = "./../../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");


    //print_r($_POST);    
    
    $textCode = "$"."result = ".$_POST['input_function']."( ";
    
    foreach ($_POST as $clave => $valor) {
        if($clave != 'input_function' && $valor != 'conexion' ){
            if($valor == '') $valor = '""';
            $textCode .= $valor.', ';
        }
        if($valor == 'conexion' ){
            $textCode .= '$'.$valor.' ';
        }
    }
    $textCode .= ' ); ';
    $texCode2 = 'print_r($result);';
    
    echo $_POST['input_function']."<br>".$textCode."<br>".$texCode2."<br>";

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
        eval($textCode.$texCode2);
    dbDesconectarMySQL($conexion);
?>
