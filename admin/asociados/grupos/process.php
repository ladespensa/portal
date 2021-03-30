<?php
session_start();
require_once "../../config.php";
require_once "../../include/database.php";
require_once "../../include/ImageResize.php";




if(isset($_POST['data']['accion']))
{

    switch($_POST['data']['accion']){

        case "BUSCAR":
        Buscar();
        break;



       default:
       break;

    }
    
}


if(isset($_POST['accion']) && $_POST['accion'] =="AGREGAR"){

    $grupo = $_POST['grupo'];
    $descripcion = $_POST['descripcion'];
    
     
    $fecha = date("Y-m-d H:i:s");

    

    $campos = array(
        'GRUPO'=>$grupo,
        'DESCRIPCION'=>$descripcion,
         );

   $resultado = database::insertRecords("GRUPOS_CATEGORIAS",$campos);


    if($resultado){
          echo "EXITOSO";
         
    }else{
          echo "ERROR";
    }




}


if(isset($_POST['accion']) && $_POST['accion'] =="UPDATE"){


    $id = $_POST['id'];
    $grupo = $_POST['grupo'];
    $descripcion = $_POST['descripcion'];
     
    $fecha = date("Y-m-d H:i:s");

    $campos = array(
        'GRUPO'=>$grupo,
        'DESCRIPCION'=>$descripcion,
         );

    $condition = "PK=".$id;

$resultado = database::updateRecords("GRUPOS_CATEGORIAS",$campos,$condition);

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
    $resultado = database::deleteRecords("GRUPOS_CATEGORIAS",$condition);

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

         $sql = "SELECT * FROM GRUPOS_CATEGORIAS";

    }else{
    
        $word = $_POST['data']['search'];
   
        $sql = "SELECT * FROM GRUPOS_CATEGORIAS WHERE GRUPO LIKE '%".$word."%'";
    
    }
    echo $sql;
     
    $rows = database::getRows($sql);

    

    $i=1;
    $table = "";

    foreach($rows as $row){
    
            $table .= '
                <tr>
                  <td>'.$i++.'</td>
                  <td><a href="edit.php?id='.$row['PK'].'">'.utf8_encode($row['GRUPO']).'</a></td>
                  <td>'.utf8_encode($row['DESCRIPCION']).'</td>
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
