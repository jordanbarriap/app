<?php

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_solicitudes = dbAdminConsultarSolicitudesColaboradoresPendientes($conexion);
$_solicitudes_respondidas = dbAdminConsultarNumSolicitudesColaboradoresRespondidas($conexion);
$accion = "";
dbDesconectarMySQL($conexion);
if(!is_null($_solicitudes)){
    echo "</br><p>".$lang_admin_solicitudes_pendientes."</p>";
    foreach ($_solicitudes as $solicitud){
        if($solicitud["accion"]==0){
            $accion = "borrar";
            $texto_accion = $lang_admin_borrar;
        }
        else{
            $accion = "agregar";
            $texto_accion = $lang_admin_agregar;
        }
    ?>
        <div id="admin_bloque_solicitud_<?php echo $solicitud["id_solicitud"];?>" class="admin_bloque_solicitud">
            <p id="admin_bloque_solicitud_error_<?php echo $solicitud["id_solicitud"];?>"></p>
            <p>
                <span class="admin_solicitante"><?php echo $solicitud["nombre_solicitante"]?></span><?php echo $lang_admin_solicita; ?> <span class="admin_accion"><?php echo $texto_accion;?></span> <?php echo $lang_admin_a; ?> <span class="admin_colaborador"><?php echo$solicitud["nombre_colaborador"];?></span> <?php echo $lang_admin_de_la_experiencia; ?> <span class="admin_exp"><?php echo $solicitud["nombre_dd"];?></span> 
            </p>
            <div class="msg_datos">
                  <div class="fecha">
                      <?php echo "".$lang_admin_enviada_el.formatearFecha($solicitud["fecha_envio"]) ;?>
                  </div>
             </div>
            <button class="admin_boton_aceptar" id="admin_solicitud_si_<?php echo $solicitud["id_solicitud"];?>" onclick="javascript:adminSolicitudesAceptar(<?php echo $solicitud["id_solicitud"];?>,<?php echo $solicitud["accion"];?>,<?php echo $solicitud["id_colaborador"];?>,<?php echo $solicitud["id_experiencia"];?>);"><?php echo $lang_admin_aceptar; ?></button>
            <button class="admin_boton_rechazar" id="admin_solicitud_no_<?php echo $solicitud["id_solicitud"];?>" onclick="javascript:adminSolicitudesRechazar(<?php echo $solicitud["id_solicitud"];?>,<?php echo $solicitud["accion"];?>,<?php echo $solicitud["id_colaborador"];?>,<?php echo $solicitud["id_experiencia"];?>);"><?php echo $lang_admin_rechazar; ?></button>
            </br>
        </div>
        
    <?php
    }
}
else{
    echo $lang_admin_solicitudes_disponibles;
}
if($_solicitudes_respondidas>0){?>
    <div class="admin_ver_sol_pasadas" onclick="javascript: cargarSolicitudesPasadas();"><?php echo $lang_admin_solicitudes_respondidas; ?></div>
    <div id="admin_solicitudes_pasadas" ></div>
<?php    
}
?>
<script type="text/javascript">

    function adminSolicitudesAceptar(id_solicitud, accion, id_colaborador, id_experiencia){
        
        $.get('admin/admin_solicitudes_responder.php?id_solicitud='+id_solicitud+"&estado=1&accion="+accion+"&codeexp="+id_experiencia+"&id_colaborador="+id_colaborador, function(data) {
            if(data ==1 ){
                $('#admin_bloque_solicitud_'+id_solicitud).html("<p><?php echo $lang_admin_cambio_exitoso; ?></p>");
            }
            else{
                $('#admin_bloque_solicitud_error_'+id_solicitud).html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
            }
          
        });
    }
    function adminSolicitudesRechazar(id_solicitud, accion, id_colaborador, id_experiencia){
        $.get('admin/admin_solicitudes_responder.php?id_solicitud='+id_solicitud+"&estado=2&accion="+accion+"&codeexp="+id_experiencia+"&id_colaborador="+id_colaborador, function(data) {
            if(data ==1 ){
                $('#admin_bloque_solicitud_'+id_solicitud).html("<p><?php echo $lang_admin_cambio_exitoso; ?></p>");
            }
            else{
                $('#admin_bloque_solicitud_error_'+id_solicitud).html("<p><?php echo $lang_admin_problema_cambio; ?></p>");
            }
          
        });
    }
    function cargarSolicitudesPasadas(){
        $.get('admin/admin_solicitudes_pasadas.php?', function(data) {                  
          $('#admin_solicitudes_pasadas').html(data);
        });
    }
    
    $(document).ready(function(){
        

            
    });
</script>