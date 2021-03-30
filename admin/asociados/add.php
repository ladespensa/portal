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

$sql = 'SELECT * FROM POLIGONO ORDER BY NOMBRE';
$poligonos = database::getRows($sql);


$folio = generate_string();



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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Tiendas</h4></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Nueva Tienda</a></li>
</ol>
</div>


<!--content-->

<div class="card mb-4">
<div class="card-body">
      <form class="needs-validation" id="formasociados" validate>

      <input type="hidden" name="accion" value="AGREGAR" />

      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Departamentos</span>
          </h4>
          <ul class="list-group mb-3">


                <?php

                $sql = 'SELECT * FROM TIPOS_TIENDAS ORDER BY TIPO';
                $rows = database::getRows($sql);
                 $i=0;
                foreach($rows as $row){
                  $i++;   
                echo ' <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="tiendas[]" id="customCheck'.$i.'" value="'.$row['PK'].'">
                            <label class="custom-control-label" for="customCheck'.$i.'">'.$row['TIPO'].'</label>
                          </div>
                        </li>';

                }
                ?>


          </ul>

          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">GEOCERCAS</span>
            
          </h4>

          <ul class="list-group mb-3">

          <li class="list-group-item d-flex justify-content-between lh-condensed">

<label  for="abre">Lunes</label>

      <select name="lunes" class="form-control" style="width:100px;">
      <?php
      

      foreach($poligonos as $row){

       
                 echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
      }
      ?>
      </select>

         
  </li>

  
  <li class="list-group-item d-flex justify-content-between lh-condensed">

<label  for="abre">Martes</label>

  <select name="martes" class="form-control" style="width:100px;">
  <?php
  

  foreach($poligonos as $row){

  
             echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
  }
  ?>
  </select>

</li>

<li class="list-group-item d-flex justify-content-between lh-condensed">

<label  for="abre">Miercoles</label>

      <select name="miercoles" class="form-control" style="width:100px;">
      <?php
      

      foreach($poligonos as $row){

      
                 echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
      }
      ?>
      </select>

      
  </li>

  <li class="list-group-item d-flex justify-content-between lh-condensed">

<label  for="abre">Jueves</label>

      <select name="jueves" class="form-control" style="width:100px;">
      <?php
      

      foreach($poligonos as $row){

        
                 echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
      }
      ?>
      </select>

         
  </li>


  <li class="list-group-item d-flex justify-content-between lh-condensed">

<label  for="abre">Viernes</label>

      <select name="viernes" class="form-control" style="width:100px;">
      <?php
      

      foreach($poligonos as $row){

        
                 echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
      }
      ?>
      </select>

      
         
  </li>

  <li class="list-group-item d-flex justify-content-between lh-condensed">

<label  for="abre">Sabado</label>

      <select name="sabado" class="form-control" style="width:100px;">
      <?php
      

      foreach($poligonos as $row){

       
                 echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
      }
      ?>
      </select>

     
         
  </li>

  <li class="list-group-item d-flex justify-content-between lh-condensed">

<label for="abre">Domingo</label>

      <select name="domingo" class="form-control" style="width:100px;">
      <?php
      

      foreach($poligonos as $row){

        
                 echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
      }
      ?>
      </select>

      
         
  </li>

          </ul>

         
            <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar Asociado</button>
            <hr class="mb-4">
          <a href="index.php" class="btn btn-warning btn-lg btn-block" >Cancelar</a>

         
        </div>
        
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Información General</h4>
          
          <div class="row">
          <div class="col-md-6 mb-3">
                <label for="firstName">Imagen</label>
                <input type="file" name="promoImage" id="promoImage" required/>
              </div>

              <div class="col-md-6 mb-3">

              <figure class="avatar avatar-120 "><img src="" id="previewPromoImage" width="100%" height="100%" alt=""> </figure>
                
              </div>
              </div>

          <div class="row">
              
            <div class="col-md-3 mb-3">
                <label for="firstName">Folio</label>
                <input type="text" class="form-control" id="folio" name="folio" placeholder="" value="<?php echo $folio ?>" required>
              </div>

              <div class="col-md-9 mb-3">
                <label for="lastName">Empresa o Negocio</label>
                <input type="text" class="form-control" id="empresa" name="empresa" placeholder="Nombre de la empresa o negocio" value="" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="encargado">Encargado<span class="text-muted"></span></label>
              <input type="text" class="form-control" name="encargado" id="encargado" placeholder="Nombre del encargado de la empresa o negocio">
            </div>


            <div class="mb-3">
              <label for="direccion">Dirección <span class="text-muted"></span></label>
              <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección de la empresa o negocio">
            </div>


            <div class="row">
              
              <div class="col-md-6 mb-3">
                  <label for="latitud">Latitud</label>
                  <input type="text" class="form-control" id="latitud" onchange="setCoordenadas()" name="latitud" placeholder="0.0" value="" required>
                </div>
  
                <div class="col-md-6 mb-3">
                  <label for="longitud">Longitud</label>
                  <input type="text" class="form-control" id="longitud" onchange="setCoordenadas()" name="longitud" placeholder="0.0" value="" required>
                </div>
              </div>


            <div class="mb-3">
              <div id="map"></div>
            </div>


            <div class="row">
              
            <div class="col-md-5 mb-3">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="mail@empresa.com" value="" required>
              </div>

              <div class="col-md-7 mb-3">
                <label for="telefono">Telefono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="" value="" required>
              </div>
            </div>


            <div class="row">
              
              <div class="col-md-5 mb-3">
                  <label for="password">Contraseña</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="*******" value="" required>
                </div>
  
                <div class="col-md-7 mb-3">
                  <label for="password2">Repetir Contraseña</label>
                  <input type="password" class="form-control" id="password2" placeholder="*******" value="" required>
                 
                </div>
              </div>

              <hr class="mb-4">

              <h4 class="mb-3">Información de Pago</h4>


            <div class="row">
              <div class="col-md-5 mb-3">
                <label for="country">Banco</label>
                <select class="custom-select d-block w-100" name="banco" id="banco" required>
                 
                <?php

                $sql = 'SELECT * FROM BANCOS ORDER BY NOMBRE';
                $rows = database::getRows($sql);

                foreach($rows as $row){
                       
                 echo '<option value="'.$row['CODE'].'">'.$row['NOMBRE'].'</option>';

                }


                ?>

                </select>
                <div class="invalid-feedback">
                  Please select a valid country.
                </div>
              </div>

              <div class="col-md-4 mb-3">
              <label for="zip">Clabe</label>
                <input type="text" class="form-control" name="clabe" id="clabe" placeholder="" required>
               
              </div>
              
              <div class="col-md-3 mb-3">
                <label for="zip">Cuenta</label>
                <input type="text" class="form-control" name="cuenta" id="cuenta" placeholder="" required>
               
              </div>
            </div>

            
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

<script src="../assets/js/pages/asociados-agregar.js"></script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHJ-WRaalWU2rA46g-M4Sg8ZFkcHwEKJk&callback=initMap">
    </script>


</body>
</html>
