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


if(isset($_POST['accion']) && $_POST['accion'] =="UPDATE"){

  try{

       //NO TIENE ARCHIVO PARA MODIFICAR     
     $id = $_POST['id'];
     $descuento = $_POST['descuento'];
     $usos = $_POST['usos'];
     $inicio = $porciones = explode("/",$_POST['date']);
     $termino = $porciones = explode("/",$_POST['date2']);

     $f_inicio = $inicio[2]."/".$inicio[1]."/".$inicio[0]." 00:00:00"; 
     $f_termino = $termino[2]."/".$termino[1]."/".$termino[0]." 00:00:00";


     $fecha = date("Y-m-d H:i:s");
     

     $campos = array(
      'POCENTAJE_DESCUENTO'=>$descuento,
      'MAXIMO_USOS'=>$usos,
      'FECHA_INICIO'=>$f_inicio,
      'FECHA_TERMINO'=>$f_termino,
      'FECHA_M'=>$fecha,
      );

    $condition = "PK=".$id;

    $resultado = database::updateRecords("CODIGOS_DESCUENTO",$campos,$condition);

     if($resultado){
          
        echo "EXITOSO";
          
     }else{
           echo "ERROR";
     }


      

  }catch(\Throwable $t) {

 
      echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
  
  }

}


if(isset($_POST['accion']) && $_POST['accion'] =="AGREGAR"){

  $codigo = $_POST['folio'];
  $descuento = $_POST['descuento'];
  $usos = $_POST['usos'];
  $inicio = $porciones = explode("/",$_POST['date']);
  $termino = $porciones = explode("/",$_POST['date2']);

  $f_inicio = $inicio[2]."/".$inicio[1]."/".$inicio[0]." 00:00:00"; 
  $f_termino = $termino[2]."/".$termino[1]."/".$termino[0]." 00:00:00";


  $fecha = date("Y-m-d H:i:s");


  $campos = array(
        'CODIGO'=>$codigo,
        'POCENTAJE_DESCUENTO'=>$descuento,
        'MAXIMO_USOS'=>$usos,
        'FECHA_INICIO'=>$f_inicio,
        'FECHA_TERMINO'=>$f_termino,
        'FECHA_C'=>$fecha,
        );

    $resultado = database::insertRecords("CODIGOS_DESCUENTO",$campos);


    if($resultado){
          echo "EXITOSO";
        
    }else{
          echo "ERROR";
    }


}



function Buscar(){


  try{
    $fecha = $_POST['data']['fecha'];  
    
    if($_POST['data']['search']==""){
    
    $sql = "SELECT * FROM CODIGOS_DESCUENTO ORDER BY FECHA_C DESC";

    }else{
    $word = $_POST['data']['search'];
   
    $sql = "SELECT * FROM CODIGOS_DESCUENTO WHERE CODIGO LIKE '%".$word."%' ORDER BY FECHA_C DESC";

    }

  
    $rows = database::getRows($sql);

    $i=1;


    $table="";

    $fecha = date("d-m-Y");

    foreach($rows as $row){
          
        
           if(strtotime($fecha) > strtotime($row['FECHA_TERMINO']->format('d-m-Y'))){
            $estatus = '<span class="badge badge-pill badge-danger"> TERMINADO </span>';
           }else{
            $estatus = '<span class="badge badge-pill badge-primary"> ACTIVO </span>';

           }
         

            $table .='
                <tr>
                  <td>'.$i++.'</td>
                  <td><a href="edit.php?id='.$row['PK'].'"><b>'.$row['CODIGO'].'</b></a></td>
                  <td><small>'.$row['POCENTAJE_DESCUENTO'].'%</small></td>
                  <td>'.$row['MAXIMO_USOS'].'</td>
                  <td><small>'.$row['FECHA_INICIO']->format('d-m-Y').'</small></td>
                  <td><small>'.$row['FECHA_TERMINO']->format('d-m-Y').'</small></td>
                  <td>'.$estatus.'</td>
                  </tr>'; 
 
    }

    if($table==""){ $table='<tr><td colspan="13" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }
    echo $table;

  }catch(\Throwable $t) {

   
    echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}

}



function Eliminar(){

  try{

  $id = $_POST['data']['id'];
  $condition = "PK=".$id;
  $resultado = database::deleteRecords("CODIGOS_DESCUENTO",$condition);

  if($resultado){
      echo "EXITOSO";
      
  }else{
      echo "ERROR";
  }
  
}catch(\Throwable $t) {

 
  echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}
}






?>