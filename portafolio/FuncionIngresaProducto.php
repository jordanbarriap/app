<?php

$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/db_functions.inc.php");
require_once($ruta_raiz."portafolio/inc/por_funciones_db.inc.php");
require_once($ruta_raiz."portafolio/inc/rp_functions.inc.php");

if(existeSesion ()){
    //Obtencion de datos del formulario de producto
    $id_actividad = $_REQUEST['id_actividad'];
    $id_experiencia = $_REQUEST['id_experiencia'];
    $id_usuario = $_REQUEST['id_usuario'];
    $id_grupo = $_REQUEST['id_grupo'];
    $nombre_producto = $_REQUEST['nombre'];
    $descripcion_producto = $_REQUEST['texto'];
    $archivo_nombre_original = $_FILES['archivo']['name'];
    $archivo_tipo = $_FILES['archivo']['type'];
    $link = $_REQUEST['link'];
    //echo " Nombre de producto".$nombre_producto;
 //Ejecucion del ingreso del producto en la base de datos
    $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
   // echo $archivo_tipo;
    $respuesta = 0;

    //verificamos el link
    if($link == $lang_por_completa_link){
        $link = null;
    }

    //verificamos si hay archivo a subir
    if($archivo_nombre_original){
        //nombre de archivo sin espacios para manejo en el servidor
        $archivo_nombre = str_replace(' ', '_', $archivo_nombre_original);

        //definir el nombre del archivo en el servidor identificando por id_grupo
        $archivo_destino =$id_grupo."_".$archivo_nombre; 
        //$archivo_destino = strtolower($archivo_destino);
        //$archivo_destino = utf8_decode($archivo_destino);

        //conversion a minusculas del nombre del archivo
        //$archivo_nombre = strtolower($archivo_nombre);
        //echo $archivo_nombre.'-';
        //eliminar acentos del archivo
        //ejemplo de uso  
        $archivo_nombre =  limpiarString($archivo_nombre);
        $archivo_destino =  limpiarString($archivo_destino);
        //echo $archivo_nombre;
        //definicion del directorio a almacenar (uno por experiencia)
        //consultamos si existe directorio de experiencia
        $ruta_experiencia = $config_ruta_documentos_pares.'exp_'.$id_experiencia.'/';
        $ruta_actividad = $ruta_experiencia.'act_'.$id_actividad.'/';
        if(!is_dir($ruta_experiencia)){
            //crear directorio de experiencia
            //consultar sobre permisos a la hora de crear el archivo
            mkdir($ruta_experiencia,0777);
            mkdir($ruta_actividad,0777);
        }
        elseif(!is_dir($ruta_actividad)){
            mkdir($ruta_actividad);
        }

        //arreglo de extensiones (mimes) permitidos para subir archivos a la herramienta trabajos
        $tipos_permitidos = 
            Array(
                /*imagen*/ 'image/jpeg','image/pjpeg','image/gif','image/png',
                /*word y pdf*/'application/pdf','application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                /*powerpoint*/'application/mspowerpoint','application/powerpoint','application/vnd.ms-powerpoint','application/x-mspowerpoint',
                                          'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                /*excel*/ 'application/excel','application/vnd.ms-excel','application/x-excel','application/x-msexcel',
                                  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                /*audio*/  'audio/mpeg3','audio/x-mpeg-3',/*mpg*/'video/mpeg','video/x-mpeg',
                /*video*/   'video/msvideo', 'video/mp4'
            );

        if(is_numeric(strpos_a($archivo_tipo, $tipos_permitidos)) === TRUE ){
            if (($_FILES["archivo"]["size"] < 20000000)){
                if ($_FILES["archivo"]["error"] > 0){
                    $respuesta = -2; //caso de error con archivo
                    }
                else{
                    //definicion del directorio a almacenar (uno por experiencia)
                    //consultamos si existe directorio de experiencia
                    $ruta_experiencia = $config_ruta_documentos_pares.'exp_'.$id_experiencia.'/';
                    //echo $ruta_experiencia;
                    $ruta_actividad = $ruta_experiencia.'act_'.$id_actividad.'/';
                    if(!is_dir($ruta_experiencia)){
                            //crear directorio de experiencia
                            //consultar sobre permisos a la hora de crear el archivo
                            mkdir($ruta_experiencia,0777);
                            mkdir($ruta_actividad,0777);

                    }
                    elseif(!is_dir($ruta_actividad)){
                        mkdir($ruta_actividad);
                    }

                
                    move_uploaded_file($_FILES["archivo"]["tmp_name"],$ruta_actividad.$archivo_destino);
                    $respuesta3 = dbPIngresaProducto($id_actividad,$id_experiencia,$id_usuario,$id_grupo,$nombre_producto,$descripcion_producto,$link,$archivo_nombre,$conexion);
                    
                    

                    //datos que consultan si es actividad de publicacion y su insercion
                    $a_info_actividad = dbExpObtenerActividad($id_actividad,$conexion);

                    if($a_info_actividad['publica_producto'] == 1){
                        echo "Actividad de publicacion";
                        $respuesta2 = dbPIngresaCoevProducto($id_grupo, $id_actividad, $id_experiencia,$conexion);
                        
                    }
                     $respuesta = 1;//caso correcto
                }
            }
            else {
                $respuesta = -3; //caso peso archivo excede
            }
          }
          else{
             $respuesta = -4;//caso extension no valida
          }
    }
    //caso que el producto no tenga archivo
    else{
        //se ingresa el producto
        //$respuesta3 = dbPIngresaProducto($id_actividad,$id_experiencia,$id_usuario,$id_grupo,$nombre_producto,$descripcion_producto,$link,$archivo_nombre,$conexion);

        $respuesta3 = dbPIngresaProducto($id_actividad,$id_experiencia,$id_usuario,$id_grupo,$nombre_producto,$descripcion_producto,$link,$archivo_nombre,$conexion);

        //datos que consultan si es actividad de publicacion y su insercion en la tabla vinculos
        $a_info_actividad = dbExpObtenerActividad($id_actividad,$conexion);
        //se debe cambiar este if porque esa columna de publica_producto ya no se usará. 
        if($a_info_actividad['publica_producto'] == 1){
            //se llama a la funcion dbpIngresaVinculoGrupo  con id de actividad de publicacion.   
            $respuesta2 = dbPIngresaCoevProducto($id_grupo, $id_actividad, $id_experiencia,$conexion);

        }

        $respuesta = 1;//caso correcto
    }

    dbDesconectarMySQL($conexion);
    echo $respuesta;
}
else{
   echo 0;
}
?>