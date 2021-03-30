<?php
session_start();
require_once "../config.php";
require_once "../include/database.php";

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

function BUSCAR1(){

    $WHERE="";
    if(isset($_POST['data']['tienda']) && strlen($_POST['data']['tienda'])>0){
      $tienda=$_POST['data']['tienda'];
      

      $sql = "SELECT PK_PRODUCTO,PRODUCTO, COUNT(PK_PRODUCTO)CANTIDAD
              FROM(
              select PD.PK_PRODUCTO,PR.PRODUCTO
              from PEDIDO_DETALLE PD
              inner join PRODUCTOS PR ON(PR.PK=PD.PK_PRODUCTO)
              inner join PEDIDOS PE ON(PE.PK=PD.PK_PEDIDO)
              WHERE PR.PROMOCION=0 AND PE.BORRADO=0 AND PE.PK_ESTATUS=5 AND PR.PK_TIENDA =  ".$tienda."
              UNION ALL
              select PRD.PK_PRODUCTO,(select PRODUCTO FROM PRODUCTOS WHERE PK=PRD.PK_PRODUCTO)PRODUCTO
              from PEDIDO_DETALLE PD
              inner join PRODUCTOS PR ON(PR.PK=PD.PK_PRODUCTO)
              inner join PEDIDOS PE ON(PE.PK=PD.PK_PEDIDO)
              inner join PROMOCION_DETALLE PRD ON(PRD.PK_PROMOCION=PR.PK)
              WHERE PR.PROMOCION=1 AND PE.BORRADO=0 AND PE.PK_ESTATUS=5 AND PR.PK_TIENDA = ".$tienda." 
              )AS TBL1
              GROUP BY PK_PRODUCTO,PRODUCTO
              order by CANTIDAD desc
              ";

       $rows = database::getRows($sql);

       $table ="";
       $i=1;

       foreach($rows as $row){

          $table .='
          <tr>
            <td><small>'.$i.'</small></td>
            <td><small>'.utf8_encode($row['PRODUCTO']).'</small></td>
            <td><small>'.utf8_encode($row['CANTIDAD']).'</small></td>
          </tr>'; 

            $i++;

        }
        
        echo $table;
      }

  }

  function BUSCAR(){
    
    if(isset($_POST['data']['tienda']) && strlen($_POST['data']['tienda'])>0){
      $tienda=$_POST['data']['tienda'];
  
      $sql = "SELECT EN.*,CONCAT(CL.NOMBRE,' ',CL.APELLIDOS)as NOMBRE,PE.TOTAL
              from ENCUESTA EN
              inner join CLIENTES CL ON(CL.PK=EN.id_usuario)
              inner join PEDIDOS PE ON(PE.PK = EN.id_pedido and PE.PK_TIENDA=88)
              where PE.PK_TIENDA=88";

       $rows = database::getRows($sql);

       $table ="";
       $i=1;

       foreach($rows as $row){

          $table .='
          <tr>
            <td><small>'.$i.'</small></td>
            <td><small>'.utf8_encode($row['correctamente']).'</small></td>
            <td><small>'.utf8_encode($row['calidad']).'</small></td>
            <td><small>'.utf8_encode($row['costos']).'</small></td>
            <td><small>'.utf8_encode($row['volversr']).'</small></td>
            <td><small>'.utf8_encode($row['cada']).'</small></td>
            <td><small>'.utf8_encode($row['observaciones']).'</small></td>
            <td><small>'.utf8_encode($row['NOMBRE']).'</small></td>
            <td><small>$'.utf8_encode($row['TOTAL']).'</small></td>
          </tr>'; 

            $i++;

        }
        
        echo $table;
      }

  }

?>