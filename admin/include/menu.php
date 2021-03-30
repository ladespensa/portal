<ul class="sidenav-inner">


<li class="sidenav-divider mb-1"></li>
<li class="sidenav-header small font-weight-semibold">UI Components</li>
 <li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/home.php" class="sidenav-link">
<i class="sidenav-icon feather icon-map"></i>
<div>Dashboard</div>
<div class="pl-1 ml-auto">
<div class="badge badge-danger">Pedidos</div>
</div>
</a>
</li>



<li class="sidenav-item"><a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-home"></i>
<div>Tiendas</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/asociados/" class="sidenav-link">
<div>Listado Tiendas y Productos</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/asociados/categorias/" class="sidenav-link">
<div>Departamentos</div>
</a>
</li>

<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/asociados/corte.php" class="sidenav-link">
<div>Corte</div>
</a>
</li>
</ul>
</li>


<!-- PRODUCTOS-->

<li class="sidenav-item"><a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-home"></i>
<div>Productos</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/codigos/" class="sidenav-link">
<div>Codigos Descuentos</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/asociados/grupos/" class="sidenav-link">
<div>Grupos</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/asociados/productos/categorias/" class="sidenav-link">
<div>Categorías</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/promociones/" class="sidenav-link">
<div>Promociones</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/productosMasPedidos/" class="sidenav-link">
<div>Màs pedidos</div>
</a>
</li>

</ul>
</li>

<!-- END PRODUCTOS-->



<li class="sidenav-item">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-layers"></i>
<div>Pedidos</div>
<div class="pl-1 ml-auto">
<div class="badge badge-primary">Monitoreo</div>
</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/pedidos/" class="sidenav-link">
<div>Listado</div>
</a>
</li>

<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/pedidos/informe.php" class="sidenav-link">
<div>Informe de Productos</div>
</a>
</li>

<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/clientes/" class="sidenav-link">
<div>Clientes</div>
</a>
</li>


</ul>
</li>



<li class="sidenav-item">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-box"></i>
<div>Repartidores</div>
</a>
<ul class="sidenav-menu">

<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/tendederos/" class="sidenav-link">
<div>Listado</div>
</a>
</li>


<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/tendederos/adeudos.php" class="sidenav-link">
<div>Adeudos</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/tendederos/corte.php" class="sidenav-link">
<div>Corte</div>
</a>
</li>

</ul>
</li>

<li class="sidenav-item">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-file"></i>
<div>Reportes</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/reportes/comprobantes_tiendas.php" class="sidenav-link">
<div>Comprobantes Tiendas</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/reportes/comprobantes_repartidores.php" class="sidenav-link">
<div>Comprobantes Repartidores</div>
</a>
</li>

<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/reportes/cupones.php" class="sidenav-link">
<div>Cupones</div>
</a>
</li>


<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/encuestas/" class="sidenav-link">
<div>Encuestas</div>
</a>
</li>

</ul>
</li>
 
<li class="sidenav-divider mb-1"></li>
<li class="sidenav-header small font-weight-semibold">Administración</li>
<li class="sidenav-item">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-clipboard"></i>
<div>Administración</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/usuarios/" class="sidenav-link">
<div>Usuarios</div>
</a>
</li>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/zonas/" class="sidenav-link">
<div>Zonas</div>
</a>
</li>
<li class="sidenav-item">
<a href="#" class="sidenav-link">
<div>Comisiones</div>
</a>
</li>
<?php if($_SESSION['POLARSESSION']['PK_ROL']==1){?>
<li class="sidenav-item">
<a href="<?php echo ROOT.DIRECTORIO; ?>/log/" class="sidenav-link">
<div>Log</div>
</a>
</li>
<?php }?>

</ul>
</li>






</ul>
