<?php
/*
 * Despliega el cuadro con las recomendaciones en sección "Gestión de Avance"
 * con todas sus funcionalidades. Crea el archivo python y lo ejecuta procesando
 * las matrices para obtener el orden de despliegue de las recomendaciones
 * de acuerdo a las variables y criterios de la Heurística.
 * Parametros:
 * 	$id_actividad: Identificador de actividad
 *	$estado: El estado actual de la actividad.
 *
 * Funciones
 *      dbExpObtenerActividad(..): obtiene los datos de
 *      la actividad (id_actividad)
 *
 *      dbRECObtenerMensajes(..): obtiene todos los
 *      comentarios a recomendar asociados a la actividad (id_actividad)
 *
 * 
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Cristian Miranda - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 * 
 */

$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."revpares/inc/rp_db_functions.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
require_once($ruta_raiz."reco/inc/rec_db_functions.inc.php");
require_once($ruta_raiz."reco/inc/rec_functions.inc.php");

$estado = $_REQUEST["estado"];
$id_actividad = $_REQUEST["id_actividad"];

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$_datos_actividad = dbExpObtenerActividad($id_actividad, $conexion);
$nombre_actividad = $_datos_actividad["nombre"];

$id_a_recomendar = $_SESSION["klwn_id_usuario"];
$_comentarios = dbRECObtenerMensajes($id_actividad,$conexion);

?>

<ul id="rec_menu_despliegue_reco">
        <li class="rec_despl_cuadroreco">
            <a id="rec_titulo_cuadroreco_id" href="#">
                <div class="rec_titulo_cuadroreco_c">
                    <img id="img_c" src="img/flecha_c.png"><?php echo $lang_rec_desp_cuadro_rec_de_pares; ?>
                </div>
                <div class="rec_titulo_cuadroreco_a">
                    <img id="img_c" src="img/flecha_a.png"><?php echo $lang_rec_desp_cuadro_rec_de_pares; ?>
                </div>
            </a>
            <ul class="rec_bloque_despliegue">
                <li class="rec_bloque_cabecera">
                    <div class="rec_datos_actividad">
                        <div class="rec_cabecera_cuadroreco">
    <?php
    if($estado ==2){
        ?>
        <p><?php echo $lang_rec_desp_cuadro_rec_actividad; ?> <b> <?php echo $nombre_actividad;?> </b> <?php echo $lang_rec_desp_cuadro_rec_dejados; ?> </p>
        <?php
        if(!is_null($_comentarios)){
            $fp = fopen($ruta_raiz."reco/procesamiento_recomendacion.py", 'w');
            fputs($fp,
"#Se asigna la fila con el ID de cada prof (de acuerdo a la columa)
def asignaFilaIDProf(mfinal):
    for i in range(len(mfinal)):
        mfinal[0,i] = mfinal[i,0]


#Rellenar la matriz con el numero de valoraciones
def rellenaMatriz(minicial, mfinal):
    cont = 0
    for i in range(1,len(mfinal)):
        for j in range(1,len(mfinal)):
            pi = mfinal[i,0]
            pj = mfinal[0,j]
            for k in range(0,len(minicial)):
                if((minicial[k,1] == pi) and (minicial[k,2] == pj)):
                    cont = cont+1
            mfinal[i,j] = int(cont)
            cont = 0

#########################################################################
#########################################################################

from numpy import *
#quita notacion cientifca
set_printoptions(suppress=True)
id_a_recomendar = ".$id_a_recomendar."
#lectura de archivo de texto
salida = open('".$ruta_raiz."reco/salida.txt', 'w')
historial = open('".$ruta_raiz."reco/historial.txt', 'a')

###########################################
############ MATRIZ PROFESOR ##############
###########################################
");

            /*********************************************************
             ****************** MATRIZ PROFESOR **********************
             ********************* criterio 1 ************************
             *********************************************************/
            $_id_distintos = dbRECObtenerProfesoresDistintos($id_actividad, $conexion);
            $tamano = count($_id_distintos);
            fputs($fp, "mp = matrix('");
            $k=0;
            while($k < $tamano){
                $_info_profesor = dbRECObtenerDatosProfesor($_id_distintos[$k]["id_usuario"],$conexion);
                fputs($fp, $_id_distintos[$k]["id_usuario"]." ".$_info_profesor[0]["NS"]." ".$_info_profesor[0]["CT"]." ".$_info_profesor[0]["TL"]." ".$_info_profesor[0]["N"]);
                if($k != ($tamano-1)){
                    fputs($fp, ";");
                }
                $k++;
            }
            //se inserta la fila del id_usuario a quien se le va a recomendar
            $m = 0;
            while(!is_null($_id_distintos[$m])){
                if($_id_distintos[$m]["id_usuario"] == $id_a_recomendar){
                    break;
                }
                else{
                    $m++;
                }
            }
            if($_id_distintos[$m]["id_usuario"] == $id_a_recomendar){
                fputs($fp,"')\r\n");
            }
            else{
                if($tamano != 0){
                    fputs($fp,";");
                }
                $_info_prof_rec = dbRECObtenerDatosProfesor($id_a_recomendar,$conexion);
                fputs($fp, $id_a_recomendar." ".$_info_prof_rec[0]["NS"]." ".$_info_prof_rec[0]["CT"]." ".$_info_prof_rec[0]["TL"]." ".$_info_prof_rec[0]["N"]."')\r\n");
            }

            fputs($fp,
'#Se guarda la columna con los id
col_id = mp[:,0]
#Se agrega un 0 en la pocision [0,0]
col_id = insert(col_id, [0], 0, axis=0)

#Se elimina columna de ids
mp_sin_id = delete(mp, 0, axis=1)

#Se aplica la correlacion
mCorrelacion = matrix(corrcoef(mp_sin_id))

#Soluciona el caso de los colaboradores (filas mat profesor con ceros)
for i in range(len(mCorrelacion)):
    for j in range(len(mCorrelacion)):
        if(isnan(mCorrelacion[i,j])):
            if(i == j):
                mCorrelacion[i,j] = 1
            else:
                mCorrelacion[i,j] = 0

#Normalizacion
MPN = (mCorrelacion + 1)/2

salida.write("Matriz Profesor\n"+str(mp)+"\n\n\n\n")
salida.write("Matriz Correlacion\n"+str(mCorrelacion)+"\n\n\n\n")
salida.write("Matriz Profesor Normalizada\n"+str(MPN)+"\n\n\n\n")

###########################################
######## MATRIZ NUMERO ME GUSTA ###########
###########################################

#Cada linea se asigna a una variable del tipo matriz
');

            /*********************************************************
             ************** MATRIZ NUMERO DE ME GUSTA ****************
             ********************* criterio 1 ************************
             *********************************************************/
            $_tabla_REC = dbRECObtenerTablaRECMG($conexion);

            // Tabla REC_MEGUSTA_MENSAJE
            $tamano = count($_tabla_REC);
            $i=0;
            fputs($fp,"mnmg = matrix('");
            while($i < ($tamano)){
                fputs($fp, $_tabla_REC[$i]["id_mensaje"]." ".$_tabla_REC[$i]["id_usuario_valora"]." ".$_tabla_REC[$i]["id_usuario_autor"].";");
                $i++;
            }

            // Tabla MD_MEGUSTA_MENSAJE
            $_tabla_MD = dbRECObtenerTablaMDMG($conexion);
            $tamano = count($_tabla_MD);
            $i=0;
            while($i < ($tamano)){
                fputs($fp, $_tabla_MD[$i]["id_mensaje"]." ".$_tabla_MD[$i]["id_usuario_valora"]." ".$_tabla_MD[$i]["id_usuario_autor"].";");
                $i++;
            }

            // Tabla MU_MEGUSTA_MENSAJE
            $_tabla_MU = dbRECObtenerTablaMUMG($conexion);
            $tamano = count($_tabla_MU);
            $i=0;
            while($i < $tamano){
                fputs($fp, $_tabla_MU[$i]["id_mensaje"]." ".$_tabla_MU[$i]["id_usuario_valora"]." ".$_tabla_MU[$i]["id_usuario_autor"]);
                //control de punto y coma, y comilla simple
                if($i == ($tamano-1)){
                    fputs($fp, "')\r\n");
                }
                else{
                    fputs($fp, ";");
                }
                $i++;
            }
            fputs($fp,
'mNumMeGusta = matrix(zeros((len(MPN)+1,len(MPN)+1), int))

#Se asigna la columna 1 con los id de profesores
mNumMeGusta[:,0] = col_id
#Se copian los id en la fila 1
asignaFilaIDProf(mNumMeGusta)
#Se rellenan con el numero de me gusta
rellenaMatriz(mnmg, mNumMeGusta)

salida.write("Matriz Numero Me Gusta\n"+str(mNumMeGusta)+"\n\n\n\n")

#Se elimina la fila y columna con los ids
mNumMeGusta = delete(mNumMeGusta, 0, axis=0)
mNumMeGusta = delete(mNumMeGusta, 0, axis=1)

#Normalizacion
maxnumMG = float(mNumMeGusta.max())
if(maxnumMG != 0):
    MNumMGN = mNumMeGusta/maxnumMG
else:
    MNumMGN = mNumMeGusta

salida.write("Matriz Numero Me Gusta Normalizada\n"+str(MNumMGN)+"\n\n\n\n")




##############################################
######## MATRIZ NUMERO COMENTARIOS ###########
##############################################
');


            /************************************************************
             ************** MATRIZ NUMERO DE COMENTARIOS ****************
             *********************** criterio 1 *************************
             ************************************************************/

            //Tabla MD_RESPUESTA_MENSAJES
            $_tabla_MD_resp = dbRECObtenerTablaMDRespuestas($conexion);
            $tamano = count($_tabla_MD_resp);
            $i=0;
            fputs($fp,"mnc = matrix('");
            while($i < $tamano){
                fputs($fp, $_tabla_MD_resp[$i]["id_mensaje_original"]." ".$_tabla_MD_resp[$i]["id_usuario_responde"]." ".$_tabla_MD_resp[$i]["id_usuario_autor"]);
                //control de punto y coma, y comilla simple
                if($i == ($tamano-1)){
                    fputs($fp, "')\r\n");
                }
                else{
                    fputs($fp, ";");
                }
                $i++;
            }
            fputs($fp,
'mNumComentarios = matrix(zeros((len(MPN)+1,len(MPN)+1), int))


#Se asigna la columna 1 con los id de profesores
mNumComentarios[:,0] = col_id
#Se copian los id en la fila 1
asignaFilaIDProf(mNumComentarios)
#Se rellenan con el numero de me gusta
rellenaMatriz(mnc, mNumComentarios)

salida.write("Matriz Numero Comentarios\n"+str(mNumComentarios)+"\n\n\n\n")

#Se elimina la fila y columna con los ids
mNumComentarios = delete(mNumComentarios, 0, axis=0)
mNumComentarios = delete(mNumComentarios, 0, axis=1)

#Normalizacion
maxnumMC = float(mNumComentarios.max())
if(maxnumMC != 0):
    MNumCN = mNumComentarios/maxnumMC
else:
    MNumNC = matrix(zeros((len(mNumComentarios),len(mNumComentarios)), int))
    MNumCN = mNumComentarios

salida.write("Matriz Numero Comentarios Normalizada\n"+str(MNumCN)+"\n\n\n\n")


##############################################
########### MATRIZ DE CERCANIA ###############
##############################################
MCercania = MPN + MNumMGN + MNumCN
#Normalizacion
MCercaniaN = MCercania/3

#Se agrega fila y columna con los ids de profesores
MCercaniaN = matrix(insert(MCercaniaN, [0], 0, axis=1))
MCercaniaN = insert(MCercaniaN, [0], 0, axis=0)
MCercaniaN[:,0] = col_id
asignaFilaIDProf(MCercaniaN)

salida.write("Matriz Cercania Normalizada\n"+str(MCercaniaN)+"\n\n\n\n")


##############################################
########### MATRIZ COMENTARIOS ###############
##############################################
');

            /**************************************************
             ************** MATRIZ COMENTARIOS ****************
             ****************** criterio 2 ********************
             **************************************************/

            $tamano = count($_comentarios);
            $i=0;
            fputs($fp,"mComentarios = matrix('");
            while($i < ($tamano)){
                //columna id_mensaje
                fputs($fp, $_comentarios[$i]["id_mensaje"]." ");
                //columna id_usuario
                fputs($fp, $_comentarios[$i]["id_usuario"]." ");
                //columna R (recomendacion o comentario)
                if($_comentarios[$i]["tipo"] == 6){ //recomendacion
                    fputs($fp, "1 ");
                }
                else{ //comentario
                    fputs($fp, "0 ");
                }
                //columna MGC (numero Me Gusta del comentario)
                $num_mg_com = dbRECNumMGComentario($_comentarios[$i]["id_mensaje"], $conexion);
                fputs($fp, $num_mg_com." ");
                // columna NE : numero de experiencias ejecutadas por el profesor
                $nexp = dbRECNumExpEjecutadas($_comentarios[$i]["id_usuario"],$conexion);
                fputs($fp, $nexp." ");
                // columna VA: Valoracion de actividad
                if($_comentarios[$i]["tipo"] == 6){
                    $eval = dbRECObtieneEvaluacionActividad($_comentarios[$i]["id_mensaje"], $conexion);
                    if($eval == "Bien" || $eval == "Mal"){
                        fputs($fp, "1 ");
                    }
                    else{
                        fputs($fp, "0 ");
                    }
                }
                else{
                    fputs($fp, "0 ");
                }
                // columna MGprof : numero de me gusta del profesor con id_usuario
                $num_mg_prof = dbRECNumMGProf($_comentarios[$i]["id_usuario"], $conexion);
                fputs($fp, $num_mg_prof);
                //control de punto y coma, y comilla simple
                if($i == ($tamano-1)){
                    fputs($fp, "')\r\n");
                }
                else{
                    fputs($fp, ";");
                }
                $i++;
            }
            fputs($fp,
'MCN = matrix(zeros((len(mComentarios),7), float))

#Maximos de cada variables para normalizar
maxR = float((mComentarios[:,2]).max())
maxMGC = float((mComentarios[:,3]).max())
maxNE = float((mComentarios[:,4]).max())
maxVA = float((mComentarios[:,5]).max())
maxMGprof = float((mComentarios[:,6]).max())

#Normalizacion (columna1: id_mensaje, columna2: id_usuario)
MCN[:,0] = mComentarios[:,0]
MCN[:,1] = mComentarios[:,1]
if(maxR != 0):
    MCN[:,2] = (mComentarios[:,2])/maxR
else:
    MCN[:,2] = mComentarios[:,2]

if(maxMGC != 0):
    MCN[:,3] = (mComentarios[:,3])/maxMGC
else:
    MCN[:,3] = mComentarios[:,3]

if(maxNE != 0):
    MCN[:,4] = (mComentarios[:,4])/maxNE
else:
    MCN[:,4] = mComentarios[:,4]

if(maxVA != 0):
    MCN[:,5] = (mComentarios[:,5])/maxVA
else:
    MCN[:,5] = mComentarios[:,5]

if(maxMGprof != 0):
    MCN[:,6] = (mComentarios[:,6])/maxMGprof
else:
    MCN[:,6] = mComentarios[:,6]

salida.write("Matriz Comentarios Normalizada\n"+str(MCN)+"\n\n\n\n")


##############################################
############ VECTOR CRITERIO 2 ###############
##############################################
VecSumaComent = matrix(zeros((len(mComentarios),4), float))

#Columna1: id_mensaje
VecSumaComent[:,0] = mComentarios[:,0]

#Columna2: id_usuario
VecSumaComent[:,1] = mComentarios[:,1]

#Columna3: Valores de la Matriz de Cercania de acuerdo al id_autor del comentario (criterio 1)
z=0
while MCercaniaN[z,0] != id_a_recomendar:
    z = z+1
if(MCercaniaN[z,0] == id_a_recomendar):
    i = z
for n in range(len(VecSumaComent)):
    id_j = VecSumaComent[n,1]
    k = 0
    while MCercaniaN[0,k] != id_j:
        k = k+1
    if(MCercaniaN[0,k] == id_j):
        j = k
    VecSumaComent[n,2] = MCercaniaN[i, j]

#Columna4: Valor resultante de suma de variables de matriz Comentarios (criterio 2)
x = 0
for i in range(len(MCN)):
	for j in range(2,7):
		x = x + MCN[i,j]
	VecSumaComent[i,3] = x
	x=0

#Normalizacion de columna4
VecSumaComent[:,3] = VecSumaComent[:,3]/5

salida.write("Vector con Valor Criterio 1 y Valor Criterio 2\n"+str(VecSumaComent)+"\n\n\n\n")



###################################
############# PRUEBAS #############
###################################

#Ponderaciones para Criterio 1 (k1) y Criterio 2 (k2)
k1 = 0.8
k2 = 0.2

#Se agrega columna para almacenar resultado de ((k1*c1) + (k2*c2))
VectorResultados = insert(VecSumaComent, [4], 0, axis=1)

#Ponderacion
for i in range(len(VectorResultados)):
    VectorResultados[i,4] = (k1*VectorResultados[i,2]) + (k2*VectorResultados[i,3])

salida.write("Vector Resultados con Suma Ponderada de (k1*C1 + k2*C2) con k1=0.8 y k2=0.2\n"+str(VectorResultados)+"\n\n\n\n")


#Se ordena el vector resultante de mayor a menor luego de la ponderacion de k1 y k2
VectorOrdenado = matrix(zeros((len(VectorResultados),5), float))
m = 0
maxn = 0
while m < len(VectorResultados):
    maxn = VectorResultados[:,4].max()
    n=0
    while VectorResultados[n,4] != maxn:
        n=n+1
    VectorOrdenado[m,0] = VectorResultados[n,0]
    VectorOrdenado[m,1] = VectorResultados[n,1]
    VectorOrdenado[m,2] = VectorResultados[n,2]
    VectorOrdenado[m,3] = VectorResultados[n,3]
    VectorOrdenado[m,4] = VectorResultados[n,4]
    VectorOrdenado[m,4] = maxn
    VectorResultados[n] = 0
    m=m+1

salida.write("Vector Ordenado\n"+str(VectorOrdenado)+"\n\n\n\n")

ids_mensajes = array(zeros((len(VectorOrdenado)), int))
for i in range(len(VectorOrdenado)):
    ids_mensajes[i] = VectorOrdenado[i,0]
    print ids_mensajes[i]

#matriz que almacena el Historial de recomendaciones entregadas
historial.write(str(id_a_recomendar)+"\n")
i=0
while i<len(VecSumaComent):
    historial.write(str(int(VecSumaComent[i,0]))+"               "+str(VecSumaComent[i,2])+"               "+str(VecSumaComent[i,3])+"\n")
    i=i+1
historial.write("\n\n")
');
            //ejecucion de archivo python recien creado
            $resultado = exec("python ".$ruta_raiz."reco/procesamiento_recomendacion.py", $_msgs_a_recomendar);
            $i = 0;
            $k = 0;
            while(!is_null($_msgs_a_recomendar[$i]) AND $i < 10){
                $_recomendacion = dbRECObtenerDatosMsgRecomendar($id_actividad, $_msgs_a_recomendar[$i] , $conexion);
                $id_mensaje_actual = $_msgs_a_recomendar[$i];
                //echo "mensaje actual: ".$id_mensaje_actual;
                $tipo        = $_recomendacion[$k]["tipo"];
                $fechastr    = date("d-m-Y h:i A", strtotime($_recomendacion[$k]["fecha"]));
                $nombre_real = $_recomendacion[$k]["nombre"];
                $img_usuario = $_recomendacion[$k]["imagen"];
                $usuario     = $_recomendacion[$k]["usuario"];
                $img_formateada = darFormatoImagen($img_usuario, $config_ruta_img_perfil, $config_ruta_img);
                ?>
                            <li>
                                <div class="rec_mensaje_cuadroreco">
                                    <div class="rec_avatar_cuadroreco">
                                        <img  src="<?php echo $img_formateada["imagen_usuario"]?>">
                                    </div>
                                    <div class="rec_menu_cuadroreco">
                                        <a href="contenido_perfil_usuario_modal.php?nombre_usuario=<?php echo $usuario;?>" class ="rec_link_nombre" title ="<?php echo $usuario;?>" ><?php echo $nombre_real;?></a>
                    <?php
                    if($tipo == 6){
                        echo " (".$fechastr.") ".$lang_rec_desp_cuadro_rec_recomendo.": "."<p>".$_recomendacion[$k]["mensaje"]."</p>";
                    }
                    else{
                        echo " (".$fechastr.") ".$lang_rec_desp_cuadro_rec_comento.": "."<p>".$_recomendacion[$k]["mensaje"]."</p>";
                    }

                    $usuario_gusta_mensaje = dbRECObtenerMeGustaMensaje($_SESSION["klwn_id_usuario"], $id_mensaje_actual, $conexion);
                    $_usuarios_valoran_mensaje = dbRECObtenerUsuariosGustaMensaje($id_mensaje_actual, $conexion);
                    $num_usuarios_valoran_mensaje = count($_usuarios_valoran_mensaje);
                    ?>
                                        <div id="rec_valoracion_mensaje<?php echo $id_mensaje_actual;?>">
                                            <div class="rec_megusta">
                                                <?php
                                                    if($usuario_gusta_mensaje<1){ ?>
                                                        <button class="rec_boton_megusta_mensaje" id="rec_megusta<?php echo $id_mensaje_actual;?>"> <?php echo $lang_rec_desp_cuadro_rec_mg; ?> </button>
                                                    <?php
                                                    }
                                                    else{  ?>
                                                        <button class="rec_boton_megusta_mensaje" id="rec_nomegusta<?php echo $id_mensaje_actual;?>"> <?php echo $lang_rec_desp_cuadro_rec_ya_no_mg; ?> </button>
                                                    <?php
                                                    }
                                                    if($num_usuarios_valoran_mensaje>0){
                                                    ?>
                                                        <div class ="rec_ver_megusta">
                                                            <a class ="rec_boton_ver_megusta" id ="rec_usuarios_gusta<?php echo $id_mensaje_actual;?>" href= "reco/rec_lista_usuarios_valoran_mensajes.php?">
                                                              <img src="<?php echo $config_ruta_img;?>me_gusta_dm.png" title ="<?php echo $lang_num_megusta;?>" alt= <?php echo $lang_num_megusta;?>/>
                                                            </a>
                                                            <span><?php echo $num_usuarios_valoran_mensaje;?></span>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
            <?php
                $i++;
            }
        }
        else{
            echo "<p>".$lang_rec_desp_cuadro_rec_sin_reco.".</p>";
        }
    }
    else{
        echo "<p>".$lang_rec_desp_cuadro_rec_sin_actividad.".</p>";
    }
    ?>
                        </div>
                        <div class="clear">
                        </div>
                    </div>
                </li>
            </ul>
        </li>
    </ul>

<script type="text/javascript">

function recMeGustaMensaje(id_mensaje){
    url ="reco/rec_gusta_mensaje.php?id_mensaje="+id_mensaje;
    $.get(url, function(data) {
        $('#rec_valoracion_mensaje'+id_mensaje).html(data);
    });
}

$('.rec_link_nombre').click(function() {
    var $linkc = $(this);
    var $dialog = $('<div></div>')
    .load($linkc.attr('href'))
    .dialog({
        autoOpen: false,
        title: '<?php echo $lang_perfil_usuario_titulo_ventana; ?>',
        width: 800,
        height: 600,
        modal: true,
        buttons: {
            "<?php echo $lang_rec_desp_cuadro_rec_cerrar; ?>": function() {
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
    recIniciarCuadroRec();
    
    <?php
    $num = 0;
    while ($_msgs_a_recomendar[$num]) {
        $id_mensaje = $_msgs_a_recomendar[$num];
?>
                $('#rec_megusta<?php echo $_msgs_a_recomendar[$num]; ?>').click(function(){
                    id_mensaje_valorado = <?php echo $_msgs_a_recomendar[$num]; ?>;
                    url_mensaje_valorado = 'reco/rec_insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valorado+'&megusta=1';
                    $.get(url_mensaje_valorado, function() {
                        recMeGustaMensaje(<?php echo $id_mensaje; ?>);
                    });
                });
                $('#rec_nomegusta<?php echo $_msgs_a_recomendar[$num]; ?>').click(function(){
                    id_mensaje_valorado = <?php echo $_msgs_a_recomendar[$num]; ?>;
                    url_mensaje_valorado = 'reco/rec_insertar_gusta_mensaje.php?id_mensaje='+id_mensaje_valorado+'&megusta=0';
                    $.get(url_mensaje_valorado, function() {
                        recMeGustaMensaje(<?php echo $id_mensaje; ?>);
                    });
                });

                $('#rec_usuarios_gusta<?php echo $_msgs_a_recomendar[$num]; ?>').each(function() {
                    var $linkc = $(this);
                    $linkc.click(function() {
                        var $dialog = $('<div></div>')
                        .load($linkc.attr('href')+'id_mensaje=<?php echo $_msgs_a_recomendar[$num]; ?>')
                        .dialog({
                            autoOpen: false,
                            title: '<?php echo $lang_usuarios_gusta_rec_titulo_ventana; ?>',
                            width: 600,
                            height: 400,
                            modal: true,
                            buttons: {
                                "<?php echo $lang_rec_desp_cuadro_rec_cerrar; ?>": function() {
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
                });
<?php
        $num++;
    }
?>

});

</script>