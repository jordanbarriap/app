<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
$noticias_url = "http://feeds.feedburner.com/kelluwen?format=xml";
?>
<div class="inicio_noticias">
    <?php
    $cache_time = 3600 * 24; // 24 hours

    $cache_file = $config_ruta_cache . "test.rss";
    $timedif = @(time() - filemtime($cache_file));

    if (file_exists($cache_file) && $timedif < $cache_time) {
        $string = file_get_contents($cache_file);
    } else {
        $string = file_get_contents($noticias_url);
        if ($f = fopen($cache_file, 'w')) {
            fwrite($f, $string, strlen($string));
            fclose($f);
        }
    }
    $rss = simplexml_load_string($string);

    $max_noticias = 0;
    foreach ($rss->channel->item as $item) {
        if ($max_noticias > 2) {
            break;
        }
        preg_match_all('/<img[^>]+>/i', $item->description, $result);
    ?>
        <div class="inicio_noticia">
        <?php $aux = $result[0]; ?>
<!--            <div class="imagen_noticia"><?php print_r($aux[0]); ?></div>-->
        <div class="enlace_noticia"><a href="<?php echo $item->link; ?>"  target="_blank"><?php echo $item->title; ?></a></div>
    </div>
    <br/>
    <?php
        $max_noticias++;
    }
    ?>
</div>