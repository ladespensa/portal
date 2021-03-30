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

      case "BUSCARINFORME":
        BuscarInforme();
        break;

      case "BUSCARINFORMECLIENTE":
        BuscarInformeCliente();
        break;

      case "ACTUALIZARPEDIDO":
        ActualizarPedido();
      break;

      case "ACTUALIZARIMPORTE":
        ActualizarImporte();
      break;

      

      case "ASIGNARREPARTIDOR":
          AsignarRepartidor();
          break;

      case "ASIGNARESTATUS":
         AsignarEstatus();
         break;

      case "CANCELARPEDIDO":
          CancelarPedido();
          break;
        
      case "REANUDARPEDIDO":
            ReanudarPedido();
            break;

      default:
       break;

    }
    
}



function ActualizarImporte(){

  $id = $_POST['data']['id'];
  $subtotal = $_POST['data']['subtotal'];
  $envio = $_POST['data']['envio'];
  $comision_tarjeta = $_POST['data']['comision_tarjeta'];
  $total = $_POST['data']['total'];
  $metodo_pago = $_POST['data']['metodo_pago'];

  $fecha = date("Y-m-d H:i:s");

  $campos = array(
    'SUBTOTAL'=>$subtotal,
    'ENVIO'=>$envio,
    'COMISION_TARJETA'=>$comision_tarjeta,
    'TOTAL'=>$total,
    'METODO_PAGO'=>$metodo_pago,
    'FECHA_M'=>$fecha,
);

$condition = "PK=".$id;

$resultado = database::updateRecords("PEDIDOS",$campos,$condition);


if($resultado){

  echo "EXITOSO";

}else{
  echo "ERROR";
}


}


function ActualizarPedido(){


     $respuesta = "EXITOSO";
     $id = $_POST['data']['id'];
     $productos = $_POST['data']['productos'];
     $fecha = date("Y-m-d H:i:s");

     $subtotal = 0;
     $total = 0;
     $envio = 0;
     $comision_tarjeta = 0;

     $sql = 'SELECT * FROM PEDIDOS where PK='.$id;
     $row = database::getRow($sql);
     if($row){  
      $envio = $row['ENVIO'];
      $comision_tarjeta = $row['COMISION_TARJETA'];
     }



     $someArray = json_decode($productos, true);
     //print_r($someArray);
     //echo $someArray[0]["PK"];


     $condition = "PK_PEDIDO=".$id;
     $resultado = database::deleteRecords("PEDIDO_DETALLE",$condition);

     if($resultado){

          foreach ($someArray as $key => $value) {
              //echo $value['TOTAL'];

            $total += (float) $value['TOTAL'];

                      $campos = array(
                              'PK_PEDIDO'=>$id,
                              'PK_PRODUCTO'=>$value['PK_PRODUCTO'],
                              'PRECIO'=>$value['PRECIO'],
                              'CANTIDAD'=>$value['CANTIDAD'],
                              'DETALLES'=>$value['DETALLE'],
                              'FECHA_M'=>$fecha,
                      );

                    
                    $resultado = database::insertRecords("PEDIDO_DETALLE",$campos);
          }
     
     }

     $subtotal = $total;
     $total = $subtotal + $envio + $comision_tarjeta;


            $campos = array(
                  'SUBTOTAL'=>$subtotal,
                  'TOTAL'=>$total,
                  'FECHA_M'=>$fecha,
            );

            $condition = "PK=".$id;

            $resultado = database::updateRecords("PEDIDOS",$campos,$condition);

     echo $respuesta;

}

function ReanudarPedido(){

  try{

    
    $id_pedido = $_POST['data']['id_pedido'];
    
    $fecha = date("Y-m-d H:i:s");
  
    $campos = array(
            'PK_ESTATUS'=>'1',
            'BORRADO'=>'0',
            'FECHA_M'=>$fecha,
    );
  
  $condition = "PK=".$id_pedido;
  
  $resultado = database::updateRecords("PEDIDOS",$campos,$condition);
  
    if($resultado){
         
       echo "EXITOSO";
     
    }else{
          echo "ERROR";
    }
  
  }catch(\Throwable $t) {
  
   
    echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
  
  }

}



function CancelarPedido(){
  try{

    
    $id_pedido = $_POST['data']['id_pedido'];
    $id_estatus = $_POST['data']['id_estatus'];
    
    $fecha = date("Y-m-d H:i:s");
  
    $campos = array(
            'PK_ESTATUS'=>$id_estatus,
            'BORRADO'=>'1',
            'FECHA_M'=>$fecha,
    );
  
  $condition = "PK=".$id_pedido;
  
  $resultado = database::updateRecords("PEDIDOS",$campos,$condition);
  
    if($resultado){
         
       echo "EXITOSO";
       sendFCMCliente($id_estatus,$id_pedido);
     
    }else{
          echo "ERROR";
    }
  
  }catch(\Throwable $t) {
  
   
    echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
  
  }
  

}



function AsignarRepartidor(){

  //Revisar que el Repartidor NO este asignado

  try{

  $id_repartidor = $_POST['data']['id_repartidor'];
  $id_pedido = $_POST['data']['id_pedido'];
  
  $fecha = date("Y-m-d H:i:s");

  $campos = array(
          'PK_REPARTIDOR'=>$id_repartidor,
          'FECHA_M'=>$fecha,
  );

$condition = "PK=".$id_pedido;

$resultado = database::updateRecords("PEDIDOS",$campos,$condition);

  if($resultado){
       
     echo "EXITOSO";

     sendFCMRepartidor($id_repartidor);
       
  }else{
        echo "ERROR";
  }

}catch(\Throwable $t) {

   
  echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}


}



function AsignarEstatus(){

  
try{
  $id_estatus = $_POST['data']['id_estatus'];
  $id_pedido = $_POST['data']['id_pedido'];
  
  $fecha = date("Y-m-d H:i:s");

  $campos = array(
          'PK_ESTATUS'=>$id_estatus,
          'FECHA_M'=>$fecha,
  );

$condition = "PK=".$id_pedido;

$resultado = database::updateRecords("PEDIDOS",$campos,$condition);

  if($resultado){
       
     echo "EXITOSO";

     sendFCMCliente($id_estatus,$id_pedido);
       
  }else{
        echo "ERROR";
  }

}catch(\Throwable $t) {

   
  echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}

}


function sendFCMCliente($id_estatus,$id_pedido) {


  $sql = 'SELECT C.TOKEN FROM PEDIDOS P, CLIENTES C WHERE P.PK_CLIENTE = C.PK AND P.PK='.$id_pedido;
  $row = database::getRow($sql);

  if($row){

    $id = $row['TOKEN'];

    switch ($id_estatus) {
      case '2':
        $tile = "Pedido en Preparación";
        $body = "Estamos preparando tu pedido, para llevarlo a la puerta de tu casa!";
        break;

      case '3':
        $tile = "Tu repartidor esta en el Negocio";
        $body = "Tu repartidor esta esperando la entrega de tu pedido!";
        break;

      case '4':
          $tile = "Tu repartidor va en camino";
          $body = "Espera en tu direccion, tu pedido va hacia ti!";
          break;

      case '5':
        $tile = "Tu pedido ha sido entregado";
        $body = "Fue un placer servirte, de parte del equipo acmarket.";
        break;

      case '7':
        $tile = "Tu pedido ha reebolsado";
        $body = "Fue un placer servirte, no dejes de usar acmarket.";
          break;
      
      default:
        # code...
        break;
    }

  
  
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array (
          'to' => $id,
          'notification' => array (
                  "body" => $body,
                  "title" => $tile,
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
  
  $result = curl_exec ( $ch );
  curl_close ( $ch );

}
  }


  function sendFCMRepartidor($id_repartidor) {


    $sql = 'SELECT TOKEN, NOMBRE FROM REPARTIDORES WHERE PK='.$id_repartidor;
    $row = database::getRow($sql);
    
    if($row){

    $tile = utf8_encode($row['NOMBRE'])." tienes un nuevo pedido asignado";
    $body = "Ingresa en la seccion de pedidos para obtener los datos del pedido";
  
    $id = $row['TOKEN'];
  
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array (
            'to' => $id,
            'notification' => array (
                    "body" => $body,
                    "title" => $tile,
                    "icon" => "myicon",
                    "sound" => "mySound",
            )
    );
    $fields = json_encode ( $fields );
    $headers = array (
            'Authorization: key='.KEY_FIREBASE_REPARTIDOR,
            'Content-Type: application/json'
    );
    
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    
    $result = curl_exec ( $ch );
    curl_close ( $ch );
  
  }
    
}


function BuscarInformeCliente(){

  $fecha = $_POST['data']['fecha']; 

    $sql = "SELECT (SELECT NOMBRE FROM TIENDAS WHERE PK = P.PK_TIENDA) AS TIENDA, (SELECT CONCAT(NOMBRE,' ',APELLIDOS) AS CLIENTE FROM CLIENTES WHERE PK = P.PK_CLIENTE) AS CLIENTE, (SELECT PRODUCTO FROM PRODUCTOS WHERE PK = PD.PK_PRODUCTO) AS PRODUCTO,PD.PK_PRODUCTO AS PK_PRODUCTO,(SELECT PROMOCION FROM PRODUCTOS WHERE PK = PD.PK_PRODUCTO) AS PROMOCION,PD.PRECIO, PD.CANTIDAD, (PD.CANTIDAD * convert(float,PD.PRECIO)) AS TOTAL, P.FECHA_ENTREGA  
    FROM PEDIDOS P, PEDIDO_DETALLE PD
    WHERE P.PK = PD.PK_PEDIDO AND P.FECHA_ENTREGA = '".$fecha."  00:00:00.000' AND P.BORRADO = 0 AND P.PK_ESTATUS  IN (1,2) AND P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") ORDER BY CLIENTE, TIENDA";

   // echo $sql;

     $rows = database::getRows($sql);


     $total = 0;
     $cantidad = 0;
     $table ="";
     $i=1;

     foreach($rows as $row){

      $ID_PRODUCTO = $row['PK_PRODUCTO'];

      $FECHA_ENTREGA = $row['FECHA_ENTREGA']->format('d-m-Y');

      if(trim($row['PROMOCION'])==1){

        $table .='
        <tr style="background:#00D791;">
          <td style="color:white; font-size:13px;"><b>-</b></td>
          <td style="color:white; font-size:13px;"><b>'.utf8_encode($row['TIENDA']).'</b></td>
          <td style="color:white; font-size:13px;"><b>'.utf8_encode($row['CLIENTE']).'</b></td>
          <td style="color:white; font-size:13px;"><b>'.utf8_encode($row['PRODUCTO']).'</b></td>
          <td style="color:white; font-size:13px;"><b>$'.number_format(round($row['PRECIO'],2),2).'</b></td>
          <td style="color:white; font-size:13px;"><b>'.$row['CANTIDAD'].'</b></td>
          <td style="color:white; font-size:13px;"><b>$'.number_format(round($row['TOTAL'],2),2).'</b></td>
          <td style="color:white; font-size:13px;"><b>'.$row['FECHA_ENTREGA']->format('d-m-Y').'</b></td>
          </tr>'; 
  
          $total += $row['TOTAL'];
          
          $sql3 = "select (SELECT NOMBRE FROM TIENDAS WHERE PK=P.PK_TIENDA) AS TIENDA,P.PRODUCTO,P.PK_TIENDA,PD.PK_PRODUCTO,PD.PRECIO,PD.CANTIDAD from PROMOCION_DETALLE PD, PRODUCTOS P WHERE PD.PK_PRODUCTO = P.PK AND PD.PK_PROMOCION =".$ID_PRODUCTO;

          $rows_3 = database::getRows($sql3);
  
          foreach($rows_3 as $row3){
  
            $table .='
            <tr style="background:#F6F6F6;">
              <td style="font-size:13px;"><b>'.$i.'</b></td>
              <td style="font-size:13px;"><b>'.utf8_encode($row3['TIENDA']).'</b></td>
              <td style="font-size:13px;"><b>'.utf8_encode($row['CLIENTE']).'</b></td>
              <td style="font-size:13px;"><b>'.utf8_encode($row3['PRODUCTO']).'</b></td>
              <td style="font-size:13px;"><b>$'.number_format(round($row3['PRECIO'],2),2).'</b></td>
              <td style="font-size:13px;"><b>'.$row3['CANTIDAD'].'</b></td>
              <td style="font-size:13px;"><b>$'.number_format((round($row3['PRECIO'],2)*$row3['CANTIDAD']),2).'</b></td>
              <td style="font-size:13px;"><b>'.$FECHA_ENTREGA.'</b></td>
              </tr>';
  
              $cantidad += $row['CANTIDAD'];
          
              $i++;
          
          }

      
      
      }else {

        $table .='
        <tr>
          <td><small>'.$i.'</small></td>
          <td><small>'.utf8_encode($row['TIENDA']).'</small></td>
          <td><small>'.utf8_encode($row['CLIENTE']).'</small></td>
          <td><small>'.utf8_encode($row['PRODUCTO']).'</small></td>
          <td><b>$'.number_format(round($row['PRECIO'],2),2).'</b></td>
          <td><b>'.$row['CANTIDAD'].'</b></td>
          <td><b>$'.number_format(round($row['TOTAL'],2),2).'</b></td>
          <td><b>'.$row['FECHA_ENTREGA']->format('d-m-Y').'</b></td>
          </tr>'; 
  
          $total += $row['TOTAL'];
          $cantidad += $row['CANTIDAD'];
  
          $i++;
        
      }

     
 
      }

      if($table==""){ $table='<tr><td colspan="8" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }else{

        $table .='
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><b>'.$cantidad.'</b></td>
          <td><b>$'.number_format(round($total,2),2).'</b></td>
          <td><b>'.$row['FECHA_ENTREGA']->format('d-m-Y').'</b></td>
          </tr>'; 
      }
      
      echo $table;


}


  

  function BuscarInforme(){

    $fecha = $_POST['data']['fecha']; 

    $sql = "SELECT (SELECT NOMBRE FROM TIENDAS WHERE PK = P.PK_TIENDA) AS TIENDA, (SELECT PRODUCTO FROM PRODUCTOS WHERE PK = PD.PK_PRODUCTO) AS PRODUCTO, PD.PK_PRODUCTO AS PK_PRODUCTO,(SELECT PROMOCION FROM PRODUCTOS WHERE PK = PD.PK_PRODUCTO) AS PROMOCION,PD.PRECIO, SUM(PD.CANTIDAD) AS CANTIDAD, SUM((PD.CANTIDAD * convert(float,PD.PRECIO))) AS TOTAL, P.FECHA_ENTREGA  
    FROM PEDIDOS P, PEDIDO_DETALLE PD
    WHERE P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK = PD.PK_PEDIDO AND P.FECHA_ENTREGA = '".$fecha." 00:00:00.000' AND P.BORRADO = 0 AND P.PK_ESTATUS IN(1,2)   
    GROUP BY P.PK_TIENDA, PD.PK_PRODUCTO,PD.PRECIO, P.FECHA_ENTREGA";

    //echo $sql;

     $rows = database::getRows($sql);

     $total = 0;
     $cantidad = 0;
     $table ="";
     $i=1;

     foreach($rows as $row){


      $ID_PRODUCTO = $row['PK_PRODUCTO'];

      $FECHA_ENTREGA = $row['FECHA_ENTREGA']->format('d-m-Y');

      if(trim($row['PROMOCION'])==1){

        $table .='
      <tr style="background:#00D791;">
        <td style="color:white; font-size:13px;"><b>-</b></td>
        <td style="color:white; font-size:13px;"><b>'.utf8_encode($row['TIENDA']).'</b></td>
        <td style="color:white; font-size:13px;"><b>'.utf8_encode($row['PRODUCTO']).'</b></td>
        <td style="color:white; font-size:13px;"><b>$'.number_format(round($row['PRECIO'],2),2).'</b></td>
        <td style="color:white; font-size:13px;"><b>'.$row['CANTIDAD'].'</b></td>
        <td style="color:white; font-size:13px;"><b>$'.number_format(round($row['TOTAL'],2),2).'</b></td>
        <td style="color:white; font-size:13px;"><b>'.$FECHA_ENTREGA.'</b></td>
        </tr>'; 

        $total += $row['TOTAL'];
        
        $sql3 = "select (SELECT NOMBRE FROM TIENDAS WHERE PK=P.PK_TIENDA) AS TIENDA,P.PRODUCTO,P.PK_TIENDA,PD.PK_PRODUCTO,PD.PRECIO,PD.CANTIDAD from PROMOCION_DETALLE PD, PRODUCTOS P WHERE PD.PK_PRODUCTO = P.PK AND PD.PK_PROMOCION =".$ID_PRODUCTO;

        $rows_3 = database::getRows($sql3);

        foreach($rows_3 as $row3){

          $table .='
          <tr style="background:#F6F6F6;">
            <td style="font-size:13px;"><b>'.$i.'</b></td>
            <td style="font-size:13px;"><b>'.utf8_encode($row3['TIENDA']).'</b></td>
            <td style="font-size:13px;"><b>'.utf8_encode($row3['PRODUCTO']).'</b></td>
            <td style="font-size:13px;"><b>$'.number_format(round($row3['PRECIO'],2),2).'</b></td>
            <td style="font-size:13px;"><b>'.$row3['CANTIDAD'].'</b></td>
            <td style="font-size:13px;"><b>$'.number_format((round($row3['PRECIO'],2)*$row3['CANTIDAD']),2).'</b></td>
            <td style="font-size:13px;"><b>'.$FECHA_ENTREGA.'</b></td>
            </tr>';

            $cantidad += $row['CANTIDAD'];
        
            $i++;
        
        }
      
      
      }else{

        $table .='
        <tr>
          <td><small>'.$i.'</small></td>
          <td><small>'.utf8_encode($row['TIENDA']).'</small></td>
          <td><small>'.utf8_encode($row['PRODUCTO']).'</small></td>
          <td><b>$'.number_format(round($row['PRECIO'],2),2).'</b></td>
          <td><b>'.$row['CANTIDAD'].'</b></td>
          <td><b>$'.number_format(round($row['TOTAL'],2),2).'</b></td>
          <td><b>'.$FECHA_ENTREGA.'</b></td>
          </tr>'; 
  
          $total += $row['TOTAL'];
          $cantidad += $row['CANTIDAD'];
          $i++;

      }
      
       
 
      }

      if($table==""){ $table='<tr><td colspan="7" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }else{

        $table .='
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><b>'.$cantidad.'</b></td>
          <td><b>$'.number_format(round($total,2),2).'</b></td>
          <td><b>'.$row['FECHA_ENTREGA']->format('d-m-Y').'</b></td>
          </tr>'; 
      }
      
      echo $table;

  
  }


function Buscar(){


  try{
    $fecha = $_POST['data']['fecha'];
    
    if($_POST['data']['search']==""){
    
    $sql = "SELECT *,(select ESTATUS FROM ESTATUS where PK=P.PK_ESTATUS) AS ESTATUS,
    (SELECT NOMBRE FROM TIENDAS WHERE PK=P.PK_TIENDA) AS NOMBRE,
    /*(SELECT ENCARGADO FROM TIENDAS WHERE PK=P.PK_TIENDA) AS ENCARGADO,
    (SELECT TELEFONO FROM TIENDAS WHERE PK=P.PK_TIENDA) AS TELENCARGADO,*/
    (SELECT NOMBRE FROM REPARTIDORES WHERE PK=P.PK_REPARTIDOR) AS REPARTIDOR, 
    /*(SELECT TELEFONO FROM REPARTIDORES WHERE PK=P.PK_REPARTIDOR) AS TELREPARTIDOR,  */
    (SELECT NOMBRE FROM CLIENTES WHERE PK=P.PK_CLIENTE) AS CLIENTE, 
    (SELECT TELEFONO FROM CLIENTES WHERE PK=P.PK_CLIENTE) AS TELEFONO 
    FROM PEDIDOS P WHERE  P.FECHA_C >= '".$fecha." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM' AND PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") ORDER BY P.FECHA_C DESC";

    }else{
    $word = $_POST['data']['search'];
   
    $sql = "SELECT *,(select ESTATUS FROM ESTATUS where PK=P.PK_ESTATUS) AS ESTATUS,
    (SELECT NOMBRE FROM TIENDAS WHERE PK=P.PK_TIENDA) AS NOMBRE,
    /*(SELECT ENCARGADO FROM TIENDAS WHERE PK=P.PK_TIENDA) AS ENCARGADO,
    (SELECT TELEFONO FROM TIENDAS WHERE PK=P.PK_TIENDA) AS TELENCARGADO,*/
    (SELECT NOMBRE FROM REPARTIDORES WHERE PK=P.PK_REPARTIDOR) AS REPARTIDOR, 
    /*(SELECT TELEFONO FROM REPARTIDORES WHERE PK=P.PK_REPARTIDOR) AS TELREPARTIDOR,  */
    (SELECT NOMBRE FROM CLIENTES WHERE PK=P.PK_CLIENTE) AS CLIENTE, 
    (SELECT TELEFONO FROM CLIENTES WHERE PK=P.PK_CLIENTE) AS TELEFONO
    FROM PEDIDOS P WHERE  P.FECHA_C >= '".$fecha." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM' AND PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") ORDER BY P.FECHA_C DESC";

    }

  
    $rows = database::getRows($sql);

    $i=1;


    $table="";

    foreach($rows as $row){
          
        $metodo = (trim($row['METODO_PAGO'])=='E')?'EFECTIVO':'TARJETA';

         $estatus = '<span class="badge badge-pill badge-primary">'.$row['ESTATUS'].'</span>';

          switch($row['PK_ESTATUS']){
              case '1':
                $estatus = '<span class="badge badge-pill badge-success">'.$row['ESTATUS'].'</span>';
              break;

              case '2':
                $estatus = '<span class="badge badge-pill badge-warning">'.$row['ESTATUS'].'</span>';
              break;

              case '3':
                $estatus = '<span class="badge badge-pill badge-info">'.$row['ESTATUS'].'</span>';
              break;

              
              case '4':
                $estatus = '<span class="badge badge-pill badge-success">'.$row['ESTATUS'].'</span>';
              break;

              case '5':
                $estatus = '<span class="badge badge-pill badge-primary">'.$row['ESTATUS'].'</span>';
              break;

              case '6':
                $estatus = '<span class="badge badge-pill badge-danger">'.$row['ESTATUS'].'</span>';
              break;

              case '7':
                $estatus = '<span class="badge badge-pill badge-dark">'.$row['ESTATUS'].'</span>';
              break;

              default:
            
              break;

          }

            $table .='
                <tr>
                  <td>'.$i++.'</td>
                  <td><a href="detalle.php?id='.$row['PK'].'" target="_blank">'.$row['PK'].'</a></td>
                  <td><small>'.utf8_encode($row['NOMBRE']).'</small></td>
                  <td><small>'.utf8_encode($row['REPARTIDOR']).'</small></td>
                  <td><a href="../clientes/detalle.php?id='.$row['PK_CLIENTE'].'" target="_blank">'.utf8_encode($row['CLIENTE']).'</a></td>
                  <td><small>'.$row['TELEFONO'].'</small></td>
                  <td><b>$'.number_format(round($row['TOTAL'],2),2).'</b></td>
                  <td><small>'.$metodo.'</small></td>
                  <td><small>'.$row['FECHA_C']->format('d-m-Y').'</small></td>
                  <td><b>'.$row['FECHA_ENTREGA']->format('d-m-Y').'</b></td>
                  <td>'.$estatus.'</td>
                  </tr>'; 
 
    }

    if($table==""){ $table='<tr><td colspan="13" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }
    echo $table;

  }catch(\Throwable $t) {

   
    echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}

}






?>