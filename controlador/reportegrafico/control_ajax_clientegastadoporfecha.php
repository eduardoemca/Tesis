<?php include '../../conexion.php';
$conectar= new conexion();

/*if (isset($_POST['hora']) && isset($_POST['hora'])!=null) {   $hora=$_POST['hora'];  }	*/
if (isset($_POST['fecha_desde']) && isset($_POST['fecha_desde'])!=null) {  $fecha_desde=$_POST['fecha_desde'];  }	
if (isset($_POST['fecha_hasta']) && isset($_POST['fecha_hasta'])!=null) {  $fecha_hasta=$_POST['fecha_hasta'];  }

$hora="23:59:59";
$fecha_desdeconhora=$fecha_desde." ".$hora;
$fecha_hastaconhora=$fecha_hasta." ".$hora;

$sql="  SELECT
        c.nombre AS nombre,
        v.codigo_venta  as codigo_venta,
        dV.cantidad_vendida AS cantidad_vendida,
        p.precio AS precio,

        SUM(COALESCE(dV.cantidad_vendida*p.precio)) AS totalgastado
            FROM cliente c
            INNER JOIN venta v ON c.cedula=v.cedula_cliente
            INNER JOIN detalle_venta dv ON v.codigo_venta=dv.codigo_venta
            INNER JOIN producto p ON p.codigo_producto=dv.codigo_producto

        GROUP BY c.cedula LIMIT 0,5;";

		$resultado=$conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

		if($num==0){ 
			echo "1";} 
						else{   }		?>