<?php 

	require_once("../modelo/modelo-compra.php");

	class control_compra
	{
		private $conectar;

		function __construct()
		{
			$this->compra = new compra();
		}

		function consultar_orden()
		{
			$datos = $this->compra->consultar_orden();
			return $datos;
		}

		function consultar_ordenes_registradas()
		{
			$datos = $this->compra->consultar_ordenes_registradas();
			return $datos;
		}
	}
	
	$objetocompra= new compra();

	if (isset($_POST['Agregar'])) 
	{
		$objetocompra->set_codigo_compra($_POST['codigo-compra']);
		$objetocompra->set_codigo_orden($_POST['codigo-orden']);
		$objetocompra->set_identificacion($_POST['identificacion-proveedor']);

		if ($objetocompra->incluir()) 
		{
			echo "Compra Registrada exitosamente";
		}
	}

	if (isset($_POST['Consultar'])) 
	{
		$objetocompra->set_codigo_orden($_POST['codigo-orden']);
		$json= $objetocompra->consulta_orden_vista_compra();
		if ($json!= null) 
		{
			echo $json;
		}
	}

	if (isset($_POST['Consultar-Generada'])) 
	{
		$objetocompra->set_codigo_orden($_POST['codigo-orden']);
		$objetocompra->set_estado($_POST['estado-orden']);
		$json= $objetocompra->consultar_orden_compra_generada();
		if ($json!= null) 
		{
			echo $json;
		}
	}

	if (isset($_POST['Consultar-Pendiente'])) 
	{
		$objetocompra->set_codigo_orden($_POST['codigo-orden']);
		$objetocompra->set_estado($_POST['estado-orden']);
		$json= $objetocompra->consultar_orden_compra_pendiente();
		if ($json!= null) 
		{
			echo $json;
		}
	}

	if (isset($_POST['Cerrar-orden'])) 
	{
		$objetocompra->set_codigo_orden($_POST['codigo-orden']);
		$objetocompra->set_estado($_POST['estado-orden']);

		if ($objetocompra->cerrar_orden_generada())
		{
			echo "Orden Cerrada exitosamente";
		}
	}

	if (isset($_POST['Cerrar-orden-compra-registrada'])) 
	{
		$objetocompra->set_codigo_orden($_POST['codigo-orden']);
		$objetocompra->set_estado($_POST['estado-orden']);
		$objetocompra->set_identificacion($_POST['codigo-proveedor']);

		if ($objetocompra->cerrar_orden_pendiente())
		{
			echo "Orden Cerrada exitosamente";
		}
	}
?>