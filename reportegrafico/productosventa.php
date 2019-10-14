<?php 
	require '../conexion.php';
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte </title>

      <link rel="stylesheet" type="text/css" href="../css/style.css">
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
#Titulo{
  text-align: center;
  position: absolute;
  padding: 50px;

}
</style>
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
<!--     AMCHART -->
    <link rel="stylesheet" href="../dist/amcharts_3.21.14/amcharts/plugins/export/export.css" type="text/css" media="all" />
    <script src="../dist/amcharts_3.21.14/amcharts/amcharts.js"></script>
    <script src="../dist/amcharts_3.21.14/amcharts/serial.js"></script>
    <script src="../dist/amcharts_3.21.14/amcharts/plugins/export/export.min.js"></script>
    <script src="../dist/amcharts_3.21.14/amcharts/plugins/export/examples/export.config.default.js"></script>
<!--         ALERTIFY -->
    <script src="../dist/alertifyjs/alertify.js"></script>
    <script src="../dist/amcharts_3.21.14/amcharts/themes/light.js"></script>
    <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    <script src="../js/reporteproductoventa.js"></script>
</head>
<body>

  <div id="Titulo" style="text-align: center;display: none;">

  </div>
<div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">
    <div class="panel-heading">
      <center>
        <h3 style="padding:0px;margin:0px; ">Reporte</h3>
      </center>
    </div>
          <div style="margin: 25px 0px 20px 0px">
            <p id="subtitulo" style="text-align: center;">Reporte de cantidad vendida del producto.</p>
          </div>

<!-- Chart code -->
<script>
var chartData = [
  <?php
    $conectar= new conexion();

  $sql="SELECT p.nombre, SUM(dv.cantidad_vendida*p.precio) AS TOTAL_GASTADO FROM producto p INNER JOIN detalle_venta dv 
        ON p.codigo_producto=dv.codigo_producto GROUP BY p.codigo_producto LIMIT 0,5;";

        $resultado=$conectar->consulta($sql);

          $num = mysqli_num_rows($resultado);

      if($num==0)
      {
      echo "  ]; ";
      echo '   </script> <center><div class="alert alert-warning alert-dismissable" style="width:80%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4>Aviso!!!</h4> No hay datos para mostrar
        </div> </center>  <script>';
        //return false;
        //$enc=0;
      }
        else
        {
            while($fila = mysqli_fetch_assoc($resultado)) {
            $enc=1;
            $datos[]=$fila;
            }
            //echo json_encode($datos);
            $n=count($datos);

            for ($i=0; $i < $n; $i++) { 
                 $filadatos=$datos[$i];
                echo "{productos: '".$filadatos['nombre']."',costo:[".$filadatos['TOTAL_GASTADO']."]}, ";
            }
            echo " ];";
        }
 ?>


/**
 * Create the chart
 */
var chart = AmCharts.makeChart("chartdiv", {
  "theme": "light",
  "type": "serial",
  "dataProvider": chartData,
  "categoryField": "productos",
  "depth3D": 20,
  "angle": 30,

  "categoryAxis": {
    "labelRotation": 90,
    "gridPosition": "start"
  },

  "valueAxes": [{
    "title": "Costo"
  }],

  "graphs": [{
    "valueField": "costo",
    "colorField": "color",
    "type": "column",
    "lineAlpha": 0.1,
    "fillAlphas": 1
  }],
  "export": {
    "enabled": true,
    "menu": []
  },
  "legend": {
    "useGraphSettings": true
  },
});
  </script>
    <!-- GRAFICA -->
    <div id="chartdiv" style="background-color: white;"></div> 

    <!-- FOOTER -->
        <div class="botonera-imprimir" style="margin:0px 0px 20px 0px">
          <button class="btn btn-danger btn-imprimir-clientes" id="btn-imprimir-clientes" onclick="exportReport();"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
        </div>

  </div>
</body>
</html>
