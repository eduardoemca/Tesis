<?php  include '../conexion.php';  ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Cantidades vendidas de Clientes en General</title>
		<style type="text/css">
		</style>
	</head>
	<body>

        <link rel="stylesheet" type="text/css" href="../dist/bootstrap-3.3.7-dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">   

        <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		<script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Gastos Totales por Cliente'
    },
    subtitle: {
      //  text: 'Source: WorldClimate.com'
    },
    xAxis: {
        categories: [
        '',
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidades Vendidas por Cliente(Gastado)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y} Bs</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
        <?php
    $conectar= new conexion();

        $sql="  SELECT
        c.nombre AS nombre,
        v.codigo_venta  as codigo_venta,
        dv.cantidad_vendida AS cantidad_vendida,
        p.precio AS precio,

        SUM(COALESCE(dv.cantidad_vendida*p.precio)) AS totalgastado
            FROM cliente c
            INNER JOIN venta v ON c.cedula=v.cedula_cliente
            INNER JOIN detalle_venta dv ON v.codigo_venta=dv.codigo_venta
            INNER JOIN producto p ON p.codigo_producto=dv.codigo_producto

        GROUP BY c.cedula LIMIT 0,5;";

        $resultado=$conectar->consulta($sql);
        $num = mysqli_num_rows($resultado);

        if($num==0)
        {
        echo "No ha realizado ventas";
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
                echo "{name: '".$filadatos['nombre']."',data:[".$filadatos['totalgastado']."]},";
            }
        }
 ?>
 ]
});
		</script>
	</body>
</html>
