<?php 

	require '../conexion.php';

	class proveedor_producto
	{
		private $conectar;
		private $codigo;
		private $proveedor;
		
		function __construct()
		{
			$this->conectar = new conexion();
		}

		function incluir()
		{
			$sql="SELECT * FROM proveedor_producto WHERE codigo_proveedor = '{$this->proveedor}' AND codigo_producto = '{$this->codigo}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Ya existe esta Relación";
			}
			else
			{
				$sql="INSERT INTO proveedor_producto(fecha_registro,codigo_proveedor,codigo_producto) VALUES(now(),'{$this->proveedor}','{$this->codigo}')";
				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la Relación";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		function reactivar()
		{
			$sql="SELECT * FROM proveedor_producto WHERE codigo_proveedor = '{$this->proveedor}' AND codigo_producto = '{$this->codigo}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				$sql="UPDATE proveedor_producto SET estado = 'ACTIVO' WHERE codigo_proveedor = '{$this->proveedor}' AND codigo_producto = '$this->codigo'";
				if(!$this->conectar->consulta($sql))
				{
					$enc=1;
					return $enc;
					mysqli_close($this->conectar);
				}
				else
				{
					$enc=0;
				}
			}
			else
			{
				$sql="INSERT INTO proveedor_producto(fecha_registro,codigo_proveedor,codigo_producto) VALUES(now(),'$this->proveedor','$this->codigo')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la Relación";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		function set_proveedor($proveedor)
		{
			$this->proveedor=$proveedor;
		}

		function get_proveedor()
		{
			return $this->proveedor;
		}

		function set_codigo($codigo)
		{
			$this->codigo=$codigo;
		}

		function get_codigo()
		{
			return $this->codigo;
		}
	}

?>