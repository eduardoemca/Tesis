<?php
  session_start();
  require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
  require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
  require '../controlador/controlador-menu.php';

  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
    exit;
        }

$login = new Menu();
  isset($con);
  $title="Menu Principal | EDDY";

?>
<!DOCTYPE html>
<html>
<head>
  <input id="tipodeusuario" type="hidden" name="tipodeusuario" value="<?php echo $tipousuario ?>">
  <input id='sessionhidden' type="hidden"  value="<?php echo $_SESSION['session_id']?>"/>
  <input id='usuariohidden' type="hidden"  value="<?php echo $userid?>"/>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>EDDY</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../dist/bootstrap-3.3.7-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../css/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="../fonts/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../css/AdminLTE.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="../css/skins/_all-skins.css">
  <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />

<style>
body {
  font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;  /*font-size: 9pt;*/
}

#chartdiv {
  width: 49%;
  height: 400px;
  padding: 0px 500px 0px 0px;
  margin-right: 2%;
}

#chartdiv2 {
  width: 49%;
  height: 400px;
}

</style>


</head>
<body class="hold-transition skin-blue sidebar-mini" >
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="menu.php" class="logo">
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>EDDY</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

       <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#">
              <img src="../img/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php  echo $usuarionombre;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <!-- IMAGEN -->
                <img src="../img/user.png" class="img-circle" alt="User Image">
              <small>
                <p style="color: white; margin-bottom: 0;padding-bottom: 0; margin-top: 5px"> <?php  echo $usuarionombre;?> </p>
                  <p style="color: white; margin-bottom: 0;padding-bottom: 0 ; margin-top: 5px"><?php  echo $tipousuario;?></p>
              </small>
              </li>
              <!-- Menu Body -->

              <!-- Menu Footer-->
              <li class="user-footer">
<!--                 <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->

                  <a href="menu.php?logout" class="btn btn-default btn-flat">Cerrar Sesion</a>
              </li>
            </ul>
          </li>
          <!-- Control Herramientas opciones -->
<!--           <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../img/user.png" class="img-circle" alt="User Image">
        <!--   <a href="#" style="margin-left: 20px"><i class="fa fa-circle text-success"></i></a>  -->
        </div>
          <div class="pull info" style="margin-left: 20%">
            <p style="color: white; margin: 0 20% 0 0"><?php  echo $usuarionombre;?></p>
            <p style="color: white; margin: 0 20% 0 0"><?php  echo $tipousuario;?></p>
          </div>
      </div>
      <!-- search form -->
<!--       <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu Principal</li>
<!-- VENTA -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-money"></i> <span>Venta</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a id="btnventa" href="#"><i class="fa fa-circle-o"></i> Registrar Ventas</a></li>
          </ul>
        </li>
<!-- COMPRA -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-shopping-cart"></i> <span>Compra</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a id="btnordencompra" href="#"><i class="fa fa-circle-o"></i>Orden De Compra</a></li>
            <li><a id="btncompra" href="#"><i class="fa fa-circle-o"></i>Compra</a></li>
          </ul>
        </li>
<!-- REGISTRO -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Registro</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a id="btncliente" href="#"><i class="fa fa-circle-o"></i> Cliente</a></li>
            <li><a id="btnproveedor" href="#"><i class="fa fa-circle-o"></i> Proveedor</a></li>
            <li><a id="btnproducto" href="#"><i class="fa fa-circle-o"></i> Producto</a></li>
          </ul>
        </li>
<!-- Inventario -->
        <li >
          <a id="btninventario" href="#">
            <i class="fa fa-file-text" ></i> <span>Inventario</span>
<!--     ETIQUETA BONITA DE NUEVO        
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span> -->
          </a>
        </li>
<!-- REPORTE -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-line-chart"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a id="btnreporteCliente" href="#"><i class="fa fa-circle-o"></i> Cliente</a></li>
            <li><a id="btnreporteProveedor" href="#"><i class="fa fa-circle-o"></i> Proveedor</a></li>
            <!-- <li><a id="btnreporteInventario" href="#"><i class="fa fa-circle-o"></i> Inventario</a></li> -->
        <!-- TOMA DE DECISION -->
        <!--     <li><a id="btnreportedecisionproductoventa" href="#"><i class="fa fa-circle-o"></i> Producto venta</a></li> -->
          </ul>
        </li>
<!-- REPORTE GRAFICOS-->
        <li>
          <a id="btnreportegrafico" href="#">
            <i class="fa fa-bar-chart"></i> <span>Reportes Graficos</span>
          </a>
        </li>
<!-- MANTENIMIENTO -->
        <li class="treeview">
          <a href="#" id="mantenimiento">
            <i class="fa fa-wrench" ></i> <span>Mantenimiento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a id="btncrearusuario" href="#"><i class="fa fa-circle-o"></i>Crear Usuario</a></li>
            <li><a id="btncambiarpass" href="#"><i class="fa fa-circle-o"></i>Cambiar Contraseña</a></li>
           <!--  <li><a id="btnrespaldo" href="#"><i class="fa fa-circle-o"></i>Respaldo</a></li> -->
            <li><a id="btnbitacora" href="#"><i class="fa fa-circle-o"></i>Bitacora y Respaldo</a></li>
          </ul>
<!-- DOCUMENTACION -->
        <li><a id="btndocumentacion" href="#"><i class="fa fa-book"></i> <span>Documentacion</span></a></li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- CONTENIDO PRINCIPAL -->

  <section class="content">
    <div id="central">
      <div class="row" style="margin-top: 0px">
    <!------------ REGISTRAR NUEVA ORDEN -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 class="ordenescantidad"></h3>

              <p>Numero de Ordenes</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a id="btnordencompra2" href="#" class="small-box-footer">Registrar Nueva Orden<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
    <!-------------- NUMERO DE VENTAS-->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 class="ventascantidad" >  <!-- <sup style="font-size: 20px">%</sup> --></h3>

              <p>Numero de ventas</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a id="btnventa2" href="#" class="small-box-footer">Registrar Nueva venta <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
    <!----------------- USUARIOS REGISTRADOS-->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 class="usuarioscantidad"> </h3>

              <p>Usuarios Registrados</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a id="btncrearusuario2" href="#" class="small-box-footer">Registrar Nuevo Usuario <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
    <!---------------------- COMPRA   -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 class="comprascantidad"></h3>

              <p>Numero de Compras</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a id="btncompra2" href="#" class="small-box-footer">Realizar una compra<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>              

            <div>
              <?php  require '../controlador/reportegrafico/reporte_del_menu1.php' ?>
            </div>

  </section>

</div>
</section>
  <!-- /.content-wrapper -->
  <footer class="main-footer" >
    <strong>Copyright &copy; 2018.</strong> Todos los derechos Reservados.
  </footer>

          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>



<!-- jQuery 3 -->
<script src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>

<!-- Bootstrap 3.3.7
<script src="../dist/Bootstrap-3.3.7-dist/js/bootstrap.min.js"></script> -->
<script src="../js/adminlte.min.js"></script>
<script type="text/javascript" src="../js/menu.js"></script>
<script src="../dist/highcharts/code/highcharts.js"></script>
<script src="../dist/highcharts/code/modules/exporting.js"></script>

</body>
</html>
