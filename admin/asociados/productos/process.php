<?php

session_start();
require_once "../../config.php";
require_once "../../include/database.php";
require_once "../../include/ImageResize.php";



if(isset($_POST['data']['accion']))
{

    switch($_POST['data']['accion']){

       case "ELIMINAR":
       Eliminar();
       break;


       case "BUSCAR":
        Buscar();
        break;

       default:
       break;

    }
    
}



function Buscar(){

    try{

    if($_POST['data']['categoria']=="ALL"){ $condition=""; }else{ $condition =" AND PK_CATEGORIA=".$_POST['data']['categoria']; }

    if($_POST['data']['search']==""){
        $id=$_POST['data']['id'];
    $sql = 'SELECT * FROM PRODUCTOS WHERE PK_TIENDA = '.$id.'  '.$condition.' ORDER BY FECHA_C';
    }else{
        $id=$_POST['data']['id'];
    $word = $_POST['data']['search'];
    $sql = "SELECT * FROM PRODUCTOS WHERE PK_TIENDA = ".$id."  ".$condition." AND (PRODUCTO LIKE '%".$word."%' OR DESCRIPCION LIKE '%".$word."%')";
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
        <a href="javascript:void(0)" class="text-dark text-big font-weight-semibold">'.utf8_encode($row['PRODUCTO']).'</a>
        </div>
        <div class="btn-group team-actions">
        <button type="button" class="btn btn-sm btn-default icon-btn borderless btn-round md-btn-flat dropdown-toggle hide-arrow" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="../../promociones/edit.php?id='.$row['PK'].'&id_tienda='.$row['PK_TIENDA'].'">Promocionar</a>
        <a class="dropdown-item" href="edit.php?id='.$row['PK'].'&id_tienda='.$row['PK_TIENDA'].'">Editar</a>
        <a class="dropdown-item" href="javascript:void(0)">Remover</a>
        </div>
        </div>
        </div>
        <div class="mt-3">
        <img class="card-img-top" src="'.$row['IMAGEN'].'" width="200" height="200"  alt="'.utf8_encode($row['PRODUCTO']).'">
        </div>
        </div>
        
        <hr class="m-0">
        <div class="card-body pb-3">
        <div class="text-muted small">'.utf8_encode($row['PRODUCTO']).'</div>
        <div class="mb-3">
        <a href="javascript:void(0)" class="text-dark font-weight-semibold">'.utf8_encode($row['DESCRIPCION']).'</a>
         </div>
        <div class="row mb-3">
        <div class="col">
        <div class="text-muted small">STOCK</div>
        <div class="small font-weight-bold">'.$row['STOCK'].'</div>
        </div>
        <div class="col">
        <div class="text-muted small">PRECIO</div>
        <div class="small font-weight-bold">$'.number_format($row['PRECIO'],2).'</div>
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
    $resultado = database::deleteRecords("PRODUCTOS",$condition);

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
            $image->scale(10);
            $image->quality_jpg = 100;
            $image->resizeToBestFit(200, 200);
            //$image->crop(200, 200, true, ImageResize::CROPCENTER);
            $foto = 'imagenes/'.uniqid().'.jpg';
            $image->save($foto);
            $foto = URL_PRODUCTOS.$foto;
            
            unlink($targetPath);
        
                   
                $tienda = $_POST['tienda'];
                $folio = $_POST['folio'];
                $producto = $_POST['producto'];
                $descripcion = $_POST['descripcion'];
                
                $categoria = $_POST['categoria'];
                $stock = $_POST['stock'];
                $medida = $_POST['medida'];
                $precio = $_POST['precio'];
                
   
                $campos = array(
                    'FOLIO'=>$folio,
                    'PK_CATEGORIA'=>$categoria,
                    'PK_TIENDA'=>$tienda,
                    'PRODUCTO'=>$producto,
                    'DESCRIPCION'=>$descripcion,
                    'STOCK'=>$stock,
                    'PK_MEDIDA'=>$medida,
                    'IMAGEN'=>$foto,
                    'PRECIO'=>$precio,
                    
                     );

               $resultado = database::insertRecords("PRODUCTOS",$campos);

        
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
                    $image->resizeToBestFit(200, 200);
                    //$image->crop(200, 200, true, ImageResize::CROPCENTER);
                    $foto = 'imagenes/'.uniqid().'.jpg';
                    $image->save($foto);
                    $foto = URL_PRODUCTOS.$foto;
                    unlink($targetPath);
                
                        $id = $_POST['id'];
                        $folio = $_POST['folio'];
                        $producto = $_POST['producto'];
                        $descripcion = $_POST['descripcion'];
                        
                        $categoria = $_POST['categoria'];
                        $stock = $_POST['stock'];
                        $medida = $_POST['medida'];
                        $precio = $_POST['precio'];
                        
           
                        $campos = array(
                            'FOLIO'=>$folio,
                            'PK_CATEGORIA'=>$categoria,
                            'PRODUCTO'=>$producto,
                            'DESCRIPCION'=>$descripcion,
                            'STOCK'=>$stock,
                            'PK_MEDIDA'=>$medida,
                            'IMAGEN'=>$foto,
                            'PRECIO'=>$precio,
                            
                             );

                    $condition = "PK=".$id;

                    $resultado = database::updateRecords("PRODUCTOS",$campos,$condition);
                
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
         $producto = $_POST['producto'];
         $descripcion = $_POST['descripcion'];
         
         $categoria = $_POST['categoria'];
         $stock = $_POST['stock'];
         $medida = $_POST['medida'];
         $precio = $_POST['precio'];
         

         $campos = array(
             'FOLIO'=>$folio,
             'PK_CATEGORIA'=>$categoria,
             'PRODUCTO'=>$producto,
             'DESCRIPCION'=>$descripcion,
             'STOCK'=>$stock,
             'PK_MEDIDA'=>$medida,
             'PRECIO'=>$precio,
             
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

    }catch(\Throwable $t) {

   
        echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
    
    }

}


?>