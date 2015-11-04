<?php

	function noEstaEn($valor, $arreglo, $propiedad) {
		for ($i = 0; $i < count($arreglo); $i++) {
			if ($arreglo[$i][$propiedad] == $valor) {
				return false;
			}
		}
		return true;
	}

	function crearVinculos($id_experiencia, $id_actividad_revision, $conexion){
		//Como profesor inicio una actividad de revision
		
		//Debo obtener información de mi experincia
		$datosExperiencia = dbPObtenerInfExperiencia($id_experiencia, $conexion);
		$anio = $datosExperiencia['anio'];
		//echo "anio".$anio."-";
		$semestre = $datosExperiencia['semestre'];
		//echo "semestre".$semestre."-";
		$id_diseno_didactico = $datosExperiencia['id_diseno_didactico'];
		

		//Obtener todos id de la tabla coevgrupo y el id_grupo los grupos de mi experiencia que estén en la tabla coev grupo ordenados por id grupo asc Ej [1,2,3,4]
		$GruposMiExperiencia = dbPObtenerGruposMiExperiencia($id_experiencia, $id_actividad_revision,$conexion);

		//Obtener todos los id de la tabla coevgrupo y de id_grupo los grupos de mi experiencia ordenados por id grupo asc sin tener grupo revisor 
		//$GruposMiExperienciaCopia = dbPObtenerGruposMiExperienciaCopia($id_experiencia, $id_actividad_revision,$conexion);
		//Obtener todos los id de la tabla coevgrupo y de id_grupo los grupos de mi experiencia ordenados por id grupo desc sin tener grupo revisor 
		$GruposMiExperienciaCopia = dbPObtenerGruposMiExperienciaDesc($id_experiencia, $id_actividad_revision,$conexion);
		$NumMisGr = count($GruposMiExperiencia);
		$NumGruposVinculados= 0;

		//Obtiene todos los id de la tabla coevgrupo y id_grupo de otras experiencias
		$GruposOtrasExperiencias = dbPObtenerGruposOtrasExperiencias($id_experiencia, $id_actividad_revision, $anio, $semestre, $id_diseno_didactico, $conexion);
		$NumOtrosGr = count($GruposOtrasExperiencias);
		$ConsultasVinculacion = array();

		//Después del for queda un arreglo con todos los grupos distintos.
		$GruposMiExperiencia1= array();
		for($i=0; $i<$NumMisGr; $i=$i+2){
			array_push($GruposMiExperiencia1, $GruposMiExperiencia[$i]);
		}

		// reordenar $GruposMiExperiencia1 para que los revisados queden al final
		$reordenado1 = array();
		$alFinal = array();
		for ($i = 0; $i < count($GruposMiExperiencia1); $i++) {
			if (noEstaEn($GruposMiExperiencia1[$i]['id_coev'], $GruposMiExperienciaCopia, 'id_coev')) {
                array_push($alFinal, $GruposMiExperiencia1[$i]);
			} else {
				array_push($reordenado1, $GruposMiExperiencia1[$i]);
			}
		}
		for ($i = 0; $i < count($alFinal); $i++) {
			array_push($reordenado1, $alFinal[$i]);
		}
		$GruposMiExperiencia1 = $reordenado1;
		
		$GruposMiExperiencia2= array();
		for($i=1; $i<$NumMisGr; $i=$i+2){
			array_push($GruposMiExperiencia2, $GruposMiExperiencia[$i]);
		}

		// reordenar $GruposMiExperiencia2 para que los revisados queden al final
		$reordenado2 = array();
		$alFinal = array();
		for ($i = 0; $i < count($GruposMiExperiencia2); $i++) {
			if (noEstaEn($GruposMiExperiencia2[$i]['id_coev'], $GruposMiExperienciaCopia, 'id_coev')) {
                array_push($alFinal, $GruposMiExperiencia2[$i]);
			} else {
				array_push($reordenado2, $GruposMiExperiencia2[$i]);
			}
		}
		for ($i = 0; $i < count($alFinal); $i++) {
			array_push($reordenado2, $alFinal[$i]);
		}
		$GruposMiExperiencia2 = $reordenado2;
		
		$GruposMiExperiencia= array();
		for($j=0;$j<count($GruposMiExperiencia1); $j++){
			array_push($GruposMiExperiencia, $GruposMiExperiencia1[$j]);
		}

		for($j=0;$j<count($GruposMiExperiencia2); $j++){
			array_push($GruposMiExperiencia, $GruposMiExperiencia2[$j]);
		}

		//Recorrer la lista de mis grupos
		foreach($GruposMiExperiencia as $indice => $datosrevisor){
			//echo "En Foreach recorriendo la lista de grupos: <br>";
			//Por cada grupo revisor de mi experiencia busco un revisado
			$id_grevisor = $datosrevisor['id_grevisor'];
			//echo "Id GrupoRevisor:".$id_grevisor." <br>";
			
			//Busco grupos en otras experiencias si el número de vinculaciones es menor al número de grupos de la otra experiencia
			if($NumGruposVinculados < $NumOtrosGr){
				//echo "En if <br>";
				$revisado = array_shift($GruposOtrasExperiencias); // El grupo obtenido para ser revisado se elimina del arreglo.
				if($revisado) {
					//echo "Existe revisado <br>";
					$id_coev = $revisado['id_coev'];
					//echo "id_coev: ".$id_coev."<br>";
					$cons1 = dbPInsertarRevisor($id_coev, $id_grevisor);
					//echo $cons1;
					$res1 = mysql_query($cons1) or die ("No se pudo ejecutar la consulta:". mysql_error());
					$NumGruposVinculados++;
				}
			}
			//Busco grupos en mi propia experiencia porque no quedan grupos en la experiencia gemela.
			else{
				//echo "En el else <br>";
				$id_grevisado = $id_grevisor;
				$cont = 0;
				$encontrado = false;
				//echo "id_grevisor:".$id_grevisor." id_grevisado:".$id_grevisado." cont:".$cont."num mis grupos: ".$NumMisGr." <br>";

				//While se ejecuta mientras no ha sido encontrado el grupo revisado y el grupo revisor sea el mimsmo que el grupo revisado y todavía hay grupos 
				//de mi experiencia. 
				while(!$encontrado and ($id_grevisor == $id_grevisado) and $cont< $NumMisGr){

					if(array_key_exists($cont, $GruposMiExperienciaCopia)){
						//echo "En if array key <br>";
						//Toma el primer grupo del arreglo y lo deja como revisado.
						$revisado = $GruposMiExperienciaCopia[$cont];
						$id_coev = $revisado['id_coev'];
						$id_grevisado = $revisado['id_grevisor'];
					}	
					$cont++;				
					if($id_grevisado != $id_grevisor){						
						$cons2 = dbPInsertarRevisor($id_coev, $id_grevisor);
						$res2 = mysql_query($cons2);
						if($res2){					
							//Saca el grupo de la lista ya que fue elegido como revisado 
							unset($GruposMiExperienciaCopia[$cont-1]);
							$NumGruposVinculados++;
							$encontrado = true;
						}
						else{
							//echo "No se metio al if del res2"; Salida flaite
							$id_grevisado = $id_grevisor;
						}
						
					}
					//echo "fuera del if id grevisado! a grevisor";
					// reordenar $GruposMiExperienciaCopia para que no se "cierren" las vinculaciones prematuramente
				}							
			}
			
			//echo "<br>"	;
		}
		
		$_resp = true;
	}


?> 

