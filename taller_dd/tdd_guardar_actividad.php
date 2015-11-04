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
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_mensajes_ayuda.php");


    $id_diseno              = $_POST['fca_id_diseno'];	
    $id_actividad           = $_POST['fca_id_actividad'];	
    $aprendizaje_esperado   = ($_POST["fca_aprendizaje_esperado"]!=$ayuda['actividad']['fca_aprendizaje_esperado'])?$_POST["fca_aprendizaje_esperado"]:'';
    $cierre                 = ($_POST["fca_cierre"]!=$ayuda['actividad']['fca_cierre'])?$_POST["fca_cierre"]:'';
    $desarrollo             = ($_POST["fca_desarrollo"]!=$ayuda['actividad']['fca_desarrollo'])?$_POST["fca_desarrollo"]:'';
    $descripcion_general    = ($_POST["fca_descripcion_general"]!=$ayuda['actividad']['fca_descripcion_general'])?$_POST["fca_descripcion_general"]:'';
    $evidencia_aprendizaje  = ($_POST["fca_evidencia_aprendizaje"]!=$ayuda['actividad']['fca_evidencia_aprendizaje'])?$_POST["fca_evidencia_aprendizaje"]:'';
    $inicio                 = ($_POST["fca_inicio"]!=$ayuda['actividad']['fca_inicio'])?$_POST["fca_inicio"]:'';
    $medios                 = ($_POST["fca_medios"]!=$ayuda['actividad']['fca_medios'])?$_POST["fca_medios"]:'';
    $nombre                 = ($_POST["fca_nombre"]!=$ayuda['actividad']['fca_nombre'])?$_POST["fca_nombre"]:'';
    //$materiales             = $_POST['fca_materiales'];
    $tipo                   = $_POST['fca_tipo_lugar'];
    $horas                  = ($_POST['fca_horas']*45);
    $medios_trabajos        = $_POST['fca_medios_trabajos'];
    $id_complementaria      = $_POST['fca_complementaria'];
    $consejos               = ($_POST["fca_consejos"]!=$ayuda['actividad']['fca_consejos'])?$_POST["fca_consejos"]:'';

    $medios_bitacora = 0;
    if(isset($_POST['fca_medios_bitacora'])){
        $medios_bitacora        = $_POST['fca_medios_bitacora'];
        if($medios_bitacora == 'on') $medios_bitacora = 1;
    }
    $medios_web2 = 0;
     if(isset($_POST['fca_medios_web2'])){
        $medios_web2            = $_POST['fca_medios_web2'];
        if($medios_web2 == 'on') $medios_web2 = 1;        
     }
     
     if(isset($_POST['fca_medios_trabajos_no'])){
        $medios_trabajos_no  = $_POST['fca_medios_trabajos_no'];
        if($medios_trabajos_no != 'on') $medios_trabajos = 1;
     }else{
         $medios_trabajos = 1;
     }
     

     $eval = array(
         'autoyco' => 0,
         'evaleco' => 0,
         'prodhetero' => 0         
     );
     if(isset($_POST['fca_eval_autoyco'])){
        if($_POST['fca_eval_autoyco'] == 'on') $eval['autoyco'] = 1;
     }
     if(isset($_POST['fca_eval_eco'])){
        if($_POST['fca_eval_eco'] == 'on') $eval['evaleco'] = 1;
     }
     if(isset($_POST['fca_eval_prodhetero'])){
        if($_POST['fca_eval_prodhetero'] == 'on') $eval['prodhetero'] = 1;
     }  
     
    $medios_otros = ($_POST["fca_medios_otros"]!=$ayuda['actividad']['fca_medios_otros'])?$_POST["fca_medios_otros"]:'';


    $instrucciones_producto = '';
    $instrucciones_revision = '';
    
    if($tipo == $actividad_laboratorio || $tipo == $actividad_casa){
        $medios = '';
        if($medios_trabajos == $actividad_revision){
                $publica_producto = 0;
                $revisa_pares = 1;            
        }else{
            $id_complementaria = 0;
            $revisa_pares = 0;                        
            if($medios_trabajos == $actividad_publicacion){
                 $publica_producto = 1;
            }else{
                $publica_producto = 0;
            }            
        }
    }else{
        $medios_web2 = 0;
        $medios_otros = '';
        $medios_trabajos = 0;
        $medios_bitacora = 0;
        $id_complementaria = 0;
        $revisa_pares = 0;
        $publica_producto = 0;
        
        
    }
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    $resultado = guardarActividadFuncion($_SESSION["klwn_id_usuario"], $id_diseno, $id_actividad, $nombre, $horas, $inicio, $desarrollo, $cierre, $descripcion_general, 
                                $publica_producto, $revisa_pares, $instrucciones_producto, $instrucciones_revision,
                                $id_complementaria, $aprendizaje_esperado, $evidencia_aprendizaje, $medios, $tipo,  
                                $medios_bitacora, $medios_trabajos, $medios_web2, $medios_otros, $consejos, $eval, $conexion);	

    dbDesconectarMySQL($conexion);

    echo $resultado;
?>