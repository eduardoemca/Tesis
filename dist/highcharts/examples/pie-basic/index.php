<?php  ?>


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
<script src="../../code/highcharts.js"></script>
<script src="../../code/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>



		<script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Productos mas vendidos'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [
        <?php 
	include '../../../conexion.php';

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
				echo "{name: '".$filadatos['NOMBRE']."',y: ".$filadatos['CANTIDAD']."},";
			}
		}

 ?>
        ]
    }]
});
		</script>
	</body>
</html>
