<?php 

	require '../conexion.php';

	class categoria
	{
		private $codigo;
		private $categoria;
		private $descripcion;
		private $conectar;

		function __construct()
		{
			$this->conectar= new conexion();
		}

		function incluir()
		{
			$sql=" SELECT * FROM categoria WHERE nombre = '{$this->categoria}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Categoria existente, por favor Verificar";
			}
			else
			{
				$sql=" INSERT INTO categoria(fecha_registro, nombre, descripcion) VALUES(now(), '{$this->categoria}','{$this->descripcion}')";
				
				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la Categoria";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		function consultar()
		{
			$sql="SELECT * FROM categoria WHERE id_categoria = '{$this->codigo}' AND estado = 'ACTIVO'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);
			if($num==0)
			{
				return false;
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
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
			$sql="UPDATE categoria SET nombre = '{$this->categoria}', descripcion = '{$this->descripcion}' WHERE id_categoria = '{$this->codigo}'";
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
			$sql="UPDATE categoria SET estado = 'INACTIVO' WHERE id_categoria = '{$this->codigo}'";
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

		function set_codigo($codigo)
		{
			$this->codigo=$codigo;
		}

		function get_codigo()
		{
			return $this->codigo;
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
	}
?>
