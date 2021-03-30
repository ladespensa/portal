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
$sql = 'SELECT * FROM TIENDAS where PK='.$id;

$row = database::getRow($sql);

    if($row){
      
          $folio = $row['FOLIO'];
          $empresa = utf8_encode($row['NOMBRE']);
          $encargado = utf8_encode($row['ENCARGADO']);
          $direccion = utf8_encode($row['DIRECCION']);
          $imagen = $row['IMAGEN'];
          $email = $row['CORREO'];
          $telefono = $row['TELEFONO'];
          $password = $row['PASSWORD'];
          $banco = $row['BANCO'];
          $clabe = $row['CLABE'];
          $cuenta = $row['CUENTA'];

      }



?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo NAME; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="description" content="Empire is one of the unique admin template built on top of Bootstrap 4 framework. It is easy to customize, flexible code styles, well tested, modern & responsive are the topmost key factors of Empire Dashboard Template" />
<meta name="keywords" content="bootstrap admin template, dashboard template, backend panel, bootstrap 4, backend template, dashboard template, saas admin, CRM dashboard, eCommerce dashboard">
<meta name="author" content="Codedthemes" />
<link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

<link rel="stylesheet" href="../assets/fonts/fontawesome.css">
<link rel="stylesheet" href="../assets/fonts/ionicons.css">
<link rel="stylesheet" href="../assets/fonts/linearicons.css">
<link rel="stylesheet" href="../assets/fonts/open-iconic.css">
<link rel="stylesheet" href="../assets/fonts/pe-icon-7-stroke.css">
<link rel="stylesheet" href="../assets/fonts/feather.css">

<link rel="stylesheet" href="../assets/css/bootstrap-material.css">
<link rel="stylesheet" href="../assets/css/shreerang-material.css">
<link rel="stylesheet" href="../assets/css/uikit.css">

<link rel="stylesheet" href="../assets/libs/perfect-scrollbar/perfect-scrollbar.css">

<link rel="stylesheet" href="../assets/css/pages/home.css">
<link rel="stylesheet" href="../assets/css/pace-theme-center-simple.css">



</head>
<body>

<div class="page-loader">
<div class="bg-primary"></div>
</div>


<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include('../include/header.php') ?>

<div class="sidenav bg-dark">
<div id="layout-sidenav" class=" container layout-sidenav-horizontal sidenav-horizontal flex-grow-0 bg-dark">

<?php include('../include/menu.php') ?>

</div>
</div>

<div class="layout-container">

<div class="layout-content">

<div class="container flex-grow-1 container-p-y">

<div class="d-flex">
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Comprobante - Tienda</h4></div>
<div class=""><a href="comprobante_print.php?id=<?php echo $id; ?>&folio=<?php echo $foliopago; ?>" target="_blank" class="btn btn-primary btn-glow-primary"><span class="ion ion-md-print"></span>&nbsp; Imprimir</a></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Lista </a></li>
</ol>
</div>


<!--content-->

<div class="card" id="print">
<div class="card-body p-5">
<div class="row">
<div class="col-sm-6 pb-4">
<div class="media align-items-center mb-4">
<a href="index.html" class="navbar-brand app-brand demo py-0 mr-4">
<span class="app-brand-logo demo">
<img src="<?php echo $imagen; ?>" alt="Brand Logo" class="img-fluid" width="200" height="200">
</span>

</a>
</div>
<div class="mb-1 font-weight-bold text-dark ml-2"><?php echo $empresa; ?></div>
<div class="mb-1"><?php echo $direccion; ?></div>

</div>
<div class="col-sm-6 text-right pb-4">
<h6 class="text-big text-large font-weight-bold mb-3">FACTURA <?php echo $foliopago; ?></h6>
<div class="mb-1">Fecha:
<strong class="font-weight-semibold"><?php echo date("Y-m-d H:i:s"); ?></strong>
</div>
<div>
<strong class="font-weight-semibold">GESDES, Polar Inc.</strong>
</div>
</div>
</div>
<hr class="mb-4">
<div class="row">
<div class="col-sm-6 mb-4">
<div class="font-weight-bold mb-2">Factura Para:</div>
<div><?php echo $encargado; ?></div>

<div><?php echo $telefono; ?></div>
<div><?php echo $email; ?></div>
</div>
<div class="col-sm-6 mb-4">
<div class="font-weight-bold mb-2">Detalles de Pago:</div>


<?php 
                   $sql = "SELECT * FROM PEDIDOS WHERE PK_TIENDA =".$id." AND FOLIO_PAGO='".$foliopago."'";

                   $rows = database::getRows($sql);

                   $i=1;
                   $total=0;
                   $table = "";

                   foreach($rows as $row){

                     $pedido = $row['PK'];

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
                      <td class="py-3">'.$i++.'</td>
                      <td class="py-3">'.$row['FECHA_C']->format("Y-m-d H:i").'</td>
                      <td class="py-3">'.$pedido.'</td>
                      <td class="py-3">'.utf8_encode($empresa).'</td>
                      <td class="py-3">$'.number_format(round($row['SUBTOTAL'],2),2).'</td>
                      <td class="py-3">'.$metodo_pago.'</td>
                      <td class="py-3">'.$fecha_pago_tienda.'</td>
                      <td class="py-3"><b>$'.number_format(round($row['SUBTOTAL'],2),2).'</b></td>
                      
                    </tr>'; 


                    $table .= '<tr>
                          <td colspan="8">

                          <table class="table table-striped table-sm">
                                <thead>
                                    <tr style="background:white;">
                                    <th class="py-3" style="width:10%"></th>
                                    <th class="py-3" style="width:5%"></th>
                                    <th class="py-3" style="width:5%">#</th>
                                    <th class="py-3" style="width:40%">Producto</th>
                                    <th class="py-3" style="width:10%">Cantidad</th>
                                    <th class="py-3" style="width:10%">Precio</th>
                                    <th class="py-3" style="width:10%"></th>
                                    <th class="py-3" style="width:10%"></th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:12px; min-height:200px;">';


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
                                  <td>$'.number_format(round($row2['PRECIO'],2),2).'</td>
                                  <td></td>
                                  <td></td>
                                </tr>'; 
                                $j++;

                             }


                    $table .= '</tbody>
                            </table>
                          </td>  
                          </tr>';

                    $total += round($row['SUBTOTAL'],2);
                     $i++;
                   }



                  // echo $table;
              
              
              ?> 


<table>
<tbody>
<tr>
<td class="pr-3">Total:</td>
<td>
<strong>$<?php echo number_format($total,2); ?></strong>
</td>
</tr>
<tr>
 <td class="pr-3">País:</td>
<td>Mexico</td>
</tr>
<tr>
<td class="pr-3">Zona:</td>
<td>Tlaxcala - Apizaco</td>
</tr>
<tr>
<td class="pr-3">Banco:</td>
<td><?php echo $banco; ?></td>
</tr>
<tr>
<td class="pr-3">CUENTA:</td>
<td><?php echo $cuenta; ?></td>
</tr>
<tr>
<td class="pr-3">CLABE</td>
<td><?php echo $clabe; ?></td>
</tr>
</tbody>
</table>
</div>
</div>


<div class="table-responsive mb-4">
<table class="table m-0">
<thead>
<tr>


                  <th class="py-3">#</th>
                  <th class="py-3">Fecha</th>
                  <th class="py-3">Pedido</th>
                  <th class="py-3">Tienda</th>
                  <th class="py-3">Subtotal</th>
                  <th class="py-3">Metodo Pago</th>
                  <th class="py-3">Fecha Pago.</th>
                  <th class="py-3">Total</th>


</tr>
</thead>
<tbody>


<?php echo $table; ?>


<tr>
<td colspan="7" class="text-right py-3">
Subtotal:
<br> IVA(0%):
<br>
<span class="d-block text-big mt-2">Total:</span>
</td>
<td class="py-3">
<strong>$<?php echo number_format($total,2) ?></strong>
<br>
<strong>$0.00</strong>
<br>
<strong class="d-block text-big mt-2">$<?php echo number_format($total,2) ?></strong>
</td>
</tr>
</tbody>
</table>
</div>
<div class="text-muted">
<strong>Nota:</strong> Los precios expresados son en Moneda Nacional.
</div>
</div>

</div>



<!--end content-->
</div>


<!--FOOTER-->

<?php include('../include/footer.php') ?>

<!--END FOOTER-->

</div>

</div>

</div>
</div>


<script src="../assets/js/pace.js"></script>
<script src="../assets/js/jquery-3.4.1.min.js"></script>
<script src="../assets/libs/popper/popper.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/sidenav.js"></script>
<script src="../assets/js/layout-helpers.js"></script>
<script src="../assets/js/material-ripple.js"></script>
<script src="../assets/js/jquery.loading.min.js"></script>
<script src="../assets/js/demo.js"></script>
<script src="../assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>




</body>
</html>
