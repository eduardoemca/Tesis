<?php include '../../conexion.php';
$conectar= new conexion();

/*if (isset($_POST['hora']) && isset($_POST['hora'])!=null) {   $hora=$_POST['hora'];  }	*/
if (isset($_POST['fecha_desde']) && isset($_POST['fecha_desde'])!=null) {  $fecha_desde=$_POST['fecha_desde'];  }	
if (isset($_POST['fecha_hasta']) && isset($_POST['fecha_hasta'])!=null) {  $fecha_hasta=$_POST['fecha_hasta'];  }

$hora="23:59:59";
$fecha_desdeconhora=$fecha_desde." ".$hora;
$fecha_hastaconhora=$fecha_hasta." ".$hora;

$sql="SELECT c.fecha_registro, c.codigo_compra, c.codigo_orden_compra, p.identificacion, p.razon_social, dc.codigo_producto, dc.cantidad_comprada, dc.precio_compra,pr.nombre FROM compra c
INNER JOIN detalle_compra dc ON dc.codigo_compra = c.codigo_compra INNER JOIN proveedor p ON c.codigo_proveedor = p.identificacion INNER JOIN producto pr ON dc.codigo_producto = 
pr.codigo_producto WHERE  c.fecha_registro BETWEEN '$fecha_desdeconhora' AND '$fecha_hastaconhora'";

		$resultado=$conectar->consulta($sql);
		$num = mysqli_num_rows($resultado);

		if($num==0){ 
			echo "1";} 
						else{   }?>