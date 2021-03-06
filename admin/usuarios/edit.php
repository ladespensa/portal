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
$sql = 'SELECT * FROM USUARIOS_PORTAL where PK='.$id;

$row = database::getRow($sql);

    if($row){
      
          $id = $row['PK'];
          $nombre = $row['NOMBRE'];
          $apellidos = $row['APELLIDOS'];
          $usuario = $row['USUARIO'];
          $password = $row['PASSWORD'];
          $rol = $row['PK_ROL'];
          $tiendaspks=$row['PK_TIENDA'];
          
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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Usuarios</h4></div>
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
      <span class="text-muted">Tiendas</span>
    </h4>
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

   
  </div>
  
  <div class="col-md-8 order-md-1">
    <h4 class="mb-3">Informaci??n General</h4>
    
      <div class="row">
        
        <div class="col-md-6 mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="apellidos">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos; ?>" required>
          </div>
          
        </div>

        
      <div class="row">
        
      <div class="col-md-5 mb-3">
          <label for="usuario">Usuario</label>
          <input type="text" class="form-control" id="usuario" name="usuario" placeholder="mail@polar" value="<?php echo $usuario; ?>" required>
        </div>

        <div class="col-md-7 mb-3">
          <label for="rol">Rol</label>
          <select name="rol" class="form-control">
                 <?php
                 
                 if($_SESSION['POLARSESSION']['PK_ROL']==1){
                  $sql = 'SELECT * FROM ROLES';
                }else{
                  $sql = 'SELECT * FROM ROLES where NOT PK=1';
                }
                 $rows = database::getRows($sql);

                 foreach($rows as $row){
                       echo '<option value="'.$row['PK'].'">'.$row['ROL'].'</option>';
                 }


                 
                 ?>
          </select>
        </div>
      </div>


      <div class="row">
        
        <div class="col-md-5 mb-3">
            <label for="password">Contrase??a</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="*******" value="<?php echo $password; ?>" required>
          </div>

          <div class="col-md-7 mb-3">
            <label for="password2">Repetir Contrase??a</label>
            <input type="password" class="form-control" id="password2" placeholder="*******" value="<?php echo $password; ?>" required>
           
          </div>
        </div>


      <hr class="mb-4">
      <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar</button>
      <a href="index.php" class="btn btn-warning btn-lg btn-block" >Cancelar</a>
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

<script src="../assets/js/pages/usuarios-editar.js"></script>

</body>
</html>
