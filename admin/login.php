<?php

session_start();
require_once('config.php');
require_once('include/database.php');



    
    $email = $_POST['email'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM USUARIOS_PORTAL WHERE USUARIO = '".$email."' AND PASSWORD ='".$password."' ";

    
    $row = database::getRow($sql);  

    if($row){

        $id = $row['PK1'];
        $PK_TIENDA = $row['PK_TIENDA'];
        $PK_ROL = $row['PK_ROL'];
        $nombre = utf8_encode($row['NOMBRE']." ".$row['APELLIDOS']);

        $campos = array(
            'FECHA_D'=>date("Y-m-d H:i:s")
       );

       $condicion = "PK1= ".$id;
       $resultado = database::updateRecords("USUARIOS_PORTAL",$campos,$condicion);

       
       $_SESSION['POLARSESSION'] = array('PK1'=>$id,'USUARIO'=>$email,'NOMBRE'=>$nombre,
                                         'PK_TIENDA'=>$PK_TIENDA,
                                         'PK_ROL'=>$PK_ROL);
       
       header('Location: home.php');
              exit;

    }else{

              header('Location: index.php?error=login_incorrect');
              exit;
    }



?>