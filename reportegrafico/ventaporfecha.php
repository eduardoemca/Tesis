<?php 
	/*FECHA DESDE*/
if (isset($_GET['fecha_desde'])&&isset($_GET['fecha_desde'])!=null){
	/*CAMBIO A DIA MES Y AÑO*/
$fecha_desde=$_GET['fecha_desde'];
/*$fecha_año=substr($fecha_desde,0,4);
$fecha_mes=substr($fecha_desde,5,2);
$fecha_dia=substr($fecha_desde,8,3);
$fecha_desde=$fecha_dia."/".$fecha_mes."/".$fecha_año;*/
}
else
{
  /* header("location: ../vista/menu.php");*/
}
	/*FECHA HASTA*/
if (isset($_GET['fecha_hasta'])&&isset($_GET['fecha_hasta'])!=null){
	/*CAMBIO A DIA MES Y AÑO*/
$fecha_hasta=$_GET['fecha_hasta'];
/*$fecha_año=substr($fecha_hasta,0,4);
$fecha_mes=substr($fecha_hasta,5,2);
$fecha_dia=substr($fecha_hasta,8,3);
$fecha_hasta=$fecha_dia."/".$fecha_mes."/".$fecha_año;*/
}
else
{
   /*header("location: ../vista/menu.php");*/
}

?>
<!DOCTYPE HTML>
<html>
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Productos mas vendidos</title>
            <link rel="stylesheet" type="text/css" href="../dist/bootstrap-3.3.7-dist/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
        <style type="text/css">
            #container {
                height: 400px;
                max-width: 800px;
                margin: 20px auto;
            }
                .highcharts-title {
                    font-weight: bold;
                    letter-spacing: 0.1em;
                    font-size: 1em;
                }
                .highcharts-subtitle {
/*                    font-family: 'Courier New', monospace;
                    font-style: italic;
                    fill: #7cb5ec;*/
                }
        @page {
          size: A4;
          margin-top: 9mm;
          margin-bottom: 8mm;
          margin-left: 7mm;
          margin-right: 7mm;  
        }
        @media print {
          body {
              width: 780px;
              overflow: hidden;
          }
              #container {
                  max-width: 780px;
              }
        }      

        </style>
	</head>
<body>
    <input type="hidden" id="fecha_desdeh" value="<?php echo $fecha_desde ?>">
    <input type="hidden" id="fecha_hastah" value="<?php echo  $fecha_hasta ?>">

    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
    <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0px auto"></div>
<center>
<button class="btn btn-info" id="export2pdf">Exportar a PDF</button>
</center>

<script type="text/javascript">
    var fecha_desde=$("#fecha_desdeh").val();
    var fecha_hasta=$("#fecha_hastah").val();

// Build the chart
$(function () {
    chart = new Highcharts.Chart({
    chart: {
        // Edit chart spacing
/*        spacingBottom: 15,
        
        spacingLeft: 10,
        spacingRight: 10,*/
        renderTo : 'container',
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        styledMode: true,
        // Explicitly tell the width and height of a chart
     /*   width: 200,
        height: 500,*/
        spacingTop: 30,
        type: 'pie',
    },

    title: {     text: '<br><br><p style="margin-top:50px"><b>Sistema Eddy C.A</b></p><br><p>J-1234567</p><br><p>Barquisimeto/Venezuela</p><br>Telefono:0251-1234567/Fax: 0251-7654321',
    useHtml: true,

    },
    subtitle: {
        text: "Productos mas vendidos desde :<br>"+fecha_desde+" Hasta :"+fecha_hasta,
    },
    tooltip: { pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'},
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
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Porcentaje',
        colorByPoint: true,
        data: [
        <?php 
    	   include '../conexion.php';

    	    $conectar= new conexion();
            $hora="23:59:59";
            $fecha_desdeconhora=$fecha_desde." ".$hora;
            $fecha_hastaconhora=$fecha_hasta." ".$hora;

$sql="SELECT p.nombre, SUM(dv.cantidad_vendida) AS cantidad_vendida FROM producto p INNER JOIN detalle_venta dv  ON p.codigo_producto=dv.codigo_producto INNER JOIN venta v ON v.codigo_venta=dv.codigo_venta WHERE v.fecha_registro BETWEEN '$fecha_desdeconhora' AND '$fecha_hastaconhora' GROUP BY p.nombre";

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
				echo "{name: '".$filadatos['nombre']."',y: ".$filadatos['cantidad_vendida']."},";
			}
		echo " ]";	
		}

 ?>
        }],

        exporting: {
        enabled: true,
        allowHTML: true,
        }
},
function (chart) {chart.renderer.image('https://66.media.tumblr.com/6c8884b25f0804fc15e0cbc669f9fe0d/tumblr_pmbosxc6Zw1wwc28lo1_1280.png', 50, 40, 100, 20)
        .add();
}

);

    $("#export2pdf").click(function(){
        chart.exportChart({
            type: 'application/pdf',
            filename:"VentasProductos<?php echo $fecha_desde ?>"
        });
    });
});
		</script>
        <script src="../dist/alertifyjs/alertify.js"></script>
	</body>
</html>
