<style>
    
</style>
<?php
$_funciones = array();
$file = fopen("function.fn", "r") or exit("Unable to open file!");
$i= 0;
while(!feof($file))
{
     
    $linea = fgets($file);
    $temp = explode("(",$linea);
    $_funciones[$i] = array();
    $_funciones[$i][0] = $linea;
    for($j = 0; $j <count($temp); $j++){
        $_funciones[$i][$j+1] = $temp[$j];
        $_funciones[$i][$j+1] = str_replace(");", "", $_funciones[$i][$j+1]);
        if($j+1 == 2){
            $temp2 = $_funciones[$i][2];
            $_funciones[$i][2] = array();
            $_funciones[$i][2] = explode(",",$temp2);            
        }
    }
    
    $i++;
}
fclose($file);


echo $lang_tdd_prueba_funciones.":<br><br>";
for($i=0; $i< count($_funciones); $i++) {
    echo $_funciones[$i][0]."<br>";
    echo "<b>".$_funciones[$i][1]."</b><br>";
    echo "<form id='form_".$i."' method='post' action='./respuesta.php'>";
    echo "<input type='hidden' name='input_function' class='input_text' value='".$_funciones[$i][1]."' />"."<br>";
    if(isset($_funciones[$i][2])){
        for($j=0; $j<count($_funciones[$i][2]); $j++){
            if(strpos($_funciones[$i][2][$j], "conexion") !== false){
                echo $_funciones[$i][2][$j].": <input name='input_".$j."' hidden value='conexion' />"."<br>";
            }else{
                echo $_funciones[$i][2][$j].": <input name='input_".$j."' class='input_text' value='' />"."<br>";
            }
        }
    }
    echo "<input type='submit' value='".$lang_tdd_prueba_probar."' />";
    echo "</form>"."<br><br><br>";

}





//print_r($_funciones);
?>