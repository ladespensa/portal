<?php

session_start();
require_once "../config.php";
require_once "../include/database.php";
require_once "../include/ImageResizeException.php";
require_once "../include/ImageResize.php";




if(isset($_POST['data']['accion']))
{

    switch($_POST['data']['accion']){

       case "ELIMINAR":
       Eliminar();
       break;


       case "BUSCAR":
        Buscar();
        break;

       case "BUSCARADEUDOS":
        ActualizarAdeudos();
         //BuscarAdeudos();
        break;

        case "BUSCARPAGOS":
            BuscarPagos();
        break;

        case "BUSCARCORTE":
            BuscarCorte();
           break;
   
        
        case "UPDATEPAGO":
         UpdatePago();
        break;


        case "LIQUIDARPAGO":
            LiquidarPago();
           break;
    
    

       default:
       break;

    }
    
}



function LiquidarPago(){

    try{

        $repartidor = $_POST['data']['repartidor'];
    
        $condicion =" AND P.PK_REPARTIDOR=".$repartidor;
        $sql = "SELECT P.* FROM PEDIDOS P, REPARTIDORES R where P.PK_TIENDA IN(".$_SESSION['POLARSESSION']['PK_TIENDA'].") and P.PK_REPARTIDOR = R.PK AND P.PAGO_REPARTIDOR = 0 ".$condicion." AND P.BORRADO = 0 ORDER BY P.FECHA_C";
        
        $rows = database::getRows($sql);
    
        $fecha = date("Y-m-d H:i:s");
        $folio = generate_string();
    
        $procesado = TRUE;
        
    
        foreach($rows as $row){
    
            $campos = array(
                'PAGO_REPARTIDOR'=>$row['COMISION_REPARTIDOR'],
                'FECHA_PAGO_REPARTIDOR'=>$fecha,
                'FOLIO_PAGO_REPARTIDOR'=>$folio,
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

    $repartidor = $_POST['data']['repartidor'];

    $condicion =" AND P.PK_REPARTIDOR=".$repartidor;

    if($_POST['data']['search']==""){

        $sql = "SELECT *,P.FECHA_C AS FECHA_PEDIDO,P.PK AS PEDIDO FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_REPARTIDOR = R.PK AND P.PAGO_REPARTIDOR = 0 ".$condicion." AND P.BORRADO = 0 ORDER BY P.FECHA_C";

    }else{
    
        $word = $_POST['data']['search'];
   
       $sql = "SELECT *,P.FECHA_C AS FECHA_PEDIDO,P.PK AS PEDIDO FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_REPARTIDOR = R.PK AND P.PAGO_REPARTIDOR = 0 ".$condicion." AND P.BORRADO = 0 ORDER BY P.FECHA_C";
    
    }


    $rows = database::getRows($sql);

    $i=1;

    $table ="";
    $subtotal = 0.00;
    $pago = 0.00;


    foreach($rows as $row){

        $fecha_pago_tienda ="";
        if($row['FECHA_PAGO_REPARTIDOR']!=NULL){
          $fecha_pago_tienda = $row['FECHA_PAGO_REPARTIDOR']->format("Y-m-d H:i");
        }

        if($row['METODO_PAGO']=="E"){ 
          $metodo_pago = "EFECTIVO";
        }else if($row['METODO_PAGO']=="T") {
          $metodo_pago = "TARJETA";
        
        }else if($row['METODO_PAGO']=="C") {
          $metodo_pago="TERMINAL";
        }else{
          $metodo_pago="DESCONOCIDO";
        }

        
            $table .= '
                <tr>
                  <td>'.$i++.'</td>
                  <td>'.$row['FECHA_PEDIDO']->format("Y-m-d H:i").'</td>
                  <td>'.$row['PEDIDO'].'</td>
                  <td>'.utf8_encode($row['NOMBRE']).'</td>
                  <td>$'.number_format(round($row['SUBTOTAL'],2),2).'</td>
                  <td>$'.number_format(round($row['ENVIO'],2),2).'</td>
                  <td>$'.number_format(round($row['COMISION_TARJETA'],2),2).'</td>
                  <td>$'.number_format(round($row['TOTAL'],2),2).'</td>
                  <td>'.$metodo_pago.'</td>
                  <td>$'.number_format(round($row['PAGO_EFECTIVO'],2),2).'</td>
                  <td>$'.number_format(round($row['COMISION_REPARTIDOR'],2),2).'</td>
                  <td>$'.number_format(round($row['PAGO_REPARTIDOR'],2),2).'</td>
                  <td>'.$fecha_pago_tienda.'</td>
                  
                </tr>'; 

                $subtotal += round($row['COMISION_REPARTIDOR'],2);
                              
    }


        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }

        //$adeudo = $subtotal - $pago;
        
         echo $table."|$".number_format($subtotal,2);

        }catch(\Throwable $t) {

   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }


}


function BuscarCorte(){

    try{
    
        $repartidor = $_POST['data']['repartidor'];
        
        if($repartidor == "ALL"){  $condicion =""; }else { $condicion =" AND P.PK_REPARTIDOR=".$repartidor;  }
    
        if($_POST['data']['search']==""){
    
            //$sql = "SELECT T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS ADEUDO FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE";
            $sql = "SELECT R.FOLIO, COUNT(*) AS PEDIDOS, P.PK_REPARTIDOR,R.NOMBRE,R.APATERNO,R.AMATERNO,R.BANCO,R.CUENTA,R.CLABE, SUM(P.COMISION_REPARTIDOR) AS ADEUDO FROM VPEDIDOS_0 P, REPARTIDORES R WHERE P.PK_REPARTIDOR = R.PK AND P.PAGO_REPARTIDOR = 0 ".$condicion." AND P.BORRADO = 0 AND P.PK_TIENDA IN(".$_SESSION['POLARSESSION']['PK_TIENDA'].") GROUP BY R.FOLIO,P.PK_REPARTIDOR,R.NOMBRE,R.APATERNO,R.AMATERNO,R.BANCO,R.CUENTA,R.CLABE";
    
        }else{
        
            $word = $_POST['data']['search'];
       
           //$sql = "SELECT T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS ADEUDO FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE";
           $sql = "SELECT R.FOLIO,COUNT(*) AS PEDIDOS ,P.PK_REPARTIDOR,R.NOMBRE,R.APATERNO,R.AMATERNO,R.BANCO,R.CUENTA,R.CLABE, SUM(P.COMISION_REPARTIDOR) AS ADEUDO FROM VPEDIDOS_0 P, REPARTIDORES R WHERE P.PK_REPARTIDOR = R.PK AND P.PAGO_REPARTIDOR = 0 ".$condicion." AND P.BORRADO = 0  AND P.PK_TIENDA IN(".$_SESSION['POLARSESSION']['PK_TIENDA'].")  GROUP BY R.FOLIO,P.PK_REPARTIDOR,R.NOMBRE,R.APATERNO,R.AMATERNO,R.BANCO,R.CUENTA,R.CLABE";
        
        }
    
         
    
        $rows = database::getRows($sql);
    
        $i=1;
    
        $table ="";
        $subtotal = 0.00;
    
    
        foreach($rows as $row){
    
        
                $table .= '
                    <tr>
                      <td>'.$i++.'</td>
                      <td><a href="pagos.php?id='.$row['PK_REPARTIDOR'].'">'.$row['FOLIO'].'</a></td>
                      <td>'.utf8_encode($row['NOMBRE'].' '.$row['APATERNO'].' '.$row['APATERNO']).'</td>
                      <td>'.$row['PEDIDOS'].'</td>
                      <td>'.$row['BANCO'].'</td>
                      <td>'.$row['CUENTA'].'</td>
                      <td>'.$row['CLABE'].'</td>
                      <td>$'.number_format(round($row['ADEUDO'],2),2).'</td>
                    </tr>'; 
    
                    $subtotal += round($row['ADEUDO'],2);
                  
        }
    
    
    
            if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }
    
             echo $table."|$".number_format($subtotal,2);
    
            }catch(\Throwable $t) {
    
       
                echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
            
            }
    
    }


function UpdatePago(){


    try{

    $id = $_POST['data']['id'];
    $pago = $_POST['data']['pago'];
    $fecha = date("Y-m-d H:i:s");

    $campos = array(
        'PAGO_EFECTIVO'=>$pago,
        'FECHA_PAGO_EFECTIVO'=>$fecha,
         );

        $condition = "PK=".$id;
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



function ActualizarAdeudos(){

    try{

        $fecha = $_POST['data']['fecha']; 
        $sql = "SELECT R.FOLIO,R.NOMBRE, R.APATERNO,R.AMATERNO, P.PK AS PEDIDO, P.SUBTOTAL, P.ENVIO, P.COMISION_TARJETA, P.TOTAL,P.METODO_PAGO, P.PAGO_EFECTIVO,PK_COSTO_ENVIO,(SELECT COMISION FROM COSTOS_ENVIOS WHERE PK=P.PK_COSTO_ENVIO) AS PORCENTAJE_COMISION, P.FECHA_PAGO_EFECTIVO, P.FECHA_C  FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_TIENDA IN(".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_REPARTIDOR = R.PK AND P.FECHA_C >= '".$fecha." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM' AND P.BORRADO = 0  ORDER BY FECHA_C";
    
        $rows = database::getRows($sql);
    
        foreach($rows as $row){
    
            $id = $row['PEDIDO'];

            $fecha = $row['FECHA_C']->format("Y-m-d H:i:s");
            $envio = round($row['ENVIO'],2);
            $porcentaje = round($row['PORCENTAJE_COMISION'],2);

            $pago_efectivo = round($row['PAGO_EFECTIVO'],2);

            $total = round($row['TOTAL'],2);

            //SE CAMBIA PARA QUE LA COMISION SIEMPRE SEA DE 6 PESOS SIN IMPORTAR NADA
            $comision = 5.00;


            if($row['METODO_PAGO']=="T" || $row['METODO_PAGO']=="C"){ 
                      
                 //SI ES TARJETA
                      $deposito = $row['TOTAL'];
                      //$comision = round(($envio*$porcentaje)/100,2);

                      $campos = array(
                        'PAGO_EFECTIVO'=>$deposito,
                        'FECHA_PAGO_EFECTIVO'=>$fecha,
                        'COMISION_REPARTIDOR'=>$comision,
                         );
                   
                      $condition = "PK=".$id;
                   
                      $resultado = database::updateRecords("PEDIDOS",$campos,$condition);

            }else{
                  //SI ES EFECTIVO
                  if($pago_efectivo==$total){


                    //$comision = round(($envio*$porcentaje)/100,2); 

                      $campos = array(
                        'COMISION_REPARTIDOR'=>$comision,
                         );
                   
                      $condition = "PK=".$id;
                   
                      $resultado = database::updateRecords("PEDIDOS",$campos,$condition);

                  }
             
            
            }
           
              
        }

        BuscarAdeudos();
    
            }catch(\Throwable $t) {
           
                echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
            
            }

}


function BuscarAdeudos(){

    try{

    $fecha = $_POST['data']['fecha'];  
    $repartidor = $_POST['data']['repartidor'];
    
    if($repartidor == "ALL"){  $condicion =""; }else { $condicion =" AND PK_REPARTIDOR=".$repartidor;  }

    if($_POST['data']['search']==""){

        $sql = "SELECT R.FOLIO,R.NOMBRE, R.APATERNO,R.AMATERNO, P.PK AS PEDIDO, P.SUBTOTAL, P.ENVIO, P.COMISION_TARJETA, P.TOTAL,P.METODO_PAGO, P.PAGO_EFECTIVO, P.FECHA_PAGO_EFECTIVO, P.FECHA_C  FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_TIENDA IN(".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND P.PK_REPARTIDOR = R.PK AND P.FECHA_C >= '".$fecha." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM' ".$condicion." AND P.BORRADO = 0 ORDER BY FECHA_C";

    }else{
    
        $word = $_POST['data']['search'];
   
       $sql = "SELECT R.FOLIO,R.NOMBRE, R.APATERNO,R.AMATERNO, P.PK AS PEDIDO, P.SUBTOTAL, P.ENVIO, P.COMISION_TARJETA, P.TOTAL,P.METODO_PAGO, P.PAGO_EFECTIVO, P.FECHA_PAGO_EFECTIVO, P.FECHA_C  FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_TIENDA IN(".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND  P.PK_REPARTIDOR = R.PK AND P.FECHA_C >= '".$fecha." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM' ".$condicion." AND P.BORRADO = 0 ORDER BY FECHA_C";
    
    }

     

    $rows = database::getRows($sql);

    $i=1;

    $table ="";
    $subtotal = 0.00;
    $envio = 0.00;
    $comision_tarjeta = 0.00;
    $total = 0.00;
    $efectivo = 0.00;
    $pago_efectivo = 0.00;
    $adeudo = 0.00;

    $red = "";

    foreach($rows as $row){

        $red = "";

        $link= "#";

        $total += round($row['TOTAL'],2);

        $pago_efectivo2 = round($row['PAGO_EFECTIVO'],2);

        if($pago_efectivo2==0){
            $red = 'style="color:red"';
        }
        
       if($row['FECHA_PAGO_EFECTIVO']==null){ 
                $fecha_pago = "";
       }else{
                $fecha_pago = $row['FECHA_PAGO_EFECTIVO']->format('d-m-Y');
       }

       if($row['METODO_PAGO']=="E"){ 
          $metodo_pago = "EFECTIVO";
          $efectivo += round($row['TOTAL'],2);
          $pago_efectivo +=round($row['PAGO_EFECTIVO'],2);
          $link = 'javascript:setPago('.$row['PEDIDO'].','.round($row['TOTAL'],2).')';

        }else if($row['METODO_PAGO']=="T") {
          $metodo_pago = "TARJETA";
        
        }else if($row['METODO_PAGO']=="C") {
          $metodo_pago="TERMINAL";
        }else{
          $metodo_pago="DESCONOCIDO";
        }

    
            $table .= '
                <tr '.$red.'>
                  <td>'.$i++.'</td>
                  <td>'.$row['FOLIO'].'</td>
                  <td>'.utf8_encode($row['NOMBRE'].' '.$row['APATERNO'].' '.$row['AMATERNO']).'</td>
                  <td><a href="../pedidos/detalle.php?id='.$row['PEDIDO'].'" target="_blank">'.$row['PEDIDO'].'</a></td>
                  <td>$'.number_format(round($row['SUBTOTAL'],2),2).'</td>
                  <td>$'.number_format(round($row['ENVIO'],2),2).'</td>
                  <td>$'.number_format(round($row['COMISION_TARJETA'],2),2).'</td>
                  <td>$'.number_format(round($row['TOTAL'],2),2).'</td>
                  <td>'.$metodo_pago.'</td>
                  <td><a href="'.$link.'">$'.number_format(round($row['PAGO_EFECTIVO'],2),2).'</a></td>
                  <td>'.$fecha_pago.'</td>
                  <td><b>'.$row['FECHA_C']->format('d-m-Y').'</b></td>
                </tr>'; 

                $subtotal += round($row['SUBTOTAL'],2);
                $envio += round($row['ENVIO'],2);
                $comision_tarjeta += round($row['COMISION_TARJETA'],2);
                
              
    }


        $adeudo = $efectivo - $pago_efectivo;

        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }

         echo $table."|$".number_format($subtotal,2)."|$".number_format($envio,2)."|$".number_format($comision_tarjeta,2)."|$".number_format($total,2)."|$".number_format($efectivo,2)."|$".number_format($pago_efectivo,2)."|$".number_format($adeudo,2);


        }catch(\Throwable $t) {

   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }
}



function Buscar(){

    try{

    if($_POST['data']['search']==""){
    $sql = 'SELECT * FROM REPARTIDORES WHERE PK_TIENDA IN ('.$_SESSION['POLARSESSION']['PK_TIENDA'].') ORDER BY FECHA_C DESC';
    }else{
    $word = $_POST['data']['search'];
    $sql = "SELECT * FROM REPARTIDORES WHERE PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND (NOMBRE LIKE '%".$word."%' OR FOLIO LIKE '%".$word."%')";
    
    }


    $rows = database::getRows($sql);

    foreach($rows as $row){
          
        $fecha = $row['FECHA_C']->format("Y-m-d");

        echo '<div class="col-sm-6 col-xl-3">
        <div class="card mb-3 overflow-hidden">
        <div class="card-body pb-0">
        <div class="card-badges bg-success text-white left"><span>Activo</span></div>
        <div class="d-flex justify-content-between align-items-start">
        <div class="text-center w-100">
        <a href="javascript:void(0)" class="text-dark text-big font-weight-semibold">'.utf8_encode($row['NOMBRE']).'</a>
        </div>
        <div class="btn-group team-actions">
        <button type="button" class="btn btn-sm btn-default icon-btn borderless btn-round md-btn-flat dropdown-toggle hide-arrow" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="edit.php?id='.$row['PK'].'">Editar</a>
        <a class="dropdown-item" href="javascript:void(0)">Remover</a>
        </div>
        </div>
        </div>
        <div class="mt-3">
        <img class="card-img-top" src="'.$row['IMAGEN'].'" width="200" height="200"  alt="'.utf8_encode($row['NOMBRE']).'">
        </div>
        </div>
        
        <hr class="m-0">
        <div class="card-body pb-3">
        <div class="text-muted small">Encargado</div>
        <div class="mb-3">
        <a href="javascript:void(0)" class="text-dark font-weight-semibold">'.utf8_encode($row['NOMBRE']).'</a>
         </div>
        <div class="row mb-3">
        <div class="col">
        <div class="text-muted small">Telefono</div>
        <div class="small font-weight-bold">'.$row['TELEFONO'].'</div>
        </div>
        <div class="col">
        <div class="text-muted small">Correo</div>
        <div class="small font-weight-bold">'.$row['CORREO'].'</div>
        </div>
        </div>
        <div class="d-flex justify-content-between align-items-center small">
        <div class="font-weight-bold">'.$row['FOLIO'].'</div>
        <div class="text-muted">'.$fecha.'</div>
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
    $resultado = database::deleteRecords("REPARTIDORES",$condition);

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
            //$image->resizeToBestFit(800, 800);
            $image->crop(200, 200, true, ImageResize::CROPCENTER);
            $foto = 'imagenes/'.uniqid().'.jpg';
            $image->save($foto);
            unlink($targetPath);
        
                   
                $folio = $_POST['folio'];
                $nombre = $_POST['nombre'];
                $apaterno = $_POST['apaterno'];
                $amaterno = $_POST['amaterno'];
                $estado = $_POST['estado'];
                $municipio = $_POST['municipio'];
                $colonia = $_POST['colonia'];
                $calle = $_POST['calle'];
                $numero = $_POST['numero'];
                
                
                $email = $_POST['email'];
                $telefono = $_POST['telefono'];

                $password = $_POST['password'];

                $fecha = date("Y-m-d H:i:s");

                $servicio = $_POST['servicio'];

                $banco = $_POST['banco'];
                $cuenta = $_POST['cuenta'];
                $clabe = $_POST['clabe'];

                $tiendaspks="";

                if(isset($_POST['tiendas'])){

                    $tipos = $_POST['tiendas'];

                    foreach($tipos as $tipo){
                            
                        if(strlen($tiendaspks)==0){
                            $tiendaspks = $tipo;
                        }else{
                            $tiendaspks = $tiendaspks.','.$tipo;
                        }

                    }

                }
   
                $campos = array(
                    'FOLIO'=>$folio,
                    'IMAGEN'=>$foto,
                    'NOMBRE'=>$nombre,
                    'APATERNO'=>$apaterno,
                    'AMATERNO'=>$amaterno,
                    'ESTADO'=>$estado,
                    'MUNICIPIO'=>$municipio,
                    'COLONIA'=>$colonia,
                    'CALLE'=>$calle,
                    'NUMERO'=>$numero,
                    'TELEFONO'=>$telefono,
                    'CORREO'=>$email,
                    'PASSWORD'=>$password,
                    'SERVICIO'=>$servicio,
                    'BANCO'=>$banco,
                    'CUENTA'=>$cuenta,
                    'CLABE'=>$clabe,
                    'PK_TIENDA'=>$tiendaspks,
                     );

               $resultado = database::insertRecords("REPARTIDORES",$campos);

        
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
                    //$image->resizeToBestFit(800, 800);
                    $image->crop(200, 200, true, ImageResize::CROPCENTER);
                    $foto = 'imagenes/'.uniqid().'.jpg';
                    $image->save($foto);
                    unlink($targetPath);
                
                        $id = $_POST['id'];
                        $folio = $_POST['folio'];
                $nombre = $_POST['nombre'];
                $apaterno = $_POST['apaterno'];
                $amaterno = $_POST['amaterno'];
                $estado = $_POST['estado'];
                $municipio = $_POST['municipio'];
                $colonia = $_POST['colonia'];
                $calle = $_POST['calle'];
                $numero = $_POST['numero'];
                
                
                $email = $_POST['email'];
                $telefono = $_POST['telefono'];

                $password = $_POST['password'];

                $servicio = $_POST['servicio'];

                $banco = $_POST['banco'];
                $cuenta = $_POST['cuenta'];
                $clabe = $_POST['clabe'];
                
                $fecha = date("Y-m-d H:i:s");

                $tiendaspks="";

                if(isset($_POST['tiendas'])){

                    $tipos = $_POST['tiendas'];

                    foreach($tipos as $tipo){
                            
                        if(strlen($tiendaspks)==0){
                            $tiendaspks = $tipo;
                        }else{
                            $tiendaspks = $tiendaspks.','.$tipo;
                        }

                    }

                }
   
   
                $campos = array(
                    'FOLIO'=>$folio,
                    'IMAGEN'=>$foto,
                    'NOMBRE'=>$nombre,
                    'APATERNO'=>$apaterno,
                    'AMATERNO'=>$amaterno,
                    'ESTADO'=>$estado,
                    'MUNICIPIO'=>$municipio,
                    'COLONIA'=>$colonia,
                    'CALLE'=>$calle,
                    'NUMERO'=>$numero,
                    'TELEFONO'=>$telefono,
                    'CORREO'=>$email,
                    'PASSWORD'=>$password,
                    'SERVICIO'=>$servicio,
                    'BANCO'=>$banco,
                    'CUENTA'=>$cuenta,
                    'CLABE'=>$clabe,
                    'PK_TIENDA'=>$tiendaspks,
                     );

                    $condition = "PK=".$id;


                    $resultado = database::updateRecords("REPARTIDORES",$campos,$condition);
                
                        if($resultado){
                            
                              echo "EXITOSO";
                             
                        }else{
                              echo "ERROR";
                        }



                }
        
        
        }else{
         //NO TIENE ARCHIVO PARA MODIFICAR

       
         $id = $_POST['id'];
         $folio = $_POST['folio'];
 $nombre = $_POST['nombre'];
 $apaterno = $_POST['apaterno'];
 $amaterno = $_POST['amaterno'];
 $estado = $_POST['estado'];
 $municipio = $_POST['municipio'];
 $colonia = $_POST['colonia'];
 $calle = $_POST['calle'];
 $numero = $_POST['numero'];
 
 
 $email = $_POST['email'];
 $telefono = $_POST['telefono'];

 $password = $_POST['password'];

 $servicio = $_POST['servicio'];

 $banco = $_POST['banco'];
                $cuenta = $_POST['cuenta'];
                $clabe = $_POST['clabe'];
 
 $fecha = date("Y-m-d H:i:s");

 $tiendaspks="";

                if(isset($_POST['tiendas'])){

                    $tipos = $_POST['tiendas'];

                    foreach($tipos as $tipo){
                            
                        if(strlen($tiendaspks)==0){
                            $tiendaspks = $tipo;
                        }else{
                            $tiendaspks = $tiendaspks.','.$tipo;
                        }

                    }

                }
   

 $campos = array(
     'FOLIO'=>$folio,
     'NOMBRE'=>$nombre,
     'APATERNO'=>$apaterno,
     'AMATERNO'=>$amaterno,
     'ESTADO'=>$estado,
     'MUNICIPIO'=>$municipio,
     'COLONIA'=>$colonia,
     'CALLE'=>$calle,
     'NUMERO'=>$numero,
     'TELEFONO'=>$telefono,
     'CORREO'=>$email,
     'PASSWORD'=>$password,
     'SERVICIO'=>$servicio,
     'BANCO'=>$banco,
     'CUENTA'=>$cuenta,
     'CLABE'=>$clabe,
     'PK_TIENDA'=>$tiendaspks,
      );

   $condition = "PK=".$id;

   $resultado = database::updateRecords("REPARTIDORES",$campos,$condition);

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


?>