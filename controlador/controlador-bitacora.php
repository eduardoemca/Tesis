<?php
		
	require_once("../modelo/modelo-cliente.php");
	require_once("../modelo/modelo-bitacora.php");

	class client
	{
		private $conect;
		
		function __construct()
		{
			$this->cliente= new cliente();
			$this->bitacora= new bitacora();
		}

		function consulta_cliente()
		{
			$datos=$this->cliente->consulta_cliente();
			return $datos;
		}
		function incluir_detallebitacora()
		{
			$datos=$this->bitacora->consulta_cliente();
			return $datos;
		}
	}
?>
