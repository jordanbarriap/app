<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    //Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");

    $idDiseno = $_POST['fcd_id_diseno'];

    $id_autor           = $_POST["fcd_id_autor"];
    $nombre             = ($_POST["fcd_nombre"]!=$ayuda['diseno']['fcd_nombre'])?$_POST["fcd_nombre"]:'';
    $sector             = $_POST["fcd_sector"];
    $nivel              = $_POST["fcd_nivel"];
    $descripcion        = ($_POST["fcd_descripcion"]!=$ayuda['diseno']['fcd_descripcion'])?$_POST["fcd_descripcion"]:'';
    $objCurriculares    = ($_POST["fcd_objetivos_curriculares"]!=$ayuda['diseno']['fcd_objetivos_curriculares'])?$_POST["fcd_objetivos_curriculares"]:'';
    $objTransversales   = ($_POST["fcd_objetivos_transversales"]!=$ayuda['diseno']['fcd_objetivos_transversales'])?$_POST["fcd_objetivos_transversales"]:'';
    $contenidos         = ($_POST["fcd_contenidos"]!=$ayuda['diseno']['fcd_contenidos'])?$_POST["fcd_contenidos"]:'';
    $descEtapa1         = ($_POST["fcd_descripcion_etapa1"]!=$ayuda['diseno']['fcd_descripcion_etapa1'])?$_POST["fcd_descripcion_etapa1"]:'';
    $descEtapa2         = ($_POST["fcd_descripcion_etapa2"]!=$ayuda['diseno']['fcd_descripcion_etapa2'])?$_POST["fcd_descripcion_etapa2"]:'';
    $descEtapa3         = ($_POST["fcd_descripcion_etapa3"]!=$ayuda['diseno']['fcd_descripcion_etapa3'])?$_POST["fcd_descripcion_etapa3"]:'';
    $web20              = $_POST["fcd_web_20"];
    $escala             = (isset($_POST["fcd_escala"]))?$_POST["fcd_escala"]:NULL;
    
$conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);    
    if($idDiseno >0){
        actualizarDisenoFuncion($id_autor, $nombre, $idDiseno, $sector, $nivel, $descripcion, $objCurriculares, $objTransversales, $contenidos, $descEtapa1, $descEtapa2, $descEtapa3, $web20, $conexion);
        echo $idDiseno;
    }else{
        crearDisenoFuncion($id_autor, $nombre, $sector, $nivel, $descripcion, $objCurriculares, $objTransversales, $contenidos, $descEtapa1, $descEtapa2, $descEtapa3, $web20, $escala, $conexion);
    }
dbDesconectarMySQL($conexion);
   
?>