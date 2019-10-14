<?php 
	
	require_once("../modelo/modelo-orden-compra.php");

	$objetoorden= new orden();

	class control_orden
	{
		private $conectar;

		function __construct()
		{
			$this->orden= new orden();
		}

		function generar()
		{
			$datos=$this->orden->codigo_orden();	

			return $datos;
		}

		function consulta_producto()
		{
			$datos=$this->orden->consulta_producto();
			return $datos;
		}
	}

	if(isset($_POST['Agregar']))
	{
		$objetoorden->set_codigo($_POST['codigo-orden']);

		if ($objetoorden->incluir()) 
		{
			echo "Orden Registrada exitosamente";
		}
	}

	if (isset($_POST['Consultar']))
	{
		$objetoorden->set_identificacion($_POST['identificacion-proveedor']);
		$json=$objetoorden->consultar_proveedor();
		if ($json!=null)
		{
			echo $json;
		}
	}

?>