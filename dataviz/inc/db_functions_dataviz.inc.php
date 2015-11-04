<?php

class Usuario{
	public $id_usuario;
    public $fila_json;
	public $nombre;
	public $url_imagen;
	public $id_experiencia;
    public $nombre_clase;
	public $id_grupo;
    public $nombre_grupo;
	public $mensajes;
	public $mensajes_respuesta;
	public $megusta;
    public $mensajes_respuesta_recibidos;
    public $megusta_recibidos;
	public $participacion;
    public $historial_participacion;
}

class Grupo{
    public $id_experiencia;
    public $id_grupo;
    public $id_grupo_kelluwen;
    public $fila_json;
    public $participacion_total;
    public $historial_participacion;
    public $nombre;
    public $nombre_clase;
    public $mensajes;
    public $mensajes_respuesta;
    public $megusta;
    public $mensajes_respuesta_recibidos;
    public $megusta_recibidos;
    public $children;
}

class IntegranteGrupo{
    public $id_usuario;
    public $fila_json;
    public $size;
    public $url_imagen;
}

class Interaccion{
    //public $fila_usuario1;
    //public $fila_usuario2;
    public $source;
    public $target;
    public $id_usuario1;
    public $id_usuario2;
    public $total_interacciones;
    public $historial_interacciones;
    public $msjs_respuesta_usuario1;
    public $msjs_respuesta_usuario2;
    public $megusta_usuario1;
    public $megusta_usuario2;
    public $ponderacion;
}

class RegistroInteraccion{
    public $emisor;
    public $receptor;
    public $id_emisor;
    public $id_receptor;
    public $fecha;
    /*public $dias_antiguedad;
      public $diferencia_tiempo;*/
    public $tipo_interaccion;//0: respuesta a mensaje , 1 me gusta, 2 nuevo comentario
    public $id_mensaje;
    public $mensaje_objetivo;
    public $mensaje;
}

class InteraccionGrupal{
    public $source;
    public $target;
    public $total_interacciones;
    public $historial_interacciones;
    public $msjs_respuesta_grupo1;
    public $msjs_respuesta_grupo2;
    public $megusta_grupo1;
    public $megusta_grupo2;
    public $ponderacion;
}


function obtenerParticipacionTotalUsuarios($id_experiencia, $conexion){
    $_resp=null;

    $consulta_mensajes ="SELECT usuarios_experiencias.u_usuario, count(bthm_id_mensaje) AS bthm_total_mensajes
						 FROM (SELECT u_usuario , ue_id_experiencia
						  		FROM usuario , usuario_experiencia
								WHERE u_id_usuario=ue_id_usuario
								AND ue_id_experiencia IN (SELECT ed_id_experiencia
							  							  FROM  experiencia_didactica
							  							  WHERE ed_etiqueta_gemela = (SELECT ed_etiqueta_gemela
																					  FROM experiencia_didactica
																					  WHERE ed_id_experiencia = ".$id_experiencia."))

						) usuarios_experiencias
						LEFT JOIN bt_historial_mensajes
						ON bthm_usuario=usuarios_experiencias.u_usuario
						AND bthm_id_experiencia = usuarios_experiencias.ue_id_experiencia
						GROUP BY usuarios_experiencias.u_usuario";

	$consulta_resp_msjes="SELECT usuarios_experiencia.u_usuario , count(btrm_id_mensaje_respuesta) AS btrm_total_respuestas
						  FROM (SELECT u_usuario
								FROM usuario , usuario_experiencia
								WHERE u_id_usuario=ue_id_usuario
								AND ue_id_experiencia IN (SELECT ed_id_experiencia
							  							  FROM  experiencia_didactica
							  							  WHERE ed_etiqueta_gemela = (SELECT ed_etiqueta_gemela
																					  FROM experiencia_didactica
																					  WHERE ed_id_experiencia = ".$id_experiencia."))
						) usuarios_experiencia
						LEFT JOIN bt_respuesta_mensajes
						ON btrm_usuario=usuarios_experiencia.u_usuario
						LEFT JOIN bt_historial_mensajes
						ON btrm_id_mensaje_original = bthm_id_mensaje
						AND bthm_id_experiencia IN  (SELECT ed_id_experiencia
													FROM  experiencia_didactica
													WHERE ed_etiqueta_gemela=(SELECT ed_etiqueta_gemela
																			  FROM experiencia_didactica
																			  WHERE ed_id_experiencia = ".$id_experiencia."))
						GROUP BY usuarios_experiencia.u_usuario";

	$consulta_megusta ="SELECT usuarios_experiencia.u_usuario , count(btmg_id_mensaje) AS btmg_total_megusta
						FROM (SELECT u_id_usuario , u_usuario
							  FROM usuario , usuario_experiencia
							  WHERE u_id_usuario=ue_id_usuario
							  AND ue_id_experiencia IN (SELECT ed_id_experiencia
														FROM  experiencia_didactica
														WHERE ed_etiqueta_gemela = (SELECT ed_etiqueta_gemela
																				    FROM experiencia_didactica
																					WHERE ed_id_experiencia = ".$id_experiencia."))
						) usuarios_experiencia
						LEFT JOIN bt_megusta_mensaje
						ON usuarios_experiencia.u_id_usuario=bt_megusta_mensaje.btmg_id_usuario
						LEFT JOIN bt_historial_mensajes
						ON btmg_id_mensaje = bthm_id_mensaje
						AND bthm_id_experiencia IN  (SELECT ed_id_experiencia
													 FROM  experiencia_didactica
												     WHERE ed_etiqueta_gemela=(SELECT ed_etiqueta_gemela
																				FROM experiencia_didactica
																				WHERE ed_id_experiencia = ".$id_experiencia."))
						GROUP BY  usuarios_experiencia.u_usuario";

	$consulta_participacion="SELECT usuario_mensaje.u_usuario , usuario_mensaje.bthm_total_mensajes , 
							 usuario_respuesta.btrm_total_respuestas , usuario_megusta.btmg_total_megusta
							 FROM (".$consulta_mensajes.") AS usuario_mensaje
							 LEFT JOIN (".$consulta_resp_msjes.") AS usuario_respuesta
							 ON usuario_mensaje.u_usuario = usuario_respuesta.u_usuario
							 LEFT JOIN (".$consulta_megusta.") AS usuario_megusta
							 ON usuario_mensaje.u_usuario = usuario_megusta.u_usuario";
	
    
    //$time_start=microtime(true);
    $resultado = dbEjecutarConsulta($consulta_participacion,$conexion);
    //$time_end=microtime(true);
    //$dif=$time_end-$time_start;

    if($resultado){
        if (mysql_num_rows($resultado) > 0) {
            $_resp = array();
            $i = 0;
            while ($_fila = mysql_fetch_array($resultado,MYSQL_BOTH)) {
            	echo ($_fila["u_usuario"]." ".$_fila["bthm_total_mensajes"]." ".$_fila["btrm_total_respuestas"]."<br/>");
                $i++;
            }
        }
    }
    return $_resp;
}

function obtenerDespliegueAyudaVisualizacion($id_usuario,$conexion){
    $consulta_despliegue_ayuda="SELECT desplegado_ayuda_vista, desplegado_ayuda_selfcentered
                                FROM vis_despliegue_ayuda
                                WHERE id_usuario=".$id_usuario;
    $resultado_despliegue_ayuda= dbEjecutarConsulta($consulta_despliegue_ayuda,$conexion);
    if ($resultado_despliegue_ayuda){
        if (mysql_num_rows($resultado_despliegue_ayuda)>0){
           if($necesidad_ayuda = mysql_fetch_assoc($resultado_despliegue_ayuda,MYSQL_BOTH)){
                $despliegue_ayuda = array($necesidad_ayuda['desplegado_ayuda_vista'],$necesidad_ayuda['desplegado_ayuda_selfcentered']);
                return $despliegue_ayuda;
            }
        }else{
            $despliegue_ayuda=array(1,1);
            $consulta_necesita_ayuda="INSERT INTO vis_despliegue_ayuda 
                                    (id_usuario,
                                    desplegado_ayuda_vista,
                                    desplegado_ayuda_selfcentered) 
                                 VALUES (".
                                    $id_usuario.",
                                    0,
                                    1)";
            dbEjecutarConsulta($consulta_necesita_ayuda,$conexion);
        }
    }else{
        $despliegue_ayuda=array(-1,-1);
    }
    return $despliegue_ayuda;

}

function obtenerParticipacionUsuarios($id_experiencia, $id_usuario_consultor, $conexion, $ruta_carpeta_imagenes){
    
    $map_idexp_idjson= array();
    $map_idgrupo_idjson = array();
    $map_datagrupos_idjson = array();
    $json_data_grupos = array();
    $json_data_usuarios = array();
    $json_data_interacciones= array();
    $lista_usuarios = array();
    
    if($_SESSION["idioma"] == "spanish"){
        $lang_function_dataviz_de = "de";
        $lang_function_dataviz_profesor = "Profesor/a";
        $lang_function_dataviz_profesores = "Profesores";
        $lang_function_dataviz_varias_aulas = "varias aulas";
    }
    elseif($_SESSION["idioma"] == "english"){
        $lang_function_dataviz_de = "from";
        $lang_function_dataviz_profesor = "Teacher";
        $lang_function_dataviz_profesores = "Teachers";
        $lang_function_dataviz_varias_aulas = "several classrooms";
    }
    
    $consulta_datos_experiencia='SELECT ed_id_diseno_didactico,ed_semestre,ed_anio,ed_fecha_termino
                                 FROM experiencia_didactica
                                 WHERE ed_id_experiencia='.$id_experiencia;

    $res_consulta_experiencia = dbEjecutarConsulta($consulta_datos_experiencia,$conexion);
    if ($res_consulta_experiencia){
        if ($datos_experiencia = mysql_fetch_assoc($res_consulta_experiencia)){
            $id_diseno_didactico=$datos_experiencia['ed_id_diseno_didactico'];
            $semestre_experiencia=$datos_experiencia['ed_semestre'];
            $anio_experiencia=$datos_experiencia['ed_anio'];
            $fecha_termino_experiencia=$datos_experiencia['ed_fecha_termino'];
        }
    }

    
    //Se crea la consulta que obtiene el id de las aulas gemelas
    //En el caso que la experiencia ya haya finalizado, se buscan los id de solo experiencias que también hayan finalizado
    if ($fecha_termino_experiencia){
        $ponderar_tiempo=false;
        $consulta_experiencias='SELECT ed_id_experiencia , ed_curso , ed_colegio
                            FROM  experiencia_didactica
                            WHERE ed_id_diseno_didactico='.$id_diseno_didactico.
                            ' AND ed_semestre="'.$semestre_experiencia.'"
                              AND ed_anio='.$anio_experiencia.'
                              AND ed_fecha_termino IS NOT NULL';
    }
    //En el caso que la experiencia no haya finalizado aún, se buscan los id de solo experiencias que tampoco hayan finalizado aún
    else{
        $ponderar_tiempo=true;
        $consulta_experiencias='SELECT ed_id_experiencia , ed_curso , ed_colegio
                            FROM  experiencia_didactica
                            WHERE ed_id_diseno_didactico='.$id_diseno_didactico.
                            ' AND ed_semestre="'.$semestre_experiencia.'"
                              AND ed_anio='.$anio_experiencia.'
                              AND ed_fecha_termino IS NULL';
    }

    $res_experiencias = dbEjecutarConsulta($consulta_experiencias,$conexion);
    $lista_experiencias = array();
    if($res_experiencias){
        $i=0;
        while ($experiencia = mysql_fetch_assoc($res_experiencias)){
            $map_idexp_idjson[$experiencia['ed_id_experiencia']]['id']=$i;
            $clase= $experiencia['ed_curso'].' '.$lang_function_dataviz_de.' '.$experiencia['ed_colegio'];
            $map_idexp_idjson[$experiencia['ed_id_experiencia']]['clase']=$clase;
            //echo 'id experiencia '.$experiencia['ed_id_experiencia'].' clase '.$i.' nombre: '.$clase.'<br/>';
            $i++;
            array_push($lista_experiencias,$experiencia['ed_id_experiencia']);
        }
    }

    $nro_clases=count($lista_experiencias);

    $string_lista_experiencias=implode(',',$lista_experiencias);
    $string_lista_experiencias='('.$string_lista_experiencias.')';
    //echo $string_lista_experiencias.'</br>';

    $consulta_profesores_multiples_clases='SELECT ue_id_usuario , count(ue_id_experiencia) AS nro_clases
                                            FROM usuario_experiencia
                                            WHERE ue_rol_usuario=1
                                            AND  ue_id_experiencia IN '.$string_lista_experiencias.' 
                                            GROUP BY ue_id_usuario';

    $res_clases_por_profesor = dbEjecutarConsulta($consulta_profesores_multiples_clases,$conexion);

    $profesores_multiples_clases=false;
    if($res_clases_por_profesor){
        while ($clases_por_profesor = mysql_fetch_assoc($res_clases_por_profesor)){
            if ($clases_por_profesor['nro_clases']>1){
                $profesores_multiples_clases=true;
                //echo 'Profesor id: '.$clases_por_profesor['ue_id_usuario'].' está en mas de una clase </br>';
                break;
            }
        }
    }
    
    //echo $string_lista_experiencias.'<br/>';
    //echo 'id dd: '.$id_diseno_didactico.' semestre: '.$semestre_experiencia.' anio: '.$anio_experiencia.'<br/>';

    //Mapear los id de los grupos dentro de cada experiencia, al rango 1 a n_i (n_i=numero de grupos por cada clase) --> el id 0 esta reservado para los profesores
    /*$consulta_id_grupos = 'SELECT experiencias.ed_id_experiencia , experiencias.ed_curso , experiencias.ed_colegio , grupos.g_id_grupo , g.g_nombre
                                FROM (
                                    SELECT ed_id_experiencia, ed_curso , ed_colegio
                                    FROM  experiencia_didactica
                                    WHERE ed_id_diseno_didactico='.$id_diseno_didactico.
                                        ' AND ed_semestre="'.$semestre_experiencia.'"
                                          AND ed_anio='.$anio_experiencia.') AS experiencias
                                LEFT JOIN (
                                    SELECT g_id_experiencia , g_id_grupo
                                    FROM grupo
                                    WHERE g_id_experiencia IN '.$string_lista_experiencias.'
                                ) AS grupos
                                ON grupos.g_id_experiencia=experiencias.ed_id_experiencia
                                LEFT JOIN grupo AS g
                                ON g.g_id_grupo=grupos.g_id_grupo
                                ORDER BY experiencias.ed_id_experiencia';*/

    //Esta consulta sólo dejara a los grupos que tienen al menos un integrante
    /*$consulta_id_grupos = 'SELECT exp_grupo.ed_id_experiencia , exp_grupo.ed_curso , exp_grupo.ed_colegio , exp_grupo.g_id_grupo , exp_grupo.g_nombre, COUNT(exp_grupo.ug_id_usuario) AS nro_miembros_grupo
                           FROM (SELECT experiencias.ed_id_experiencia , experiencias.ed_curso , experiencias.ed_colegio , grupos.g_id_grupo , g.g_nombre
                                FROM (
                                    SELECT ed_id_experiencia, ed_curso , ed_colegio
                                    FROM  experiencia_didactica
                                    WHERE ed_id_diseno_didactico='.$id_diseno_didactico.
                                        ' AND ed_semestre="'.$semestre_experiencia.'"
                                          AND ed_anio='.$anio_experiencia.') AS experiencias
                                LEFT JOIN (
                                    SELECT g_id_experiencia , g_id_grupo
                                    FROM grupo
                                    WHERE g_id_experiencia IN '.$string_lista_experiencias.'
                                ) AS grupos
                                ON grupos.g_id_experiencia=experiencias.ed_id_experiencia
                                LEFT JOIN grupo AS g
                                ON g.g_id_grupo=grupos.g_id_grupo
                                LEFT JOIN usuario_grupo AS ug
                                ON ug.ug_id_grupo=g.g_id_grupo
                                ORDER BY experiencias.ed_id_experiencia) AS exp_grupo
                            GROUP BY exp_grupo.g_id_grupo';*/

    $consulta_id_grupos= 'SELECT exp_grupo.ed_id_experiencia , exp_grupo.ed_curso , exp_grupo.ed_colegio , exp_grupo.g_id_grupo , exp_grupo.g_nombre, COUNT(exp_grupo.ug_id_usuario) AS nro_miembros_grupo
                            FROM (SELECT experiencias.ed_id_experiencia , experiencias.ed_curso , experiencias.ed_colegio , grupos.g_id_grupo , g.g_nombre, ug.ug_id_usuario
                                FROM ('.
                                    $consulta_experiencias.') AS experiencias
                                LEFT JOIN (
                                    SELECT g_id_experiencia , g_id_grupo
                                    FROM grupo
                                    WHERE g_id_experiencia IN '.$string_lista_experiencias.'
                                ) AS grupos
                                ON grupos.g_id_experiencia=experiencias.ed_id_experiencia
                                LEFT JOIN grupo AS g
                                ON g.g_id_grupo=grupos.g_id_grupo
                                LEFT JOIN usuario_grupo AS ug
                                ON ug.ug_id_grupo=g.g_id_grupo) AS exp_grupo
                                GROUP BY exp_grupo.ed_id_experiencia,exp_grupo.g_id_grupo
                                ORDER BY exp_grupo.ed_id_experiencia';

    $res_id_grupos = dbEjecutarConsulta($consulta_id_grupos,$conexion);
    $num_filas_query_idgrupos=mysql_num_rows($res_id_grupos);
    $total_grupos=$num_filas_query_idgrupos;

    $json_arreglo_clases_grupos=array();

    if($res_id_grupos){
        if ($num_filas_query_idgrupos > 0) {
            $i=1;
            $j=0;
            $ultima_experiencia=-1;
            while ($id_exp_grupo = mysql_fetch_array($res_id_grupos,MYSQL_BOTH)) {
                $id_experiencia=$id_exp_grupo['ed_id_experiencia'];
                $id_grupo=$id_exp_grupo['g_id_grupo'];
                $nombre_grupo=$id_exp_grupo['g_nombre'];
                $nombre_curso=$id_exp_grupo['ed_curso'];
                $nombre_colegio=$id_exp_grupo['ed_colegio'];
                $nro_miembros_grupo=$id_exp_grupo['nro_miembros_grupo'];
                //echo 'id exp: '.$id_experiencia.' id grupo '.$id_grupo.' nro miembros: '.$nro_miembros_grupo.'<br/>';
                if ($id_grupo){
                    if ($ultima_experiencia>0 && $id_experiencia!=$ultima_experiencia){
                        $nro_grupos=$i;
                        array_push($json_arreglo_clases_grupos,$nro_grupos);
                        $i=1;
                    }
                    $map_idgrupo_idjson[$id_experiencia][$id_grupo]=$i;

                    //Código agregado por Jordan Barría el 16-11-14
                    if ($i==1){
                        $map_datagrupos_idjson[$map_idexp_idjson[$id_experiencia]['id']][0]=$j;
                        $grupo=new Grupo();
                        $grupo->id_experiencia=$map_idexp_idjson[$id_experiencia]['id'];
                        $grupo->id_grupo=0;
                        $grupo->id_grupo_kelluwen=0;
                        $grupo->fila_json=$j;
                        $grupo->nombre=$lang_function_dataviz_profesor;
                        $grupo->nombre_clase=$nombre_curso.' '.$lang_function_dataviz_de.' '.$nombre_colegio;
                        $grupo->mensajes=0;
                        $grupo->mensajes_respuesta=0;
                        $grupo->mensajes_respuesta_recibidos=0;
                        $grupo->megusta=0;
                        $grupo->megusta_recibidos=0;
                        $grupo->children=array();
                        $grupo->historial_participacion=array();
                        array_push($json_data_grupos,$grupo);
                        //echo $map_idexp_idjson[$id_experiencia]['id']." 0 ".$j."</br>";
                        $j++;
                    }
                    if ($nro_miembros_grupo>0){
                        $map_datagrupos_idjson[$map_idexp_idjson[$id_experiencia]['id']][$i]=$j;
                        //echo $map_idexp_idjson[$id_experiencia]['id']." ".$i." ".$j."</br>";
                        $grupo=new Grupo();
                        $grupo->id_experiencia=$map_idexp_idjson[$id_experiencia]['id'];
                        $grupo->id_grupo=$i;
                        $grupo->id_grupo_kelluwen=$id_grupo;
                        $grupo->fila_json=$j;
                        $grupo->nombre=$nombre_grupo;
                        $grupo->nombre_clase=$nombre_curso.' '.$lang_function_dataviz_de.' '.$nombre_colegio;
                        $grupo->mensajes=0;
                        $grupo->mensajes_respuesta=0;
                        $grupo->mensajes_respuesta_recibidos=0;
                        $grupo->megusta=0;
                        $grupo->megusta_recibidos=0;
                        $grupo->children=array();
                        $grupo->historial_participacion=array();
                        array_push($json_data_grupos,$grupo);
                        //fin código agregado por Jordan Barría el 16-11-14

                        //echo 'id exp: '.$id_experiencia.' id grupo '.$id_grupo.' le corresponde nro: '.$i.'<br/>';
                        //$ultima_experiencia=$id_experiencia;
                        //echo $id_experiencia.' '.$id_grupo.' : '.$i.'<br/>';
                        $j++;
                        $i++;
                    }
                    $ultima_experiencia=$id_experiencia;
                }else{
                    if ($ultima_experiencia>0 && $id_experiencia!=$ultima_experiencia){
                        $nro_grupos=$i;
                        array_push($json_arreglo_clases_grupos,$nro_grupos);
                    }
                    $map_datagrupos_idjson[$map_idexp_idjson[$id_experiencia]['id']][0]=$j;
                    $grupo=new Grupo();
                    $grupo->id_experiencia=$map_idexp_idjson[$id_experiencia]['id'];
                    $grupo->id_grupo=0;
                    $grupo->id_grupo_kelluwen=$id_grupo;
                    $grupo->fila_json=$j;
                    $grupo->nombre=$nombre_grupo;
                    $grupo->nombre_clase=$nombre_curso.' '.$lang_function_dataviz_de.' '.$nombre_colegio;
                    $grupo->mensajes=0;
                    $grupo->mensajes_respuesta=0;
                    $grupo->mensajes_respuesta_recibidos=0;
                    $grupo->megusta=0;
                    $grupo->megusta_recibidos=0;
                    $grupo->children=array();
                    $grupo->historial_participacion=array();
                    array_push($json_data_grupos,$grupo);
                    $j++;
                    array_push($json_arreglo_clases_grupos,1);
                    $ultima_experiencia=-1;
                    $i=1;
                }
            }
            if ($ultima_experiencia>0){
                $nro_grupos=$i;
                array_push($json_arreglo_clases_grupos,$nro_grupos);
            }
        }
    }

    //echo count($json_arreglo_clases_grupos).'</br>';


    //En caso de que exista al menos una profesor tomando mas de una clase, agregamos una experiencia ficticia, la cual
    //solo sera integrada por profesores de todas las experiencias
    if ($profesores_multiples_clases){
        //echo "Uno o más profesores están a cargo de más de un aula </br>";
        foreach ($json_arreglo_clases_grupos as &$clase) {
            $clase--;
        }
        array_push($json_arreglo_clases_grupos,1);
        $nro_clases++;

        //Se agrega el grupo de los profesores clusterizados
        $id_experiencia_prof=sizeof($json_arreglo_clases_grupos)-1;
        $fila_json_grupo=sizeof($json_data_grupos);
        $grupo=new Grupo();
        $grupo->id_experiencia=$id_experiencia_prof;
        $grupo->id_grupo=0;
        $grupo->id_grupo_kelluwen=0;
        $grupo->fila_json=$fila_json_grupo;
        $grupo->nombre=$lang_function_dataviz_profesores;
        $grupo->nombre_clase=" ".$lang_function_dataviz_varias_aulas;
        $grupo->mensajes=0;
        $grupo->mensajes_respuesta=0;
        $grupo->mensajes_respuesta_recibidos=0;
        $grupo->megusta=0;
        $grupo->megusta_recibidos=0;
        $grupo->children=array();
        $grupo->historial_participacion=array();
        array_push($json_data_grupos,$grupo);
        $map_datagrupos_idjson[$id_experiencia_prof][0]=$fila_json_grupo;
        //echo $id_experiencia_prof." 0 ".$fila_json_grupo."</br>";
    }


    /*$json_arreglo_clases_grupos=array();
    $fila_json=0;
    foreach($map_idgrupo_idjson as $id_clase => $clase) {
        $nro_grupos=0;
        foreach ($clase as $id_grupo => $grupo){
            $nro_grupos++;
        }
        array_push($json_arreglo_clases_grupos,$nro_grupos);
        $fila_json++;
    }*/


    //Pendiente agregar la condicion de fecha, que la ed_fecha_termino sea NULL (o sea que aún se encuentre en ejecución)
    $consulta_usuarios_exp_msjs='SELECT usuario_exp.u_id_usuario , usuario_exp.ue_id_experiencia , usuario_exp.ue_rol_usuario , usuario_exp.u_usuario , usuario_exp.u_nombre , usuario_exp.u_url_imagen,  g_id_grupo , g_nombre , bthm_id_mensaje , usuario_exp.ue_id_experiencia , bthm_fecha , bthm_mensaje
                            FROM (
                            SELECT u_id_usuario , u_usuario , u_nombre , u_url_imagen , ue_id_experiencia , ue_rol_usuario 
                            FROM  usuario , usuario_experiencia
                            WHERE u_id_usuario=ue_id_usuario '. 
                            //AND ue_rol_usuario != 3
                            'AND ue_id_experiencia IN '.$string_lista_experiencias.'
                            ) AS usuario_exp
                            LEFT JOIN 
                            (SELECT ug_id_usuario  ,ug_id_grupo FROM usuario_grupo WHERE ug_id_grupo IN (SELECT g_id_grupo FROM grupo WHERE g_id_experiencia IN '.$string_lista_experiencias.')) AS grupos_usuarios
                            ON usuario_exp.u_id_usuario=grupos_usuarios.ug_id_usuario
                            LEFT JOIN grupo
                            ON grupo.g_id_grupo=grupos_usuarios.ug_id_grupo
                            LEFT JOIN bt_historial_mensajes
                            ON usuario_exp.u_usuario=bthm_usuario
                            AND bthm_id_experiencia = usuario_exp.ue_id_experiencia 
                            ORDER BY usuario_exp.u_id_usuario';

    /*$consulta_usuarios_exp_msjs='SELECT usuario_exp.u_id_usuario , usuario_exp.ue_id_experiencia , usuario_exp.ue_rol_usuario , usuario_exp.u_usuario , usuario_exp.u_nombre , usuario_exp.u_url_imagen , bthm_id_mensaje , usuario_exp.ue_id_experiencia , bthm_fecha , bthm_mensaje
                            FROM (
                            SELECT u_id_usuario , u_usuario , u_nombre , u_url_imagen , ue_id_experiencia , ue_rol_usuario 
                            FROM  usuario , usuario_experiencia
                            WHERE u_id_usuario=ue_id_usuario
                            AND ue_rol_usuario != 3
                            AND ue_id_experiencia IN '.$string_lista_experiencias.'
                            ) AS usuario_exp
                            LEFT JOIN bt_historial_mensajes
                            ON usuario_exp.u_usuario=bthm_usuario
                            AND bthm_id_experiencia = usuario_exp.ue_id_experiencia 
                            ORDER BY usuario_exp.u_id_usuario';*/

    $consulta_usuarios_exp_msjrespuesta='SELECT usuario_exp.u_id_usuario , usuario_exp.u_usuario , usuario_exp.u_nombre , msjs_rpta.btrm_id_mensaje_respuesta , msjs_rpta.btrm_fecha , msjs_rpta.btrm_mensaje , msjs_rpta.btrm_id_mensaje_original , msjs_rpta.bthm_usuario , msjs_rpta.bthm_nombre , msjs_rpta.bthm_id_experiencia , msjs_rpta.bthm_mensaje
                    FROM (
                            SELECT u_id_usuario , u_usuario , u_nombre
                            FROM usuario , usuario_experiencia
                            WHERE u_id_usuario=ue_id_usuario '. 
                            //AND ue_rol_usuario != 3
                            'AND ue_id_experiencia IN '.$string_lista_experiencias.'
                            GROUP BY u_id_usuario
                            ) AS usuario_exp
                    LEFT JOIN (
                            SELECT btrm_usuario , btrm_id_mensaje_respuesta , btrm_fecha , btrm_mensaje , btrm_id_mensaje_original , bthm_usuario , bthm_nombre , bthm_mensaje , bthm_id_experiencia
                            FROM bt_respuesta_mensajes , bt_historial_mensajes
                            WHERE btrm_id_mensaje_original = bthm_id_mensaje
                            AND bthm_id_experiencia IN '.$string_lista_experiencias.'
                            ) AS msjs_rpta
                    ON usuario_exp.u_usuario=msjs_rpta.btrm_usuario
                    ORDER BY usuario_exp.u_id_usuario';

    $consulta_usuarios_exp_megusta='SELECT usuario_exp.u_id_usuario , usuario_exp.u_usuario , usuario_exp.u_nombre , megusta.btmg_fecha , megusta.bthm_id_mensaje , megusta.bthm_mensaje, megusta.bthm_usuario , megusta.bthm_nombre , megusta.bthm_id_experiencia
                    FROM (
                            SELECT u_id_usuario , u_usuario , u_nombre
                            FROM usuario , usuario_experiencia
                            WHERE u_id_usuario=ue_id_usuario '.
                            //AND ue_rol_usuario != 3
                            'AND ue_id_experiencia IN '.$string_lista_experiencias.'
                            GROUP BY u_id_usuario
                            ) AS usuario_exp
                    LEFT JOIN (
                            SELECT btmg_id_usuario , btmg_fecha , bthm_id_mensaje , bthm_usuario , bthm_nombre , bthm_mensaje , bthm_id_experiencia
                            FROM bt_megusta_mensaje , bt_historial_mensajes
                            WHERE btmg_id_mensaje = bthm_id_mensaje
                            AND bthm_id_experiencia IN '.$string_lista_experiencias.'
                            ) AS megusta
                    ON usuario_exp.u_id_usuario=megusta.btmg_id_usuario
                    ORDER BY usuario_exp.u_id_usuario';


    $res_usuarios_exp_msjs = dbEjecutarConsulta($consulta_usuarios_exp_msjs,$conexion);
    $num_filas_querymsjs=mysql_num_rows($res_usuarios_exp_msjs);

    //$ini_querymsjs=microtime(true);
    if($res_usuarios_exp_msjs){
    	$ultimo_id_usuario = -1 ;

        //Se inicializa en 1 la participacion base de cada usuario dado que en la visualizacion se le aplica una escala logaritmica, 
        //para lo cual el 1 tiene asociado un valor de 0 (sin participacion)

    	$ultimo_participacion_usuario = 1 ;
    	$pond_participacion_msj = 0 ;
    	$msjs=0;
        $ultima_actividad_usuario=array();
        if ($num_filas_querymsjs > 0) {
        	$i=1;
            while ($usuario_experiencia_msj = mysql_fetch_assoc($res_usuarios_exp_msjs)){//,MYSQL_ASSOC)) {
                //$ini=microtime(true);

            	$id_usuario=$usuario_experiencia_msj['u_id_usuario'];

            	$usuario=$usuario_experiencia_msj['u_usuario'];
            	$nombre=$usuario_experiencia_msj['u_nombre'];
                $rol_usuario=$usuario_experiencia_msj['ue_rol_usuario'];
                $url_imagen='../img/no_avatar_bigger.jpg';
                if ($usuario_experiencia_msj['u_url_imagen']){
                    $url_imagen=$ruta_carpeta_imagenes.$usuario_experiencia_msj['u_url_imagen'];
                }

                $id_experiencia=$usuario_experiencia_msj['ue_id_experiencia'];

                $id_grupo=$usuario_experiencia_msj['g_id_grupo'];
                $nombre_grupo=$usuario_experiencia_msj['g_nombre'];

            	$id_mensaje=$usuario_experiencia_msj['bthm_id_mensaje'];

	            if ($id_mensaje){
	            	
	            	$mensaje=$usuario_experiencia_msj['bthm_mensaje'];
		            $string_fecha_mensaje=$usuario_experiencia_msj['bthm_fecha'];
                    $data_ponderacion_msj=ponderarTiempoParticipacion($string_fecha_mensaje,$ponderar_tiempo);
                    $string_fecha_mensaje=date('d-m-Y H:i',strtotime($string_fecha_mensaje));
		            //$pond_participacion_msj=$data_ponderacion_msj[0];
                    $pond_participacion_msj=$data_ponderacion_msj;
                    $comentario_usuario=new RegistroInteraccion();
                    $indice_substr_emisor=strpos($nombre,' ');
                    $comentario_usuario->emisor=($indice_substr_emisor ? substr($nombre,0,$indice_substr_emisor) : $nombre);
                    $comentario_usuario->id_emisor=$id_usuario;
                    $comentario_usuario->tipo_interaccion=2;
                    $comentario_usuario->fecha=$string_fecha_mensaje;
                    /*$comentario_usuario->dias_antiguedad=$data_ponderacion_msj[1];
                    $comentario_usuario->diferencia_tiempo=$data_ponderacion_msj[2];*/
                    $comentario_usuario->id_mensaje=$id_mensaje;
                    $comentario_usuario->mensaje=$mensaje;
		            //$participacion_usuario=$participacion_usuario+$pond_participacion_msj;
		        }else{
		        	$pond_participacion_msj=0;
		        }

		        //Crea y agrega un nuevo objeto Usuario a medida que estos van apareciendo en la lectura de la query de mensajes
            	if ($ultimo_id_usuario!=-1){
            		if ($id_usuario!=$ultimo_id_usuario){

                        $id_experiencia_usuario= $map_idexp_idjson[$ultimo_id_experiencia]['id'];
                        $nombre_clase_usuario= $map_idexp_idjson[$ultimo_id_experiencia]['clase'];
                        if ($ultimo_grupo){
                            $grupo=$map_idgrupo_idjson[$ultimo_id_experiencia][$ultimo_grupo];
                            //echo $ultimo_id_usuario.'->usuario '.$ultimo_id_experiencia.' id experiencia: '.$id_experiencia_usuario.' '.$ultimo_grupo.' id grupo: '.$grupo.'<br/>';
                        }else{
                            if ($ultimo_rol_usuario==1){
                                $grupo=0;
                                if ($profesores_multiples_clases){
                                    $id_experiencia_usuario=$nro_clases-1;
                                    $consulta_clases_profesor='SELECT ed_curso , ed_colegio
                                                        FROM experiencia_didactica
                                                        WHERE ed_id_profesor='.$ultimo_id_usuario.'
                                                        AND ed_id_experiencia IN '.$string_lista_experiencias;
                                    $res_clases_profesor = dbEjecutarConsulta($consulta_clases_profesor,$conexion);
                                    $total_clases=mysql_num_rows($res_clases_profesor);
                                    if($res_clases_profesor && $total_clases>1){
                                        $nombre_clase_usuario="";
                                        $i=0;
                                        while ($clases_profesor = mysql_fetch_assoc($res_clases_profesor)){
                                            $nombre_clase_usuario=$nombre_clase_usuario.$clases_profesor['ed_curso'].' '.$lang_function_dataviz_de.' '.$clases_profesor['ed_colegio'];
                                            if ($i<$total_clases-1){
                                                $nombre_clase_usuario=$nombre_clase_usuario.', ';
                                            }
                                            $i++;
                                        }
                                    }
                                }
                                //echo $ultimo_id_usuario.'->usuario '.$ultimo_id_experiencia.' id experiencia: '.$id_experiencia_usuario.' '.$ultimo_grupo.' id grupo: '.$grupo.'<br/>';
                            }else{
                                $grupo=-1;
                            }
                        }
                        
            			$usuario_participacion=new Usuario();
            			$usuario_participacion->id_usuario=$ultimo_id_usuario;
            			$usuario_participacion->nombre=$ultimo_nombre_usuario;
            			$usuario_participacion->url_imagen=$ultimo_url_imagen;
                        $usuario_participacion->id_experiencia=$id_experiencia_usuario;
                        $usuario_participacion->nombre_clase=$nombre_clase_usuario;
                        $usuario_participacion->id_grupo=$grupo;
                        $usuario_participacion->nombre_grupo=$ultimo_nombre_grupo;
            			$usuario_participacion->participacion=$ultimo_participacion_usuario;
            			$usuario_participacion->mensajes=$msjs;
                        $usuario_participacion->historial_participacion=$ultima_actividad_usuario;
                        $usuario_participacion->mensajes_respuesta_recibidos=0;
                        $usuario_participacion->megusta_recibidos=0;

            			$json_data_usuarios[$ultimo_id_usuario]=$usuario_participacion;
                        $lista_usuarios[$ultimo_usuario]=$ultimo_id_usuario;

            			$ultimo_participacion_usuario=1;
                        $ultimo_grupo=null;
                        $ultimo_nombre_grupo=null;
            			$msjs=0;
                        $ultima_actividad_usuario=array();

            		}else{
                        if($ultimo_id_experiencia!=$id_experiencia && $rol_usuario==2 && $id_grupo!=-1){
                            $grupo_exp1=array_key_exists($id_grupo, $map_idgrupo_idjson[$ultimo_id_experiencia]);
                            $grupo_exp2=array_key_exists($id_grupo, $map_idgrupo_idjson[$id_experiencia]);
                            if ($grupo_exp1 && !$grupo_exp2){
                                $id_experiencia=$ultimo_id_experiencia;
                            }
                        }
                    }
            	}
                //if ($ultimo_usuario==$id_usuario){
                    /*$grupo_exp1=array_key_exists($id_grupo, $map_idgrupo_idjson[$ultimo_id_experiencia]);
                    $grupo_exp2=array_key_exists($id_grupo, $map_idgrupo_idjson[$id_experiencia]);
                    if ($grupo_exp1 && !$grupo_exp2){
                        $id_experiencia=$ultimo_id_experiencia;
                    }*/
                //}
		        $ultimo_id_usuario=$id_usuario;
		        $ultimo_usuario=$usuario;
            	$ultimo_nombre_usuario=$nombre;
            	$ultimo_url_imagen=$url_imagen;
                $ultimo_id_experiencia=$id_experiencia;
                $ultimo_rol_usuario=$rol_usuario;
                if ($id_grupo){
                    $ultimo_grupo=$id_grupo;
                    $ultimo_nombre_grupo=$nombre_grupo;
                }
                $ultimo_participacion_usuario=$ultimo_participacion_usuario+$pond_participacion_msj;
            	if ($id_mensaje) {
                    array_push($ultima_actividad_usuario,$comentario_usuario);
                    $msjs++;
                }
		        $i++;
            }
            //Esta seccion de codigo podria agruparse en una funcion crearUsuario(id,nombre,...)

            $id_experiencia_usuario= $map_idexp_idjson[$ultimo_id_experiencia]['id'];
            $nombre_clase_usuario= $map_idexp_idjson[$ultimo_id_experiencia]['clase'];
            if ($ultimo_grupo){
                $grupo=$map_idgrupo_idjson[$ultimo_id_experiencia][$ultimo_grupo];
            }else{
                if ($ultimo_rol_usuario==1){
                    $grupo=0;
                    if ($profesores_multiples_clases){
                        $id_experiencia_usuario=$nro_clases-1;
                        $consulta_clases_profesor='SELECT ed_curso , ed_colegio
                                            FROM experiencia_didactica
                                            WHERE ed_id_profesor='.$ultimo_id_usuario.'
                                            AND ed_id_experiencia IN '.$string_lista_experiencias;
                        $res_clases_profesor = dbEjecutarConsulta($consulta_clases_profesor,$conexion);
                        $total_clases=mysql_num_rows($res_clases_profesor);
                        if($res_clases_profesor && $total_clases>1){
                            $nombre_clase_usuario="";
                            $i=0;
                            while ($clases_profesor = mysql_fetch_assoc($res_clases_profesor)){
                                $nombre_clase_usuario=$nombre_clase_usuario.$clases_profesor['ed_curso'].' '.$lang_function_dataviz_de.' '.$clases_profesor['ed_colegio'];
                                if ($i<$total_clases-1){
                                    $nombre_clase_usuario=$nombre_clase_usuario.' y ';
                                }
                                $i++;
                            }
                        }
                    }
                    //echo $ultimo_id_usuario.'->usuario '.$ultimo_id_experiencia.' id experiencia: '.$id_experiencia_usuario.' '.$ultimo_grupo.' id grupo: '.$grupo.'<br/>';
                }else{
                    $grupo=-1;
                }
            }

            $usuario_participacion=new Usuario();
            $usuario_participacion->id_usuario=$ultimo_id_usuario;
            $usuario_participacion->nombre=$ultimo_nombre_usuario;
            $usuario_participacion->url_imagen=$ultimo_url_imagen;
            $usuario_participacion->id_experiencia=$id_experiencia_usuario;
            $usuario_participacion->nombre_clase=$nombre_clase_usuario;
            $usuario_participacion->id_grupo=$grupo;
            $usuario_participacion->participacion=$ultimo_participacion_usuario;
            $usuario_participacion->mensajes=$msjs;
            $usuario_participacion->historial_participacion=$ultima_actividad_usuario;
            $usuario_participacion->mensajes_respuesta_recibidos=0;
            $usuario_participacion->megusta_recibidos=0;

            $lista_usuarios[$ultimo_usuario]=$ultimo_id_usuario;

            $json_data_usuarios[$ultimo_id_usuario]=$usuario_participacion;
        }
    }
    /*$end_querymsjs=microtime(true);
    $execution_time =$end_querymsjs - $ini_querymsjs;
    echo 'Tiempo ejecucion msjes usuario'.$execution_time.'<br/>';*/

    
    //Query para obtener cuantos mensajes de respuesta efectuo cada usuario y a qué usuarios 
    
    //$ini_queryrptas=microtime(true);
    $res_usuarios_exp_msjrespuesta = dbEjecutarConsulta($consulta_usuarios_exp_msjrespuesta,$conexion);
    $num_filas_res_queryrespuestas=mysql_num_rows($res_usuarios_exp_msjrespuesta);
    /*$end_queryrptas=microtime(true);
    $dif=$end_queryrptas-$ini_queryrptas;
    echo 'Tiempo query rptas: '.$dif.'<br/>';*/
    //$ini_queryrptas=microtime(true);
    if($res_usuarios_exp_msjrespuesta){
    	$ultimo_id_usuario = -1 ;
    	$ultimo_participacion_usuario = 0 ;
    	$pond_participacion_usuario = 0 ;
    	$msjs_respuesta=0;
        if ($num_filas_res_queryrespuestas > 0) {
        	$i=1;
        	while ($usuario_experiencia_msjrespuesta = mysql_fetch_array($res_usuarios_exp_msjrespuesta,MYSQL_BOTH)) {
                //$time_start = microtime(true);

        		$id_usuario=$usuario_experiencia_msjrespuesta['u_id_usuario'];
        		$usuario=$usuario_experiencia_msjrespuesta['u_usuario'];
                $nombre_usuario=$usuario_experiencia_msjrespuesta['u_nombre'];
        	    $id_experiencia_original=$usuario_experiencia_msjrespuesta['bthm_id_experiencia'];
        		$id_msjeoriginal=$usuario_experiencia_msjrespuesta['btrm_id_mensaje_original'];

        		if ($id_experiencia_original){
        			$usuario_msj_original=$usuario_experiencia_msjrespuesta['bthm_usuario'];
                    $nombre_usuario_msj_original=$usuario_experiencia_msjrespuesta['bthm_nombre'];
                    $id_usuario_msj_original=$lista_usuarios[$usuario_msj_original];
                    $mensaje_original=$usuario_experiencia_msjrespuesta['bthm_mensaje'];
                    $id_msjrespuesta=$usuario_experiencia_msjrespuesta['btrm_id_mensaje_respuesta'];
                    $msj_respuesta=$usuario_experiencia_msjrespuesta['btrm_mensaje'];
        			$string_fecha_msjrespuesta=$usuario_experiencia_msjrespuesta['btrm_fecha'];
                    $data_ponderacion_msj=ponderarTiempoParticipacion($string_fecha_msjrespuesta,$ponderar_tiempo);
                    $string_fecha_msjrespuesta=date('d-m-Y H:i',strtotime($string_fecha_msjrespuesta));
                    $pond_participacion_msj=$data_ponderacion_msj;
        			/*$pond_participacion_msj=$data_ponderacion_msj[0];
                    $dias_antiguedad_msj=$data_ponderacion_msj[1];
                    $diferencia_relativa_tiempo_msj=$data_ponderacion_msj[2];*/
                                 

                    if ($id_usuario!=$id_usuario_msj_original){//Revisar: incluir las autorespuesta dentro de la ponderacion de participacion?
                        $key_par_usuarios=($id_usuario<$id_usuario_msj_original?''.$id_usuario.','.$id_usuario_msj_original:''.$id_usuario_msj_original.','.$id_usuario);
                        $interaccion_existente=array_key_exists($key_par_usuarios,$json_data_interacciones);//$json_data_interacciones[$key_par_usuarios];

                        $registroInteraccion=new RegistroInteraccion();
                        $indice_substr_emisor=strpos($nombre_usuario,' ');
                        $registroInteraccion->emisor=($indice_substr_emisor ? substr($nombre_usuario,0,$indice_substr_emisor) : $nombre_usuario);
                        $registroInteraccion->id_emisor=$id_usuario;

                        $indice_substr_receptor=strpos($nombre_usuario_msj_original,' ');
                        $registroInteraccion->receptor=($indice_substr_receptor ? substr($nombre_usuario_msj_original,0,$indice_substr_receptor) : $nombre_usuario_msj_original);
                        $registroInteraccion->id_receptor=$id_usuario_msj_original;

                        $registroInteraccion->tipo_interaccion=0;
                        $registroInteraccion->fecha=$string_fecha_msjrespuesta;
                        //$registroInteraccion->dias_antiguedad=$dias_antiguedad_msj;
                        //$registroInteraccion->diferencia_tiempo=$diferencia_relativa_tiempo_msj;
                        $registroInteraccion->id_mensaje=$id_msjrespuesta;
                        $registroInteraccion->mensaje_objetivo=$mensaje_original;
                        $registroInteraccion->mensaje=$msj_respuesta;

                        array_push($json_data_usuarios[$id_usuario]->historial_participacion, $registroInteraccion);//Agrega mensaje de respuesta al registro de actividad individual

                        $json_data_usuarios[$id_usuario_msj_original]->mensajes_respuesta_recibidos++;

                        if (!$interaccion_existente){
                            $interaccion=new Interaccion();
                            $interaccion->id_usuario1=$id_usuario;
                            $interaccion->id_usuario2=$id_usuario_msj_original;
                            $interaccion->total_interacciones=1;
                            $interaccion->historial_interacciones=array($registroInteraccion);
                            $interaccion->msjs_respuesta_usuario1=1;
                            $interaccion->msjs_respuesta_usuario2=0;
                            $interaccion->megusta_usuario1=0;
                            $interaccion->megusta_usuario2=0;
                            $json_data_interacciones[$key_par_usuarios]=$interaccion;
                        }else{
                            $json_data_interacciones[$key_par_usuarios]->total_interacciones++;
                            array_push($json_data_interacciones[$key_par_usuarios]->historial_interacciones,$registroInteraccion);
                            $id_usuario1=$json_data_interacciones[$key_par_usuarios]->id_usuario1;
                            $id_usuario2=$json_data_interacciones[$key_par_usuarios]->id_usuario2;
                            if ($id_usuario==$id_usuario1){
                                $json_data_interacciones[$key_par_usuarios]->msjs_respuesta_usuario1++;
                            }else{
                                $json_data_interacciones[$key_par_usuarios]->msjs_respuesta_usuario2++;
                            } 
                        }
                    }
                    //echo 'Usuario '.$id_usuario.' respondio al mensaje escrito por usuario '.$id_usuario_msj_original.'<br/>';
		            //$participacion_usuario=$participacion_usuario+$pond_participacion_msj;
        			//echo $usuario_experiencia_msjrespuesta['btrm_mensaje'].'<br/>';
        		}else{
        			$pond_participacion_msj=0;
        		}

        		
            	if ($ultimo_id_usuario!=-1){
            		if ($id_usuario!=$ultimo_id_usuario){
            			$participacion_mensajes=$json_data_usuarios[$ultimo_id_usuario]->participacion;
            			$json_data_usuarios[$ultimo_id_usuario]->participacion=$participacion_mensajes+$ultimo_participacion_usuario;
            			$json_data_usuarios[$ultimo_id_usuario]->mensajes_respuesta=$msjs_respuesta;
            			//echo 'Usuario: '.$ultimo_id_usuario.' ';
            			//echo 'Participacion respuesta: '.$ultimo_participacion_usuario.' Msjs respuesta: '.$msjs_respuesta.'<br/>';
                        //echo ' Participacion hasta ahora: '.$json_data_usuarios[$ultimo_id_usuario]->participacion.'</br>';
            			$ultimo_participacion_usuario=0;
            			$msjs_respuesta=0;
            		}
            		//else{
            			//$ultimo_participacion_usuario=$ultimo_participacion_usuario+$ultimo_pond_participacion_usuario;
            		//}
            	}
                
        		$ultimo_id_usuario=$id_usuario;
        		$ultimo_participacion_usuario=$ultimo_participacion_usuario+$pond_participacion_msj;
        		if ($id_experiencia_original) $msjs_respuesta++;
                /*$time_end = microtime(true);
                $execution_time =$time_end - $time_start;
                echo 'Tiempo ejecucion q '.$i.' respuestamsj: '.$execution_time.'<br/>';*/

        		$i++;
        	}
            
        	$participacion_mensajes=$json_data_usuarios[$ultimo_id_usuario]->participacion;
            $json_data_usuarios[$ultimo_id_usuario]->participacion=$participacion_mensajes+$ultimo_participacion_usuario;
            $json_data_usuarios[$ultimo_id_usuario]->mensajes_respuesta=$msjs_respuesta;

             
            //echo 'Usuario: '.$ultimo_id_usuario.' ';
            //echo 'Participacion respuesta: '.$ultimo_participacion_usuario.' Msjs respuesta: '.$msjs_respuesta.'<br/>';
        }
    }

    $res_usuarios_exp_megusta = dbEjecutarConsulta($consulta_usuarios_exp_megusta,$conexion);

    $num_filas_res_querymegusta=mysql_num_rows($res_usuarios_exp_megusta);
    if($res_usuarios_exp_megusta){
        $ultimo_id_usuario = -1 ;
        $ultimo_participacion_usuario = 0 ;
        $pond_participacion_usuario = 0 ;
        $megusta=0;
        if ($num_filas_res_querymegusta > 0) {
            $i=1;
            while ($usuario_experiencia_megusta = mysql_fetch_array($res_usuarios_exp_megusta,MYSQL_BOTH)) {
                

                $id_usuario=$usuario_experiencia_megusta['u_id_usuario'];
                $usuario=$usuario_experiencia_megusta['u_usuario'];
                $nombre_usuario=$usuario_experiencia_megusta['u_nombre'];
                $id_experiencia_original=$usuario_experiencia_megusta['bthm_id_experiencia'];
                $id_msje=$usuario_experiencia_megusta['bthm_id_mensaje'];
                $mensaje_original=$usuario_experiencia_megusta['bthm_mensaje'];

                
                if ($id_msje){
                    $usuario_msj_original=$usuario_experiencia_megusta['bthm_usuario'];
                    $string_fecha_megusta=$usuario_experiencia_megusta['btmg_fecha'];

                    $data_ponderacion_mg=ponderarTiempoParticipacion($string_fecha_megusta,$ponderar_tiempo);
                    $string_fecha_megusta=date('d-m-Y H:i',strtotime($string_fecha_megusta));
                    $pond_participacion_mg=0.2*$data_ponderacion_mg;
                    /*$pond_participacion_mg=$data_ponderacion_mg[0];
                    $dias_antiguedad_mg=$data_ponderacion_mg[1];
                    $diferencia_relativa_tiempo_mg=$data_ponderacion_mg[2];*/

                    $id_usuario_msj_original =$lista_usuarios[$usuario_msj_original];
                    $nombre_usuario_msj_original =$usuario_experiencia_megusta['bthm_nombre'];
                    //echo 'id usuario msje original '.$id_usuario_msj_original.'<br/>';
                    //$id_usuario_msj_original=$usuario_experiencia_megusta['id_usuario_msje'];
                    
                    if ($id_usuario!=$id_usuario_msj_original){
                        //$time_start = microtime(true);
                        $key_par_usuarios=($id_usuario<$id_usuario_msj_original?''.$id_usuario.','.$id_usuario_msj_original:''.$id_usuario_msj_original.','.$id_usuario);
                        $interaccion_existente=array_key_exists($key_par_usuarios,$json_data_interacciones);

                        $registroInteraccion=new RegistroInteraccion();
                        $indice_substr_emisor=strpos($nombre_usuario,' ');
                        $registroInteraccion->emisor=($indice_substr_emisor ? substr($nombre_usuario,0,$indice_substr_emisor) : $nombre_usuario);
                        $registroInteraccion->id_emisor=$id_usuario;

                        $indice_substr_receptor=strpos($nombre_usuario_msj_original,' ');
                        $registroInteraccion->receptor=($indice_substr_receptor ? substr($nombre_usuario_msj_original,0,$indice_substr_receptor) : $nombre_usuario_msj_original);
                        $registroInteraccion->id_receptor=$id_usuario_msj_original;

                        $registroInteraccion->tipo_interaccion=1;
                        $registroInteraccion->fecha=$string_fecha_megusta;
                        //$registroInteraccion->dias_antiguedad=$dias_antiguedad_mg;
                        //$registroInteraccion->diferencia_tiempo=$diferencia_relativa_tiempo_mg;
                        $registroInteraccion->id_mensaje=$id_msje;
                        $registroInteraccion->mensaje_objetivo=$mensaje_original;

                        array_push($json_data_usuarios[$id_usuario]->historial_participacion, $registroInteraccion);//Agrega me gusta al registro de actividad individual

                        $json_data_usuarios[$id_usuario_msj_original]->megusta_recibidos++;

                        if (!$interaccion_existente){
                            $interaccion=new Interaccion();//'id_usuario1'=>$id_usuario,'id_usuario2'=>$id_usuario_msj_original,'total_interacciones'=>1);
                            $interaccion->id_usuario1=$id_usuario;
                            $interaccion->id_usuario2=$id_usuario_msj_original;
                            $interaccion->total_interacciones=1;
                            $interaccion->historial_interacciones=array($registroInteraccion);
                            $interaccion->msjs_respuesta_usuario1=0;
                            $interaccion->msjs_respuesta_usuario2=0;
                            $interaccion->megusta_usuario1=1;
                            $interaccion->megusta_usuario2=0;
                            $json_data_interacciones[$key_par_usuarios]=$interaccion;
                            //echo $id_usuario.' dio me gusta a '.$id_usuario_msj_original.' id msje: '.$id_msje.'<br/>';
                        }else{  
                            $json_data_interacciones[$key_par_usuarios]->total_interacciones++;
                            array_push($json_data_interacciones[$key_par_usuarios]->historial_interacciones,$registroInteraccion);
                            $id_usuario1=$json_data_interacciones[$key_par_usuarios]->id_usuario1;
                            $id_usuario2=$json_data_interacciones[$key_par_usuarios]->id_usuario2;
                            if ($id_usuario==$id_usuario1){
                                $json_data_interacciones[$key_par_usuarios]->megusta_usuario1++;
                            }else{
                                $json_data_interacciones[$key_par_usuarios]->megusta_usuario2++;
                            } 
                        }
                        /*$time_end = microtime(true);
                        $execution_time =$time_end - $time_start;
                        echo 'Tiempo ejecucion q '.$i.' megusta: '.$execution_time.'<br/>';*/
                    }
                    
                    //echo 'Usuario '.$id_usuario.' dio me gusta al mensaje escrito por usuario '.$id_usuario_msj_original.'<br/>';
                    //$participacion_usuario=$participacion_usuario+$pond_participacion_msj;
                    //echo $usuario_experiencia_msjrespuesta['btrm_mensaje'].'<br/>';
                }else{
                    $pond_participacion_mg=0;
                }

                
                if ($ultimo_id_usuario!=-1){
                    if ($id_usuario!=$ultimo_id_usuario){
                        //$startupdate=microtime(true);
                        $participacion_megusta=$json_data_usuarios[$ultimo_id_usuario]->participacion;
                        //echo 'Usuario: '.$ultimo_id_usuario.' ';
                        //echo 'Participacion respuesta: '.$ultimo_participacion_usuario.' Msjs respuesta: '.$msjs_respuesta.'<br/>';
                        //echo ' Participacion hasta ahora: '.$json_data_usuarios[$ultimo_id_usuario]->participacion.'</br>';
                        $json_data_usuarios[$ultimo_id_usuario]->participacion=$participacion_megusta+$ultimo_participacion_usuario;
                        $json_data_usuarios[$ultimo_id_usuario]->megusta=$megusta;

                        

                        $ultimo_participacion_usuario=0;
                        $megusta=0;
                    }
                    //else{
                        //$ultimo_participacion_usuario=$ultimo_participacion_usuario+$ultimo_pond_participacion_usuario;
                    //}
                }

                $ultimo_id_usuario=$id_usuario;
                $ultimo_participacion_usuario=$ultimo_participacion_usuario+$pond_participacion_mg;
                if ($id_experiencia_original) $megusta++;

                $i++;
            }
            $participacion_megusta=$json_data_usuarios[$ultimo_id_usuario]->participacion;
            $json_data_usuarios[$ultimo_id_usuario]->participacion=$participacion_megusta+$ultimo_participacion_usuario;
            $json_data_usuarios[$ultimo_id_usuario]->megusta=$megusta;
            //echo 'Usuario: '.$ultimo_id_usuario.' ';
            //echo 'Participacion respuesta: '.$ultimo_participacion_usuario.' Msjs respuesta: '.$msjs_respuesta.'<br/>';
        }
    }
    /*$end_querymg=microtime(true);
    $execution_time =$end_querymg - $ini_querymg;
    echo 'Tiempo ejecucion mg usuario'.$execution_time.'<br/>';*/
    /*$time_end = microtime(true);
    $execution_time =$time_end - $time_start;
    echo 'Tiempo ejecucion megusta: '.$execution_time.'<br/>';*/

    //$time_start= microtime(true);

    $json_arreglo_usuarios=array();
    $fila_json=0;

    foreach($json_data_usuarios as $id => $us_exp) {
        if ($id!="" && $us_exp){
            $us_exp->fila_json=$fila_json;
            usort($us_exp->historial_participacion,"compararInteracciones");
        	array_push($json_arreglo_usuarios,$us_exp);

            //Código agregado por Jordan Barría el 16-11-14
            //Agrega el usuario respectivo al arreglo "children" del grupo correspondiente
            $id_experiencia=$us_exp->id_experiencia;
            $id_grupo=$us_exp->id_grupo;
            $nombre_usuario=$us_exp->nombre;
            $id_usuario=$us_exp->id_usuario;
            //echo $id_experiencia." id exp , ".$id_usuario." id usuario , ".$id_grupo." id grupo</br>";
            if ($id_grupo!=-1) {
                $grupojson=$map_datagrupos_idjson[$id_experiencia][$id_grupo];
                $integrante_grupo= new IntegranteGrupo();
                $integrante_grupo->id_usuario=$us_exp->id_usuario;
                $integrante_grupo->fila_json=$fila_json;
                $integrante_grupo->size=$us_exp->participacion;
                $integrante_grupo->url_imagen=$us_exp->url_imagen;

                array_push($json_data_grupos[$grupojson]->children,$integrante_grupo);
                $json_data_grupos[$grupojson]->historial_participacion=array_merge($json_data_grupos[$grupojson]->historial_participacion,$us_exp->historial_participacion);
                
                $json_data_grupos[$grupojson]->mensajes+=$us_exp->mensajes;
                $json_data_grupos[$grupojson]->mensajes_respuesta+=$us_exp->mensajes_respuesta;
                $json_data_grupos[$grupojson]->mensajes_respuesta_recibidos+=$us_exp->mensajes_respuesta_recibidos;
                $json_data_grupos[$grupojson]->megusta+=$us_exp->megusta;
                $json_data_grupos[$grupojson]->megusta_recibidos+=$us_exp->megusta_recibidos;
            }
            //fin código agregado por Jordan Barría

            $fila_json++;
        }
    	
    }

    $json_arreglo_grupos=array();

    foreach($json_data_grupos as $id => $grupo_exp) {
        $array_integrantes=$grupo_exp->children;
        $participacion_total=1;
        if($array_integrantes){
            foreach ($array_integrantes as $integrante){
                $participacion_total=$participacion_total+($integrante->size-1);
            }
        }
        $grupo_exp->participacion_total=$participacion_total;
        usort($grupo_exp->historial_participacion,"compararInteracciones");
        array_push($json_arreglo_grupos,$grupo_exp);
    }

    $json_arreglo_interacciones=array();
    $json_data_interacciones_grupales=array();

    $i=0;
    foreach($json_data_interacciones as $us1us2 => $interaccion) {
        $id_usuario1=$interaccion->id_usuario1;
        $id_usuario2=$interaccion->id_usuario2;

        $usuario1=$json_data_usuarios[$id_usuario1];
        $usuario2=$json_data_usuarios[$id_usuario2];

        $fila_usuario1=$usuario1->fila_json;
        $fila_usuario2=$usuario2->fila_json;

        $id_exp_usuario1=$usuario1->id_experiencia;
        $id_exp_usuario2=$usuario2->id_experiencia;

        $grupo_usuario1=$usuario1->id_grupo;
        $grupo_usuario2=$usuario2->id_grupo;
        
        $existe_grupo1=array_key_exists($grupo_usuario1,$map_datagrupos_idjson[$id_exp_usuario1]);
        $existe_grupo2=array_key_exists($grupo_usuario2,$map_datagrupos_idjson[$id_exp_usuario2]);
        //echo "Existe grupo 1: ".$existe_grupo1." existe grupo 2: ".$existe_grupo2."</br>";

        if ($existe_grupo1 && $existe_grupo2){//Línea de código agregada por Jordan Barría el 18-12-14
            $fila_grupo1=$map_datagrupos_idjson[$id_exp_usuario1][$grupo_usuario1];
            $fila_grupo2=$map_datagrupos_idjson[$id_exp_usuario2][$grupo_usuario2];
            //echo "Interaccion entre grupo ".$fila_grupo1." (usuario1 ".$id_usuario1.") y grupo ".$fila_grupo2." (usuario2 ".$id_usuario2.")</br>";
            $key_par_grupos=($fila_grupo1<$fila_grupo2?''.$fila_grupo1.','.$fila_grupo2:''.$fila_grupo2.','.$fila_grupo1);
            $interaccion_grupal_existente=array_key_exists($key_par_grupos,$json_data_interacciones_grupales);

            //if ($fila_grupo1!=null && $fila_grupo2!=null && ($fila_grupo1!=$fila_grupo2)){
            if ($fila_grupo1!=$fila_grupo2){
                if (!$interaccion_grupal_existente){
                    $interaccion_grupal=new InteraccionGrupal();
                    $interaccion_grupal->total_interacciones=sizeof($interaccion->historial_interacciones);
                    $interaccion_grupal->historial_interacciones=$interaccion->historial_interacciones;

                    if ($fila_grupo1<$fila_grupo2){
                        $interaccion_grupal->source=$fila_grupo1;
                        $interaccion_grupal->target=$fila_grupo2;
                        $interaccion_grupal->msjs_respuesta_grupo1=$interaccion->msjs_respuesta_usuario1;
                        $interaccion_grupal->msjs_respuesta_grupo2=$interaccion->msjs_respuesta_usuario2;
                        $interaccion_grupal->megusta_grupo1=$interaccion->megusta_usuario1;
                        $interaccion_grupal->megusta_grupo2=$interaccion->megusta_usuario2;
                    }else{
                        $interaccion_grupal->source=$fila_grupo2;
                        $interaccion_grupal->target=$fila_grupo1;
                        $interaccion_grupal->msjs_respuesta_grupo1=$interaccion->msjs_respuesta_usuario2;
                        $interaccion_grupal->msjs_respuesta_grupo2=$interaccion->msjs_respuesta_usuario1;
                        $interaccion_grupal->megusta_grupo1=$interaccion->megusta_usuario2;
                        $interaccion_grupal->megusta_grupo2=$interaccion->megusta_usuario1;
                    }
                    $json_data_interacciones_grupales[$key_par_grupos]=$interaccion_grupal;
                }else{
                    $interaccion_grupal=$json_data_interacciones_grupales[$key_par_grupos];

                    if ($fila_grupo1<$fila_grupo2){
                        $interaccion_grupal->msjs_respuesta_grupo1+=$interaccion->msjs_respuesta_usuario1;
                        $interaccion_grupal->msjs_respuesta_grupo2+=$interaccion->msjs_respuesta_usuario2;
                        $interaccion_grupal->megusta_grupo1+=$interaccion->megusta_usuario1;
                        $interaccion_grupal->megusta_grupo2+=$interaccion->megusta_usuario2;
                    }else{
                        $interaccion_grupal->msjs_respuesta_grupo1+=$interaccion->msjs_respuesta_usuario2;
                        $interaccion_grupal->msjs_respuesta_grupo2+=$interaccion->msjs_respuesta_usuario1;
                        $interaccion_grupal->megusta_grupo1+=$interaccion->megusta_usuario2;
                        $interaccion_grupal->megusta_grupo2+=$interaccion->megusta_usuario1;
                    }
                    
                    $interacciones_existentes=$interaccion_grupal->historial_interacciones;
                    $json_data_interacciones_grupales[$key_par_grupos]->historial_interacciones=array_merge($interacciones_existentes,$interaccion->historial_interacciones);
                    $json_data_interacciones_grupales[$key_par_grupos]->total_interacciones=$json_data_interacciones_grupales[$key_par_grupos]->total_interacciones+sizeof($interaccion->historial_interacciones);
                
                }
            }
            
            else{
                //Agregar código aquí en el caso que se quieran separar e incluir en el análisis de datos las interaccioens entre usuarios de un mismo grupo
                $total_respuestas_miembros_mismo_grupo=$interaccion->msjs_respuesta_usuario1+$interaccion->msjs_respuesta_usuario2;
                $total_megusta_miembros_mismo_grupo=$interaccion->megusta_usuario1+$interaccion->megusta_usuario2;
                $json_arreglo_grupos[$fila_grupo1]->mensajes_respuesta_recibidos-=$total_respuestas_miembros_mismo_grupo;
                $json_arreglo_grupos[$fila_grupo1]->megusta_recibidos-=$total_megusta_miembros_mismo_grupo;
            }
        }

        //$start=microtime(true);
        usort($interaccion->historial_interacciones,"compararInteracciones");
        /*$end=microtime(true);
        $dif=$end-$start;
        echo 'Tiempo sort array '.$i.' : '.$dif.'<br/>';*/
        if ($id_usuario1 && $id_usuario2){
            $interaccion->source=$fila_usuario1;
            $interaccion->target=$fila_usuario2;
            $ponderacion_tiempo=ponderarTiempoParticipacion($interaccion->historial_interacciones[0]->fecha,$ponderar_tiempo);
            $interaccion->ponderacion=$ponderacion_tiempo;
            array_push($json_arreglo_interacciones,$interaccion);
        }
        $i++;
 
    }

    $json_arreglo_interacciones_grupales=array();

    foreach($json_data_interacciones_grupales as $par_grupal=>$int_grupal){
        if ($int_grupal->historial_interacciones){
            usort($int_grupal->historial_interacciones,"compararInteracciones");
        }
        $ponderacion_tiempo=ponderarTiempoParticipacion($int_grupal->historial_interacciones[0]->fecha,$ponderar_tiempo);
        $int_grupal->ponderacion=$ponderacion_tiempo;
        array_push($json_arreglo_interacciones_grupales,$int_grupal);
    }



    $necesita_despliegue_ayuda=obtenerDespliegueAyudaVisualizacion($id_usuario_consultor,$conexion);

    $json_data=array('id_usuario'=>$id_usuario_consultor,'despliegue_ayuda'=>$necesita_despliegue_ayuda,'clasesgrupos'=> $json_arreglo_clases_grupos , 'grupos'=>$json_arreglo_grupos , 'links_grupos'=>$json_arreglo_interacciones_grupales ,'nodes'=>$json_arreglo_usuarios , 'links'=>$json_arreglo_interacciones,'profesores_multiples_clases'=>$profesores_multiples_clases);
    //$json_data=array('nodes'=> $json_arreglo_usuarios);
    
    return json_encode($json_data);
}

function ponderarParticipacionMensaje($string_fecha_mensaje , $ponderar_tiempo){
	$fecha_actual=new DateTime();
	$fecha_mensaje=date_create($string_fecha_mensaje);
	//echo $fecha_mensaje->format('Y-m-d H:i:s').' ';
	$dif_tiempo=$fecha_actual->diff($fecha_mensaje);
	$dif_dias=$dif_tiempo->days;
    if ($ponderar_tiempo){
        $pond_participacion=exp(-1*pow(($dif_dias/14),2));
    }else{
        $pond_participacion=1;
    }

    //$dif_relativa=diferenciaRelativaTiempo($string_fecha_mensaje);
	//$pond_participacion=array();
    //$pond_participacion[0]=log(pow($dif_dias,-0.5)); Ecuación original Base Level Learning (BLL)
    //$pond_participacion[0]=exp(-1*$dif_dias/10.0988); Intento para que se alcanzara la valoración 0.5 en 7 días de antiguedad de un mensaje
    //$pond_participacion[0]=exp(-1*pow(($dif_dias/20),2));
    //echo "Msje dif dias: ".$dif_dias." ponderacion: ".$pond_participacion[0]."</br>";
    //$pond_participacion[1]=$dif_dias;
    //$pond_participacion[2]=$dif_relativa;
	return $pond_participacion;
}

function ponderarParticipacionMeGusta($string_fecha_megusta , $ponderar_tiempo){
	$fecha_actual=new DateTime();
	$fecha_megusta=date_create($string_fecha_megusta);

	$dif_tiempo=$fecha_actual->diff($fecha_megusta);
	$dif_dias=$dif_tiempo->days;

    if ($ponderar_tiempo){
        $pond_participacion=0.2*exp(-1*pow(($dif_dias/14),2));
    }else{
        $pond_participacion=0.2;
    }

    //$dif_relativa=diferenciaRelativaTiempo($string_fecha_megusta);
    //$pond_participacion=array();
    //$pond_participacion[0]=0.2*pow(log($dif_dias),-0.5);
    //$pond_participacion[0]=0.2*exp(-1*pow(($dif_dias/20),2));
    //$pond_participacion[1]=$dif_dias;
    //$pond_participacion[2]=$dif_relativa;
	return $pond_participacion;
}

function ponderarTiempoParticipacion($string_fecha_megusta , $ponderar_tiempo){
    $fecha_actual=new DateTime();
    $fecha_megusta=date_create($string_fecha_megusta);

    $dif_tiempo=$fecha_actual->diff($fecha_megusta);
    $dif_dias=$dif_tiempo->days;

    if ($ponderar_tiempo){
        $pond_participacion=exp(-1*pow(($dif_dias/12),2));
    }else{
        $pond_participacion=1;
    }

    //$dif_relativa=diferenciaRelativaTiempo($string_fecha_megusta);
    //$pond_participacion=array();
    //$pond_participacion[0]=0.2*pow(log($dif_dias),-0.5);
    //$pond_participacion[0]=0.2*exp(-1*pow(($dif_dias/20),2));
    //$pond_participacion[1]=$dif_dias;
    //$pond_participacion[2]=$dif_relativa;
    return $pond_participacion;
}

function compararInteracciones($int1, $int2)
{
    //$t1=$int1->dias_antiguedad;
    //$t2=$int2->dias_antiguedad;
    $t1=strtotime($int1->fecha);
    $t2=strtotime($int2->fecha);
    return  $t1 == $t2 ? 0 : ( $t1 < $t2 ) ? 1 : -1;
    //return $d1>$d2;
}

function diferenciaRelativaTiempo($fecha_mensaje, $prefijo = '', $formato_fecha = 'Y-m-d H:i:s'){
    $prefijo = $lang_function_dataviz_hace;
    $dif = time() - strtotime($fecha_mensaje);
    if($dif < 60) 
        return $prefijo.' '.$dif . ' '.$lang_function_dataviz_segundo. ($dif != 1 ? 's' : '');
    $dif = round($dif/60);
    if($dif < 60) 
        return $prefijo.' '.$dif . ' '.$lang_function_dataviz_minuto. ($dif != 1 ? 's' : '');
    $dif = round($dif/60);
    if($dif < 24) 
        return $prefijo.' '.$dif . ' '.$lang_function_dataviz_hora. ($dif != 1 ? 's' : '');
    $dif = round($dif/24);
    if($dif < 7) 
        return $prefijo.' '.$dif . ' '.$lang_function_dataviz_dia. ($dif != 1 ? 's' : '');
    $dif = round($dif/7);
    if($dif < 4) 
        return $prefijo.' '.$dif . ' '.$lang_function_dataviz_semana. ($dif != 1 ? 's' : '');
    $dif = round($dif/4);
    if($dif < 12) 
        return $prefijo.' '.$dif . ' '.$lang_function_dataviz_mes. ($dif != 1 ? 'es' : '');
    $dif = round($dif/12);
    if($dif < 10)//diferencia arbitraria de 10 años para el despliegue
        return $prefijo.' '.$dif . ' '.$lang_function_dataviz_ano. ($dif != 1 ? 's' : '');

    return date($formato_fecha, strtotime($fecha_mensaje));
}

?>