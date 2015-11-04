<?php
    /**
     * @author  Elson Gueregat - Kelluwen
     * @copyleft Kelluwen, Universidad Austral de Chile
     * @license www.kelluwen.cl/app/licencia_kelluwen.txt
     * @version 0.1  
     **/

    //Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
    //if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

    $ruta_raiz = "./../";
    require_once($ruta_raiz . "conf/config.php");
    require_once($ruta_raiz . "inc/all.inc.php");
    require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
    require_once($ruta_raiz . "taller_dd/conf/tdd_config.php");
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");

    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    $archivo_descripcion        = $_POST['faa_descripcion'];
    $nombre_archivo             = $_FILES["file"]['name'];
    if($_FILES["file"]['name'] == null || $_FILES["file"]['name'] == ""){
       // header("Location:tdd_form_subir_archivo.php?idActividad=".$id_actividad."&error=3");
        $nombre_archivo=null;
        subirArchivoEjemploFuncion($nombre_archivo, $archivo_descripcion, $conexion);
        $resultado = 1;
                header("Location:tdd_form_subir_archivo_ejemplo.php?error=0");
    }else{
        $destino = $ruta_raiz.$config_ruta_archivo_ejemplo;
        $resultado = 0;
        // Leemos el tamaño del fichero 
        $tamano = $_FILES['file']['size']; 

    // Comprovamos el tamaño
        if($tamano < $max_tamano){ 
            if(copy($_FILES['file']['tmp_name'], $destino.'/'.$nombre_archivo)){
                subirArchivoEjemploFuncion($nombre_archivo, $archivo_descripcion, $conexion);
                $resultado = 1;
         
                header("Location:tdd_form_subir_archivo_ejemplo.php?error=0");
            }else{
                header("Location:tdd_form_subir_archivo_ejemplo.php?error=1");
            }
        }else{
            header("Location:tdd_form_subir_archivo_ejemplo.php?error=2");
        } 
    }
    
dbDesconectarMySQL($conexion);    
?>