<?php

session_start();
require_once "config.php";
require_once "include/database.php";





if(isset($_POST['data']['accion']))
{

    switch($_POST['data']['accion']){

       
       case "BUSCAR":
        Buscar();
        break;

        case "BUSCARREPARTIDORES":
            BuscarRepartidores();
        break;

       default:
       break;

    }
    
}



function BuscarRepartidores(){

    

    try{

        $fecha = date("Y-m-d");
        //-1 dia
        //$fecha = date('Y-m-d', strtotime($fecha . ' -2 day'));
    
        
        //$sql = "SELECT PK,(SELECT NOMBRE FROM TIENDAS WHERE PK=PK_TIENDA) AS TIENDA,(SELECT IMAGEN FROM TIENDAS WHERE PK=PK_TIENDA) AS IMAGEN_TIENDA,PK_CLIENTE,DIRECCION,LATITUD,LONGITUD,PK_REPARTIDOR,PK_ESTATUS FROM PEDIDOS WHERE FECHA_C >= '".$fecha." 12:00:00 AM' AND  FECHA_C <= '".$fecha." 11:59:59 PM' ".$condicion." ORDER BY FECHA_C DESC";
        $sql = "SELECT P.PK,P.PK_CLIENTE,P.PK_ESTATUS,P.DIRECCION,CONCAT(R.NOMBRE,'',R.APATERNO) AS REPARTIDOR,R.LATITUD,R.LONGITUD,P.FECHA_C FROM PEDIDOS P, REPARTIDORES R WHERE P.PK_REPARTIDOR = R.PK AND P.PK_ESTATUS IN (3,4) AND P.PK_REPARTIDOR IS NOT NULL AND P.FECHA_C >= '".$fecha." 12:00:00 AM' AND  P.FECHA_C <= '".$fecha." 11:59:59 PM' AND P.PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].")";
       
        
        $rows = database::getRows($sql);
        
        $list = array();
        $i=0;
    
        foreach($rows as $row){
    
            $list[] = array('PK' => $row['PK'], 'PK_CLIENTE' => $row['PK_CLIENTE'],'DIRECCION' => utf8_encode($row['DIRECCION']),'LATITUD' => $row['LATITUD'],'LONGITUD' => $row['LONGITUD'],'REPARTIDOR' => $row['REPARTIDOR'],'PK_ESTATUS' => $row['PK_ESTATUS']);
    
            
        }
    
        echo json_encode($list);
    
        }catch(\Throwable $t) {
    
            
                echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
    
        }

}






function Buscar(){

    try{

    $fecha = date("Y-m-d");
    //-1 dia
    //$fecha = date('Y-m-d', strtotime($fecha . ' -2 day'));

    $status = $_POST['data']['status'];

    $condicion = ' AND PK_ESTATUS IN ('.$status.')';
    
    $sql = "SELECT (SELECT COUNT(*) FROM PEDIDOS WHERE PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND FECHA_C >= '".$fecha." 12:00:00 AM' AND  FECHA_C <= '".$fecha." 11:59:59 PM') AS TODOS, (SELECT COUNT(*) FROM PEDIDOS WHERE PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND FECHA_C >= '".$fecha." 12:00:00 AM' AND  FECHA_C <= '".$fecha." 11:59:59 PM' AND  PK_ESTATUS IN (1)) AS PENDIENTES,(SELECT COUNT(*) FROM PEDIDOS WHERE PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND FECHA_C >= '".$fecha." 12:00:00 AM' AND  FECHA_C <= '".$fecha." 11:59:59 PM' AND  PK_ESTATUS IN (2,3,4)) AS PROGRESO,(SELECT COUNT(*) FROM PEDIDOS WHERE PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND FECHA_C >= '".$fecha." 12:00:00 AM' AND  FECHA_C <= '".$fecha." 11:59:59 PM' AND  PK_ESTATUS IN (5)) AS ENTREGADOS,(SELECT COUNT(*) FROM PEDIDOS WHERE PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") AND FECHA_C >= '".$fecha." 12:00:00 AM' AND  FECHA_C <= '".$fecha." 11:59:59 PM' AND  PK_ESTATUS IN (6)) AS CANCELADOS,PK,(SELECT NOMBRE FROM TIENDAS WHERE PK=PK_TIENDA) AS TIENDA,(SELECT IMAGEN FROM TIENDAS WHERE PK=PK_TIENDA) AS IMAGEN_TIENDA,PK_CLIENTE,DIRECCION,LATITUD,LONGITUD,PK_REPARTIDOR,PK_ESTATUS FROM PEDIDOS WHERE FECHA_C >= '".$fecha." 12:00:00 AM' AND  FECHA_C <= '".$fecha." 11:59:59 PM' and PK_TIENDA IN (".$_SESSION['POLARSESSION']['PK_TIENDA'].") ".$condicion." ORDER BY FECHA_C DESC";
    
    $rows = database::getRows($sql);
    
    $list = array();
    $i=0;

    foreach($rows as $row){

        $list[] = array('PK' => $row['PK'], 'PK_CLIENTE' => $row['PK_CLIENTE'],'DIRECCION' => utf8_encode($row['DIRECCION']),'LATITUD' => $row['LATITUD'],'LONGITUD' => $row['LONGITUD'],'PK_REPARTIDOR' => $row['PK_REPARTIDOR'],'PK_ESTATUS' => $row['PK_ESTATUS'],'TIENDA' => utf8_encode($row['TIENDA']),'IMAGEN_TIENDA' => $row['IMAGEN_TIENDA'],'TODOS' => $row['TODOS'],'PENDIENTES' => $row['PENDIENTES'],'PROGRESO' => $row['PROGRESO'],'ENTREGADOS' => $row['ENTREGADOS'],'CANCELADOS' => $row['CANCELADOS']);

        
    }

    echo json_encode($list);

    }catch(\Throwable $t) {

        
            echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";

    }
}



?>