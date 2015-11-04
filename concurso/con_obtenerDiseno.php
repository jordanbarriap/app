<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "concurso/inc/con_db_funciones.inc.php");
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

    $id_diseno   = $_GET["id_diseno"];
        
    $_diseno = obtenerDisenoFuncion($id_diseno, $conexion); 
  
    dbDesconectarMySQL($conexion);
    
    $separador= '$@%%@$';
    $texto = '';
    $texto .=  $_diseno[0]['dd_nombre'].$separador;           
    $texto .=  $_diseno[0]['dd_nivel'].$separador;           
    $texto .=  $_diseno[0]['dd_subsector'].$separador;           
    $texto .=  $_diseno[0]['dd_descripcion'].$separador;           
    $texto .=  $_diseno[0]['dd_objetivos_curriculares'].$separador;           
    $texto .=  $_diseno[0]['dd_objetivos_transversales'].$separador;           
    $texto .=  $_diseno[0]['dd_contenidos'].$separador;           
    $texto .=  $_diseno[0]['dd_descripcion_e1'].$separador;           
    $texto .=  $_diseno[0]['dd_descripcion_e2'].$separador;           
    $texto .=  $_diseno[0]['dd_descripcion_e3'].$separador;           
    $texto .=  $_diseno[0]['hd_id_herramienta'].$separador;           

    echo $texto;   

?>