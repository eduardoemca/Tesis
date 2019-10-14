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

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php
require_once("../../../../clases/clsCargo.php");// incluimos la clase de daatos (Modelo)
$objc=new clsCargo();
$cargos=$objc->ConsultarEmpleadoxCargo();
$tira="'".$cargos[0]['Cargo']."'";
$tira1=$cargos[0]['Cuantos'];
$n=count($cargos);
for ($i=1;$i<=$n;$i++)
{
	$tira.=",'".$cargos[$i]['Cargo']."'";
	$tira1.=",".$cargos[$i]['Cuantos'];
}
echo $tira1;
echo $tira;
?>

		<script type="text/javascript">
		
Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Ejemplo'
    },
    subtitle: {
        text: 'yo'
    },
    xAxis: {
        categories: [<?php echo $tira;?>]
    },
    yAxis: {
        title: {
            text: 'Temperature (°C)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Tokyo',
        data: [<?php echo $tira1;?>]
    }]
});
		</script>
	</body>
</html>
