<?php 


	class bitacora
	{
		private $modulo;
		private $movimiento;
		private $session;
		private $usuario;
		private $conectar;
	
		function __construct()
		{
			$this->conectar= new conexion();
		}

		function incluir_detallebitacora()
		{
			$sql=" SELECT * FROM bitacora WHERE session_id = '{$this->session}'";
			$resultado=$this->conectar->consulta($sql);
			$num = mysqli_num_rows($resultado);

			if($num!=0)
			{
				$sql=" INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('{$this->session}','{$this->usuario}','{$this->movimiento}','{$this->modulo}',now())";

				if(!$this->conectar->consulta($sql))
				{
					echo "No se pudo Incluir el movimiento en el detalle bitacora";
				}
				else
				{
					return $this->conectar;
					mysqli_close($this->conectar);
				}
			}
			else
			{
				echo "error no se encuentra este usuario";
			}
		}

		function consultar_bitacora()
		{
			$sql=" SELECT * FROM bitacora WHERE session_id = '{$this->codigo}'";
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

		function set_movimiento($movimiento)
		{
			$this->movimiento=$movimiento;
		}

		function get_movimiento()
		{
			return $this->movimiento;
		}
		
		function set_modulo($modulo)
		{
			$this->modulo=$modulo;
		}

		function get_modulo()
		{
			return $this->modulo;
		}

		function set_usuario($usuario)
		{
			$this->usuario=$usuario;
		}

		function get_usuario()
		{
			return $this->usuario;
		}

		function set_session($session)
		{
			$this->session=$session;
		}

		function get_session()
		{
			return $this->session;
		}
	}
?>