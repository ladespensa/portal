<?php
session_start();
require_once ('../config.php');
require_once('../include/database.php');

if(!isset($_SESSION['POLARSESSION']))
{
    header('Location: '.ROOT.DIRECTORIO.'/index.php');
    exit;
}


$id = $_GET['id'];
$foliopago = $_GET['folio'];
$sql = 'SELECT * FROM REPARTIDORES where PK='.$id;

$row = database::getRow($sql);

    if($row){
      
          $folio = $row['FOLIO'];
          $nombre = $row['NOMBRE'];
          $apaterno = $row['APATERNO'];
          $amaterno = $row['AMATERNO'];
          $imagen = $row['IMAGEN'];
          $email = $row['CORREO'];
          $telefono = $row['TELEFONO'];
          $password = $row['PASSWORD'];
          $banco = $row['BANCO'];
          $clabe = $row['CLABE'];
          $cuenta = $row['CUENTA'];

      }



?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo NAME; ?></title>

    

    <!-- Bootstrap core CSS -->
<link href="../plugins/bootstrap-4.4.1-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../plugins/bootstrap-datapicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="../plugins/fontawesome/css/fontawesome.min.css" rel="stylesheet">

    <!-- Favicons -->
<link rel="apple-touch-icon" href="/docs/4.4/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/4.4/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/4.4/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="mask-icon" href="/docs/4.4/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
<link rel="icon" href="/docs/4.4/assets/img/favicons/favicon.ico">

<meta name="theme-color" content="#563d7c">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="../css/custom.css" rel="stylesheet">
  </head>
  <body>
    

  

<main role="main" class="container">
 
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">COMPROBANTE DE PAGO <small><?php echo $foliopago; ?></small></h1><br/>
            <h5><?php echo utf8_encode($nombre.' '.$apaterno.' '.$amaterno); ?> </h5>
            <small></small>
            <div class="btn-toolbar mb-2 mb-md-0">
              
              <div class="btn-group mr-2">

              
              <img src="<?php echo $imagen; ?>"/>
                
              </div>


            </div>
          </div>


          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fecha</th>
                  <th>Pedido</th>
                  <th>Tienda</th>
                  <th>Monto</th>
                  <th>Deposito</th>
                  <th>Costo Envio</th>
                  <th>Metodo Pago</th>
                  <th>Comision</th>
                  <th>Fecha Pago.</th>
                </tr>
              </thead>
              <tbody id="grid" style="font-size:12px; min-height:200px;">
              <?php 
                   $sql = "SELECT *, (SELECT NOMBRE FROM TIENDAS WHERE PK=PK_TIENDA) AS TIENDA  FROM PEDIDOS WHERE PK_REPARTIDOR =".$id." AND FOLIO_PAGO_REPARTIDOR='".$foliopago."'";

                   $rows = database::getRows($sql);

                   $i=1;
                   $total=0;
                   $table = "";

                   foreach($rows as $row){

                    $pedido = $row['PK'];
                    $fecha_pago_repartidor ="";
                    if($row['FECHA_PAGO_REPARTIDOR']!=NULL){
                      $fecha_pago_repartidor = $row['FECHA_PAGO_REPARTIDOR']->format("Y-m-d H:i");
                    }

                    $metodo_pago = "EFECTIVO";
                        if($row['METODO_PAGO']=="T"){
                        $metodo_pago = "TARJETA";
                        }

                    $table .= '
                    <tr>
                      <td>'.$i++.'</td>
                      <td>'.$row['FECHA_C']->format("Y-m-d H:i").'</td>
                      <td>'.$pedido.'</td>
                      <td>'.utf8_encode($row['TIENDA']).'</td>
                      <td>$'.round($row['TOTAL'],2).'</td>
                      <td><b>$'.round($row['PAGO_EFECTIVO'],2).'</b></td>
                      <td>$'.round($row['ENVIO'],2).'</td>
                      <td>'.$metodo_pago.'</td>
                      <td><b>$'.round($row['COMISION_REPARTIDOR'],2).'</b></td>
                      <td>'.$fecha_pago_repartidor.'</td>
                      
                    </tr>'; 


                    $table .= '<tr>
                          <td colspan="8">

                          <table class="table table-striped table-sm">
                                <thead>
                                    <tr style="background:white;">
                                    <th></th>
                                    <th></th>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th></th>
                                    <th></th>
                                    </tr>
                                </thead>
                                <tbody id="grid" style="font-size:12px; min-height:200px;">';


                             $sql2 =  "select * from PEDIDO_DETALLE PD, PRODUCTOS P WHERE PD.PK_PRODUCTO = P.PK AND PD.PK_PEDIDO = ".$pedido;
                             $rows2 = database::getRows($sql2);
                             $j=1;
                             foreach($rows2 as $row2){

                                $table .= '
                                <tr style="background:white;">
                                  <td></td>
                                  <td></td>
                                  <td>'.$j++.'</td>
                                  <td>'.utf8_encode($row2['PRODUCTO']).'</td>
                                  <td>'.$row2['CANTIDAD'].'</td>
                                  <td>$'.round($row2['PRECIO'],2).'</td>
                                  <td></td>
                                  <td></td>
                                </tr>'; 
                                $j++;

                             }


                    $table .= '</tbody>
                            </table>
                          </td>  
                          </tr>';

                    $total += round($row['COMISION_REPARTIDOR'],2);
                     $i++;
                   }



                   echo $table;
              
              
              
              ?> 
              </tbody>
            </table>



            <hr class="mb-4">
           

            <div style="float:right">
            <h5 class="mb-3">Pago</h5>
                        <hr class="mb-4">
            <b>Total:</b><span id="adeudo" style="color:blue; font-weight:bold;"> $<?php echo $total; ?></span><br/>

            </div>
            </div>
</main>


<footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#"><?php echo date("Y-m-d H:i:s"); ?></a>
        </p>
        <p>Polar Powered by <a href="https://gesdes.com" target="_blank">GESDES</a> © 2020 - 2022 llevamos el placer a tu hogar!</p>
      </div>
    </footer>

<script src="../plugins/jquery/jquery-3.4.1.min.js"></script>
    
    <script src="../js/popper.min.js"></script>
    <script src="../plugins/bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
    <script src="../plugins/bootstrap-datapicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-datapicker/locales/bootstrap-datepicker.es.min.js" charset="UTF-8"></script>
    <script src="../js/holder.min.js"></script>
    <script src="../js/jquery.loading.min.js"></script>


    <script type="text/javascript" src="../plugins/excel/libs/FileSaver/FileSaver.min.js"></script>
    <script type="text/javascript" src="../plugins/excel/libs/js-xlsx/xlsx.core.min.js"></script>
    <script type="text/javascript" src="../plugins/excel/tableExport.min.js"></script>

 <script>


$(document).ready(function(){

  
         
});



</script>

</html>
