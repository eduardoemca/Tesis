<?php include '../../conexion.php';
$conectar= new conexion();

/*if (isset($_POST['hora']) && isset($_POST['hora'])!=null) {   $hora=$_POST['hora'];  }	*/
if (isset($_POST['fecha_desde']) && isset($_POST['fecha_desde'])!=null) {  $fecha_desde=$_POST['fecha_desde'];  }	
if (isset($_POST['fecha_hasta']) && isset($_POST['fecha_hasta'])!=null) {  $fecha_hasta=$_POST['fecha_hasta'];  }

$hora="23:59:59";
$fecha_desdeconhora=$fecha_desde." ".$hora;
$fecha_hastaconhora=$fecha_hasta." ".$hora;

$sql="SELECT p.nombre, SUM(dv.cantidad_vendida) AS cantidad_vendida FROM producto p INNER JOIN detalle_venta dv  ON p.codigo_producto=dv.codigo_producto INNER JOIN venta v ON v.codigo_venta=dv.codigo_venta WHERE v.fecha_registro BETWEEN '$fecha_desdeconhora' AND '$fecha_hastaconhora' GROUP BY p.nombre";

		$resultado=$conectar->consulta($sql);
		$num = mysqli_num_rows($resultado);

		if($num==0){ 
			echo "1";} 
						else{   }			?>