
<?php
/**
 * Página de ingreso a la plataforma. Si el usuario no tiene una sesión iniciada se muestra un formulario
 * para el inicio de sesión, en el caso contrario muestra el listado de las experiencias inscritas
 * por el usuario.
 * Los datos ingresados por el usuario son validados con el método dbValidarUsuario,si los datos son válidos
 * se crea la sesión correspondiente al usuario, si no se entrega un mensaje de error.
 *
 * Los parametros solicitados para el ingreso son:
 * $_REQUEST["fl_campo_usuario"]
 * $_REQUEST["fl_campo_password"]
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Daniel Guerra - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */
$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz. "encuestas/inc/en_db_functions.inc.php");

$titulo_pagina = $lang_sufijo_titulo_paginas . $lang_pagina_ingresar;
$descripcion_pagina = $lang_mensaje_ingreso;

$error = 0;
$error_msg = "";

$salir = $_REQUEST["salir"];
$mostrar_formulario = true;

if (!is_null($salir) AND strlen($salir) > 0) {
    
    require_once($ruta_raiz . "taller_dd/inc/tdd_db_funciones.inc.php");    
    $id_usuario     = $_SESSION["klwn_id_usuario"];
    $id_sesion      = $_SESSION["id_sesion"];//Agregado por Jordan Barría el 27-10-14
    $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
    $resultado = desbloquearTodoFuncion($id_usuario, $conexion);
    dbLogCerrarSesion($id_sesion,1,$conexion);//Agregado por Jordan Barría el 27-10-14
    dbDesconectarMySQL($conexion);
    eliminarSesion();
    header("Location:ingresar.php");
} else {
    if (existeSesion ()) {
        $mostrar_formulario = false;
        $hora_actual = date("Y-n-j H:i:s");
        $tiempo_transcurrido = (strtotime($hora_actual) - strtotime($_SESSION["klwn_ultimo_acceso"]));
        if ($tiempo_transcurrido >= $config_tiempo_sesion) {
            $id_sesion      = $_SESSION["id_sesion"];//Agregado por Jordan Barría el 27-10-14
            $conexion= dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);//Agregado por Jordan
            dbLogCerrarSesion($id_sesion,3,$conexion);//Agregado por Jordan Barría el 27-10-14
            dbDesconectarMySQL($conexion);
            session_destroy();
            header("Location:ingresar.php");
        } else {
            $_SESSION["klwn_ultimo_acceso"] = $hora_actual;
        }
    }
    $msg_fallo_login = "";
    /* Se ha hecho post */
    if (!is_null($_REQUEST["fl_campo_usuario"])) {
        $usuario = $_REQUEST["fl_campo_usuario"];
        $pass = $_REQUEST["fl_campo_password"];
        $navegador_usuario=$_REQUEST["fl_navegador_usuario"];//Agregado por Jordan Barría el 27-10-14
        $sistema_operativo_usuario=$_REQUEST["fl_sistema_operativo_usuario"];
        $conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
        $_usuario = dbValidarUsuario($usuario, $pass, $conexion);
//        print_r($_usuario);
        if (is_null($_usuario)) {
            $msg_fallo_login = "<div class=\"caja_error\">" . $lang_fallo_login . "</div>\r\n";
        } else {
            if (existeSesion ()) {
                dbActualizarUltimoAcceso($_SESSION["klwn_id_usuario"], $_SESSION["klwn_ultimo_acceso"], $conexion);
                $mostrar_formulario = false;
                $_experiencias = dbObtenerExpUsuarioMin($_SESSION["klwn_usuario"], $conexion);
                if (!is_null($_experiencias)) {
                    agregarExperienciasSesion($_experiencias);
                }
            }else{
                //Codigo agregado por Jordan Barría el 27-10-14
                //$ip_sesion= $_SERVER["REMOTE_ADDR"];
                //$ip_sesion= $_SERVER["HTTP_X_FORWARDED_FOR"];
                $ip_sesion = '';
                if ($_SERVER['HTTP_CLIENT_IP'])
                    $ip_sesion = $_SERVER['HTTP_CLIENT_IP'];
                else if($_SERVER['HTTP_X_FORWARDED_FOR'])
                    $ip_sesion = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if($_SERVER['HTTP_X_FORWARDED'])
                    $ip_sesion = $_SERVER['HTTP_X_FORWARDED'];
                else if($_SERVER['HTTP_FORWARDED_FOR'])
                    $ip_sesion = $_SERVER['HTTP_FORWARDED_FOR'];
                else if($_SERVER['HTTP_FORWARDED'])
                    $ip_sesion = $_SERVER['HTTP_FORWARDED'];
                else if($_SERVER['REMOTE_ADDR'])
                    $ip_sesion = $_SERVER['REMOTE_ADDR'];
                else
                    $ip_sesion = 'UNKNOWN';

                //$ip_sesion = get_client_ip();
                $id_sesion= dbLogCrearSesion($_usuario["id_usuario"],$navegador_usuario,$ip_sesion,$sistema_operativo_usuario,$conexion);
                crearSesion($_usuario["id_usuario"], $_usuario["usuario"], $_usuario["inscribe_diseno"], $_usuario["nombre"], $_usuario["url_imagen"], $_usuario["email"],$_usuario["mostrar_correo"],$_usuario["mostrar_fecha"], $_usuario["administrador"],$id_sesion);
                $mostrar_formulario = false;
            }    
        }
        dbDesconectarMySQL($conexion);
    }
}
$pagina_cargada = "ingresar";
require_once($ruta_raiz . "inc/header.inc.php");
if ($mostrar_formulario) {
?>
    <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/md5-min.js"></script>
    <script type="text/javascript">
        function ingresar(){
            var pass = $("#fl_campo_password").val();
            md5pass = hex_md5(pass);
            $("#fl_campo_password").val(md5pass);
            $("#fl_navegador_usuario").val(detectarNavegadorUsuario());//Agregado por Jordan Barría el 27-10-14
            $("#fl_sistema_operativo_usuario").val(detectarSOUsuario());//Agregado por Jordan Barría el 01-11-14
            $("#form_login").submit();
        }
    </script>
    <div class="container_12">
        <div class="grid_3">&nbsp;</div>
        <div class="grid_6">
            <div id="contenido_login">
                <div id="intro_login"><?php echo $lang_mensaje_ingreso; ?></div>
            <?php echo $msg_fallo_login; ?>
            <form id="form_login" method="post" action="">
                <div id="caja_form_login">
                    <label><?php echo $lang_nombre_usuario; ?></label>
                    <input type="text" maxlenght="20" size="20" id="fl_campo_usuario" name="fl_campo_usuario" />
                    <div class="clear"></div>
                    <label><?php echo $lang_password; ?></label>
                    <input type="password" maxlenght="20" size="20" id="fl_campo_password" name="fl_campo_password" />
                    <div class="clear"></div>
                    <input type="hidden" id="fl_navegador_usuario" name="fl_navegador_usuario" />
                    <input type="hidden" id="fl_sistema_operativo_usuario" name="fl_sistema_operativo_usuario" />
                    <br>
                    <div id="recuperar_contrasena">
                        <a href="registro.php"><?php echo $lang_ingresar_resgistrate; ?></a><br>
                        <a href="recuperar_contrasena.php"><?php echo $lang_ingresar_olvido_contrasena; ?></a>
                    </div>                    
                    <div class="clear"></div>
                    <button onclick="javascript: ingresar();"><?php echo $lang_pagina_ingresar; ?></button>
                </div>
            </form>
        </div>
    </div>
    <div class="grid_3">&nbsp;</div>
    <div class="clear">
    </div>
</div>
<?php
        } else {
?>
            <div class="container_16">
                <div id="tabs2" class="grid_16">
                    <ul>
            <?php
            $conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
            $_experiencias = dbObtenerExpUsuario($_SESSION["klwn_usuario"], $conexion);
            dbDesconectarMySQL($conexion);
            ?>
            <li>
                <a id="tab_inicio" href="inicio.php" title="<?php echo $lang_ingresar_inicio; ?>" class="tab_home" name="Inicio"><?php echo $lang_ingresar_inicio; ?></a>
            </li>
            <li>
                <a id="tab_experiencias" href="experiencias_kelluwen.php" title="<?php echo $lang_ingresar_exp_kellu; ?>" name="Experiencias Kelluwen"><?php echo $lang_ingresar_exp_kellu; ?></a>
            </li>
            <li>
                <a id="tab_disenos" href="dis_lista_disenos.php" title ="<?php echo $lang_ingresar_dd; ?>" name="Diseños Didácticos"><?php echo $lang_ingresar_dd; ?></a>
            </li>
            <li>
                <a id="tab_inscribir" href="dis_inscribir_diseno.php" title="<?php echo $lang_ingresar_inscribir_exp; ?>" name="Inscribir Experiencia"><?php echo $lang_ingresar_inscribir_exp; ?></a>
            </li>
            <?php
            if($_SESSION["klwn_inscribe_diseno"] != 0){                
            ?>
                <li>
                    <a id="tab_taller" href="taller_disenos_didacticos.php" title="<?php echo $lang_ingresar_crear_dd; ?>" name="Taller Diseños Didácticos"><?php echo $lang_ingresar_taller_dd; ?></a>
                </li>
            <?php
            }
            ?>
            <?php
//            print_r($_SESSION);
            if($_SESSION["klwn_administrador"]==1){
            ?>
           <li>
                <a href="admin/admin_inicio.php" id="tab_admin" title="<?php echo $lang_ingresar_admin;?>" name="Administrador"><?php echo $lang_ingresar_admin; ?></a>
            </li>  
            <?php    
            }
            ?>
            <?php
//            print_r($_SESSION);
            if($_SESSION["klwn_administrador"]==3){
            ?>
           <li>
                <a href="concurso/con_form_crear_diseno.php" title="<?php echo $lang_ingresar_concurso; ?>" id="tab_concurso"><?php echo $lang_ingresar_concurso_crear_dd; ?></a>
            </li>
            <?php
            }
            ?>
        </ul>
        <?php
        }
        ?>
        <div id="dialogo_cargando">
            <img id="imagen_carga" src="<?php echo $config_ruta_img; ?>ajaxloader.gif" alt="<?php echo $lang_cargando; ?>" />
        </div>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">   
        
        function aceptaInvitacion(idUsuario, idDiseno){
            $.get('./taller_dd/tdd_aceptarInvitacion.php?id_diseno='+idDiseno+"&id_usuario="+idUsuario, function(data) {
                $('#tab_taller').click();
            });
        }
</script>
<?php
        require_once($ruta_raiz . "inc/footer.inc.php");
?>


<script type="text/javascript">
    function detectarSOUsuario(){
        var nombre_agente = navigator.userAgent;
        var so = "";
        var palabras_cliente = [
            {s:'Windows 3.11', r:/Win16/},
            {s:'Windows 95', r:/(Windows 95|Win95|Windows_95)/},
            {s:'Windows ME', r:/(Win 9x 4.90|Windows ME)/},
            {s:'Windows 98', r:/(Windows 98|Win98)/},
            {s:'Windows CE', r:/Windows CE/},
            {s:'Windows 2000', r:/(Windows NT 5.0|Windows 2000)/},
            {s:'Windows XP', r:/(Windows NT 5.1|Windows XP)/},
            {s:'Windows Server 2003', r:/Windows NT 5.2/},
            {s:'Windows Vista', r:/Windows NT 6.0/},
            {s:'Windows 7', r:/(Windows 7|Windows NT 6.1)/},
            {s:'Windows 10', r:/(Windows 10|Windows NT 10)/},
            {s:'Windows 8.1', r:/(Windows 8.1|Windows NT 6.3)/},
            {s:'Windows 8', r:/(Windows 8|Windows NT 6.2)/},
            {s:'Windows NT 4.0', r:/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
            {s:'Windows ME', r:/Windows ME/},
            {s:'Android', r:/Android/},
            {s:'Open BSD', r:/OpenBSD/},
            {s:'Sun OS', r:/SunOS/},
            {s:'Linux', r:/(Linux|X11)/},
            {s:'iOS', r:/(iPhone|iPad|iPod)/},
            {s:'Mac OS X', r:/Mac OS X/},
            {s:'Mac OS', r:/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
            {s:'QNX', r:/QNX/},
            {s:'UNIX', r:/UNIX/},
            {s:'BeOS', r:/BeOS/},
            {s:'OS/2', r:/OS\/2/},
            {s:'Search Bot', r:/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
        ];
        for (var id in palabras_cliente) {
            var cs = palabras_cliente[id];
            if (cs.r.test(nombre_agente)) {
                so = cs.s;
                break;
            }
        }

        var version_so = "";

        if (/Windows/.test(so)) {
            version_so = /Windows (.*)/.exec(so)[1];
            so = 'Windows';
        }

        switch (so) {
            case 'Mac OS X':
                version_so = /Mac OS X (10[\.\_\d]+)/.exec(nombre_agente)[1];
                break;

            case 'Android':
                version_so = /Android ([\.\_\d]+)/.exec(nombre_agente)[1];
                break;

            case 'iOS':
                version_so = /OS (\d+)_(\d+)_?(\d+)?/.exec(nombre_agente);
                version_so = version_so[1] + '.' + version_so[2] + '.' + (version_so[3] | 0);
                break;
        }

        return so+" "+version_so;
    }

    function detectarNavegadorUsuario(){
        var nro_version = navigator.appVersion;
        var nombre_agente = navigator.userAgent;
        var nombre_navegador  = navigator.appName;
        var version_full  = ''+parseFloat(navigator.appVersion); 
        var version_mayor = parseInt(navigator.appVersion,10);
        var nombre_offset,version_offset,ix;

        // En Opera, la verdadera version es despues de "Opera" o despues de "Version"
        if ((version_offset=nombre_agente.indexOf("Opera"))!=-1) {
         nombre_navegador = "Opera";
         version_full = nombre_agente.substring(version_offset+6);
         if ((version_offset=nombre_agente.indexOf("Version"))!=-1) 
           version_full = nombre_agente.substring(version_offset+8);
        }
        // En IE, la verdadera version es despues de "MSIE" en userAgent
        else if ((version_offset=nombre_agente.indexOf("MSIE"))!=-1) {
         nombre_navegador = "Microsoft Internet Explorer";
         version_full = nombre_agente.substring(version_offset+5);
        }

        //Modificado el 01-11-14
        else if ((version_offset=nombre_agente.indexOf("Trident/6.0"))!=-1) {
         nombre_navegador = "Microsoft Internet Explorer";
         version_full = "10";
        }

        else if ((version_offset=nombre_agente.indexOf("Trident/7.0"))!=-1) {
         nombre_navegador = "Microsoft Internet Explorer";
         version_full = "11";
        }

        // En Chrome, la version real va despues de "Chrome"
        else if ((version_offset=nombre_agente.indexOf("Chrome"))!=-1) {
         nombre_navegador = "Chrome";
         version_full = nombre_agente.substring(version_offset+7);
        }
        // En Safari, la verdadera versión va despues de "Safari" od despues de "Version" 
        else if ((version_offset=nombre_agente.indexOf("Safari"))!=-1) {
         nombre_navegador = "Safari";
         version_full = nombre_agente.substring(version_offset+7);
         if ((version_offset=nombre_agente.indexOf("Version"))!=-1) 
           version_full = nombre_agente.substring(version_offset+8);
        }
        // En Firefox, la verdadera versión va despues de "Firefox"
        else if ((version_offset=nombre_agente.indexOf("Firefox"))!=-1) {
         nombre_navegador = "Firefox";
         version_full = nombre_agente.substring(version_offset+8);
        }
        // En la mayoría de los otros navegadores, "nombre/version" va al final de userAgent 
        else if ( (nombre_offset=nombre_agente.lastIndexOf(' ')+1) < 
                  (version_offset=nombre_agente.lastIndexOf('/')) ) 
        {
         nombre_navegador = nombre_agente.substring(nombre_offset,version_offset);
         version_full = nombre_agente.substring(version_offset+1);
         if (nombre_navegador.toLowerCase()==nombre_navegador.toUpperCase()) {
          nombre_navegador = navigator.appName;
         }
        }
        // Corta version_full hasta un punto y coma o espacio si es que lo presenta
        if ((ix=version_full.indexOf(";"))!=-1)
           version_full=version_full.substring(0,ix);
        if ((ix=version_full.indexOf(" "))!=-1)
           version_full=version_full.substring(0,ix);

        version_mayor = parseInt(''+version_full,10);
        if (isNaN(version_mayor)) {
         version_full  = ''+parseFloat(navigator.appVersion); 
         version_mayor = parseInt(navigator.appVersion,10);
        }
        return nombre_navegador+' '+version_full;
    }
    
</script>