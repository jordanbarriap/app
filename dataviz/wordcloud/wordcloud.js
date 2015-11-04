//Simple animated example of d3-cloud - https://github.com/jasondavies/d3-cloud
//Based on https://github.com/jasondavies/d3-cloud/blob/master/examples/simple.html

// Encapsula la funcionalidad de la nube de palabras en Kelluwen
function wordCloud(selector, words, scalingPalabras,font) {
    var width = $(selector).width()+5;
    var height= $(selector).height()-$("#titulo_nube_palabras").height()+15;//el +5 es porque el algoritmo de wordcloud desaprovecha un poco la seccion inferior del svg

    //Construye el elemento SVG que sirve de canvas a la nube de palabras
    var svg_wordcloud = d3.select(selector).append("svg")
        .attr("width", width)
        .attr("height", height)
        .attr("id","svg_nube_palabras")
        .append("g")
        .attr("transform", "translate("+(width/2)+","+(height/2)+")");

    $("#svg_nube_palabras").css("margin-top",28);
    //Dibuja la nube de palabras
    function draw(words) {
        var cloud = svg_wordcloud.selectAll("g text")
                        .data(words, function(d) { return d.text; })

        //Ingresa palabras siguiendo el patrón de actualización de d3
        cloud.enter()
            .append("text")
            .style("font-family", font)
            .style("fill", "#666")//function(d, i) { return fill(i); })
            .attr("text-anchor", "middle")
            .attr('font-size', 1)
            .text(function(d) { return d.text; });

        //Actualización de palabras nuevas y ya existentes de acuerdo al patrón de actualización de d3
        cloud
            .transition()
            .delay(500)
                .duration(1500)
                .style("font-size", function(d) { return d.size+"px"; })
                .attr("transform", function(d) {
                    return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
                })
                .style("fill-opacity", 1);

        //Palabras a eliminar de la nube de palabras
        cloud.exit()
            .transition()
                .duration(500)
                .style('fill-opacity', 1e-6)
                .attr('font-size', 1)
                .remove();
    }

    //Use the module pattern to encapsulate the visualisation code. We'll
    // expose only the parts that need to be public.
    return {

        //Recompute the word cloud for a new set of words. This method will
        // asycnhronously call draw when the layout has been computed.
        //The outside world will need to call this function, so make it part
        // of the wordCloud return value.
        recargarWordcloud: function(words) {
            d3.layout.cloud().size([width, height])
                .words(words)
                .padding(0.75)
                .rotate(function() { return 0;})//~~(Math.random() * 2) * 90; })
                .font(font)
                .fontSize(function(d) { console.log(d.text+" size: "+d.size+" scaling: "+scalingPalabras(d.size)); return scalingPalabras(d.size); })
                .on("end", draw)
                .start();
        }
    }

}
