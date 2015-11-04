<?php
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

if(!is_null($_REQUEST["grupos"])){
    $publicando_grupo = $_REQUEST["grupo"];
    $limite_inferior = (($publicando_grupo - 1)*10);     
    $_solicitudes = dbAdminConsultarSolicitudesColaboradoresRespondidasAgrupadas($limite_inferior, $conexion);
}
else{
    $num_respondidas = dbAdminConsultarNumSolicitudesColaboradoresRespondidas($conexion);
    $grupos = intval($num_respondidas/10);
    $resto = $num_respondidas%10;
    if($resto > 0){
        $grupos++;
    }
    $publicando_grupo = 1;
    $_solicitudes = dbAdminConsultarSolicitudesColaboradoresRespondidasAgrupadas(0, $conexion);
}


$accion = "";
$texto_accion = "";
dbDesconectarMySQL($conexion);
if(!is_null($_solicitudes)){
    foreach ($_solicitudes as $solicitud){
        if($solicitud["accion"]==0){
            $accion = "borrar";
            $texto_accion = $lang_admin_borrar;
        }
        else{
            $accion = "agregar";
            $texto_accion = $lang_admin_agregar;
        }
        if($solicitud["estado"]==1){
            $respuesta = "Aceptada";
            $texto_respuesta = $lang_admin_aceptada;
        }
        else if($solicitud["estado"]==2){
            $respuesta = "Rechazada";
            $texto_respuesta = $lang_admin_rechazada;
        }
    ?>
        <div id="admin_bloque_solicitud_<?php echo $solicitud["id_solicitud"];?>" class="admin_bloque_solicitud">
            <p id="admin_bloque_solicitud_error_<?php echo $solicitud["id_solicitud"];?>"></p>
            <p>
                <span class="admin_solicitante"><?php echo $solicitud["nombre_solicitante"]?></span><?php echo " solicit&oacute "?> <span class="admin_accion"><?php echo $texto_accion;?></span> <?php echo $lang_admin_a; ?> <span class="admin_colaborador"><?php echo$solicitud["nombre_colaborador"];?></span> <?php echo $lang_admin_de_la_experiencia; ?> <span class="admin_exp"><?php echo $solicitud["nombre_dd"];?></span> 
            </p>
            <div class="msg_datos">
                  <div class="fecha">
                      <?php echo $texto_respuesta." ".$lang_admin_el." ".formatearFecha($solicitud["fecha_envio"])." ".$lang_admin_por." ".$solicitud["nombre_admin_responde"] ;?>
                  </div>
             </div>
            </br>
        </div>
        
    <?php
    }
        if($publicando_grupo < $grupos){
        ?>
                <div class ="admin_colaborador_ver_mas" id="solicitudes_vermas_<?php echo $publicando_grupo+1;?>">
                    <button class="admin_colaborador_ver_mas_boton" onclick="javascript: adminSolicitudesVerMas(<?php echo $grupos;?>,<?php echo $publicando_grupo+1;?>);"><?php echo $lang_admin_ver_mas; ?></button>
                </div>
        <?php
        }  
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
    function adminSolicitudesVerMas(grupos, grupo){
        $.get('admin/admin_solicitudes_pasadas.php?grupos='+grupos+'&grupo='+grupo, function(data) { 
            $('#solicitudes_vermas_'+grupo).html(data);
        });
    }
    $(document).ready(function(){
        

            
    });
</script>
