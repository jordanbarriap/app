<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz . "conf/config.php");
require_once($ruta_raiz . "inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");

$conexion = dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);
$semestres_menu = dbExpSemestresExperiencias($conexion);
dbDesconectarMySQL($conexion);

?>
<div class="container_16">
    <div class="grid_16">
        <div class="intro">
        <?php echo $lang_exp_kellu_exp_didacticas; ?><br>
        <?php echo $lang_exp_kellu_acceder; ?>
        </div>
        <div class="kellu_exp_menu" >
            <ul class="exp_menu">
            <?php
            if(!is_null($semestres_menu)){
                $i=0;
                foreach ($semestres_menu as $semestre){
                $i++;
                $numero_semestre = substr($semestre["semestre"],0,1);
                if($numero_semestre == 1){
                    $nombre_semestre = $lang_exp_todas_exp_sem_uno;
                }
                elseif($numero_semestre == 2){
                    $nombre_semestre = $lang_exp_todas_exp_sem_dos;
                }
                ?>
                <li <?php if($i==1){echo "class='selected'";}?>>
                    <a class="enlace_menu" id="<?php echo substr($semestre["semestre"],0,1).$semestre["anio"];?>">
                        <?php echo $nombre_semestre.' '.$semestre["anio"];?>
                    </a>
                </li>
                <?php
                }
            }
            ?>
            </ul>
        </div>
       
    <div class="kellu_exp_contenido">
        
    </div>
        <div class="clear"></div>
    </div>
</div>

<script type="text/javascript">
    function cargarExperiencias(){
        $.get('exp_todas_experiencias.php?semestre=<?php echo substr($semestres_menu[0]["semestre"],0,1)?>&anio=<?php echo $semestres_menu[0]["anio"];?>', function(data) {                  
          $('.kellu_exp_contenido').html(data);
        });
    }

    $(document).ready(function(){
        cargarExperiencias();
        <?php
        $i=0;
            while($semestres_menu[$i]){
                ?>
                 $('#<?php echo substr($semestres_menu[$i]["semestre"],0,1).$semestres_menu[$i]["anio"];?>').click(function(){
                     url = 'exp_todas_experiencias.php?semestre=<?php echo substr($semestres_menu[$i]["semestre"],0,1)?>+&anio=<?php echo $semestres_menu[$i]["anio"];?>';
                     $.get(url, function(data) {                  
                       $('.kellu_exp_contenido').html(data);
                     });
                });
                <?php
                $i++;
            }
        ?>
                 $(".exp_menu a").click(function(){
                $(this).parent().addClass('selected'). // <li>
                siblings().removeClass('selected');
            });
    });
</script>

