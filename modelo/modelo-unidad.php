<?php 
	
	require '../conexion.php';

	class unidad
	{
		private $codigo;
		private $unidad;
		private $descripcion;
		private $conectar;

		function __construct()
		{
			$this->conectar= new conexion();
		}

		function incluir()
		{
			$sql="SELECT * FROM unidad WHERE nombre = '{$this->unidad}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Unidad Existente, por favor Verificar";
			}
			else
			{
				$sql="INSERT INTO unidad(fecha_registro, nombre, descripcion) VALUES(now(), '{$this->unidad}', '{$this->descripcion}')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la Unidad";
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
			$sql="SELECT * FROM unidad WHERE id_unidad = '{$this->codigo}' AND estado = 'ACTIVO'";
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
			$sql="UPDATE unidad SET nombre ='{$this->unidad}', descripcion ='{$this->descripcion}' WHERE id_unidad = '{$this->codigo}'";

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
			$sql="UPDATE unidad SET estado = 'INACTIVO' WHERE id_unidad = '{$this->codigo}'";

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

		function set_unidad($unidad)
		{
			$this->unidad=$unidad;
		}

		function get_unidad()
		{
			return $this->unidad;
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
