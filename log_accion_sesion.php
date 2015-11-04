<?php

	
	if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

	$ruta_raiz = "./";
	require_once($ruta_raiz . "conf/config.php");
	require_once($ruta_raiz . "inc/all.inc.php");
	//require_once($ruta_raiz."inc/verificar_sesion.inc.php");

	$accion=$_REQUEST["accion"];
	$id_sesion=$_REQUEST["id_sesion"];

	if ($id_sesion!=""){
		//Registra cuando un usuario cierra su navegador y por ende su sesión activa
		if ($accion=="cerrar_navegador"){
			$conexion=dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
			dbLogCerrarSesion($id_sesion,2,$conexion);
			dbDesconectarMySQL($conexion);
			salirActualPaginaKelluwen();
			echo 1;
		//Registra cuando un usuario clickea una sección dentro de la plataforma de Kelluwen (secciones guardadas al clickear están en archivo /inc/header.inc.php)
		}elseif($accion=="click_seccion"){
			$nombre_seccion=$_REQUEST["nombre_seccion"];
			$conexion=dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
			dbLogClickVisitaSeccion($id_sesion,$nombre_seccion,$conexion);
			dbDesconectarMySQL($conexion);
			echo 1;
		//Borra aquellos falsos positivos que se pueden dar a lugar en el registro de cierres de sesión
		}elseif($accion=="revertir_cierre"){
			$conexion=dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
			dbLogRevertirCierreSesion($id_sesion,$conexion);
			dbDesconectarMySQL($conexion);
			echo 1;
		//Registra los clicks sobre los elementos clickeables de la herramienta visualización
		}elseif($accion=="vis_detalle_info"){
			$id_experiencia=$_REQUEST["id_experiencia"];
			$id_elemento=$_REQUEST["id_elemento"];
			$accion_detalle=$_REQUEST["accion_detalle"];
			$vista_activa=$_REQUEST["vista"];
			$perspectiva_activa=$_REQUEST["perspectiva"];
			$tamano_elemento=$_REQUEST["tamano_elemento"];
			$zoom_activo=$_REQUEST["zoom_activo"];
			$conexion=dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
			dbLogVisualizacionAccesoDetalleInfo($id_sesion,$id_experiencia,$id_elemento,$accion_detalle,$vista_activa,$perspectiva_activa,$tamano_elemento,$zoom_activo,$conexion);
			dbDesconectarMySQL($conexion);
			echo 1;
		//Registra cuando un usuario cambia de la vista general a la vista centrada en un usuario
		}elseif($accion=="vis_cambio_vista"){
			$id_experiencia=$_REQUEST["id_experiencia"];
			$tipo_cambio_vista=$_REQUEST["tipo_cambio_vista"];
			$vista_transicion=$_REQUEST["vista_transicion"];
			$perspectiva_transicion=$_REQUEST["perspectiva_transicion"];
			$conexion=dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
			dbLogVisualizacionCambioVista($id_sesion,$id_experiencia,$tipo_cambio_vista,$vista_transicion,$perspectiva_transicion,$conexion);
			dbDesconectarMySQL($conexion);
			echo 1;
		}else{
			echo 0;
		}
	}else{
		//Registra el momento en el cual se le despliega el tutorial de ayuda al usuario activo (para que no se le vuelva a mostrar automáticamente la próxima vez)
		if ($accion="despliegue_ayuda"){
			$id_usuario=$_REQUEST["id_usuario"];
			$despliegue_ayuda_clase=$_REQUEST["ayuda_clase"];
			$despliegue_ayuda_selfcentered=$_REQUEST["ayuda_selfcentered"];
			$conexion=dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
			$resp=dbLogActualizarDespliegueAyuda($id_usuario,$despliegue_ayuda_clase,$despliegue_ayuda_selfcentered,$conexion);
			dbDesconectarMySQL($conexion);
			echo $resp;
		}else{
			echo 0;
		}
	}
	

?>