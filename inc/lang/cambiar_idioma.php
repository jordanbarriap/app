<?php
    session_start();
    $idioma=$_REQUEST["idioma"];
    $_SESSION["idioma"]=$idioma;
    
    //Cambio en el valor de la cookie de idioma de kelluwen, al escoger otro idioma desde la plataforma
    //Verifica si las cookies están activadas
    if(count($_COOKIE) > 0) { //crea la cookie
        $tiempo_expiracion=time()+60*60*24*30; // tiempo de duración de la cookie de idioma = 1 mes
        setcookie("idioma_kelluwen", $idioma, $tiempo_expiracion, "/");
    }
    else{
        echo $idioma;    
    }  
    
?>