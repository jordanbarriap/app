<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function quitar_espacios_dobles($cadena)
{
	$limpia    = '';
	$parts    = array();
	// dividir la cadena con todos los espacios que exista
	$parts = split(' ',$cadena);
	foreach($parts as $subcadena)
	{
		// de cada subcadena elimino sus espacios a los lados
		$subcadena = trim($subcadena);
		// Unimos con un espacio para rearmar la cadena pero omitiendo los que sean espacio en blanco
		if($subcadena!='')
		{
			$limpia .= $subcadena.' ';
		}
	}
	$limpia = trim($limpia);
	return $limpia;
}
function generar_clave_aleatoria(){ 
       $cadena="[^0-9]"; 
       return substr(eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())), 
       0, 6); 
} 
?>
