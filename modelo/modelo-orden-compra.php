<?php 

	require '../conexion.php';
	include '../controlador/recurso.php';
	
	class orden
	{
		private $conectar;
		private $recurso;
		private $codigo;

		function __construct()
		{
			$this->conectar= new conexion();
			$this->recurso= new recurso();
		}

		public function codigo_orden()
		{
			$codigo_primero="O0001";
			$codigo="";
			$c="";

			$sql="SELECT MAX(codigo_orden_compra) FROM orden_compra";

			$resultado=$this->conectar->consulta($sql);
			$row = mysqli_fetch_array($resultado);
			$cod=$row[0];
			$numeros=substr($cod, 1);

			$num3=$numeros{3};
			$num2=$numeros{2};
			$num1=$numeros{1};
			$num0=$numeros{0};
			$tot=$num0.$num1.$num2.$num3;
			
			if($cod==null)
			{
				return $codigo_primero;
			}

			else
			{
			   $this->recurso->generar($tot);
			   $codigo="O".$this->recurso->serie();
			   
			}
			return $codigo;
		}

		public function incluir()
		{
			$sql="SELECT * FROM orden_compra WHERE codigo_orden_compra = '{$this->codigo}' AND estado IN ('PENDIENTE','PROCESADA','ANULADA')";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Orden existente";
			}
			else
			{
				$sql = "INSERT INTO orden_compra(fecha_registro,codigo_orden_compra) VALUES(now(),'{$this->codigo}')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la orden";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		public function consulta_producto()
		{
			$sql="SELECT p.codigo, p.nombre, c.nombre AS CATEGORIA, p.descripcion, u.nombre AS UNIDAD, p.cantidad_minima, p.nombre_iva FROM producto p INNER JOIN categoria c ON p.nombre_categoria=c.id_categoria INNER JOIN unidad u ON p.nombre_unidad=u.id_unidad";

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

		public  function consultar_producto_id($id)
		{
			$sql="SELECT p.codigo AS codigo, p.nombre AS nombre, p.descripcion AS descripcion, u.nombre AS unidad FROM producto p INNER JOIN unidad u ON p.nombre_unidad=u.id_unidad WHERE p.codigo='$id'";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentra el producto";
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

		public function listar_tabla($iden)
		{
			$sql="SELECT pr.codigo_producto as codigo, p.nombre, c.nombre AS CATEGORIA, p.descripcion, u.nombre AS UNIDAD, p.cantidad_minima AS STOCK, p.precio, i.iva AS IVA FROM producto p INNER JOIN categoria c ON p.nombre_categoria=c.id_categoria INNER JOIN unidad u ON p.nombre_unidad=u.id_unidad INNER JOIN iva i ON p.nombre_iva=i.id_iva INNER JOIN proveedor_producto pr ON pr.codigo_producto=p.codigo WHERE pr.codigo_proveedor='$iden'";

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

		public function listar_tabla_facturar_compra($orden,$producto)
		{
			$sql="SELECT * FROM detalle_orden_compra WHERE codigo_orden_compra = '$orden' AND codigo_producto='$producto'";
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

		public function listar_tabla_compra($orden)
		{
			$sql="SELECT * FROM detalle_orden_compra WHERE codigo_orden_compra = '$orden'";
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

		public function reporte_detalle_orden_compra($codigo_oc,$codigo_proveedor)
		{
			$sql = "SELECT oc.codigo_orden_compra,doc.codigo_proveedor, pr.razon_social, doc.codigo_producto, 
			p.nombre AS producto, c.nombre AS categoria, doc.cantidad_solicitada, u.nombre AS unidad
			FROM detalle_orden_compra doc INNER JOIN orden_compra oc ON doc.codigo_orden_compra = 
			oc.codigo_orden_compra INNER JOIN proveedor pr ON doc.codigo_proveedor = pr.identificacion 
			INNER JOIN producto p ON doc.codigo_producto = p.codigo_producto INNER JOIN categoria c ON 
			p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad WHERE 
			oc.codigo_orden_compra = '$codigo_oc' AND doc.codigo_proveedor = '$codigo_proveedor' GROUP BY doc.codigo_producto;";

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

		function set_codigo($codigo)
		{
			$this->codigo = $codigo;
		}

		function get_codigo()
		{
			return $this->codigo;
		}
	}
?>