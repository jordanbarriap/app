<?php

//Agregado el 10-11-14, modificado el 19-05-15
function obtenerArrayExpGemelas($id_experiencia,$conexion){

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
        $consulta_experiencias='SELECT ed_id_experiencia , ed_curso , ed_colegio
                            FROM  experiencia_didactica
                            WHERE ed_id_diseno_didactico='.$id_diseno_didactico.
                            ' AND ed_semestre="'.$semestre_experiencia.'"
                              AND ed_anio='.$anio_experiencia.'
                              AND ed_fecha_termino IS NOT NULL';
    }
    //En el caso que la experiencia no haya finalizado aún, se buscan los id de solo experiencias que tampoco hayan finalizado aún
    else{
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
        while ($experiencia = mysql_fetch_assoc($res_experiencias)){
            array_push($lista_experiencias,$experiencia['ed_id_experiencia']);
        }
    }
    array_push($lista_experiencias,351);
    return $lista_experiencias;
}

?>