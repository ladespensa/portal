<?php
session_start();
require_once ('../../config.php');
require_once('../../include/database.php');
require_once('../../include/functions.php');

if(!isset($_SESSION['POLARSESSION']))
{
    header('Location: '.ROOT.DIRECTORIO.'/index.php');
    exit;
}


$folio = generate_string();

$tienda = $_GET['id'];

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

<link rel="stylesheet" href="../../assets/fonts/fontawesome.css">
<link rel="stylesheet" href="../../assets/fonts/ionicons.css">
<link rel="stylesheet" href="../../assets/fonts/linearicons.css">
<link rel="stylesheet" href="../../assets/fonts/open-iconic.css">
<link rel="stylesheet" href="../../assets/fonts/pe-icon-7-stroke.css">
<link rel="stylesheet" href="../../assets/fonts/feather.css">

<link rel="stylesheet" href="../../assets/css/bootstrap-material.css">
<link rel="stylesheet" href="../../assets/css/shreerang-material.css">
<link rel="stylesheet" href="../../assets/css/uikit.css">

<link rel="stylesheet" href="../../assets/libs/perfect-scrollbar/perfect-scrollbar.css">

<link rel="stylesheet" href="../../assets/css/pages/home.css">
<link rel="stylesheet" href="../../assets/css/pace-theme-center-simple.css">

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


</head>
<body>

<div class="page-loader">
<div class="bg-primary"></div>
</div>


<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include('../../include/header.php') ?>

<div class="sidenav bg-dark">
<div id="layout-sidenav" class=" container layout-sidenav-horizontal sidenav-horizontal flex-grow-0 bg-dark">

<?php include('../../include/menu.php') ?>

</div>
</div>

<div class="layout-container">

<div class="layout-content">

<div class="container flex-grow-1 container-p-y">

<div class="d-flex">
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Productos</h4></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Agregar Nuevo</a></li>
</ol>
</div>


<!--content-->

<div class="card mb-4">
<div class="card-body">
<form class="needs-validation" id="formasociados" validate>

<input type="hidden" name="accion" value="AGREGAR" />

<input type="hidden" name="tienda" id="tienda" value="<?php echo $tienda; ?>" />

<div class="row">
  <div class="col-md-4 order-md-2 mb-4">
   
    <ul class="list-group mb-3">
      <li class="list-group-item">
          <label for="precio"><b>Precio</b></label>
          <input type="number" class="form-control" min="1" max="3000" step="0.01" id="precio" style="width:150px;" name="precio" placeholder="$0.00" value="" required>
      </li>

      <li class="list-group-item">
             <label for="categoria"><b>Categoria</b></label>
             <select class="form-control" name="categoria" id="categoria">
             <?php
                
                $sql = 'SELECT * FROM CATEGORIAS ORDER BY CLASIFICACION';
                $rows = database::getRows($sql);

                foreach($rows as $row){

                      echo '<option value="'.$row['PK'].'" >'.utf8_encode($row['CLASIFICACION']).'</option>';
                }
                       

              ?>
             </select>
      </li>

      <li class="list-group-item">
      <label for="stock"><b>Stock - <span style="color:red;">ilimitado</span></b></label>
          <input type="text"  class="form-control" id="stock" style="width:150px;" name="stock" placeholder="10" value="10" required>
      </li>

      <li class="list-group-item">
      <label for="medida"><b>Medida</b></label>
             <select class="form-control" name="medida" id="medida">
             <?php
                
                $sql = 'SELECT * FROM MEDIDAS ORDER BY MEDIDA';
                $rows = database::getRows($sql);

                foreach($rows as $row){
                 
                      echo '<option value="'.$row['PK'].'" >'.$row['MEDIDA'].'</option>';
                }
                       

              ?>
             </select>
      </li>
      
    
     
    </ul>

    <a href="index.php?id=<?php echo $tienda; ?>" class="btn btn-warning btn-lg btn-block" >Cancelar</a>

   
  </div>
  
  <div class="col-md-8 order-md-1">
    <h4 class="mb-3">Información General</h4>
    
    <div class="row">
    <div class="col-md-6 mb-3">
          <label for="firstName"><b>Imagen</b></label>
          <input type="file" name="promoImage" id="promoImage" required/>
        </div>

        <div class="col-md-6 mb-3">

        <figure class="avatar avatar-120 "><img src="" id="previewPromoImage" width="100%" height="100%" alt=""> </figure>
          
        </div>
        </div>

    <div class="row">
        
      <div class="col-md-3 mb-3">
          <label for="firstName"><b>Folio</b></label>
          <input type="text" class="form-control" id="folio" name="folio" placeholder="" value="<?php echo $folio ?>" required>
        </div>

        <div class="col-md-9 mb-3">
          <label for="lastName"><b>Producto</b></label>
          <input type="text" class="form-control" id="producto" name="producto" placeholder="Nombre del Producto" value="" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="encargado"><b>Descripción</b><span class="text-muted"></span></label>
        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción del producto" rows="4"></textarea>
      </div>


      <hr class="mb-4">
      <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar Producto</button>
    </form>
        </div>

        </div>

        </div>






<!--end content-->
</div>


<!--FOOTER-->

<?php include('../../include/footer.php') ?>

<!--END FOOTER-->

</div>

</div>

</div>
</div>


<script src="../../assets/js/pace.js"></script>
<script src="../../assets/js/jquery-3.4.1.min.js"></script>
<script src="../../assets/libs/popper/popper.js"></script>
<script src="../../assets/js/bootstrap.js"></script>
<script src="../../assets/js/sidenav.js"></script>
<script src="../../assets/js/layout-helpers.js"></script>
<script src="../../assets/js/material-ripple.js"></script>
<script src="../../assets/js/jquery.loading.min.js"></script>
<script src="../../assets/js/demo.js"></script>



<script src="../../assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="../../assets/js/pages/productos-agregar.js"></script>



</body>
</html>
