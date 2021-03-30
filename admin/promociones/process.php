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

       case "ACTUALIZARPEDIDO":
        ActualizarPedido();
       break;

       case "BUSCAR":
        Buscar();
        break;

       default:
       break;

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


    $someArray = json_decode($productos, true);
    //print_r($someArray);
    //echo $someArray[0]["PK"];


    $condition = "PK_PROMOCION=".$id;
    $resultado = database::deleteRecords("PROMOCION_DETALLE",$condition);

    if($resultado){

         foreach ($someArray as $key => $value) {
             //echo $value['TOTAL'];

           $total += (float) $value['TOTAL'];

                     $campos = array(
                             'PK_PROMOCION'=>$id,
                             'PK_PRODUCTO'=>$value['PK_PRODUCTO'],
                             'PRECIO'=>$value['PRECIO'],
                             'CANTIDAD'=>$value['CANTIDAD'],
                             'DETALLES'=>$value['DETALLE'],
                             'FECHA_M'=>$fecha,
                     );

                   
                   $resultado = database::insertRecords("PROMOCION_DETALLE",$campos);
         }
    
    }

    echo $respuesta;

}



function Buscar(){

    try{

    if($_POST['data']['search']==""){
    $sql = 'SELECT *,P.PK AS ID_PRODUCTO FROM PRODUCTOS P, TIENDAS T WHERE T.PK in('.$_SESSION['POLARSESSION']['PK_TIENDA'].') and P.PK_TIENDA=T.PK AND P.PROMOCION = 1 ORDER BY P.FECHA_C DESC';
    }else{
        $sql = 'SELECT *,P.PK AS ID_PRODUCTO FROM PRODUCTOS P, TIENDAS T WHERE T.PK in('.$_SESSION['POLARSESSION']['PK_TIENDA'].') and P.PK_TIENDA=T.PK AND P.PROMOCION = 1 ORDER BY P.FECHA_C DESC';
    
    }


    $rows = database::getRows($sql);

    foreach($rows as $row){
          


        echo '<div class="col-sm-6 col-xl-4">
        <div class="card mb-4 overflow-hidden">
        <div class="card-body pb-0">
        <div class="card-badges bg-danger text-white left"><span>Activo</span></div>
        <div class="d-flex justify-content-between align-items-start">
        <div class="text-center w-100">
        <a href="javascript:void(0)" class="text-dark text-big font-weight-semibold">'.utf8_encode($row['PRODUCTO']).'</a>
        </div>
        <div class="btn-group team-actions">
        <button type="button" class="btn btn-sm btn-default icon-btn borderless btn-round md-btn-flat dropdown-toggle hide-arrow" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="edit.php?id='.$row['ID_PRODUCTO'].'">Editar</a>
        <a class="dropdown-item" href="javascript:void(0)">Remover</a>
        </div>
        </div>
        </div>
        <div class="mt-3">
        <img class="card-img-top" src="'.$row['IMAGEN_PROMO'].'" width="200" height="200"  alt="'.utf8_encode($row['PRODUCTO']).'">
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
                        
    $fecha = date("Y-m-d H:i:s");

    $campos = array(
            'PROMOCION'=>0,
            'IMAGEN_PROMO'=>'NULL',
            'FECHA_M'=>$fecha,
    );

    
$condition = "PK=".$id;

$resultado = database::updateRecords("PRODUCTOS",$campos,$condition);



     $condition = "PK_PROMOCION=".$id;
     $resultado = database::deleteRecords("PROMOCION_DETALLE",$condition);


    if($resultado){
        echo "EXITOSO";
        
    }else{
        echo "ERROR";
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
                    //$image->resizeToBestFit(200, 200);
                    //$image->crop(200, 200, true, ImageResize::CROPCENTER);
                    $foto = 'imagenes/'.uniqid().'.jpg';
                    $image->save($foto);
                    $foto = URL_PROMOS.$foto;
                    unlink($targetPath);
                
                        $id = $_POST['id'];
                        
                        $fecha = date("Y-m-d H:i:s");
   
                        $campos = array(
                                'PROMOCION'=>1,
                                'IMAGEN_PROMO'=>$foto,
                                'FECHA_M'=>$fecha,
                        );
            
                        
                    $condition = "PK=".$id;

                    $resultado = database::updateRecords("PRODUCTOS",$campos,$condition);
                
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


?>