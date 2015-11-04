<?php

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "../";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz . "inc/verificar_sesion.inc.php");
require_once($ruta_raiz."admin/inc/admin_db_functions.inc.php");
require_once($ruta_raiz."admin/inc/admin_functions.inc.php");

$modo = $_REQUEST["modo"]; /*modo = 0 carga de los 10 primeros usuarios   modo = 1 carga según limite superior e inferior, para implementar ver más*/
?>
    <div class="admin_volver_administrador">
    <input class="admin_usuario_boton_volver_inicio" type="button" value="<?php echo $lang_admin_volver_admin_gral; ?>" onclick="javascript: volverAdminInicio();">
    <br>
    </div>
    <div class="admin_filtros_usuario">
        <ul class="admin_bloque_filtros_usuarios">
            <li id="admin_filtro_todos" class="admin_li_filtro_usuario admin_selected"><a class="admin_filtro_usuario " id = "admin_filtro_todos"><?php echo $lang_admin_todos; ?></a> </li>
            <li class="admin_li_filtro_usuario"><a class="admin_filtro_usuario" id = "admin_filtro_profesores"><?php echo $lang_admin_profesores; ?></a> </li>
            <li class="admin_li_filtro_usuario"><a class="admin_filtro_usuario" id = "admin_filtro_colaboradores"><?php echo $lang_admin_colaboradores; ?></a> </li>
            <li class="admin_li_filtro_usuario"><a class="admin_filtro_usuario" id = "admin_filtro_busqueda"><?php echo $lang_admin_busqueda; ?></a></li>
            <li class="admin_li_filtro_usuario"><a class="admin_filtro_usuario" id = "admin_filtro_crear_usuario"><?php echo $lang_admin_crear_usuario; ?></a></li>
        </ul>
    </div>
    </br>
    <div class="clear"></div>
    <div class="admin_listado_usuarios"></div>

<script type="text/javascript">

    function cargarUsuariosDefecto(){
        $.get('admin/admin_usuarios_listado.php?modo=0', function(data) { 
          $('.admin_listado_usuarios').html(data);
        });
    }
    function cargarProfesores(){
        $.get('admin/admin_usuarios_listado.php?modo=1', function(data) { 
          $('.admin_listado_usuarios').html(data);
        });
    }
    function cargarColaboradores(){
        $.get('admin/admin_usuarios_listado.php?modo=2', function(data) { 
          $('.admin_listado_usuarios').html(data);
        });
    }
    function busquedaUsuario(){

        $.get('admin/admin_usuario_busqueda.php?', function(data) { 
          $('.admin_listado_usuarios').html(data);
        });
    }
    function crearUsuario(){

        $.get('admin/admin_usuario_crear.php?', function(data) { 
          $('.admin_listado_usuarios').html(data);
        });
    }
    function volverAdminInicio(){
        $.get('admin/admin_inicio.php?', function(data) {
            $('.admin_contenido').html(data);
        });
    }
    $(document).ready(function(){
        cargarUsuariosDefecto();
        
        $('#admin_filtro_todos').click(function() {
            cargarUsuariosDefecto();
        });
        $('#admin_filtro_profesores').click(function() {
            cargarProfesores();
        });
        $('#admin_filtro_colaboradores').click(function() {
            cargarColaboradores();
        });
        $('#admin_filtro_busqueda').click(function() {
             busquedaUsuario();
        });
        $('#admin_filtro_crear_usuario').click(function() {
             crearUsuario();
        });
        $(".admin_bloque_filtros_usuarios a").click(function(){
            $(this).parent().addClass('admin_selected'). 
            siblings().removeClass('admin_selected');
        });
        

    });
</script>