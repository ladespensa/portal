<?php
session_start();
require_once ('../config.php');
require_once('../include/database.php');

if(!isset($_SESSION['POLARSESSION']))
{
    header('Location: '.ROOT.DIRECTORIO.'/index.php');
    exit;
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
<link href="../plugins/bootstrap-datapicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/shreerang-material.css">
<link rel="stylesheet" href="../assets/css/uikit.css">

<link rel="stylesheet" href="../assets/libs/perfect-scrollbar/perfect-scrollbar.css">

<link rel="stylesheet" href="../assets/css/pages/pedidos.css">
<link rel="stylesheet" href="../assets/css/pace-theme-center-simple.css">



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
<div class=" flex-grow-1"><h4 class="font-weight-bold py-3 mb-0">Corte Adeudos Repartidores</h4></div>
<div class=""><a href="javascript:Buscar()" class="btn btn-primary btn-glow-primary"><span class="ion ion-md-refresh"></span>&nbsp; Actualizar</a></div>&nbsp;
<div class=""><a href="javascript:_export()" class="btn btn-primary btn-glow-primary"><span class="ion ion-md-download"></span>&nbsp; Exportar</a></div>

</div>

<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Lista</a></li>
</ol>
</div>


<!--content-->

<div class="card mb-4">
<div class="card-body">
<div class="form-row align-items-center">
<div class="col-md my-2">
<label class="form-label">Repartidores</label>
<select name="repartidor" class="form-control" id="repartidor" onchange="Buscar()">
              <?php
                      
                      $sql = 'SELECT * FROM REPARTIDORES WHERE PK_TIENDA IN ('.$_SESSION['POLARSESSION']['PK_TIENDA'].') ORDER BY NOMBRE';
                      $rows = database::getRows($sql);

                      echo '<option value="ALL">Todo</option>';

                      foreach($rows as $row){

                            echo '<option value="'.$row['PK'].'" >'.$row['NOMBRE'].'</option>';
                      }
                             

                    ?>
              </select>

</div>

<div class="col-md my-2">
<label class="form-label">Fecha</label>

<div class="input-group date">
                <input type="text" class="form-control" style="width:110px;" id="date"><span class="input-group-addon">
                <i class="fa fa-calendar" style="font-size:36px"></i>
                </span>
              </div>

</div>

<div class="col-md my-2">
<label class="form-label pb-1">Buscar</label>
<input type="text" class="form-control" onkeypress="return runScript(event)" id="search" />  
</div>

<div class="col-md col-xl-2 my-2">
<label class="form-label d-none d-md-block">&nbsp;</label>
<button type="button" class="btn btn-primary btn-block" onclick="Buscar()">Buscar</button>
</div>
</div>
</div>
</div>

<div class="card mb-4">
<div class="card-body">

<div class="table-responsive">
          
           
              
              <table class="table table-striped table-sm">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Folio</th>
                  <th>Repartidor</th>
                  <th># Pedidos</th>
                  <th>Banco</th>
                  <th>Cuenta</th>
                  <th>Clabe</th>
                  <th>Adeudo</th>
                </tr>
              </thead>
              <tbody id="grid" style="font-size:12px; min-height:200px;">
                
                
               
              </tbody>
            </table>



            <hr class="mb-4">
           

<div style="float:right">
<h5 class="mb-3">Información de Pago</h5>
            <hr class="mb-4">
<b>Adeudo:</b><span id="adeudo" style="color:red;"> $0.00</span><br/>

</div>

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
<script src="../plugins/bootstrap-datapicker/js/bootstrap-datepicker.min.js"></script>
<script src="../plugins/bootstrap-datapicker/locales/bootstrap-datepicker.es.min.js" charset="UTF-8"></script>
<script src="../plugins/excel/libs/FileSaver/FileSaver.min.js"></script>
<script src="../plugins/excel/libs/js-xlsx/xlsx.core.min.js"></script>
<script src="../plugins/excel/tableExport.min.js"></script>
<script src="../assets/js/sidenav.js"></script>
<script src="../assets/js/layout-helpers.js"></script>
<script src="../assets/js/material-ripple.js"></script>
<script src="../assets/js/jquery.loading.min.js"></script>
<script src="../assets/js/demo.js">



<script src="../assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="../assets/js/pages/tendederos-cortes.js"></script>



</body>
</html>
