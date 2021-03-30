<?php

session_start();
require_once "../../../config.php";
require_once "../../../include/database.php";
require_once "../../../include/ImageResize.php";



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


     $categoria =  $_POST['data']['categoria'];
     $word = $_POST['data']['search'];

    if($_POST['data']['search']=="" && $_POST['data']['categoria']=="ALL"){
    $sql = 'SELECT * FROM CATEGORIAS ORDER BY FECHA_C DESC';
    }else if($_POST['data']['search']!="" && $_POST['data']['categoria']=="ALL"){
    
    $sql = "SELECT * FROM CATEGORIAS WHERE CLASIFICACION LIKE '%".$word."%'";
    }else if ($_POST['data']['search']=="" && $_POST['data']['categoria']!="ALL") {
        
        $sql = 'SELECT * FROM CATEGORIAS WHERE PK_TIPO_TIENDA ='.$categoria.' ORDER BY FECHA_C DESC';
    
    }else if($_POST['data']['search']!="" && $_POST['data']['categoria']!="ALL"){

        $sql = "SELECT * FROM CATEGORIAS WHERE PK_TIPO_TIENDA =".$categoria." AND CLASIFICACION LIKE '%".$word."%'";

      
    }


    $rows = database::getRows($sql);

    foreach($rows as $row){
          

        echo '<div class="col-sm-6 col-xl-3">
        <div class="card mb-3 overflow-hidden">
        <div class="card-body pb-0">
        <div class="d-flex justify-content-between align-items-start">
        <div class="text-center w-100">
        <a href="javascript:void(0)" class="text-dark text-big font-weight-semibold">'.utf8_encode($row['CLASIFICACION']).'</a>
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
        <img class="card-img-top" src="'.$row['IMAGEN'].'" width="200" height="200"  alt="'.utf8_encode($row['CLASIFICACION']).'">
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
    $resultado = database::deleteRecords("CATEGORIAS",$condition);

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
            $image->resizeToBestFit(200, 200);
            //$image->crop(200, 200, true, ImageResize::CROPCENTER);
            $foto = 'imagenes/'.uniqid().'.jpg';
            $image->save($foto);
            $foto = URL_CATEGORIAS_PRODUCTOS.$foto;
            unlink($targetPath);


                $categoria = $_POST['categoria'];
                $descripcion = $_POST['descripcion'];
                $tipo = $_POST['tipo'];
                $grupo = $_POST['grupo'];
                $fecha = date("Y-m-d H:i:s");
   
                $campos = array(
                    'CLASIFICACION'=>$categoria,
                    'IMAGEN'=>$foto,
                    'DESCRIPCION'=>$descripcion,
                    'PK_GRUPO'=>$grupo,
                    'PK_TIPO_TIENDA'=>$tipo,
                     );
        
               $resultado = database::insertRecords("CATEGORIAS",$campos);

        
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
                    $foto = URL_CATEGORIAS_PRODUCTOS.$foto;
                    unlink($targetPath);
                
                        $id = $_POST['id'];
                        $categoria = $_POST['categoria'];
                        $descripcion = $_POST['descripcion'];
                        $tipo = $_POST['tipo'];
                        $grupo = $_POST['grupo'];
                        $fecha = date("Y-m-d H:i:s");
        
                        $campos = array(
                            'CLASIFICACION'=>$categoria,
                            'IMAGEN'=>$foto,
                            'DESCRIPCION'=>$descripcion,
                            'PK_GRUPO'=>$grupo,
                            'PK_TIPO_TIENDA'=>$tipo,
                            );
            
                        
                    $condition = "PK=".$id;

                    $resultado = database::updateRecords("CATEGORIAS",$campos,$condition);
                
                        if($resultado){
                            
                              echo "EXITOSO";
                             
                        }else{
                              echo "ERROR";
                        }



                }
        
        
        }else{
         //NO TIENE ARCHIVO PARA MODIFICAR

       
       $id = $_POST['id'];
       $categoria = $_POST['categoria'];
       $descripcion = $_POST['descripcion'];
       $tipo = $_POST['tipo'];
       $grupo = $_POST['grupo'];
       $fecha = date("Y-m-d H:i:s");
        
        $campos = array(
            'CLASIFICACION'=>$categoria,
            'DESCRIPCION'=>$descripcion,
            'PK_TIPO_TIENDA'=>$tipo,
            'PK_GRUPO'=>$grupo,
            );

   $condition = "PK=".$id;

   $resultado = database::updateRecords("CATEGORIAS",$campos,$condition);

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