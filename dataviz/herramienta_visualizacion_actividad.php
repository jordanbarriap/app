<?php
$ruta_raiz = "../";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");

$id_experiencia  = $_REQUEST["codexp"];
$id_usuario      = $_REQUEST["id_usuario"];
$id_sesion       = $_REQUEST["id_sesion"];

?>

<!--<img id="simbologia" src="/app/dataviz/img/simbologia_vis1.svg">-->

<!--<p id="titulo-historial">Historial de Actividad en la Bitácora</p>-->
<div id="div-historial-actividad">
  <div id="div-userinfo">
      <div id="div-user1" class="div-userid">
        <p id="text-username1" class="text-username"></p>
        <p id="text-classname1" class="text-classname"></p>
      </div>
      <div id="div-user2" class="div-userid">
        <p id="text-username2" class="text-username"></p>
        <p id="text-classname2" class="text-classname"></p>
      </div>
      <table id="table-datos">
        <tr>
          <td id="cell-dato1a" class="cell-dato"></td>
          <td id="cell-dato2a" class="cell-dato"></td>
          <td id="cell-dato3a" class="cell-dato"></td>
        </tr>
        <tr>
          <td id="cell-dato1b" class="cell-dato"></td>
          <td id="cell-dato2b" class="cell-dato"></td>
          <td id="cell-dato3b" class="cell-dato"></td>
        </tr>
      </table>
  </div>
  <div id="div-usershistory">
    <div id="div-listmessages">
      <ul id="list-usershistory">
      </ul>
    </div>
  </div>
</div>
<div id="div-radiobutton">
  <form>
  <input type="radio" id="vista_individual" title="<?php echo $lang_hv_act_click_participacion; ?>" name="view" value=1 checked /><label for="vista_individual"><?php echo $lang_hv_act_estudiantes; ?></label>
  <input type="radio" id="vista_grupal" name="view" title="<?php echo $lang_hv_act_click_agrupar; ?>" value=0 /><label for="vista_grupal"><?php echo $lang_hv_act_grupos_trabajo; ?></label>
  </form> 
</div>
<!--<div id="div-explicacion_nodos_clase">
  <span class='close'><b>x</b></span>
  <p><b>¡Bienvenido/a a la herramienta de visualización de actividad Kelluwen!</b></p>
  <p>A través de esta herramienta podrás explorar tu participación y la de tus compañeros en esta experiencia didáctica.</p>
  <ul>
    <li>Las <b>nubes</b> representan <b>clases</b> ejecutando el mismo diseño didáctico. De esta forma, existe una nube para tu aula y una para cada una de tus aulas gemelas.</li>
    <li>Los <b>nodos de la red</b> representan a cada uno de los <b>participantes</b> de las experiencias didácticas.</li>
    <li>El <b>tamaño de los nodos</b> representa el <b>grado de participación</b> de un usuario dentro de la bitácora, definido por el número de comentarios, respuestas a mensajes y "me gusta" dejados en ella. Así, mientras mayor sea tu participación en la bitácora, más destacado aparecerás.</li>
    <li>El <b>color del borde</b> de cada nodo representa el <b>grupo de trabajo</b> al cual pertenece.</li>
  <ul>
</div>
<div id="div-explicacion_vistas">
  <span class='close'><b>x</b></span>
  <p>Puedes explorar tu actividad y la de tus compañeros en la bitácora  por medio de la <b>Vista Individual</b>, como también puedes acceder a un resumen de la actividad grupal a través de la <b>Vista de Grupos</b>.</p>
</div>
<div id="div-explicacion_interaccion">
  <span class='close'><b>x</b></span>
  <p>Para explorar la información de la red de participantes, puedes efectuar diversas acciones:</p>
  <ul>
    <li><b>Pasa el cursor por encima</b> de un nodo para conocer con quiénes un usuario ha <b>interactuado</b> (dándose respuesta o "me gusta" a un mensaje al menos una vez).</li>
    <li>Haz <b>click</b> en un nodo para acceder a un detalle de su <b>historial de participación en la bitácora</b>.</li>
    <li>Usando tu mouse o los controles de navegación, puedes hacer <b>zoom</b> y <b>desplazarte</b> a través de las nubes para facilitar tu exploración del espacio.</li>
  </ul>
</div>
<div id="div-explicacion_historial">
  <span class='close'><b>x</b></span>
  <p>En este espacio se detalla el <b>historial de participación reciente del usuario seleccionado</b> o el <b>historial de interacciones</b> que dicho <b>usuario</b> ha experimentado con el resto de <b>sus compañeros</b></p>
</div>
<div id="div-explicacion_cercania_nodos">
  <span class='close'><b>x</b></span>
  <p>Al <b>clickear un círculo de la red</b>, accedes al detalle del <b>historial de actividad</b> de dicho usuario o grupo en la <b>bitácora</b></p>
  <ul>
    <li>El <b>círculo del centro</b> corresponde al usuario o grupo de quién estamos consultando su <b>historial de actividad.</b></li>
    <li>Los <b>círculos conectados</b> representan a los usuarios o grupos con quienes el usuario o grupo seleccionado ha interactuado.</li>
  </ul>
</div>
<div id="div-explicacion_links">
  <span class='close'><b>x</b></span>
  <ul>
    <li>Puedes <b>clickear</b> sobre uno de los enlaces que une a uno de los <b>círculos</b> al <b>círculo del centro</b> para acceder a un detalle de las <b>interacciones</b> entre ambos usuarios o grupos</li>
    <li>Mientras <b>más gruesa</b> sea la <b>línea</b> que los une, significa que <b>mayor ha sido la interacción</b> entre ambos usuarios o grupos a lo largo de la experiencia didáctica.</li>
  </ul>
</div>
<div id="div-explicacion_volver_red">
  <span class='close'><b>x</b></span>
  <p>Puedes <b>clickear este botón</b> o <b>hacer click en el fondo blanco</b> para <b>volver a la red</b> de usuarios (o grupos)</p>
</div>-->
<input type="image" id="boton_volver_red"  src="/app/dataviz/img/flecha_volver.png" title="<?php echo $lang_hv_act_volver_red_general; ?>" width="20" height="20">
<input type="image" id="boton_ayuda" src="/app/dataviz/img/icono_ayuda.png" title="<?php echo $lang_hv_act_ayuda; ?>" width="20" height="20">
</button>
<div id="control-container">
  <div class="top left has-pan-control">
    <div class="control-pan control">
      <div class="control-pan-up-wrap">
        <a id="panUp" class="control-pan-up" href="#" title="<?php echo $lang_hv_act_arriba; ?>" name="Desplazar Arriba"></a>
      </div>
      <div class="control-pan-left-wrap">
        <a id="panLeft" class="control-pan-left" href="#" title="<?php echo $lang_hv_act_izquierda; ?>" name="Desplazar Izquierda"></a>
      </div>
      <div class="control-pan-right-wrap">
        <a id="panRight" class="control-pan-right" href="#" title="<?php echo $lang_hv_act_derecha; ?>" name="Desplazar Derecha"></a>
      </div>
      <div class="control-pan-down-wrap">
        <a id="panDown" class="control-pan-down" href="#" title="<?php echo $lang_hv_act_abajo; ?>" name="Desplazar Abajo"></a>
      </div>
      <div class="control-pan-middle-wrap">
        <a id="panMiddle" class="control-pan-middle" href="#" title="<?php echo $lang_hv_act_donde_estoy; ?>" name="Desplazar Usuario"></a>
      </div>
    </div>
    <div class="control-zoom bar control">
      <a id="zoomIn" class="control-zoom-in" href="#" title="<?php echo $lang_hv_act_acercar; ?>" name="Zoom In">+</a>
      <a id="zoomOut" class="control-zoom-out" href="#" title="<?php echo $lang_hv_act_alejar; ?>" name="Zoom Out">-</a>
    </div>
  </div>
  <div class="top right has-pan-control"></div>
  <div class="bottom left has-pan-control"></div>
  <div class="bottom right has-pan-control"></div>
</div>

<link rel="stylesheet" type="text/css" href="/app/dataviz/css/dataviz.css" />
<script src="/app/dataviz/wordcloud/wordcloud.js"></script>

<link type="text/css" href="/app/dataviz/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />
 
<!-- the mousewheel plugin - optional to provide mousewheel support -->
<script type="text/javascript" src="/app/dataviz/css/jquery.mousewheel.js"></script>
 
<!-- the jScrollPane script -->
<script type="text/javascript" src="/app/dataviz/css/jquery.jscrollpane.min.js"></script>

<script>

//Sección de variables necesarias para mostrar las instrucciones que explican la visualización
//En primer lugar se asume que no necesita ayuda
var activarAyuda=[0,0];
var activarAyudaPerspClase=false
var activarAyudaPerspSelfCentered=false
var clickAyuda=false;
var divs_ayuda_activos=0;
var array_id_divs_ayuda_persp_clase=["div-explicacion_vistas","div-explicacion_nodos_clase","div-explicacion_interaccion"];
var array_id_divs_ayuda_persp_selfcentered=["div-explicacion_historial","div-explicacion_cercania_nodos","div-explicacion_links","div-explicacion_volver_red"];
//fin ayuda visualización

var scrollpane;
var isscrollpane=false;

var grupal=false;
var force_individual=null
var force_grupal=null;
var pack_layout_array=[];
var vista_individual=1;
var self_centered=false;
var nodo_centro = -1;
var array_usuarios;

var clickRelacion=false;
var transicion=false;
var coord_links_transgrupal;
var transicion_datos=1000;

var min_radius=6.5;
    max_radius=24.5;
    min_radius_ind=6.5;
    max_radius_ind=24.5;
    ratio_foto=0.8;
    width_border_ratio = 0.0;
    max_radius_radial_layout=150;//Radio máximo del radial layout
    fixed_node_radius=12.5;
    fixed_node_grupo_radius=17.5;
    stroke_width_self_centered=1.5;
    tamano_img_prof=15;//Tamaño icono profesor en pixeles

var wordcloud;

var width = $('#visualizacion_actividad').width(),
    height = $('#visualizacion_actividad').height()-$("#titulo_red_actividad").height();


//Seccion variables que permiten realizar zoom y pan
var margin = {top: -5, right: -5, bottom: -5, left: -5};

var zoom = d3.behavior.zoom()
    .scaleExtent([0.5, 2])
    .size([1.25*width,1.25*height])
    .on("zoom", zoomed);

var pan_actual=[0,0];
var ultimo_pan=pan_actual;
var zoom_actual=1;
var ultimo_zoom=zoom_actual;
var endZoomed=new Date();
var startZoomed;
var timeZoomed=0;
var zoom_activo=1;
var tamano_elemento;

var not_zoom_not_pan = d3.behavior.zoom();



var drag = d3.behavior.drag()
    .origin(function(d) { return d; })
    .on("dragstart", dragstarted)
    .on("drag", dragged)
    .on("dragend", dragended);
//fin sección variables zoom y pan

var node_space_ind = 30;
var node_space_grup = 40;
var node_space_clases = 60;

var general_view_edge_color="#666666";
var stroke_width_general_view=0.9;
var offset_posx_radial_layout = 0.5 * (width-280)/width;


//Creación canvas para el renderizado de la visualizacion de modo que soporte acciones de zoom y pan
var main_svg= d3.select("#visualizacion_actividad")
          .append("svg")
            .attr("id","viewport")
            .attr("width", width)// + margin.left + margin.right)
            .attr("height", height)// + margin.top + margin.bottom)
          .append("g")
            .on("mousemove", mousemove)
            //.attr("id","viewport")
            //.attr("transform", "translate(" + margin.left + "," + margin.right + ")")
            .call(zoom).on("dblclick.zoom", null);

var svg_canvas=main_svg.append("rect")
    .attr("width", width)
    .attr("height", height)
    .style("fill", "none")
    .style("pointer-events", "all")
    .attr("class","canvas");

var svg=main_svg.append("g");
//fin creación canvas

//Limites para el Pan
var maxPan_x=0.25*width,
    minPan_x=-0.25*width,
    maxPan_y=height,
    minPan_y=-1*height;

// Control Pan-Zoom
var makePanZoomCTRL = function(width, height) {
  var control = {}
  var zoomMin = zoom.scaleExtent()[0],// Levels of Zoom Out
      zoomMax = zoom.scaleExtent()[1],// Levels of Zoom In
      zoomCur =   0, // Current Zoom
      offsetX =   0, // Current X Offset (Pan)
      offsetY =   0; // Current Y Offset (Pan)
  var transformMove = function () {
    
    var x=zoom.translate()[0]+offsetX;
    var y=zoom.translate()[1]+offsetY;

    svg.transition().duration(100).attr("transform", "translate(" +x+" "+y+")scale("+zoom.scale()+")");
    zoom.translate([x,y]);
    ultimo_pan=[x,y];
    
  };
  var transformZoom = function(){
    var centro = [width / 2, height / 2];
    var s = zoom.scale()+zoomCur;

    var vista = {x: zoom.translate()[0], y: zoom.translate()[1], k: zoom.scale()};
    var translate = [(centro[0] - vista.x) / vista.k, (centro[1] - vista.y) / vista.k];
    vista.k = s;
    l = [translate[0] * vista.k + vista.x, translate[1] * vista.k + vista.y];

    vista.x += centro[0] - l[0];
    vista.y += centro[1] - l[1];
    
    if (s>=zoomMin && s<=zoomMax){
      svg.transition().duration(100).attr("transform", "translate("+vista.x+" "+vista.y+")scale(" +vista.k+ ")");
      zoom.scale(vista.k);
      ultimo_zoom=vista.k;
      zoom.translate([vista.x,vista.y]);
    }
  }
  control.pan = function (btnID) {
    if (btnID === "panLeft") {
      offsetX = 50;
      offsetY = 0;
    } else if (btnID === "panRight") {
      offsetX = -50;
      offsetY = 0;
    } else if (btnID === "panUp") {
      offsetX = 0;
      offsetY = 50;
    } else if (btnID === "panDown") {
      offsetX = 0; 
      offsetY = -50;
    }
    transformMove();
  };
  control.zoom = function (btnID) {
    if (btnID === "zoomIn") {
      //if (zoomCur >= zoomMax) return;
      zoomCur=0.1;
    } else if (btnID === "zoomOut") {
      //if (zoomCur <= zoomMin) return;
      zoomCur=-0.1;
    }
    transformZoom();
  };
  return control;
}

// Crear instancia del Control de Pan-Zoom
var panZoom = makePanZoomCTRL(width, height);

//Setear listeners de los eventos a los botones que permiten hacer Pan y Zoom
d3.selectAll("#zoomIn, #zoomOut")
  .on("click", function () {
    d3.event.preventDefault();
    var id = d3.select(this).attr("id");
    panZoom.zoom(id);
  });
d3.selectAll("#panLeft, #panRight, #panUp, #panDown")
  .on("click", function () {
    d3.event.preventDefault();
    var id = d3.select(this).attr("id");
    panZoom.pan(id);
  });
d3.select("#panMiddle")
  .on("click",function(){
    d3.event.preventDefault();
    focoVisualizacionUsuarioActivo(id_usuario);
});

//Crea la etiqueta para que los usuarios sepan que pueden clickear sobre los links
var hover_tooltip= d3.select("#visualizacion_actividad").append("div")   
    .attr("class", "hover_tooltip")               
    .style("opacity", 0);
var mouse_coords= [0,0];

//Crea la variable que representa a la etiqueta que sirve para ayudar al usuario a identificar donde se encuentra
var tooltip;

//Variables para ordenar la posición de cada experiencia (clase) dentro de la visualización
var a=width*0.6;
var b=height*0.8;
var nro_clases=0;
var usuarios_por_clase=new Array();

var id_experiencia=<?php echo $id_experiencia;?>;
var id_usuario    =<?php echo $id_usuario;?>;
var id_sesion     =<?php echo $id_sesion;?>;

var id_grupo             =-1;
var id_nodo_seleccionado =-1;
var id_nodo_conectado    =-1;

var ruta_json       ='/app/dataviz/obtener_json_info_usuarios.php?codexp='+id_experiencia+'&id_usuario='+id_usuario;
var dataUsuarios,dataGrupos,dataLinksUsuarios,dataLinksGrupales={};
var array_svg_grupos=[];
var grupos_por_clase=[];
var centros_clases  =[];
var mapeo_idgrupo;


var linkedByIndex = {};
var antiguedadLinkIndividual = {};
var linkedByTeam  = {};
var antiguedadLinkGrupal = {};
var historialInteracciones  = {};
var respuestasInteracciones = {};
var megustaInteracciones    = {};
var historialInteraccionesGrupales = {};
var respuestasInteraccionesGrupales = {};
var megustaInteraccionesGrupales    = {};
var historialParticipacion = {};
var historialParticipacionGrupal = {};
var arrayMaxMinInteraccion = [];
var arrayMaxMinInteraccionGrupal = [];
var max_interacciones_desplegadas =30;

var scalingIndividual,scalingGrupal,scalingLinkIndividual,scalingLinkGrupal,scalingPalabras,scalingTiempoLinks;

var color_grupos,color_clases;

//Variables globales para el almacenamiento de la data necesaria para el renderizado de la visualización
var bilinksIndividuales, bilinksGrupales;
var linksIndividuales, linksGrupales;
var nodesIndividuales, nodesGrupales;


$(document).ready(function(){
  //Agrega título a cada una de las visualizaciones
  $("#nube_palabras").append('<span id="titulo_nube_palabras" class="titulo_div"><?php echo $lang_hv_act_de_que_habla; ?></span>');
  $("#visualizacion_actividad").append('<span id="titulo_red_actividad" class="titulo_div"><?php echo $lang_hv_act_como_participamos; ?></span>');
  var pos_titulo_red= (width-280)*0.25;
  $("#titulo_red_actividad").css("left",pos_titulo_red+"px");

  $(".close").click(function(){
      var id_div=$(this).parent().attr("id");
      $("#"+id_div).css("display","none");
      divs_ayuda_activos--;
      if (divs_ayuda_activos==0){
        svg.style("opacity",1);
        if (!self_centered){
          activarAyudaPerspClase=false;
        }else{
          $("#div-usershistory").css("opacity",1);
          activarAyudaPerspSelfCentered=false;
        }
        clickAyuda=false;
      } 
      return false;
  });
  //Funciones necesarias para mover elementos SVG hacia al frente o hacia el fondo respectivamente dentro de la visualización
  d3.selection.prototype.moveToFront = function() {
    return this.each(function(){
      this.parentNode.appendChild(this);
    });
  };

  d3.selection.prototype.moveToBack = function() { 
      return this.each(function() { 
          var firstChild = this.parentNode.firstChild; 
          if (firstChild) { 
              this.parentNode.insertBefore(this, firstChild); 
          } 
      }); 
  };

  scalingTiempoLinks=d3.scale.linear()
      .domain([0,1])
      .range([0.35,1]);

  d3.json(ruta_json, function(error, data) {

  var activarAyuda=data.despliegue_ayuda;
  if (activarAyuda[0]=="1") activarAyudaPerspClase=true;
  //if (activarAyuda[1]=="1") activarAyudaPerspSelfCentered=true;

  //Verifica si es necesario desplegar ayuda al usuario
  if ((!self_centered && activarAyudaPerspClase)){// || (self_centered && activarAyudaPerspSelfCentered)){
    desplegarAyudaVis();
    $.ajax({ 
          type: "POST", 
          url: "log_accion_sesion.php", 
          data:{accion:"despliegue_ayuda" , id_usuario: id_usuario , ayuda_clase:0, ayuda_selfcentered:-1}
        }); 
  }

  dataUsuarios=data.nodes;
  dataLinksUsuarios=data.links;
  dataGrupos=data.grupos;
  dataLinksGrupales=data.links_grupos;

  //Sección de código que determina si el usuario de la sesión activa pertenece a alguna de las aulas gemelas presentes en la visualización
  array_usuarios=dataUsuarios.map(function(d){return d.id_usuario;});
  var usuario_pertenece_exp=array_usuarios.indexOf(id_usuario.toString());

  //Arreglo que servirá para renderizar los links individuales al pasar de dicha vista a la vista grupal
  coord_links_transgrupal=new Array(dataLinksUsuarios.length);

  var data_grupos_por_clase=data.clasesgrupos.slice();

  nro_clases=data_grupos_por_clase.length;
  console.log("Nro clases: "+nro_clases);

  var nro_grupos=0;
  var sin_grupos=true;
  for (var i=0 ; i<nro_clases ; i++){
    grupos_por_clase[i]=data_grupos_por_clase[i];
    nro_grupos=nro_grupos+grupos_por_clase[i];
    if (nro_grupos>1) sin_grupos=false;
  }

  if (sin_grupos){
    $("#div-radiobutton").css("display","none");
  }

  mapeo_idgrupo=new Array(nro_clases);
  for (var j=0 ; j<nro_clases ; j++){
    mapeo_idgrupo[j]=new Array(grupos_por_clase[j]);
  }

  var max_numero_grupos = d3.max(grupos_por_clase);

  arreglo_colores_libro = ["#219b24","#f04148","#21549b","#f7e652","#82cfd1","#f292b1","#9b9f95","#f89e5b","#b03930","#9f3f94","#00000"];
  //[verde, rojo, azul, amarillo, aqua, rosado,gris,naranjo,cafe, ]
  arreglo_colores=["#cc7a29","#219b24","#f02b32","#21549b","#f7e331","#69ced1","#f8852f","#aeb3a7","#fc6c85","#9f3f94","#a1c21d","#8a4117","#198080","#e32b9b","#990819","#e8a723","#374550","#ffe3b0","#c9bb46"];
  /*
  prof café claro: "#cc751f"
  1 verde: "#219b24"
  2 rojo: "#f02e35"
  3 azul: "#21549b"
  4 amarillo: "#f7e331"
  5 aqua (celeste): "#69ced1"
  6 naranjo: "#f8852f"
  7 gris: "#aeb3a7"
  8 rosado: "#fc6c85"
  9 morado: "#9f3f94"
  10 verde claro: "#a1c21d"
  11 café oscuro: "#8a4117"
  12 azul-verde: "#198080"
  13 rosa-violeta (fucsia): "#e32b9b"
  14 rojo-violeta (burdeo): "#990819"
  15 amarillo-naranja (ocre): "#e8a723"
  16 gris-azulado (charcoal):"#374550"
  17 beige:"#ffe3b0"
  sin grupo negro: "#000000"

   */

  //color_grupos=d3.scale.category20();

  color_grupos = d3.scale.ordinal()
              .domain(d3.range(max_numero_grupos))
              .range(arreglo_colores);/*["#AB0200", "#F4BC48", "#DA5814", "#7A923B", "#3B311C",
                      "#0064C7", "#642253", "#48D1CC", "#C71585", "#FF6347",
                      "#00FF00", "#A0522D", "#FF0000", "#FFA07A", "#FF8C00",
                      "#00B000", "#808080", "#DAA520", "#000080", "#FFA500",
                      "#800080", "#7FFF00", "#6495ED", "#006400", "#800000", 
                      "#FFFF00", "#9ACD32", "#696969", "#FF4500", "#32CD32",
                      "#00CED1", "#FFD700", "#00FA9A", "#A9A9A9", "#0000FF",
                      "#CD853F", "#ADFF2F", "#000000", "#7B68EE", "#8B0000", 
                      "#8B4513", "#00FFFF", "#EE82EE", "#808000", "#A52A2A",
                      ]);*/
  
  arreglo_colores_clases=["#ffff80","#b3cc99","#99cccc","#b399cc","#cc9999","#99cc99","#99b3cc","#cc99cc","#ccb399","#99ccb3","#9999cc","#cc99b3","#cccc99"];
  color_clases = d3.scale.ordinal()
              .domain(d3.range(nro_clases))
              .range(arreglo_colores_clases);//["#F4BC48","#7A923B","#DA5814","#39C","#CCCCFF","#960","#9DE"]);


  dataGrupos.forEach(function(d){
    if(d.children && d.children.length>0 && d.id_grupo!=-1){
      mapeo_idgrupo[d.id_experiencia][d.id_grupo]=d.fila_json;
      historialParticipacionGrupal[d.fila_json]=d.historial_participacion;
    }

  });
  usuarios_por_clase=obtenerUsuariosPorClase(nro_clases,dataUsuarios);
  var participacion_por_clase=obtenerParticipacionPorClase(nro_clases,dataUsuarios);
  var proporcion_participacion_usuario=[];
  var total_proporcion_participacion=0;
  var total_usuarios=0;
  for (var i=0;i<nro_clases;i++){
    if (usuarios_por_clase[i]!=0){ 
      proporcion_participacion_usuario[i]=participacion_por_clase[i]/usuarios_por_clase[i];
    }else{
      proporcion_participacion_usuario[i]=0;
    }
    total_proporcion_participacion=total_proporcion_participacion+proporcion_participacion_usuario[i];
    total_usuarios=total_usuarios+usuarios_por_clase[i];
  }


  var theta_acumulado=0;
  var nro_usuarios_ultima_clase=usuarios_por_clase[nro_clases-1];

  //Se consulta si en este grupo de aulas gemelas existen profesores asociados a más de un aula, para posicionar su nube al centro del canvas
  var profesores_multiples_clases=data.profesores_multiples_clases;

  //En caso de que la clase no tenga aulas gemelas, se posiciona al centro del canvas
  if (nro_clases==1){
    classx=width/2,
    classy=height/2;
    centros_clases[0]={x:classx,y:classy};
  }else{
    if (!profesores_multiples_clases){
      for (var i=0;i<nro_clases;i++){
        console.log("Usuarios clase "+i+": "+usuarios_por_clase[i]);
        if (usuarios_por_clase[i]>0){
          /*if (i==0){
            var theta=0;
          }else{*/
          var theta= 0.5*((nro_usuarios_ultima_clase/total_usuarios)+(usuarios_por_clase[i]/total_usuarios)) * 2 * Math.PI;
          //}
          nro_usuarios_ultima_clase=usuarios_por_clase[i];
        }else{
          var theta=0;
        }
        theta_acumulado=theta_acumulado+theta;
        ellipse_r=(a * b) / Math.sqrt( Math.pow(b*Math.cos(theta_acumulado),2)+ Math.pow(a*Math.sin(theta_acumulado),2))/2,
        classx=ellipse_r * Math.cos(theta_acumulado)+width/2,
        classy=ellipse_r * Math.sin(theta_acumulado)+height/2;
        centros_clases[i]={x:classx,y:classy};
      }
    }else{
      //Este es el caso de que se hayan agrupado a los profesores en un aula ficticia por el hecho que uno o más de ellos hacen clases en más de una aula a la misma vez.
      //En este caso se posiciona al aula compuesta por los profesores al centro del canvas.
      for (var i=0;i<nro_clases-1;i++){
        console.log("Usuarios clase "+i+": "+usuarios_por_clase[i]);
        if (usuarios_por_clase[i]>0){
          /*if (i==0){
            var theta=0;
          }else{*/
          var theta= 0.5*((nro_usuarios_ultima_clase/total_usuarios)+(usuarios_por_clase[i]/total_usuarios)) * 2 * Math.PI;
          //}
          nro_usuarios_ultima_clase=usuarios_por_clase[i];
        }else{
          var theta=0;
        }
        theta_acumulado=theta_acumulado+theta;
        ellipse_r=(a * b) / Math.sqrt( Math.pow(b*Math.cos(theta_acumulado),2)+ Math.pow(a*Math.sin(theta_acumulado),2))/2,
        classx=ellipse_r * Math.cos(theta_acumulado)+width/2,
        classy=ellipse_r * Math.sin(theta_acumulado)+height/2;
        centros_clases[i]={x:classx,y:classy};
      }
      classx=width/2,
      classy=height/2;
      centros_clases[nro_clases-1]={x:classx,y:classy};

    }
  }
  

  for (var i=0;i<nro_clases;i++){
    if (usuarios_por_clase[i]>0){
      svg.append("path")
          .attr("class","clase")
          .attr("id","clase"+i)
          .style("fill", color_clases(i))
          .style("stroke", color_clases(i))
          .style("stroke-width", 20)
          .style("opacity",0.4);
    }
  }

  
  //Genera el arreglo que determina el menor y el mayor número de interacciones por cada elemento a nivel de usuarios
  var n_nodes=dataUsuarios.length;
  
  dataUsuarios.forEach(function(d){
    historialParticipacion[d.fila_json]=d.historial_participacion;
  });

  dataLinksUsuarios.forEach(function(d) {
    linkedByIndex[d.source + "," + d.target] = d.total_interacciones;
    antiguedadLinkIndividual[d.source+","+d.target] = d.ponderacion;
    var data_historial_interacciones= d.historial_interacciones;

    var respuestas_usuario1= d.msjs_respuesta_usuario1;
    var respuestas_usuario2= d.msjs_respuesta_usuario2;
    var megusta_usuario1   = d.megusta_usuario1;
    var megusta_usuario2   = d.megusta_usuario2;

    var nro_interacciones = data_historial_interacciones.length;
    var historial_arreglo = [];
    for (var i=0;i<nro_interacciones;i++){
      historial_arreglo[i]=data_historial_interacciones[i];
    }
    historialInteracciones[d.source + "," + d.target] = historial_arreglo;

    //Almacena cuántas respuestas ha dado cada participante de la interacción (posición 0: usuario1, posición 1: usuario2)
    var respuestas_usuarios=new Array(2);
    respuestas_usuarios[0]=respuestas_usuario1;
    respuestas_usuarios[1]=respuestas_usuario2;
    respuestasInteracciones[d.source + "," + d.target]=respuestas_usuarios;

    //Almacena cuántos me gusta ha dado cada participante de la interacción (posición 0: usuario1, posición 1: usuario2)
    var megusta_usuarios=new Array(2);
    megusta_usuarios[0]=megusta_usuario1;
    megusta_usuarios[1]=megusta_usuario2;
    megustaInteracciones[d.source + "," + d.target]=megusta_usuarios;

    var sourceIndex=d.source;
    var targetIndex=d.target;
    
    //Determina el nro máximo y mínimo de interacciones de cada usuario
    if (arrayMaxMinInteraccion[sourceIndex]){
      var currentMin=arrayMaxMinInteraccion[sourceIndex].min;
      var currentMax=arrayMaxMinInteraccion[sourceIndex].max;
      if (d.total_interacciones<currentMin){
        arrayMaxMinInteraccion[sourceIndex].min=d.total_interacciones;
      }
      if (d.total_interacciones>currentMax){
        arrayMaxMinInteraccion[sourceIndex].max=d.total_interacciones;
      }
    }else{
      arrayMaxMinInteraccion[sourceIndex]={min: d.total_interacciones, max:d.total_interacciones};
    }

    if (arrayMaxMinInteraccion[targetIndex]){
      var currentMin=arrayMaxMinInteraccion[targetIndex].min;
      var currentMax=arrayMaxMinInteraccion[targetIndex].max;
      if (d.total_interacciones<currentMin){
        arrayMaxMinInteraccion[targetIndex].min=d.total_interacciones;
      }
      if (d.total_interacciones>currentMax){
        arrayMaxMinInteraccion[targetIndex].max=d.total_interacciones;
      }
    }else{
      arrayMaxMinInteraccion[targetIndex]={min: d.total_interacciones, max:d.total_interacciones};
    }
  });

  for (var i = 0; i < n_nodes; i++) {
    if (arrayMaxMinInteraccion[i]){
      if (arrayMaxMinInteraccion[i].min===arrayMaxMinInteraccion[i].max){
        arrayMaxMinInteraccion[i].min=0.1;
      }
    }
  };

  //Genera el arreglo que determina el menor y el mayor número de interacciones por cada elemento a nivel de grupo
  var n_nodes_grupos=dataGrupos.length;

  dataLinksGrupales.forEach(function(d) {
    linkedByTeam[d.source + "," + d.target] = d.total_interacciones;
    antiguedadLinkGrupal[d.source + "," + d.target] = d.ponderacion;
    var data_historial_interacciones= d.historial_interacciones;

    var respuestas_grupo1= d.msjs_respuesta_grupo1;
    var respuestas_grupo2= d.msjs_respuesta_grupo2;
    var megusta_grupo1   = d.megusta_grupo1;
    var megusta_grupo2   = d.megusta_grupo2;

    var nro_interacciones = data_historial_interacciones.length;
    var historial_arreglo = [];
    for (var i=0;i<nro_interacciones;i++){
      historial_arreglo[i]=data_historial_interacciones[i];
    }
    historialInteraccionesGrupales[d.source + "," + d.target] = historial_arreglo;
    var sourceIndex=d.source;
    var targetIndex=d.target;

    //Almacena cuántas respuestas ha dado cada grupo de la interacción (posición 0: grupo1, posición 1: grupo2)
    var respuestas_grupos=new Array(2);
    respuestas_grupos[0]=respuestas_grupo1;
    respuestas_grupos[1]=respuestas_grupo2;
    respuestasInteraccionesGrupales[d.source + "," + d.target]=respuestas_grupos;

    //Almacena cuántos me gusta ha dado cada grupo de la interacción (posición 0: grupo1, posición 1: grupo2)
    var megusta_grupos=new Array(2);
    megusta_grupos[0]=megusta_grupo1;
    megusta_grupos[1]=megusta_grupo2;
    megustaInteraccionesGrupales[d.source + "," + d.target]=megusta_grupos;
    
    //Determina el nro máximo y mínimo de interacciones de cada grupo
    if (arrayMaxMinInteraccionGrupal[sourceIndex]){
      var currentMin=arrayMaxMinInteraccionGrupal[sourceIndex].min;
      var currentMax=arrayMaxMinInteraccionGrupal[sourceIndex].max;
      if (d.total_interacciones<currentMin){
        arrayMaxMinInteraccionGrupal[sourceIndex].min=d.total_interacciones;
      }
      if (d.total_interacciones>currentMax){
        arrayMaxMinInteraccionGrupal[sourceIndex].max=d.total_interacciones;
      }
    }else{
      arrayMaxMinInteraccionGrupal[sourceIndex]={min: d.total_interacciones, max:d.total_interacciones};
    }

    if (arrayMaxMinInteraccionGrupal[targetIndex]){
      var currentMin=arrayMaxMinInteraccionGrupal[targetIndex].min;
      var currentMax=arrayMaxMinInteraccionGrupal[targetIndex].max;
      if (d.total_interacciones<currentMin){
        arrayMaxMinInteraccionGrupal[targetIndex].min=d.total_interacciones;
      }
      if (d.total_interacciones>currentMax){
        arrayMaxMinInteraccionGrupal[targetIndex].max=d.total_interacciones;
      }
    }else{
      arrayMaxMinInteraccionGrupal[targetIndex]={min: d.total_interacciones, max:d.total_interacciones};
    }
  });

  for (var i = 0; i < n_nodes_grupos; i++) {
    if (arrayMaxMinInteraccionGrupal[i]){
      if (arrayMaxMinInteraccionGrupal[i].min===arrayMaxMinInteraccionGrupal[i].max){
        arrayMaxMinInteraccionGrupal[i].min=0.1;
      }
    }
  };
  layoutVistaIndividual(dataUsuarios,dataLinksUsuarios);
  layoutVistaGrupal(dataUsuarios,dataGrupos,dataLinksGrupales);

  //Sección de código en la cual se grafica el signo que le indica a un usuario en dónde se ubica dentro de la visualización en caso de que
  //dicho usuario sea partícipe de alguna experiencia asociada.
  if ( usuario_pertenece_exp > -1){
    tooltip=svg.append("g")
                    .attr("id", "tooltip");

    tooltip.append("polygon")
        .attr("points","10,0 , 80,0 , 80,20 , 10,20 , 0,10");
    var x_tooltip=13;
    var y_tooltip=15;
    tooltip.append("svg:text")
        .attr("class","tooltip-text")
        .attr("x",x_tooltip)
        .attr("y",y_tooltip)
        .text("<?php echo $lang_hv_act_aqui_estoy; ?>");

    focoVisualizacionUsuarioActivo(id_usuario);
  }

  d3.json('/app/dataviz/wordcloud/obtiene_frecuencia_palabras.php?codexp='+id_experiencia,function(error,data){
    var words=data.frecuencia_palabras;
    words=words.sort(function(a,b){
      return d3.descending(parseInt(a.size),parseInt(b.size));
    });
    words=words.slice(0,50);
    console.log("words");
    console.log(words);
    //Create a new instance of the word cloud visualisation.
    if (words){
      scalingPalabras= d3.scale.log()
      .domain([d3.min(words, function(d){return parseInt(d.size)}), d3.max(words, function(d){return parseInt(d.size)})])
      .range([14,36]);
      wordcloud = wordCloud('#nube_palabras',words,scalingPalabras,'Impact');
      wordcloud.recargarWordcloud(words);
    }
  });

  
});

  $(".canvas").click(function(e) {
      e.stopPropagation();

      if (e.target.getAttribute('class')=='canvas'){

          main_svg.call(zoom).on("dblclick.zoom", null);

          
          if (self_centered){

            //Se desactiva el estado de click relacion en el caso que haya estado activado
            clickRelacion=false;

            ocultarAyudaVis();

            //$('#div-usershistory').css('visibility','hidden');
            $('#div-historial-actividad').css("display","none");
            $('#div-usershistory').css("display","none");
            $('#titulo-historial').css('visibility','hidden');
            $('#div-radiobutton').css('visibility','visible');
            $('#control-container').css('visibility','visible');
            $(".imagen_profesor").css('visibility','visible');
            $('#boton_volver_red').css('visibility','hidden');
            $('#tooltip').css('visibility','visible');
            //$('#simbologia').attr('src','/app/dataviz/img/simbologia_vis1.svg');

            /*svg.select(".radialLayout")
              .transition().style("display","none");*/

            svg.transition()
               .duration(250)
               .attr("transform","translate("+zoom.translate()+")scale("+zoom.scale()+")");
            

           isscrollpane=false;

            if (vista_individual){

              /*dataUsuarios.forEach(function(d){
                d.fixed=false;
              });*/
              //Hace visibles tanto nodos como links individuales para que se vean en la red general
              svg.selectAll(".node").style("opacity",1);
              svg.selectAll(".link").style("opacity",1);

              //Quita la clase que destacaba nodos seleccionados en la vista selfcentered
              svg.selectAll(".seleccionado_selfcentered").attr("class","node");
              svg.selectAll(".conectado_selfcentered").attr("class","node");

              id_nodo_seleccionado=-1;
              id_nodo_conectado   =-1;
              nodo_centro         =-1;
              //Hace visible los clusteres de clases
              for (var i=0;i<nro_clases;i++){
                if (usuarios_por_clase[i]>2){
                  svg.select("#clase"+i).transition()
                   .duration(100)
                   .style("visibility","visible");
                }
              }

              var newNode = svg.selectAll(".node");

              newNode.style("visibility","visible");

              var newLinks= svg.selectAll(".link")
                .transition()
                .duration(500)
                .style("stroke", general_view_edge_color)
                .style("stroke-width",stroke_width_general_view)
                .style("visibility","visible")
                .style("display","inline")
                .style("opacity",function(d){
                  return scalingTiempoLinks(ponderacionTiempoLinkIndividual(d[0].index,d[2].index));
                });

              svg.selectAll(".nodetext")
                .style("display","inline");

              svg.selectAll(".link_info")
                .style("visibility","hidden");


            }else{

              //Hace visibles tanto nodos como links grupales para que se vean en la red general
              svg.selectAll(".contenedor_grupo").style("opacity",1);

              //Quita la clase que destacaba nodos grupales seleccionados en la vista selfcentered
              svg.selectAll(".seleccionado_selfcentered").attr("class","grupo");
              svg.selectAll(".conectado_selfcentered").attr("class","grupo");

              id_nodo_seleccionado =-1;
              id_nodo_conectado    =-1;
              nodo_centro          =-1;


              for (var i=0;i<nro_clases;i++){
                if (grupos_por_clase[i]>2){
                  svg.select("#clase"+i).transition()
                   .duration(100)
                   .style("visibility","visible");
                }
              }
              var new_grupo=svg.selectAll(".grupo");
              var new_int=svg.selectAll(".integrante");

              new_grupo.transition().style("visibility","visible");
              new_int.transition().style("visibility","visible");

              //desactualizarNodosGruposMismoTamano();

              svg.selectAll("g.contenedor_grupo")
              //.transition().duration(200)
              //.style("visibility",function(d){
              //  return (related_nodes[d.index]!=0) ? "visible" : "hidden";
              .style("display","inline");

              var newLinks= svg.selectAll(".link_grupal")
                .transition()
                .duration(500)
                .style("stroke", general_view_edge_color)
                .style("stroke-width",stroke_width_general_view)
                .style("visibility","visible")
                .style("opacity",function(d){
                  return scalingTiempoLinks(ponderacionTiempoLinkGrupal(d[0].index,d[2].index));
                });

              svg.selectAll(".link_info_grupal")
                .style("visibility","hidden");

            }

            self_centered = false;
            center_node = -1;

            //Se vuelve el texto del nombre de usuario a vacío dado a que no hay ningún nodo en el centro del análisis
            $(".text-username").html("");
            ocultarDatosUsuarios();

            if (vista_individual){
              var vista_activa=0;
            }else{
              var vista_activa=1;
            }
            //Registra la vuelta de la perspectiva selfcentered a perspectiva de clase (de red)
            $.ajax({ 
              type: "POST", 
              url: "log_accion_sesion.php", 
              data:{accion:"vis_cambio_vista" , id_sesion: id_sesion , id_experiencia: id_experiencia, tipo_cambio_vista:1,
                    vista_transicion:vista_activa, perspectiva_transicion: 0}
            });

            ocultarDatosParticipacion();
            if (vista_individual){
              force_individual.resume();
            }else{
              force_grupal.resume();
            }
            
          }
          

          
      }
    });
  $("input[name='view']").click(function() {
        var valorInput=parseInt(this.value);
        
        if (vista_individual!=valorInput){
          vista_individual = valorInput;
          console.log(valorInput);
          if (!vista_individual){

            //Quita la propiedad del click a los nodos individuales para que no interfieran con los grupales
            svg.selectAll(".node").on("click",null);

            //Agrega la propiedad del click a los nodos grupales
            svg.selectAll("g.contenedor_grupo")
              .on("mouseover", fadeGrupal(.1,true))
              .on("mouseout", fadeGrupal(1,false))
              .on("mousedown", function() { d3.event.stopPropagation(); })
              .on("click", clickNode)
              //.on("dblclick",doubleClickNode)
              .call(force_grupal.drag);

            activarVistaGrupal();

            //Registrar el cambio de vista individual a grupal
            $.ajax({ 
              type: "POST", 
              url: "log_accion_sesion.php", 
              data:{accion:"vis_cambio_vista" , id_sesion: id_sesion , id_experiencia: id_experiencia, tipo_cambio_vista:0,
                    vista_transicion:1, perspectiva_transicion: 0},
              async:true
            });
            
            //force_grupal.resume();
          }else{
           //Quita la propiedad del click a los nodos grupales para que no interfieran con los grupales
            svg.selectAll("g.contenedor_grupo")
              .on("click",null);

            activarVistaIndividual();

            //Agrega la propiedad del click a los nodos individuales
            svg.selectAll(".node").on("click",clickNode);

            //Registrar el cambio de vista grupal a individual
            $.ajax({ 
              type: "POST", 
              url: "log_accion_sesion.php", 
              data:{accion:"vis_cambio_vista" , id_sesion: id_sesion , id_experiencia: id_experiencia, tipo_cambio_vista:0,
                    vista_transicion: 0, perspectiva_transicion: 0},
              async:true
            });


            //force_individual.resume();

          }
        }     
  });
  $("#boton_ayuda").click(function(){
    if (!clickAyuda){
      desplegarAyudaVis();
    }else{
      ocultarAyudaVis();
    }
  });
  $("#boton_volver_red").click(function(){
    $(".canvas").click();
  });
})

function desplegarAyudaVis(){
  //svg.style("opacity",0.4);
  //if (!self_centered){
      /*for (var i=0; i<array_id_divs_ayuda_persp_clase.length;i++){
        $("#"+array_id_divs_ayuda_persp_clase[i]).css("display","inline");
      }
      divs_ayuda_activos=array_id_divs_ayuda_persp_clase.length;*/
      activarAyudaPerspClase=false;
      cargarVideo("https://www.youtube.com/watch?v=Z52QQG1hboo&index=3&list=LLz2gzT3so90YLFtZzVdDlnQ");
  //}else{
    /*$("#div-usershistory").css("opacity",0.4);
      for (var i=0; i<array_id_divs_ayuda_persp_selfcentered.length ;i++){
        $("#"+array_id_divs_ayuda_persp_selfcentered[i]).css("display","inline");
      }
      divs_ayuda_activos=array_id_divs_ayuda_persp_selfcentered.length;*/
      //activarAyudaPerspSelfCentered=false;
  //}
  clickAyuda=true;
}


function ocultarAyudaVis(){
  /*svg.style("opacity",1);
  if (!self_centered){
    for (var i=0; i<array_id_divs_ayuda_persp_clase.length;i++){
      $("#"+array_id_divs_ayuda_persp_clase[i]).css("display","none");
    }
  }else{
    $("#div-usershistory").css("opacity",1);
    for (var i=0; i<array_id_divs_ayuda_persp_selfcentered.length;i++){
      $("#"+array_id_divs_ayuda_persp_selfcentered[i]).css("display","none");
    }
  }*/
  divs_ayuda_activos=0;
  clickAyuda=false;
}
function isConnected(a, b) {
    return linkedByIndex[a.index + "," + b.index] || linkedByIndex[b.index + "," + a.index] || a.index == b.index;
}

function ponderacionTiempoLinkIndividual(a,b){//1 más reciente, 0 más antiguo
  return antiguedadLinkIndividual[a+ "," + b] || antiguedadLinkIndividual[b + "," + a] || a == b;
}

function ponderacionTiempoLinkGrupal(a,b){
  return antiguedadLinkGrupal[a+ "," + b] || antiguedadLinkGrupal[b + "," + a] || a == b;
}

function isConnectedToTeam(a,b){//a y b deben ser los índices de cada nodo grupal
    return linkedByTeam[a + "," + b] || linkedByTeam[b + "," + a] || a == b;
}

function obtenerHistorialParticipacion(indice_nodo){
    return historialParticipacion[indice_nodo];
}

function obtenerHistorialParticipacionGrupal(indice_nodo){
    return historialParticipacionGrupal[indice_nodo];
}

function obtenerHistorialInteracciones(indice_centro,indice_nodo){
    return historialInteracciones[indice_centro + "," + indice_nodo] || historialInteracciones[indice_nodo + "," + indice_centro];
}

function obtenerRespuestasInteracciones(indice_centro,indice_nodo){
    var array_respuestas_interacciones=respuestasInteracciones[indice_centro + "," + indice_nodo];
    if (!array_respuestas_interacciones){
      array_respuestas_interacciones=respuestasInteracciones[indice_nodo + "," + indice_centro].slice();
      var elem0_array=array_respuestas_interacciones[0];
      array_respuestas_interacciones[0]=array_respuestas_interacciones[1];
      array_respuestas_interacciones[1]=elem0_array;
    }else{
      array_respuestas_interacciones=respuestasInteracciones[indice_centro + "," + indice_nodo].slice();
    }
    return array_respuestas_interacciones;
}

function obtenerRespuestasInteraccionesGrupales(indice_centro,indice_nodo){
    var array_respuestas_interacciones=respuestasInteraccionesGrupales[indice_centro + "," + indice_nodo];
    if (!array_respuestas_interacciones){
      array_respuestas_interacciones=respuestasInteraccionesGrupales[indice_nodo + "," + indice_centro].slice();
      var elem0_array=array_respuestas_interacciones[0];
      array_respuestas_interacciones[0]=array_respuestas_interacciones[1];
      array_respuestas_interacciones[1]=elem0_array;
    }else{
      array_respuestas_interacciones=respuestasInteraccionesGrupales[indice_centro + "," + indice_nodo].slice();
    }
    return array_respuestas_interacciones;
}

function obtenerMeGustaInteracciones(indice_centro,indice_nodo){
    var array_megusta_interacciones=megustaInteracciones[indice_centro + "," + indice_nodo];
    if (!array_megusta_interacciones){
      array_megusta_interacciones=megustaInteracciones[indice_nodo + "," + indice_centro].slice();//el .slice() clona el arreglo en la variable, así no lo pasa por referencia y no se modifica
      var elem0_array=array_megusta_interacciones[0];
      array_megusta_interacciones[0]=array_megusta_interacciones[1];
      array_megusta_interacciones[1]=elem0_array;
    }else{
      array_megusta_interacciones=megustaInteracciones[indice_centro + "," + indice_nodo].slice();//el .slice() clona el arreglo en la variable, así no lo pasa por referencia y no se modifica
    }
    return array_megusta_interacciones;
}

function obtenerMeGustaInteraccionesGrupales(indice_centro,indice_nodo){
    var array_megusta_interacciones=megustaInteraccionesGrupales[indice_centro + "," + indice_nodo];
    if (!array_megusta_interacciones){
      array_megusta_interacciones=megustaInteraccionesGrupales[indice_nodo + "," + indice_centro].slice();//el .slice() clona el arreglo en la variable, así no lo pasa por referencia y no se modifica
      var elem0_array=array_megusta_interacciones[0];
      array_megusta_interacciones[0]=array_megusta_interacciones[1];
      array_megusta_interacciones[1]=elem0_array;
    }else{
      array_megusta_interacciones=megustaInteraccionesGrupales[indice_centro + "," + indice_nodo].slice();//el .slice() clona el arreglo en la variable, así no lo pasa por referencia y no se modifica
    }
    return array_megusta_interacciones;
}


function obtenerHistorialInteraccionesGrupales(indice_centro,indice_nodo){
    return historialInteraccionesGrupales[indice_centro + "," + indice_nodo] || historialInteraccionesGrupales[indice_nodo + "," + indice_centro];
}

function desplegarRegistroInteraccion(registro){
  var tipo_interaccion =registro.tipo_interaccion;
  var diferencia_tiempo=registro.diferencia_tiempo;
  var nombre_emisor    =registro.emisor;
  var nombre_receptor  =registro.receptor;
  var id_mensaje       =registro.id_mensaje;
  var fecha            =registro.fecha;
  var id_emisor        =registro.id_emisor;
  var id_receptor      =registro.id_receptor;
  if (tipo_interaccion==0){//Mensajes de respuesta
    var respuesta = registro.mensaje;
    var mensaje_objetivo =escape(registro.mensaje_objetivo);
    //$('#list-usershistory').append('<li><img src="/img/comentarios_16.png">'+diferencia_tiempo+' '+nombre_emisor+' respondió al <a href=#'+id_mensaje+' >comentario</a> escrito por '+nombre_receptor+' : <i>"'+respuesta+'"</i></li>');
    //$('#list-usershistory').append('<li><img src="/img/comentarios_16.png"> '+nombre_emisor+' respondió al comentario de '+nombre_receptor+' : <i>"'+respuesta+'"</i> - '+diferencia_tiempo+'</li>');
    //console.log(nombre_emisor+"dio me gusta al mensaje de"+ nombre_receptor+": "+mensaje_objetivo);
    $('#list-usershistory').append('<li><img src="/app/dataviz/img/mensajes_interaccion_bicolor.png" width=23px height=22px"> '+nombre_emisor+' <?php echo $lang_hv_act_comento; ?> <a href="" onclick="cargarMensajePopUp(\''+mensaje_objetivo+'\') ;return false;" name="Respuesta '+id_mensaje+' '+id_emisor+'-'+id_receptor+'"><?php echo $lang_hv_act_publicacion_de; ?> '+nombre_receptor+'</a> : <i>"'+respuesta+'"</i> - '+fecha+'</li>');
  }
  else{
    if (tipo_interaccion==1){//Me gusta
      var mensaje_objetivo =escape(registro.mensaje_objetivo);
      //$('#list-usershistory').append('<li><img src="/img/me_gusta.jpg">'+diferencia_tiempo+' '+nombre_emisor+' dió me gusta al <a href=#'+id_mensaje+' >comentario</a> escrito por '+nombre_receptor+'</li>');
      //$('#list-usershistory').append('<li><img src="/img/me_gusta.jpg"> '+nombre_emisor+' dió me gusta al comentario de '+nombre_receptor+' - '+diferencia_tiempo+'</li>');
      //console.log(nombre_emisor+"dio me gusta al mensaje de"+ nombre_receptor+": "+mensaje_objetivo);
      $('#list-usershistory').append('<li><img src="/app/dataviz/img/megusta_verde.png" width=20px height=20px"> <?php echo $lang_hv_act_A; ?> '+nombre_emisor+' <?php echo $lang_hv_act_le_gusta_la; ?> <a href="" onclick="cargarMensajePopUp(\''+mensaje_objetivo+'\'); return false;" name="MeGusta msje '+id_mensaje+' '+id_emisor+'-'+id_receptor+'"><?php echo $lang_hv_act_publicacion_de; ?> '+nombre_receptor+'</a> - '+fecha+'</li>');
    }else{
      if (tipo_interaccion==2){//Nuevo comentario
        var mensaje = registro.mensaje;
        //$('#list-usershistory').append('<li><img src="/img/comentarios.gif" width=16px height=16px>'+diferencia_tiempo+' '+nombre_emisor+' escribió un <a href=#'+id_mensaje+' >comentario</a> en la bitácora : <i>'+mensaje+'</i></li>');
        //$('#list-usershistory').append('<li><img src="/img/comentarios.gif" width=16px height=16px> '+nombre_emisor+' escribió un comentario en la bitácora : <i>'+mensaje+'</i> - '+diferencia_tiempo+'</li>');
        $('#list-usershistory').append('<li><img src="/app/dataviz/img/comentarios_amarillo.png" width=20px height=20px> '+nombre_emisor+' <?php echo $lang_hv_act_escribio_bit; ?> : <i>'+mensaje+'</i> - '+fecha+'</li>');
      }
    }
  }
}

function obtenerNumeroAmigos(index_nodo){
  var nro_amigos=0;
   dataLinksUsuarios.forEach(function(link) {
      if (link.source==index_nodo || link.target==index_nodo){
        nro_amigos++;
      }
   });
   return nro_amigos;
}

function obtenerNumeroGruposAmigos(index_nodo){
  var nro_amigos=0;
   dataLinksGrupales.forEach(function(link) {
      if (link.source==index_nodo || link.target==index_nodo){
        nro_amigos++;
      }
   });
   return nro_amigos;
}

//Función que obtiene el número de comentarios (mensajes nuevos y mensajes de respuesta) que un cierto usuario ha recibido en la bitácora
function obtenerNumeroComentarios(index_nodo){
  var total_msjes=0;
  var nro_msjes=dataUsuarios[index_nodo].mensajes;
  var nro_msjes_rpta=dataUsuarios[index_nodo].mensajes_respuesta;
  total_msjes=total_msjes+nro_msjes+nro_msjes_rpta;
  return total_msjes;
}

//Función que obtiene el número de comentarios (mensajes nuevos y mensajes de respuesta) que un cierto grupo ha recibido en la bitácora
function obtenerNumeroComentariosGrupales(index_nodo){
  var total_msjes=0;
  var nro_msjes=dataGrupos[index_nodo].mensajes;
  var nro_msjes_rpta=dataGrupos[index_nodo].mensajes_respuesta;
  total_msjes=total_msjes+nro_msjes+nro_msjes_rpta;
  return total_msjes;
}

//Función que obtiene el número de mensajes de respuesta que un cierto usuario ha recibido en la bitácora
function obtenerNumeroRespuestasRecibidas(index_nodo){
  var respuestas_recibidas=dataUsuarios[index_nodo].mensajes_respuesta_recibidos;
  return respuestas_recibidas;
}

//Función que obtiene el número de mensajes de respuesta que un cierto grupo ha recibido en la bitácora
function obtenerNumeroRespuestasRecibidasGrupales(index_nodo){
  var respuestas_recibidas=dataGrupos[index_nodo].mensajes_respuesta_recibidos;
  return respuestas_recibidas;
}

//Función que obtiene el número de me gusta que un cierto usuario ha dejado en la bitácora
function obtenerNumeroMeGustaDados(index_nodo){
  var megusta=dataUsuarios[index_nodo].megusta;
  return megusta;
}

//Función que obtiene el número de me gusta que un cierto grupo ha dejado en la bitácora
function obtenerNumeroMeGustaDadosGrupales(index_nodo){
  var megusta=dataGrupos[index_nodo].megusta;
  return megusta;
}

//Función que obtiene el número de me gusta que un cierto usuario ha recibido en la bitácora
function obtenerNumeroMeGustaRecibidos(index_nodo){
  var megusta_recibidos=dataUsuarios[index_nodo].megusta_recibidos;
  return megusta_recibidos;
}

//Función que obtiene el número de me gusta que un cierto grupo ha recibido en la bitácora
function obtenerNumeroMeGustaRecibidosGrupales(index_nodo){
  var megusta_recibidos=dataGrupos[index_nodo].megusta_recibidos;
  return megusta_recibidos;
}

function desplegarDatosParticipacionIndividual(index_nodo){
  //Código que añade datos numéricos acerca de la participación del usuario

  //$(".div-datos").css("text-align","initial");
  $("#div-user1").css("width","100%");
  $("#div-user2").css("width","0%");
  //$(".text-username").css("text-align","initial");

  var nro_amigos= obtenerNumeroAmigos(index_nodo);
  $("#cell-dato1a").empty();
  $("#cell-dato1a").html("<span id='dato1a' class='dato'>"+nro_amigos+"</span><img src='/app/dataviz/img/icono_usuario_naranjo.png' title='<?php echo $lang_hv_act_num_pers_interactuado; ?>' width=25 height=25 style='vertical-align:middle'>");

  var nro_comentarios= obtenerNumeroComentarios(index_nodo);
  $("#cell-dato2a").empty();
  $("#cell-dato2a").html("<span id='dato2a' class='dato'>"+nro_comentarios+"</span><img src='/app/dataviz/img/comentarios_emitidos_amarillo.png' title='<?php echo $lang_hv_act_num_msj_escritos; ?>' width=25 height=25 style='vertical-align:middle'>");

  var nro_rptas_recibidas= obtenerNumeroRespuestasRecibidas(index_nodo);
  $("#cell-dato3a").empty();
  $("#cell-dato3a").html("<span id='dato3a' class='dato'>"+nro_rptas_recibidas+"</span><img src='/app/dataviz/img/comentarios_recibidos_amarillo.png' title='<?php echo $lang_hv_num_resp_msj_recibido; ?>' width=25 height=25 style='vertical-align:middle'>");
  
  var nro_megusta= obtenerNumeroMeGustaDados(index_nodo);
  $("#cell-dato2b").empty();
  $("#cell-dato2b").html("<span id='dato2b' class='dato'>"+nro_megusta+"</span><img src='/app/dataviz/img/megusta_emitidos_verde.png' title='<?php echo $lang_hv_num_mg_dados; ?>' width=25 height=25 style='vertical-align:middle'>");
  
  var nro_megusta_recibidos= obtenerNumeroMeGustaRecibidos(index_nodo);
  $("#cell-dato3b").empty();
  $("#cell-dato3b").html("<span id='dato3b' class='dato'>"+nro_megusta_recibidos+"</span><img src='/app/dataviz/img/megusta_recibidos_verde.png' title='<?php echo $lang_hv_num_mg_recibidos; ?>' width=25 height=25 style='vertical-align:middle'>");
 
}

function actualizarDespliegueDatosParticipacionIndividual(index_nodo){
  //Código que actualiza los datos numéricos asociados a la participación del usuario, solo si es necesario

  //$(".div-datos").css("text-align","initial");
  $("#div-user1").css("width","100%");
  $("#div-user2").css("width","0%");
  //$(".text-username").css("text-align","initial");

  var nro_amigos= obtenerNumeroAmigos(index_nodo);
  var nro_amigos_anterior = parseInt($("#dato1a").html());
  if (nro_amigos_anterior!=nro_amigos){
  $("#dato1a").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_amigos);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var nro_comentarios= obtenerNumeroComentarios(index_nodo);
  var nro_comentarios_anterior= parseInt($("#dato2a").html());
  if (nro_comentarios_anterior!=nro_comentarios){
    $("#dato2a").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_comentarios);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var nro_rptas_recibidas= obtenerNumeroRespuestasRecibidas(index_nodo);
  var nro_rptas_recibidas_anterior= parseInt($("#dato3a").html());
  if (nro_rptas_recibidas_anterior!=nro_rptas_recibidas){
    $("#dato3a").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_rptas_recibidas);
      $(this).fadeTo(transicion_datos,1);
    });
  }
  
  var nro_megusta= obtenerNumeroMeGustaDados(index_nodo);
  var nro_megusta_anterior= parseInt($("#dato2b").html());
  if (nro_megusta_anterior!=nro_megusta){
    $("#dato2b").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_megusta);
      $(this).fadeTo(transicion_datos,1);
    });
  }
  
  var nro_megusta_recibidos= obtenerNumeroMeGustaRecibidos(index_nodo);
  var nro_megusta_recibidos_anterior= parseInt($("#dato3b").html());
  if (nro_megusta_recibidos_anterior!=nro_megusta_recibidos){
    $("#dato3b").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_megusta);
      $(this).fadeTo(transicion_datos,1);
    });
  }
}

function actualizarDespliegueDatosParticipacionGrupal(index_nodo){
  //Código que actualiza los datos numéricos asociados a la participación de un grupo en particular, solo si es necesario

  //$(".div-datos").css("text-align","initial");
  $("#div-user1").css("width","100%");
  $("#div-user2").css("width","0%");
  //$(".text-username").css("text-align","initial");

  var nro_amigos= obtenerNumeroGruposAmigos(index_nodo);
  var nro_amigos_anterior = parseInt($("#dato1a").html());
  if (nro_amigos_anterior!=nro_amigos){
    $("#dato1a").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_amigos);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var nro_comentarios= obtenerNumeroComentariosGrupales(index_nodo);
  var nro_comentarios_anterior= parseInt($("#dato2a").html());
  if (nro_comentarios_anterior!=nro_comentarios){
    $("#dato2a").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_comentarios);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var nro_rptas_recibidas= obtenerNumeroRespuestasRecibidasGrupales(index_nodo);
  var nro_rptas_recibidas_anterior= parseInt($("#dato3a").html());
  if (nro_rptas_recibidas_anterior!=nro_rptas_recibidas){
    $("#dato3a").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_rptas_recibidas);
      $(this).fadeTo(transicion_datos,1);
    });
  }
  
  var nro_megusta= obtenerNumeroMeGustaDadosGrupales(index_nodo);
  var nro_megusta_anterior= parseInt($("#dato2b").html());
  if (nro_megusta_anterior!=nro_megusta){
    $("#dato2b").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_megusta);
      $(this).fadeTo(transicion_datos,1);
    });
  }
  
  var nro_megusta_recibidos= obtenerNumeroMeGustaRecibidosGrupales(index_nodo);
  var nro_megusta_recibidos_anterior= parseInt($("#dato3b").html());
  if (nro_megusta_recibidos_anterior!=nro_megusta_recibidos){
    $("#dato3b").fadeTo(transicion_datos,0,function(){
      $(this).html(nro_megusta);
      $(this).fadeTo(transicion_datos,1);
    });
  }
}

function ocultarDatosParticipacion(){
  //$(".dato").empty();
  $(".cell-dato").empty();
}

function ocultarDatosUsuarios(){
  $(".text-username").empty();
  $(".text-classname").empty();
}

function desplegarDatosParticipacionGrupal(index_nodo){
  //Código que añade datos numéricos acerca de la participación del usuario
  //$(".div-datos").css("text-align","initial");
  $("#div-user1").css("width","100%");
  $("#div-user2").css("width","0%");
  //$(".text-username").css("text-align","initial");

  var nro_grupos_amigos= obtenerNumeroGruposAmigos(index_nodo);
  $("#cell-dato1a").empty();
  $("#cell-dato1a").html("<span id='dato1a' class='dato'>"+nro_grupos_amigos+"</span><img src='/app/dataviz/img/icono_grupo_naranjo.png' title='<?php echo $lang_hv_num_grupos_interactuado; ?>' width=30 height=30 style='vertical-align:middle'>");

  var nro_comentarios= obtenerNumeroComentariosGrupales(index_nodo);
  $("#cell-dato2a").empty();
  $("#cell-dato2a").html("<span id='dato2a' class='dato'>"+nro_comentarios+"</span><img src='/app/dataviz/img/comentarios_emitidos_amarillo.png' title='<?php echo $lang_hv_num_msj_miembros_escrito; ?>' width=25 height=25 style='vertical-align:middle'>");

  var nro_rptas_recibidas= obtenerNumeroRespuestasRecibidasGrupales(index_nodo);
  $("#cell-dato3a").empty();
  $("#cell-dato3a").html("<span id='dato3a' class='dato'>"+nro_rptas_recibidas+"</span><img src='/app/dataviz/img/comentarios_recibidos_amarillo.png' title='<?php echo $lang_hv_num_resp_miembros_recibido; ?>' width=25 height=25 style='vertical-align:middle'>");
  
  var nro_megusta= obtenerNumeroMeGustaDadosGrupales(index_nodo);
  $("#cell-dato2b").empty();
  $("#cell-dato2b").html("<span id='dato2b' class='dato'>"+nro_megusta+"</span><img src='/app/dataviz/img/megusta_emitidos_verde.png' title='<?php echo $lang_hv_num_mg_miembros_dado; ?>' width=25 height=25 style='vertical-align:middle'>");
  
  var nro_megusta_recibidos= obtenerNumeroMeGustaRecibidosGrupales(index_nodo);
  $("#cell-dato3b").empty();
  $("#cell-dato3b").html("<span id='dato3b' class='dato'>"+nro_megusta_recibidos+"</span><img src='/app/dataviz/img/megusta_recibidos_verde.png' title='<?php echo $lang_hv_num_mg_miembros_recibido; ?>' width=25 height=25 style='vertical-align:middle'>");
}

function desplegarDatosInteraccionIndividual(nodo_centro,nodo){
  ocultarDatosParticipacion();
  //$("#dato2a").empty();
  //$(".div-datos").css("text-align","center");
  $("#div-user1").css("width","50%");
  $("#div-user2").css("width","50%");
  //$(".text-username").css("text-align","center");

  var respuestas_usuario=obtenerRespuestasInteracciones(nodo_centro.index,nodo.index);
  var megusta_usuario=obtenerMeGustaInteracciones(nodo_centro.index,nodo.index);

  $("#cell-dato1a").html("<span id='dato1a' class='dato'>"+respuestas_usuario[0]+"</span>");
  $("#cell-dato2a").html("<img src='/app/dataviz/img/mensajes_interaccion_bicolor.png' title='<?php echo $lang_hv_num_resp_msj_cada_uno; ?>' width=25 height=25 style='vertical-align:middle; margin-right:25px; margin-left:25px'>");
  $("#cell-dato3a").html("<span id='dato3a' class='dato'>"+respuestas_usuario[1]+"</span>");

  //$("#dato2b").empty();
  $("#cell-dato1b").html("<span id='dato1b' class='dato'>"+megusta_usuario[0]+"</span>");
  $("#cell-dato2b").html("<img src='/app/dataviz/img/megusta_verde.png' title='<?php echo $lang_hv_num_mg_dado_cada_uno; ?>' width=25 height=25 style='vertical-align:middle; margin-right:25px; margin-left:25px'>");
  $("#cell-dato3b").html("<span id='dato3b' class='dato'>"+megusta_usuario[1])+"</span>";

}

function actualizarDespliegueDatosInteraccionIndividual(nodo_centro,nodo){
  //ocultarDatosParticipacion();
  //$("#dato2a").empty();
  //$(".div-datos").css("text-align","center");
  $("#div-user1").css("width","50%");
  $("#div-user2").css("width","50%");
  //$(".text-username").css("text-align","center");

  var respuestas_usuario=obtenerRespuestasInteracciones(nodo_centro.index,nodo.index);
  var megusta_usuario=obtenerMeGustaInteracciones(nodo_centro.index,nodo.index);

  var rptas_usuario1=respuestas_usuario[0];
  var rptas_usuario1_anterior=parseInt($("#dato1a").html());

  if (rptas_usuario1!=rptas_usuario1_anterior){
    $("#dato1a").fadeTo(transicion_datos,0,function(){
      $(this).html(rptas_usuario1);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var rptas_usuario2=respuestas_usuario[1];
  var rptas_usuario2_anterior=parseInt($("#dato3a").html());
  if (rptas_usuario2!=rptas_usuario2_anterior){
    $("#dato3a").fadeTo(transicion_datos,0,function(){
      $(this).html(rptas_usuario2);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var mg_usuario1=megusta_usuario[0];
  var mg_usuario1_anterior=parseInt($("#dato1b").html());
  if (mg_usuario1!=mg_usuario1_anterior){
    $("#dato1b").fadeTo(transicion_datos,0,function(){
      $(this).html(mg_usuario1);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var mg_usuario2=megusta_usuario[1];
  var mg_usuario2_anterior=parseInt($("#dato3b").html());
  if (mg_usuario2!=mg_usuario2_anterior){
    $("#dato3b").fadeTo(transicion_datos,0,function(){
      $(this).html(mg_usuario2);
      $(this).fadeTo(transicion_datos,1);
    });
  }
}

function desplegarDatosInteraccionGrupal(nodo_centro,nodo){
  ocultarDatosParticipacion();
  //$("#dato2a").empty();
  //$(".div-datos").css("text-align","center");
  $("#div-user1").css("width","50%");
  $("#div-user2").css("width","50%");
  //$(".text-username").css("text-align","center");

  var respuestas_grupo=obtenerRespuestasInteraccionesGrupales(nodo_centro.index,nodo.index);
  var megusta_grupo=obtenerMeGustaInteraccionesGrupales(nodo_centro.index,nodo.index);
  
  $("#cell-dato1a").html("<span id='dato1a' class='dato'>"+respuestas_grupo[0]+"</span>");
  $("#cell-dato2a").html("<img src='/app/dataviz/img/mensajes_interaccion_bicolor.png' title='<?php echo $lang_hv_num_resp_msj_cad_uno_escrito; ?>' width=25 height=25 style='vertical-align:middle;'>");
  $("#cell-dato3a").html("<span id='dato3a' class='dato'>"+respuestas_grupo[1]+"</span>");

  //$("#dato2b").empty();
  $("#cell-dato1b").html("<span id='dato1b' class='dato'>"+megusta_grupo[0]+"</span>");
  $("#cell-dato2b").html("<img src='/app/dataviz/img/megusta_verde.png' title='<?php echo $lang_hv_num_mg_cada_grupo_dado; ?>' width=25 height=25 style='vertical-align:middle;'>");
  $("#cell-dato3b").html("<span id='dato3b' class='dato'>"+megusta_grupo[1]+"</span>");


}

function actualizarDespliegueDatosInteraccionGrupal(nodo_centro,nodo){
  //ocultarDatosParticipacion();
  //$("#dato2a").empty();
  //$(".div-datos").css("text-align","center");
  $("#div-user1").css("width","50%");
  $("#div-user2").css("width","50%");
  //$(".text-username").css("text-align","center");

  var respuestas_grupo=obtenerRespuestasInteraccionesGrupales(nodo_centro.index,nodo.index);
  var megusta_grupo=obtenerMeGustaInteraccionesGrupales(nodo_centro.index,nodo.index);

  var rptas_grupo1=respuestas_grupo[0];
  var rptas_grupo1_anterior=parseInt($("#dato1a").html());

  if (rptas_grupo1!=rptas_grupo1_anterior){
    $("#dato1a").fadeTo(transicion_datos,0,function(){
      $(this).html(rptas_grupo1);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var rptas_grupo2=respuestas_grupo[1];
  var rptas_grupo2_anterior=parseInt($("#dato3a").html());
  if (rptas_grupo2!=rptas_grupo2_anterior){
    $("#dato3a").fadeTo(transicion_datos,0,function(){
      $(this).html(rptas_grupo2);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var mg_grupo1=megusta_grupo[0];
  var mg_grupo1_anterior=parseInt($("#dato1b").html());
  if (mg_grupo1!=mg_grupo1_anterior){
    $("#dato1b").fadeTo(transicion_datos,0,function(){
      $(this).html(mg_grupo1);
      $(this).fadeTo(transicion_datos,1);
    });
  }

  var mg_grupo2=megusta_grupo[1];
  var mg_grupo2_anterior=parseInt($("#dato3b").html());
  if (mg_grupo2!=mg_grupo2_anterior){
    $("#dato3b").fadeTo(transicion_datos,0,function(){
      $(this).html(mg_grupo2);
      $(this).fadeTo(transicion_datos,1);
    });
  }
}

function layoutVistaIndividual(dataUsuarios, dataLinksUsuarios) {
   scalingIndividual = d3.scale.log()
      .domain([d3.min(dataUsuarios, function(d){return d.participacion}), d3.max(dataUsuarios, function(d){return d.participacion})])
      .range([min_radius_ind, max_radius_ind]);

    scalingLinkIndividual = d3.scale.log()
      .domain([d3.min(dataLinksUsuarios, function(d){return d.total_interacciones;}), d3.max(dataLinksUsuarios, function(d){return d.total_interacciones;})])
      .range([stroke_width_general_view, 1.8*min_radius_ind]);
  
   var update = function() {
      //append nodes and links from data
      nodesIndividuales=dataUsuarios.slice();
      linksIndividuales=[];
      bilinksIndividuales=[];

      nodesIndividuales.forEach(function(d){
        var randomPosNeg=Math.random();
        var posNeg=1;
        if (randomPosNeg<0.5) posNeg=-1;
        //console.log("Usuario id: "+d.id_usuario+" id_exp: "+d.id_experiencia);
        d.x=centros_clases[d.id_experiencia].x+50*Math.random()*posNeg;
        d.y=centros_clases[d.id_experiencia].y+50*Math.random()*posNeg;
      });

      dataLinksUsuarios.forEach(function(link) {
        var s = nodesIndividuales[link.source],
            t = nodesIndividuales[link.target],
            i = {}; // intermediate node
        nodesIndividuales.push(i);
        linksIndividuales.push({source: s, target: i}, {source: i, target: t});
        bilinksIndividuales.push([s, i, t]);
      });

      force_individual= d3.layout.force()
                          .size([width, height]);
      var nodes = force_individual.nodes(nodesIndividuales);
      var links = force_individual.links(linksIndividuales);

      var k = Math.sqrt(dataUsuarios.length / (width * height));
      force_individual.charge(-2/k)
                      .gravity(2*k)
                      .friction(0.85)
                      //.alpha(0.02)
                      .start();
      /*force_individual.charge(-300)
                      .start();*/

      var link = svg.selectAll(".link")
          .data(bilinksIndividuales)
        .enter().append("path")
          .attr("class", "link")
          .attr("id", function(d){
            return "link"+d[0].index+"-"+d[2].index;
          })
          .attr("pointer-events", "none")
          .style("stroke",general_view_edge_color)
          .style("stroke-width",stroke_width_general_view)
          .style("fill","none")
          .style("opacity", function(d){
            return scalingTiempoLinks(ponderacionTiempoLinkIndividual(d[0].index,d[2].index));
          });
          //.style("stroke-width", function(d) { return Math.sqrt(d.total_interacciones); });
      
      var infolinks= svg.selectAll(".link_info")
          .data(bilinksIndividuales)
        .enter().append("path")
          .attr("class", "link_info")
          .attr("pointer-events", "visible")
          .style("stroke",general_view_edge_color)
          .style("stroke-width",20)
          .on("click", clickLink(.15))
          .on("mouseover",fadeLinkIndividual(.15,true))
          .on("mouseout",fadeLinkIndividual(1,false))
          .style("opacity",0);


      var defs = svg.append("defs");
      defs.selectAll("clipPath")
          .data(dataUsuarios)
          .enter().append("clipPath")
            .attr("id", function(d){return "clipnode"+d.id_usuario;})
            .attr("class","clipPath_node")
            .append("circle")
            .attr("cx", 0)
            .attr("cy", 0)
            .attr("r",function(d){
              return ratio_foto*scalingIndividual(d.participacion);});


      var node = svg.append("g")
        .selectAll(".node")
          .data(dataUsuarios)
        .enter().append("g")
          .attr("class", "node")
          .attr("id", function(d){return "node"+d.id_usuario;})
          .on("mouseover", fadeIndividual(.1,true))
          .on("mouseout", fadeIndividual(1,false))
          .on("mousedown", function() { d3.event.stopPropagation(); })
          .on("click", clickNode)
          //.on("dblclick",doubleClickNode)
          .call(force_individual.drag); 

      node.append("circle")
          .attr("r", function(d){
            console.log(d.participacion+" "+d.nombre);
            return (1+width_border_ratio)*scalingIndividual(d.participacion);
          })
          .style("fill", function(d) { return (d.id_grupo!=-1) ?color_grupos(d.id_grupo):"#00000";});//mapeo_idgrupo[d.id_experiencia][d.id_grupo]); });

      node.append("image")
          .attr("xlink:href",function(d){return d.url_imagen})
          .attr("x",function(d){return -1*ratio_foto*scalingIndividual(d.participacion);})
          .attr("y",function(d){return -1*ratio_foto*scalingIndividual(d.participacion);})
          .attr("width",function(d){return 2*ratio_foto*scalingIndividual(d.participacion);})
          .attr("height",function(d){return 2*ratio_foto*scalingIndividual(d.participacion);})
          .attr("clip-path",function(d){return "url(#clipnode"+d.id_usuario+")";});

      node.append("svg:text")
          .attr("class","nodetext")
          .attr("text-anchor", "middle")
          .attr("dy", function(d){ return (1+width_border_ratio)*scalingIndividual(d.participacion)+8;})
          .text(function(d) {
            if (d.id_usuario==id_usuario){
              return "<?php echo $lang_hv_yo; ?>";
            }else{
              var indice_substring=d.nombre.indexOf(' ');
              return d.nombre.slice(0,indice_substring);
            }
          });

      //Agrega un ícono de profesor, para que sea más fácil de detectar visualmente al manipular la red de usuarios
      node.filter(function(d){return d.id_grupo==0;})
          .append("image")
          .attr("class","imagen_profesor")
          .attr("xlink:href","/img/profesor.png")
          .attr("title","<?php echo $lang_hv_num_mg_profesor; ?>")
          .attr("x",-1*(tamano_img_prof/2))
          .attr("y",function(d){return -(1+width_border_ratio)*scalingIndividual(d.participacion)-(tamano_img_prof-1);})
          .attr("width",tamano_img_prof)
          .attr("height",tamano_img_prof);

      force_individual.on("tick",function(e){
        var visualizacion_activa=$("#visualizacion_actividad").is(":visible");
        if (visualizacion_activa && force_individual){
          /*if (!self_centered){
            if (force_individual.alpha()>0.075){
              var x_usuario=svg.select("#node"+id_usuario).attr("cx");
              var y_usuario=svg.select("#node"+id_usuario).attr("cy");

              //svg.attr("transform", "translate("+((-1*x_usuario)+(width/2))+","+((-1*y_usuario)+(height/2))+")");
            }else{
              //svg.attr("transform", "translate(" + margin.left + "," + margin.right + ")");
            }
          }*/
          //var k = .4 * e.alpha;

          var k = 0.75*e.alpha;

          var nodes=dataUsuarios;

          var q = d3.geom.quadtree(nodes),
                  i = 0,
                  n = nodes.length;

          if (vista_individual){
            if (self_centered){
              /*if (arrayMaxMinInteraccion[nodo_centro]){
                var scalingDistance = d3.scale.linear()
                        .domain([arrayMaxMinInteraccion[nodo_centro].min,arrayMaxMinInteraccion[nodo_centro].max])
                        .range([fixed_node_radius*(1+width_border_ratio),max_radius_radial_layout-2.75*fixed_node_radius*(1+width_border_ratio)]);
              }*/
              

              /*svg.selectAll(".node")
                .attr("cx", function(d) { return d.x; })
                .attr("cy", function(d) { return d.y; });*/


              //Código que permite agregar usuarios con los que han interactuado tus contactos
              /*var max_usuarios_recomendados=15;

              var j = 0;
                  n_related_nodes=0;
              while (j < n){
                if (j!=nodo_centro && related_nodes[j] == 1){
                  for (var k=0;k<n;k++){
                    if (related_nodes[k]==0 && isConnected(nodes[j],nodes[k]) && n_related_nodes<max_usuarios_recomendados){
                      related_nodes[k]=2;
                      n_related_nodes++;
                    }
                  }
                }
                j++;
              }
              
              var ith_relnode=0;
              for (var l=0;l<n;l++){
                if (related_nodes[l]==2){
                  
                  var distance_radius = max_radius_radial_layout;
                  var theta= ith_relnode / n_related_nodes * 2 * Math.PI;
                        nodes[l].x=distance_radius*Math.cos(theta)+width*offset_posx_radial_layout;
                        nodes[l].y=distance_radius*Math.sin(theta)+height/2;
                        ith_relnode++;
                }
              }*/

              //var t0 = svg.transition().duration(300).ease("linear");

              //var center_node=svg.
              //t0.select("#node"+dataUsuarios[nodo_centro].id_usuario)
                //.transition().duration(100)
                //.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });;

              
              /*var trans_nodes=svg.selectAll(".node").filter(function(d){ return d.id_usuario!==dataUsuarios[nodo_centro].id_usuario;});
              //t0.selectAll(".node")
              trans_nodes.transition().delay(150).duration(100)
                .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });*/

              //t0.selectAll(".node").filter(function(d){ return d.id_usuario!==dataUsuarios[nodo_centro].id_usuario;}).attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

              //t0.selectAll(".link")
              svg.selectAll(".link")//.transition().duration(250)
                .attr("d", function(d) {
                /*if (d[0].index===nodo_centro || d[2].index===nodo_centro) console.log("d0 "+d[0].nombre+" d2 "+d[2].nombre);
                if (d[0].index==nodo_centro){*/
                  return "M" + svg.select("#node"+dataUsuarios[d[0].index].id_usuario).attr("cx") + "," + svg.select("#node"+dataUsuarios[d[0].index].id_usuario).attr("cy")
                    + "L" + svg.select("#node"+dataUsuarios[d[2].index].id_usuario).attr("cx") + "," + svg.select("#node"+dataUsuarios[d[2].index].id_usuario).attr("cy");
                  /*}else{
                    if(d[2].index==nodo_centro){
                      return "M" + d[0].x + "," + d[0].y
                    + "L" + d[2].x + "," + d[2].y;
                    }
                  }*/
                });
                
                /*.style("stroke-width", function(d){
                      return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkIndividual(isConnected(d[0],d[2])) : stroke_width_general_view;
                });*/

              

              /*var t1 = t0.transition();
              t1.selectAll(".link").style("stroke-width", function(d){
                      return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkIndividual(isConnected(d[0],d[2])) : stroke_width_general_view;
                });*/
              
              /*svg.selectAll(".link").transition().delay(0).duration(100).style("stroke-width", function(d){
                      return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkIndividual(isConnected(d[0],d[2])) : stroke_width_general_view;
                });*/

              svg.selectAll(".link_info")
                .attr("d", function(d) {
                /*return "M" + d[0].x + "," + d[0].y
                    + "L" + d[2].x + "," + d[2].y;});*/
                return "M" + svg.select("#node"+dataUsuarios[d[0].index].id_usuario).attr("cx") + "," + svg.select("#node"+dataUsuarios[d[0].index].id_usuario).attr("cy")
                    + "L" + svg.select("#node"+dataUsuarios[d[2].index].id_usuario).attr("cx") + "," + svg.select("#node"+dataUsuarios[d[2].index].id_usuario).attr("cy");});
                
              /*var newLinks= svg.selectAll(".link")
                .style("stroke",function(d){
                  if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
                    return color_grupos(mapeo_idgrupo[nodes[nodo_centro].id_experiencia][nodes[nodo_centro].id_grupo]);
                  }
                })
                .style("stroke-width",stroke_width_self_centered)
                .style("visibility",function(d){
                  //return (d[0]!=nodo_centro || d[2]!=nodo_centro) ?  "visible" : "hidden";
                  if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
                    return "visible";
                  }else{
                    return "hidden";
                  }
                });*/
                

            }else{

              //Código agregado post-test usabilidad
              /*for (var j=0;j<nodes.length;j++){
                nodes[j].fixed=false;
              }*/
              if (!transicion){
                nodes.forEach(function(o, i) {
                    o.y += (centros_clases[o.id_experiencia].y - o.y) * k;
                    o.x += (centros_clases[o.id_experiencia].x - o.x) * k;
                });

                while (i < n){
                    q.visit(collide(nodes[i]));
                    i++;
                }

                svg.selectAll(".node")
                  .attr("cx", function(d) { return d.x; })
                  .attr("cy", function(d) { return d.y; })
                  //.style("visibility","visible");
                  .style("display","inline");

                //Sección de código que permite adherir la etiqueta al nodo del usuario de la sesión activa
                if (tooltip){
                  var nodo_usuario=svg.select("#node"+id_usuario);
                  var x_usuario=Number(nodo_usuario.attr("cx"));
                  var y_usuario=Number(nodo_usuario.attr("cy"));
                  var r_usuario=Number(nodo_usuario.select("circle").attr("r"));//*(1+width_border_ratio);
                  tooltip.attr("transform", "translate("+(x_usuario+r_usuario)+","+(y_usuario-10)+")");
                  tooltip.moveToFront();
                }

                svg.selectAll(".link")
                  .attr("d", function(d) {
                  return "M" + d[0].x + "," + d[0].y
                      + "S" + d[1].x + "," + d[1].y
                      + " " + d[2].x + "," + d[2].y;
                  /*return "M" + d[0].x + "," + d[0].y
                      + "L" + d[2].x + "," + d[2].y;*/
                });

                /*svg.selectAll(".link")
                  .transition()
                  .duration(100)
                  .style("stroke-width", stroke_width_general_view);*/

                node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
                
                for (var i=0;i<nro_clases;i++){
                  if (usuarios_por_clase[i]>2){
                    svg.select("#clase"+i)
                    .data([d3.geom.hull(nodes.filter(function(d){ return d.id_experiencia===i;})
                                            .map(function(d){ return [ d.x, d.y ]; }))])
                                            .attr("d", function(d) { 
                                              var npoints=d.length;                                   
                                              var curveHull="M"+d[0][0] + "," + d[0][1];
                                              
                                              for (var j=0;j<npoints;j=j+1){
                                                if (j==npoints-1){
                                                  var dx = d[0][0] - d[j][0],
                                                      dy = d[0][1] - d[j][1],
                                                      dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                                                  curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[0][0] + "," + d[0][1]+"Z";

                                                }else{
                                                  var dx = d[j+1][0] - d[j][0],
                                                      dy = d[j+1][1] - d[j][1],
                                                      dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                                                  curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[j+1][0] + "," + d[j+1][1];
                                                }
                                                
                                              }

                                              return curveHull;})
                                            .style("visibility","visible");
                    /*svg.selectAll(".texto_nube")
                        .attr("transform",function(d){return "translate("+d.parent.x+","+d.parent.y+")";});*/

                  }else{
                    /*svg.select("#clase"+i)
                     .transition()
                     .duration(100)
                     .style("visibility","hidden");*/
                    svg.select("#clase"+i)
                      .data([dataUsuarios.filter(function(d){ return d.id_experiencia===i;})
                        .map(function(d){ return [ d.x, d.y ]; })])
                      .attr("d", function(d) {
                        var npoints=d.length;
                        var curveHull="M"+d[0][0] + "," + d[0][1];
                                                
                        for (var j=0;j<npoints;j=j+1){
                          if (j==npoints-1){
                            var dx = d[0][0] - d[j][0],
                                dy = d[0][1] - d[j][1],
                                dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                            curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[0][0] + "," + d[0][1]+"Z";

                          }else{
                            var dx = d[j+1][0] - d[j][0],
                                dy = d[j+1][1] - d[j][1],
                                dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                            curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[j+1][0] + "," + d[j+1][1];
                          } 
                        }
                        return curveHull;
                      })
                      .style("visibility","visible");
                  }
                }
              }else{
                svg.selectAll(".integrante")
                  .select(function(d){
                    var fila_json=d.fila_json;
                    var fila_json_grupo=d.parent.fila_json;
                    var grupo=array_svg_grupos[fila_json_grupo];
                    var r_grupo=grupo.data()[0].r;
                    var x_grupo=grupo.x+d.x;//-r_grupo;
                    var y_grupo=grupo.y+d.y;//-r_grupo;
                    nodesIndividuales[fila_json].x=x_grupo;
                    nodesIndividuales[fila_json].y=y_grupo;
                  });

                node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
              }
            }
          }else{
            if (!transicion){
              /*svg.selectAll(".node")
                .attr("cx", function(d) { return svg.select("#integrante"+d.id_usuario).attr("cx"); })
                .attr("cy", function(d) { return svg.select("#integrante"+d.id_usuario).attr("cy"); })
                .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });*/
              svg.selectAll(".integrante")
              .select(function(d){
                var fila_json=d.fila_json;
                var fila_json_grupo=d.parent.fila_json;
                var grupo=array_svg_grupos[fila_json_grupo];
                var r_grupo=grupo.data()[0].r;
                var x_grupo=grupo.x+d.x;//-r_grupo;
                var y_grupo=grupo.y+d.y;//-r_grupo;
                nodesIndividuales[fila_json].x=x_grupo;
                nodesIndividuales[fila_json].y=y_grupo;
              });
              
              svg.selectAll(".link")//.transition().duration(250)
                .attr("d", function(d) {
                  return "M" + d[0].x + "," + d[0].y
                    + "S" + d[1].x + "," + d[1].y
                    + " " + d[2].x + "," + d[2].y;
              });

            }else{

              /*svg.selectAll(".node")
                .attr("cx", function(d) { return d.x; })
                .attr("cy", function(d) { return d.y; });*/
              
              svg.selectAll(".link")//.transition().duration(250)
                .attr("d", function(d,i) {
                  var x0=svg.select("#node"+dataUsuarios[d[0].index].id_usuario).attr("cx");
                  var y0=svg.select("#node"+dataUsuarios[d[0].index].id_usuario).attr("cy");
                  var x2=svg.select("#node"+dataUsuarios[d[2].index].id_usuario).attr("cx");
                  var y2=svg.select("#node"+dataUsuarios[d[2].index].id_usuario).attr("cy");
                  var x1=(parseInt(x0)+parseInt(x2))/2;
                  var y1=(parseInt(y0)+parseInt(y2))/2+coord_links_transgrupal[i];
                  /*return "M" + x0 + "," + y0
                    + "S" + x1 + "," + y1
                    + " " + x2 + "," + y2;*/
                  return "M" + x0 + "," + y0
                    + "S" + x1 + "," + y1
                    + " " + x2 + "," + y2;
              });
            }
            /*svg.selectAll(".integrante")
              .transition()
              .select(function(d){
                var fila_json=d.fila_json;
                var fila_json_grupo=d.parent.fila_json;
                var grupo=array_svg_grupos[fila_json_grupo];
                var r_grupo=grupo.data()[0].r;
                var x_grupo=grupo.x+d.x;//-r_grupo;
                var y_grupo=grupo.y+d.y;//-r_grupo;
                nodes[fila_json].x=x_grupo;
                nodes[fila_json].y=y_grupo;
              });*/


              
          }
          

        
        }else{
          force_individual=null;//Se hace null para prevenir que después de cambiar de pestaña, el force layout siga ejecutando simulacion
          isscrollpane=false;
        }
      });
   }
   update();
   

   function collide(node) {
          var r = scalingIndividual(node.radius) +node_space_ind,
              nx1 = node.x - r,
              nx2 = node.x + r,
              ny1 = node.y - r,
              ny2 = node.y + r;
          return function(quad, x1, y1, x2, y2) {
            if (quad.point && (quad.point !== node)) {
              var x = node.x - quad.point.x,
                  y = node.y - quad.point.y,
                  l = Math.sqrt(x * x + y * y);
                  if (node.id_experiencia==quad.point.id_experiencia){
                    r = scalingIndividual(node.participacion) + scalingIndividual(quad.point.participacion)+node_space_ind;
                  }else{
                    r = scalingIndividual(node.participacion) + scalingIndividual(quad.point.participacion)+node_space_clases;
                  }
              if (l < r) {
                l = (l - r) / l * .5;
                node.x -= x *= l;
                node.y -= y *= l;
                quad.point.x += x;
                quad.point.y += y;
              }
            }
            return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
          };
        }
 }

function layoutVistaGrupal(dataUsuarios,dataGrupos,dataLinksGrupales) {
  scalingGrupal = d3.scale.log()
    .domain([d3.min(dataGrupos.filter(function(d){ return d.children && d.children.length>0;}), function(d){return d.participacion_total}), d3.max(dataGrupos, function(d){return d.participacion_total})])
    //.domain([d3.min(dataGrupos, function(d){return d.participacion_total}), d3.max(dataGrupos, function(d){return d.participacion_total})])
    .range([min_radius, max_radius]);

  scalingIndividual = d3.scale.log()
      .domain([d3.min(dataUsuarios, function(d){return d.participacion}), d3.max(dataUsuarios, function(d){return d.participacion})])
      .range([min_radius_ind, max_radius_ind]);

  /*console.log("Min part grupal: "+d3.min(dataGrupos, function(d){return d.participacion_total;}));
  console.log("Max part grupal: "+d3.max(dataGrupos, function(d){return d.participacion_total;}));*/

  console.log("Min interacciones: "+d3.min(dataLinksGrupales, function(d){return d.total_interacciones;}));
  console.log("Max interacciones: "+d3.max(dataLinksGrupales, function(d){return d.total_interacciones;}));
  //Fin código post-test de usabilidad

  var update = function() {
      var svg_grupo;
      var svg_nodo_grupo= svg.append("g");

      for(var i=0;i<dataGrupos.length;i++){
        if (dataGrupos[i]["children"] && dataGrupos[i]["children"].length>0){
          var tamano_grupo=scalingGrupal(dataGrupos[i]["participacion_total"]);
          pack_layout_array[i] = d3.layout.pack()
            //.size([2*tamano_grupo,2*tamano_grupo])
            .value(function(d) { return d.size; })
            .radius(function(d){ return ratio_foto*scalingIndividual(d);});

          svg_grupo=svg_nodo_grupo.append("g")
                    .attr("class","contenedor_grupo")
                    .attr("id","cont_grupo"+dataGrupos[i]["fila_json"]);


          array_svg_grupos[i]=svg_grupo.datum(dataGrupos[i])
                                .selectAll(".componente_nodo_grupal")
                                .data(pack_layout_array[i].nodes)
                              .enter().append("g")
                                .attr("class", function(d) { return d.children ? "componente_nodo_grupal grupo" : "componente_nodo_grupal integrante"; })
                                .attr("id",function(d) { 
                                  if (d.children){
                                    return "grupo"+d.fila_json;
                                  }else{
                                    if (d.id_usuario==id_usuario){
                                      id_grupo=d.parent.fila_json;
                                    }
                                    return "integrante"+d.id_usuario;
                                  }
                                })
                                //.attr("transform", function(d) { return d.children ? "translate(" + (d.x-d.r) + "," +(d.y-d.r) + ")" : "translate(" + (d.x-d.parent.r) + "," +  (d.y-d.parent.r)+ ")"; })
                                .attr("transform", function(d) { return  "translate(" + d.x + "," +d.y + ")";})
                                  .append("circle")
                                    .attr("x",0)
                                    .attr("y",0)
                                    .attr("r", function(d) {
                                      if (!d.children){
                                        dataUsuarios[d.fila_json].r_grupo=d.r
                                      }
                                      return d.r; })
                                    .style("fill", function(d) { return d.children ? color_grupos(d.id_grupo) : null;})//mapeo_idgrupo[d.id_experiencia][d.id_grupo]) : null; })
                                    .style("opacity",0)
                                    .style("display","none");
                                    
                                    
        }else{
          svg_grupo=svg_nodo_grupo.append("g")
                    .attr("class","contenedor_grupo")
                    .attr("id","cont_grupo"+dataGrupos[i]["fila_json"]);

          array_svg_grupos[i]=svg_grupo;
        }               
      }

      nodesGrupales=array_svg_grupos.slice();
      linksGrupales=[];
      bilinksGrupales=[];

      nodesGrupales.forEach(function(d){
        var datagrupo=d.data()[0];
        if (datagrupo){
          var randomPosNeg=Math.random();
          var posNeg=1;
          if (randomPosNeg<0.5) posNeg=-1;
          d.x=centros_clases[datagrupo.id_experiencia].x+50*Math.random()*posNeg;
          d.y=centros_clases[datagrupo.id_experiencia].y+50*Math.random()*posNeg;
        }
      });

      dataLinksGrupales.forEach(function(link) {
        var s = nodesGrupales[link.source],
            t = nodesGrupales[link.target],
            i = {}; // intermediate node
        nodesGrupales.push(i);
        linksGrupales.push({source: s, target: i}, {source: i, target: t});
        bilinksGrupales.push([s, i, t]);
      });

      force_grupal = d3.layout.force()
                              .size([width, height]);
      var nodes = force_grupal.nodes(nodesGrupales);
      var links = force_grupal.links(linksGrupales);

      var k = Math.sqrt(dataGrupos.length / (width * height));

      force_grupal.charge(-2/k)
                      .gravity(2*k)
                      .friction(0.85)
                      .start();

      var link = svg.selectAll(".link_grupal")
          .data(bilinksGrupales)
        .enter().append("path")
          .attr("class", "link_grupal")
          .attr("id", function(d){ return "link_grupal"+d[0].index+"-"+d[2].index;})
          .attr("pointer-events", "none")
          .style("stroke",general_view_edge_color)
          .style("stroke-width",stroke_width_general_view)
          .style("fill","none")
          .style("opacity",0);

      var link_info_grupal = svg.selectAll(".link_info_grupal")
          .data(bilinksGrupales)
        .enter().append("path")
          .attr("class", "link_info_grupal")
          .attr("pointer-events", "visible")
          .style("stroke",general_view_edge_color)
          .style("stroke-width",20)
          .on("click", clickLink(.15))
          .on("mouseover",fadeLinkGrupal(0.15,true))
          .on("mouseout",fadeLinkGrupal(1,false))
          .style("opacity",0)
          .style("visibility","hidden");

      var defs = svg.append("defs");
      defs.selectAll("clipPath")
          .data(dataUsuarios)
          .enter().append("clipPath")
            .attr("id", function(d){return "int"+d.id_usuario;})
            .attr("class","clipPath_int")
            .append("circle")
            .attr("cx", 0)
            .attr("cy", 0)
            .attr("r",function(d){
            return d.r_grupo;});//return participacion_grupos[d.id_usuario];});

      svg.selectAll(".integrante")
        .append("image")
          .attr("xlink:href",function(d){return d.url_imagen})
          .attr("x",function(d){return -1*d.r;})
          .attr("y",function(d){return -1*d.r;})
          .attr("width",function(d){return 2*d.r;})
          .attr("height",function(d){return 2*d.r;})
          .attr("clip-path",function(d){return "url(#int"+d.id_usuario+")";})
          .style("opacity",0)
          .style("display","none");
      
      /*svg.selectAll(".integrante")
        .append("text")
          .attr("class","grouptext")
          .attr("dx", function(d){return d.r;})
          .attr("dy", "0.5em")
          .text(function(d){
            var nombre_completo=dataUsuarios[d.fila_json].nombre;
            var indice_primer_nombre=nombre_completo.indexOf(" ");
            if (indice_primer_nombre==-1) indice_primer_nombre=nombre_completo.length;
            return nombre_completo.substring(0,indice_primer_nombre);
          })
          .style("display","none");*/

      //Código agregado post-test usabilidad
      /*svg.selectAll(".integrante")
        .style("display","none");*/

      svg.selectAll(".grupo")
        .append("svg:text")
            .attr("class","grouptext")
            .attr("text-anchor","middle")
            .attr("dy", "1em")//function(d){ return d.r;})
            .attr("transform",function(d){ return "translate(0,"+d.r+")";})
            //.style("display","none")
            .text(function(d) { return d.nombre });

      var node = svg.selectAll("g.contenedor_grupo")
                .data(array_svg_grupos);/*
                .call(force_grupal.drag)
                .on("mouseover", fadeGrupal(.1,true))
                .on("mouseout", fadeGrupal(1,false))
                .on("click", clickNode);*/


      //Sección que permite llevar al fondo los links grupales, los cuales dado el orden de creación, quedaban sobre los nodos grupales
      svg.selectAll(".link_info_grupal").moveToBack();
      svg.selectAll(".link_grupal").moveToBack();
      svg.selectAll("clipPath").moveToBack();

      svg.selectAll("g.integrante")
        .on("mouseover",fadeIntegrante(true))
        .on("mouseout",fadeIntegrante(false));
      //svg.selectAll(".radialLayout").moveToBack();
      //fin modificación links grupales

      setMaxMinRadiusGrupal();

      //Código post-test de usabilidad
      scalingLinkGrupal = d3.scale.log()
          .domain([d3.min(dataLinksGrupales, function(d){return d.total_interacciones;}), d3.max(dataLinksGrupales, function(d){return d.total_interacciones;})])
          .range([stroke_width_general_view, 1.5*min_radius]);

      force_grupal.on("tick",function(e){
        var visualizacion_activa=$("#visualizacion_actividad").is(":visible");
        if (visualizacion_activa && force_grupal){

          var k = 0.75*e.alpha;

          var nodes=array_svg_grupos;
          var  q = d3.geom.quadtree(nodes);
              i = 0,
              n = nodes.length;

          if (!vista_individual){//Si está activada la vista grupal
            if (self_centered){
              if (arrayMaxMinInteraccionGrupal[nodo_centro]){
                var scalingDistance = d3.scale.linear()
                        .domain([arrayMaxMinInteraccionGrupal[nodo_centro].min,arrayMaxMinInteraccionGrupal[nodo_centro].max])
                        .range([2*fixed_node_grupo_radius,max_radius_radial_layout-2.5*fixed_node_grupo_radius]);
              }    
              /*var related_nodes=new Array(n);
              for (var a=0;a<n;a++){
                related_nodes[a]=0;
              }
              var n_connected_center=nodes[nodo_centro].weight;
              var c=0;
              while (i < n){
              //if (self_centered){
                if (i==nodo_centro){
                  related_nodes[i]=1;
                  nodes[i].x=width*offset_posx_radial_layout;                  
                }else{
                  distance_radius=isConnectedToTeam(nodes[nodo_centro].index,nodes[i].index);

                  if (!distance_radius) {
                    distance_radius = max_radius_radial_layout;

                  }else{
                    related_nodes[i]=1;
                    var theta= c / n_connected_center * 2 * Math.PI - (Math.PI/2);
                    distance_radius = max_radius_radial_layout;// - scalingDistance(distance_radius);
                    nodes[i].x=distance_radius*Math.cos(theta)+width*offset_posx_radial_layout;
                    nodes[i].y=distance_radius*Math.sin(theta)+height/2;
                    c++;
                  }
                }
                i++;
              }*/

              //Código que permite agregar usuarios con los que han interactuado tus contactos
              /*var max_usuarios_recomendados=15;

              var j = 0;
                  n_related_nodes=0;
              while (j < n){
                if (j!=nodo_centro && related_nodes[j] == 1){
                  for (var k=0;k<n;k++){
                    if (related_nodes[k]==0 && isConnectedToTeam(nodes[j].index,nodes[k].index) && n_related_nodes<max_usuarios_recomendados){
                      related_nodes[k]=2;
                      n_related_nodes++;
                    }
                  }
                }
                j++;
              }
              
              var ith_relnode=0;
              for (var l=0;l<n;l++){
                if (related_nodes[l]==2){
                  
                  var distance_radius = max_radius_radial_layout;
                  var theta= ith_relnode / n_related_nodes * 2 * Math.PI;
                        nodes[l].x=distance_radius*Math.cos(theta)+width*offset_posx_radial_layout;
                        nodes[l].y=distance_radius*Math.sin(theta)+height/2;
                        ith_relnode++;
                }
              }*/

              /*svg.selectAll(".contenedor_grupo")
                .attr("cx", function(d) { return d.x; })
                .attr("cy", function(d) { return d.y; });*/

             /* svg.selectAll(".grupo")
                .style("visibility",function(d){
                  if (self_centered) return (related_nodes[d.fila_json]!=0) ? "visible": "hidden";
                });

              svg.selectAll(".integrante")
                .style("visibility",function(d){
                  if (self_centered) return (related_nodes[d.parent.fila_json]!=0) ? "visible": "hidden";
                });*/
                /*.style("visibility",function(d){
                  if (self_centered) return (related_nodes[d.fila_json]!=0) ? "visible" : "hidden";
              });*/

              svg.selectAll(".link_grupal")
                .attr("d", function(d) {
                /*return "M" + d[0].x + "," + d[0].y
                    + "L" + d[2].x + "," + d[2].y;*/
                return "M" + svg.select("#cont_grupo"+d[0].index).attr("cx") + "," + svg.select("#cont_grupo"+d[0].index).attr("cy")
                    + "L" + svg.select("#cont_grupo"+d[2].index).attr("cx") + "," + svg.select("#cont_grupo"+d[2].index).attr("cy");
              });
                /*.style("stroke-width", function(d){
                      return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkGrupal(isConnectedToTeam(d[0].index,d[2].index)):stroke_width_general_view;
                });*/

              svg.selectAll(".link_info_grupal")
                .attr("d", function(d) {
                /*return "M" + d[0].x + "," + d[0].y
                    + "L" + d[2].x + "," + d[2].y;*/
                return "M" + svg.select("#cont_grupo"+d[0].index).attr("cx") + "," + svg.select("#cont_grupo"+d[0].index).attr("cy")
                    + "L" + svg.select("#cont_grupo"+d[2].index).attr("cx") + "," + svg.select("#cont_grupo"+d[2].index).attr("cy");
              });

            }else{
              nodes.forEach(function(o, i) {
                  o.y += (centros_clases[dataGrupos[i].id_experiencia].y - o.y) * k;
                  o.x += (centros_clases[dataGrupos[i].id_experiencia].x - o.x) * k;
              });

              while (i < n){
                q.visit(collide(nodes[i]));
                i++;
              }

              svg.selectAll(".contenedor_grupo")
                .attr("cx", function(d) { return d.x; })
                .attr("cy", function(d) { return d.y; });

              //Sección de código que permite adherir la etiqueta al nodo del usuario de la sesión activa
              if(tooltip){
                if (id_grupo!=-1){
                  var nodo_grupo=svg.select("#cont_grupo"+id_grupo);
                  var x_grupo=Number(nodo_grupo.attr("cx"));
                  var y_grupo=Number(nodo_grupo.attr("cy"));
                  var r_grupo=Number(svg.select("#grupo"+id_grupo).select("circle").attr("r"));//*(1+width_border_ratio);
                  tooltip.attr("transform", "translate("+(x_grupo+r_grupo)+","+(y_grupo-10)+")");
                  //tooltip.moveToFront();
                }else{
                  tooltip.style("visibility","hidden");
                }
              }

              svg.selectAll(".link_grupal")
                .attr("d", function(d) {
                  return "M" + d[0].x + "," + d[0].y
                      + "S" + d[1].x + "," + d[1].y
                      + " " + d[2].x + "," + d[2].y;
              });

              svg.selectAll(".link_info_grupal")
                .attr("d", function(d) {
                  return "M" + d[0].x + "," + d[0].y
                      + "S" + d[1].x + "," + d[1].y
                      + " " + d[2].x + "," + d[2].y;
              });

              for (var i=0;i<nro_clases;i++){
                if (grupos_por_clase[i]>2){
                  //console.log("Experiencia "+i);
                  svg.select("#clase"+i)
                  .data([d3.geom.hull(dataGrupos.filter(function(d){ return d.children && d.children.length>0 && d.id_experiencia===i && d.id_grupo!=-1;})
                                          .map(function(d){ 
                                            var data_grupo=array_svg_grupos[d.fila_json];
                                            return [ data_grupo.x, data_grupo.y ]; }))])
                                          .attr("d", function(d) {
                                            var npoints=d.length;                                   
                                            var curveHull="M"+d[0][0] + "," + d[0][1];
                                            
                                            for (var j=0;j<npoints;j=j+1){
                                              if (j==npoints-1){
                                                var dx = d[0][0] - d[j][0],
                                                    dy = d[0][1] - d[j][1],
                                                    dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                                                curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[0][0] + "," + d[0][1]+"Z";

                                              }else{
                                                var dx = d[j+1][0] - d[j][0],
                                                    dy = d[j+1][1] - d[j][1],
                                                    dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                                                curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[j+1][0] + "," + d[j+1][1];
                                              }
                                              
                                            }

                                            return curveHull;})
                                          .style("visibility","visible")
                                          .moveToBack();
                }else{
                  /*svg.select("#clase"+i)
                     .transition()
                     //.duration(100)
                     .style("visibility","hidden");*/
                  if (grupos_por_clase[i]==2){
                    svg.select("#clase"+i)
                      .data([dataGrupos.filter(function(d){ return d.children && d.children.length>0 && d.id_experiencia===i && d.id_grupo!=-1;})
                        .map(function(d){ 
                                var data_grupo=array_svg_grupos[d.fila_json];
                                return [ data_grupo.x, data_grupo.y ]; })])
                      .attr("d", function(d) {
                        var npoints=d.length;
                        var curveHull="M"+d[0][0] + "," + d[0][1];
                                                
                        for (var j=0;j<npoints;j=j+1){
                          if (j==npoints-1){
                            var dx = d[0][0] - d[j][0],
                                dy = d[0][1] - d[j][1],
                                dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                            curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[0][0] + "," + d[0][1]+"Z";

                          }else{
                            var dx = d[j+1][0] - d[j][0],
                                dy = d[j+1][1] - d[j][1],
                                dr = Math.sqrt(dx * dx + dy * dy)*0.75;
                            curveHull+="A" + dr + "," + dr + " 0 0,0 " + d[j+1][0] + "," + d[j+1][1];
                          }
                          
                        }
                        return curveHull;
                      })
                      .style("visibility","visible")
                      .moveToBack();
                    }else{
                      svg.select("#clase"+i)
                       .transition()
                       .duration(100)
                       .style("visibility","hidden");
                    }
                }
              node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
              }
            }
          }

          //node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
        }else{
          force_grupal=null;//Se hace null para prevenir que después de cambiar de pestaña, el force layout siga ejecutando simulacion
        }
      });
   }
   update();

   

   function collide(node) {
    //console.log(svg.select("#grupo"+node.index+" circle").attr("r"));
    var r = dataGrupos[node.index].r +node_space_grup,
        nx1 = node.x - r,
        nx2 = node.x + r,
        ny1 = node.y - r,
        ny2 = node.y + r;
    return function(quad, x1, y1, x2, y2) {
      if (quad.point && (quad.point !== node)) {
        var x = node.x - quad.point.x,
            y = node.y - quad.point.y,
            l = Math.sqrt(x * x + y * y);
        if (dataGrupos[node.index].id_experiencia==dataGrupos[quad.point.index].id_experiencia){
            r = dataGrupos[node.index].r + dataGrupos[quad.point.index].r + node_space_grup;
        }else{
            r = dataGrupos[node.index].r + dataGrupos[quad.point.index].r + node_space_clases;
        }
        if (l < r) {
          l = (l - r) / l * .3;
          node.x -= x *= l;
          node.y -= y *= l;
          quad.point.x += x;
          quad.point.y += y;
        }
      }
      return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
    };
  }

}


function clickNode(node){
  if (d3.event.defaultPrevented) {
    if (!self_centered){
        console.log("Drag");
        //d3.event.stopPropagation();
        /*if (vista_individual){
          force_individual.alpha(0.05);
        }else{
          force_grupal.alpha(0.05);
        }*/
        return;
    }
  }

  console.log("Clickee "+node.index);
  d3.event.stopPropagation();
  
  //svg.attr("transform", "translate(0,0)scale(1)");

  svg.transition()
    .delay(200)
    .duration(500)
    .attr("transform", "translate("+zoom.translate()[0]+","+zoom.translate()[1]+")scale(1)");

  main_svg.call(not_zoom_not_pan);

  $("#div-radiobutton").css('visibility','hidden');
  //$(".imagen_profesor").css('visibility','hidden');
  //$("#simbologia").attr('src','/app/dataviz/img/simbologia_vis2.svg');

  ocultarAyudaVis();//Oculta la ayuda en caso de que haya estado activa para la perspectiva de clase

  //Hace invisible los clusteres de clases
  for (var i=0;i<nro_clases;i++){
    if (usuarios_por_clase[i]>0){
      svg.select("#clase"+i)
       .style("visibility","hidden");
    }
  }

  //$('#div-usershistory').css('display','inline-block');
  $('#titulo-historial').css('visibility','visible');
  $('#tooltip').css('visibility','hidden');
  $('#dato1b').empty();
  /*svg.select(".radialLayout")
        .transition()
        .style("display","inline");*/

  $("#control-container").css("visibility","hidden");

  if (vista_individual){
    var vista=0;
  }else{
    var vista=1;
  }

  var perspectiva=1;
  var elemento_accesado="";
  if (vista_individual){

    //Setea nodo individual del centro
    nodo_centro=node.index;
    elemento_accesado=node.id_usuario+'';
    id_nodo_seleccionado=node.id_usuario;
    //tamano_elemento=normalizar(parseFloat(svg.select("#node"+id_nodo_seleccionado+" circle").attr("r")),min_radius_ind,max_radius_ind);
    tamano_elemento=parseFloat(svg.select("#node"+id_nodo_seleccionado+" circle").attr("r")).toFixed(2);
    nodes=dataUsuarios.slice();

    var i=0;
    var n=dataUsuarios.length;

    var related_nodes=new Array(n);
    for (var a=0;a<n;a++){
      related_nodes[a]=0;
    }
    var n_connected_center=nodes[nodo_centro].weight;  
    var c=0;
    var centro_x=width*offset_posx_radial_layout-zoom.translate()[0];
    var centro_y=height/2-zoom.translate()[1];
    while (i < n){
      if (i==nodo_centro){
        related_nodes[i]=1;
        /*nodes[i].x=width*offset_posx_radial_layout;
        nodes[i].y=height/2;*/
        nodes[i].x=centro_x;
        nodes[i].y=centro_y;
        nodes[i].px=nodes[i].x;
        nodes[i].py=nodes[i].y; 
      }else{
        distance_radius=isConnected(nodes[nodo_centro],nodes[i]);
        if (!distance_radius) {
          //distance_radius = max_radius_radial_layout;
          //Código agregado post-test usabilidad
          //Código necesario para que el fondo sea visible al clickear un nodo
          //nodes[i].fixed=true;
        }else{
          related_nodes[i]=1;
          var theta= c / n_connected_center * 2 * Math.PI - (Math.PI/2);
          distance_radius = height/2 - max_radius_ind;// - scalingDistance(distance_radius);
          nodes[i].x=distance_radius*Math.cos(theta)+centro_x;
          nodes[i].y=distance_radius*Math.sin(theta)+centro_y;
          nodes[i].px=nodes[i].x;
          nodes[i].py=nodes[i].y;
          c++;
        }
      }
      i++;
    }

    svg.selectAll(".node")
      .style("display",function(d){
        return (related_nodes[d.index]!=0) ? "inline" : "none";
    });

    //Código para permitir que se vea el resto de la red al hacer click en un nodo
    /*svg.selectAll(".node")
      .style("opacity",function(d){
        return (related_nodes[d.index]!=0) ? 1 : 0.05;
    })
      .attr("pointer-events",function(d){
        return (related_nodes[d.index]!=0) ? "initial" : "none";
    });*/

    svg.selectAll(".nodetext")
      .style("display",function(d){
        return (related_nodes[d.index]!=0) ? "inline" : "none";
    });

    svg.selectAll(".node").transition().duration(500).ease("sin")
      //.delay(200).duration(500).ease("sin")
      .attr("cx", function(d){ return d.x})
      .attr("cy", function(d){ return d.y})     
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .style("opacity",1);

    svg.selectAll(".link")
      .style("opacity",function(d){return scalingTiempoLinks(ponderacionTiempoLinkIndividual(d[0].index,d[2].index))})
      .style("stroke",general_view_edge_color);          ;

    svg.selectAll(".link").transition().delay(500).duration(500).ease("sin")
      .style("stroke-width", function(d){
        return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkIndividual(isConnected(d[0],d[2])) : stroke_width_general_view;
      });

    if (!self_centered){//El click individual solo servirá para pasar de la vista de red a la selfcentered
      zoom_activo=zoom.scale();

      self_centered=true;

      //Destaca nodo del centro del radial layout      
      //document.getElementById("node"+id_nodo_seleccionado).setAttribute("class","node seleccionado_selfcentered");

      //Muestra su historial de participación
      desplegarDatosParticipacionIndividual(nodo_centro);
      desplegarHistorialParticipacionIndividual(nodo_centro);
      mostrarDivHistorialAjustado();

      /*if (activarAyudaPerspSelfCentered){
        
        desplegarAyudaVis();
        $.ajax({ 
          type: "POST", 
          url: "log_accion_sesion.php", 
          data:{accion:"despliegue_ayuda" , id_usuario: id_usuario , ayuda_clase:-1, ayuda_selfcentered:0}
        }); 
      }*/

      perspectiva=0;

      //Registra el cambio de perspectiva a selfcentered
      $.ajax({ 
        type: "POST", 
        url: "log_accion_sesion.php",
        data:{accion:"vis_cambio_vista" , id_sesion: id_sesion , id_experiencia: id_experiencia, tipo_cambio_vista:1,
              vista_transicion:0, perspectiva_transicion: 1}
      }); 

      var newLinks= svg.selectAll(".link")
        //.transition()
        //.duration(100)
        .style("visibility",function(d){
            //return (d[0]!=nodo_centro || d[2]!=nodo_centro) ?  "visible" : "hidden";
            if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
              return "visible";
            }else{
              return "hidden";
            }
          });
        /*.style("stroke-width", function(d){
                      return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkIndividual(isConnected(d[0],d[2])) : stroke_width_general_view;
        });*/

      //Código añadido post-test de usabilidad
      var infoLinks= svg.selectAll(".link_info")
            .style("visibility",function(d){
              if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
                return "visible";
              }else{
                return "hidden";
              }
          })

      //Deja visible el botón de cómo volver a la red general
      $("#boton_volver_red").css("visibility","visible");
    }else{
        zoom_activo=1;
        //Se desactiva estado que destaca relacion entre dos nodos al hacer click en ella
        clickRelacion=false;
        //Quita la clase que destacaba nodos seleccionados en la vista selfcentered
        svg.selectAll(".seleccionado_selfcentered").attr("class","node");
        svg.selectAll(".conectado_selfcentered").attr("class","node");
        id_nodo_conectado=-1;

        vista=0;
        //En caso de que se haya consultado un usuario en particular
        /*if (id_nodo_seleccionado!=-1 && id_nodo_seleccionado!=node.id_usuario){
            var nodo_anterior=document.getElementById("node"+id_nodo_seleccionado);
            nodo_anterior.setAttribute("class","node");
            id_nodo_seleccionado=-1;
        }*/

        // Quita la clase del nodo conectado, que corresponde al mismo al cual se le hizo click
        /*if (id_nodo_conectado!=-1){
            var nodo_anterior_conectado=document.getElementById("node"+id_nodo_conectado);
            nodo_anterior_conectado.setAttribute("class","node");
            id_nodo_conectado=-1;
        }*/

        if (node.id_usuario==id_usuario){
          $('#text-username1').html('<span class="principal"><?php echo $lang_hv_yo; ?></span>');
        }else{
          $('#text-username1').html('<span class="principal">'+node.nombre+'</span>');
        }

        $('#text-classname1').text(node.nombre_clase);

        //Muestra su historial de participación
        desplegarDatosParticipacionIndividual(nodo_centro);
        desplegarHistorialParticipacionIndividual(nodo_centro);
        mostrarDivHistorialAjustado();

        
        var newLinks= svg.selectAll(".link")
          .style("opacity",function(d){return scalingTiempoLinks(ponderacionTiempoLinkIndividual(d[0].index,d[2].index));})
          .style("visibility",function(d){
            //return (d[0]!=nodo_centro || d[2]!=nodo_centro) ?  "visible" : "hidden";
            if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
              return "visible";
            }else{
              return "hidden";
            }
          });
          /*.style("stroke-width", function(d){
                      return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkIndividual(isConnected(d[0],d[2])) : stroke_width_general_view;
        });*/

        //Código añadido post-test de usabilidad
        var infoLinks= svg.selectAll(".link_info")
            .style("visibility",function(d){
              if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
                return "visible";
              }else{
                return "hidden";
              }
          })
    }

    force_individual.resume();
  }else{
    //Click nodo grupal

    //Setea nodo grupal del centro
    nodo_centro=node.index;
    id_nodo_seleccionado=node.index;
    elemento_accesado=dataGrupos[node.index].id_grupo_kelluwen+'';
    //tamano_elemento=normalizar(parseFloat(svg.select("#grupo"+id_nodo_seleccionado+" circle").attr("r")),min_radius,max_radius);
    tamano_elemento=parseFloat(svg.select("#grupo"+id_nodo_seleccionado+" circle").attr("r")).toFixed(2);
    var nodes      =array_svg_grupos.slice();
    var nodes_grupo=svg.selectAll(".grupo");
    var nodes_int  =svg.selectAll(".integrante");

    var i=0;
    var n=nodes.length;

    var related_nodes=new Array(n);
    for (var a=0;a<n;a++){
      related_nodes[a]=0;
    }
    var n_connected_center=nodes[nodo_centro].weight;  
    var c=0;
    var centro_x=width*offset_posx_radial_layout-zoom.translate()[0];
    var centro_y=height/2-zoom.translate()[1];
    while (i < n){
      if (i==nodo_centro){
        related_nodes[i]=1;
        /*nodes[i].x=width*offset_posx_radial_layout;
        nodes[i].y=height/2;*/
        nodes[i].x=centro_x;
        nodes[i].y=centro_y;
        nodes[i].px=nodes[i].x;
        nodes[i].py=nodes[i].y; 
      }else{
        distance_radius=isConnectedToTeam(nodes[nodo_centro].index,nodes[i].index);
        if (!distance_radius) {
          //distance_radius = max_radius_radial_layout;
          //Código agregado post-test usabilidad
          //nodes[i].fixed=true;
        }else{
          related_nodes[i]=1;
          var theta= c / n_connected_center * 2 * Math.PI - (Math.PI/2);
          distance_radius = height/2-max_radius + 15;// - scalingDistance(distance_radius);
          nodes[i].x=distance_radius*Math.cos(theta)+centro_x;
          nodes[i].y=distance_radius*Math.sin(theta)+centro_y;
          nodes[i].px=nodes[i].x;
          nodes[i].py=nodes[i].y;
          c++;
        }
      }
      i++;
    }

    svg.selectAll("g.contenedor_grupo")
      .style("display",function(d){
        return (related_nodes[d.index]!=0) ? "inline" : "none";
    });

    //Agrega etiquetas de texto a nodos seleccionados
    svg.selectAll(".grouptext")
      .style("display",function(d){
        return (related_nodes[d.index]!=0) ? "inline" : "none";
    });

    svg.selectAll("g.contenedor_grupo").transition().duration(500).ease("sin")
      //.delay(200).duration(500).ease("sin")
      .attr("cx", function(d){ return d.x})
      .attr("cy", function(d){ return d.y})
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .style("opacity",1);

    svg.selectAll(".link_grupal")
      .style("opacity",function(d){
        return scalingTiempoLinks(ponderacionTiempoLinkGrupal(d[0].index,d[2].index));
      })
      .style("stroke",general_view_edge_color);

    svg.selectAll(".link_grupal").transition().delay(500).duration(500).ease("sin")
      .style("stroke-width", function(d){
        return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkGrupal(isConnectedToTeam(d[0].index,d[2].index)):stroke_width_general_view;
      });

    
    if (!self_centered){//El click grupal solo servirá para pasar de la vista de red a la selfcentered
      
      zoom_activo=zoom.scale();

      self_centered=true;
      perspectiva=0;

      /*if (activarAyudaPerspSelfCentered){
        
        desplegarAyudaVis();
        $.ajax({ 
          type: "POST", 
          url: "log_accion_sesion.php", 
          data:{accion:"despliegue_ayuda" , id_usuario: id_usuario , ayuda_clase:-1, ayuda_selfcentered:0}
        }); 
      }*/

      //Destaca los links de los nodos conectados con el grupo accesado
      var newLinks= svg.selectAll(".link_grupal")
        /*.style("stroke",function(d){
          if (d[0].index===nodo_centro || d[2].index===nodo_centro){
            return color_grupos(mapeo_idgrupo[dataGrupos[nodo_centro].id_experiencia][dataGrupos[nodo_centro].id_grupo]);
          }
        })*/
        //.style("stroke-width",stroke_width_self_centered)
        .style("opacity",function(d){
          return scalingTiempoLinks(ponderacionTiempoLinkGrupal(d[0].index,d[2].index));
        })
        .style("visibility",function(d){
          if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
            return "visible";
          }else{
            return "hidden";
          }
        });

      //Código añadido post-test de usabilidad
      var infoLinksGrupales= svg.selectAll(".link_info_grupal")
            .style("visibility",function(d){
              if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
                return "visible";
              }else{
                return "hidden";
              }
      })

      //Despliega el historial de participación del grupo consultado
      desplegarDatosParticipacionGrupal(nodo_centro);
      desplegarHistorialParticipacionGrupal(nodo_centro);
      mostrarDivHistorialAjustado();
      //Quita etiquetas de texto a nodos asociados al hover anterior del nodo grupal
      /*nodes_grupo.selectAll(".grouptext")
              .style("display","none");*/

      //Deja visible el botón de cómo volver a la red general
      $("#boton_volver_red").css("visibility","visible");

    }else{
      zoom_activo=1;
      //Se desactiva estado que destaca relacion entre dos nodos al hacer click en ella
      clickRelacion=false;
      //Quita la clase que destacaba nodos seleccionados en la vista selfcentered
      svg.selectAll(".seleccionado_selfcentered").attr("class","grupo");
      svg.selectAll(".conectado_selfcentered").attr("class","grupo");

      vista=1;
      //var node=svg.selectAll(".grupo");
      //var int_node=svg.selectAll(".integrante");
      var link=svg.selectAll(".link_grupal");
      
      //En caso de que se haya consultado un grupo en particular
      /*if (id_nodo_seleccionado!=-1 && id_nodo_seleccionado!=node.index){
          var nodo_anterior=document.getElementById("grupo"+id_nodo_seleccionado);
          nodo_anterior.setAttribute("class","grupo");
          id_nodo_seleccionado=-1;
      }*/

      // Quita la clase del nodo conectado, que corresponde al mismo al cual se le hizo click
      /*if (id_nodo_conectado!=-1){
          var nodo_anterior_conectado=document.getElementById("grupo"+id_nodo_conectado);
          nodo_anterior_conectado.setAttribute("class","grupo");
          id_nodo_conectado=-1;
      }*/

      //Destaca nodo del centro del radial layout      
      //document.getElementById("grupo"+id_nodo_seleccionado).setAttribute("class","grupo seleccionado_selfcentered");

      var newLinks= svg.selectAll(".link_grupal")
        /*.style("stroke",function(d){
          if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
            return color_grupos(mapeo_idgrupo[dataGrupos[nodo_centro].id_experiencia][dataGrupos[nodo_centro].id_grupo]);
          }
        })*/
        //.style("stroke-width",stroke_width_self_centered)
        .style("opacity",function(d){
          return scalingTiempoLinks(ponderacionTiempoLinkGrupal(d[0].index,d[2].index));
        })
        .style("visibility",function(d){
          if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
            return "visible";
          }else{
            return "hidden";
          }
        });

      //Código añadido post-test de usabilidad
      var infoLinksGrupales= svg.selectAll(".link_info_grupal")
            .style("visibility",function(d){
              if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
                return "visible";
              }else{
                return "hidden";
              }
          })

      /*link.style("stroke",function(d){
        if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
          return color_grupos(nodo_centro);
        }
      });*/

      if (node.id_grupo==id_grupo){
        $('#text-username1').html('<span class="principal"><?php echo $lang_hv_mi_grupo; ?></span>');
      }else{
        $('#text-username1').html('<span class="principal">'+dataGrupos[node.index].nombre+'</span');
      }
      desplegarDatosParticipacionGrupal(nodo_centro);
      desplegarHistorialParticipacionGrupal(nodo_centro);
      mostrarDivHistorialAjustado();
      //$("#text-classname1").text("");
    }

    //force_grupal.resume();
      /*var link=svg.selectAll(".link_grupal");
      if (nodo_centro!=node.index){
        //Se deselecciona el nodo grupal anterior en caso de que haya habido uno seleccionado
        if (id_nodo_conectado!=-1 && id_nodo_conectado!=node.index){
          var nodo_anterior=document.getElementById("grupo"+id_nodo_conectado);
          nodo_anterior.setAttribute("class","grupo");
          /*link.style("stroke",function(d){
            if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
              return color_grupos(mapeo_idgrupo[dataGrupos[nodo_centro].id_experiencia][dataGrupos[nodo_centro].id_grupo]);
            }
          });*/
          /*id_nodo_conectado=-1;
        }

        /*
        //Destaca al nodo grupal seleccionado y su arista respectiva que lo une al grupo del centro
        id_nodo_conectado=node.index;
        var nodo_conectado=document.getElementById("grupo"+id_nodo_conectado);
        nodo_conectado.setAttribute("class","grupo conectado_selfcentered");
        elemento_accesado=dataGrupos[nodo_centro].id_grupo_kelluwen+"-"+id_nodo_conectado;

        //Destaca la arista que conecta al nodo con el nodo grupal del centro
        link.style("stroke", function(o) {
          var index1=o[0].index;
          var index2=o[2].index;
          if((index1 === node.index && index2 === nodo_centro) || (index1 === nodo_centro && index2 === node.index)){
            return "#FFF";
          }else{
            return this;
          }
        });
        */
        /*$('#text-username').html('<span class=principal>'+dataGrupos[nodo_centro].nombre+' y '+dataGrupos[node.index].nombre+'</span>');
        var historial = obtenerHistorialInteraccionesGrupales(nodo_centro,node.index);
        $('#list-usershistory').empty();
        if (historial && historial.length>0){
          var l=historial.length;
          if (l>max_interacciones_desplegadas) l=max_interacciones_desplegadas
          for (var i=0;i<l;i++){
            desplegarRegistroInteraccion(historial[i]);
          }
        }else{
          $('#list-usershistory').append('<li>Aún no existen interacciones entre ambos grupos</li>');
        }
      }else{
         if (id_nodo_conectado!=-1){
          var nodo_anterior=document.getElementById("grupo"+id_nodo_conectado);
          nodo_anterior.setAttribute("class","grupo");
          /*link.style("stroke",function(d){
            console.log(d[0].index+" "+d[2].index);
            if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
              return color_grupos(mapeo_idgrupo[dataGrupos[nodo_centro].id_experiencia][dataGrupos[nodo_centro].id_grupo]);
            }
          });*/
          /*id_nodo_conectado=-1;
        }
        if (node.id_grupo==id_grupo){
              $('#text-username').html('<span class="principal">Mi Grupo</span>');
        }else{
          $('#text-username').html('<span class="principal">'+dataGrupos[node.index].nombre+'</span>');
        }
        desplegarHistorialParticipacionGrupal(nodo_centro);
        $("#text-classname").text("");
      }
    }*/
    force_grupal.resume();
  }

  console.log("zoom activo: "+zoom_activo);
  
  //Registra el click a un nodo, sea este grupal o individual
  $.ajax({ 
    type: "POST", 
    url: "log_accion_sesion.php", 
    data:{accion:"vis_detalle_info" , id_sesion: id_sesion , id_experiencia: id_experiencia, 
          vista: vista, perspectiva: perspectiva, id_elemento: elemento_accesado, accion_detalle:0, tamano_elemento: tamano_elemento, zoom_activo:zoom_activo}
  });
  
}

function clickLink(opacity){
  return function(d){
    clickRelacion=true;
    zoom_activo=1;
    if (vista_individual){
      var vista=0;
    }else{
      var vista=1;
    }
    var perspectiva = 1;
    if (vista_individual){
      //Quita la clase que destacaba nodos seleccionados en la vista selfcentered
      svg.selectAll(".seleccionado_selfcentered").attr("class","node");
      svg.selectAll(".conectado_selfcentered").attr("class","node");

      if (d[0].index===nodo_centro){
        desplegarDatosInteraccionIndividual(d[0],d[2]);
        desplegarHistorialInteracciones(d[0],d[2]);
        id_nodo_conectado=d[2].id_usuario;
      }else{
        desplegarDatosInteraccionIndividual(d[2],d[0]);
        desplegarHistorialInteracciones(d[2],d[0]);
        id_nodo_conectado=d[0].id_usuario;
      }
      mostrarDivHistorialAjustado();

      var elemento_accesado=id_nodo_seleccionado+"-"+id_nodo_conectado;

      //Se obtiene el grosor en pixeles de la arista clickeada
      var svg_link=svg.select("#link"+d[0].index+"-"+d[2].index);
      if (svg_link){
        var string_tamano_elemento=svg_link.style("stroke-width");
      }else{
        var string_tamano_elemento=svg.select("#link"+d[2].index+"-"+d[0].index).style("stroke-width");
      }
      var pos_px=string_tamano_elemento.indexOf("px");
      if (pos_px!=-1){
        string_tamano_elemento=string_tamano_elemento.substr(0,string_tamano_elemento.indexOf("px"));
      }
      tamano_elemento=(parseFloat(string_tamano_elemento)).toFixed(2);

      var nodo_conectado=document.getElementById("node"+id_nodo_conectado);
      nodo_conectado.setAttribute("class","node conectado_selfcentered");

      var nodo_central=document.getElementById("node"+id_nodo_seleccionado);
      nodo_central.setAttribute("class","node seleccionado_selfcentered");

      svg.selectAll(".node")
        .style("opacity",function(e){
          return (e==d[0] || e==d[2]) ? 1 : opacity;
        });
      svg.selectAll(".link")
        .style("opacity",function(e){
          return (e==d) ? scalingTiempoLinks(ponderacionTiempoLinkIndividual(d[0].index,d[2].index)) : opacity;
        });
        /*.style("stroke",function(e){
          return (e==d) ? "#333" : general_view_edge_color;
        });*/
    }else{
      //Quita la clase que destacaba nodos seleccionados en la vista selfcentered
      svg.selectAll(".seleccionado_selfcentered").attr("class","grupo");
      svg.selectAll(".conectado_selfcentered").attr("class","grupo");


      if (d[0].index==nodo_centro){
        desplegarDatosInteraccionGrupal(d[0],d[2]);
        desplegarHistorialInteraccionesGrupales(d[0],d[2]);
        id_nodo_conectado=d[2].index;
      }else{
        desplegarDatosInteraccionGrupal(d[2],d[0]);
        desplegarHistorialInteraccionesGrupales(d[2],d[0]);
        id_nodo_conectado=d[0].index;
      }
      mostrarDivHistorialAjustado();

      var elemento_accesado=dataGrupos[nodo_centro].id_grupo_kelluwen+"-"+dataGrupos[id_nodo_conectado].id_grupo_kelluwen;

      //Se obtiene el grosor en pixeles de la arista clickeada
      var svg_link=svg.select("#link_grupal"+d[0].index+"-"+d[2].index);
      if (svg_link){
        //console.log("existe link");
        var string_tamano_elemento=svg_link.style("stroke-width");
      }else{
        //console.log("existe link invertido");
        var string_tamano_elemento=svg.select("#link_grupal"+d[2].index+"-"+d[0].index).style("stroke-width");
      }
      //console.log("tamano_elemento "+string_tamano_elemento);
      var pos_px=string_tamano_elemento.indexOf("px");
      if (pos_px!=-1){
        string_tamano_elemento=string_tamano_elemento.substr(0,string_tamano_elemento.indexOf("px"));
      }
      tamano_elemento=(parseFloat(string_tamano_elemento)).toFixed(2);

      var nodo_conectado=document.getElementById("grupo"+id_nodo_conectado);
      nodo_conectado.setAttribute("class","grupo conectado_selfcentered");
      var nodo_central=document.getElementById("grupo"+id_nodo_seleccionado);
      nodo_central.setAttribute("class","grupo seleccionado_selfcentered");

      svg.selectAll(".contenedor_grupo")
        .style("opacity",function(e){
          return (e==d[0] || e==d[2]) ? 1 : opacity;
        });
      svg.selectAll(".link_grupal")
        /*.transition().duration(100)*/
        .style("opacity",function(e){
          return (e==d) ? scalingTiempoLinks(ponderacionTiempoLinkGrupal(e[0].index,e[2].index)) : opacity;
        });
        /*.style("stroke",function(e){
          return (e==d) ? "#333" : general_view_edge_color;
        });*/
    }

    //Registra el click del link, sea este grupal o individual
    $.ajax({ 
      type: "POST", 
      url: "log_accion_sesion.php", 
      data:{accion:"vis_detalle_info" , id_sesion: id_sesion , id_experiencia: id_experiencia, 
            vista: vista, perspectiva: perspectiva, id_elemento: elemento_accesado, accion_detalle:0, 
            zoom_activo: zoom_activo, tamano_elemento: tamano_elemento}
    });
  }
}
function fadeLinkIndividual(opacity,hover){
  return function(d){
    if (hover){
      $(this).css('cursor','pointer');
        hover_tooltip.transition()
          .duration(100)
          .style("opacity", 0.9);

        //Agrega los nombres al tooltip de ayuda, poniendo en primer lugar el nombre del nodo central y luego el del nodo conectado
        if (d[0].index==nodo_centro){
          var nombre_nodo_centro=d[0].nombre;
          var nombre_nodo_conectado=d[2].nombre;
          svg.select("#node"+d[2].id_usuario).moveToFront();
        }else{
          var nombre_nodo_centro=d[2].nombre;
          var nombre_nodo_conectado=d[0].nombre;
          svg.select("#node"+d[0].id_usuario).moveToFront();
        }

        hover_tooltip.html("<?php echo $lang_hv_ver_historial; ?> <b>"+nombre_nodo_centro+"</b> <?php echo $lang_hv_y; ?> <b>"+nombre_nodo_conectado+"</b>")
          /*.style("left", (d3.event.pageX-width/2 -($(".hover_tooltip").width()/2)) + "px")     
          .style("top",  (d3.event.pageY-height/2 -($(".hover_tooltip").height()/2)) + "px");*/
          .style("left", mouse_coords[0] + "px")     
          .style("top",  mouse_coords[1] + "px");
        svg.selectAll(".node")
          /*.transition()
          .duration(100)*/
          .style("opacity",function(e){
            return (e==d[0] || e==d[2] || (clickRelacion && e.id_usuario==id_nodo_conectado )) ? 1 : opacity;
          });
        svg.selectAll(".link")
          .style("opacity",function(e){
            //Seccion que permite que se destaque el link clickeado mientras se hace hover en otros links
            var link_clickeado=false;
            var usuario1=e[0].id_usuario;
            var usuario2=e[2].id_usuario;
            if ((usuario1==id_nodo_seleccionado && usuario2==id_nodo_conectado) || (usuario1==id_nodo_conectado && usuario2==id_nodo_seleccionado)){
              link_clickeado=true;
            }
            return (e==d || (clickRelacion && link_clickeado)) ? scalingTiempoLinks(ponderacionTiempoLinkIndividual(e[0].index,e[2].index)) : opacity;
          });

    }else{
      $(this).css('cursor','auto');
      svg.selectAll(".node")
        /*.transition()
        .duration(100)*/
        .style("opacity",function(e){
            return (!clickRelacion || (clickRelacion && (e.id_usuario==id_nodo_seleccionado || e.id_usuario==id_nodo_conectado))) ? opacity : 0.15;
        });
      svg.selectAll(".link")
        /*.transition()
        .duration(100)*/
        .style("opacity",function(e){
            //Seccion que permite que se destaque el link clickeado mientras se hace hover en otros links
            var link_clickeado=false;
            var usuario1=e[0].id_usuario;
            var usuario2=e[2].id_usuario;
            if ((usuario1==id_nodo_seleccionado && usuario2==id_nodo_conectado) || (usuario1==id_nodo_conectado && usuario2==id_nodo_seleccionado)){
              link_clickeado=true;
            }
            return (!clickRelacion || (clickRelacion && link_clickeado)) ? scalingTiempoLinks(ponderacionTiempoLinkIndividual(e[0].index,e[2].index)) : 0.15;
          });
      hover_tooltip.transition()
          .duration(100)
          .style("opacity", 0);
    }
  };
}

function fadeLinkGrupal(opacity,hover){
  return function (d){
    if (hover){
      $(this).css('cursor','pointer');
      hover_tooltip.transition()
          .duration(100)
          .style("opacity", 0.9);

      //Agrega los nombres al tooltip de ayuda, poniendo en primer lugar el nombre del nodo central y luego el del nodo conectado
      if (d[0].index==nodo_centro){
        var nombre_nodo_centro   =dataGrupos[d[0].index].nombre;
        var nombre_nodo_conectado=dataGrupos[d[2].index].nombre;
        svg.select("#cont_grupo"+d[2].index).moveToFront();
      }else{
        var nombre_nodo_centro   =dataGrupos[d[2].index].nombre;
        var nombre_nodo_conectado=dataGrupos[d[0].index].nombre;
        svg.select("#cont_grupo"+d[0].index).moveToFront();
      }

      hover_tooltip.html("<?php echo $lang_hv_ver_historial_entre_integrantes; ?> <b>"+nombre_nodo_centro+"</b> <?php echo $lang_hv_y; ?> <b>"+nombre_nodo_conectado)
          /*.style("left", (d3.event.pageX-width/2 -($(".hover_tooltip").width()/2)) + "px")     
          .style("top",  (d3.event.pageY-height/2 -($(".hover_tooltip").height()/2)) + "px");*/
          .style("left", mouse_coords[0] + "px")     
          .style("top",  mouse_coords[1] + "px");
      svg.selectAll(".contenedor_grupo")
        /*.transition().duration(100)*/
        .style("opacity",function(e){
            return (e==d[0] || e==d[2] || (clickRelacion && e.index==id_nodo_conectado )) ? 1 : opacity;
        });
      svg.selectAll(".link_grupal")
        .style("opacity",function(e){
          //Seccion que permite que se destaque el link clickeado mientras se hace hover en otros links
          var link_clickeado=false;
          var grupo1=e[0].index;
          var grupo2=e[2].index;
          if ((grupo1==id_nodo_seleccionado && grupo2==id_nodo_conectado) || (grupo1==id_nodo_conectado && grupo2==id_nodo_seleccionado)){
            link_clickeado=true;
          }
          return (e==d || (clickRelacion && link_clickeado)) ? scalingTiempoLinks(ponderacionTiempoLinkGrupal(e[0].index,e[2].index)) : opacity;
        });
    }else{
      $(this).css('cursor','auto');
      svg.selectAll(".contenedor_grupo")
        .style("opacity",function(e){
          return (!clickRelacion || (clickRelacion && (e.index==id_nodo_seleccionado || e.index==id_nodo_conectado))) ? opacity : 0.15;
      });
      svg.selectAll(".link_grupal")
        .style("opacity",function(e){
          //Seccion que permite que se destaque el link clickeado mientras se hace hover en otros links
            var link_clickeado=false;
            var grupo1=e[0].index;
            var grupo2=e[2].index;
            if ((grupo1==id_nodo_seleccionado && grupo2==id_nodo_conectado) || (grupo1==id_nodo_conectado && grupo2==id_nodo_seleccionado)){
              link_clickeado=true;
            }
            return (!clickRelacion || (clickRelacion && link_clickeado)) ? scalingTiempoLinks(ponderacionTiempoLinkGrupal(e[0].index,e[2].index)) : 0.15;
      });
      hover_tooltip.transition()
        .duration(100)
        .style("opacity", 0);

    }
  }
}

function fadeIndividual(opacity,hover) {
    return function(d) {
      var node=svg.selectAll(".node");
      var link=svg.selectAll(".link");
      var nodes=dataUsuarios.slice();
      if (hover){
        $(this).css('cursor','pointer');
        if (self_centered){//En caso de que se haya posado el cursor sobre un usuario en particular
          if (nodo_centro != d.index){
            //Trae al frente dicho nodo
            svg.select("#node"+d.id_usuario).style("opacity",1);
            svg.select("#node"+d.id_usuario).moveToFront();
          }
        }else{
           $("#div-historial-actividad").css("display","inline");
            if (d.id_usuario==id_usuario){
              $('#text-username1').html('<span class="principal"><?php echo $lang_hv_yo; ?></span>');
            }else{
              $('#text-username1').html('<span class="principal">'+d.nombre+'</span>');
            }
          $('#text-classname1').text(d.nombre_clase);
          desplegarDatosParticipacionIndividual(d.index);

          //Aplicar efecto de entrada a datos grales del usuario consultado
          /*if ($("#div-userinfo").is( ":hidden" )) {
            $("#div-userinfo").show("slide");
          }*/

          node.style("opacity", function(o) {
              return isConnected(d, o) ? 1 : opacity;
            });
          node.selectAll(".nodetext")
              .style("display",function(o) {
                var cssdisplay="none";
                if (hover) cssdisplay = "inline";
                //return (isConnected(d, o) && o.id_usuario!=id_usuario) ? cssdisplay : "none";
                return (isConnected(d, o)) ? cssdisplay : "none";
            });

          link.style("stroke-opacity", function(o) {
              return o[0] === d || o[2] === d ? 1 : opacity;
            });

        }
      }else{

        $(this).css('cursor','auto');
        if (self_centered){
          //$('#text-username').html(dataUsuarios[nodo_centro].nombre);
          //$("#text-classname").text("");
          if (clickRelacion && d.id_usuario!=id_nodo_conectado && d.index!=nodo_centro){
            svg.select("#node"+d.id_usuario).style("opacity",.15);
          }
        }else{
          $("#div-historial-actividad").css("display","none");
          $("#text-username1").html("");
          $("#text-classname1").text("");
          $("#div-usershistory").css("display","none");

          node.selectAll(".nodetext")
              .style("display","inline");
          ocultarDatosParticipacion();
          //$("#div-userinfo").hide("slide");
          node.style("opacity", 1 );
        }
        link.style("stroke-opacity", 1);  
      }
  };
  };

  function fadeGrupal(opacity,hover) {
    return function(d) {
      if (!vista_individual){
      var node=svg.selectAll(".grupo");
      var int_node=svg.selectAll(".integrante");
      var link=svg.selectAll(".link_grupal");
      if (hover){
        $(this).css('cursor','pointer');
        if (self_centered){//En caso que se haya posado el cursor sobre un grupo en particular
          if (nodo_centro != d.index){
            //Trae hacia al frente al nodo grupal seleccionado, para que no exista oclusión con el resto
            svg.select("#cont_grupo"+d.index).style("opacity",1);
            svg.select("#cont_grupo"+d.index).moveToFront();
          }
        }else{
          $("#div-historial-actividad").css("display","inline");
          desplegarMiembrosGrupo(d.index);
          desplegarDatosParticipacionGrupal(d.index);
          

          node.style("opacity", function(o) {
              return isConnectedToTeam(d.index, o.fila_json) ? 1 : opacity;
            });

          int_node.style("opacity",function(o) {
              return isConnectedToTeam(d.index,o.parent.fila_json) ? 1 : opacity;
            });

          node.selectAll(".nodetext")
              .style("display",function(o) {
                var cssdisplay="none";
                if (hover) cssdisplay = "inline";
                return (isConnectedToTeam(d.index, o.fila_json) && o.fila_json!=id_grupo) ? cssdisplay : "none";
            });

          link.style("stroke-opacity", function(o) {
              return o[0] === d || o[2] === d ? 1 : opacity;
            });

        }
      }else{
        $(this).css('cursor','auto');
        if (self_centered){
          /*$('#text-username').html(dataGrupos[nodo_centro].nombre);
          $("#text-classname1").text("");*/
          if (clickRelacion && d.index!=id_nodo_conectado && d.index!=nodo_centro){
            svg.select("#cont_grupo"+d.index).style("opacity",0.15);
          }
        }else{
          $("#div-historial-actividad").css("display","none");
          $('#text-username1').html("");
          $("#text-classname1").text("");
          node.selectAll(".nodetext")
              .style("display","inline");
          ocultarDatosParticipacion();
        }
        node.style("opacity", 1 );
        int_node.style("opacity",1);
        link.style("stroke-opacity", 1);
      }
    }
  };
};

function fadeIntegrante(hover) {
    return function(d) {
      if (!vista_individual){
        var selector=".usuario"+d.id_usuario;
        if (hover){
          setTimeout(
          function() 
          {
            $(selector).css("height","12px");
            $(selector).css("border-radius","5px");
            $(selector).css("background-color","rgba(237,165,65,0.7)");
          }, 50);
          
        }else{
          setTimeout(
          function() 
          {
            $(selector).css("background-color","transparent");
          },50);
        }
      }
    }
  }

function activarVistaIndividual(){

  transicion=true;

  force_grupal.resume();
  force_individual.resume();

  for (var i=0; i<array_svg_grupos.length;i++){
    array_svg_grupos[i].fixed=true;
  }

  var delay_layout_ind=500;
  var duracion_transicion=1000;

  //Hace visible el tooltip que indica donde está el usuario, para el caso de que el usuario no haya estado asignado en ningún grupo
  if(tooltip) tooltip.style("visibility","visible");

  svg.selectAll(".node").style("display","initial");
  svg.selectAll(".link").style("display","initial");

  svg.selectAll(".integrante image")
    .transition()
    .duration(delay_layout_ind)
    .style("opacity",0)
    .each("end",function(d){d3.select(this).style("display","none");});

  svg.selectAll(".grupo circle")
    .transition()
    .duration(duracion_transicion)
    .style("opacity",0);
    //.each("end",function(d){d3.select(this).style("display","none");});

  setTimeout(function(d){
    svg.selectAll(".node")
      .transition()
      .duration(duracion_transicion)
      .style("opacity",1);

    svg.selectAll(".link")
      .transition()
      .duration(duracion_transicion)
      .style("opacity",function(d){
        return scalingTiempoLinks(ponderacionTiempoLinkIndividual(d[0].index,d[2].index));
      });

    svg.selectAll(".link_grupal")
      .transition()
      .duration(duracion_transicion)
      .style("opacity",0)
      .each("end",function(d){d3.select(this).style("display","none");});

    svg.selectAll(".grouptext")
      .style("display","none");
  },delay_layout_ind);

  setTimeout(function(d){
    svg.selectAll(".nodetext")
      .transition()
      .duration(delay_layout_ind)
      .style("opacity",1);
    transicion=false;
    for (var i=0; i<array_svg_grupos.length;i++){
      array_svg_grupos[i].fixed=false;
    }
    svg.selectAll(".contenedor_grupo").style("display","none");
  },delay_layout_ind/2 + duracion_transicion);

  

}

function activarVistaGrupal(){
  
  force_individual.resume();
  force_grupal.resume();
  transicion=true;

  var delay_desp_nodos=750;
  var duracion_transicion=1000;
  var delay_layout_grupal=300;

  svg.selectAll(".contenedor_grupo").style("display","initial");
  svg.selectAll(".link_grupal").style("display","initial");

  svg.selectAll(".nodetext")
    .transition()
    .duration(delay_layout_grupal+delay_desp_nodos)
    .style("opacity",0);

  svg.selectAll(".link")
    .select(function(d,i){
      var y_linkrecta=(d[0].y+d[2].y)/2;
      var y_linkcurva=d[1].y;
      if (y_linkcurva<y_linkrecta){
        coord_links_transgrupal[i]=(Math.random()+1)*20*-1;
      }else{
        coord_links_transgrupal[i]=(Math.random()+1)*20;
      }
    });

  setTimeout(function(){
  for (var i=0; i<array_svg_grupos.length;i++){
    array_svg_grupos[i].fixed=true;
  }

  svg.selectAll(".grupo").style("display","inline");
  svg.selectAll(".grupo circle").style("display","inline");
  svg.selectAll(".integrante image").style("display","inline");
  svg.selectAll(".link_grupal").style("display","inline");

  var nodes=dataUsuarios.slice();

  svg.selectAll(".integrante")
      .select(function(d){
        var fila_json=d.fila_json;
        var fila_json_grupo=d.parent.fila_json;
        var grupo=array_svg_grupos[fila_json_grupo];
        var r_grupo=grupo.data()[0].r;
        var x_grupo=grupo.x+d.x;
        var y_grupo=grupo.y+d.y;
        nodes[fila_json].x=x_grupo;
        nodes[fila_json].y=y_grupo;
        nodes[fila_json].px=nodes[fila_json].x;
        nodes[fila_json].py=nodes[fila_json].y;
      });


  svg.selectAll(".node").transition().duration(delay_desp_nodos).ease("sin")
      .attr("cx", function(d){ return d.x})
      .attr("cy", function(d){ return d.y})     
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

  svg.selectAll(".grupo circle")
    .transition()
    .delay(delay_desp_nodos)
    .duration(duracion_transicion)
    .style("opacity",1);

  svg.selectAll(".integrante image")
    .transition()
    .delay(delay_desp_nodos+100)
    .duration(duracion_transicion)
    .style("opacity",1);

  svg.selectAll(".link_grupal")
    .transition()
    .delay(delay_desp_nodos)
    .duration(duracion_transicion)
    .style("opacity",function(d){
      console.log(d[0].index+"-"+d[2].index);
      return scalingTiempoLinks(ponderacionTiempoLinkGrupal(d[0].index,d[2].index));
    });

  svg.selectAll(".grouptext")
    .transition()
    .delay(delay_desp_nodos)
    .duration(duracion_transicion)
    .style("display","inline");

  svg.selectAll(".node")
    .transition()
    .delay(delay_desp_nodos)
    .duration(1000)
    .style("opacity",0)
    .each("end",function(d){d3.select(this).style("display","none");});

  svg.selectAll(".link")
    .transition()
    .delay(delay_desp_nodos)
    .duration(duracion_transicion)
    .style("opacity",0)
    .each("end",function(d){d3.select(this).style("display","none");});

  },delay_layout_grupal);

  setTimeout(function(){
    for (var i=0; i<array_svg_grupos.length;i++){
      array_svg_grupos[i].fixed=false;
    }
    transicion=false;
    force_grupal.resume();
  },duracion_transicion+delay_desp_nodos+delay_layout_grupal);

  
  

  
}

function desplegarHistorialParticipacionIndividual(nodo_centro){
  $("#text-username2").empty();
  $("#text-classname2").empty();
  //desplegarDatosParticipacionIndividual(nodo_centro);

  var historial = obtenerHistorialParticipacion(nodo_centro);
  $('#list-usershistory').empty();
  if (historial && historial.length>0){
    var l=historial.length;
    if (l>max_interacciones_desplegadas) l=max_interacciones_desplegadas
    for (var i=0;i<l;i++){
      desplegarRegistroInteraccion(historial[i]);
    }
  }else{
    $('#list-usershistory').append('<li><?php echo $lang_hv_usuario_no_registra_act; ?></li>');
  }
}

function desplegarHistorialParticipacionGrupal(nodo_centro){
  $("#text-username2").empty();
  $("#text-classname2").empty();
  desplegarMiembrosGrupo(nodo_centro);
  //desplegarDatosParticipacionGrupal(nodo_centro);
  var historial = obtenerHistorialParticipacionGrupal(nodo_centro);
  $('#list-usershistory').empty();
  if (historial && historial.length>0){
    var l=historial.length;
    if (l>max_interacciones_desplegadas) l=max_interacciones_desplegadas
    for (var i=0;i<l;i++){
      desplegarRegistroInteraccion(historial[i]);
    }
  }else{
    $('#list-usershistory').append('<li><?php echo $lang_hv_grupo_no_registra_act; ?></li>');
  }
}

function desplegarMiembrosGrupo(nodo_grupo){
  var string_integrantes="";
  var integrantes=dataGrupos[nodo_grupo].children;
  var fila_json_integrante;
  for (var i=0;i<integrantes.length;i++){
    var fila_json_integrante=integrantes[i].fila_json;
    console.log(dataUsuarios[fila_json_integrante].id_usuario);
    if (i==0){
      string_integrantes=string_integrantes+"<span class='usuario"+dataUsuarios[fila_json_integrante].id_usuario+"'>"+dataUsuarios[fila_json_integrante].nombre+"</span>";
    }else{
      string_integrantes=string_integrantes+", <span class='usuario"+dataUsuarios[fila_json_integrante].id_usuario+"'>"+dataUsuarios[fila_json_integrante].nombre+"</span>";
    }
  }
  $('#text-username1').html("<span class='principal'>"+dataGrupos[nodo_grupo].nombre+": </br></span> <span class='secundario'>"+string_integrantes+"</span>");
  $('#text-classname1').text(dataGrupos[nodo_grupo].nombre_clase);
}

function desplegarHistorialInteracciones(nodo_centro, nodo){
  //$('#text-username').html("<span class='principal'>"+nodo_centro.nombre+" y "+nodo.nombre+"</span>");
  var nombre1=dataUsuarios[nodo_centro.index].nombre;
  var nombre2=dataUsuarios[nodo.index].nombre;
  var nombreclase1=dataUsuarios[nodo_centro.index].nombre_clase;
  var nombreclase2=dataUsuarios[nodo.index].nombre_clase;
  $('#text-username1').html("<span class='principal'>"+nombre1.substr(0,nombre1.indexOf(' '))+"</span>");
  $('#text-username2').html("<span class='principal'>"+nombre2.substr(0,nombre2.indexOf(' '))+"</span>");
  $('#text-classname1').text(nombreclase1);
  $('#text-classname2').text(nombreclase2);
  var historial = obtenerHistorialInteracciones(nodo_centro.index,nodo.index);
  $('#list-usershistory').empty();
  if (historial && historial.length>0){
    var l=historial.length;
    if (l>max_interacciones_desplegadas) l=max_interacciones_desplegadas
      for (var i=0;i<l;i++){
        desplegarRegistroInteraccion(historial[i]);
      }
    }else{
        $('#list-usershistory').append('<li><?php echo $lang_hv_no_existen_int_usuarios; ?></li>');
  }
}

function desplegarHistorialInteraccionesGrupales(nodo_centro,nodo){
  $('#text-username1').html('<span class=principal>'+dataGrupos[nodo_centro.index].nombre+'</span>');
  $('#text-username2').html('<span class=principal>'+dataGrupos[nodo.index].nombre+'</span>')
  $('#text-classname1').html('<span class=secundario>'+dataGrupos[nodo_centro.index].nombre_clase+'</span>');
  $('#text-classname2').html('<span class=secundario>'+dataGrupos[nodo.index].nombre_clase+'</span>');
  var historial = obtenerHistorialInteraccionesGrupales(nodo_centro.index,nodo.index);
  $('#list-usershistory').empty();
  if (historial && historial.length>0){
    var l=historial.length;
    if (l>max_interacciones_desplegadas) l=max_interacciones_desplegadas
    for (var i=0;i<l;i++){
      desplegarRegistroInteraccion(historial[i]);
    }
  }else{
    $('#list-usershistory').append('<li><?php echo $lang_hv_no_existen_int_grupos; ?></li>');
  }
}

function actualizarRedGrupal(){
  //Tiempo duración de la actualización de los nodos grupales
   var duracion_transicion_act_grupal=2000;

   var svg_grupo;

   for(var i=0;i<dataGrupos.length;i++){
      if (dataGrupos[i]["children"].length>0){
          var tamano_grupo=scalingGrupal(dataGrupos[i]["participacion_total"]);
          pack_layout_array[i] = d3.layout.pack()
            //.size([2*tamano_grupo,2*tamano_grupo])
            .value(function(d) { return d.size; })
            .radius(function(d){ return ratio_foto*scalingIndividual(d);});

          svg_grupo=svg.select("#cont_grupo"+i);

          var nuevo_grupo=svg_grupo.selectAll(".componente_nodo_grupal").data(pack_layout_array[i].nodes(dataGrupos[i]),function(d){return d.id_usuario});
          nuevo_grupo.select("circle");
          nuevo_grupo.select("image");

      }               
  }


  svg.selectAll(".grupo")
    .selectAll("circle")
    .transition()
    .duration(duracion_transicion_act_grupal)
    .attr("r",function(d){return d.r;});

  svg.selectAll(".integrante")
    .transition()
    .duration(duracion_transicion_act_grupal)
    .attr("transform",function(d){return "translate("+d.x+","+d.y+")";});

  svg.selectAll(".integrante")
    .selectAll("circle")
        .transition()
        .duration(duracion_transicion_act_grupal)
        .attr("r",function(d){
          dataUsuarios[d.fila_json].r_grupo=d.r;
          return d.r;});

  svg.selectAll(".clipPath_int")
    .selectAll("circle")
    .transition()
    .duration(duracion_transicion_act_grupal)
    .attr("r",function(d){
          return d.r_grupo;});

  svg.selectAll(".integrante")
    .selectAll("image")
      .transition()
      .duration(duracion_transicion_act_grupal)
      .attr("x",function(d){return -1*d.r;})
      .attr("y",function(d){return -1*d.r;})
      .attr("width",function(d){return 2*d.r;})
      .attr("height",function(d){return 2*d.r;});

}

function actualizarNodoIndividual(nodo,nodoActualizado){
  nodo.mensajes=nodoActualizado.mensajes;
  nodo.mensajes_respuesta=nodoActualizado.mensajes_respuesta;
  nodo.megusta=nodoActualizado.megusta;
  nodo.mensajes_respuesta_recibidos=nodoActualizado.mensajes_respuesta_recibidos;
  nodo.megusta_recibidos=nodoActualizado.megusta_recibidos;
  nodo.participacion=nodoActualizado.participacion;
  nodo.historial_participacion=nodoActualizado.historial_participacion;
  historialParticipacion[nodo.fila_json]=nodo.historial_participacion;
}

function actualizarNodoGrupal(nodo,nodoActualizado){
  nodo.mensajes=nodoActualizado.mensajes;
  nodo.mensajes_respuesta=nodoActualizado.mensajes_respuesta;
  nodo.megusta=nodoActualizado.megusta;
  nodo.mensajes_respuesta_recibidos=nodoActualizado.mensajes_respuesta_recibidos;
  nodo.participacion_total=nodoActualizado.participacion_total;
  nodo.historial_participacion=nodoActualizado.historial_participacion;
  nodo.children=nodoActualizado.children;
  historialParticipacionGrupal[nodo.fila_json]=nodo.historial_participacion;
}

function actualizarLinkIndividual(link,linkActualizado){
  var source=link.source;
  var target=link.target;
  //if (link.total_interacciones!=linkActualizado.total_interacciones){
  //console.log("Source: "+source+" target: "+target);
  //console.log("Old interacciones: "+link.total_interacciones+" new interacciones: "+linkActualizado.total_interacciones);
  link.total_interacciones=linkActualizado.total_interacciones;
  linkedByIndex[source+","+target]=linkActualizado.total_interacciones;
  antiguedadLinkIndividual[source+","+target]=linkActualizado.ponderacion;
  link.historial_interacciones=linkActualizado.historial_interacciones;
  link.respuestas_usuario1=linkActualizado.respuestas_usuario1;
  link.respuestas_usuario2=linkActualizado.respuestas_usuario2;
  link.megusta_usuario1=linkActualizado.megusta_usuario1;
  link.megusta_usuario2=linkActualizado.megusta_usuario2;
  //}
}

function actualizarLinkGrupal(link,linkActualizado){
  var source=link.source;
  var target=link.target;
  //if (link.total_interacciones!=linkActualizado.total_interacciones){
  //console.log("Source grupal: "+source+" target grupal: "+target);
  //console.log("Old interacciones Grupal: "+link.total_interacciones+" new interacciones: "+linkActualizado.total_interacciones);
  link.total_interacciones=linkActualizado.total_interacciones;
  linkedByTeam[source+","+target]=linkActualizado.total_interacciones;
  antiguedadLinkGrupal[source+","+target]=linkActualizado.ponderacion;
  link.historial_interacciones= linkActualizado.historial_interacciones;
  link.respuestas_grupo1=linkActualizado.respuestas_grupo1;
  link.respuestas_grupo2=linkActualizado.respuestas_grupo2;
  link.megusta_grupo1=linkActualizado.megusta_grupo1;
  link.megusta_grupo2=linkActualizado.megusta_grupo2;
  //}
}

function añadirLinkIndividual(link){//Función de código agregada por Jordan Barría el 28-12-14
    var s = nodesIndividuales[link.source],
        t = nodesIndividuales[link.target],
        i = {};
    nodesIndividuales.push(i);
    linksIndividuales.push({source: s, target: i}, {source: i, target: t});
    bilinksIndividuales.push([s, i, t]);
    dataLinksUsuarios.push(link);
    linkedByIndex[link.source + "," + link.target] = link.total_interacciones;
    antiguedadLinkIndividual[link.source + "," + link.target] = link.ponderacion;
    var data_historial_interacciones= link.historial_interacciones;
    var nro_interacciones = data_historial_interacciones.length;
    var historial_arreglo = [];
    for (var i=0;i<nro_interacciones;i++){
      historial_arreglo[i]=data_historial_interacciones[i];
    }
    historialInteracciones[link.source + "," + link.target] = historial_arreglo;

    //Código necesario para que el link no se dibuje sobrepuesto a los nodos de los usuarios
    /*id_usuario_source=s.id_usuario;
    id_usuario_target=t.id_usuario;
    console.log("id s: "+id_usuario_source+" id t: "+id_usuario_target);
    var nodo_source=svg.select("#node"+id_usuario_source);
    var nodo_target=svg.select("#node"+id_usuario_target);
    nodo_source.moveToFront();
    nodo_target.moveToFront();*/

    //actualizarVisualizacion();
}

function añadirLinkGrupal(link){//Función de código agregada por Jordan Barría el 28-12-14
    var s = nodesGrupales[link.source],
        t = nodesGrupales[link.target],
        i = {};
    nodesGrupales.push(i);
    linksGrupales.push({source: s, target: i}, {source: i, target: t});
    bilinksGrupales.push([s, i, t]);
    dataLinksGrupales.push(link);
    linkedByTeam[link.source + "," + link.target] = link.total_interacciones;
    antiguedadLinkGrupal[link.source + "," + link.target] = link.ponderacion;
    var data_historial_interacciones= link.historial_interacciones;
    var nro_interacciones = data_historial_interacciones.length;
    var historial_arreglo = [];
    for (var i=0;i<nro_interacciones;i++){
      historial_arreglo[i]=data_historial_interacciones[i];
    }
    historialInteraccionesGrupales[link.source + "," + link.target] = historial_arreglo;

    //Código necesario para que el link no se dibuje sobrepuesto a los nodos de los usuarios
    /*id_usuario_source=s.id_usuario;
    id_usuario_target=t.id_usuario;
    console.log("id s: "+id_usuario_source+" id t: "+id_usuario_target);
    var nodo_source=svg.select("#node"+id_usuario_source);
    var nodo_target=svg.select("#node"+id_usuario_target);
    nodo_source.moveToFront();
    nodo_target.moveToFront();*/

    //actualizarVisualizacion();
}


function removerLinkIndividual(link){
  var index0,index1,index2;
  var source=link.source;
  var target=link.target;
  for (var i = 0; i < bilinksIndividuales.length; i++) {
      //console.log("Primer nodo bilinks: "+bilinks[i][0].index);
      //console.log("Segundo nodo bilinks: "+bilinks[i][2].index);
      if (bilinksIndividuales[i][0].index == source && bilinksIndividuales[i][2].index == target){//} || (bilinksIndividuales[i][0].index == target && bilinksIndividuales[i][2].index == source)) {
          index0=bilinksIndividuales[i][0].index;
          index1=bilinksIndividuales[i][1].index;
          index2=bilinksIndividuales[i][2].index;
          console.log("Borré relación bilinks");
          console.log("Index intermedio: "+index1);
          bilinksIndividuales.splice(i, 1);
          break;
      }
  }
  console.log("Largo linksIndividuales: "+linksIndividuales.length);
  var nro_links=linksIndividuales.length;
  var i=0;
  //for (var i = 0; i < nro_links; i++) {
      //console.log("Source links: "+linksIndividuales[i]["source"].index);
      //console.log("Target links: "+linksIndividuales[i]["target"].index);
      //console.log("Tipo source: "+ typeof(linksIndividuales[i]["source"].index));
      //var sonIgualesSource=(linksIndividuales[i]["source"].index == index1);
      //var sonIgualesTarget=(linksIndividuales[i]["target"].index == index1);
      //console.log("Son iguales source?: "+sonIgualesSource+" son iguales target?: "+sonIgualesTarget);
      //if (sonIgualesSource || sonIgualesTarget) {
  while (i<nro_links){
      console.log("i:"+i);
      console.log("Source links: "+linksIndividuales[i]["source"].index);
      console.log("Target links: "+linksIndividuales[i]["target"].index);
      if((linksIndividuales[i]["source"].index == index1) || (linksIndividuales[i]["target"].index == index1)){
          console.log("Borré relación links");
          linksIndividuales.splice(i, 1);
          nro_links=nro_links-1;//Se debe disminuir en uno el largo del arreglo, dado que se elmininó uno de sus elementos
      }else{
        i++;
      }
  }

  for (var i = 0; i < dataLinksUsuarios.length; i++) {
      if(dataLinksUsuarios[i].source==source && dataLinksUsuarios[i].target==target){
          console.log("Borré relación links en dataLinksUsuarios");
          dataLinksUsuarios.splice(i,1);
      }
  }

  delete linkedByIndex[source + "," + target];
  delete antiguedadLinkIndividual[source+","+target];
  delete historialInteracciones[source + "," + target];

  //actualizarVisualizacion();
}

function removerLinkGrupal(link){
  var index0,index1,index2;
  var source=link.source;
  var target=link.target;
  for (var i = 0; i < bilinksGrupales.length; i++) {
      //console.log("Primer nodo bilinks: "+bilinks[i][0].index);
      //console.log("Segundo nodo bilinks: "+bilinks[i][2].index);
      if (bilinksGrupales[i][0].index == source && bilinksGrupales[i][2].index == target){//} || (bilinksIndividuales[i][0].index == target && bilinksIndividuales[i][2].index == source)) {
          index0=bilinksGrupales[i][0].index;
          index1=bilinksGrupales[i][1].index;
          index2=bilinksGrupales[i][2].index;
          console.log("Borré relación bilinks");
          console.log("Index intermedio: "+index1);
          bilinksGrupales.splice(i, 1);
          break;
      }
  }
  console.log("Largo linksIndividuales: "+linksIndividuales.length);
  var nro_links=linksGrupales.length;
  var i=0;
  //for (var i = 0; i < nro_links; i++) {
      //console.log("Source links: "+linksIndividuales[i]["source"].index);
      //console.log("Target links: "+linksIndividuales[i]["target"].index);
      //console.log("Tipo source: "+ typeof(linksIndividuales[i]["source"].index));
      //var sonIgualesSource=(linksIndividuales[i]["source"].index == index1);
      //var sonIgualesTarget=(linksIndividuales[i]["target"].index == index1);
      //console.log("Son iguales source?: "+sonIgualesSource+" son iguales target?: "+sonIgualesTarget);
      //if (sonIgualesSource || sonIgualesTarget) {
  while (i<nro_links){
      console.log("i:"+i);
      console.log("Source links: "+linksGrupales[i]["source"].index);
      console.log("Target links: "+linksGrupales[i]["target"].index);
      if((linksGrupales[i]["source"].index == index1) || (linksGrupales[i]["target"].index == index1)){
          console.log("Borré relación links");
          linksGrupales.splice(i, 1);
          nro_links=nro_links-1;//Se debe disminuir en uno el largo del arreglo, dado que se elmininó uno de sus elementos
      }else{
        i++;
      }
  }

  for (var i = 0; i < dataLinksGrupales.length; i++) {
      if(dataLinksGrupales[i].source==source && dataLinksGrupales[i].target==target){
          console.log("Borré relación links en dataLinksGrupales");
          dataLinksGrupales.splice(i,1);
      }
  }

  delete linkedByTeam[source + "," + target];
  delete historialInteraccionesGrupales[source + "," + target];
  delete antiguedadLinkGrupal[source + "," + target];

  //actualizarVisualizacion();
}

function mantenerNodosTop(){
  $(".node").each(function( index ) {
      var gnode = this.parentNode;
      gnode.parentNode.appendChild(gnode);
  });
  $(".contenedor_grupo").each(function( index ) {
      var gnode = this.parentNode;
      gnode.parentNode.appendChild(gnode);
  });
}



function actualizarVisualizacion(){

  scalingIndividual = d3.scale.log()
      .domain([d3.min(dataUsuarios, function(d){return d.participacion}), d3.max(dataUsuarios, function(d){return d.participacion})])
      .range([min_radius_ind, max_radius_ind]);
  
  scalingLinkIndividual = d3.scale.log()
      .domain([d3.min(dataLinksUsuarios, function(d){return d.total_interacciones;}), d3.max(dataLinksUsuarios, function(d){return d.total_interacciones;})])
      .range([stroke_width_general_view, 1.8*min_radius_ind]);

  scalingGrupal = d3.scale.log()
    .domain([d3.min(dataGrupos.filter(function(d){ return d.children.length>0;}), function(d){return d.participacion_total}), d3.max(dataGrupos, function(d){return d.participacion_total})])
    .range([min_radius, max_radius]);

  var link = svg.selectAll(".link")
        .data(bilinksIndividuales,function(d){ 
          //console.log("link nuevo: "+d[0].id_usuario+"-"+d[2].id_usuario);
          return d[0].id_usuario+"-"+d[2].id_usuario;
        });

  link.enter().append("path")
      .attr("class","link")
      .attr("pointer-events", "none")
      .style("stroke",general_view_edge_color)
      .style("stroke-width",stroke_width_general_view)
      .style("fill","none")
      .style("opacity",function(d){
        if (vista_individual){
          return 1;
        }else{
          return 0;
        }
      })
      .style("visibility",function(d){
        if (vista_individual){
          return "visible";
        }else{
          return "hidden";
        }
      });

  link.exit().remove();

  var infolinks= svg.selectAll(".link_info")
    .data(bilinksIndividuales,function(d){ 
      return d[0].id_usuario+"-"+d[2].id_usuario;
  });

  infolinks.enter().append("path")
    .attr("class", "link_info")
    .attr("pointer-events", "visible")
    .style("stroke",general_view_edge_color)
    .style("stroke-width",20)
    .on("click", clickLink(.15))
    .on("mouseover",fadeLinkIndividual(.15,true))
    .on("mouseout",fadeLinkIndividual(1,false))
    .style("opacity",0);

  infolinks.exit().remove();

  var link_grupal = svg.selectAll(".link_grupal")
        .data(bilinksGrupales,function(d){ 
          return d[0].index+"-"+d[2].index;
        });

  link_grupal.enter().append("path")
      .attr("class", "link_grupal")
      .attr("id", function(d){ return "link_grupal"+d[0].index+"-"+d[2].index;})
      .attr("pointer-events", "none")
      .style("stroke",general_view_edge_color)
      .style("stroke-width",stroke_width_general_view)
      .style("fill","none")
      .style("opacity",function(d){
        if (!vista_individual){
          return 1;
        }else{
          return 0;
        }
      })
      .style("visibility",function(d){
        if (!vista_individual){
          return "visible";
        }else{
          return "hidden";
        }
      });;

  link_grupal.exit().remove();

  var link_info_grupal = svg.selectAll(".link_info_grupal")
    .data(bilinksGrupales,function(d){ 
      return d[0].index+"-"+d[2].index;
  });

  link_info_grupal.enter().append("path")
      .attr("class", "link_info_grupal")
      .attr("pointer-events", "visible")
      .style("stroke",general_view_edge_color)
      .style("stroke-width",20)
      .on("click", clickLink(.15))
      .on("mouseover",fadeLinkGrupal(0.15,true))
      .on("mouseout",fadeLinkGrupal(1,false))
      .style("opacity",0)
      .style("visibility","hidden");

  link_info_grupal.exit().remove();


  //Actualiza los clipPath que permiten recortar las imágenes de los nodos de forma circular -- Agregado el 03-01-15

  //Añade la nueva data a los clipPath, correspondiente a la data de los nodos
  var defsAntiguo = svg.selectAll(".clipPath_node")
        .data(dataUsuarios,function(d){
          return d.id_usuario;});

  //Crea nuevos clipPath en caso que hayan nuevos nodos
  var defs= defsAntiguo.enter().append("clipPath")
        .attr("id", function(d){
          return "clipnode"+d.id_usuario;})
        .attr("class","clipPath_node")
        .append("circle");

  //Setea los atributos correspondientes a cada clipPath
  defsAntiguo.selectAll("circle")
    .transition().duration(1500)
        .attr("cx", 0)
        .attr("cy", 0)
        .attr("r",function(d){
            return ratio_foto*scalingIndividual(d.participacion);});

  //Borra los clipPath de los nodos que ya no existen en la red
  defsAntiguo.exit().remove();
  //Fin actualizacion de los clipPath


  //Actualiza el tamaño de los nodos (tanto circle como image) -- Agregado el 03-01-15
  
  //Añade la nueva data a la seleccion que corresponde a los nodos
  var nodeAntiguo = svg.selectAll(".node")
      .data(dataUsuarios,function(d){
        //console.log("id_usuario: "+d.id_usuario);
        return d.id_usuario;
      });

  //Crea nuevos nodos en caso de que se agreguen nuevos integrantes a la red de participación
  var nodeNuevo= nodeAntiguo.enter()
      .append("g")
        .attr("class", "node")
        .attr("id", function(d){
          //console.log("Nodo nuevo "+d.id_usuario);
          return "node"+d.id_usuario;
        })
        .on("mouseover", fadeIndividual(.1,true))
        .on("mouseout", fadeIndividual(1,false))
        .on("mousedown", function() { d3.event.stopPropagation(); })
        .on("click", clickNode)
        //.on("dblclick",doubleClickNode)
        .call(force_individual.drag); 

  var nodeCircle= nodeNuevo.append("circle");

  var nodeImage=nodeNuevo.append("image");
  nodeImage.attr("xlink:href",function(d){return d.url_imagen})
      .attr("clip-path",function(d){return "url(#clipnode"+d.id_usuario+")";});

  var nodeText=nodeNuevo.append("svg:text");

  nodeText.attr("class","nodetext")
      .style("display","none")
      .text(function(d) { 
        if (d.id_usuario==id_usuario){
          return "<?php echo $lang_hv_yo; ?>";
        }else{
          var indice_substring=d.nombre.indexOf(' ');
          return d.nombre.slice(0,indice_substring);
        }
      });
  //Fin creación de nuevos nodos

  //Elimina nodos que por alguna u otra razón puedan haber sido eliminados de la red en el intertanto de una actividad
  nodeAntiguo.exit().remove();

  //Setea los atributos a los nuevos nodos y modifica los de los nodos existentes en caso de ser necesario
  nodeAntiguo.selectAll("circle")
    .transition().duration(1500)
      .attr("r", function(d){
            //console.log(d.participacion+" "+d.nombre+" radio actualizado");
            return (1+width_border_ratio)*scalingIndividual(d.participacion);
      })
      .style("fill", function(d) { return (d.id_grupo!=-1) ? color_grupos(d.id_grupo):"#00000";});//mapeo_idgrupo[d.id_experiencia][d.id_grupo]); });

  nodeAntiguo.selectAll("image")
    .transition().duration(1500)
      .attr("x",function(d){return -1*ratio_foto*scalingIndividual(d.participacion);})
      .attr("y",function(d){return -1*ratio_foto*scalingIndividual(d.participacion);})
      .attr("width",function(d){return 2*ratio_foto*scalingIndividual(d.participacion);})
      .attr("height",function(d){return 2*ratio_foto*scalingIndividual(d.participacion);});

  nodeAntiguo.selectAll(".nodetext")
    .transition().duration(1500)
      .attr("text-anchor", "middle")
      .attr("dy", function(d){ return (1+width_border_ratio)*scalingIndividual(d.participacion)+8;});
  
  nodeAntiguo.selectAll(".imagen_profesor")
    .transition().duration(1500)
        .attr("x",-1*(tamano_img_prof/2))
        .attr("y",function(d){return -(1+width_border_ratio)*scalingIndividual(d.participacion)-(tamano_img_prof-1);})
  
  actualizarRedGrupal();

  var k_ind = Math.sqrt(nodesIndividuales.length / (width * height));
  
  force_individual.nodes(nodesIndividuales)
    .links(linksIndividuales)
    /*.charge(-2/k)
    .gravity(4*k)
    .size([width, height])*/
    .start();

  //Actualiza la vista centrada individual
  if (vista_individual && self_centered){
    actualizarSelfCenteredIndividual();
  }



  var k_grupal = Math.sqrt(dataGrupos.length / (width * height));
  
  //nodesGrupales=array_svg_grupos.slice();

  force_grupal.nodes(nodesGrupales)
    .links(linksGrupales)
    /*.charge(-2/k)
    .gravity(4*k)
    .size([width, height])*/
    .start();

  setMaxMinRadiusGrupal();
  //Código post-test de usabilidad
  scalingLinkGrupal = d3.scale.log()
      .domain([d3.min(dataLinksGrupales, function(d){return d.total_interacciones;}), d3.max(dataLinksGrupales, function(d){return d.total_interacciones;})])
      .range([stroke_width_general_view, 1.5*min_radius]);

  if (!vista_individual && self_centered){
    actualizarSelfCenteredGrupal();
  }
  
}

function actualizarSelfCenteredIndividual(){
  var nodes=dataUsuarios.slice();

  var i=0;
  var n=dataUsuarios.length;

  var related_nodes=new Array(n);
  for (var a=0;a<n;a++){
    related_nodes[a]=0;
  }
  var n_connected_center=nodes[nodo_centro].weight;
  console.log("Nodos conectados al centro: "+n_connected_center);  
  var c=0;
  var centro_x=width*offset_posx_radial_layout-zoom.translate()[0];
  var centro_y=height/2-zoom.translate()[1];
  while (i < n){
    if (i==nodo_centro){
      related_nodes[i]=1;
      /*nodes[i].x=width*offset_posx_radial_layout;
      nodes[i].y=height/2;*/
      nodes[i].x=centro_x;
      nodes[i].y=centro_y;
      nodes[i].px=nodes[i].x;
      nodes[i].py=nodes[i].y; 
    }else{
      distance_radius=isConnected(nodes[nodo_centro],nodes[i]);
      if (!distance_radius) {
        //distance_radius = max_radius_radial_layout;
        //Código agregado post-test usabilidad
        //nodes[i].fixed=true;
      }else{
        related_nodes[i]=1;
        var theta= c / n_connected_center * 2 * Math.PI - (Math.PI/2);
        distance_radius = height/2 - max_radius_ind;// - scalingDistance(distance_radius);
        nodes[i].x=distance_radius*Math.cos(theta)+centro_x;
        nodes[i].y=distance_radius*Math.sin(theta)+centro_y;
        nodes[i].px=nodes[i].x;
        nodes[i].py=nodes[i].y;
        c++;
      }
    }
    i++;
  }

  svg.selectAll(".node")
    .style("display",function(d){
      return (related_nodes[d.index]!=0) ? "inline" : "none";
  });

  svg.selectAll(".nodetext")
    .style("display",function(d){
      return (related_nodes[d.index]!=0) ? "inline" : "none";
  });

  svg.selectAll(".node").transition().duration(500).ease("sin")
    //.delay(200).duration(500).ease("sin")
    .attr("cx", function(d){ return d.x})
    .attr("cy", function(d){ return d.y})     
    .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
    .style("opacity",1);

  var newLinks= svg.selectAll(".link")
    .transition().duration(500).ease("sin")
    .style("visibility",function(d){
      //return (d[0]!=nodo_centro || d[2]!=nodo_centro) ?  "visible" : "hidden";
      if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
        return "visible";
      }else{
        return "hidden";
      }
    })
    .style("opacity",function(d){
      //return (d[0]!=nodo_centro || d[2]!=nodo_centro) ?  "visible" : "hidden";
      if (d[0].index===nodo_centro || d[2].index===nodo_centro) {
        return 1;
      }else{
        return 0;
      }
    })
    .style("stroke-width", function(d){
      return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkIndividual(isConnected(d[0],d[2])) : stroke_width_general_view;
    });
}

function actualizarSelfCenteredGrupal(){
    var nodes      =array_svg_grupos.slice();
    //var nodes      =svg.selectAll(".contenedor_grupo").data();
    var nodes_grupo=svg.selectAll(".grupo");
    var nodes_int  =svg.selectAll(".integrante");

    var i=0;
    var n=nodes.length;

    var related_nodes=new Array(n);
    for (var a=0;a<n;a++){
      related_nodes[a]=0;
    }
    var n_connected_center=nodes[nodo_centro].weight; 
    console.log("Nodos conectados a "+nodo_centro+": "+n_connected_center); 
    var c=0;
    var centro_x=width*offset_posx_radial_layout-zoom.translate()[0];
    var centro_y=height/2-zoom.translate()[1];
    while (i < n){
      if (i==nodo_centro){
        related_nodes[i]=1;
        /*nodes[i].x=width*offset_posx_radial_layout;
        nodes[i].y=height/2;*/
        nodes[i].x=centro_x;
        nodes[i].y=centro_y;
        nodes[i].px=nodes[i].x;
        nodes[i].py=nodes[i].y; 
      }else{
        distance_radius=isConnectedToTeam(nodes[nodo_centro].index,nodes[i].index);
        if (!distance_radius) {
          //distance_radius = max_radius_radial_layout;
          //Código agregado post-test usabilidad
          //nodes[i].fixed=true;
        }else{
          related_nodes[i]=1;
          var theta= c / n_connected_center * 2 * Math.PI - (Math.PI/2);
          distance_radius = height/2-max_radius + 15;// - scalingDistance(distance_radius);
          nodes[i].x=distance_radius*Math.cos(theta)+centro_x;
          nodes[i].y=distance_radius*Math.sin(theta)+centro_y;
          nodes[i].px=nodes[i].x;
          nodes[i].py=nodes[i].y;
          c++;
        }
      }
      i++;
    }

    svg.selectAll("g.contenedor_grupo")
      .style("display",function(d){
        return (related_nodes[d.index]!=0) ? "inline" : "none";
    });

    //Agrega etiquetas de texto a nodos seleccionados
    svg.selectAll(".grouptext")
      .style("display",function(d){
        return (related_nodes[d.index]!=0) ? "inline" : "none";
    });

    svg.selectAll("g.contenedor_grupo").transition().duration(500).ease("sin")
      //.delay(200).duration(500).ease("sin")
      .attr("cx", function(d){ return d.x})
      .attr("cy", function(d){ return d.y})
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .style("opacity",1);

    svg.selectAll(".link_grupal")
      .style("opacity",function(d){
        return scalingTiempoLinks(ponderacionTiempoLinkGrupal(d[0].index,d[2].index));
      })
      .style("stroke",general_view_edge_color);

    svg.selectAll(".link_grupal").transition().delay(500).duration(500).ease("sin")
      .style("stroke-width", function(d){
        return (d[0].index==nodo_centro || d[2].index==nodo_centro) ? scalingLinkGrupal(isConnectedToTeam(d[0].index,d[2].index)):stroke_width_general_view;
      });
}

function recargarVisualizacion(){
  console.log("Recarga visualización: "+ruta_json);
  d3.json(ruta_json, function(error, data) {
    dataUsuariosAct=data.nodes.slice();
    dataLinksUsuariosAct=data.links;
    dataGruposAct=data.grupos;
    dataLinksGrupalesAct=data.links_grupos;

    //console.log("data links usuarios");
    //console.log(dataLinksUsuariosAct);

    var nro_nodos= dataUsuariosAct.length;

    var misma_red_carga_anterior=true;

    for (var i=0;i<nro_nodos;i++){
      var nodoRedAnterior=dataUsuarios[i];
      var nodoRedActualizada=dataUsuariosAct[i];
      //console.log("Id Anterior : "+nodoRedAnterior.id_usuario+" Id Actualizado: "+nodoRedActualizada.id_usuario);
      if (nodoRedAnterior.id_usuario!=nodoRedActualizada.id_usuario){
        misma_red_carga_anterior=false;
        console.log("No coinciden datos de la red anterior con la actual");
        break;
      }else{
        actualizarNodoIndividual(dataUsuarios[i],dataUsuariosAct[i]);
      }
    }

    var nro_nodos_grupales=dataGruposAct.length;

    for (var j=0;j<nro_nodos_grupales;j++){
      var grupoRedAnterior=dataGrupos[j];
      var grupoRedActualizada=dataGruposAct[j];
      if (nodoRedAnterior.id_grupo_kelluwen!=nodoRedActualizada.id_grupo_kelluwen){
        misma_red_carga_anterior=false;
        console.log("No coinciden datos de la red anterior con la actual");
        break;
      }else{
        //console.log("Se actualizó nodo grupal "+j);
        actualizarNodoGrupal(dataGrupos[j],dataGruposAct[j]);
      }
    }

    if (misma_red_carga_anterior){
      //Agrega links inexistentes en la red anterior
      dataLinksUsuariosAct.forEach(function(d){
        var id_source=d.id_usuario1;
        var id_target=d.id_usuario2;
        var existeLink=false;
        for (var i = 0; i < dataLinksUsuarios.length; i++) {
          if (dataLinksUsuarios[i].id_usuario1 == id_source && dataLinksUsuarios[i].id_usuario2 == id_target) {
              //console.log("(Buscando agregar) Link entre "+id_source+" y "+id_target+" existe (Source: "+d.source+" target: "+d.target+")");
              existeLink=true;
              actualizarLinkIndividual(dataLinksUsuarios[i],d);
              break;
          }
        }
        if (!existeLink){
          //console.log("Link entre "+id_source+" y "+id_target+" no existe");
          //console.log("Nro de interacciones: "+d.total_interacciones);
          añadirLinkIndividual(d);
        }
      });

      //Elimina links que ya no existen en la red actual en comparación con la red anterior
      dataLinksUsuarios.forEach(function(d){
        var id_source=d.id_usuario1;
        var id_target=d.id_usuario2;
        var existeLink=false;
        for (var i = 0; i < dataLinksUsuariosAct.length; i++) {
          if (dataLinksUsuariosAct[i].id_usuario1 == id_source && dataLinksUsuariosAct[i].id_usuario2 == id_target) {
              //console.log("(Buscando borrar) Link entre "+id_source+" y "+id_target+" existe (Source: "+d.source+" target: "+d.target+")");
              existeLink=true;
              break;
          }
        }
        if (!existeLink){
          //console.log("Link entre "+id_source+" y "+id_target+" no existe");
          //console.log("Nro de interacciones: "+d.total_interacciones);
          removerLinkIndividual(d);
        }
      });

      //Agrega links grupales inexistentes en la red anterior
      dataLinksGrupalesAct.forEach(function(d){
        var id_source=d.source;
        var id_target=d.target;
        var existeLink=false;
        for (var i = 0; i < dataLinksGrupales.length; i++) {
          if (dataLinksGrupales[i].source == id_source && dataLinksGrupales[i].target == id_target) {
              //console.log("(Buscando agregar) Link Grupal entre "+id_source+" y "+id_target+" existe (Source: "+d.source+" target: "+d.target+")");
              existeLink=true;
              actualizarLinkGrupal(dataLinksGrupales[i],d);
              break;
          }
        }
        if (!existeLink){
          //console.log("Link Grupal entre "+id_source+" y "+id_target+" no existe");
          //console.log("Nro de interacciones: "+d.total_interacciones);
          añadirLinkGrupal(d);
        }
      });

      //Elimina links grupales que ya no existen en la red actual en comparación con la red anterior
      dataLinksGrupales.forEach(function(d){
        var id_source=d.source;
        var id_target=d.target;
        var existeLink=false;
        for (var i = 0; i < dataLinksGrupalesAct.length; i++) {
          if (dataLinksGrupalesAct[i].source == id_source && dataLinksGrupalesAct[i].target == id_target) {
              //console.log("(Buscando borrar) Link Grupal entre "+id_source+" y "+id_target+" existe (Source: "+d.source+" target: "+d.target+")");
              existeLink=true;
              break;
          }
        }
        if (!existeLink){
          //console.log("Link Grupal entre "+id_source+" y "+id_target+" no existe");
          //console.log("Nro de interacciones: "+d.total_interacciones);
          removerLinkGrupal(d);
        }
      });
    }

    //Actualiza distancia para cada uno de los nodos en la vista individual centrada, luego del cambio ocurrido en los datos
    var n_nodes=dataUsuarios.length;
    dataLinksUsuarios.forEach(function(d) {
      linkedByIndex[d.source + "," + d.target] = d.total_interacciones;
      antiguedadLinkIndividual[d.source+","+d.target] = d.ponderacion;
      var data_historial_interacciones= d.historial_interacciones;

      var respuestas_usuario1= d.msjs_respuesta_usuario1;
      var respuestas_usuario2= d.msjs_respuesta_usuario2;
      var megusta_usuario1   = d.megusta_usuario1;
      var megusta_usuario2   = d.megusta_usuario2;

      var nro_interacciones = data_historial_interacciones.length;
      var historial_arreglo = [];
      for (var i=0;i<nro_interacciones;i++){
        historial_arreglo[i]=data_historial_interacciones[i];
      }
      historialInteracciones[d.source + "," + d.target] = historial_arreglo;

      //Almacena cuántas respuestas ha dado cada participante de la interacción (posición 0: usuario1, posición 1: usuario2)
      var respuestas_usuarios=new Array(2);
      respuestas_usuarios[0]=respuestas_usuario1;
      respuestas_usuarios[1]=respuestas_usuario2;
      respuestasInteracciones[d.source + "," + d.target]=respuestas_usuarios;

      //Almacena cuántos me gusta ha dado cada participante de la interacción (posición 0: usuario1, posición 1: usuario2)
      var megusta_usuarios=new Array(2);
      megusta_usuarios[0]=megusta_usuario1;
      megusta_usuarios[1]=megusta_usuario2;
      megustaInteracciones[d.source + "," + d.target]=megusta_usuarios;

      var sourceIndex=d.source;
      var targetIndex=d.target;

      //Determina el nro máximo y mínimo de interacciones de cada usuario
      if (arrayMaxMinInteraccion[sourceIndex]){
        var currentMin=arrayMaxMinInteraccion[sourceIndex].min;
        var currentMax=arrayMaxMinInteraccion[sourceIndex].max;
        if (d.total_interacciones<currentMin){
          arrayMaxMinInteraccion[sourceIndex].min=d.total_interacciones;
        }
        if (d.total_interacciones>currentMax){
          arrayMaxMinInteraccion[sourceIndex].max=d.total_interacciones;
        }
      }else{
        arrayMaxMinInteraccion[sourceIndex]={min: d.total_interacciones, max:d.total_interacciones};
      }

      if (arrayMaxMinInteraccion[targetIndex]){
        var currentMin=arrayMaxMinInteraccion[targetIndex].min;
        var currentMax=arrayMaxMinInteraccion[targetIndex].max;
        if (d.total_interacciones<currentMin){
          arrayMaxMinInteraccion[targetIndex].min=d.total_interacciones;
        }
        if (d.total_interacciones>currentMax){
          arrayMaxMinInteraccion[targetIndex].max=d.total_interacciones;
        }
      }else{
        arrayMaxMinInteraccion[targetIndex]={min: d.total_interacciones, max:d.total_interacciones};
      }
    });

    for (var i = 0; i < n_nodes; i++) {
      if (arrayMaxMinInteraccion[i]){
        if (arrayMaxMinInteraccion[i].min===arrayMaxMinInteraccion[i].max){
          arrayMaxMinInteraccion[i].min=0.1;
        }
      }
    };

    //Actualiza distancia para cada uno de los nodos en la vista grupal centrada, luego del cambio ocurrido en los datos
    //Genera el arreglo que determina el menor y el mayor número de interacciones por cada elemento a nivel de grupo
    var n_nodes_grupos=dataGrupos.length;

    dataLinksGrupales.forEach(function(d) {
      linkedByTeam[d.source + "," + d.target] = d.total_interacciones;
      antiguedadLinkGrupal[d.source + "," + d.target] = d.ponderacion;
      var data_historial_interacciones= d.historial_interacciones;

      var respuestas_grupo1= d.msjs_respuesta_grupo1;
      var respuestas_grupo2= d.msjs_respuesta_grupo2;
      var megusta_grupo1   = d.megusta_grupo1;
      var megusta_grupo2   = d.megusta_grupo2;

      var nro_interacciones = data_historial_interacciones.length;
      var historial_arreglo = [];
      for (var i=0;i<nro_interacciones;i++){
        historial_arreglo[i]=data_historial_interacciones[i];
      }
      historialInteraccionesGrupales[d.source + "," + d.target] = historial_arreglo;
      var sourceIndex=d.source;
      var targetIndex=d.target;

      //Almacena cuántas respuestas ha dado cada grupo de la interacción (posición 0: grupo1, posición 1: grupo2)
      var respuestas_grupos=new Array(2);
      respuestas_grupos[0]=respuestas_grupo1;
      respuestas_grupos[1]=respuestas_grupo2;
      respuestasInteraccionesGrupales[d.source + "," + d.target]=respuestas_grupos;

      //Almacena cuántos me gusta ha dado cada grupo de la interacción (posición 0: grupo1, posición 1: grupo2)
      var megusta_grupos=new Array(2);
      megusta_grupos[0]=megusta_grupo1;
      megusta_grupos[1]=megusta_grupo2;
      megustaInteraccionesGrupales[d.source + "," + d.target]=megusta_grupos;


      var nro_interacciones = data_historial_interacciones.length;
      var historial_arreglo = [];
      for (var i=0;i<nro_interacciones;i++){
        historial_arreglo[i]=data_historial_interacciones[i];
      }
      
      //Determina el nro máximo y mínimo de interacciones de cada grupo
      if (arrayMaxMinInteraccionGrupal[sourceIndex]){
        var currentMin=arrayMaxMinInteraccionGrupal[sourceIndex].min;
        var currentMax=arrayMaxMinInteraccionGrupal[sourceIndex].max;
        if (d.total_interacciones<currentMin){
          arrayMaxMinInteraccionGrupal[sourceIndex].min=d.total_interacciones;
        }
        if (d.total_interacciones>currentMax){
          arrayMaxMinInteraccionGrupal[sourceIndex].max=d.total_interacciones;
        }
      }else{
        arrayMaxMinInteraccionGrupal[sourceIndex]={min: d.total_interacciones, max:d.total_interacciones};
      }

      if (arrayMaxMinInteraccionGrupal[targetIndex]){
        var currentMin=arrayMaxMinInteraccionGrupal[targetIndex].min;
        var currentMax=arrayMaxMinInteraccionGrupal[targetIndex].max;
        if (d.total_interacciones<currentMin){
          arrayMaxMinInteraccionGrupal[targetIndex].min=d.total_interacciones;
        }
        if (d.total_interacciones>currentMax){
          arrayMaxMinInteraccionGrupal[targetIndex].max=d.total_interacciones;
        }
      }else{
        arrayMaxMinInteraccionGrupal[targetIndex]={min: d.total_interacciones, max:d.total_interacciones};
      }
    });

    for (var i = 0; i < n_nodes_grupos; i++) {
      if (arrayMaxMinInteraccionGrupal[i]){
        if (arrayMaxMinInteraccionGrupal[i].min===arrayMaxMinInteraccionGrupal[i].max){
          arrayMaxMinInteraccionGrupal[i].min=0.1;
        }
      }
    };

    //En caso de que haya un nodo seleccionado o una relación (link) entre nodos seleccionada,
    //actualiza los datos de participación o de interacciones correspondientes
    if (self_centered){
      if (!clickRelacion){
        if (vista_individual){
          actualizarDespliegueDatosParticipacionIndividual(nodo_centro);
          desplegarHistorialParticipacionIndividual(nodo_centro);
        }else{
          actualizarDespliegueDatosParticipacionGrupal(nodo_centro);
          desplegarHistorialParticipacionGrupal(nodo_centro);
        }
      }else{
        if (vista_individual){
          var data_nodo_seleccionado=svg.select("#node"+id_nodo_seleccionado).data()[0];
          var data_nodo_conectado=svg.select("#node"+id_nodo_conectado).data()[0];
          if (obtenerHistorialInteracciones(nodo_centro,data_nodo_conectado.index)){
            actualizarDespliegueDatosInteraccionIndividual(data_nodo_seleccionado,data_nodo_conectado);
            desplegarHistorialInteracciones(data_nodo_seleccionado,data_nodo_conectado);
          }
          //En caso de que la relación que haya sido consultada haya sido borrada, es necesario quitar la selección
          //y hacer selección en el nodo del centro de la vista selfcentered
          else{
            $("#node"+id_nodo_seleccionado).d3Click();
          }
        }else{
          if (obtenerHistorialInteraccionesGrupales(nodo_centro,id_nodo_conectado)){
            actualizarDespliegueDatosInteraccionGrupal(dataGrupos[nodo_centro],dataGrupos[id_nodo_conectado]);
            desplegarHistorialInteraccionesGrupales(dataGrupos[nodo_centro],dataGrupos[id_nodo_conectado]);
          }else{
            $("#grupo"+id_nodo_seleccionado).d3Click();
          }
        }
      }
    }

    actualizarVisualizacion();

    if (misma_red_carga_anterior){
      mantenerNodosTop();
    }
    return misma_red_carga_anterior;
  });
  d3.json('/app/dataviz/wordcloud/obtiene_frecuencia_palabras.php?codexp='+id_experiencia,function(error,data){
    var words=data.frecuencia_palabras;
    words=words.sort(function (a, b) {
      return d3.descending(parseInt(a.size),parseInt(b.size));
    });
    words=words.slice(0,50);
    if (words){
      if (!wordcloud){
        scalingPalabras= d3.scale.log()
        .domain([d3.min(words, function(d){return parseInt(d.size)}), d3.max(words, function(d){return parseInt(d.size)})])
        .range([14,36]);
        wordcloud = wordCloud('#nube_palabras',words,scalingPalabras,'Impact');
      }
      wordcloud.recargarWordcloud(words);
    }
  });

}

//Funciones para habilitar zoom y pan
function zoomed() {
  pan_actual=d3.event.translate;
  zoom_actual=d3.event.scale;
  svg.attr("transform", "translate(" + pan_actual + ")scale(" + zoom_actual + ")");
  zoom.scale(zoom_actual);
  zoom.translate(pan_actual);
  startZoomed = new Date();
  timeZoomed = startZoomed - endZoomed;
  if (timeZoomed>500){
    if (ultimo_zoom>zoom_actual){
      console.log("zoom - timezoomed: "+timeZoomed);
      registrarClickSeccion(id_sesion,'Zoom out mouse');
    }else{
      if(ultimo_zoom<zoom_actual){
        console.log("zoom + timezoomed: "+timeZoomed);
        registrarClickSeccion(id_sesion,'Zoom in mouse');
      }else{
        var zoom_iguales=true;
        if (ultimo_zoom!=zoom_actual) zoom_iguales=false;
        var pan_iguales=false;
        if (parseFloat(ultimo_pan[0])==parseFloat(pan_actual[0]) && parseFloat(ultimo_pan[1])==parseFloat(pan_actual[1])) pan_iguales=true;
        if (zoom_iguales && !pan_iguales){
          registrarClickSeccion(id_sesion,'Desplazar mouse');
        }
      }
    }
  }
  endZoomed= new Date();
  ultimo_pan=pan_actual;
  ultimo_zoom=zoom_actual
}

function dragstarted(d) {
  d3.event.sourceEvent.stopPropagation();
  d3.select(this).classed("dragging", true);
}

function dragged(d) {
  d3.select(this).attr("cx", d.x = d3.event.x).attr("cy", d.y = d3.event.y);
}

function dragended(d) {
  d3.select(this).classed("dragging", false);
}

jQuery.fn.d3Click = function () {
  this.each(function (i, e) {
    var evt = document.createEvent("MouseEvents");
    evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
    e.dispatchEvent(evt);
  });
};

function obtenerUsuariosPorClase(nro_clases,nodos){
  var usuarios_por_clase=[];
  for (var i=0;i<nro_clases;i++){
    usuarios_por_clase[i]=0;
  }
  nodos.forEach(function(d){
    usuarios_por_clase[d.id_experiencia]++;  
  });
  return usuarios_por_clase;
}

function obtenerParticipacionPorClase(nro_clases,nodos){
  var participacion_por_clase=[];
  for (var i=0;i<nro_clases;i++){
    participacion_por_clase[i]=0;
  }
  nodos.forEach(function(d){
    participacion_por_clase[d.id_experiencia]=participacion_por_clase[d.id_experiencia]+(d.participacion-1);  
  });
  return participacion_por_clase;
}

function focoVisualizacionUsuarioActivo(id_usuario){
  //Se posiciona el centro del foco visual en el usuario activo que esta consultando la visualización
    if (array_usuarios){
      var index_usuario=array_usuarios.indexOf(id_usuario.toString());
      if (index_usuario!=-1 ){

        svg.transition().delay(100).duration(500).attr("transform", function(){

          if (svg.select("#node"+id_usuario).attr("cx") && svg.select("#node"+id_usuario).attr("cy")){
            var x_usuario_activo=svg.select("#node"+id_usuario).attr("cx");
            var y_usuario_activo=svg.select("#node"+id_usuario).attr("cy");
          }else{

            var x_usuario_activo=centros_clases[dataUsuarios[index_usuario].id_experiencia].x;
            var y_usuario_activo=centros_clases[dataUsuarios[index_usuario].id_experiencia].y;
          }
          var x=width/2-x_usuario_activo;
          var y=height/2-y_usuario_activo;
          zoom.translate([x,y]);
          zoom.scale(1);
          //return "translate(" +x+" "+y+")scale("+zoom.scale()+")";
          return "translate(" +x+" "+y+")scale(1)";
        });
        
      }
    }
    
}

function cargarMensajePopUp(mensaje){
    mensaje_decodificado=unescape(mensaje);
    var $dialog = $('<div class=\"mensaje_compartido\"> <p>'+mensaje_decodificado+'</p></div>')
    
    .dialog({
        autoOpen: false,
        title: '<?php echo $lang_header_inc_mensaje;?>',
        width: 500,
        height: 200,
        modal: true,
        buttons: {
            "<?php echo $lang_hv_cerrar; ?>": function() {
            $(this).dialog("close");
            }
        },
        close: function(ev, ui) {
            $(this).remove();
        }
        });
    $dialog.dialog('open');
    return false;
}

function cargarVideo(url_video){
    //var $dialog = $('<div><iframe width="640" height="360" src="https://www.youtube.com/embed/6uMxWMNhbSo" frameborder="0" allowfullscreen></iframe></div>')
    var $dialog = $('<div id="dialog-tutorial"><iframe width="640" height="360" src="https://www.youtube.com/embed/Ce0y1tkkmZs?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe></div>')
    .dialog({
        height: "400px",
        width: "665px",
        resizable: false,
        modal: true,
        title: "<?php echo $lang_hv_tutorial_hv_bitacora; ?>",
        close: function () {
         // necessary as it stops the video when the dialog is closed
         clickAyuda=false;
         $(this).dialog('destroy');
                              //For Firefox, you need to remove the popup from the DOM
                              //completely. The following 2 lines are need.
                              content1 = $("#dialog-tutorial").remove();
                              //counter1 = counter1 +1;
        }
       });
    //dialog.dialog("option", "title", "Tutorial").dialog("open");
    $dialog.dialog('open');
    return false;
}

function mostrarDivHistorialAjustado(){
  var height_history=$("#visualizacion_actividad").height()-$("#div-userinfo").outerHeight()-20;
  $('#div-usershistory').height(height_history);

  if (!isscrollpane){
      setTimeout(function(){
        $('#div-usershistory').jScrollPane({
          horizontalGutter:5,
          verticalGutter:5,
          'showArrows': false
        });
        $('.jspDrag').hide();
        $('.jspScrollable').mouseenter(function(){
            $(this).find('.jspDrag').stop(true, true).fadeIn('slow');
        });
        $('.jspScrollable').mouseleave(function(){
            $(this).find('.jspDrag').stop(true, true).fadeOut('slow');
        });
        scrollpane=$('#div-usershistory').data('jsp');
        scrollpane.scrollTo(0,0);
        isscrollpane=true;
      },100);
    }else{
      scrollpane.reinitialise();
      scrollpane.scrollTo(0,0);
    }

  $('#div-usershistory').show("slide",{"direction":"up"},1000);

  /*Añadir custom scrollPane via JScrollPane*/
  /*setTimeout(function(){
    if (!api){
      $('#div-usershistory').jScrollPane({
        horizontalGutter:5,
        verticalGutter:5,
        'showArrows': false
      });
      $('.jspDrag').hide();
      $('.jspScrollable').mouseenter(function(){
          $(this).find('.jspDrag').stop(true, true).fadeIn('slow');
      });
      $('.jspScrollable').mouseleave(function(){
          $(this).find('.jspDrag').stop(true, true).fadeOut('slow');
      });
      api=$('#div-usershistory').data('jsp');
    }else{
      api.reinitialise();
      api.scrollTo(0,0);
    }
  },100);*/
}

function normalizar(valor,min_rango,max_rango){
  if (valor<min_rango){
    return 0;
  }else{
    if (valor>max_rango){
      return 1;
    }else{
      var rango=max_rango-min_rango;
      return (valor-min_rango)/rango;
    }
  }
}

function setMaxMinRadiusGrupal(){
  var array_radius_grupales=[];
      svg.selectAll(".grupo circle").each(function(d,i){
        array_radius_grupales.push(parseFloat(d3.select(this).attr("r")));
      });

  console.log(array_radius_grupales);
  max_radius=d3.max(array_radius_grupales);
  min_radius=d3.min(array_radius_grupales);
}

function mousemove(d, i) {
  mouse_coords=d3.mouse(this);
}

/*function clickRadialLayout(){
    d3.event.stopPropagation();
}*/


</script>
