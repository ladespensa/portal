<?php

session_start();
require_once "../config.php";
require_once "../include/database.php";
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

       case "BUSCARCORTE":
        BuscarCorte();
        break;

        case "BUSCARADEUDOS":
        BuscarPagos();
        break;


        case "UPDATEPAGO":
            UpdatePago();
            break;

        
       default:
       break;

    }
    
}







function UpdatePago(){

    try{

    $tienda = $_POST['data']['tienda'];

    $condicion =" AND P.PK_TIENDA=".$tienda;
    $sql = "SELECT P.* FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." AND P.BORRADO = 0 ORDER BY P.FECHA_C";
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

        $sql = "SELECT *,P.PK AS PK_PEDIDO FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." AND P.BORRADO = 0 ORDER BY P.FECHA_C";

    }else{
    
        $word = $_POST['data']['search'];
   
       $sql = "SELECT *,P.PK AS PK_PEDIDO FROM PEDIDOS P, TIENDAS T WHERE P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." AND P.BORRADO = 0 ORDER BY P.FECHA_C";
    
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
                  <td>'.$row['PK_PEDIDO'].'</td>
                  <td>'.utf8_encode($row['NOMBRE']).'</td>
                  <td>$'.number_format(round($row['SUBTOTAL'],2),2).'</td>
                  <td>$'.number_format(round($row['ENVIO'],2),2).'</td>
                  <td>$'.number_format(round($row['COMISION_TARJETA'],2),2).'</td>
                  <td>$'.number_format(round($row['TOTAL'],2),2).'</td>
                  <td>'.$metodo_pago.'</td>
                  <td>$'.number_format(round($row['PAGO_EFECTIVO'],2),2).'</td>
                  <td>$'.number_format(round($row['PAGO_TIENDA'],2),2).'</td>
                  <td>'.$fecha_pago_tienda.'</td>
                  
                </tr>'; 

                $subtotal += round($row['SUBTOTAL'],2);
                $pago += round($row['PAGO_TIENDA'],2);
              
    }



        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }

        $adeudo = $subtotal - $pago;

        
         echo $table."|$".number_format($adeudo,2);

        }catch(\Throwable $t) {

   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }


}



function BuscarCorte(){

try{

    $tienda = $_POST['data']['tienda'];
    
    if($tienda == "ALL"){  $condicion =""; }else { $condicion =" AND P.PK_TIENDA=".$tienda;  }

    if($_POST['data']['search']==""){

        $sql = "SELECT T.FOLIO,COUNT(*) AS PEDIDOS,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS ADEUDO FROM PEDIDOS P, TIENDAS T WHERE T.PK IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") and P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." AND P.BORRADO = 0 GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE";

    }else{
    
        $word = $_POST['data']['search'];
   
       $sql = "SELECT T.FOLIO,COUNT(*) AS PEDIDOS,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE, SUM(P.SUBTOTAL) AS ADEUDO FROM PEDIDOS P, TIENDAS T WHERE T.PK IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") and P.PK_TIENDA = T.PK AND P.PAGO_TIENDA = 0 ".$condicion." AND P.BORRADO = 0 GROUP BY T.FOLIO,P.PK_TIENDA,T.NOMBRE,T.BANCO,T.CUENTA,T.CLABE";
    
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


function Buscar(){

    try{


    if($_POST['data']['search']=="" && $_POST['data']['categoria']=="ALL"){
    $sql = 'SELECT *, PK AS PK_TIENDA FROM TIENDAS WHERE PK IN ('.$_SESSION['POLARSESSION']['PK_TIENDA'].') ORDER BY FECHA_C DESC';
    }else if($_POST['data']['search']!="" && $_POST['data']['categoria']=="ALL"){
        $word = $_POST['data']['search'];
        $sql = "SELECT *, PK AS PK_TIENDA FROM TIENDAS WHERE PK IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") and (NOMBRE LIKE '%".$word."%' OR FOLIO LIKE '%".$word."%')";
    
    }else if($_POST['data']['search']!="" && $_POST['data']['categoria']!="ALL"){
        $categoria = $_POST['data']['categoria'];
        $word = $_POST['data']['search'];
        $sql = "SELECT *, T.PK AS PK_TIENDA  FROM TIENDAS T, TIENDAS_TIPOS TT  WHERE T.PK IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") and (T.NOMBRE LIKE '%".$word."%' OR T.FOLIO LIKE '%".$word."%') AND T.PK = TT.PK_TIENDA AND PK_TIPO =".$categoria;

    }else if($_POST['data']['search']=="" && $_POST['data']['categoria']!="ALL"){
        $categoria = $_POST['data']['categoria'];
        $sql = "SELECT *, T.PK AS PK_TIENDA FROM TIENDAS T, TIENDAS_TIPOS TT  WHERE T.PK IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND T.PK = TT.PK_TIENDA AND PK_TIPO =".$categoria;
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
        <a class="dropdown-item" href="productos/?id='.$row['PK_TIENDA'].'">Ver Productos</a>
        <a class="dropdown-item" href="edit.php?id='.$row['PK_TIENDA'].'">Editar</a>
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

                $lunes = $_POST['lunes'];
                $martes = $_POST['martes'];
                $miercoles = $_POST['miercoles'];
                $jueves = $_POST['jueves'];
                $viernes = $_POST['viernes'];
                $sabado = $_POST['sabado'];
                $domingo = $_POST['domingo'];
                
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
                    'LUNES'=>$lunes,
                    'MARTES'=>$martes,
                    'MIERCOLES'=>$miercoles,
                    'JUEVES'=>$jueves,
                    'VIERNES'=> $viernes,
                    'SABADO'=>$sabado,
                    'DOMINGO'=>$domingo,

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


                        $lunes = $_POST['lunes'];
                        $martes = $_POST['martes'];
                        $miercoles = $_POST['miercoles'];
                        $jueves = $_POST['jueves'];
                        $viernes = $_POST['viernes'];
                        $sabado = $_POST['sabado'];
                        $domingo = $_POST['domingo'];
                

                        
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
                            'LUNES'=>$lunes,
                            'MARTES'=>$martes,
                            'MIERCOLES'=>$miercoles,
                            'JUEVES'=>$jueves,
                            'VIERNES'=> $viernes,
                            'SABADO'=>$sabado,
                            'DOMINGO'=>$domingo,
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

        $lunes = $_POST['lunes'];
        $martes = $_POST['martes'];
        $miercoles = $_POST['miercoles'];
        $jueves = $_POST['jueves'];
        $viernes = $_POST['viernes'];
        $sabado = $_POST['sabado'];
        $domingo = $_POST['domingo'];


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
           'LUNES'=>$lunes,
           'MARTES'=>$martes,
           'MIERCOLES'=>$miercoles,
           'JUEVES'=>$jueves,
           'VIERNES'=> $viernes,
           'SABADO'=>$sabado,
           'DOMINGO'=>$domingo,
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