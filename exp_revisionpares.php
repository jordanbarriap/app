<?php
/**
*Carga de menu experiencia para herrmaienta de trabajos del alumno.
*
* LICENSE: código fuente distribuido con licencia LGPL
*
* @author  Sergio Bustamante M. - Kelluwen
* @copyleft Kelluwen, Universidad Austral de Chile
* @license www.kelluwen.cl/app/licencia_kelluwen.txt
* @version 0.1
*
**/  

//Llamada a archivos necesarios
$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."revpares/inc/rp_db_functions.inc.php");
require_once($ruta_raiz."inc/db_functions.inc.php");

//variables por url
$id_experiencia       = $_REQUEST["codexp"];
$es_estudiante         = $_REQUEST["es_estudiante"];

//obtener identificacion de usuario
$usuario = $_SESSION["klwn_usuario"];

$conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);

//obtener identificacion de usuario
$id_mi_usuario = dbRPObtenerIdUsuario($usuario,$conexion);

//obtener grupo al que pertenece usuario
//$_mi_grupo = dbObtenerGrupoUsuario($usuario, $id_experiencia, $conexion);

//obtener info de experiencia
$info_exp = dbExpObtenerInfo($id_experiencia, $conexion);

if($es_estudiante){//Obtener el grupo al que pertenece el usuario y sus grupo gemelo, si es que existe
    $grupo_usuario = dbObtenerGrupoUsuario($usuario, $id_experiencia, $conexion);
    $integrantes_grupo_usuario = dbExpObtenerEstudiantesPorGrupo($grupo_usuario["id_grupo"], $conexion);
    $etiqueta_grupo = $grupo_usuario["et_grupo"];
    if(!is_null($grupo_usuario["et_gemela"])){ // Obtener el grupo gemelo del grupo, si es que existe
        $grupo_gemelo_usuario = dbObtenerGrupoGemeloUsuario($grupo_usuario["id_grupo"],$grupo_usuario["et_gemela"] , $conexion);
        $i=0;
        $integrantes_grupo_gemelo = array();
        while ($grupo_gemelo_usuario[$i]){
            $_integrantes_grupo_gemelo[$i] = dbExpObtenerEstudiantesPorGrupo($grupo_gemelo_usuario[$i]["id_grupo"], $conexion);
            $i++;
        }
        if(!is_null($grupo_gemelo_usuario[0]["id_grupo"])){
            $etiqueta_grupo_gemelo = $grupo_usuario["et_gemela"];
        }
    }
}

?>

<!--
    Definicion del div para la herramienta de trabajos
-->
<!--<div class="rp_herramienta">-->
<?php
//Bloque de información sobre el grupo al que pertenece el usuario y su grupo gemelo
    if ($es_estudiante && !is_null($grupo_usuario["nombre"])){
    ?>
    <div class="rp_inicio_izquierda">
        <!--Boton nuevo trabajo-->
        <div id="trabajo_nuevo">
          <div class="rp_ubica_link">
              <a class="link_nuevo_trabajo" id="link_nuevo_trabajo" href="revpares/rpCuadroIngresaTrabajo.php?id_experiencia=<?php echo $id_experiencia;?>&id_usuario=<?php echo $id_mi_usuario;?>&id_grupo=<?php echo $grupo_usuario['id_grupo']?>&et_exp=<?php echo $etiqueta_exp;?>&et_gemela=<?php echo $etiqueta_gemela;?>&es_estudiante=<?php echo $es_estudiante;?>">
                  <strong><?php echo $lang_rp_boton_nuevo_trabajo;?></strong>
              </a>
          </div>
      </div>
        <!--Pestanas de navegacion cargadas desde rpprestanas-->
        <div id="rp_pestanas">
        </div>
        <!--Muestra integrantes del grupo-->
        
            <div class="rp_cuadro_grupo">
                <div class="rp_cabecera_info_grupos rp_margen_izquierda rp_destaca_revisor rp_margen_bottom">
                    <a class="rp_info_grupos_ver " id="rp_info_grupos_ver" href="#">
                        <img src="<?php echo $config_ruta_img.'flecha_c.png'?>"/>
                        <?php echo $lang_bitacora_mi_grupo.": ".obtenerNombreGrupo($grupo_usuario["nombre"]);?>
                    </a>
                    <a class="rp_info_grupos_ver" id="rp_info_grupos_ocultar" href="#">
                        <img src="<?php echo $config_ruta_img.'flecha_a.png'?>">
                        <?php echo $lang_bitacora_mi_grupo.": ".obtenerNombreGrupo($grupo_usuario["nombre"]);?>
                    </a>
                </div>
                <div class="rp_bloque_integrantes_mi_grupo" id="rp_info_integrantes_mi_grupo">
                <?php
                if(!is_null($integrantes_grupo_usuario)){
                    foreach($integrantes_grupo_usuario as $datos_usuario){
                        $imagen_usuario_grupo = darFormatoImagen($datos_usuario["url_imagen"], $config_ruta_img_perfil, $config_ruta_img);
                        ?>
                        <div class="rp_datos_integrante">
                            <div class="rp_integrante_img rp_margen_bottom">
                                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $datos_usuario["usuario"];?>" class="rp_img_integrantes_info_grupo" title="<?php echo $datos_usuario["usuario"];?>" >
                                    <img class="rp_imagen_bloque_infg" src="<?php echo $imagen_usuario_grupo["imagen_usuario"];?>"/>
                                </a>
                                <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $datos_usuario["usuario"];?>" style="" class="rp_img_integrantes_info_grupo" title="<?php echo $datos_usuario["usuario"];?>" >
                                    <div class="rp_nombre_integrantes_bloque_infg"> <?php echo $datos_usuario["nombre_usuario"];?></div>
                                </a>
                            </div>
                        </div>
                <?php
                    }
                }
               ?>
                </div>
            </div>
            
        <!--Cuadro guia-->
        <div id="rp_cuadro_guia">
            <?php echo $lang_rp_portafolio_mi_grupo_explica;?>
        </div>
    </div>
    <!--Div a la derecha donde seran desplegadas las diferentes pestanas-->
    <div id="rp_inicio_bloque_central">
    </div>
<!--</div>-->
    <?php
    }
    else{
    ?>
    <div class="rp_aviso_no_asignado">
       <?php echo $lang_rp_usuario_sin_grupo?>
    </div>

    <?php
    }
    ?>

<!--Llamada al plugin guia para completar campos-->
<script type="text/javascript">

//Funcion que invoca el archivo php para desplegar trabajo en cuadro producto
function desplegarTrabajo(id_actividad,producto,grupo,usuario,revisa){
    if (revisa === undefined) revisa = 0;
    url = "revpares/rpDespliegaTrabajo.php?id_actividad="+id_actividad+"&id_producto="+producto+"&id_grupo="+grupo+"&id_usuario="+usuario+"&revisa="+revisa;
    $.post(url, function(data){
        $('#cuadro_producto').html(data);
    });
    return false;
}

//Funcion que invoca formulario php para ingresar producto
function ingresarTrabajo(experiencia,usuario,grupo){
    url = "revpares/rpCuadroIngresaTrabajo.php?id_experiencia="+experiencia+"&id_usuario="+usuario+"&id_grupo="+grupo;
    $.post(url,function(data){
        $('#cuadro_producto').html(data);
    });
}

//Funcion que despliega formulario de evaluacion
function evaluarActividad(grevisor,vinculo){
    url = "revpares/rpCuadroIngresaEvaluacion.php?id_grevisor="+grevisor+'&id_vinculo='+vinculo;
    $.get(url,function(data){
        $('#rp_inicio_bloque_central').html(data);
    });
}

//Funcionq ue despliega pantalla con los resultados de la coevaluacion
function desplegarEvaluacion(actividad,experiencia,grevisor1,grevisor2,vinculo){
    if (grevisor1 === undefined) grevisor1 = 0;
    if (grevisor2 === undefined) grevisor2 = 0;
    url = "revpares/rpCuadroDespliegaEvaluacion.php?id_actividad="+actividad+"&id_experiencia="+experiencia+"&id_grevisor1="+grevisor1+"&id_grevisor2="+grevisor2+"&id_vinculo="+vinculo;
    $.post(url,function(data){
        $('#rp_inicio_bloque_central').html(data);
    });
}

function cargaPestanas(grupo,experiencia,pestana_inicial){
     url ='revpares/rpPestanas.php?id_grupo='+grupo+'&id_experiencia='+experiencia+'&pestana_inicial='+pestana_inicial;
     $.post(url,function(data){
       $('#rp_pestanas').html(data);
    });
}

//Funcion para cargar resumen para vision de alumno con trabajos de su grupo
function cargaResumenAlumno(experiencia,grupo){
    url = 'revpares/rp_resumen_alumno.php?codexp='+experiencia+'&id_grupo='+grupo;
    $.post(url,function(data){
       $('#rp_inicio_bloque_central').html(data);
    });
}

//Funcion para cargar evaluacion de trabajos
function cargaEvaluacionesAlumnos(experiencia,grupo){
    url = 'revpares/rpEvaluacionesAlumno.php?codexp='+experiencia+'&id_grupo='+grupo;
    $.post(url,function(data){
       $('#rp_inicio_bloque_central').html(data);
    });
}

//Funcion para cargar otras experiencias y sus trabajos
function cargaTrabajosOtrasClases(experiencia){
    url = 'revpares/rpDespliegaTrabajosOtrasClases.php?codexp='+experiencia;
    $.post(url,function(data){
       $('#rp_inicio_bloque_central').html(data);
    });
}

//Funcion para cargar texto y actualizar cuadro guia
function cargaTextoGuia(identificador_texto){
    url = 'revpares/rpTextosGuias.php?identificador_texto='+identificador_texto;
    $.post(url,function(data){
       $('#rp_cuadro_guia').html(data);
    });
}

function cargaElementos(id_grupo,id_experiencia){
    if (id_grupo == undefined){

    }
    else{
         cargaPestanas(id_grupo, id_experiencia,1);

        //carga del contenido de la primera pestana
        cargaResumenAlumno(id_experiencia,id_grupo);
    }
}

        var $dialogo2;

        //definicion de modal para nuevo trabajo
        $('.link_nuevo_trabajo').click(function() {
        var $linkc = $(this);
        $dialogo2 = $('<div></div>')
        .load($linkc.attr('href'))
        .dialog({
            autoOpen: false,
            title: '<?php echo $lang_rp_nuevo_trabajo;?>',
            width: 800,
            height: 290,
            modal: true,
            close: function(ev, ui) {
                $(this).remove();
            }
        });
        $dialogo2.dialog('open');
        return false;
    });

    $('.rp_img_integrantes_info_grupo').click(function() {
            var $linkc = $(this);
            var $dialog = $('<div></div>')
            .load($linkc.attr('href'))
            .dialog({
                autoOpen: false,
                title: '<?php echo $lang_perfil_usuario_titulo_ventana;?>',
                width: 800,
                height: 600,
                modal: true,
                buttons: {
                    "<?php echo $lang_exp_revpares_cerrar; ?>": function() {
                    $(this).dialog("close");
                    }
                },
                close: function(ev, ui) {
                    $(this).remove();
                }
            });
            $dialog.dialog('open');
            return false;

       });

$(document).ready(function(){
    //var $inicio_tabes = $('#tabs2').tabs();
    var fecha_termino = '<?php echo $info_exp['fecha_termino'];?>';
    //verificar si la experiencia ha terminado para desactivar link
//    console.log(fecha_termino);
    if(fecha_termino != ''){
       $('#link_nuevo_trabajo').attr('href','#');
       $('.link_nuevo_trabajo').unbind();
       $('.link_nuevo_trabajo').css('color','#D8D8D8');

    }

        $("#rp_info_grupos_ocultar").hide();
        $('#rp_info_integrantes_mi_grupo').hide();
        $('#rp_info_grupos_ver').click(function(){
            $('#rp_info_grupos_ver').hide();
            $('.rp_bloque_integrantes_mi_grupo').slideDown('normal');
            $('#rp_info_grupos_ocultar').show();
            return false;
        });
        $('#rp_info_grupos_ocultar').click(function(){
            $('#rp_info_grupos_ocultar').hide();
            $('.rp_bloque_integrantes_mi_grupo').slideUp('normal');
            $('#rp_info_grupos_ver').show();
            return false;
        });

        //funciones detener
        detenerBitacoraNM();
        detenerMuralDisenoNM();

//        var grupo = '<?php// echo $grupo_usuario['id_grupo'];?>';
//        console.log(grupo+'adadaa');
//
//        if( grupo != ''){
//            //cargar pestanas del menu
//            cargaPestanas(<?php //echo $grupo_usuario['id_grupo'];?>, <?php// echo $id_experiencia;?>,1);
//
//            //carga del contenido de la primera pestana
//            cargaResumenAlumno(<?php// echo  $id_experiencia?>,<?php// echo $grupo_usuario['id_grupo']?>);
//        }
        cargaElementos(<?php echo $grupo_usuario['id_grupo'];?>,<?php echo  $id_experiencia?>);
});

</script>

