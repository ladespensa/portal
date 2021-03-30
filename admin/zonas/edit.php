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
$sql = 'SELECT * FROM POLIGONO where PK='.$id;
$row = database::getRow($sql);

    if($row){
      
          $id = $row['PK'];
          $zona = $row['NOMBRE'];
          $version = $row['POLIGONO_VERSION'];
          
      }


      $sql = 'SELECT * FROM POLIGONO_COORDENADAS where PK_POLIGONO='.$id;
      $rows = database::getRows($sql);


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



label{
  font-weight:bold;
}



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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">ZONAS</h4></div>
</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Editar Zona</a></li>
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
    
   
    
    <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar</button>
    <hr class="mb-4">
    <button class="btn btn-danger btn-lg btn-block" type="button" onclick="Eliminar(<?php echo $id; ?>)">Eliminar</button>
    <hr class="mb-4">
    <a href="index.php" class="btn btn-warning btn-lg btn-block" >Cancelar</a>
    
     
  </div>
  
  <div class="col-md-8 order-md-1">
    <h4 class="mb-3">Información General</h4>
    
      <div class="row">
        
        <div class="col-md-6 mb-3">
            <label for="nombre">Zona</label>
            <input type="text" class="form-control" id="zona" name="zona" placeholder="Zona" value="<?php echo $zona; ?>" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="apellidos">Versión</label>
            <input type="text" class="form-control" id="version" name="version" placeholder="Versión" value="<?php echo $version; ?>" required>
          </div>
          
        </div>


        <div class="row">
        
        <div class="col-md-12 mb-3">
        <label for="nombre">Coordenadas <a href="https://gmapgis.com/" target="_blank">Construye tu Poligono aquí</a></label>
             <small>Copia y pega tus coordenadas:</small>
            <textarea style="width:100%;" name="coordenadas" rows="10" required><?php foreach($rows as $row){echo trim($row['LONGITUD']).",".trim($row['LATITUD'])."\n";}?></textarea>
            
          </div>

         
          
        </div>



        <div class="row">
        <div class="col-md-12 mb-3">

        <div id="map"></div>    
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


<script type="text/javascript">

var map="";
        var marker="";
        var myLatlng="";

        <?php
           
           $medium = round(count($rows)/2);
           $coordenadas = $rows[$medium];

        ?>

        var latitud = <?php echo $coordenadas['LATITUD']; ?>  /*-30.466697939183756;*/
        var longitud = <?php echo $coordenadas['LONGITUD']; ?> /*-63.462234249999995;*/


        function initMap() {
    // The location of Uluru

    var uluru = {lat:  latitud, lng: longitud};
    
    // The map, centered at Uluru
    map = new google.maps.Map(
        document.getElementById('map'), {zoom: 11.5, center: uluru});
    // The marker, positioned at Uluru
    //marker = new google.maps.Marker({position: uluru, map: map});


     // Definiendo las coordenadas para el path del polígono
     var triangleCoords = [
           
      <?php
               foreach($rows as $row){

                         echo "{lat:".$row['LATITUD'].",lng:".$row['LONGITUD']."},\n";

               }
              
            ?>


         /* {lat: -34.6036844, lng: -58.381559100000004}, // Brasil
          {lat: -14.235004, lng: -51.92527999999999},// Argentina
          {lat: -33.4488897, lng: -70.6692655} // Chile*/
        ];

           // Construyendo el póligono
          var poligono = new google.maps.Polygon({
          paths: triangleCoords,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.35
        });
        poligono.setMap(map);

}

</script>



<script src="../assets/js/pages/zonas.js"></script>


<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHJ-WRaalWU2rA46g-M4Sg8ZFkcHwEKJk&callback=initMap">
    </script>

</body>
</html>
