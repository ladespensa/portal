<?php
session_start();
require_once "../config.php";
require_once "../include/database.php";
require_once "../include/ImageResize.php";



if(isset($_POST['data']['accion']))
{

    switch($_POST['data']['accion']){

       case "BUSCAR":
        Buscar();
        break;

        case "SENDPUSH":
            SendPush();
        break;


        case "SENDSMS":
            sendSMS();
        break;

        
        case "SENDSMSCODE":
            sendSMSCODE();
        break;

        

       default:
       break;

    }
    
}


function sendSMSCODE(){

    $telefonos = $_POST['data']['telefonos'];

    foreach($telefonos as $telefono){

        $sql = "SELECT CODIGO FROM CLIENTES WHERE TELEFONO = '".$telefono."' ";
        $row = database::getRow($sql);  

        if($row){

        $url = 'https://rest.nexmo.com/sms/json?'.http_build_query([
            'api_key' => '87f2ddc7',
            'api_secret' => '2NgzFye0S9nX9Vif',
            'to' => '+52'.$telefono,
            'from' => 'ACMARKET',
            'text' => 'ACMARKET Tu codigo de Verificacion es: '.$row['CODIGO']
        ]);
        
        
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        
        $result = curl_exec($ch);
        curl_close ($ch);

        }
    
    
    }


}


function sendSMS(){

    $titulo = $_POST['data']['titulo'];
    $mensaje = $_POST['data']['mensaje'];
    $telefonos = $_POST['data']['telefonos'];

    foreach($telefonos as $telefono){

        $url = 'https://rest.nexmo.com/sms/json?'.http_build_query([
            'api_key' => '87f2ddc7',
            'api_secret' => '2NgzFye0S9nX9Vif',
            'to' => '+52'.$telefono,
            'from' => 'ACMARKET '.$titulo,
            'text' => $mensaje
        ]);
        
        
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        
        $result = curl_exec($ch);
        curl_close ($ch);
    
    
    }

    


}



function SendPush(){

   $titulo = $_POST['data']['titulo'];
   $mensaje = $_POST['data']['mensaje'];

   $sql = "SELECT TOKEN FROM CLIENTES WHERE TOKEN IS NOT NULL";
   $rows = database::getRows($sql);
   
   foreach($rows as $row){
   
    $token = $row['TOKEN'];

    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array (
          'to' => $token,
          'notification' => array (
                  "body" => $mensaje,
                  "title" => $titulo,
                  "icon" => "myicon",
                  "sound" => "mySound",
          )
  );
  $fields = json_encode ( $fields );
  $headers = array (
          'Authorization: key='.KEY_FIREBASE,
          'Content-Type: application/json'
  );
  
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_POST, true );
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
  
  $result = curl_exec ($ch);
  curl_close ($ch);

   }


}

function Buscar(){

    try {

    $plataforma = $_POST['data']['plataforma'];

    if($plataforma!="ALL"){

        $condicion = " AND PLATAFORMA ='".$plataforma."'";
    }else{
        $condicion = "";
    }

    if($_POST['data']['search']==""){
        $sql = "select PK,TELEFONO, CONCAT(NOMBRE,'',APELLIDOS) AS NOMBRE, FECHA_NACIMIENTO, GENERO, CORREO,CODIGO,FECHA_C, PLATAFORMA from CLIENTES WHERE TELEFONO IS NOT NULL ".$condicion." ORDER BY FECHA_C";
        }else{
        $word = $_POST['data']['search'];
        $sql = "select PK,TELEFONO, CONCAT(NOMBRE,'',APELLIDOS) AS NOMBRE, FECHA_NACIMIENTO, GENERO, CORREO,CODIGO,FECHA_C, PLATAFORMA from CLIENTES WHERE (NOMBRE LIKE '%".$word."%' OR TELEFONO LIKE '%".$word."%') ".$condicion;    
    }


    $rows = database::getRows($sql);

    $i=1;

    $table = "";

    

    foreach($rows as $row){


    $fecha_nacimiento = $row['FECHA_NACIMIENTO'];

    if($fecha_nacimiento==NULL){
        $fecha_nacimiento ="";
    }else{
        $fecha_nacimiento = $row['FECHA_NACIMIENTO']->format('d-m-Y');
    }
          
        $table.= '
                <tr>
                <td><input type="checkbox" name="cliente" value="'.$row['TELEFONO'].'"></td>
                  <td>'.$i++.'</td>
                  <td>'.$row['TELEFONO'].'</td>
                  <td><a href="detalle.php?id='.$row['PK'].'" target="_blank">'.utf8_encode($row['NOMBRE']).'</a></td>
                  <td>'.$row['CORREO'].'</td>
                  <td>'.$row['GENERO'].'</td>
                  <td>'.$row['CODIGO'].'</td>
                  <td>'.$fecha_nacimiento.'</td>
                  <td>'.$row['PLATAFORMA'].'</td>
                  <td>'.$row['FECHA_C']->format('d-m-Y').'</td>
                </tr>'; 
     
    }

    echo $table;

}catch(\Throwable $t) {

   
    echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}

}

?>