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
$sql = 'SELECT * FROM REPARTIDORES where PK='.$id;

$row = database::getRow($sql);

    if($row){
      
      try{
          $folio = $row['FOLIO'];
          $nombre = utf8_encode($row['NOMBRE']);
          $apaterno = utf8_encode($row['APATERNO']);
          $amaterno = utf8_encode($row['AMATERNO']);
          $estado = utf8_encode($row['ESTADO']);
          $municipio = utf8_encode($row['MUNICIPIO']);
          $colonia = utf8_encode($row['COLONIA']);
          $calle = utf8_encode($row['CALLE']);
          $numero = $row['NUMERO'];
          $telefono = $row['TELEFONO'];
          $email = $row['CORREO'];
          $imagen = $row['IMAGEN'];
          $servicio = $row['SERVICIO'];
          $password = $row['PASSWORD'];

          $banco = $row['BANCO'];
          $cuenta = $row['CUENTA'];
          $clabe = $row['CLABE'];

          $tiendaspks=$row['PK_TIENDA'];

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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Asociados</h4></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Editar Asociado</a></li>
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
      <span class="text-muted">Tipo de Servicio</span>
      
    </h4>
    <ul class="list-group mb-3">

    <li class="list-group-item d-flex justify-content-between lh-condensed">

          <select name="servicio" class="form-control"> 
          <option value="N" <?php if(trim($servicio)=="N"){ echo 'selected';}?>>Normal</option>
          <option value="E" <?php if(trim($servicio)=="E"){ echo 'selected';}?>>Express</option>
          </select>
    
    </li>

    </ul>
    <ul class="list-group mb-3">


                <?php

                if($_SESSION['POLARSESSION']['PK_ROL']==1){
                  $sql = 'SELECT * FROM TIENDAS ORDER BY NOMBRE';
                }else{
                  $sql = 'SELECT * FROM TIENDAS WHERE PK IN ('.$_SESSION['POLARSESSION']['PK_TIENDA'].') ORDER BY NOMBRE';
                }
                $rows = database::getRows($sql);
                 $i=0;
                foreach($rows as $row){
                  $i++;   
                  if( strpos($tiendaspks, $row['PK'])!==false){ 
                    $selected = "checked";
                  }else{ 
                    $selected = "";
                  }
                echo ' <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="tiendas[]" id="customCheck'.$i.'" value="'.$row['PK'].'" '.$selected.'>
                            <label class="custom-control-label" for="customCheck'.$i.'">'.$row['NOMBRE'].'</label>
                          </div>
                        </li>';

                }
                ?>


          </ul>    

    <button class="btn btn-danger btn-lg btn-block" type="button" onclick="Eliminar(<?php echo $id; ?>)">Eliminar</button>
    <a href="index.php" class="btn btn-warning btn-lg btn-block" >Cancelar</a>
   
  </div>
  
  <div class="col-md-8 order-md-1">
    <h4 class="mb-3">Información General</h4>
    
    <div class="row">
    <div class="col-md-6 mb-3">
          <label for="firstName">Imagen</label>
          <input type="file" name="promoImage" id="promoImage"/>
        </div>

        <div class="col-md-6 mb-3">

        <figure class="avatar avatar-120 "><img src="<?php echo $imagen; ?>" id="previewPromoImage" width="100%" height="100%" alt=""> </figure>
          
        </div>
        </div>

        <div class="row">
        
        <div class="col-md-3 mb-3">
            <label for="firstName">Folio</label>
            <input type="text" class="form-control" id="folio" name="folio" placeholder="" value="<?php echo $folio ?>" required>
          </div>

          <div class="col-md-9 mb-3">
            <label for="lastName">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" required>
          </div>

        </div>


        <div class="row">
          
          <div class="col-md-6 mb-3">
              <label for="firstName">Apellido Paterno</label>
              <input type="text" class="form-control" id="apaterno" name="apaterno" placeholder="Apellido Paterno" value="<?php echo $apaterno; ?>" required>
            </div>

            <div class="col-md-6 mb-3">
              <label for="lastName">Apellido Materno</label>
              <input type="text" class="form-control" id="amaterno" name="amaterno" placeholder="Apellido Materno" value="<?php echo $amaterno; ?>" required>
            </div>
            
          </div>

          <hr class="mb-4">

         <h4 class="mb-3">Dirección</h4>

          <div class="row">
          
          <div class="col-md-3 mb-3">
              <label for="firstName">Estado</label>
              <input type="text" class="form-control" id="estado" name="estado" placeholder="" value="<?php echo $estado; ?>" required>
            </div>

            <div class="col-md-4 mb-3">
              <label for="lastName">Municipio</label>
              <input type="text" class="form-control" id="municipio" name="municipio" placeholder="" value="<?php echo $municipio; ?>" required>
            </div>

            <div class="col-md-5 mb-3">
              <label for="lastName">Colonia</label>
              <input type="text" class="form-control" id="colonia" name="colonia" placeholder="" value="<?php echo $colonia; ?>" required>
            </div>
            
          </div>



          <div class="row">
          
          <div class="col-md-9 mb-3">
              <label for="firstName">Calle</label>
              <input type="text" class="form-control" id="calle" name="calle" placeholder="" value="<?php echo $calle; ?>" required>
            </div>

            <div class="col-md-3 mb-3">
              <label for="lastName">Numero</label>
              <input type="text" class="form-control" id="numero" name="numero" placeholder="" value="<?php echo $numero; ?>" required>
            </div>

           
            
          </div>


          <hr class="mb-4">

         <h4 class="mb-3">Información de la Cuenta</h4>


        <div class="row">
          
        <div class="col-md-5 mb-3">
            <label for="email">Email o Usuario</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="mail@empresa.com" value="<?php echo $email; ?>" required>
          </div>

          <div class="col-md-7 mb-3">
            <label for="telefono">Telefono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="" value="<?php echo $telefono; ?>" required>
          </div>
        </div>


        <div class="row">
          
          <div class="col-md-5 mb-3">
              <label for="password">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="*******" value="<?php echo $password; ?>" required>
            </div>

            <div class="col-md-7 mb-3">
              <label for="password2">Repetir Contraseña</label>
              <input type="password" class="form-control" id="password2" placeholder="*******" value="<?php echo $password; ?>" required>
             
            </div>
          </div>

          <hr class="mb-4">

          <h4 class="mb-3">Información de Pago</h4>

          <div class="row">
        <div class="col-md-5 mb-3">
          <label for="country">Banco</label>
          <select class="custom-select d-block w-100" name="banco" value="<?php echo $banco; ?>" id="banco" required>
           
          <?php

          $sql = 'SELECT * FROM BANCOS ORDER BY NOMBRE';
          $rows = database::getRows($sql);

          foreach($rows as $row){


            if($banco == $row['CODE']){ $selected = "selected"; }else{ $selected = ""; }
                 
           echo '<option value="'.$row['CODE'].'" '.$selected.'>'.$row['NOMBRE'].'</option>';

          }


          ?>

          </select>
          
        </div>

        <div class="col-md-4 mb-3">
        <label for="zip">Clabe</label>
          <input type="text" class="form-control" name="clabe" id="clabe" placeholder="" value="<?php echo $clabe; ?>" required>
         
        </div>
        
        <div class="col-md-3 mb-3">
          <label for="zip">Cuenta</label>
          <input type="text" class="form-control" name="cuenta" id="cuenta" placeholder="" value="<?php echo $cuenta; ?>" required>
         
        </div>
      </div>

      <button class="btn btn-primary btn-lg btn-block" type="submit">Actualizar Asociado</button>
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

<script src="../assets/js/pages/tendederos-editar.js"></script>

</body>
</html>
