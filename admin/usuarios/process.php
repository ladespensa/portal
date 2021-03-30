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


if(isset($_POST['accion']) && $_POST['accion'] =="AGREGAR"){

    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];
     
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
        'NOMBRE'=>$nombre,
        'APELLIDOS'=>$apellidos,
        'USUARIO'=>$usuario,
        'PASSWORD'=>$password,
        'PK_ROL'=>$rol,
        'PK_TIENDA'=>$tiendaspks,
         );

   $resultado = database::insertRecords("USUARIOS_PORTAL",$campos);


    if($resultado){
          echo "EXITOSO";
         
    }else{
          echo "ERROR";
    }




}


if(isset($_POST['accion']) && $_POST['accion'] =="UPDATE"){


    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];
     
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
        'NOMBRE'=>$nombre,
        'APELLIDOS'=>$apellidos,
        'USUARIO'=>$usuario,
        'PASSWORD'=>$password,
        'PK_ROL'=>$rol,
        'PK_TIENDA'=>$tiendaspks,
         );

    $condition = "PK=".$id;

$resultado = database::updateRecords("USUARIOS_PORTAL",$campos,$condition);

if($resultado){
    echo "EXITOSO";
   
}else{
    echo "ERROR";
}


}


function Eliminar(){

    try{

    $id = $_POST['data']['id'];
    $condition = "PK=".$id;
    $resultado = database::deleteRecords("USUARIOS_PORTAL",$condition);

    if($resultado){
        echo "EXITOSO";
        
    }else{
        echo "ERROR";
    }
    }catch(\Throwable $t) {

    
        echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

    }
    
}





function Buscar(){


    try{

    
    if($_POST['data']['search']==""){

         $sql = "SELECT * FROM USUARIOS_PORTAL";

    }else{
    
        $word = $_POST['data']['search'];
   
        $sql = "SELECT * FROM USUARIOS_PORTAL WHERE NOMBRE LIKE '%".$word."%'";
    
    }

     
    $rows = database::getRows($sql);

    $i=1;
    $table = "";

    foreach($rows as $row){
    
            $table .= '
                <tr>
                  <td>'.$i++.'</td>
                  <td><a href="edit.php?id='.$row['PK'].'">'.$row['USUARIO'].'</a></td>
                  <td>'.$row['PASSWORD'].'</td>
                  <td>'.utf8_encode($row['NOMBRE']).'</td>
                  <td>'.utf8_encode($row['APELLIDOS']).'</td>
                  <td>'.$row['PK_ROL'].'</td>
                  <td>'.$row['FECHA_C']->format('d/m/Y').'</td>  
                </tr>'; 
    }

        if($table==""){ $table='<tr><td colspan="12" style="padding:50px; text-align:center; font-family:fantasy;"><h3>NO EXISTEN REGISTROS</h3></td></tr>'; }
        
         echo $table;

        }catch(\Throwable $t) {
   
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
        
        }


}

function UpdateTiendas($id){

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
