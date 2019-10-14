<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Highcharts Example</title>

		<style type="text/css">

		</style>
	</head>
	<body>
        <?php
        include '../../../conexion.php';
        ?>
<script src="../../code/highcharts.js"></script>
<script src="../../code/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>




		<script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Cantidades Compradas por Mes'
    },
    subtitle: {
      //  text: 'Source: WorldClimate.com'
    },
    xAxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidades Vendidas(unidades)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
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

    $sql="SELECT P.NOMBRE, SUM(DV.CANTIDAD) AS CANTIDAD FROM PRODUCTO P INNER JOIN DETALLE_VENTA DV ON P.IDPRODUCTO = DV.CODIGO_PRODUCTO GROUP BY P.NOMBRE;";

        $resultado=$conectar->consulta($sql);

        $num = mysqli_num_rows($resultado);

        if($num==0)
        {
        echo "No se encuentran productos";
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
                echo "{name: '".$filadatos['NOMBRE']."',data:[".$filadatos['CANTIDAD']."]},";
            }
        }
 ?>
 ]
});
		</script>
	</body>
</html>
