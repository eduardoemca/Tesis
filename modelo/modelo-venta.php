<?php 

	require '../conexion.php';
	include '../controlador/recurso.php';

	class venta
	{
		private $conectar;
		private $recurso;
		private $codigo_venta;
		private $identificacion;

		function __construct()
		{
			$this->conectar = new conexion();
			$this->recurso= new recurso();
		}

		public function codigo_venta()
		{
			$codigo_primero="V0001";
			$codigo="";
			$c="";

			$sql="SELECT MAX(codigo_venta) FROM venta";

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
			   $codigo="V".$this->recurso->serie();
			   
			}
			return $codigo;
		}

		public function incluir()
		{
			$sql="SELECT * FROM venta WHERE codigo_venta = '{$this->codigo_venta}' AND cedula_cliente = '{$this->identificacion}' AND estado ='PROCESADA' ";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Venta existente";
			}
			else
			{
				$sql = "INSERT INTO venta(fecha_registro,codigo_venta,cedula_cliente) VALUES(now(),'{$this->codigo_venta}','{$this->identificacion}')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la venta";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		public function reporte_detalle_venta($codigo_venta,$identificacion_cliente)
		{
			$sql = "SELECT 
			v.codigo_venta, v.cedula_cliente, cl.nombre AS cliente_nombre,	dv.codigo_venta, dv.codigo_producto,
			dv.cantidad_vendida, dv.codigo_producto, p.nombre AS producto, p.descripcion, p.tipo_categoria,
			p.cantidad_minima, p.cantidad_maxima, p.precio AS precio_producto, u.nombre AS nombre_unidad, dv.gravable,
			ca.nombre AS categoria FROM venta v INNER JOIN detalle_venta dv ON dv.codigo_venta=v.codigo_venta 
			INNER JOIN cliente cl ON cl.cedula=v.cedula_cliente INNER JOIN producto p ON p.codigo_producto = 
			dv.codigo_producto INNER JOIN categoria ca ON ca.id_categoria = p.tipo_categoria INNER JOIN unidad u ON 
			u.id_unidad = p.tipo_unidad WHERE dv.codigo_venta = '$codigo_venta' AND v.cedula_cliente = 
			'$identificacion_cliente' GROUP BY dv.codigo_producto;";

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

		function set_codigo_venta($codigo_venta)
		{
			$this->codigo_venta = $codigo_venta;
		}

		function get_codigo_venta()
		{
			return $this->codigo_venta;
		}

		function set_identificacion($identificacion)
		{
			$this->identificacion = $identificacion;
		}

		function get_identificacion()
		{
			return $this->identificacion;
		}
	}

?>
