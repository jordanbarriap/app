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
    require_once($ruta_raiz . "inc/db_functions.inc.php");
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");


    $enunciado      = $_POST['fcp_enunciado'];
    $fcp_id_enunciado = $_POST['fcp_id_enunciado'];
    $id_actividad   = $_POST['fcp_id_actividad'];
    $id_diseno      = $_POST['fcp_id_diseno'];
    $tipo           =  $_POST['fcp_tipo'];
    
    $id_tipo = -1;    
    if($tipo == "autoyco"){$id_tipo = 1;}
    if($tipo == "prodhetero"){$id_tipo = 4;}
    if($tipo == "eco"){$id_tipo = 5;}
    
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    
    if($fcp_id_enunciado > 0){
        $idNuevoEnunciado = $fcp_id_enunciado;
    }else{
        $idNuevoEnunciado = agregarEnunciadoFuncion($enunciado, $conexion);
    }

    if($idNuevoEnunciado > 0){
        $_resultadoMax=  obtenerMaxPautasPorTipoFuncion($id_actividad, $id_tipo, $conexion);
        if(count($_resultadoMax) <= 0){
            if($id_tipo == 1){
                $_resultadoMax=  obtenerMaxPautasPorTipoFuncion($id_actividad, 2, $conexion);
            }else if($id_tipo == 4){
                $_resultadoMax=  obtenerMaxPautasPorTipoFuncion($id_actividad, 3, $conexion);
            }
        }
        
        if(count($_resultadoMax) > 0){

            $idRubrica  = $_resultadoMax[0]['rbenu_id_rubrica'];
            $orden = ($_resultadoMax[0]['rbenu_orden'] + 1);
            $_resultado2=  agregarRubricaEnunciadoFuncion($idRubrica, $idNuevoEnunciado, $orden, $conexion);
            
            if($id_tipo == 1){
                $_resultadoMax=  obtenerMaxPautasPorTipoFuncion($id_actividad, 2, $conexion);
                if(count($_resultadoMax) > 0){

                    $idRubrica  = $_resultadoMax[0]['rbenu_id_rubrica'];
                    $orden = ($_resultadoMax[0]['rbenu_orden'] + 1);
                    $_resultado2=  agregarRubricaEnunciadoFuncion($idRubrica, $idNuevoEnunciado, $orden, $conexion);
                }
            }else if($id_tipo == 4){
                $_resultadoMax=  obtenerMaxPautasPorTipoFuncion($id_actividad, 3, $conexion);
                if(count($_resultadoMax) > 0){

                    $idRubrica  = $_resultadoMax[0]['rbenu_id_rubrica'];
                    $orden = ($_resultadoMax[0]['rbenu_orden'] + 1);
                    $_resultado2=  agregarRubricaEnunciadoFuncion($idRubrica, $idNuevoEnunciado, $orden, $conexion);
                }
            }
        }else{

            $_escalaDiseno = obtenerDisenoEscalaByActividadFuncion($id_actividad, $conexion);

            if(count($_escalaDiseno) > 0){
                $idNuevaRubrica = -1;
                $idNuevaRubrica = agregarRubricaFuncion($_escalaDiseno[0]['dd_escala'], $conexion);
                if($idNuevaRubrica > 0){

                    $idNuevaEvaluacion = -1;
                    $idNuevaEvaluacion = agregarEvaluacionFuncion($idNuevaRubrica, $id_tipo, $conexion);
                    
                    if($id_tipo == 1){
                        $idNuevaEvaluacion2 = agregarEvaluacionFuncion($idNuevaRubrica, 2, $conexion);
                        if($idNuevaEvaluacion2 > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion2, $id_actividad, $conexion);
                        }
                    }
                    else if($id_tipo == 4){
                        $idNuevaEvaluacion2 = agregarEvaluacionFuncion($idNuevaRubrica, 3, $conexion);
                        if($idNuevaEvaluacion2 > 0){
                            $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion2, $id_actividad, $conexion);
                        }
                    }
                    
                    if($idNuevaEvaluacion > 0){

                        $resul = agregarEvaluacionActividadFuncion($idNuevaEvaluacion, $id_actividad, $conexion);
                        if($resul){

                            $_resultado2=  agregarRubricaEnunciadoFuncion($idNuevaRubrica, $idNuevoEnunciado, 1, $conexion);
                        }
                    }
                }
            }
        }
    }
    
    if($idNuevoEnunciado > 0){
        
        $_diseno =obtenerDisenoActividadFuncion($id_actividad, $conexion);
        if(isset($_diseno[0]['dd_id_diseno_didactico'])){
            agregarRegistroCambio($_SESSION["klwn_id_usuario"], $_diseno[0]['dd_id_diseno_didactico'], $id_actividad, 1, 1, 'Se Agregó un enunciado "'.$enunciado
                                .'" a la actividad "'.$_diseno[0]['ac_nombre'].'" del diseño "'.$_diseno[0]['dd_nombre'].'"'
                                , "", $conexion);
        }        
        echo "1";
    }else{
        echo "-1";
    }
    dbDesconectarMySQL($conexion);
    
?>
