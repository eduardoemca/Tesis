<?php

	require '../conexion.php';
	include '../controlador/recurso.php';

	class producto
	{
		private $inventario;
		private $codigo;
		private $nombre;
		private $categoria;
		private $descripcion;
		private $unidad;
		private $cantidad_minima;
		private $cantidad_maxima;
		private $precioventa;
		private $gravado;
		private $proveedor;

		private $conectar;

		function __construct()
		{
	        $this->conectar= new conexion();
	        $this->recurso= new recurso();
		}

		public function codigo_producto()
	    {
	        $codigo_primero="P0001";
	        $codigo="";
	        $c="";

	        $sql="SELECT MAX(codigo_producto) FROM producto";
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
	            $codigo="P".$this->recurso->serie();
	        }
	        return $codigo;
	    }

		function incluir()
		{
			$sql= "SELECT * FROM producto WHERE codigo_producto = '{$this->codigo}'";
			$resultado= $this->conectar->consulta($sql);
			$num= mysqli_num_rows($resultado);
			if ($num!=0) 
			{
				echo "producto existente";
			}
			else
			{
				$sql= "INSERT INTO producto(fecha_registro,codigo_producto,nombre,descripcion,tipo_categoria,cantidad_minima,cantidad_maxima,tipo_unidad,gravado) VALUES(now(),'{$this->codigo}','{$this->nombre}','{$this->descripcion}','{$this->categoria}','{$this->cantidad_minima}','{$this->cantidad_maxima}',
				'{$this->unidad}','{$this->gravado}')";

				if (!$this->conectar->consulta($sql)) 
				{
					echo "No se pudo incluir el producto";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		function consultar_producto()
		{
			$sql= "SELECT * FROM producto WHERE codigo_producto = '{$this->codigo}'";
			$resultado= $this->conectar->consulta($sql);
			$num= mysqli_num_rows($resultado);
			if ($num==0) 
			{
				return false;
			}
			else
			{
				while($fila= mysqli_fetch_assoc($resultado)) 
				{
					$enc= 1;
					$datos["data"][]= $fila;
				}
				$json= json_encode($datos);
				return $json;
				mysqli_close($this->conectar);
			}
		}

		function modificar()
		{
			$sql="UPDATE producto SET nombre='{$this->nombre}', descripcion='{$this->descripcion}',tipo_categoria='{$this->categoria}',cantidad_minima='{$this->cantidad_minima}',cantidad_maxima='{$this->cantidad_maxima}',tipo_unidad='{$this->unidad}',gravado='{$this->gravado}' WHERE codigo_producto= '{$this->codigo}'";

			if (!$this->conectar->consulta($sql)) 
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
			$sql="UPDATE producto SET estado = 'INACTIVO' WHERE codigo_producto= '{$this->codigo}'";
			if (!$this->conectar->consulta($sql)) 
			{
				echo "No se pudo Inactivar";
				$enc=0;
			}
			else
			{
				$sql="UPDATE proveedor_producto SET estado = 'INACTIVO' WHERE codigo_producto = '{$this->codigo}'";
				
				if (!$this->conectar->consulta($sql)) 
				{
					echo "No se pudo Inactivar";
					$enc=0;
				}
				else
				{
					$enc=1;
					return $enc;
					mysqli_close($this->conectar);
				}
			}
		}

		function inventario_producto()
		{
			$sql= "SELECT * FROM inventario_producto WHERE codigo_producto= '{$this->codigo}' AND id_inventario= '{$this->inventario}'";
			$resultado= $this->conectar->consulta($sql);
			$num= mysqli_num_rows($resultado);
			if ($num==0) 
			{
				echo "No existe esta relación";
			}
			else
			{
				$sql = "UPDATE inventario_producto SET estado = 'INACTIVO' WHERE codigo_producto = '{$this->codigo}' AND id_inventario = '{$this->inventario}'";
				if (!$this->conectar->consulta($sql)) 
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
		}

		function proveedor_producto()
		{
			$sql= "SELECT * FROM proveedor_producto WHERE codigo_producto= '{$this->codigo}' AND codigo_proveedor= '{$this->proveedor}'";
			$resultado= $this->conectar->consulta($sql);
			$num= mysqli_num_rows($resultado);
			if ($num==0) 
			{
				echo "No existe esta relación";
			}
			else
			{
				$sql="UPDATE proveedor_producto SET estado = 'INACTIVO' WHERE codigo_producto = '{$this->codigo}' AND codigo_proveedor = '{$this->proveedor}'";
				if (!$this->conectar->consulta($sql)) 
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
		}

		public function consulta_categoria()
		{
			$sql="SELECT id_categoria, nombre FROM categoria WHERE estado = 'ACTIVO'";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran categoria";
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

		public function consulta_unidad()
		{
			$sql="SELECT id_unidad, nombre FROM unidad WHERE estado = 'ACTIVO'";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran unidad";	
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

		public function consulta_iva()
		{
			$sql="SELECT id_iva, iva FROM iva WHERE estado = 'ACTIVO'";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran Iva";
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
			$this->codigo=$codigo;
		}

		function get_codigo()
		{
			return $this->codigo;
		}

		function set_proveedor($proveedor)
		{
			$this->proveedor=$proveedor;
		}

		function get_proveedor()
		{
			return $this->proveedor;
		}

		function set_inventario($inventario)
		{
			$this->inventario=$inventario;
		}

		function get_inventario()
		{
			return $this->inventario;
		}

		function set_nombre($nombre)
		{
			$this->nombre=$nombre;
		}

		function get_nombre()
		{
			return $this->nombre;
		}

		function set_categoria($categoria)
		{
			$this->categoria=$categoria;
		}

		function get_categoria()
		{
			return $this->categoria;
		}

		function set_descripcion($descripcion)
		{
			$this->descripcion=$descripcion;
		}

		function get_descripcion()
		{
			return $this->descripcion;
		}

		function set_unidad($unidad)
		{
			$this->unidad=$unidad;
		}

		function get_unidad()
		{
			return $this->unidad;
		}

		function set_stock_minimo($cantidad_minima)
		{
			$this->cantidad_minima=$cantidad_minima;
		}

		function get_stock_minimo()
		{
			return $this->cantidad_minima;
		}

		function set_stock_maximo($cantidad_maxima)
		{
			$this->cantidad_maxima=$cantidad_maxima;
		}

		function get_stock_maximo()
		{
			return $this->cantidad_maxima;
		}

		function set_precio($precioventa)
		{
			$this->precioventa=$precioventa;
		}

		function get_precio()
		{
			return $this->precioventa;
		}

		function set_gravado($gravado)
		{
			$this->gravado=$gravado;
		}

		function get_gravado()
		{
			return $this->gravado;
		}
	}
?>
