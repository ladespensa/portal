<?php
session_start();
require_once ('config.php');
require_once('include/database.php');
require_once('include/functions.php');

if(!isset($_SESSION['POLARSESSION']))
{
    header('Location: '.ROOT.DIRECTORIO.'/index.php');
    exit;
}


?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title>AC MARKET | Monitoreo de Pedidos</title>
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

<link rel="stylesheet" href="assets/css/pages/home.css">
<!--<link rel="stylesheet" href="assets/css/pace-theme-center-simple.css">-->


<style>

    /* Set the size of the div element that contains the map */
 #map {
        height: 600px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }


.badge-primary {
    background-color: blue;
    color: #fff;
}


.badge-success {
    background-color: green;
    color: #fff;
}

.badge-secondary {
    background-color: orange;
    color: #fff;
}

.badge-warning {
    background-color: cyan;
    color: #fff;
}

.badge-info {
    background-color: #A700B2;
    color: #fff;
}

.badge-danger {
    background-color: red;
    color: #fff;
}












</style>


</head>
<body>

<div class="page-loader">
<div class="bg-primary"></div>
</div>


<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include('include/header.php') ?>

<div class="sidenav bg-dark">
<div id="layout-sidenav" class=" container layout-sidenav-horizontal sidenav-horizontal flex-grow-0 bg-dark">

<?php include('include/menu.php') ?>

</div>
</div>

<div class="layout-container">

<div class="layout-content">

<div class="container flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Dashboard</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="javascript:getPedidos('1,2,3,4,5');">Todos <span class="badge badge-dark" id="todos">30</span></a></li>
<li class="breadcrumb-item"><a href="javascript:getPedidos(1);">Pendiente <span class="badge badge-success" id="pendientes">30</span></a></li>
<li class="breadcrumb-item"><a href="javascript:getPedidos('2,3,4');">En progreso <span class="badge badge-warning" id="progreso">10</span></a></li>
<li class="breadcrumb-item"><a href="javascript:getPedidos(5);">Entregado <span class="badge badge-primary" id="entregados">50</span></a></li>
<li class="breadcrumb-item"><a href="javascript:getPedidos(0);">Cancelado <span class="badge badge-danger" id="cancelado">0</span></a></li>
<!--<li class="breadcrumb-item"><a href="#">Drivers <span class="badge badge-success">14</span></a></li>-->
</ol>
</div>


<!--content-->
<div class="chat-wrapper">

<div class="card flex-grow-1 position-relative overflow-hidden">

<div class="row no-gutters h-100">
<div class="chat-sidebox col">

<div class="flex-grow-0 px-4">
<div class="media align-items-center">
<div class="media-body">
<input type="text" class="form-control chat-search my-3" placeholder="Search...">
<div class="clearfix"></div>
</div>
<a href="javascript:void(0)" class="chat-sidebox-toggler d-lg-none d-block text-muted text-large font-weight-light pl-3">&times;</a>
</div>
<hr class="border-light m-0">
</div>


<div class="flex-grow-1 position-relative">
<div class="chat-contacts list-group chat-scroll py-3" id="pedidos">


</div>

</div>
</div>
<div class="d-flex col flex-column">

<!--MAPA-->
<div id="map"></div>

</div>
</div>

</div>

</div>



<!--end content-->


</div>


<!--FOOTER-->

<?php include('include/footer.php') ?>

<!--END FOOTER-->

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

<script src="assets/js/demo.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHJ-WRaalWU2rA46g-M4Sg8ZFkcHwEKJk"></script>


<script src="assets/js/jquery.easing.1.3.js"></script>
<script src="assets/js/markerAnimate.js"></script>
<script src="assets/js/SlidingMarker.js"></script>


<script src='https://cdn.rawgit.com/admsev/jquery-play-sound/master/jquery.playSound.js'></script>

<script src="assets/js/pages/home.js"></script>



</body>
</html>
