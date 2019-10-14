<?php 

	class conexion
	{
		private $host;
		private $user;
		private $pw;
		private $db;
		private $conectar;

		function __construct()
		{
			$this->host="localhost";
			$this->user="root";
			$this->pw="";
			$this->db="eddibd";

			$this->conectar=mysqli_connect($this->host,$this->user,$this->pw,$this->db);
			if(!$this->conectar)
			{
				echo "<h1>Error en la Conexión</h1>";
			}
			else
			{
				return $this->conectar;
			}
		}

		function consulta($sql)
		{
			$consulta= mysqli_query($this->conectar,$sql);
			return $consulta;
		}
	}
?>