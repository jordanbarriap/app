<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");


$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_solicitudes_pendientes = dbAdminConsultarSolicitudesColaboradoresPendientes($conexion);
$num_solicitudes_respondidas = dbAdminConsultarNumSolicitudesColaboradoresRespondidas($conexion);
$num_solicitudes_pendientes = count($_solicitudes_pendientes);

$id_usuario = $_REQUEST['id_usuario'];

?>

<div class="container_16">
    <div class ="admin_contenido">
        <div class="grid_16">
            <?php
            if($num_solicitudes_pendientes > 0){
            ?>
                <button id="admin_solicitudes" class="admin_bonton_ver_solicitudes_pendientes" onclick="javascript:adminMostrarSolicitudes();"><?php echo $lang_admin_ver_solicitudes_pend; ?></button>
            <?php   
            }
            else{
                if($num_solicitudes_respondidas>0){
                    ?>
                    <button id="admin_solicitudes" class="admin_bonton_ver_solicitudes" onclick="javascript:adminMostrarSolicitudes();"><?php echo $lang_admin_ver_solicitudes; ?></button>
                    <?php
                }
            }
            ?>
            
            <p class="admin_intro_incio"><?php echo $lang_admin_escoja_admin; ?></p>
            <button class="admin_boton_escoger_administrador" onclick="javascript:adminAdministrarExperiencias();"><?php echo $lang_admin_experiencias; ?></button>
            <button class="admin_boton_escoger_administrador" onclick="javascript:adminAdministrarUsuarios();"><?php echo $lang_admin_usuarios; ?></button>
            <button class="admin_boton_escoger_administrador" onclick="javascript:adminAdministrarDisenos();"><?php echo $lang_admin_disenos; ?></button>
            <button class="admin_boton_escoger_administrador" onclick="javascript:adminAdministrarHerramientaReportes();"><?php echo $lang_re_herramienta_de_reportes?></button>
            <button class="admin_boton_escoger_administrador" onclick="javascript:adminAdministrarHerramientaEncuestas();"><?php echo $lang_he_herramienta_de_encuestas?></button>
            <button class="admin_boton_escoger_administrador" onclick="javascript:adminVerEstadisticas();"><?php echo $lang_estadisticas?></button>
        </div>
    </div>
</div>
<?php
dbDesconectarMySQL($conexion);
?>
<script type="text/javascript">

    function adminAdministrarExperiencias(){
        $.get('admin/admin_experiencias.php?origen=0', function(data) {                  
          $('.admin_contenido').html(data);
        });
    }

    function adminAdministrarUsuarios(){
        $.get('admin/admin_usuarios.php?modo=0', function(data) {                  
          $('.admin_contenido').html(data);
        });
    }

    function adminAdministrarDisenos(){
        $.get('admin/admin_disenos.php', function(data) {
          $('.admin_contenido').html(data);
        });
    }

    function adminAdministrarHerramientaReportes(){
        $.get('admin/admin_herramienta_reportes.php?id_usuario=<?php echo $id_usuario?>',function(data){
            $('.admin_contenido').html(data);
        });
    }

    function adminAdministrarHerramientaEncuestas(){
        $.get('admin/admin_herramienta_encuestas.php',function(data){
            $('.admin_contenido').html(data);
        });
    }

    function adminMostrarSolicitudes(){
        var $dialog = $('<div id=\"admin_exp_ventana_solicitudes\"></div>')
        .load('admin/admin_solicitudes_despliegue.php')
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_admin_ver_solicitudes; ?>',
            width: 900,
            heigth: 600,
            modal: true,
            resizable: true,
            dialogClass:'dialogBotonesVerSolicitudes',
            buttons: {
                //CERRAR
                "<?php echo $lang_admin_cerrar; ?>": function() {
                    $(this).dialog('destroy').remove();
                }
            },
            close: function() {
            }
            });
        $dialog.dialog('open');
    }

    function adminVerEstadisticas(){

    }

    $(document).ready(function(){


    });
</script>



