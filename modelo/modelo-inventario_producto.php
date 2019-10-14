<?php 

	require '../conexion.php';

	class inventario_producto
	{
		private $conectar;
		private $inventario;
		private $codigo;
		
		function __construct()
		{
			$this->conectar = new conexion();
		}

		function incluir()
		{
			$sql="SELECT * FROM inventario_producto WHERE id_inventario = '{$this->inventario}' AND codigo_producto = '{$this->codigo}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Ya existe esta Relación";
			}
			else
			{
				$sql="INSERT INTO inventario_producto(fecha_registro,id_inventario,codigo_producto) VALUES(now(),'$this->inventario','{$this->codigo}')";
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
			$sql="SELECT * FROM inventario_producto WHERE id_inventario = '{$this->inventario}' AND codigo_producto = '{$this->codigo}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				$sql="UPDATE inventario_producto SET estado = 'ACTIVO' WHERE codigo_producto = '{$this->codigo}' AND id_inventario = '{$this->inventario}'";
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
				$sql="INSERT INTO inventario_producto(fecha_registro,id_inventario,codigo_producto) VALUES(now(),'$this->inventario','$this->codigo')";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir la Relacion";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		function set_inventario($inventario)
		{
			$this->inventario=$inventario;
		}

		function get_inventario()
		{
			return $this->inventario;
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