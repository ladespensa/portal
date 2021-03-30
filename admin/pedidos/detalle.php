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
$sql = 'SELECT * FROM PEDIDOS where PK='.$id;
$row = database::getRow($sql);

    if($row){

      try{
      
          $folio = $row['PK'];
          $pk_cliente = $row['PK_CLIENTE'];
          $direccion = utf8_encode($row['DIRECCION']);
          $latitud = $row['LATITUD'];
          $longitud = $row['LONGITUD'];
          $pk_repartidor = $row['PK_REPARTIDOR'];
          $pk_estatus = $row['PK_ESTATUS'];
          $subtotal = number_format($row['SUBTOTAL'],2);
          
          $subtotal_ = number_format($row['SUBTOTAL'], 2, '.', '');
           
          $envio = number_format($row['ENVIO'],2);
          $envio_ = number_format($row['ENVIO'], 2, '.', '');
          $comision_tarjeta = number_format($row['COMISION_TARJETA'],2);
          $comision_tarjeta_ = number_format($row['COMISION_TARJETA'], 2, '.', '');
          $total = number_format($row['TOTAL'],2);
          $total_ = number_format($row['TOTAL'], 2, '.', '');
          $metodo_pago = $row['METODO_PAGO'];
          $fecha = $row['FECHA_C']->format('d-m-Y');
          $hora = $row['FECHA_C']->format('H:i'); 
          $status = $row['PK_ESTATUS']; 
          $cancelado = $row['BORRADO']; 
          $pk_tienda = $row['PK_TIENDA'];

          //OBTENER PRODUCTOS
          $sql = 'SELECT * FROM PEDIDO_DETALLE PD, PRODUCTOS P WHERE P.PK =PD.PK_PRODUCTO AND PD.PK_PEDIDO ='.$id;
          
          $rows = database::getRows($sql);
          
         
          $count_detalle =0;
          foreach($rows as $row){

          $count_detalle++;
          }
          

          //OBTENER TIENDA
          $sql = 'SELECT * FROM TIENDAS where PK='.$pk_tienda;
          $row = database::getRow($sql);
         
          if($row){
            $empresa = utf8_encode($row['NOMBRE']);
            $encargado =utf8_encode($row['ENCARGADO']);
            $encargado_email =$row['CORREO'];
            $encargado_telefono =$row['TELEFONO'];
            $direccion_tienda =utf8_encode($row['DIRECCION']);
            $latitud_tienda =  $row['LATITUD'];
            $longitud_tienda =  $row['LONGITUD'];
            $imagen_tienda =$row['IMAGEN'];
            
          }
          

          //OBTENER CLIENTE
          $sql = 'SELECT * FROM CLIENTES where PK='.$pk_cliente;
          $row = database::getRow($sql);
          if($row){
            $cliente = utf8_encode($row['NOMBRE'].' '.$row['APELLIDOS']);
            $cliente_email =$row['CORREO'];
            $cliente_telefono =$row['TELEFONO'];
          }

        }catch(\Throwable $t) {
    
       
          echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";
      
      }
          

      }


?>

<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo NAME; ?></title>
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


select{
  font-size:18px !important;;
  font-weight:700 !important;
}

  label{

    font-weight:bold;
  }

/* Set the size of the div element that contains the map */
#map {
       height: 400px;  /* The height is 400 pixels */
       width: 100%;  /* The width is the width of the web page */
      }


/* Set the size of the div element that contains the map */
#map2 {
       height: 400px;  /* The height is 400 pixels */
       width: 100%;  /* The width is the width of the web page */
      }

     
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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Detalle del Pedido</h4></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Pedido</a></li>
</ol>
</div>


<!--content-->

<div class="card mb-4">
<div class="card-body">
<form class="needs-validation" id="formasociados" validate>

<input type="hidden" name="accion" value="UPDATE" />

<input type="hidden" name="id" id="id_pedido" value="<?php echo $id; ?>" />

<div class="row">
  <div class="col-md-4 order-md-2 mb-4">
  <h4 class="d-flex justify-content-between align-items-center mb-3">
      <span class="text-muted">Pedido</span>

      <?php

        if($status==6){
         echo '<span class="badge badge-pill badge-danger">CANCELADO</span>';
        }else if($status==7){
          echo '<span class="badge badge-pill badge-dark">REEMBOLSADO</span>';
        }

      ?>
      <span class="badge badge-secondary badge-pill"><?php echo  $count_detalle;  ?></span>
    </h4>
    <ul class="list-group mb-3">

    

    <li class="list-group-item d-flex justify-content-between">
              <div>
              <img src="<?php echo $imagen_tienda; ?>" width="100%" />
              
              </div>
    </li>

            <?php

            $sql = 'SELECT * FROM PEDIDO_DETALLE PD, PRODUCTOS P WHERE P.PK =PD.PK_PRODUCTO AND PD.PK_PEDIDO ='.$id;
            $rows = database::getRows($sql);
            $i=0;
            
            //echo $sql;

            foreach($rows as $row){

              $totalproducto = $row['CANTIDAD'] * number_format($row['PRECIO'],2);

              $i++;

            echo ' <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
              <h6 class="my-0">'.utf8_encode($row['PRODUCTO']).'</h6>
              <small class="text-muted">'.utf8_encode($row['DESCRIPCION']).'</small><br/>
              <small class="text-muted">'.utf8_encode($row['DETALLES']).'</small>
              
            </div>
            <span class="text-muted">'.$row['CANTIDAD'].' x $'.number_format($row['PRECIO'],2).' = '.number_format($totalproducto,2).'</span>
          </li>';

               }

               echo '<li class="list-group-item d-flex justify-content-between">
               <span>Total (MXN)</span>
               <h2>$'.$total.'</h2>
             </li>';

            ?>

        <li class="list-group-item d-flex justify-content-between lh-condensed">
        
        <select name="repartidor" id="repartidor" class="form-control" onchange="AsignarRepartidor(<?php echo $id; ?>)"  style="width:100%;" >
        
        <?php
                
                $sql = 'SELECT * FROM REPARTIDORES ORDER BY NOMBRE';
                $rows = database::getRows($sql);

                echo '<option value="NULL">Asignar Repartidor</option>';

                foreach($rows as $row){
                           
                  if($pk_repartidor==$row['PK']){ $select = "selected"; }else{ $select = ""; }

                      echo '<option value="'.$row['PK'].'" '.$select.' >'.utf8_encode($row['NOMBRE']).' '.utf8_encode($row['APATERNO']).'</option>';
                }
                       

              ?>
        </select>
        
        </li>


        <li class="list-group-item d-flex justify-content-between lh-condensed">
        
        <select name="estatus" id="estatus" class="form-control custom-select" onchange="CambiarEstatus(<?php echo $id; ?>)"  style="width:100%;" >
        
        <?php
                
                $sql = 'SELECT * FROM ESTATUS_PEDIDOS ORDER BY PK';
                $rows = database::getRows($sql);

                foreach($rows as $row){
                           
                  if($pk_estatus==$row['PK']){ $select = "selected"; }else{ $select = ""; }

                      echo '<option value="'.$row['PK'].'" '.$select.' >'.$row['ESTATUS'].'</option>';
                }
                       

              ?>
        </select>
        
        </li>
      
    
     
    </ul>


    

    <?php if($cancelado==1){  $disabled ="disabled"; }else{ $disabled =""; } ?>

    <button class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#modify" type="button" <?php echo $disabled; ?>>Modificar Pedido</button>

<!--
    <button class="btn btn-danger btn-lg btn-block" type="button" <?php echo $disabled; ?> onclick="CancelarPedido(<?php echo $id; ?>,5)">Cancelar Pedido</button>

    <button class="btn btn-primary btn-lg btn-block" type="button" <?php echo $disabled; ?> onclick="AsignarRepartidor(<?php echo $id; ?>)">Asignar Repartidor</button>

    <button class="btn btn-warning btn-lg btn-block" type="button" <?php echo $disabled; ?> onclick="CambiarEstatus(<?php echo $id; ?>)">Cambiar Estatus Pedido</button>
-->

    <?php 
    if($cancelado==1){  
   ?>
      <button class="btn btn-success btn-lg btn-block" type="button" onclick="ReanudarPedido(<?php echo $id; ?>)">Reanudar Pedido</button>
    <?php
    } ?>

  </div>
  
  <div class="col-md-8 order-md-1">
    <h4 class="mb-3">Información Negocio</h4>

       
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
          <label for="empresa">Empresa o Negocio</label>
          <p class="bd-lead"><?php echo $empresa; ?></p>
        </div>
      </div>

     
      <div class="row">

      <div class="col-md-4 mb-3">
        <label for="encargado">Encargado<span class="text-muted"></span></label>
        <p class="bd-lead"><?php echo $encargado; ?></p>
      </div>
        
      <div class="col-md-4 mb-3">
          <label for="email">Email</label>
          <p class="bd-lead"><a href="mailto:<?php echo $encargado_email; ?>?Subject=Polar%20Pedido"> <?php echo $encargado_email; ?></a></p>
        </div>

        <div class="col-md-4 mb-3">
          <label for="telefono">Telefono</label>
          <p class="bd-lead"><a href="https://wa.me/52<?php echo $encargado_telefono; ?>?texto=Polar%20Solicitud de Pedido"><?php echo $encargado_telefono; ?></a></p>
        </div>
      </div>


        <hr class="mb-4">

        <h4 class="mb-3">Dirección de la Tienda</h4>


        <div class="mb-3">
        
        <p class="bd-lead"><?php echo $direccion_tienda; ?></p>
        </div>

        <div class="mb-3">
        <div id="map"></div>
        </div>


        <hr class="mb-4">

        <h4 class="mb-3">Información del Cliente</h4>

        <div class="row">

<div class="col-md-4 mb-3">
<label for="encargado">Cliente<span class="text-muted"></span></label>
<p class="bd-lead"><?php echo $cliente; ?></p>
</div>

<div class="col-md-4 mb-3">
<label for="email">Email</label>
<p class="bd-lead"><a href="mailto:<?php echo $cliente_email; ?>?Subject=Polar%20Pedido"> <?php echo $cliente_email; ?></a></p>
</div>

<div class="col-md-4 mb-3">
<label for="telefono">Telefono</label>
<p class="bd-lead"><a href="https://wa.me/52<?php echo $cliente_telefono; ?>?texto=Polar%20Solicitud de Pedido"><?php echo $cliente_telefono; ?></a></p>
</div>
</div>

        <hr class="mb-4">
        <h4 class="mb-3">Dirección de Entrega</h4>

        <div class="mb-3">
        
        <p class="bd-lead"><?php echo $direccion; ?></p>
        </div>

        <div class="mb-3">
        <div id="map2"></div>
        </div>

        <hr class="mb-4">
        <h4 class="mb-3">Información de Pago</h4>

        <hr class="mb-4">

        <div class="row">

        <div class="col-md-9 mb-6">
       

         <table style="width:100%">
         <tr>
         <td><b>SUBTOTAL</b></td>
         <td><b>ENVÍO</b></td>
         <td><b>COMISIÓN</b></td>
         <td><b>TOTAL</b></td>
         <td><b>MÉTODO</b></td>
         </tr>

         <tr>
         <td><h4>$<?php echo $subtotal; ?></h4></td>
         <td><h4>$<?php echo $envio; ?></h4></td>
         <td><h4>$<?php echo $comision_tarjeta; ?></h4></td>
         <td><h4>$<?php echo $total; ?></h4></td>
         <td><h5><?php echo ($metodo_pago=="E")?"EFECTIVO.":"TARJETA."; ?></h5></td>
         </tr>
         
         </table>

      </div>
       
        <div class="col-md-3 mb-6">
        <button type="button" class="btn btn-danger btn-lg btn-block" data-toggle="modal" data-target="#modify-pago"  <?php echo $disabled; ?>>Modificar</button>
        </div>
      </div>


      <hr class="mb-4">
      <h4 class="mb-3">Información de Cargos</h4>
      <hr class="mb-4">
        <div class="row">

        <div class="col-md-12 mb-6">

        <table style="width:100%;">
        <tr>
        <td>ID</td>
        <td>AUTHORIZATION</td>
        <td>AMOUNT</td>
        <td>METHOD</td>
        <td>ESTATUS</td>
        </tr>

<?php

$sql = 'SELECT * FROM CARGOS WHERE PK_PEDIDO ='.$id;
$rows = database::getRows($sql);

foreach($rows as $row){

  echo '<tr>
  <td>'.$row['ID'].'</td>
  <td>'.$row['AUTHORIZATION1'].'</td>
  <td>$'.number_format($row['AMOUNT'],2).'</td>
  <td>'.$row['METHOD'].'</td>
  <td>'.$row['ESTATUS'].'</td>
  </tr>';

}

?>


        </table>
        
        </div>

        </div>


        <hr class="mb-4">
      <h4 class="mb-3">Información de Cargos Declinados</h4>
      <hr class="mb-4">
        <div class="row">

        <div class="col-md-12 mb-6">

        <table style="width:100%;">
        <tr>
        <td>CATEGORY</td>
        <td>DESCRIPTION</td>
        <td>HTTP_CODE</td>
        <td>ERROR_CODE</td>
        <td>REQUEST_ID</td>
        </tr>

<?php

$sql = 'SELECT * FROM CARGOS_ERRORES WHERE PK_PEDIDO ='.$id;
$rows = database::getRows($sql);

foreach($rows as $row){

  echo '<tr>
  <td>'.$row['category'].'</td>
  <td>'.$row['description'].'</td>
  <td>'.$row['http_code'].'</td>
  <td>'.$row['error_code'].'</td>
  <td>'.$row['request_id'].'</td>
  </tr>';

}

?>


        </table>


        </div>

        </div>
      
    </form>
        </div>

        </div>

        </div>






<!--end content-->
</div>


<!-- Modal Modificar Pedido-->
<div class="modal fade" id="modify"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:700px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar Pedido</h5>
       
      </div>
      <div class="modal-body" >

<table style="width:100%;">
<tr>
      <td style="width:40%;"><label>Producto</label></td>
      <td style="width:5%;"><label>Cant.</label></td>
      <td style="width:10%;"><label>Precio</label></td>
      <td style="width:10%;"><label>Total</label></td>
      <td style="width:30%;"><label>Detalles</label></td>
      <td style="width:5%;"></td>
      </tr>
</table>



<table style="width:100%;" id="productos"> 
<?php

$table = "";
$sql = 'SELECT * FROM PRODUCTOS WHERE PK_TIENDA ='.$pk_tienda.' ORDER BY PRODUCTO ASC';
$productos = database::getRows($sql);


$sql = 'SELECT * FROM PEDIDO_DETALLE PD, PRODUCTOS P WHERE P.PK = PD.PK_PRODUCTO AND PD.PK_PEDIDO ='.$id;
$rows = database::getRows($sql);
            $num_prod=0;
            $i=0;
            
            //echo $sql;

            foreach($rows as $row){
              
    $i++;
    $num_prod = $i;

    $detalle = utf8_encode($row['DETALLES']);

    $precio = 0;
    $cantidad = 0;
    $total = 0;

     $table .= '<tr id="'.$i.'">
     <td style="width:40%;">
      <select name="producto" id="productos-'.$i.'" class="form-control" onchange="UpdateCosto(this.id)">';

      foreach($productos as $producto)
      {

        if(trim($row['PK_PRODUCTO'])==trim($producto['PK'])){ 
          $selected = "selected"; $precio = number_format($row['PRECIO'],2); $cantidad = $row['CANTIDAD']; $total = number_format(($cantidad * $precio),2); 
        }else { $selected = "";}

        $table .= '<option value="'.$producto['PK'].'" data-precio="'.number_format($producto['PRECIO'],2).'" '.$selected.'><small>'.utf8_encode($producto['PRODUCTO']).'</small></option>';
      }
      
      
      $table .='</select>
      </td>
      <td style="width:5%;">
      <input type="number" class="form-control" min="1" max="60" step="1" id="cantidad-'.$i.'" style="width:50px;" onchange="UpdateCosto(this.id)" name="cantidad" placeholder="1" value="'.$cantidad.'" required>
      </td>
      <td style="width:10%;">
      <input type="number" class="form-control" min="1" max="16000" step="0.01" id="precio-'.$i.'"  onchange="UpdateCosto(this.id)" name="precio" placeholder="" value="'.$precio.'" required></td>
      <td style="width:10%;">
      <input type="number" class="form-control" min="1" max="16000" step="0.01" id="total-'.$i.'" name="total" placeholder="" value="'.$total.'" required></td>
      <td style="width:30%;">
      <textarea class="form-control" name="detalle" id="detalle-'.$i.'" placeholder="Detalles del producto" rows="2">'.$detalle.'</textarea>
      </td>
      <td style="width:5%;">
      <button type="button" class="btn btn-danger" onClick="_delete(this.id)" id="btn_delete-'.$i.'"><span class="ion ion-md-trash"></span></button></td>
      </tr>';
            }


      echo $table;

?>

      </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="location.reload();">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="_clone()" id="btn_add">Agregar</button>
        <button type="button" class="btn btn-success" onclick="_save()" id="btn_save">Guardar</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal Modificar Pago-->
<div class="modal fade" id="modify-pago"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:700px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar Pago</h5>
       
      </div>
      <div class="modal-body" >

        <table style="width:100%;">
        <tr>
              <td style="width:20%;"><label>SUBTOTAL</label></td>
              <td style="width:20%;"><label>ENVÍO</label></td>
              <td style="width:20%;"><label>COMISIÓN</label></td>
              <td style="width:20%;"><label>TOTAL</label></td>
              <td style="width:20%;"><label>MÉTODO</label></td>
              
              </tr>

        <tr>
        <td>
        <input type="number" class="form-control" min="1" max="26000" step="0.01" id="subtotal"  onchange="UpdateImporte(this.id)" name="subtotal" placeholder="" value="<?php echo $subtotal_; ?>" required>
        </td>
         <td>
         <input type="number" class="form-control" min="1" max="26000" step="0.01" id="envio"  onchange="UpdateImporte(this.id)" name="envio" placeholder="" value="<?php echo $envio_; ?>" required>
         </td>
         <td>
         <input type="number" class="form-control" min="1" max="26000" step="0.01" id="comision_tarjeta"  onchange="UpdateImporte(this.id)" name="comision_tarjeta" placeholder="" value="<?php echo $comision_tarjeta_; ?>" required>
         </td>
         <td>
         <input type="number" class="form-control" min="1" max="26000" step="0.01" id="total"  onchange="UpdateImporte(this.id)" name="total" placeholder="" value="<?php echo $total_; ?>" required>
         </td>
         <td>
         <select name="metodo_pago" id="metodo_pago">
         
         <option value="E" <?php echo ($metodo_pago=="E")?"selected":""; ?>>Efectivo</option>
         <option value="T" <?php echo ($metodo_pago=="T")?"selected":""; ?>>Tarjeta</option>
         
         </select>
         
         </td>
        </tr>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="location.reload();">Cerrar</button>
        <button type="button" class="btn btn-success" onclick="_saveImporte()" id="btn_save_importe">Guardar</button>
      </div>
    </div>
  </div>
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




<script type="text/javascript">


    var x = <?php echo $num_prod; ?>


    var productos = new Array();

    <?php
     for($i=1;$i<=$num_prod;$i++){
        echo "productos.push(".$i.");";      
     }
     
    ?>


function UpdateImporte(id){


  var subtotal = parseFloat($('#subtotal').val());
  var envio = parseFloat($('#envio').val());
  var comision_tarjeta = parseFloat($('#comision_tarjeta').val());
  
  var total = parseFloat(subtotal + envio + comision_tarjeta).toFixed(2);

  $('#total').val(total);

}


function _saveImporte(){

  var confirmacion = confirm("¿Confirma cambiar el Pedido?");
  var id_pedido = $('#id_pedido').val();

  var subtotal = $('#subtotal').val();
  var envio = $('#envio').val();
  var comision_tarjeta = $('#comision_tarjeta').val();
  var total = $('#total').val();
  var metodo_pago = $('#metodo_pago').val();



  var data = {
                accion: 'ACTUALIZARIMPORTE',
                id: id_pedido,
                subtotal: subtotal,
                envio: envio,
                comision_tarjeta: comision_tarjeta,
                total: total,
                metodo_pago: metodo_pago
            }
            
      if(!confirmacion){
        
      }else{
      
      Pace.start();

      $.ajax({
        type: "POST",
        cache: false,
        url: "process.php",
        data: { data: data },
        success: function (data) {

            Pace.stop();

            console.log(data);
              
            if(data=="EXITOSO"){
                
              location.reload();
               // window.location.href='index.php?accion=delete&message=Asociado Eliminado';
                
                }else if(data=="ERROR"){

                alert("ERROR al intentar cambiar el estatus del Pedido.");
              
              }
        }
      });
    }


}


function _save(){

  var confirmacion = confirm("¿Confirma cambiar el Pedido?");

  var id_pedido = $('#id_pedido').val();

 
  var text = '[';

for(var i=1;i<=productos.length;i++){

         j = i-1;
        id =  productos[j];
        producto =  $('#productos-'+id).val();
        cantidad = $('#cantidad-'+id).val();
        precio = $('#precio-'+id).val();
        total = $('#total-'+id).val();
        detalle = $('#detalle-'+id).val();
        
        text +='{ "PK_PRODUCTO":"'+producto+'" , "CANTIDAD":"'+cantidad+'", "PRECIO":"'+precio+'", "TOTAL":"'+total+'", "DETALLE":"'+detalle+'" }';
        
        if(i!=productos.length){ text +=','; }
}

  text += ']';
      

//var obj = JSON.parse(text);

//console.log("--->"+obj);

      var data = {
                accion: 'ACTUALIZARPEDIDO',
                id: id_pedido,
                productos: text
            }
            
      if(!confirmacion){
        
      }else{
      
      Pace.start();

      $.ajax({
        type: "POST",
        cache: false,
        url: "process.php",
        data: { data: data },
        success: function (data) {

            Pace.stop();

            console.log(data);
              
            if(data=="EXITOSO"){
                
              location.reload();
               // window.location.href='index.php?accion=delete&message=Asociado Eliminado';
                
                }else if(data=="ERROR"){

                alert("ERROR al intentar cambiar el estatus del Pedido.");
              
              }
        }
      });
    }
  



}


function UpdateCosto(id){

  id_arr =  id.split("-");
  id = id_arr[1];

  var precio = parseFloat($('#productos-'+id).find(':selected').data('precio'));
  var cantidad = $('#cantidad-'+id).val();

  precio = precio.toFixed(2);

  var total = parseFloat(precio * cantidad).toFixed(2);


  $('#precio-'+id).val(precio);
  $('#total-'+id).val(total);

  console.log("TOTAL>>"+total);
}


  function _delete(id){
         
         id_arr =  id.split("-");
         id = id_arr[1];


         if(productos.length == 1){

            alert("Es necesario que el pedido tenga mínimo un producto");

         }else{
         $('#'+id).remove();
         
         //console.log(productos);
         index = productos.indexOf(parseInt(id));
         //console.log("INDEX->"+index);
         productos.splice(index,1);

         //console.log(productos);
         }
         
  }


  
    function _clone(){

      //var x = document.getElementById("productos").childElementCount;
      x = x+1;

      productos.push(x);

      console.log(">>"+productos); 

      var itm = document.getElementById(productos[0]);
      var cln = itm.cloneNode(true);
      cln.id = x;
      cln.getElementsByTagName('select')[0].id = "productos-" + x;
      cln.getElementsByTagName('input')[0].id = "cantidad-" + x;
      cln.getElementsByTagName('input')[1].id = "precio-" + x;
      cln.getElementsByTagName('input')[2].id = "total-" + x;
      cln.getElementsByTagName('textarea')[0].id = "detalle-" + x;
      cln.getElementsByTagName('textarea')[0].value = "";
      cln.getElementsByTagName('button')[0].id = "btn_delete-" + x;
      
      document.getElementById("productos").appendChild(cln);
      
    }

    function CalcularTotal(element,cantidad,precio){

       id = $(element).closest("tr").attr("id");
      $('#'+id+' #total-'+id).val('345.00');
    }



   function initMap() {
                // The location of Uluru
                
                var uluru = {lat:  <?php echo $latitud_tienda; ?>, lng:  <?php echo $longitud_tienda; ?>};
                var urlentrega = {lat: <?php echo $latitud; ?>, lng: <?php echo $longitud; ?>};
                // The map, centered at Uluru
                var map = new google.maps.Map(
                    document.getElementById('map'), {zoom: 17, center: uluru});
                // The marker, positioned at Uluru
                var marker = new google.maps.Marker({position: uluru, map: map});


                var map2 = new google.maps.Map(
                    document.getElementById('map2'), {zoom: 17, center: urlentrega});
                    marker = new google.maps.Marker({position: urlentrega, map: map2});


   }


  </script>


<script src="../assets/js/pages/pedidos-detalle.js"></script>



    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHbtaNdd8CjgHXhLetYIcFbFAG0IXjwCI&callback=initMap">
    </script>



</body>
</html>
