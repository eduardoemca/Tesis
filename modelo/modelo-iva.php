<?php 

	require '../conexion.php';

	class iva
	{
		private $codigo;
		private $iva;
		private $descripcion;
		private $conectar;

		function __construct()
		{
			$this->conectar= new conexion();
		}

		function incluir()
		{
			$sql="SELECT * FROM iva WHERE iva = '{$this->iva}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Iva existente, por favor Verificar";
			}
			else
			{
				$sql="INSERT INTO iva(fecha_registro, iva, descripcion) VALUES(now(), '{$this->iva}', '{$this->descripcion}')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la Iva";
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
			$sql="SELECT * FROM iva WHERE id_iva = '{$this->codigo}' AND estado = 'ACTIVO'";
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
			$sql="UPDATE iva SET iva = '{$this->iva}', descripcion = '{$this->descripcion}' WHERE id_iva = '{$this->codigo}'";

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
		$sql="UPDATE iva SET estado = 'INACTIVO' WHERE id_iva = '{$this->codigo}'";
		
			if (!$this->conectar->consulta($sql)) 
			{
				echo "No se pudo Desactivar";
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

		function set_iva($iva)
		{
			$this->iva=$iva;
		}

		function get_iva()
		{
			return $this->iva;
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