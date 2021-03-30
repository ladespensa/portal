<?php
session_start();
require_once ('../config.php');
require_once('../include/database.php');
require_once('../include/functions.php');

if(!isset($_SESSION['POLARSESSION']))
{
    header('Location: '.ROOT.DIRECTORIO.'/index.php');
    exit;
}


$id = $_GET['id'];
$sql = 'SELECT * FROM CLIENTES where PK='.$id;
$row = database::getRow($sql);

    if($row){
      
          $folio = $row['PK'];
          $nombre = utf8_encode($row['NOMBRE']." ".$row['APELLIDOS']);
          $telefono = $row['TELEFONO'];
          $correo = $row['CORREO'];
          $fecha_nac = ($row['FECHA_NACIMIENTO']==NULL)? "":$row['FECHA_NACIMIENTO']->format('d-m-Y');
          $genero = $row['GENERO'];
          $codigo = $row['CODIGO'];
          $foto = $row['FOTO'];
          $fecha = $row['FECHA_C']->format('d-m-Y');
          
          /*OBTENER PRODUCTOS
          $sql = 'SELECT * FROM PEDIDO_DETALLE PD, PRODUCTOS P WHERE P.PK =PD.PK_PRODUCTO AND PD.PK_PEDIDO ='.$id;
          $rows = database::getRows($sql);

          foreach($rows as $row){

          $pk_tienda = $row['PK_TIENDA'];
        
          }
           */


      }

  $sqlPedidos="SELECT * from PEDIDOS WHERE PK_CLIENTE=".$id;
  //database::executeQuery("set names utf8mb4");
  $pedidosList=database::getRows($sqlPedidos);


?>

<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo NAME; header('Content-Type: text/html; charset=UTF-8'); ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="<?php echo NAME; ?>" />
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

<style>

label{ font-weight:bold; }
</style>

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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Detalle del Cliente</h4></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Cliente</a></li>
</ol>
</div>


<!--content-->

<div class="card mb-4">
<div class="card-body">
<form class="needs-validation" id="formasociados" validate>

      <input type="hidden" name="accion" value="UPDATE" />

      <input type="hidden" name="id" value="<?php echo $id; ?>" />

      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
           
          </h4>
          <ul class="list-group mb-3">

          <li class="list-group-item d-flex justify-content-between lh-condensed">
                  <div style="width:100%">

                 <img src="data:image/png;base64,<?php echo $foto; ?>" width="100%" height="100%" />   
                    
                  </div>
                 
                </li> 

                
           
          </ul>


          <button class="btn btn-danger btn-lg btn-block" type="button" disabled onclick="Eliminar(<?php echo $id; ?>)">Cancelar</button>

         
        </div>
        
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Información</h4>

          <div class="row">
              
            <div class="col-md-12 mb-3">
                
              </div>

            </div>
          
          
          <div class="row">
              
            <div class="col-md-3 mb-3">
                <label for="firstName">Folio</label>
                <input type="text" class="form-control" id="folio" name="folio" placeholder="" readonly value="<?php echo $folio ?>" required>
              </div>

              <div class="col-md-9 mb-3">
                <label for="empresa">Nombre</label>
                <p class="bd-lead"><?php echo $nombre; ?></p>
              </div>
            </div>

           
            <div class="row">

            <div class="col-md-4 mb-3">
              <label for="encargado">Genero<span class="text-muted"></span></label>
              <p class="bd-lead"><?php echo $genero; ?></p>
            </div>
              
            <div class="col-md-4 mb-3">
                <label for="email">Email</label>
                <p class="bd-lead"><a href="mailto:<?php echo $correo; ?>?Subject=Polar%20Pedido"> <?php echo $correo; ?></a></p>
              </div>

              <div class="col-md-4 mb-3">
                <label for="telefono">Telefono</label>
                <p class="bd-lead"><a href="https://wa.me/52<?php echo $telefono; ?>?texto=Polar%20Solicitud de Pedido"><?php echo $telefono; ?></a></p>
              </div>
            </div>


            <div class="row">

            <div class="col-md-4 mb-3">
              <label for="encargado">Fecha Nacimiento<span class="text-muted"></span></label>
              <p class="bd-lead"><?php echo $fecha_nac; ?></p>
            </div>
              
            <div class="col-md-4 mb-3">
                <label for="email">Codigo</label>
                <p class="bd-lead"> <?php echo $codigo; ?></p>
              </div>

              <div class="col-md-4 mb-3">
                <label for="telefono">Fecha Registro</label>
                <p class="bd-lead"><?php echo $fecha; ?></p>
              </div>
            </div>


            


              <hr class="mb-4">

              <h4 class="mb-3">Historial Pedidos</h4>

<?php
             
    if($pedidosList && count($pedidosList)>0){
      
      $table ="<table class='table table-striped table-sm'><thead class='thead-dark'> <tr><th>No.</th><th>Direcciòn</th><th>Subtotal</th><th>Envìo</th><th>Total</th><th>Codigo</th><th>Descuento</th><th>Fecha pedido</th><th>Fecha entrega</th></tr></thead><tbody>";
        $i=1;
      foreach($pedidosList as $row){

          $fecha_c = $row['FECHA_C'];

          if($fecha_c==NULL){
            $fecha_c ="";
          }else{
            $fecha_c = $row['FECHA_C']->format('d-m-Y');
          }
          
          $fecha_entrega = $row['FECHA_ENTREGA'];

          if($fecha_entrega==NULL){
            $fecha_entrega ="";
          }else{
            $fecha_entrega = $row['FECHA_ENTREGA']->format('d-m-Y');
          }

        $table.= '
                <tr>
                  <td>'.$i++.'</td>
                  <td>'.utf8_encode($row['DIRECCION']).'</td>
                  <td>'.$row['SUBTOTAL'].'</td>
                  <td>'.$row['ENVIO'].'</a></td>
                  <td>'.$row['TOTAL'].'</td>
                  <td>'.$row['CODIGO_DESCUENTO'].'</td>
                  <td>'.$row['DESCUENTO'].'</td>
                  <td>'.$fecha_c.'</td>
                  <td>'.$fecha_entrega.'</td>
                </tr>';  
      }

      $table.= '</tbody></table>';
      echo $table;
    }


?>

             

             
       

              <hr class="mb-4">
              <h4 class="mb-3">Información de Pago</h4>


             

              <hr class="mb-4">
              <h4 class="mb-3">Asistencia y Ayuda</h4>

              <hr class="mb-4">
            
          </form>
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
