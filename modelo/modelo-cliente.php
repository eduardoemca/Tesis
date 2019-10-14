<?php 

	require '../conexion.php';
	
	class cliente
	{
		private $cedula;
		private $nombre;
		private $apellido;
		private $telefono;
		private $correo;
		private $direccion;
		private $nacionalidad;
		private $conectar;

	
		function __construct()
		{
			$this->conectar= new conexion();
		}

		function incluir()
		{
			$sql=" SELECT * FROM cliente WHERE cedula = '{$this->cedula}' AND estado = 'INACTIVO'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				echo "Cliente Existente, por favor Verifique";
			}
			else
			{
				$sql=" INSERT INTO cliente(fecha_registro, cedula, nombre, apellido, direccion, correo, telefono) VALUES(now(),'{$this->cedula}','{$this->nombre}','{$this->apellido}','{$this->direccion}','{$this->correo}','{$this->telefono}')";

			if(!$this->conectar->consulta($sql))
			{
				echo "No se pudo Incluir al Cliente";
			}
			else
			{
				return $this->conectar;
				mysqli_close($this->conectar);
			}

			}
		}

		function consultar_cliente()
		{
			$sql=" SELECT * FROM cliente WHERE cedula = '{$this->cedula}' AND estado = 'ACTIVO'";
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

		public function consulta_cliente()
		{
			$sql=" SELECT cedula, nombre, apellido, direccion, correo, telefono FROM cliente WHERE estado = 'ACTIVO'";

			$resultado=$this->conectar->consulta($sql);

			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran cliente";
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
			$sql=" UPDATE cliente SET nombre = '{$this->nombre}', apellido = '{$this->apellido}', direccion = '{$this->direccion}', correo = '{$this->correo}', telefono = '{$this->telefono}' WHERE cedula = '{$this->cedula}'";

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
		$sql=" UPDATE cliente SET estado = 'INACTIVO' WHERE cedula = '{$this->cedula}'";
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

		function consultarclientes_rep()
		{
			$sql="SELECT * FROM cliente WHERE estado = 'ACTIVO' ORDER BY NOMBRE ASC";
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
					$datos[]=$fila;
				}
				return 	$datos;
				mysqli_close($this->conectar);
			}
		}

		function consultarclientes()
		{
			$sql="SELECT * FROM cliente ORDER BY nombre ASC";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num==0)
			{
				echo "No se encuentran cliente";
			}
			else
			{
				while($fila = mysqli_fetch_assoc($resultado))
				{
		    		//echo $fila['foo'];
					$enc=1;
					$datos["data"][]=$fila;
				}
				$json=json_encode($datos);
				return 	$json;
				//mysqli_close($this->conectar);
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

		function set_cedula($cedula)
		{
			$this->cedula=$cedula;
		}

		function get_cedula()
		{
			return $this->cedula;
		}

		function set_nombre($nombre)
		{
			$this->nombre=$nombre;
		}

		function get_nombre()
		{
			return $this->nombre;
		}

		function set_apellido($apellido)
		{
			$this->apellido=$apellido;
		}

		function get_apellido()
		{
			return $this->apellido;
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
