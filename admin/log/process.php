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

      default:
       break;

    }
    
}


  function BUSCAR(){

    $WHERE="";
    if(isset($_POST['data']['fecha']) && strlen($_POST['data']['fecha'])>0){
      $WHERE=" WHERE convert(varchar(10),FECHA_C,111)='".$_POST['data']['fecha']."'";
    }
    
    if(isset($_POST['data']['search']) && strlen($_POST['data']['search'])>0){
      if(strlen($WHERE)>0){
        $WHERE=$WHERE." AND ERROR LIKE '%".$_POST['data']['search']."%'";
      }else{
        $WHERE=$WHERE." WHERE ERROR LIKE '%".$_POST['data']['search']."%'";
      }
    }


    $sql = "SELECT * FROM LOG ".$WHERE." ORDER BY FECHA_C DESC";

     $rows = database::getRows($sql);

     $table ="";
     $i=1;

     foreach($rows as $row){

        $table .='
        <tr>
          <td><small>'.$i.'</small></td>
          <td><small>'.utf8_encode($row['ERROR']).'</small></td>
          <td><small>'.utf8_encode($row['DETALLE']).'</small></td>
          <td><small>'.$row['FECHA_C']->format('d/m/Y').'</small></td>
        </tr>'; 

          $i++;

      }
      
      echo $table;

  }

?>