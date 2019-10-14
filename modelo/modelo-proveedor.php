<?php 

	require '../conexion.php';

	class proveedor
	{
		private $nacionalidad;
		private $identificacion;
		private $razon_social;
		private $direccion;
		private $correo;
		private $telefono;
		private $conectar;

		function __construct()
		{
			$this->conectar= new conexion();
		}

		function incluir()
		{
			$sql="SELECT * FROM proveedor WHERE identificacion = '{$this->identificacion}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Proveedor Existente, por favor Verifique";
			}
			else
			{
				$sql="INSERT INTO proveedor(fecha_registro, identificacion, razon_social, direccion, correo, telefono) VALUES(now(), '{$this->identificacion}', '{$this->razon_social}', '{$this->direccion}', '{$this->correo}', '{$this->telefono}')";

				if (!$this->conectar->consulta($sql)) 
				{
					echo "No se pudo Incluir al Proveedor";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
		}

		function consultar_proveedor()
		{
			$sql="SELECT * FROM proveedor WHERE identificacion = '{$this->identificacion}' AND estado = 'ACTIVO'";
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
		function consultarproveedor_rep()
		{
			$sql="SELECT * FROM proveedor ORDER BY razon_social ASC";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
				{
				//echo "No se encuentran proveedor";
				return false;
				//$enc=0;
				}
				else
				{
					while($fila = mysqli_fetch_assoc($resultado)) {
		    		//echo $fila['foo'];
					$enc=1;
					$datos[]=$fila;
					/*$this->cedula=$fila['cedula'];
					$this->nombre=$fila['nombre'];
					$this->apellido=$fila['apellido'];
					$this->telefono=$fila['telefono'];
					$this->correo=$fila['correo'];
					$this->direccion=$fila['direccion'];*/	
					}
					//echo json_encode($datos);
					return 	$datos;
					mysqli_close($this->conectar);
				}
		}
		function consultarproveedor()
		{
			$sql="SELECT * FROM proveedor ORDER BY identificacion ASC";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
			//echo "No se encuentran proveedores";
			//$enc=0;
			}
			else
				{
					while($fila = mysqli_fetch_assoc($resultado)) {
					$enc=1;
					$datos["data"][]=$fila;
					}
					$json=json_encode($datos);
					return 	$json;
					//mysqli_close($this->conectar);
				}
		}
		public function consultar_inventario_id($id_inventario)
		{
			$sql="SELECT * FROM inventario WHERE id_inventario = '$id_inventario'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentra el Inventario";
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

		public function consultar_proveedor_id($id)
		{
			$sql="SELECT * FROM proveedor WHERE identificacion = '$id'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran proveedores";
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

		function modificar()
		{
			$sql="UPDATE proveedor SET razon_social = '{$this->razon_social}', direccion = '{$this->direccion}', correo = '{$this->correo}', telefono = '{$this->telefono}' WHERE identificacion = '{$this->identificacion}'";

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
			$sql="UPDATE proveedor SET estado = 'INACTIVO' WHERE identificacion = '{$this->identificacion}'";

			if (!$this->conectar->consulta($sql)) 
			{
				echo "No se pudo Eliminar";
				$enc=0;
			}
			else
			{
				$sql="UPDATE proveedor_producto SET estado = 'INACTIVO' WHERE codigo_proveedor = '{$this->identificacion}'";
				
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

		public function consulta_proveedor_modal()
		{
			$sql=" SELECT identificacion, razon_social, direccion, correo, telefono FROM proveedor WHERE estado = 'ACTIVO'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran proveedores";
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

		function set_nacionalidad($nacionalidad)
		{
			$this->nacionalidad=$nacionalidad;
		}

		function get_nacionalidad()
		{
			return $this->nacionalidad;
		}

		function set_identificacion($identificacion)
		{
			$this->identificacion=$identificacion;
		}

		function get_identificacion()
		{
			return $this->identificacion;
		}

		function set_razon_social($razon_social)
		{
			$this->razon_social=$razon_social;
		}

		function get_razon_social()
		{
			return $this->razon_social;
		}

	    function set_telefono($telefono)
	    {
	     	$this->telefono=$telefono;
	    }

	    function get_telefono()
	    {
	     	return $this->telefono;
	    }
	    function set_correo($correo)
	    {
	     	$this->correo=$correo;
	    }

	    function get_correo()
	    {
	     	return $this->correo;
	    }

	     function set_direccion($direccion)
	    {
	     	$this->direccion=$direccion;
	    }

	    function get_direccion()
	    {
	     	return $this->direccion;
	    }
	}
?>
