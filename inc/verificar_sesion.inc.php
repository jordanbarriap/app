<?php
if (!existeSesion ()) {
?>
    <script type="text/javascript">
        window.top.location="ingresar.php";
    </script>
<?php
} else {
    $hora_actual = date("Y-n-j H:i:s");
    $tiempo_transcurrido = (strtotime($hora_actual) - strtotime($_SESSION["klwn_ultimo_acceso"]));

    if ($tiempo_transcurrido >= $config_tiempo_sesion) {
        require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");
        $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
        desbloquearTodoFuncion($_SESSION["klwn_id_usuario"], $conexion);
        $id_sesion      = $_SESSION["id_sesion"];//Agregado por Jordan Barría el 27-10-14
        dbLogCerrarSesion($id_sesion,3,$conexion);//Agregado por Jordan Barría el 27-10-14
        dbDesconectarMySQL($conexion);

        session_destroy();

?>
        <script type="text/javascript">
            expulsar_sesion=true;
            window.top.location="ingresar.php";
        </script>
<?php
    } else {
        $_SESSION["klwn_ultimo_acceso"] = $hora_actual;
    }
}
?>