<?php

session_start();

if(isset($_SESSION['POLARSESSION']))
{
    header('Location: home.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title>AcMarket | Administración</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="description" content="Empire is one of the unique admin template built on top of Bootstrap 4 framework. It is easy to customize, flexible code styles, well tested, modern & responsive are the topmost key factors of Empire Dashboard Template" />
<meta name="keywords" content="bootstrap admin template, dashboard template, backend panel, bootstrap 4, backend template, dashboard template, saas admin, CRM dashboard, eCommerce dashboard">
<meta name="author" content="Codedthemes" />
<link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

<link rel="stylesheet" href="assets/fonts/fontawesome.css">
<link rel="stylesheet" href="assets/fonts/ionicons.css">
<link rel="stylesheet" href="assets/fonts/linearicons.css">
<link rel="stylesheet" href="assets/fonts/open-iconic.css">
<link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
<link rel="stylesheet" href="assets/fonts/feather.css">

<link rel="stylesheet" href="assets/css/bootstrap-material.css">
<link rel="stylesheet" href="assets/css/shreerang-material.css">
<link rel="stylesheet" href="assets/css/uikit.css">

<link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">

<link rel="stylesheet" href="assets/css/pages/authentication.css">
</head>
<body>

<div class="page-loader">
<div class="bg-primary"></div>
</div>


<div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4" style="background-image: url('assets/img/bg/21.jpg');">
<div class="ui-bg-overlay bg-dark opacity-25"></div>
<div class="authentication-inner py-5">
<div class="card">
<div class="p-4 p-sm-5">

<div class="d-flex justify-content-center align-items-center pb-2 mb-4">
<div class="ui-w-60">
<div class="w-100 position-relative">
<img src="assets/img/logo-dark.png" alt="Brand Logo" class="img-fluid">
<div class="clearfix"></div>
</div>
</div>
</div>

<h5 class="text-center text-muted font-weight-normal mb-4">Ingresa con su cuenta</h5>

<form class="form-signin" action="login.php" method="POST">
<div class="form-group">
<label class="form-label">Email</label>
<input type="text" id="email" name="email" class="form-control" required autofocus>
<div class="clearfix"></div>
</div>
<div class="form-group">
<label class="form-label d-flex justify-content-between align-items-end">
<span>Password</span>
<a href="reset-password.php" class="d-block small">Olvidaste tu password?</a>
</label>
<input type="password" id="password" name="password" class="form-control" required>
<div class="clearfix"></div>
</div>
<div class="d-flex justify-content-between align-items-center m-0">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" class="custom-control-input">
<span class="custom-control-label">Remember me</span>
</label>
<button type="submit" class="btn btn-primary">Ingresar</button>
</div>
</form>

</div>
<div class="card-footer py-3 px-4 px-sm-5">
<div class="text-center text-muted">
Todos los derechos reservados
<a href="https://gesdes.com" target="_blank">GESDES</a>
</div>
</div>
</div>
</div>
</div>


<script src="assets/js/pace.js"></script>
<script src="assets/js/jquery-3.4.1.min.js"></script>
<script src="assets/libs/popper/popper.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/sidenav.js"></script>
<script src="assets/js/layout-helpers.js"></script>
<script src="assets/js/material-ripple.js"></script>

<script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>


</body>
</html>
