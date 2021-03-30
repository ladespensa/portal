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

        case "ELIMINAR":
            Eliminar();
        break;

       default:
       break;

    }
    
}





if(isset($_POST['accion']) && $_POST['accion'] =="AGREGAR"){

    $zona = $_POST['zona'];
    $version = $_POST['version'];
    $coordenadas = trim($_POST['coordenadas']);

    $coordenadasAr = explode("\n", $coordenadas);
    $coordenadasAr = array_filter($coordenadasAr, 'trim');

  
    $fecha = date("Y-m-d H:i:s");

    

    $campos = array(
        'NOMBRE'=>$zona,
        'POLIGONO_VERSION'=>$version,
         );

   $ID = database::insertRecordsbyID("POLIGONO",$campos);


   foreach ($coordenadasAr as $line) {
    // processing here. 
    $lineAr =  explode(",", $line);

    $campos = array(
        'PK_POLIGONO'=>$ID,
        'LATITUD'=>trim($lineAr[1]),
        'LONGITUD'=>trim($lineAr[0]),
         );

   $resultado = database::insertRecordsbyID("POLIGONO_COORDENADAS",$campos);
       
    } 
   
  
    if($resultado){
          echo "EXITOSO";
         
    }else{
          echo "ERROR";
    }




}


if(isset($_POST['accion']) && $_POST['accion'] =="UPDATE"){


    $id = $_POST['id'];
    $zona = $_POST['zona'];
    $version = $_POST['version'];
    $coordenadas = trim($_POST['coordenadas']);

    $coordenadasAr = explode("\n", $coordenadas);
    $coordenadasAr = array_filter($coordenadasAr, 'trim');

  
    $fecha = date("Y-m-d H:i:s");

    $campos = array(
        'NOMBRE'=>$zona,
        'POLIGONO_VERSION'=>$version,
         );

    $condition = "PK=".$id;

$resultado = database::updateRecords("POLIGONO",$campos,$condition);


$condition = "PK_POLIGONO=".$id;
$resultado = database::deleteRecords("POLIGONO_COORDENADAS",$condition);


if($resultado){


    foreach ($coordenadasAr as $line) {
        // processing here. 
        $lineAr =  explode(",", $line);
    
        $campos = array(
            'PK_POLIGONO'=>$id,
            'LATITUD'=>trim($lineAr[1]),
            'LONGITUD'=>trim($lineAr[0]),
             );
    
       $resultado = database::insertRecordsbyID("POLIGONO_COORDENADAS",$campos);
            }



}





if($resultado){
    echo "EXITOSO";
   
}else{
    echo "ERROR";
}


}


function Eliminar(){

    try{

    $id = $_POST['data']['id'];
    $condition = "PK=".$id;
    $resultado = database::deleteRecords("POLIGONO",$condition);

    $condition = "PK_POLIGONO=".$id;
    $resultado = database::deleteRecords("POLIGONO_COORDENADAS",$condition);

    if($resultado){
        echo "EXITOSO";
        
    }else{
        echo "ERROR";
    }
    }catch(\Throwable $t) {

    
        echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

    }
    
}





function Buscar(){


    try{

    
    if($_POST['data']['search']==""){

         $sql = "SELECT * FROM POLIGONO";

    }else{
    
        $word = $_POST['data']['search'];
   
        $sql = "SELECT * FROM POLIGONO WHERE NOMBRE LIKE '%".$word."%'";
    
    }
    echo $sql;
     
    $rows = database::getRows($sql);

    

    $i=1;
    $table = "";

    foreach($rows as $row){
    
            $table .= '
                <tr>
                  <td>'.$i++.'</td>
                  <td><a href="edit.php?id='.$row['PK'].'">'.utf8_encode($row['NOMBRE']).'</a></td>
                  <td>'.$row['POLIGONO_VERSION'].'</td>
                  <td>'.$row['FECHA_C']->format('d/m/Y').'</td>  
                </tr>'; 
    }

        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }
        
         echo $table;

        }catch(\Throwable $t) {
   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }


}


?>
