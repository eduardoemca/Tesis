<?php  include '../conexion.php';   

?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cantidades vendidas</title>
            <link rel="stylesheet" type="text/css" href="../dist/bootstrap-3.3.7-dist/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
            <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">    
<body>
    <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="../dist/amcharts4/core.js"></script>
<script src="../dist/amcharts4/charts.js"></script>
<script src="../dist/amcharts4/themes/animated.js"></script>
<script src="../dist/amcharts4/themes/kelly.js"></script> 
            <div style="display: flex;">
            <div id="chartdiv" style="margin-top: 20px;padding-right: 20px; background-color: white;"></div>
           
            <div id="chartdiv2" style="margin-top: 20px; background-color: white;"></div>
            </div>

<script type="text/javascript">
    ////////////////////////////////////// VENTAS
    am4core.useTheme(am4themes_animated);
    am4core.useTheme(am4themes_kelly);
    var chart = am4core.create("chartdiv", am4charts.XYChart3D);
    // Add data
    chart.data = [
<?php
$conectar= new conexion();
$sql1="SET lc_time_names = 'es_ES'";
    $sql2="
    SELECT 
    COALESCE(SUM(dv.cantidad_vendida),0) AS cantidad_vendida, 
    MONTHNAME(v.fecha_registro) AS mesventa,YEAR(v.fecha_registro) AS yearventa
    FROM producto p 
    INNER JOIN detalle_venta dv ON p.codigo_producto=dv.codigo_producto 
    INNER JOIN venta v ON v.codigo_venta=dv.codigo_venta 
    WHERE MONTHNAME(v.fecha_registro) = MONTHNAME(now()) AND YEAR(v.fecha_registro) = YEAR(now())
        ";

        $resultado1=$conectar->consulta($sql1);
        $resultado=$conectar->consulta($sql2);
        $num = mysqli_num_rows($resultado);

        if($num==0)
        {
            echo "  ]; ";
            echo '</script> <center><div class="alert alert-warning alert-dismissable" style="width:80%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>Aviso!!!</h4> No hay datos para mostrar
            </div> </center>  <script>';
        }
        else
        {
            while($fila = mysqli_fetch_assoc($resultado))
            {
                $enc=1;
                $datos[]=$fila;
            }

            $n=count($datos);

            for ($i=0; $i < $n; $i++)
            { 
                $filadatos=$datos[$i];
                echo "{\"mes\": '".$filadatos['mesventa']."',\"ventas\":'".$filadatos['cantidad_vendida']."',  },";
            }

        }
 ?>
    ];

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "mes";
    categoryAxis.title.text = "Ventas";

    var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Unidades";

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries3D());
    //series.columns.template.fill = am4core.color("#00ff00"); // green fill
    series.dataFields.valueY = "ventas";
    series.dataFields.categoryX = "mes";
    series.name = "Ventas";
    series.tooltipText = "{name}: [bold]{valueY}[/]";

/*    var series2 = chart.series.push(new am4charts.ColumnSeries3D());
    series2.dataFields.valueY = "ventas";
    series2.dataFields.categoryX = "mes";
    series2.name = "Ventas";
    series2.tooltipText = "{name}: [bold]{valueY}[/]";*/

    // Add cursor
    chart.cursor = new am4charts.XYCursor();

/////////////////////////////////////////////////////////////////////// CHART 2

    ////////////////////////////////////// COMPRAS
    am4core.useTheme(am4themes_animated);
    am4core.useTheme(am4themes_kelly);
    var chart = am4core.create("chartdiv2", am4charts.XYChart3D);
    // Add data
    chart.data = [
<?php
$conectar= new conexion();
$sql3="SET lc_time_names = 'es_ES'";
    $sql4="
    SELECT 
    COALESCE(SUM(dc.cantidad_comprada),0) AS cantidad_comprada, 
    MONTHNAME(c.fecha_registro) AS mescompra,YEAR(c.fecha_registro) AS yearcompra
    FROM producto p 
    INNER JOIN detalle_compra dc ON p.codigo_producto=dc.codigo_producto 
    INNER JOIN compra c ON c.codigo_compra=dc.codigo_compra 
    WHERE MONTHNAME(c.fecha_registro) = MONTHNAME(now()) AND YEAR(c.fecha_registro) = YEAR(now())
        ";


        $resultado2=$conectar->consulta($sql3);
        $resultado3=$conectar->consulta($sql4);
        $num = mysqli_num_rows($resultado3);

        if($num==0)
        {
            echo "  ]; ";
            echo '</script> <center><div class="alert alert-warning alert-dismissable" style="width:80%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>Aviso!!!</h4> No hay datos para mostrar
            </div> </center>  <script>';
        }
        else
        {
            while($fila2 = mysqli_fetch_assoc($resultado3))
            {
                $enc=1;
                $datos2[]=$fila2;
            }

            $n=count($datos2);

            for ($i=0; $i < $n; $i++)
            { 
                $filadatos=$datos2[$i];
                echo "{\"mes\": '".$filadatos['mescompra']."',\"compras\":'".$filadatos['cantidad_comprada']."',  },";
            }
        }
 ?>
    ];

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "mes";
    categoryAxis.title.text = "Compras";

    var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Unidades";

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries3D());
    series.columns.template.fill = am4core.color("#86578E"); // green fill
    series.dataFields.valueY = "compras";
    series.dataFields.categoryX = "mes";
    series.name = "Compras";
    series.tooltipText = "{name}: [bold]{valueY}[/]";

   /* var series2 = chart.series.push(new am4charts.ColumnSeries3D());
    series2.dataFields.valueY = "compras";
    series2.dataFields.categoryX = "mes";
    series2.name = "mescompra";
    series2.tooltipText = "{name}: [bold]{valueY}[/]";*/

    // Add cursor
    chart.cursor = new am4charts.XYCursor();
    	</script>
    </body>
</html>