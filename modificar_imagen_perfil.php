<?php
//Si la forma de acceder al script es mediante el navegador web entonces redirige a la pagina principal
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' && !isset($_SERVER['HTTP_REFERER']))header("Location:ingresar.php");

$ruta_raiz = "./";
require_once($ruta_raiz."conf/config.php");
require_once($ruta_raiz."inc/all.inc.php");
require_once($ruta_raiz."inc/verificar_sesion.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html >
    <head>
        <meta http-equiv="Content-Type" content="text/html;<?php echo $config_charset; ?>">
        <meta name="author" content="Daniel Guerra" />
        <meta name="description" lang="es" content="<?php echo $descripcion_pagina; ?>" />
        <link href="<?php echo $config_ruta_img; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/reset.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/text.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/960.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/default.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery-ui-1.7.2.custom.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/ui.spinner.css" />
        <link rel="stylesheet" href="<?php echo $ruta_raiz; ?>css/jquery/jquery.autocomplete.css" />
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.validate.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.imgareaselect-0.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/info.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="<?php echo $ruta_raiz; ?>js/ui.spinner.js"></script>
    </head>
    <body id="body_modificar_imagen">
<?php
$large_image_name  = $_SESSION["klwn_usuario"]."_original.jpg";
$thumb_image_name  = $_SESSION["klwn_usuario"]."_grande.jpg";
$normal_image_name = $_SESSION["klwn_usuario"]."_normal.jpg";
$id_sesion         = $_SESSION["id_sesion"];//Código agregado por Jordan el 28-10-14
//Image Locations
$large_image_location = $upload_path.$large_image_name; //ruta donde se encuentra la imagen grande
$thumb_image_location = $upload_path.$thumb_image_name; //ruta donde se encuentra la imagen pequeña
$normal_image_location = $upload_path.$normal_image_name;
//crear el directorio para guardar las imagenes con los permisos correctos
if(!is_dir($upload_dir)){
	mkdir($upload_dir, 0777);
	chmod($upload_dir, 0777);
}
//Ver si es que existen imagenes con el mismo nombre
//las imagenes deben sobreescribirse
//fase 3
if (file_exists($large_image_location)){
	if(file_exists($thumb_image_location)){
		$thumb_photo_exists = "<img src=\"".$upload_path.$thumb_image_name."\" alt=\"<".$lang_modificar_imagen_perfil_alt_min."\"/>"; //identifica si es que existe la imagen pequeña
	}else{
		$thumb_photo_exists = "";
	}
   	$large_photo_exists = "<img src=\"".$upload_path.$large_image_name."\" alt=\"".$lang_modificar_imagen_perfil_alt_original."\"/>"; //identifica si es que existe la imagen grande
} else {
   	$large_photo_exists = ""; // no hay imagen grande
	$thumb_photo_exists = ""; // no hay imagen pequeña
}
//******se llega aqui despues de subir la imagen grande, se ve que corresponda el formato y se sube*****

if (isset($_POST["upload"])) {
	//Obtener la informacion del archivo
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$filename = basename($_FILES['image']['name']);
	$file_ext = substr($filename, strrpos($filename, '.') + 1);
        
	//Verifica que el formato de la imagen sea jpeg y que pese menos de 1mb
	if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
            //Ver que no sea de  mas de 
            $width = getWidth($userfile_tmp);
            $height = getHeight($userfile_tmp);
            $file_ext = strtolower($file_ext);
            if ($file_ext!="jpg")  {
                $error= $lang_modificar_imagen_perfil_error1; // pasar a spanish.php
            }
            if($userfile_size > $max_file){
                $error= $lang_modificar_imagen_perfil_error2;
            }

            if(($max_ancho_subir<$width) || ($max_alto_subir<$height)){
                $error = $lang_modif_imagen_perfil_imagen_grande;
            }

        }
	//Todo ok, asi que se puede subir la imagen
	if (strlen($error)==0){

		if (isset($_FILES['image']['name'])){
			move_uploaded_file($userfile_tmp, $large_image_location);
			chmod($large_image_location, 0777);

			$width = getWidth($large_image_location);
			$height = getHeight($large_image_location);
			//Escalar la imagen si es que esta es muy grande
			if ($width > $max_width){
				$scale = $max_width/$width;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}else{
				$scale = 1;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}
			//borrar la imagen pequeña para que el usuario pueda crear una nueva
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
		}
		//Refresh the page to show the new uploaded image
                ?>
                <script type="text/javascript">
                    window.location.replace("modificar_imagen_perfil.php");
                </script>
                <?php
		exit();
	}
}
// ******Caso en el que se va a subir la imagen pequeña ******
//Fase 3
if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
	//Cuando se sube la imagen con el tamaño final
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	//Escalar la imagen que se quiere subir segun los valores ingresados
	$scale = $thumb_width/$w;
        $scale_normal = $normal_width/$w;
        $normal_cropped = resizeThumbnailImage($normal_image_location, $large_image_location,$w,$h,$x1,$y1,$scale_normal);
	$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
        $conexion = dbConectarMySQL($config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd);
        // Guardar el nombre de la imagen en la BD
        $sube_imagen_bd = dbActualizaDatosUsuario($_SESSION["klwn_id_usuario"], null, null, null, null, null, null, null,$normal_image_name,null,null,$conexion);
        $_SESSION["klwn_foto"]= $normal_image_name;
        dbLogActualizarPerfil($id_sesion,2,$conexion);//Código agregado por Jordan Barría el 31-10-14
        dbDesconectarMySQL($conexion);
	//Reload the page again to view the thumbnail
	?>
                <script type="text/javascript">
                    window.location.replace("modificar_imagen_perfil.php");
                </script>
                <?php
	exit();
}
//***** para borrar ambas imagenes ******
if ($_GET['a']=="delete"){
	if (file_exists($large_image_location)) {
		unlink($large_image_location);
	}
	if (file_exists($thumb_image_location)) {
		unlink($thumb_image_location);
	}
        if (file_exists($normal_image_location)) {
		unlink($normal_image_location);
	}
	?>
            <script type="text/javascript">
                window.location.replace("modificar_imagen_perfil.php");
            </script>
        <?php
	exit();
}
if(strlen($large_photo_exists)>0){
	$current_large_image_width = getWidth($large_image_location);
	$current_large_image_height = getHeight($large_image_location);?>
<script type="text/javascript">
function preview(img, selection) {
	var scaleX = '<?php echo $thumb_width;?>' / selection.width;
	var scaleY = '<?php echo $thumb_height;?>' / selection.height;

	$('#thumbnail + div > img').css({
		width: Math.round(scaleX * <?php echo $current_large_image_width;?>) + 'px',
		height: Math.round(scaleY * <?php echo $current_large_image_height;?>) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
}

$(document).ready(function () {
    $('#save_thumb').click(function() {
                    var x1 = $('#x1').val();
                    var y1 = $('#y1').val();
                    var x2 = $('#x2').val();
                    var y2 = $('#y2').val();
                    var w = $('#w').val();
                    var h = $('#h').val();
                    if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
                            alert("<?php echo $lang_modif_imagen_perfil_cursor; ?>");
                            return false;
                    }else{
                            return true;
                    }
    });
});

$(window).load(function () {
	$('#thumbnail').imgAreaSelect({ aspectRatio: '1:1', onSelectChange: preview });
});

</script>
<?php }?>        
            <div id="modificar_imagen_perfil">
                    <?php
                    if(strlen($error)>0){
                            echo "<div class=\"caja_error_imagen\"><strong>".$error."</strong></div>";
                    }
                    if(strlen($thumb_photo_exists)>0){ //imagen de 70x70
                            if (file_exists($large_image_location)) {
                                unlink($large_image_location);
                            }
//                            echo "Su imagen de modificó de manera exitosa";
//                            echo $thumb_photo_exists;
                            ?>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $.post('inicio_datos_usuario.php', function(data) {
                                        $(parent.document).find('#inicio_info_usuario').html(data);
                                        location.reload(); //recarga el iframe

                                    });
                                });
                            </script>
                            <?php
                    }
                    else{
                        if(strlen($large_photo_exists)>0){?>
                        <div class="intro_modificar_perfil"><?php echo $lang_modificar_imagen_perfil_area;?></div>
                        <p id="modifica_perfil_original"><b><?php echo $lang_modificar_imagen_perfil_original.": ";?></b></p>
                        <p id="modifica_perfil_miniatura"><b><?php echo $lang_modificar_imagen_perfil_perfil.": ";?></b></p>
                        <br/>
                        <br/>
                                <img  src="<?php echo $upload_path.$large_image_name;?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="<?php echo $lang_modif_imagen_perfil_area; ?>" />
                                <div style="float:right; position:relative; border: 1px solid #CDCDBF; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
                                    <img src="<?php echo $upload_path.$large_image_name;?>" style="position: relative;" alt="<?php echo $lang_modificar_imagen_perfil_miniatura_perfil;?>" title="<?php echo $lang_modificar_imagen_perfil_miniatura_perfil;?>" />
                                </div>
                                <br style="clear:both;"/>
                                <form id="form_miniatura_perfil"name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                                        <input type="hidden" name="x1" value="" id="x1" />
                                        <input type="hidden" name="y1" value="" id="y1" />
                                        <input type="hidden" name="x2" value="" id="x2" />
                                        <input type="hidden" name="y2" value="" id="y2" />
                                        <input type="hidden" name="w" value="" id="w" />
                                        <input type="hidden" name="h" value="" id="h" />
                                        <input class="submit" type="submit" name="upload_thumbnail" value="<?php echo $lang_modificar_imagen_perfil_guardar_img;?>" id="save_thumb" />
                                </form>

                            <hr />
                            <?php   } ?>
                            <div class="intro_modificar_perfil"><?php echo $lang_modificar_imagen_perfil_intro;?></div>
                            <form id="form_subir_foto" name="photo" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                            <input type="file" name="image" size="30" />
                            <div class="clear"></div>
                            <input class="submit" type="submit" name="upload" value="<?php echo $lang_modificar_imagen_perfil_subir;?>" />
                            <div class="clear"></div>
                            </form>
                    <?php } ?>
                </div>
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('.intro_modificar_perfil').show();
    });

</script>
