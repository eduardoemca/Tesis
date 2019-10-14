<?php 

	require '../conexion.php';
	include '../controlador/recurso.php';
	
	class inventario
	{
		private $codigo;
		private $descripcion;
		private $conectar;
		private $recurso;

		function __construct()
		{
			$this->conectar= new conexion();
			$this->recurso= new recurso();
		}

		/*public function codigo_inventario()
		{
			$codigo_primero="ALM0001";
			$codigo="";
			$c="";

			$sql="SELECT MAX(id_inventario) FROM inventario";

			$resultado=$this->conectar->consulta($sql);
			$row = mysqli_fetch_array($resultado);
			$cod=$row[0];
			$numeros=substr($cod, 3);

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
			   $codigo="ALM".$this->recurso->serie();
			}
			return $codigo;
		}*/

		function incluir()
		{
			$sql="SELECT * FROM inventario WHERE id_inventario = '{$this->codigo}' AND estado = 'ACTIVO'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Inventario existente";
			}
			else
			{
				$sql=" INSERT INTO inventario(fecha_registro, id_inventario, descripcion) VALUES(now(),'{$this->codigo}','{$this->descripcion}')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir el Inventario";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		public function consultar_inventario()
		{
			$sql=" SELECT * FROM inventario WHERE id_inventario = '{$this->codigo}' AND estado = 'ACTIVO'";

			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				return false;
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado)){
				$enc=1;
				$datos["data"][]= $fila;
			}
				$json=json_encode($datos);
				return 	$json;
				mysqli_close($this->conectar);
			}
		}

		function modificar()
		{
			$sql=" UPDATE inventario SET descripcion = '{$this->descripcion}', fecha_modificacion = now() WHERE id_inventario = '{$this->codigo}'";

			if(!$this->conectar->consulta($sql))
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

		function eliminar()
		{
			$sql=" UPDATE inventario SET estado = 'INACTIVO', fecha_modificacion = now() WHERE id_inventario = '{$this->codigo}'";
			if(!$this->conectar->consulta($sql))
			{
				echo "No se pudo Eliminar";
				$enc=0;
			}
			else
			{
				$enc=1;
				return $enc;
				mysqli_close($this->conectar);
			}
		}

		public function consulta_inventario()
		{
			$sql=" SELECT id_inventario, descripcion FROM inventario WHERE estado = 'ACTIVO'";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran Inventario";
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

		public function inventario()
		{
			$sql=" SELECT id_inventario FROM inventario WHERE estado = 'ACTIVO' AND id_inventario <> 'ALM0000'";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran Inventario";
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

		public function consultar_inventario_master()
		{
			$sql="SELECT p.codigo_producto AS Codigo, p.nombre AS Producto, c.nombre AS Categoria, p.cantidad_minima AS Stock_Minimo, p.cantidad_actual AS Cantidad_Actual, p.cantidad_maxima AS Stock_Maximo, u.nombre AS Unidad, p.precio AS Precio, p.gravado FROM producto p  INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad WHERE p.estado = 'ACTIVO' GROUP BY p.codigo_producto;";

			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran Productos";
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

		function set_descripcion($descripcion)
		{
			$this->descripcion = $descripcion;
		}

		function get_descripcion()
		{
			return $this->descripcion;
		}
	}
?>