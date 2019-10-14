<?php 

	require '../conexion.php';

	class compra
	{
		private $conectar;
		private $codigo_compra;
		private $codigo_orden;
		private $identificacion;
		private $estado;

		function __construct()
		{
			$this->conectar = new conexion();
		}

		public function incluir()
		{
			$sql="SELECT * FROM compra WHERE codigo_compra = '{$this->codigo_compra}' AND codigo_proveedor = '{$this->identificacion}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Compra existente";
			}
			else
			{
				$sql = "INSERT INTO compra(fecha_registro,codigo_compra,codigo_orden_compra,codigo_proveedor) VALUES(now(),'{$this->codigo_compra}','{$this->codigo_orden}','{$this->identificacion}')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la compra";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		public function consultar_ordenes_registradas()
		{
			$sql = "SELECT DISTINCT estado FROM orden_compra WHERE estado NOT IN ('PROCESADA') ORDER BY estado ASC";

			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);
			if($num==0)
			{
				echo "";
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
					$enc=1;
					$datos[]= $fila;
				}
				return 	$datos;
				mysqli_close($this->conectar);
			}
		}

		public function consulta_orden_vista_compra()
		{
			$sql = "SELECT oc.codigo_orden_compra, doc.codigo_proveedor, p.razon_social FROM orden_compra oc INNER JOIN
			detalle_orden_compra doc ON oc.codigo_orden_compra = doc.codigo_orden_compra INNER JOIN proveedor p 
			ON doc.codigo_proveedor = p.identificacion WHERE oc.codigo_orden_compra = '{$this->codigo_orden}' AND oc.estado IN ('GENERADA');";

			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);
			if($num==0)
			{
				echo "";
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
					$enc=1;
					$datos["data"][]= $fila;
				}
				$json= json_encode($datos);
				return 	$json;
				mysqli_close($this->conectar);
			}
		}

		public function consultar_orden_compra_generada()
		{
			$sql = "SELECT oc.codigo_orden_compra, doc.codigo_proveedor, p.razon_social FROM orden_compra oc INNER JOIN detalle_orden_compra doc ON oc.codigo_orden_compra = doc.codigo_orden_compra INNER JOIN proveedor p ON doc.codigo_proveedor = p.identificacion WHERE oc.codigo_orden_compra = '{$this->codigo_orden}' AND oc.estado = '{$this->estado}';";

			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);
			if($num==0)
			{
				echo "";
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
					$enc=1;
					$datos["data"][]= $fila;
				}
				$json= json_encode($datos);
				return 	$json;
				mysqli_close($this->conectar);
			}
		}

		public function consultar_orden_compra_pendiente()
		{
			$sql = "SELECT oc.codigo_orden_compra, doc.codigo_proveedor, p.razon_social, c.codigo_compra FROM orden_compra oc INNER JOIN detalle_orden_compra doc ON oc.codigo_orden_compra = doc.codigo_orden_compra INNER JOIN proveedor p ON doc.codigo_proveedor = p.identificacion INNER JOIN compra c ON oc.codigo_orden_compra = c.codigo_orden_compra WHERE c.codigo_orden_compra = '{$this->codigo_orden}' AND c.estado = '{$this->estado}';";

			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);
			if($num==0)
			{
				echo "";
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
					$enc=1;
					$datos["data"][]= $fila;
				}
				$json= json_encode($datos);
				return 	$json;
				mysqli_close($this->conectar);
			}
		}

		function cerrar_orden_generada()
		{
			$sql="SELECT * FROM orden_compra WHERE codigo_orden_compra = '{$this->codigo_orden}' AND estado IN ('{$this->estado}')";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);
			if($num!=0)
			{
				$cerrar = "UPDATE orden_compra SET estado = 'CERRADA' WHERE codigo_orden_compra = '{$this->codigo_orden}' AND estado IN ('{$this->estado}');";
				if (!$this->conectar->consulta($cerrar)) 
				{
					echo "No se pudo Actualizar";
					$enc=0;
				}
				else
				{
					$enc=1;
					return $enc;
					mysqli_close($this->conectar);
				}
			}
			else
			{
				echo "Orden inexistente";
			}
		}

		function cerrar_orden_pendiente()
		{
			$sql="SELECT * FROM orden_compra WHERE codigo_orden_compra = '{$this->codigo_orden}' AND estado IN ('{$this->estado}')";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);
			if($num!=0)
			{
				$cerrar = "UPDATE orden_compra SET estado = 'CERRADA' WHERE codigo_orden_compra = '{$this->codigo_orden}' AND estado IN ('{$this->estado}');";
				if (!$this->conectar->consulta($cerrar)) 
				{
					echo "No se pudo Actualizar";
					$enc=0;
				}
				else
				{
					$cerrar = "UPDATE orden_compra SET estado = 'CERRADA' WHERE codigo_orden_compra = '{$this->codigo_orden}' AND estado IN ('{$this->estado}');";
					if (!$this->conectar->consulta($cerrar)) 
					{
						echo "No se pudo Actualizar";
						$enc=0;
					}
					else
					{
						$sql="SELECT * FROM compra WHERE codigo_orden_compra = '{$this->codigo_orden}'  AND codigo_proveedor =  '{$this->identificacion}' AND estado IN ('{$this->estado}');";
						$resultado=$this->conectar->consulta($sql);
						$num = mysqli_num_rows($resultado);
						if($num!=0)
						{
							$cerrar = "UPDATE compra SET estado = 'CERRADA' WHERE codigo_orden_compra = '{$this->codigo_orden}'  AND codigo_proveedor =  '{$this->identificacion}' AND estado IN ('{$this->estado}');";
							if (!$this->conectar->consulta($cerrar)) 
							{
								echo "No se pudo Actualizar";
								$enc=0;
							}
							else
							{
								$enc=1;
								return $enc;
								mysqli_close($this->conectar);
							}
						}
						else
						{
							echo "Compra inexistente";
						}
					}
				}
			}
			else
			{
				echo "Orden inexistente";
			}

		}

		public function reporte_detalle_compra($codigo_c,$codigo_proveedor)
		{
			$sql=
			"SELECT c.codigo_compra,c.codigo_orden_compra,
			p.identificacion,p.razon_social,
			dc.codigo_producto,pr.descripcion,dc.cantidad_comprada,dc.precio_compra,pr.nombre,dc.gravable FROM compra c INNER JOIN
			detalle_compra dc ON dc.codigo_compra = c.codigo_compra INNER JOIN proveedor p ON 
			c.codigo_proveedor = p.identificacion INNER JOIN producto pr ON dc.codigo_producto = pr.codigo_producto WHERE c.codigo_compra = '$codigo_c' AND 
			c.codigo_proveedor = '$codigo_proveedor' AND dc.cantidad_comprada <> 0 GROUP BY dc.codigo_producto;";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran productos";
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
					$enc=1;
					$datos[]=$fila;
				}
				return 	$datos;
				mysqli_close($this->conectar);
			}
		}

		public function reporte_detalle_compras($fecha_desde,$fecha_hasta)
		{  $hora="23:59:59";
			$sql=
			"SELECT c.fecha_registro,c.codigo_compra,c.codigo_orden_compra,
			p.identificacion,p.razon_social,
			dc.codigo_producto,SUM(dc.cantidad_comprada) AS cantidad_comprada,SUM(dc.precio_compra) AS precio_compra,dc.gravable,pr.nombre,pr.descripcion FROM compra c INNER JOIN 
			detalle_compra dc ON dc.codigo_compra = c.codigo_compra INNER JOIN proveedor p ON 
			c.codigo_proveedor = p.identificacion INNER JOIN producto pr ON dc.codigo_producto = pr.codigo_producto WHERE  c.fecha_registro BETWEEN '$fecha_desde $hora' AND '$fecha_hasta $hora' GROUP BY dc.codigo_compra";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "1";
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
					$enc=1;
					$datos[]=$fila;
				}
				return 	$datos;
				mysqli_close($this->conectar);
			}
		}

		function set_codigo_compra($codigo_compra)
		{
			$this->codigo_compra = $codigo_compra;
		}

		function get_codigo_compra()
		{
			return $this->codigo_compra;
		}

		function set_codigo_orden($codigo_orden)
		{
			$this->codigo_orden = $codigo_orden;
		}

		function get_codigo_orden()
		{
			return $this->codigo_orden;
		}

		function set_identificacion($identificacion)
		{
			$this->identificacion = $identificacion;
		}

		function get_identificacion()
		{
			return $this->identificacion;
		}

		function set_estado($estado)
		{
			$this->estado = $estado;
		}

		function get_estado()
		{
			return $this->estado;
		}
	}
?>
