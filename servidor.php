#!/php -q

<?php  /*  >php -q server.php  */





set_time_limit(0);

ob_implicit_flush();









require_once("conf/config.php");

require_once("inc/db_functions.inc.php");

require_once("db_functions_websocket.php");





$master    = WebSocket("0.0.0.0",9000);

$sockets   = array($master);

$users     = array();

$users_exp = array();

$debug     = true;





while(true){

  $changed = $sockets;

  $write=NULL;

  $except=NULL;

  socket_select($changed,$write,$except,NULL);

  foreach($changed as $socket){

    if($socket==$master){

      $client=socket_accept($master);

      if($client<0){ console("socket_accept() failed"); continue; }

      else{ connect($client); }

    }

    else{

      $bytes = @socket_recv($socket,$buffer,2048,0);

      if($bytes==0){ disconnect($socket); }

      else{

        $user = getuserbysocket($socket);

        if(!$user->handshake){ dohandshake($user,$buffer); }

        else{ 

          process($user,$buffer); }

      }

    }

  }

}





function process($user,$mensaje){

  global $users,$users_exp;

  $string_instruccion = unwrap($mensaje);

  $_data_instruccion=explode(" ",$string_instruccion);

  $accion=$_data_instruccion[0];

  say("< ".$accion);

  switch($accion){

    case "Bitacora" ://Caso en que el usuario avisa que abrió la Bitácora de la experiencia "x"

                    //->Mensaje enviado por el cliente: "Bitácora x"

        $experiencia=$_data_instruccion[1];

        $ultima_experiencia=$user->experiencia_activa;

        if ($ultima_experiencia){//Si el usuario ya tenia una bitácora activa asociada

          if ($ultima_experiencia!=$experiencia){//y diferente a la experiencia entrante, eliminarlo del arreglo respectivo

            say("Experiencia asociada al cliente distinta de la entrante");

            $_array_exp_anterior=$users_exp[$ultima_experiencia];//Obtiene el arreglo de usuarios que pertenecían a la experiencia anterior de aquel usuario que envió el mensaje

            if (($indice=array_search($user->id,$_array_exp_anterior)) !== false){

              unset($_array_exp_anterior[$indice]);//Borra el id del socket del cliente del arreglo de clientes de la experiencia anterior

              $users_exp[$ultima_experiencia]=array_values($_array_exp_anterior);//Normaliza los indices numericos asociados al arreglo de clientes, 

                                                                                    //para mantener el orden correlativo despues de haber borrado el elemento

              say("Cliente borrado de la experiencia ".$ultima_experiencia);

            }

          }else{//En caso de que la nueva experiencia informada por el cliente sea la misma a la anterior, no debe cambiar nada

            say("El usuario ya estaba asociado a la experiencia ".$ultima_experiencia);

            break;

          }    

        }

        if (!array_key_exists($experiencia,$users_exp)){//En caso de que sea el primer cliente que ingresa a la bitácora de una cierta experiencia

          say("Primer cliente asociado a la experiencia id ".$experiencia);

          $users_exp[$experiencia]=array();//Crea un arreglo de clientes para dicha experiencia

        }

        array_push($users_exp[$experiencia], $user->id);//Agrega el id del socket del cliente al arreglo de clientes de la experiencia

        $user->experiencia_activa=$experiencia;//Asocia la nueva experiencia como experiencia activa

        $users[$user->id]=$user;

        break;

    case "Actividad" :

        $experiencia=$_data_instruccion[1];

        //$id_usuario=$_data_instruccion[2];

        $id_sesion=$_data_instruccion[2];//Código agregado por Jordan Barría el 13-12-14

        $tipo_bitacora=$_data_instruccion[3];

        //multiCastMsjeActividadBitacora("Actividad ".$id_usuario." ".$tipo_bitacora." ".$experiencia,$experiencia,$tipo_bitacora);

        multiCastMsjeActividadBitacora("Actividad ".$id_sesion." ".$tipo_bitacora." ".$experiencia,$experiencia,$tipo_bitacora);//Código agregado por Jordan Barría el 13-12-14

        break;

    default        : break;

  }

}



function send($client,$mensaje){

  say("> ".$mensaje);

  $mensaje = wrap($mensaje);

  $sent = socket_write($client, $mensaje);

}



function broadcastMensaje($mensaje){



  say("> ".$mensaje);

  $mensaje = wrap($mensaje);



  global $sockets;

  foreach($sockets as $socket_cliente){

    @socket_write($socket_cliente, $mensaje , strlen($mensaje));

  }

  return true;

}



function multiCastMsjeActividadBitacora($mensaje,$id_experiencia,$tipo_bitacora){

  global $users,$users_exp,$config_host_bd,$config_usuario_bd,$config_password_bd,$config_bd;



  say("> ".$mensaje);

  $mensaje = wrap($mensaje);

  

  /*if ($tipo_bitacora=="Clase"){//Caso que haya sido una actividad en la Bitácora de Mi Clase

    say("Experiencia Mi Clase: ".$id_experiencia);

    $_clientes_experiencia=$users_exp[$id_experiencia];

    $i=0;

    foreach($_clientes_experiencia as $cliente){

      $socket_cliente=$users[$cliente]->socket;

      @socket_write($socket_cliente, $mensaje , strlen($mensaje));

      $i++;

    }

    say("Nro clientes: ".$i);

  }else{//Caso de que haya sido una actividad en la Bitácora Compartida*/

    //Se obtiene las experiencias gemelas correspondientes a la experiencia asociada a un mensaje

    $conexion=dbConectarMySQL($config_host_bd, $config_usuario_bd, $config_password_bd, $config_bd);

    $_arreglo_exp_gemelas=obtenerArrayExpGemelas($id_experiencia,$conexion);

    dbDesconectarMySQL($conexion);

    say("> Nro exp gemelas".sizeof($_arreglo_exp_gemelas));

    foreach($_arreglo_exp_gemelas as $exp_gemela){

      say("Experiencia Gemela: ".$exp_gemela);

      $_clientes_experiencia=$users_exp[$exp_gemela];

      $i=0;

      foreach($_clientes_experiencia as $cliente){

        $socket_cliente=$users[$cliente]->socket;

        @socket_write($socket_cliente, $mensaje , strlen($mensaje));

        $i++;

      }

      say("Nro clientes: ".$i);

    }

  //}

  return true;

}



function WebSocket($address,$port){

  $master=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)     or die("socket_create() failed");

  socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1)  or die("socket_option() failed");

  socket_bind($master, $address, $port)                    or die("socket_bind() failed");

  socket_listen($master,20)                                or die("socket_listen() failed");

  echo "Server Started : ".date('Y-m-d H:i:s')."\n";

  echo "Master socket  : ".$master."\n";

  echo "Listening on   : ".$address." port ".$port."\n\n";

  return $master;

}



function connect($socket){

  global $sockets,$users;

  $user = new User();

  $user->id = uniqid();

  $user->socket = $socket;

  //array_push($users,$user);

  $users[$user->id]=$user;

  array_push($sockets,$socket);

  console($socket." CONNECTED!");

}



function disconnect($socket){

  global $sockets,$users,$users_exp;

  $found=null;

  //$n=count($users);

  //for($i=0;$i<$n;$i++){

  foreach($users as $id=>$user){

    //if($users[$i]->socket==$socket){ $found=$i; break; }

    if($user->socket==$socket){ $found=$id; break; }

  }

  //if(!is_null($found)){ array_splice($users,$found,1); }

  if(!is_null($found)){

    $active_user=$users[$found];

    $id_experiencia_activa=$active_user->id_experiencia_activa;

    unset($users[$found]);

    if ($id_experiencia_activa){

      $_array_exp=$users_exp[$id_experiencia_activa];//Obtiene el arreglo de usuarios que pertenecían a la experiencia anterior de aquel usuario que envió el mensaje

        if (($indice=array_search($active_user->id,$_array_exp)) !== false){

          unset($_array_exp[$indice]);//Borra el id del socket del cliente del arreglo de clientes de la experiencia anterior

          $users_exp[$id_experiencia_activa]=array_values($_array_exp);//Normaliza los indices numericos asociados al arreglo de clientes, 

          say("Cliente eliminado de la experiencia ".$id_experiencia_activa);

        } 

    } 

  }

  $index = array_search($socket,$sockets);

  socket_close($socket);

  console($socket." DISCONNECTED!");

  if($index>=0){ array_splice($sockets,$index,1); }

}



function dohandshake($user,$buffer){

  console("\nRequesting handshake...");

  console($buffer);

  list($resource,$host,$origin,$key) = getheaders($buffer);

  console("Handshaking...");



$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .

            "Upgrade: WebSocket\r\n" .

            "Connection: Upgrade\r\n" .

            "Sec-WebSocket-Accept: ".base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B11",true))."\r\n".

            "\r\n";

  socket_write($user->socket,$upgrade);



  $user->handshake=true;

  console($upgrade);

  console("Done handshaking...");

  return true;

}



function getheaders($req){

  $r=$h=$o=null;

  if(preg_match("/GET (.*) HTTP/"   ,$req,$match)){ $r=$match[1]; }

  if(preg_match("/Host: (.*)\r\n/"  ,$req,$match)){ $h=$match[1]; }

  if(preg_match("/Origin: (.*)\r\n/",$req,$match)){ $o=$match[1]; }

  if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/",$req,$match)){ $key1=$match[1]; }

  //if(preg_match("/\r\n(.*?)\$/",$req,$match)){ $data=$match[1]; }

  return array($r,$h,$o,$key1);

}



function getuserbysocket($socket){

  global $users;

  $found=null;

  //foreach($users as $user){

  foreach($users as $id=>$user){

    if($user->socket==$socket){ $found=$user; break; }

  }

  return $found;

}



function     say($mensaje=""){ echo $mensaje."\n"; }

function    wrap($mensaje=""){

$length=strlen($mensaje);

$header=chr(0x81).chr($length);

$mensaje=$header.$mensaje;

return $mensaje;

}

function  unwrap($mensaje=""){

{

$firstMask=     bindec("10000000");

$secondMask=    bindec("01000000");//im not doing anything with the rsvs since we arent negotiating extensions...

$thirdMask=     bindec("00100000");

$fourthMask=    bindec("00010000");

$firstHalfMask= bindec("11110000");

$secondHalfMask=bindec("00001111");

$payload="";

$firstHeader=ord(($mensaje[0]));

$secondHeader=ord($mensaje[1]);

$key=Array();

$fin=(($firstHeader & $firstMask)?1:0);

$rsv1=$rsv2=$rsv3=0;

$opcode=$firstHeader & (~$firstHalfMask);//TODO: make the opcode do something. it extracts it but the program just assumes text;

$masked=(($secondHeader & $firstMask) !=0);

$length=$secondHeader & (~$firstMask);

$index=2;

if($length==126)

{

$length=ord($mensaje[$index])+ord($mensaje[$index+1]);

$index+=2;

}

if($length==127)

{

$length=ord($mensaje[$index])+ord($mensaje[$index+1])+ord($mensaje[$index+2])+ord($mensaje[$index+3])+ord($mensaje[$index+4])+ord($mensaje[$index+5])+ord($mensaje[$index+6])+ord($mensaje[$index+7]);

$index+=8;

}

if($masked)

{

for($x=0;$x<4;$x++)

{

$key[$x]=ord($mensaje[$index]);

$index++;

}

}

echo $length."\n";

for($x=0;$x<$length;$x++)

{

$mensajenum=ord($mensaje[$index]);

$keynum=$key[$x % 4];

$unmaskedKeynum=$mensajenum ^ $keynum;

$payload.=chr($unmaskedKeynum);

$index++;

}



/*if($fin!=1)

{

return $payload.processMsg(substr($mensaje,$index));

}*/

return $payload;

}

}

function console($mensaje=""){ global $debug; if($debug){ echo $mensaje."\n"; } }



class User{

  var $id;

  var $socket;

  var $handshake;

  var $experiencia_activa;

}



?>