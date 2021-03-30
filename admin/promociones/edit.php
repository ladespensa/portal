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
$sql = 'SELECT *,P.IMAGEN AS IMAGEN_PRODUCTO FROM PRODUCTOS P, TIENDAS T WHERE P.PK_TIENDA=T.PK AND P.PK='.$id;


$row = database::getRow($sql);

    if($row){
      
          $tienda = $row['NOMBRE'];
          $producto = $row['PRODUCTO'];
          $imagen_producto = $row['IMAGEN_PRODUCTO'];
          $imagen_promo = $row['IMAGEN_PROMO'];
          $pk_tienda = $row['PK_TIENDA'];
        
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

#map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
      }


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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Promocion</h4></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Editar Promoción</a></li>
</ol>
</div>


<!--content-->

<div class="card mb-4">
<div class="card-body">




<form class="needs-validation" id="formasociados" validate>

      <input type="hidden" name="accion" value="UPDATE" />

      <input type="hidden" name="id" id="id_promo" value="<?php echo $id; ?>" />

      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted"><?php echo utf8_encode($producto) ?></span>
            
          </h4>
           <div style="width:100%; text-align:center;">
          <img src="<?php echo $imagen_producto; ?>" />
          </div>
          <button class="btn btn-danger btn-lg btn-block" type="button" onclick="Eliminar(<?php echo $id; ?>)">Eliminar Promo</button>
          <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar Promo</button>
          <button class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#modify" type="button" >Relacionar Productos</button>
         
        </div>
        
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3"><?php echo utf8_encode($tienda) ?></h4>
          <hr class="mb-4">
          
          <div class="row">
              <div class="col-md-6 mb-3">
                    <label for="firstName">Imagen</label>
                    <input type="file" name="promoImage" id="promoImage" required/>
              </div>
              <div class="col-md-6 mb-3">

              <figure class="avatar avatar-120 "><img src="<?php echo $imagen_promo; ?>" id="previewPromoImage" width="100%" height="100%" alt=""> </figure>
                
              </div>
          </div>

          <div class="row">

           <div class="col-md-12 mb-3">
           <?php

$total = 0;
$sql = 'SELECT * FROM PROMOCION_DETALLE PD, PRODUCTOS P WHERE P.PK =PD.PK_PRODUCTO AND PD.PK_PROMOCION ='.$id;
$rows = database::getRows($sql);
$i=0;

//echo $sql;

foreach($rows as $row){

  $totalproducto = $row['CANTIDAD'] * number_format($row['PRECIO'],2);

  $total += $totalproducto;

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
   <h2>$'.number_format($total,2).'</h2>
 </li>';

?>
           
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


$sql = 'SELECT * FROM PROMOCION_DETALLE PD, PRODUCTOS P WHERE P.PK = PD.PK_PRODUCTO AND PD.PK_PROMOCION ='.$id;
$rows = database::getRows($sql);
            $num_prod=0;
            $i=0;
            
  $tamano = sizeof($rows);     
 if($tamano>0){
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
      
    }else{

    $i++;
    $num_prod = $i;

    $detalle = "";

    $precio = 0;
    $cantidad = 0;
    $total = 0;

     $table .= '<tr id="'.$i.'">
     <td style="width:40%;">
      <select name="producto" id="productos-'.$i.'" class="form-control" onchange="UpdateCosto(this.id)">';

      foreach($productos as $producto)
      {

        if($i==1){ 
          $selected = "selected"; $precio = number_format($producto['PRECIO'],2); $cantidad = 1; $total = number_format(($cantidad * $precio),2); 
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

<script src="../assets/js/pages/promociones-editar.js"></script>

<script>


var x = <?php echo $num_prod; ?>


var productos = new Array();

<?php
 for($i=1;$i<=$num_prod;$i++){
    echo "productos.push(".$i.");";      
 }
 
?>

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



  function _save(){

var confirmacion = confirm("¿Confirma cambiar el Pedido?");

var id_promo = $('#id_promo').val();


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
              id: id_promo,
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

</script>


</body>
</html>
