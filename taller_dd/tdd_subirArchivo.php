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
    $solo_profesor__            = $_POST['faa_solo_profesor'];
    $id_actividad               = $_POST['faa_id_actividad'];
    $nombre_archivo             = $_FILES["file"]['name'];

    if($_FILES["file"]['name'] == null || $_FILES["file"]['name'] == ""){
       // header("Location:tdd_form_subir_archivo.php?idActividad=".$id_actividad."&error=3");
        $nombre_archivo=null;
        $solo_profesor = 0;
        if($solo_profesor__ == 'on') $solo_profesor = 1;
        subirArchivoFuncion($nombre_archivo, $archivo_descripcion, $solo_profesor, $id_actividad, $conexion);
        $resultado = 1;
                header("Location:tdd_form_subir_archivo.php?idActividad=".$id_actividad."&error=0");
    }else{
        $solo_profesor = 0;
        if($solo_profesor__ == 'on') $solo_profesor = 1;

        $destino = $ruta_raiz.$carpeta_subida_archivos;

        $resultado = 0;
        // Leemos el tamaño del fichero 
        $tamano = $_FILES['file']['size']; 

        if(!is_dir($destino.'/'.$id_actividad)){
            mkdir($destino.'/'.$id_actividad, 0775);
        }

    // Comprovamos el tamaño
        if($tamano < $max_tamano){ 
            if(@copy($_FILES['file']['tmp_name'], $destino.'/'.$id_actividad.'/'.$nombre_archivo)){
                subirArchivoFuncion($nombre_archivo, $archivo_descripcion, $solo_profesor, $id_actividad, $conexion);
                $resultado = 1;
         
                $_diseno =obtenerDisenoActividadFuncion($id_actividad, $conexion);
                if(isset($_diseno[0]['dd_id_diseno_didactico'])){
                    agregarRegistroCambio($_SESSION["klwn_id_usuario"], $_diseno[0]['dd_id_diseno_didactico'], $id_actividad, 1, 1, 'Se Agregó el material "'.$nombre_archivo
                                        .'" a la actividad "'.$_diseno[0]['ac_nombre'].'" del diseño "'.$_diseno[0]['dd_nombre'].'"'
                                        , $consulta, $conexion);
                }
                header("Location:tdd_form_subir_archivo.php?idActividad=".$id_actividad."&error=0");
            }else{
                header("Location:tdd_form_subir_archivo.php?idActividad=".$id_actividad."&error=1");
            }
        }else{
            header("Location:tdd_form_subir_archivo.php?idActividad=".$id_actividad."&error=2");
        } 
    }
    
dbDesconectarMySQL($conexion);    
?>