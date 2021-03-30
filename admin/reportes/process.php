<?php

session_start();
require_once "../config.php";
require_once "../include/database.php";
require_once "../include/ImageResize.php";




if(isset($_POST['data']['accion']))
{

    switch($_POST['data']['accion']){


        case "BUSCARCUPONES":
            BuscarCupones();
        break;

        case "BUSCARCOMPROBANTES":
        BuscarComprobantes();
        break;


        case "BUSCARCOMPROBANTESREPARTIDOR":
            BuscarComprobantesRepartidor();
            break;

  

       default:
       break;

    }
    
}


function BuscarCupones(){


    try{
      $fecha = $_POST['data']['fecha'];
      $fechainicio = $_POST['data']['fechainicio'];
      
      if($_POST['data']['search']==""){
      
      $sql = "SELECT *,(select ESTATUS FROM ESTATUS where PK=P.PK_ESTATUS) AS ESTATUS,
      (SELECT NOMBRE FROM TIENDAS WHERE PK=P.PK_TIENDA) AS NOMBRE,
      /*(SELECT ENCARGADO FROM TIENDAS WHERE PK=P.PK_TIENDA) AS ENCARGADO,
      (SELECT TELEFONO FROM TIENDAS WHERE PK=P.PK_TIENDA) AS TELENCARGADO,*/
      (SELECT NOMBRE FROM REPARTIDORES WHERE PK=P.PK_REPARTIDOR) AS REPARTIDOR, 
      /*(SELECT TELEFONO FROM REPARTIDORES WHERE PK=P.PK_REPARTIDOR) AS TELREPARTIDOR,  */
      (SELECT NOMBRE FROM CLIENTES WHERE PK=P.PK_CLIENTE) AS CLIENTE, 
      (SELECT TELEFONO FROM CLIENTES WHERE PK=P.PK_CLIENTE) AS TELEFONO 
      FROM PEDIDOS P WHERE P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND (P.FECHA_C >= '".$fechainicio." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM') AND P.CODIGO_DESCUENTO IS NOT NULL ORDER BY P.FECHA_C DESC";
  
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
      FROM PEDIDOS P WHERE P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND  (P.FECHA_C >= '".$fechainicio." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM') AND P.CODIGO_DESCUENTO IS NOT NULL ORDER BY P.FECHA_C DESC";
  
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
                    <td><small>'.$row['CODIGO_DESCUENTO'].'</small></td>
                    <td><small>$'.number_format(round($row['DESCUENTO'],2),2).'</small></td>
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


function BuscarComprobantesRepartidor(){

    try{

        $fecha = $_POST['data']['fecha'];
        $repartidor = $_POST['data']['repartidor'];
    
     
        if($repartidor == "ALL"){  $condicion =""; }else { $condicion =" AND P.PK_REPARTIDOR=".$repartidor;  }
    
        if($_POST['data']['search']==""){
    
             $sql = "SELECT R.FOLIO,P.FOLIO_PAGO_REPARTIDOR,P.PK_REPARTIDOR,R.NOMBRE,R.BANCO,R.CUENTA,R.CLABE, SUM(P.COMISION_REPARTIDOR) AS SUBTOTAL,SUM(P.PAGO_REPARTIDOR) AS PAGO,P.FECHA_PAGO_REPARTIDOR FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_REPARTIDOR = R.PK AND P.PAGO_REPARTIDOR > 0 AND P.FECHA_PAGO_REPARTIDOR >= '".$fecha." 12:00:00 AM' AND  P.FECHA_PAGO_REPARTIDOR <= '".$fecha." 11:59:59 PM' ".$condicion." GROUP BY R.FOLIO,P.PK_REPARTIDOR,R.NOMBRE,R.BANCO,R.CUENTA,R.CLABE,P.FECHA_PAGO_REPARTIDOR,P.FOLIO_PAGO_REPARTIDOR";
    
        }else{
        
            $word = $_POST['data']['search'];
       
            $sql = "SELECT R.FOLIO,P.FOLIO_PAGO_REPARTIDOR,P.PK_REPARTIDOR,R.NOMBRE,R.BANCO,R.CUENTA,R.CLABE, SUM(P.COMISION_REPARTIDOR) AS SUBTOTAL,SUM(P.PAGO_REPARTIDOR) AS PAGO,P.FECHA_PAGO_REPARTIDOR FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_REPARTIDOR = R.PK AND P.PAGO_REPARTIDOR > 0 AND P.FECHA_PAGO_REPARTIDOR >= '".$fecha." 12:00:00 AM' AND  P.FECHA_PAGO_REPARTIDOR <= '".$fecha." 11:59:59 PM' ".$condicion." GROUP BY R.FOLIO,P.PK_REPARTIDOR,R.NOMBRE,R.BANCO,R.CUENTA,R.CLABE,P.FECHA_PAGO_REPARTIDOR,P.FOLIO_PAGO_REPARTIDOR";
        
        }

        
    
         
        $rows = database::getRows($sql);
    
        $i=1;
        $table ="";
        $subtotal = 0.00;
    
        foreach($rows as $row){
        
                $table .= '
                    <tr>
                      <td>'.$i++.'</td>
                      <td>'.$row['FOLIO'].'</td>
                      <td>'.utf8_encode($row['NOMBRE']).'</td>
                      <td>'.$row['FECHA_PAGO_REPARTIDOR']->format('d/m/Y').'</td>
                      <td><a href="../tendederos/comprobante.php?id='.$row['PK_REPARTIDOR'].'&folio='.$row['FOLIO_PAGO_REPARTIDOR'].'" target="_blank">'.$row['FOLIO_PAGO_REPARTIDOR'].'</a></td>
                      <td>'.$row['BANCO'].'</td>
                      <td>'.$row['CUENTA'].'</td>
                      <td>'.$row['CLABE'].'</td>
                      <td>$'.round($row['SUBTOTAL'],2).'</td>
                      <td>$'.round($row['PAGO'],2).'</td>
                    </tr>'; 
    
                    $subtotal += round($row['SUBTOTAL'],2);
                  
        }
    
    
    
            if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }
    
            
             echo $table."|$".$subtotal;
    
            }catch(\Throwable $t) {
    
       
                echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
            
            }


}


function BuscarComprobantes(){


    try{

    $fecha = $_POST['data']['fecha'];
    $tienda = $_POST['data']['tienda'];

 
    if($tienda == "ALL"){  $condicion =""; }else { $condicion =" AND P.PK_TIENDA=".$tienda;  }

    if($_POST['data']['search']==""){

         $sql = "SELECT T.FOLIO,P.FOLIO_PAGO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS SUBTOTAL,SUM(P.PAGO_TIENDA) AS PAGO,P.FECHA_PAGO_TIENDA FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA > 0 AND P.FECHA_PAGO_TIENDA >= '".$fecha." 12:00:00 AM' AND  P.FECHA_PAGO_TIENDA <= '".$fecha." 11:59:59 PM' AND P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") ".$condicion." GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE,P.FECHA_PAGO_TIENDA,P.FOLIO_PAGO";

    }else{
    
        $word = $_POST['data']['search'];
   
        $sql = "SELECT T.FOLIO,P.FOLIO_PAGO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS ,SUM(P.PAGO_TIENDA) AS PAGO,P.FECHA_PAGO_TIENDA FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA > 0 AND P.FECHA_PAGO_TIENDA >= '".$fecha." 12:00:00 AM' AND  P.FECHA__PAGO_TIENDA <= '".$fecha." 11:59:59 PM' AND P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND ".$condicion." GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE,P.FECHA_PAGO_TIENDA,P.FOLIO_PAGO";
    
    }

     
    $rows = database::getRows($sql);

    $i=1;
    $table ="";
    $subtotal = 0.00;

    foreach($rows as $row){
    
            $table .= '
                <tr>
                  <td>'.$i++.'</td>
                  <td>'.$row['FOLIO'].'</td>
                  <td>'.utf8_encode($row['NOMBRE']).'</td>
                  <td>'.$row['FECHA_PAGO_TIENDA']->format('d/m/Y').'</td>
                  <td><a href="../asociados/comprobante.php?id='.$row['PK_TIENDA'].'&folio='.$row['FOLIO_PAGO'].'" target="_blank">'.$row['FOLIO_PAGO'].'</a></td>
                  <td>'.$row['BANCO'].'</td>
                  <td>'.$row['CUENTA'].'</td>
                  <td>'.$row['CLABE'].'</td>
                  <td>$'.round($row['SUBTOTAL'],2).'</td>
                  <td>$'.round($row['PAGO'],2).'</td>
                </tr>'; 

                $subtotal += round($row['SUBTOTAL'],2);
              
    }



        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }

        
         echo $table."|$".$subtotal;

        }catch(\Throwable $t) {

   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }


}




function UpdatePago(){


    try{

    $tienda = $_POST['data']['tienda'];

    $condicion =" AND P.PK_TIENDA=".$tienda;
    $sql = "SELECT P.* FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." ORDER BY P.FECHA_C";
    $rows = database::getRows($sql);

    $fecha = date("Y-m-d H:i:s");
    $folio = generate_string();

    $procesado = TRUE;
    

    foreach($rows as $row){

        $campos = array(
            'PAGO_TIENDA'=>$row['SUBTOTAL'],
            'FECHA_PAGO_TIENDA'=>$fecha,
            'FOLIO_PAGO'=>$folio,
            );
 
    $condition = "PK=".$row['PK'];
 
    $resultado = database::updateRecords("PEDIDOS",$campos,$condition);

    if(!$resultado){
        $procesado = FALSE;
    }

    
    }


    if($procesado){

        echo "EXITOSO|".$folio;
        
    }else{
        echo "ERROR|".$folio;
    }

}catch(\Throwable $t) {

   
    echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}

}


function generate_string($strength = 4) {

    $input = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input2 = '0123456789';

    $input_length = strlen($input);
    $input_length2 = strlen($input2);
    
    $random_string = '';
    
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];

        $random_character2 = $input2[mt_rand(0, $input_length2 - 1)];

        $random_string .= $random_character.$random_character2;
    }
 
    return $random_string;
}




function BuscarPagos(){


    try{

    $tienda = $_POST['data']['tienda'];

    $condicion =" AND P.PK_TIENDA=".$tienda;

    if($_POST['data']['search']==""){

        $sql = "SELECT * FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." ORDER BY P.FECHA_C";

    }else{
    
        $word = $_POST['data']['search'];
   
       $sql = "SELECT * FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." ORDER BY P.FECHA_C";
    
    }


    $rows = database::getRows($sql);

    $i=1;

    $table ="";
    $subtotal = 0.00;
    $pago = 0.00;


    foreach($rows as $row){

        $fecha_pago_tienda ="";
        if($row['FECHA_PAGO_TIENDA']!=NULL){
          $fecha_pago_tienda = $row['FECHA_PAGO_TIENDA']->format("Y-m-d H:i");
        }

        $metodo_pago = "EFECTIVO";
        if($row['METODO_PAGO']=="T"){
        $metodo_pago = "TARJETA";
        }

            $table .= '
                <tr>
                  <td>'.$i++.'</td>
                  <td>'.$row['FECHA_C']->format("Y-m-d H:i").'</td>
                  <td>'.$row['PK'].'</td>
                  <td>'.utf8_encode($row['NOMBRE']).'</td>
                  <td>$'.round($row['SUBTOTAL'],2).'</td>
                  <td>$'.round($row['ENVIO'],2).'</td>
                  <td>$'.round($row['COMISION_TARJETA'],2).'</td>
                  <td>$'.round($row['TOTAL'],2).'</td>
                  <td>'.$metodo_pago.'</td>
                  <td>$'.round($row['PAGO_EFECTIVO'],2).'</td>
                  <td>$'.round($row['PAGO_TIENDA'],2).'</td>
                  <td>$'.$fecha_pago_tienda.'</td>
                  
                </tr>'; 

                $subtotal += round($row['SUBTOTAL'],2);
                $pago += round($row['PAGO_TIENDA'],2);
              
    }



        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }

        $adeudo = $subtotal - $pago;

        
         echo $table."|$".$adeudo;

        }catch(\Throwable $t) {

   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }


}



function BuscarCorte(){

    try{

    $tienda = $_POST['data']['tienda'];
    
    if($tienda == "ALL"){  $condicion =""; }else { $condicion =" AND P.PK_TIENDA=".$tienda;  }

    if($_POST['data']['search']==""){

        $sql = "SELECT T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS ADEUDO FROM PEDIDOS P, TIENDAS T WHERE  P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE";

    }else{
    
        $word = $_POST['data']['search'];
   
       $sql = "SELECT T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS ADEUDO FROM PEDIDOS P, TIENDAS T WHERE  P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE";
    
    }

     

    $rows = database::getRows($sql);

    $i=1;

    $table ="";
    $subtotal = 0.00;


    foreach($rows as $row){

    
            $table .= '
                <tr>
                  <td>'.$i++.'</td>
                  <td><a href="pagos.php?id='.$row['PK_TIENDA'].'">'.$row['FOLIO'].'</a></td>
                  <td>'.utf8_encode($row['NOMBRE']).'</td>
                  <td>'.$row['BANCO'].'</td>
                  <td>'.$row['CUENTA'].'</td>
                  <td>'.$row['CLABE'].'</td>
                  <td>$'.round($row['ADEUDO'],2).'</td>
                </tr>'; 

                $subtotal += round($row['ADEUDO'],2);
              
    }



        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }

         echo $table."|$".$subtotal;

        }catch(\Throwable $t) {

   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }

}


function Buscar(){

    try{

    if($_POST['data']['search']==""){
    $sql = 'SELECT * FROM TIENDAS WHERE PK IN ('.$_SESSION['POLARSESSION']['PK_TIENDA'].') ORDER BY FECHA_C';
    }else{
    $word = $_POST['data']['search'];
    $sql = "SELECT * FROM TIENDAS WHERE PK IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND (NOMBRE LIKE '%".$word."%' OR FOLIO LIKE '%".$word."%')";
    
    }


    $rows = database::getRows($sql);

    foreach($rows as $row){
          

        echo '
        <div class="col-md-3">
          <div class="card mb-3 box-shadow">
            <img class="card-img-top" src="'.$row['IMAGEN'].'" width="200" height="200"  alt="Card image cap">
            <div class="card-body">
              <p class="card-text">
              <h6 class="border-bottom border-gray pb-2 mb-0">'.$row['NOMBRE'].'</h6>
              <small class="text-muted">'.$row['TELEFONO'].'</small><br>
              <small class="text-muted">'.$row['CORREO'].'</small>
              </p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="productos/?id='.$row['PK'].'" class="btn btn-sm btn-outline-secondary">Ver</a>
                  <a href="edit.php?id='.$row['PK'].'" class="btn btn-sm btn-outline-secondary">Editar</a>
                </div>
                <small class="text-muted">'.$row['FOLIO'].'</small>
              </div>
            </div>
          </div>
        </div>';

    }


}catch(\Throwable $t) {

   
    echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

}

}


function Eliminar(){
    
    try{

    $id = $_POST['data']['id'];
    $condition = "PK=".$id;
    $resultado = database::deleteRecords("TIENDAS",$condition);

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

   try{
    if(is_array($_FILES)) {

        if(is_uploaded_file($_FILES['promoImage']['tmp_name'])) {
        $sourcePath = $_FILES['promoImage']['tmp_name'];
        $targetPath = "imagenes/".$_FILES['promoImage']['name'];

        if(move_uploaded_file($sourcePath,$targetPath)) {
        
            $image = new ImageResize($targetPath);
            //$image->scale(10);
            $image->quality_jpg = 100;
            $image->resizeToBestFit(400, 400);
            //$image->crop(200, 200, true, ImageResize::CROPCENTER);
            $foto = 'imagenes/'.uniqid().'.jpg';
            $image->save($foto);
            $foto = URL_ASOCIADOS.$foto;
            unlink($targetPath);
        
                   
                $empresa = $_POST['empresa'];
                $folio = $_POST['folio'];
                $encargado = $_POST['encargado'];
                $direccion = $_POST['direccion'];
                $email = $_POST['email'];
                $telefono = $_POST['telefono'];
                $password = $_POST['password'];
                $banco = $_POST['banco'];
                $clabe = $_POST['clabe'];
                $cuenta = $_POST['cuenta'];

                $latitud = $_POST['latitud'];
                $longitud = $_POST['longitud'];
                 
                $fecha = date("Y-m-d H:i:s");

                
   
                $campos = array(
                    'NOMBRE'=>$empresa,
                    'FOLIO'=>$folio,
                    'ENCARGADO'=>$encargado,
                    'IMAGEN'=>$foto,
                    'FECHA_C'=>$fecha,
                    'DIRECCION'=>$direccion,
                    'TELEFONO'=>$telefono,
                    'CORREO'=>$email,
                    'PASSWORD'=>$password,
                    'BANCO'=>$banco,
                    'CLABE'=>$clabe,
                    'CUENTA'=>$cuenta,
                    'LATITUD'=>$latitud,
                    'LONGITUD'=>$longitud,

                     );

               $resultado = database::insertRecords("TIENDAS",$campos);

        
                if($resultado){
                      echo "EXITOSO";
                     
                }else{
                      echo "ERROR";
                }
        
        }
        }
        }

    }catch(\Throwable $t) {

   
        echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
    
    }

}




if(isset($_POST['accion']) && $_POST['accion'] =="UPDATE"){

    try{

    $DBConnect =  new Database();    

        if(is_array($_FILES)) {
        if(is_uploaded_file($_FILES['promoImage']['tmp_name'])) {
        //TIENE ARCHIVO A MODIFICAR

                $sourcePath = $_FILES['promoImage']['tmp_name'];
                $targetPath = "imagenes/".$_FILES['promoImage']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)) {


                    $image = new ImageResize($targetPath);
                    //$image->scale(10);
                    $image->quality_jpg = 100;
                    $image->resizeToBestFit(400, 400);
                    //$image->crop(200, 200, true, ImageResize::CROPCENTER);
                    $foto = 'imagenes/'.uniqid().'.jpg';
                    $image->save($foto);
                    $foto = URL_ASOCIADOS.$foto;
                    unlink($targetPath);
                
                        $id = $_POST['id'];
                        $empresa = $_POST['empresa'];
                        $folio = $_POST['folio'];
                        $encargado = $_POST['encargado'];
                        $direccion = $_POST['direccion'];
                        $email = $_POST['email'];
                        $telefono = $_POST['telefono'];
                        $password = $_POST['password'];
                        $banco = $_POST['banco'];
                        $clabe = $_POST['clabe'];
                        $cuenta = $_POST['cuenta'];
                        $latitud = $_POST['latitud'];
                        $longitud = $_POST['longitud'];
            
                        $fecha = date("Y-m-d H:i:s");


                        UpdateCategorias($id);
        
                        $campos = array(
                            'NOMBRE'=>$empresa,
                            'FOLIO'=>$folio,
                            'IMAGEN'=>$foto,
                            'ENCARGADO'=>$encargado,
                            'FECHA_C'=>$fecha,
                            'DIRECCION'=>$direccion,
                            'TELEFONO'=>$telefono,
                            'CORREO'=>$email,
                            'PASSWORD'=>$password,
                            'BANCO'=>$banco,
                            'CLABE'=>$clabe,
                            'CUENTA'=>$cuenta,
                            'LATITUD'=>$latitud,
                            'LONGITUD'=>$longitud,
                            );

                    $condition = "PK=".$id;

                    $resultado = database::updateRecords("TIENDAS",$campos,$condition);
                
                        if($resultado){
                            
                              echo "EXITOSO";
                             
                        }else{
                              echo "ERROR";
                        }



                }
        
        
        }else{
         //NO TIENE ARCHIVO PARA MODIFICAR

       
       $id = $_POST['id'];
       $empresa = $_POST['empresa'];
       $folio = $_POST['folio'];
       $encargado = $_POST['encargado'];
       $direccion = $_POST['direccion'];
       $email = $_POST['email'];
       $telefono = $_POST['telefono'];
       $password = $_POST['password'];
       $banco = $_POST['banco'];
       $clabe = $_POST['clabe'];
       $cuenta = $_POST['cuenta'];
       $latitud = $_POST['latitud'];
       $longitud = $_POST['longitud'];


       UpdateCategorias($id);


       $fecha = date("Y-m-d H:i:s");

       $campos = array(
           'NOMBRE'=>$empresa,
           'FOLIO'=>$folio,
           'ENCARGADO'=>$encargado,
           'FECHA_C'=>$fecha,
           'DIRECCION'=>$direccion,
           'TELEFONO'=>$telefono,
           'CORREO'=>$email,
           'PASSWORD'=>$password,
           'BANCO'=>$banco,
           'CLABE'=>$clabe,
           'CUENTA'=>$cuenta,
           'LATITUD'=>$latitud,
           'LONGITUD'=>$longitud,
           );

   $condition = "PK=".$id;

   $resultado = database::updateRecords("TIENDAS",$campos,$condition);

       if($resultado){
            
          echo "EXITOSO";
            
       }else{
             echo "ERROR";
       }


        }
        }

    }catch(\Throwable $t) {

   
        echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
    
    }

}




function UpdateCategorias($id){


    try{

    if(isset($_POST['tipos'])){

        $tipos = $_POST['tipos'];

        
        $condition = "PK_TIENDA=".$id;
        $resultado = database::deleteRecords("TIENDAS_TIPOS",$condition);

        foreach($tipos as $tipo){

            
                
            $campos = array(
                'PK_TIENDA'=>$id,
                'PK_TIPO'=>$tipo,
                 );

           $resultado = database::insertRecords("TIENDAS_TIPOS",$campos);

        }

        
       }else{
            
        $condition = "PK_TIENDA=".$id;
        $resultado = database::deleteRecords("TIENDAS_TIPOS",$condition);

       }

    }catch(\Throwable $t) {

   
        echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
    
    }

}

?>